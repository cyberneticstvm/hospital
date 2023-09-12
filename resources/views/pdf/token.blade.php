<!DOCTYPE html>
<html>
<head>
    <title>Devi Eye Clinic & Opticians</title>
    <style>
        .text-center{
            text-aling: center;
        }
        .bordered{
            border: 1px solid #000;
        }
        .title{
            font-size: 1.5rem;
        }
        table>tbody>tr>td{
            padding-left: 25px;
        }
        thead{
            border-bottom: 1px solid #000;
        }
        td{
            padding: 5px;
        }
    </style>
</head>
<body>
    <table width="100%" cellpadding='0' cellspacing='0' class='bordered' >
        <thead>
            <tr rowspan='2'><th colspan='4' class='text-center title'>Devi Eye Clinic & Opticians</th></tr>
            <tr><th colspan='4' class='text-center'>{{ $branch->address}}, Phone: {{ $branch->contact_number }}</th></tr>
        </thead>
        <tbody>
            <tr>
                <td>Token No / Mob:</td><td>{{ $reference->token }} / {{ $patient->mobile_number }}</td>
                <td>MR.ID</td><td>{{ $reference->medical_record_id }}</td>
            </tr>
            <tr>
                <td>Patient Name</td><td>{{ $patient->patient_name }}</td>
                <td>Address</td><td>{{ $patient->address }}</td>
            </tr>
            <tr>
                <td>Gender:</td><td>{{ $patient->gender }}</td>
                <td>Age</td><td>{{ $patient->age }} {{ ($patient->new_born_baby == 1) ? 'Months' : '' }}</td>
            </tr>
            <tr>
                <td>Doctor</td><td>{{ $doctor->doctor_name }}</td>
                <td>Date</td><td>{{ date('d/M/Y h:i a', strtotime($reference->created_at)) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>