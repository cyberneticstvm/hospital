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

        .bordered th,
        .bordered td {
            border: 1px solid #000;
        }
    </style>
</head>

<body>
    <center>
        <img src="./images/assets/Devi-Logo-Transparent.jpg" height='75' width='115' />
        <p>{{ $branch->branch_name }}, {{ $branch->address }}, {{ $branch->contact_number }}</p>
        <h3>Lab Test Request</h3>
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
            <td>Address</td>
            <td colspan="2">{{ $patient->address }}</td>
            <td>Doctor Name</td>
            <td colspan="2">{{ $doctor->doctor_name }}</td>
        </tr>
        <tr>
            <td colspan="4">Date: {{ $labs->first()->created_at->format('d M, Y') }}</td>
        </tr>
    </table>
    <br>
    <table width="100%" border="1" cellpadding='1' cellspacing='0' class="text-large">
        <tr>
            <th width="10%">SL No</th>
            <th width="70%">Test</th>
            <th>Date</th>
        </tr>
        @php $c = 1; @endphp
        @foreach($labs as $lab)
        @php
        $test = DB::table('lab_types')->find($lab->lab_type_id);
        @endphp
        <tr>
            <td>{{ $c++ }}</th>
            <td>{{ $test->lab_type_name }}</td>
            <td>{{ date('d/M/Y', strtotime($lab->created_at)) }}</td>
        </tr>
        @endforeach
    </table>
    <p class='text-right'>Printed On: {{ date('d/M/Y H:i:A') }}</p>
</body>

</html>