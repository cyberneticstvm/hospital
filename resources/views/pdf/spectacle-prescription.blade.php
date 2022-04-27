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
        table thead th, table tbody td{
            padding: 5px;
        }
        .bordered td{
            border: 1px solid #e6e6e6;
        }
        .text-right{
            text-align: right;
        }
        .text-center{
            text-align: center;
        }
        .col{
            width: 50%;
            float: left;
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
        <thead><tr><th text-align="center" colspan="4">PRESCRIPTION</th></tr></thead>
        <tbody>
            <tr>
                <td>PATIENT NAME</td>
                <td>{{ $patient->patient_name }}</td>
                <td>AGE / SEX</td>
                <td>{{ $patient->age }} / {{ $patient->gender }}</td>
            </tr>
            <tr>
                <td>PATIENT ID</td>
                <td>{{ $patient->patient_id }}</td>
                <td>PRESCRIPTION NUMBER</td>
                <td>{{ $spectacle->id }}</td>
            </tr>
            <tr>
                <td>DOCTOR NAME</td>
                <td>{{ $doctor->doctor_name }}</td>
                <td>ENTRY DATE</td>
                <td>{{ ($spectacle->created_at) ? date('d/M/Y h:i:A', strtotime($spectacle->created_at)) : '' }}</td>
            </tr>
        </tbody>
    </table>
    <center><p>EYE GLASS PRESCRIPTION</p></center>
    <div class="row">
        <div class="col">
            <table width="99%" class="bordered" cellspacing="0" cellpadding="0">
                <tr><td>RIGHT</td><td>SPH</td><td>CYL</td><td>AXIS</td><td>VA</td><td>PRISM</td></tr>
                <tr><td>DIST.</td><td>{{ $spectacle->re_dist_sph }}</td><td>{{ $spectacle->re_dist_cyl }}</td><td>{{ $spectacle->re_dist_axis }}</td><td>{{ $spectacle->re_dist_va }}</td><td>{{ $spectacle->re_dist_prism }}</td></tr>
                <tr><td>INT.</td><td>{{ $spectacle->re_int_sph }}</td><td>{{ $spectacle->re_int_cyl }}</td><td>{{ $spectacle->re_int_axis }}</td><td>{{ $spectacle->re_int_va }}</td><td>{{ $spectacle->re_int_prism }}</td></tr>
                <tr><td>NEAR.</td><td>{{ $spectacle->re_near_sph }}</td><td>{{ $spectacle->re_near_cyl }}</td><td>{{ $spectacle->re_near_axis }}</td><td>{{ $spectacle->re_near_va }}</td><td>{{ $spectacle->re_near_prism }}</td></tr>
                <tr><td><b>ADD</b></td><td>{{ $spectacle->re_dist_add }}</td><td><b>INT ADD<b></td><td>{{ $spectacle->re_int_add }}</td></tr>
            </table>
        </div>
        <div class="col">
            <table width="99%" class="bordered" cellspacing="0" cellpadding="0">
                <tr><td>LEFT</td><td>SPH</td><td>CYL</td><td>AXIS</td><td>VA</td><td>PRISM</td></tr>
                <tr><td>DIST.</td><td>{{ $spectacle->le_dist_sph }}</td><td>{{ $spectacle->le_dist_cyl }}</td><td>{{ $spectacle->le_dist_axis }}</td><td>{{ $spectacle->le_dist_va }}</td><td>{{ $spectacle->le_dist_prism }}</td></tr>
                <tr><td>INT.</td><td>{{ $spectacle->le_int_sph }}</td><td>{{ $spectacle->le_int_cyl }}</td><td>{{ $spectacle->le_int_axis }}</td><td>{{ $spectacle->le_int_va }}</td><td>{{ $spectacle->le_int_prism }}</td></tr>
                <tr><td>NEAR.</td><td>{{ $spectacle->le_near_sph }}</td><td>{{ $spectacle->le_near_cyl }}</td><td>{{ $spectacle->le_near_axis }}</td><td>{{ $spectacle->le_near_va }}</td><td>{{ $spectacle->le_near_prism }}</td></tr>
                <tr><td><b>ADD</b></td><td>{{ $spectacle->le_dist_add }}</td><td><b>INT ADD<b></td><td>{{ $spectacle->le_int_add }}</td></tr>
            </table>
        </div>
    </div>
    <pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre />
    <div class="row">
        <table width="100%" class="bordered text-center" cellspacing="0" cellpadding="0">
            <tr><td>VD</td><td>IPD</td><td>NPD</td><td>RPD</td><td>LPD</td><td>OD-BC</td><td>OS-BC</td></tr>
            <tr><td>{{ $spectacle->vd }}</td><td>{{ $spectacle->ipd }}</td><td>{{ $spectacle->npd }}</td><td>{{ $spectacle->rpd }}</td><td>{{ $spectacle->lpd }}</td><td>{{ $spectacle->vbr }}</td><td>{{ $spectacle->vbl }}</td></tr>
        </table>
    </div>
    <br />
    <p>IOP/R: {{ $spectacle->re_iop }} &nbsp;&nbsp; IOP/L: {{ $spectacle->le_iop }}</p>
    <center><p>CONTACT LENS PRESCRIPTION</p></center>
    <div class="row">
        <table width="60%" class="bordered" cellspacing="0" cellpadding="0">
            <tr><td></td><td>BASE CURVE</td><td>DIAMETER</td><td>SPH</td><td>CYL</td><td>AXIS</td></tr>
            <tr><td>RIGHT</td><td>{{ $spectacle->re_base_curve }}</td><td>{{ $spectacle->re_dia }}</td><td>{{ $spectacle->re_sph }}</td><td>{{ $spectacle->re_cyl }}</td><td>{{ $spectacle->re_axis }}</td></tr>
            <tr><td>LEFT</td><td>{{ $spectacle->le_base_curve }}</td><td>{{ $spectacle->le_dia }}</td><td>{{ $spectacle->le_sph }}</td><td>{{ $spectacle->le_cyl }}</td><td>{{ $spectacle->le_axis }}</td></tr>
        </table>
    </div>
    <p>Remarks: {{ $spectacle->remarks }}
    <p>Advice: {{ $spectacle->advice }}
    @if($spectacle->review_date)
    <p>Advised a further examination not later: {{ ($spectacle->review_date) ? date('d/M/Y', strtotime($spectacle->review_date)) : '' }}
    @endif
</body>
</html>