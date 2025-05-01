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

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <center>
        <img src="./images/assets/Devi-Logo-Transparent.jpg" height='100' width='115' />
        <p>{{ $ack?->branch?->branch_name }}, {{ $ack?->branch?->address }}, {{ $ack?->branch?->contact_number }}</p>
        <p>DL Number: RLF21KL2022000540, RLF20KL2022000543</p>
        <h3>Patient Acknowledgement</h3>
    </center>
    <table width="100%" cellpadding='0' cellspacing='0' class="text-large bordered">
        <tr>
            <td>MR ID</td>
            <td>{{ $ack->medical_record_id }}</td>
            <td>Patient ID</td>
            <td>{{ $ack->patient->patient_id }}</td>
            <td>Name/Age/Gender</td>
            <td>{{ $ack->patient->patient_name }} / {{ $ack->patient->age }} / {{ $ack->patient->gender }}</td>
        </tr>
        <tr>
            <td>Address</td>
            <td colspan="3">{{ $ack->patient->address }}</td>
            <td>Ack. Number / Date</td>
            <td>{{ $ack->id }} / {{ $ack->created_at->format('d, M Y h:i a') }}</td>
        </tr>
        <!--<tr>
            <td>Doctor Name</td>
            <td colspan="3">{{ $doctor->doctor_name }}</td>
            <td>Surgeon Name</td>
            <td>{{ $surgery?->surgeondetails?->doctor_name }}</td>
        </tr>-->
    </table>
    <br />
    <strong>Reports</strong>
    <br />
    <br />
    <table width="100%" border="1" cellpadding='1' cellspacing='0' class="text-large">
        <tr>
            <th width="10%">SL No</th>
            <th width="90%">Report</th>
        </tr>
        @php $c = 1; @endphp
        @forelse($procs as $key => $item)
        @if(in_array($item->id, $ackproc))
        <tr>
            <td>{{ $c++ }}</th>
            <td>{{ $item->name }}</td>
        </tr>
        @endif
        @empty
        @endforelse
    </table>
    <p class="">Notes: {{ $ack->notes }}</p>
    <br />
    <p class="">Received by Patient / Bystander / Authorized Person</p>
    <p class='text-right'>Hospital Staff Signature</p>
    <p class='text-right'>Printed at: {{ date('d/M/Y H:i:A') }}</p>
</body>

</html>