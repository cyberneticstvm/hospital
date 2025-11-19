<!DOCTYPE html>
<html>

<head>
    <title>Devi Eye Clinic & Opticians</title>
    <style>
        .text-blue {
            color: blue;
        }

        .text-medium {
            font-size: 12px;
        }

        .text-small {
            font-size: 10px;
        }

        .text-large {
            font-size: 15px;
        }

        table tr td {
            height: 25px;
            padding-left: 5px;
        }

        .text-right {
            text-align: right;
        }

        .bordered th,
        .bordered td {
            border: 1px solid #000;
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
        <p>{{ $branch->branch_name }}, {{ $branch->address }}, {{ $branch->contact_number }}</p>
        <h3>Lab Test Report</h3>
    </center>
    <table width="100%" cellpadding='0' cellspacing='0' class="text-large bordered">
        <tr>
            <td>MR ID</td>
            <td>{{ $mrecord->id }}</td>
            <td>Patient ID</td>
            <td>{{ $patient->patient_id }}</td>
            <td>Name/Age/Gender</td>
            <td>{{ $patient->patient_name }} / {{ $patient->age }} / {{ $patient->gender }}</td>
        </tr>
        <tr>
            <td>Patient Address</td>
            <td colspan="5">{{ $patient->address }}</td>
        </tr>
    </table>
    <br>
    @php $c = 1; @endphp
    @foreach($labs as $lab)
    @php
    $test = DB::table('lab_types')->find($lab->lab_type_id);
    $user = DB::table('users')->find($lab->updated_by);
    @endphp
    <p>{{ $c }}. {{ $test->lab_type_name }} - {{ $lab->lab_result }}, Lab Technician: {{ ($lab->tested_from == 1) ? $user->name : 'NA' }}, Report From: {{ ($lab->tested_from == 1) ? 'Own Laboratory' : 'Outside Laboratory' }}</p>
    @php $c++ @endphp
    @endforeach
    <p class='text-right'>Authorized Signatory</p><br>
    <p class='text-right'>Requested On: {{ date('d/M/Y', strtotime($labs->pluck('created_at')->first())) }}</p>
</body>

</html>