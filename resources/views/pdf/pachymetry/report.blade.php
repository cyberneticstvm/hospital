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
        <thead><tr><th text-align="center" colspan="4">PACHYMETRY REPORT</th></tr></thead>
        <tbody>
            <tr>
                <td>Patient Name / ID / MR.ID</td>
                <td>{{ $patient->patient_name }} / {{ $patient->patient_id }} / {{ $pachymetry->medical_record_id }}</td>
                <td>Date</td><td>{{ date('d/M/Y', strtotime($pachymetry->created_at)) }}</td>
            </tr>
        </tbody>
    </table>
    <br />
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td width="50%">
                    <img src="./storage/{{ $pachymetry->img1 }}" width="100%" /><br/>
                    {{ $pachymetry->img1_value }}        
                </td>
                <td>
                <img src="./storage/{{ $pachymetry->img2 }}" width="100%" /><br/>
                    {{ $pachymetry->img2_value }}
                </td>
            </tr>
            <tr>
                <td width="50%">
                    <img src="./storage/{{ $pachymetry->img3 }}" width="100%" /><br/>
                    {{ $pachymetry->img3_value }}        
                </td>
                <td>
                <img src="./storage/{{ $pachymetry->img4 }}" width="100%" /><br/>
                    {{ $pachymetry->img4_value }}
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>