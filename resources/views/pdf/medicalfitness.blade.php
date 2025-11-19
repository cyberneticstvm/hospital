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

        .text-large {
            font-size: 15px;
        }

        .text-end {
            text-align: right;
        }

        .desc {
            line-height: 25px;
        }
    </style>
</head>

<body>
    <table width="100%" cellpadding='0' cellspacing='0' class="text-large">
        <tr>
            <td width='20%'>MR.ID</td>
            <td width='50%'>{{ $mfit->medical_record_id }}</td>
            <td rowspan='3'>
                @if(Helper::subdomain() == 'emrsas')
                <img src="./images/assets/devi-sas-logo.png" width="35%" /><br />
                @else
                <img src="./images/assets/Devi-Logo-Transparent.jpg" width="15%" /><br />
                @endif
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
            <td>{{ $patient->age }} / {{ $patient->gender }}</td>
            <td class='text-blue'>{{ strtoupper($doctor->doctor_name) }}</td>
        </tr>
        <tr>
            <td>Address</td>
            <td>{{ strtoupper($patient->address) }}</td>
            <td class='text-blue'>{{ strtoupper($doctor->designation) }}</td>
        </tr>
    </table><br>
    <center>
        <h3>Medical Fitness Request</h3>
    </center>
    <p class='text-end'>Date: {{ date('d/M/Y', strtotime($mfit->created_at)) }}</p>
    <br><br>
    <p>To,</p>
    <p>{{ $mfit->head }}</p>
    <p class='desc'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $madvice->fitness_advice }}</p>
    <p>{!! nl2br($mfit->notes) !!}</p>
    <div class="text-end">Thanking You</div><br><br>
    <div class="text-end">{{ $doctor->doctor_name }}</div>
    <div class="text-end">{{ $doctor->designation }}</div>
    <div class="text-end">Reg No: {{ $doctor->reg_no }}</div>
    <!--<img src="data:image/png;base64, {!! $qrcode !!}">
    <hr>-->
</body>

</html>