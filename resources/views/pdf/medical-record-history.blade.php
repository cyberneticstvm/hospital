<!DOCTYPE html>
<html>
<head>
    <title>Devi Eye Clinic & Opticians</title>
    <style>
        .text-right{
            text-align: right;
        }
        hr{
            border-top: 1px dashed red;
            border-bottom: 0px;
        }
        .bold{
            font-weight: bold;
            font-size: 14px;
            color: #696969;
        }
    </style>
</head>
<body>
<center>
    <img src="./images/assets/Devi-Logo-Transparent.jpg" width="15%"/><br/>
</center>
<br/>
<center><h5>PATIENT MEDICAL HISTORY</h5></center>
<table width="100%">
    <tr><td>Patient Name: {{ strtoupper($patient->patient_name) }}</td><td>Patient ID: {{ $patient->patient_id }}</td><td class="text-right">Age / Sex: {{ $patient->age }} / {{ strtoupper($patient->gender) }}</td></tr>
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
        <table width="100%">
            <tr><td>Date: {{ ($record->created_at) ? date('d/M/Y', strtotime($record->created_at)) : '' }}</td><td class="text-right">Medical Record Number: {{ $record->mrn }}</td></tr>
            <tr><td>Doctor Name: {{ $doctor->doctor_name }} ({{ $department->department_name }})</td><td class="text-right">Branch: {{ $branch->branch_name }}</td></tr>
        </table>
        <p class="bold">1) Symptoms / Notes (Front Desk)</p>
        {{ $reference->symptoms }} / {{ $reference->notes }}
        <br />
        <p class="bold">2) Symptoms (Consultation)</p>
        @foreach($symptoms as $sympt)
            {{ $sympt->symptom_name }}, 
        @endforeach
        <br />
        <p class="bold">3) Patient Complaints</p>
        {{ $record->patient_complaints }}
        <br />
        <p class="bold">4) Diagnosis</p>
        @foreach($diagnosis as $diag)
            {{ $diag->diagnosis_name }}, 
        @endforeach
        <br />
        <p class="bold">5) Doctor Findings</p>
        {{ $record->doctor_findings }}
        <br />
        <p class="bold">6) Doctor Recommondations</p>
        {{ $record->doctor_recommondations }}
        <br />
        <p class="bold">7) Medicine / Lab Advise</p>
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
        <p class="bold">8) Medicine Notes / List</p>
        {{ $record->medicine_list }}
        <br />
        <p class="bold">9) Other Details</p>
        <table width="100%" class="no-border">
            <tr><th>Admission Advised: </th><th>Surgery Advised: </th><th>Review Date: </th></tr>
            <tr><th>{{ ($record->is_admission == 0) ? 'No' : 'Yes' }}</th><th>{{ ($record->is_surgery == 0) ? 'No' : 'Yes' }}</th><th>{{ ($record->review_date) ? date('d/M/Y', strtotime($record->review_date)) : '' }}</th></tr>
        </table>
        <br />
        <hr>
        <br />
        @endforeach
    @endif
@endforeach
</body>
</html>