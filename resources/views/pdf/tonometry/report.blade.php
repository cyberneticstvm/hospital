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
        <thead><tr><th text-align="center" colspan="4">TONOMETRY REPORT</th></tr></thead>
        <tbody>
            <tr>
                <td>Patient Name / ID / MR.ID</td>
                <td>{{ $patient->patient_name }} / {{ $patient->patient_id }} / {{ $tonometry->medical_record_id }}</td>
                <td>Date</td><td>{{ date('d/M/Y', strtotime($tonometry->created_at)) }}</td>
            </tr>
            <tr><td>Address</td><td colspan="3">{{ $patient->address }}</td></tr>
        </tbody>
    </table>
    <br />
    <table class="bordered" width="50%" cellspacing="0" cellpadding="0" style="margin:0 auto;">
        <tbody>
            <tr>
                <td></td>
                <td>NCT</td>
                <td>AT</td>                                                                               
            </tr>
            <tr>
                <td>OD</td>
                <td>{{ $tonometry->nct_od }} {{ ($tonometry->nct_od) ? 'mmHg' : '' }}</td>
                <td>{{ $tonometry->at_od }} {{ ($tonometry->at_od) ? 'mmHg' : '' }}</td>                                                                                
            </tr>
            <tr>
                <td>OS</td>
                <td>{{ $tonometry->nct_os }} {{ ($tonometry->nct_os) ? 'mmHg' : '' }}</td>
                <td>{{ $tonometry->at_os }} {{ ($tonometry->at_os) ? 'mmHg' : '' }}</td>                                                                                
            </tr>
            <tr>
                <td>TIME</td>
                <td>{{ $tonometry->nct_time }}</td>
                <td>{{ $tonometry->at_time }}</td>                                                                                
            </tr>
        </tbody>
    </table>
</body>
</html>