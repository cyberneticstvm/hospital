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
    </style>
</head>
<body>
    <table width="100%" cellpadding='0' cellspacing='0' >
        <tr>
            <td width='15%'>MRN</td><td width='60%'>{{ $reference->id }}</td>
            <td rowspan='3'>
                <img src="./images/assets/devi-logo.jpg" height='50' width='75'/>
            </td>
        </tr>
        <tr>
            <td>Name</td><td>{{ strtoupper($patient->patient_name) }}</td>
        </tr>
        <tr>
            <td>Patient ID</td><td>{{ $patient->patient_id }}</td>
        </tr>
        <tr>
            <td width='15%'>Dob</td><td>{{ $patient->dob }} / {{ $patient->gender }}</td><td class='text-blue'>{{ strtoupper($doctor->doctor_name) }}</td>
        </tr>
        <tr>
            <td>Address</td><td>{{ strtoupper($patient->address) }}</td><td class='text-blue'>{{ strtoupper($doctor->designation) }}</td>
        </tr>
    </table>
    <pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre />
    <pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre />
    <img src="data:image/png;base64, {!! $qrcode !!}">
    <hr>
    <center>
        <p>VARKALA | PARIPPALLY | POTHENCODE | PARAVOOR | CHIRAYINKEEZHU | KADAKKAL | ATTINGAL | OONNINMOODU | EDAVA | NADAYARA</p>
        <p>Ph: +91 9388611622</p>
    </center>
</body>
</html>