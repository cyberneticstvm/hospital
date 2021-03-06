<!DOCTYPE html>
<html>
<head>
    <title>Devi Eye Clinic & Opticians</title>
    <style>
        .text-blue{
            color: blue;
        }
        table{
            border-bottom: 1px solid #000;
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
    </style>
</head>
<body>
    <table width="100%" cellpadding='0' cellspacing='0' class="text-large">
        <tr>
            <td width='20%'>MR.ID</td><td width='60%'>{{ $reference->medical_record_id }}</td>
            <td rowspan='3'>
                <img src="./images/assets/Devi-Logo-Transparent.jpg" height='75' width='115'/>
            </td>
        </tr>
        <tr>
            <td>Patient Name</td><td>{{ strtoupper($patient->patient_name) }}</td>
        </tr>
        <tr>
            <td>Patient ID</td><td>{{ $patient->patient_id }}</td>
        </tr>
        <tr>
            <td width='20%'>Age</td><td>{{ $patient->age }} / {{ $patient->gender }}</td><td class='text-blue'>{{ strtoupper($doctor->doctor_name) }}</td>
        </tr>
        <tr>
            <td>Address</td><td>{{ strtoupper($patient->address) }}</td><td class='text-blue'>{{ strtoupper($doctor->designation) }}</td>
        </tr>
    </table>
    <p class='text-right'>Date: {{ date('d/M/Y H:i:A') }}</p>
    <pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre />
    <pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre />
    <img src="data:image/png;base64, {!! $qrcode !!}">
    <hr>
    <center>
        <p class='text-medium'>VARKALA | PARIPPALLY | POTHENCODE | PARAVOOR | CHIRAYINKEEZHU | KADAKKAL | ATTINGAL | OONNINMOODU | EDAVA | NADAYARA</p>
        <p class="text-small">Ph: +91 9388611622</p>
    </center>
</body>
</html>