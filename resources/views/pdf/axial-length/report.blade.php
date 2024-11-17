<!DOCTYPE html>
<html>

<head>
    <title>Devi Eye Clinic & Opticians</title>
    <style>
        table {
            border: 1px solid #e6e6e6;
            font-size: 12px;
        }

        thead {
            border-bottom: 1px solid #e6e6e6;
        }

        table thead th,
        table tbody td,
        table tfoot td {
            padding: 5px;
        }

        .bordered td {
            border: 1px solid #e6e6e6;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .axial {
            margin: 0 auto;
            background-image: url('./images/assets/axial-length.png');
            background-repeat: no-repeat;
            height: 50%;
            width: 50%;
        }
    </style>
</head>

<body>
    <center>
        <img src="./images/assets/Devi-Logo-Transparent.jpg" width="15%" /><br />
        {{ $branch?->address }}, Phone:
        {{ $branch?->contact_number }}
    </center>
    <br />
    <table width="100%">
        <thead>
            <tr>
                <th text-align="center" colspan="4">AXIAL LENGTH REPORT</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Patient Name / ID / MR.ID</td>
                <td>{{ $patient->patient_name }} / {{ $patient->patient_id }} / {{ $ax->medical_record_id }}</td>
                <td>Date</td>
                <td>{{ date('d/M/Y', strtotime($ax->created_at)) }}</td>
            </tr>
            <tr>
                <td>Address</td>
                <td colspan="3">{{ $patient->address }}</td>
            </tr>
        </tbody>
    </table>
    <br />
    <h3>Axial-Length</h3>
    <table class="bordered" width="100%" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th>AXL</th>
                <th>ACD</th>
                <th>LENS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">{{ $ax->axl }}</td>
                <td class="text-center">{{ $ax->acd }}</td>
                <td class="text-center">{{ $ax->lens }}</td>
            </tr>
        </tbody>
    </table>
    <br />
    <center>
        <div class="axial">
            <div style="height:25px; text-align: left; left: 35; top:123; position:relative;">{{ $ax->acd }}</div>
            <div style="height:25px; text-align: left; left: 75; top:105; position: relative;">{{ $ax->lens }}</div>
            <div style="height:25px; text-align: left; left: 150; top:85; position: relative;">{{ ($ax->acd && $ax->lens && $ax->axl) ? ($ax->acd+$ax->lens)-$ax->axl : '' }}</div>
            <div style="height:25px; text-align: left; left: 110; top:180; position: relative;">{{ $ax->axl }}</div>
        </div>
    </center>
</body>

</html>