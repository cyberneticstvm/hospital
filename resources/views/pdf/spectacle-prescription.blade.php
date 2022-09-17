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
        .bordered td, .bordered th, .bordered tr{
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
            <table width="100%" class="bordered" cellspacing="0" cellpadding="0">
                <tr><td>RIGHT</td><td>SPH</td><td>CYL</td><td>AXIS</td><td>VA</td><td>PRISM</td></tr>
                <tr><td>DIST.</td><td>{{ $spectacle->re_dist_sph }}</td><td>{{ $spectacle->re_dist_cyl }}</td><td>{{ $spectacle->re_dist_axis }}</td><td>{{ $spectacle->re_dist_va }}</td><td>{{ $spectacle->re_dist_prism }}</td></tr>
                <tr><td>INT.</td><td>{{ $spectacle->re_int_sph }}</td><td>{{ $spectacle->re_int_cyl }}</td><td>{{ $spectacle->re_int_axis }}</td><td>{{ $spectacle->re_int_va }}</td><td>{{ $spectacle->re_int_prism }}</td></tr>
                <tr><td>NEAR.</td><td>{{ $spectacle->re_near_sph }}</td><td>{{ $spectacle->re_near_cyl }}</td><td>{{ $spectacle->re_near_axis }}</td><td>{{ $spectacle->re_near_va }}</td><td>{{ $spectacle->re_near_prism }}</td></tr>
                <tr><td><b>ADD</b></td><td>{{ $spectacle->re_dist_add }}</td><td><b>INT ADD<b></td><td>{{ $spectacle->re_int_add }}</td><td colspan="3"></td></tr>
            </table>
        </div>
        <div class="col">
            <table width="100%" class="bordered" cellspacing="0" cellpadding="0">
                <tr><td>LEFT</td><td>SPH</td><td>CYL</td><td>AXIS</td><td>VA</td><td>PRISM</td></tr>
                <tr><td>DIST.</td><td>{{ $spectacle->le_dist_sph }}</td><td>{{ $spectacle->le_dist_cyl }}</td><td>{{ $spectacle->le_dist_axis }}</td><td>{{ $spectacle->le_dist_va }}</td><td>{{ $spectacle->le_dist_prism }}</td></tr>
                <tr><td>INT.</td><td>{{ $spectacle->le_int_sph }}</td><td>{{ $spectacle->le_int_cyl }}</td><td>{{ $spectacle->le_int_axis }}</td><td>{{ $spectacle->le_int_va }}</td><td>{{ $spectacle->le_int_prism }}</td></tr>
                <tr><td>NEAR.</td><td>{{ $spectacle->le_near_sph }}</td><td>{{ $spectacle->le_near_cyl }}</td><td>{{ $spectacle->le_near_axis }}</td><td>{{ $spectacle->le_near_va }}</td><td>{{ $spectacle->le_near_prism }}</td></tr>
                <tr><td><b>ADD</b></td><td>{{ $spectacle->le_dist_add }}</td><td><b>INT ADD<b></td><td>{{ $spectacle->le_int_add }}</td><td colspan="3"></td></tr>
            </table>
        </div>
    </div>
    <pre /><pre /><pre /><pre /><pre /><pre /><pre /><pre />
    <div class="row">
        <table width="100%" class="bordered text-center" cellspacing="0" cellpadding="0">
            <tr><td>OD-BC</td><td>OS-BC</td><td>VD</td><td>IPD</td><td>NPD</td><td>RPD</td><td>LPD</td></tr>
            <tr><td>{{ $spectacle->vbr }}</td><td>{{ $spectacle->vbl }}</td><td>{{ $spectacle->vd }}</td><td>{{ $spectacle->ipd }}</td><td>{{ $spectacle->npd }}</td><td>{{ $spectacle->rpd }}</td><td>{{ $spectacle->lpd }}</td></tr>
        </table>
    </div>
    <br />    
    <div class="row">
        <table width="100%" class="bordered text-center" cellspacing="0" cellpadding="0">
            <thead class="bordered">
                <tr><th colspan="3">IOP</th><th colspan="3">ARM VALUE</th><th colspan="6">PGP</th></tr>
                <tr><td></td><td>NCT</td><td>AT</td><td>SPH</td><td>CYL</td><td>AXIS</td><td>SPH</td><td>CYL</td><td>AXIS</td><td>ADD</td><td>VISION</td><td>NV</td></tr>
            </thead>
            <tbody>
                <tr><td>OD</td><td>{{ $spectacle->re_iop }}</td><td>{{ $spectacle->iop_at_r }}</td><td>{{ $spectacle->arm_od_sph }}</td><td>{{ $spectacle->arm_od_cyl }}</td><td>{{ $spectacle->arm_od_axis }}</td><td>{{ $spectacle->pgp_od_sph }}</td><td>{{ $spectacle->pgp_od_cyl }}</td><td>{{ $spectacle->pgp_od_axis }}</td><td>{{ $spectacle->pgp_od_add }}</td><td>{{ $spectacle->pgp_od_vision }}</td><td>{{ $spectacle->pgp_od_nv }}</td></tr>
                <tr><td>OS</td><td>{{ $spectacle->le_iop }}</td><td>{{ $spectacle->iop_at_l }}</td><td>{{ $spectacle->arm_os_sph }}</td><td>{{ $spectacle->arm_os_cyl }}</td><td>{{ $spectacle->arm_os_axis }}</td><td>{{ $spectacle->pgp_os_sph }}</td><td>{{ $spectacle->pgp_os_cyl }}</td><td>{{ $spectacle->pgp_os_axis }}</td><td>{{ $spectacle->pgp_os_add }}</td><td>{{ $spectacle->pgp_os_vision }}</td><td>{{ $spectacle->pgp_os_nv }}</td></tr>
                <tr><td>Time</td><td>{{ $spectacle->iop_nct_time }}</td><td>{{ $spectacle->iop_at_time }}</td></tr>
            </tbody>
        </table>
    </div>
    <br />
    <div class="row">
        <table width="100%" class="bordered text-center" cellspacing="0" cellpadding="0">
            <thead class="bordered">
                <tr><th colspan="7">Dilated Refraction</th><th colspan="5">CONTACT LENS PRESCRIPTION</th></tr>
                <tr><td></td><td>SPH</td><td>CYL</td><td>AXIS</td><td>ADD</td><td>VISION</td><td>NV</td><td>BASE CURVE</td><td>DIAMETER</td><td>SPH</td><td>CYL</td><td>AXIS</td></tr>
            </thead>
            <tbody>
                <tr><td>OD</td><td>{{ $spectacle->dr_od_sph }}</td><td>{{ $spectacle->dr_od_cyl }}</td><td>{{ $spectacle->dr_od_axis }}</td><td>{{ $spectacle->dr_od_add }}</td><td>{{ $spectacle->dr_od_vision }}</td><td>{{ $spectacle->dr_od_nv }}</td><td>{{ $spectacle->re_base_curve }}</td><td>{{ $spectacle->re_dia }}</td><td>{{ $spectacle->re_sph }}</td><td>{{ $spectacle->re_cyl }}</td><td>{{ $spectacle->re_axis }}</td></tr>

                <tr><td>OS</td><td>{{ $spectacle->dr_os_sph }}</td><td>{{ $spectacle->dr_os_cyl }}</td><td>{{ $spectacle->dr_os_axis }}</td><td>{{ $spectacle->dr_os_add }}</td><td>{{ $spectacle->dr_os_vision }}</td><td>{{ $spectacle->dr_os_nv }}</td><td>{{ $spectacle->le_base_curve }}</td><td>{{ $spectacle->le_dia }}</td><td>{{ $spectacle->le_sph }}</td><td>{{ $spectacle->le_cyl }}</td><td>{{ $spectacle->le_axis }}</td></tr>
            </tbody>
        </table>
    </div>
    <br />
    <div class="row">
        <p>Biometry</p>
        <table width="100%" class="bordered text-center" cellspacing="0" cellpadding="0">
            <thead class="bordered">
                <tr><td></td><td>K1(A)</td><td>K2(A)</td><td>K1(M)</td><td>K2(M)</td><td>AXL</td><td>ACD</td><td>LENS</td><td>K-VALUE(Avg)</td><td>IOL Power</td></tr>
            </thead>
            <tbody>
                <tr><td>OD</td><td>{{ $spectacle->bm_k1_od_a }}</td><td>{{ $spectacle->bm_k2_od_a }}</td><td>{{ $spectacle->bm_k1_od_m }}</td><td>{{ $spectacle->bm_k2_od_m }}</td><td>{{ $spectacle->bm_od_axl }}</td><td>{{ $spectacle->bm_od_acd }}</td><td>{{ $spectacle->bm_od_lens }}</td><td>{{ $spectacle->bm_od_kvalue_a }}</td><td>{{ $spectacle->bm_od_iol }}</td></tr>

                <tr><td>OS</td><td>{{ $spectacle->bm_k1_os_a }}</td><td>{{ $spectacle->bm_k2_os_a }}</td><td>{{ $spectacle->bm_k1_os_m }}</td><td>{{ $spectacle->bm_k2_os_m }}</td><td>{{ $spectacle->bm_os_axl }}</td><td>{{ $spectacle->bm_os_acd }}</td><td>{{ $spectacle->bm_os_lens }}</td><td>{{ $spectacle->bm_os_kvalue_a }}</td><td>{{ $spectacle->bm_os_iol }}</td></tr>
            </tbody>
        </table>
    </div>
    <p>Remarks: {{ $spectacle->remarks }}
    <p>Advice: {{ $spectacle->advice }}
    @if($spectacle->review_date)
    <p>Advised a further examination not later: {{ ($spectacle->review_date) ? date('d/M/Y', strtotime($spectacle->review_date)) : '' }}
    @endif
</body>
</html>