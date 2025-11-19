<!DOCTYPE html>
<html>

<head>
    <title>Devi Eye Clinic & Opticians</title>
    <style>
        .text-right {
            text-align: right;
        }

        hr {
            border-top: 1px dashed red;
            border-bottom: 0px;
        }

        .bold {
            font-weight: bold;
            font-size: 14px;
            color: #696969;
        }

        .fw-bold {
            font-weight: bold;
        }

        .bordered td,
        .bordered th {
            border: 1px solid #e6e6e6;
            padding: 5px;
        }
    </style>
</head>

<body>
    <center>
        @if(Helper::subdomain() == 'emrsas')
        <img src="./images/assets/devi-sas-logo.png" width="35%" /><br />
        @else
        <img src="./images/assets/Devi-Logo-Transparent.jpg" width="15%" /><br />
        @endif
    </center>
    <br />
    <center>
        <h5>PATIENT MEDICAL HISTORY</h5>
    </center>
    <table width="100%">
        <tr>
            <td>Patient Name: {{ strtoupper($patient->patient_name) }}</td>
            <td>Patient ID: {{ $patient->patient_id }}</td>
            <td class="text-right">Age / Sex: {{ $patient->age }} {{ ($patient->new_born_baby == 1) ? 'Months' : '' }} / {{ strtoupper($patient->gender) }}</td>
        </tr>
    </table>
    <hr>
    @foreach($references as $reference)
    @php
    $mrecords = DB::table('patient_medical_records')->where('mrn', $reference->id)->get();
    $branch = DB::table('branches')->find($reference->branch);
    $department = DB::table('departments')->find($reference->department_id);
    $doctor = DB::table('doctors')->find($reference->doctor_id);
    @endphp
    @if($mrecords)
    @foreach($mrecords as $record)
    @php
    $sympt = explode(',', $record->symptoms);
    $diag = explode(',', $record->diagnosis);
    $symptoms = DB::table('symptoms')->whereIn('id', $sympt)->get();
    $diagnosis = DB::table('diagnosis')->whereIn('id', $diag)->get();
    $medicines = DB::table('patient_medicine_records as m')->leftJoin('products as p', 'm.medicine', '=', 'p.id')->select('p.product_name', 'm.dosage', 'm.notes', 'm.qty')->where('m.medical_record_id', $record->id)->get();
    @endphp
    <table width="100%" class="fw-bold">
        <tr>
            <td>Date: {{ ($record->created_at) ? date('d/M/Y', strtotime($record->created_at)) : '' }}</td>
            <td class="text-right">Medical Record Number: {{ $record->id }}</td>
        </tr>
        <tr>
            <td>Doctor Name: {{ $doctor->doctor_name }} ({{ $department->department_name }})</td>
            <td class="text-right">Branch: {{ $branch->branch_name }}</td>
        </tr>
    </table>
    <p class="bold">1) Notes</p>
    {{ $reference->notes }}
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
    <table width="100%" cellspacing="0" cellpadding="0" class="bordered">
        <thead>
            <th>SL No.</th>
            <th>Medicine Name</th>
            <th>Dosage</th>
            <th>Qty</th>
            <th>Notes</th>
        </thead>
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
        <tr>
            <td>Admission Advised: </td>
            <td>Surgery Advised: </td>
            <td>Review Date: </td>
        </tr>
        <tr>
            <td>{{ ($record->is_patient_admission == 'sel') ? '' : 'Yes' }}</td>
            <td>{{ ($record->is_patient_surgery == 'sel') ? '' : 'Yes' }}</td>
            <td>{{ ($record->review_date) ? date('d/M/Y', strtotime($record->review_date)) : '' }}</td>
        </tr>
    </table>
    <br />
    <hr>
    <br />
    @endforeach
    @endif
    @endforeach
</body>

</html>