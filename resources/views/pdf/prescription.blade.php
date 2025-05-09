<!DOCTYPE html>
<html>

<head>
    <title>Devi Eye Clinic & Opticians</title>
    <style>
        .text-blue {
            color: blue;
        }

        table {
            border-bottom: 1px solid #000;
        }

        .text-medium {
            font-size: 12px;
        }

        .text-small {
            font-size: 10px;
        }

        .text-medium-large {
            font-size: 14px;
        }

        .text-large {
            font-size: 15px;
        }

        .text-right {
            text-align: right !important;
        }

        .text-gray {
            color: gray !important;
        }
    </style>
</head>

<body>
    <table width="100%" cellpadding='0' cellspacing='0' class="text-large">
        <tr>
            <td width='20%'>MR.ID / Token</td>
            <td width='50%'>{{ $reference->medical_record_id }} / {{ $reference->token }}</td>
            <td rowspan='3'>
                <img src="./images/assets/Devi-Logo-Transparent.jpg" width='40%' />
            </td>
        </tr>
        <tr>
            <td>Patient Name</td>
            <td>{{ strtoupper($patient->patient_name) }}</td>
        </tr>
        <tr>
            <td>Patient ID</td>
            <td>{{ $patient->patient_id }}</td>
        </tr>
        <tr>
            <td width='20%'>Age</td>
            <td>{{ $patient->patientAge() }} {{ ($patient->new_born_baby == 1) ? 'Months' : '' }} / {{ $patient->gender }}</td>
            <td class='text-blue text-medium-large'>{{ strtoupper($doctor->doctor_name) }}</td>
        </tr>
        <tr>
            <td>Address</td>
            <td class="text-medium">{{ strtoupper($patient->address) }}</td>
            <td class='text-blue text-medium'>{{ strtoupper($doctor->designation) }}<br />{{ strtoupper($doctor->additional_qualification) }}</td>
        </tr>
    </table>
    <p class='text-right'>Date: {{ date('d/M/Y h:i a') }}</p>
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <img src="data:image/png;base64, {!! $qrcode !!}">
    <p class="text-small">To book an appointment, scan this QR code.</p>
    <hr>
    <center>
        <p class='text-medium'>VARKALA | PARIPPALLY | POTHENCODE | PARAVOOR | CHIRAYINKEEZHU | KADAKKAL | ATTINGAL | OONNINMOODU | EDAVA | NADAYARA</p>
        <p>Ph: {{ $branch->contact_number }}, +91 9388611622</p>
    </center>
</body>

</html>