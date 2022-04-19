<!DOCTYPE html>
<html>
<head>
    <title>Devi Eye Clinic & Opticians</title>
    <style>
        table{
            border: 1px solid #e6e6e6;
            font-size: 12px;
        }
        thead{
            border-bottom: 1px solid #e6e6e6;
        }
        table thead th, table tbody td{
            padding: 5px;
        }
        tbody td{
            border: 1px solid #e6e6e6;
        }
        .text-right{
            text-align: right;
        }
        .bold{
            font-weight: bold;
            font-size: 14px;
            color: #696969;
        }
        table.no-border{
            border: 0px;
        }
    </style>
</head>
<body>
    <center>
        <img src="./images/assets/Devi-Logo-Transparent.jpg" width="15%"/><br/>
            {{ $branch->address }}, Phone:
            {{ $branch->contact_number }}
    </center>
    <br/>
    <center><h5>PATIENT MEDICAL RECORD</h5></center>
    <table width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td>Patient Name</td><td>{{ $patient->patient_name }}</td>
                <td>Patient ID</td><td>{{ $patient->patient_id }}</td>
                <td>AGE / SEX</td><td>{{ $patient->age }} / {{ $patient->gender }}</td>
            </tr>
            <tr>
                <td>Doctor Name</td><td>{{ $doctor->doctor_name }}</td>
                <td>Medical Record Number</td><td>{{ $record->id }}</td>
                <td>Date</td><td>{{ ($record->created_at) ? date('d/M/Y', strtotime($record->created_at)) : '' }}</td>
            </tr>
        </tbody>
    </table>
    <p class="bold">1) Symptoms / Notes (Front Desk)</p>
    {{ $reference->symptoms }} / {{ $reference->notes }}
    <br />
    <p class="bold">2) Symptoms (Consultation)</p>
    @foreach($symptoms as $sympt)
        {{ $sympt->symptom_name }}, 
    @endforeach
    <br />
    <p class="bold">3) Diagnosis</p>
    @foreach($diagnosis as $diag)
        {{ $diag->diagnosis_name }}, 
    @endforeach
    <br />
    <p class="bold">4) Doctor Recommondations</p>
    {{ $record->doctor_recommondations }}
    <br />
    <p class="bold">5) Medicine / Lab Advise</p>
    <table width="100%" cellspacing="0" cellpadding="0">
        <thead><th>SL No.</th><th>Medicine Name</th><th>Dosage</th><th>Qty</th><th>Notes</th></thead>
        <tbody>
        @php $c = 1 @endphp
        @foreach($medicines as $medicine)
        <tr>
            <td>{{ $c++ }}</td>
            <td>{{ $medicine->product_name }}</td>
            <td>{{ $medicine->dosage }}</td>
            <td class="text-right">{{ $medicine->qty }}</td>
            <td>{{ $medicine->notes }}</td>            
        </tr>
        @endforeach
        </tbody>
    </table>
    <br />
    <p class="bold">6) Other Details</p>
    <table width="100%" class="no-border">
        <tr><th>Admission Advised: </th><th>Surgery Advised: </th><th>Review Date: </th></tr>
        <tr><th>{{ ($record->is_admission == 0) ? '' : 'Yes' }}</th><th>{{ ($record->is_surgery == 0) ? '' : 'Yes' }}</th><th>{{ ($record->review_date) ? date('d/M/Y', strtotime($record->review_date)) : '' }}</th></tr>
    </table>
</body>
</html>