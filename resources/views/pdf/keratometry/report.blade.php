<!DOCTYPE html>
<html>
<head>
    <title>Devi Eye Clinic & Opticians</title>
    <style>
        table{
            border: 1px solid #e6e6e6;
            font-size: 12px;
        }
        thead{
            border-bottom: 1px solid #e6e6e6;
        }
        table thead th, table tbody td, table tfoot td{
            padding: 5px;
        }
        .bordered td{
            border: 1px solid #e6e6e6;
        }
        .text-right{
            text-align: right;
        }
    </style>
</head>
<body>
    <center>
        <img src="./images/assets/Devi-Logo-Transparent.jpg" width="15%"/><br/>
            {{ $branch->address }}, Phone:
            {{ $branch->contact_number }}
    </center>
    <br/>
    <table width="100%">
        <thead><tr><th text-align="center" colspan="4">KERATOMETRY REPORT</th></tr></thead>
        <tbody>
            <tr>
                <td>Patient Name / ID / MR.ID</td>
                <td>{{ $patient->patient_name }} / {{ $patient->patient_id }} / {{ $keratometry->medical_record_id }}</td>
                <td>Date</td><td>{{ date('d/M/Y', strtotime($keratometry->created_at)) }}</td>
            </tr>
            <tr><td>Address</td><td colspan="3">{{ $patient->address }}</td></tr>
        </tbody>
    </table>
    <br />
    <table class="bordered" width="100%" cellspacing="0" cellpadding="0">
        <thead><tr><th></th><th>K1(A)</th><th>AXIS</th><th>K2(A)</th><th>AXIS</th><th>K1(M)</th><th>AXIS</th><th>K2(M)</th><th>AXIS</th></tr></thead>
        <tbody>
            <tr>
                <td>OD</td><td>{{ $keratometry->k1_od_auto }}</td><td>{{ $keratometry->k1_od_axis_a }}</td><td>{{ $keratometry->k2_od_auto }}</td><td>{{ $keratometry->k2_od_axis_a }}</td><td>{{ $keratometry->k1_od_manual }}</td><td>{{ $keratometry->k1_od_axis_m }}</td><td>{{ $keratometry->k2_od_manual }}</td><td>{{ $keratometry->k2_od_axis_m }}</td>
            </tr>
            <tr>
                <td>OS</td><td>{{ $keratometry->k1_os_auto }}</td><td>{{ $keratometry->k1_os_axis_a }}</td><td>{{ $keratometry->k2_os_auto }}</td><td>{{ $keratometry->k2_os_axis_a }}</td><td>{{ $keratometry->k1_os_manual }}</td><td>{{ $keratometry->k1_os_axis_m }}</td><td>{{ $keratometry->k2_os_manual }}</td><td>{{ $keratometry->k2_os_axis_m }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>