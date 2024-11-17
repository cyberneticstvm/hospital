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
                <th text-align="center" colspan="4">A-SCAN REPORT</th>
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
                <td>{{ $ax->axl }}</td>
                <td>{{ $ax->acd }}</td>
                <td>{{ $ax->lens }}</td>
            </tr>
        </tbody>
    </table>
    <p>Procedures: {{ $procs->whereIn('id', $procedures->pluck('procedure'))->pluck('name')->implode(',') }}</p>
</body>

</html>