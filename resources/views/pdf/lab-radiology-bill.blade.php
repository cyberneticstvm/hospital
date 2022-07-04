<!DOCTYPE html>
<html>
<head>
    <title>Devi Eye Clinic & Opticians</title>
    <style>
        .text-blue{
            color: blue;
        }
        .text-medium{
            font-size: 12px;
        }
        .text-small{
            font-size: 10px;
        }
        .text-large{
            font-size: 15px;
        }
        table tr td{
            height: 25px;
            padding-left: 5px;
        }
        .text-right{
            text-align: right;
        }
    </style>
</head>
<body>
    <center>
    <img src="./images/assets/Devi-Logo-Transparent.jpg" height='75' width='115'/>
    <p>{{ $branch->branch_name }}, {{ $branch->address }}, {{ $branch->contact_number }}</p>
    </center>
    <table width="100%" border="0" cellpadding='0' cellspacing='0' class="text-large">
        <tr>
            <td>MR ID</td><td>{{ $mrecord->id }}</td>
            <td>Patient ID</td><td>{{ $patient->patient_id }}</td>
            <td>Patient Name</td><td>{{ $patient->patient_name }} / {{ $patient->age }} / {{ $patient->gender }}</td>
        </tr>
        <tr>
            <td>Doctor Name</td><td>{{ $doctor->doctor_name }}</td><td>Bill No</td><td>LABR/{{ $bno->bill_number }}</td>
        </tr>
    </table>
    <br>
    <table width="100%" border="1" cellpadding='1' cellspacing='0' class="text-large">
    <tr>
        <th width="10%">SL No</th><th width="50%">Test</th><th>Qty</th><th>Amount</th><th>Total</th>
    </tr>
    @php $c = 1; $tot = 0; @endphp;
    @foreach($labs as $lab)
    @php
    $test = DB::table('lab_types')->find($lab->lab_type_id);
    @endphp
    <tr>
        <td>{{ $c++ }}</th><td>{{ $test->lab_type_name }}</td><td class="text-right">1</td><td class="text-right">{{ $test->fee }}</td><td class="text-right">{{ $test->fee }}</td>
    </tr>
    @php $tot += $test->fee; @endphp
    @endforeach
    <tr><td colspan="4" class="text-right">Total</td><td class="text-right">{{ number_format($tot, 2) }}</td></tr>
    </table>
    <p class='text-right'>Printed On: {{ date('d/M/Y H:i:A') }}</p>
</body>
</html>