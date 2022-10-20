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
        .text-medium{
            font-size: 12px;
        }
        .text-small{
            font-size: 10px;
        }
        .text-large{
            font-size: 15px;
        }
        td,th{
            padding: 5px;
        }
        th{
            text-align: left;
        }
    </style>
</head>
<body>
    <center>
    <img src="./images/assets/Devi-Logo-Transparent.jpg" height='75' width='115'/>
    <p>BASIC VISION SCREENING REPORT</p>
    </center>
    <table width="100%" cellpadding='0' cellspacing='0' class="text-large">
        <tr>
            <td>Name / SL No: {{ strtoupper($camp->patient_name) }} / {{ $camp->id }}</td><td>Age/Gender: {{ $camp->age }}/{{ $camp->gender }}</td>
            <td>Date: {{ date('d/M/Y', strtotime($camp->camp_date)) }}</td>
        </tr>        
        <tr>
            <td>Further Investigation Required: {{ ($camp->treatment_required == 1) ? 'Yes' : 'No' }}</td>
            <td>Glasses Required: {{ ($camp->specs_required == 1) ? 'Yes' : 'No' }}</td>
            <td>Yearly Test: {{ ($camp->yearly_test_advised == 1) ? 'Yes' : 'No' }}</td>
        </tr>    
        <tr>            
            <td>Venue: {{ $camp->venue }}</td><td colspan="2">Standard/Address: {{ $camp->standard }}</td>
        </tr>      
    </table>
    <table width="100%" cellpadding='0' cellspacing='0' class="text-large">
        <thead><tr><th>Eye</th><th>Sph</th><th>Cyl</th><th>Axis</th><th>Add</th><th>VB</th><th>VA</th></tr></thead>
        <tbody>
            <tr><td>RE</td><td>{{ $camp->re_sph }}</td><td>{{ $camp->re_cyl }}</td><td>{{ $camp->re_axis }}</td><td>{{ $camp->re_add }}</td><td>{{ $camp->re_vb }}</td><td>{{ $camp->re_va }}</td></tr>
            <tr><td>LE</td><td>{{ $camp->le_sph }}</td><td>{{ $camp->le_cyl }}</td><td>{{ $camp->le_axis }}</td><td>{{ $camp->le_add }}</td><td>{{ $camp->le_vb }}</td><td>{{ $camp->le_va }}</td></tr>
        </tbody>
    </table>
    <center>
        <p class="text-large">Devi Eye Hospitals, Ph: +91 9388611622</p>
    </center>
</body>
</html>