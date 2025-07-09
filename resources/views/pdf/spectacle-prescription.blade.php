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
        table tbody td {
            padding: 5px;
        }

        .bordered td,
        .bordered th,
        .bordered tr {
            border: 1px solid #e6e6e6;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .col {
            width: 50%;
            float: left;
        }
    </style>
</head>

<body>
    <center>
        <img src="./images/assets/Devi-Logo-Transparent.jpg" width="15%" /><br />
        {{ $branch->address }}, Phone:
        {{ $branch->contact_number }}
    </center>
    <br />
    <table width="100%">
        <thead>
            <tr>
                <th text-align="center" colspan="4">PRESCRIPTION</th>
            </tr>
        </thead>
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
            <tr>
                <td>OPTOMETRIST:</td>
                <td> {{ ($spectacle && $spectacle->updateduser) ? $spectacle->updateduser->name : '' }}</td>
                <td>MR ID</td>
                <td>{{ $reference->id }}</td>
            </tr>
        </tbody>
    </table>
    <center>
        <p>EYE GLASS PRESCRIPTION</p>
    </center>
    <div class="row">
        <table width="100%" class="bordered" cellspacing="0" cellpadding="0">
            <tr>
                <td width="50%">OD UCV: {{ ($spectacle->vbr) ? $spectacle->vbr : '--' }}</td>
                <td>OS UCV: {{ ($spectacle->vbl) ? $spectacle->vbl : '--' }}</td>
            </tr>
        </table>
    </div>
    <div class="row">
        <div class="col">
            <table width="100%" class="bordered" cellspacing="0" cellpadding="0">
                <tr>
                    <td>RIGHT</td>
                    <td>SPH</td>
                    <td>CYL</td>
                    <td>AXIS</td>
                    <td>BCV</td>
                    <td>PRISM</td>
                </tr>
                <tr>
                    <td>DIST.</td>
                    <td>{{ ($spectacle->re_dist_sph) ? $spectacle->re_dist_sph : '0.00' }}</td>
                    <td>{{ ($spectacle->re_dist_cyl) ? $spectacle->re_dist_cyl : '0.00' }}</td>
                    <td>{{ ($spectacle->re_dist_axis) ? $spectacle->re_dist_axis: '0.00' }}</td>
                    <td>{{ ($spectacle->re_dist_va) ? $spectacle->re_dist_va : '--' }}</td>
                    <td>{{ ($spectacle->re_dist_prism) ? $spectacle->re_dist_prism : '--' }}</td>
                </tr>
                <tr>
                    <td>INT.</td>
                    <td>{{ ($spectacle->re_int_sph) ? $spectacle->re_int_sph : '0.00' }}</td>
                    <td>{{ ($spectacle->re_int_cyl) ? $spectacle->re_int_cyl : '0.00' }}</td>
                    <td>{{ ($spectacle->re_int_axis) ? $spectacle->re_int_axis : '0.00' }}</td>
                    <td>{{ ($spectacle->re_int_va) ? $spectacle->re_int_va : '--' }}</td>
                    <td>{{ ($spectacle->re_int_prism) ? $spectacle->re_int_prism : '--' }}</td>
                </tr>
                <tr>
                    <td>NEAR.</td>
                    <td>{{ ($spectacle->re_near_sph) ? $spectacle->re_near_sph : '0.00' }}</td>
                    <td>{{ ($spectacle->re_near_cyl) ? $spectacle->re_near_cyl : '0.00' }}</td>
                    <td>{{ ($spectacle->re_near_axis) ? $spectacle->re_near_axis : '0.00' }}</td>
                    <td>{{ ($spectacle->re_near_va) ? $spectacle->re_near_va : '--' }}</td>
                    <td>{{ ($spectacle->re_near_prism) ? $spectacle->re_near_prism : '--' }}</td>
                </tr>
                <tr>
                    <td><b>ADD</b></td>
                    <td>{{ ($spectacle->re_dist_add) ? $spectacle->re_dist_add : '--' }}</td>
                    <td><b>INT ADD<b></td>
                    <td>{{ ($spectacle->re_int_add) ? $spectacle->re_int_add : '--' }}</td>
                    <td colspan="3"></td>
                </tr>
            </table>
        </div>
        <div class="col">
            <table width="100%" class="bordered" cellspacing="0" cellpadding="0">
                <tr>
                    <td>LEFT</td>
                    <td>SPH</td>
                    <td>CYL</td>
                    <td>AXIS</td>
                    <td>BCV</td>
                    <td>PRISM</td>
                </tr>
                <tr>
                    <td>DIST.</td>
                    <td>{{ ($spectacle->le_dist_sph) ? $spectacle->le_dist_sph : '0.00' }}</td>
                    <td>{{ ($spectacle->le_dist_cyl) ? $spectacle->le_dist_cyl : '0.00' }}</td>
                    <td>{{ ($spectacle->le_dist_axis) ? $spectacle->le_dist_axis : '0.00' }}</td>
                    <td>{{ ($spectacle->le_dist_va) ? $spectacle->le_dist_va : '--' }}</td>
                    <td>{{ ($spectacle->le_dist_prism) ? $spectacle->le_dist_prism : '--' }}</td>
                </tr>
                <tr>
                    <td>INT.</td>
                    <td>{{ ($spectacle->le_int_sph) ? $spectacle->le_int_sph : '0.00' }}</td>
                    <td>{{ ($spectacle->le_int_cyl) ? $spectacle->le_int_cyl : '0.00' }}</td>
                    <td>{{ ($spectacle->le_int_axis) ? $spectacle->le_int_axis : '0.00' }}</td>
                    <td>{{ ($spectacle->le_int_va) ? $spectacle->le_int_va : '--' }}</td>
                    <td>{{ ($spectacle->le_int_prism) ? $spectacle->le_int_prism : '--' }}</td>
                </tr>
                <tr>
                    <td>NEAR.</td>
                    <td>{{ ($spectacle->le_near_sph) ? $spectacle->le_near_sph : '0.00' }}</td>
                    <td>{{ ($spectacle->le_near_cyl) ? $spectacle->le_near_cyl : '0.00' }}</td>
                    <td>{{ $spectacle->le_near_axis ? $spectacle->le_near_axis : '0.00' }}</td>
                    <td>{{ ($spectacle->le_near_va) ? $spectacle->le_near_va : '--' }}</td>
                    <td>{{ ($spectacle->le_near_prism) ? $spectacle->le_near_prism : '--' }}</td>
                </tr>
                <tr>
                    <td><b>ADD</b></td>
                    <td>{{ ($spectacle->le_dist_add) ? $spectacle->le_dist_add : '--' }}</td>
                    <td><b>INT ADD<b></td>
                    <td>{{ ($spectacle->le_int_add) ? $spectacle->le_int_add : '--' }}</td>
                    <td colspan="2"></td>
                </tr>
            </table>
        </div>
    </div>
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <pre />
    <div class="row">
        <table width="100%" class="bordered text-center" cellspacing="0" cellpadding="0">
            <tr>
                <td>VD</td>
                <td>IPD</td>
                <td>NPD</td>
                <td>RPD</td>
                <td>LPD</td>
            </tr>
            <tr>
                <td>{{ ($spectacle->vd) ? $spectacle->vd : '--' }}</td>
                <td>{{ ($spectacle->ipd) ? $spectacle->ipd : '--' }}</td>
                <td>{{ ($spectacle->npd) ? $spectacle->npd : '--' }}</td>
                <td>{{ ($spectacle->rpd) ? $spectacle->rpd : '--' }}</td>
                <td>{{ ($spectacle->lpd) ? $spectacle->lpd : '--' }}</td>
            </tr>
        </table>
    </div>
    <br />
    <div class="row">
        <table width="100%" class="bordered text-center" cellspacing="0" cellpadding="0">
            <thead class="bordered">
                <tr>
                    <th colspan="3">IOP</th>
                    <th colspan="3">ARM VALUE</th>
                    <th colspan="6">PGP</th>
                </tr>
                <tr>
                    <td></td>
                    <td>NCT</td>
                    <td>AT</td>
                    <td>SPH</td>
                    <td>CYL</td>
                    <td>AXIS</td>
                    <td>SPH</td>
                    <td>CYL</td>
                    <td>AXIS</td>
                    <td>ADD</td>
                    <td>VISION</td>
                    <td>NV</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>OD</td>
                    <td>{{ ($spectacle->re_iop) ? $spectacle->re_iop : '--' }}</td>
                    <td>{{ ($spectacle->iop_at_r) ? $spectacle->iop_at_r : '--' }}</td>
                    <td>{{ ($spectacle->arm_od_sph) ? $spectacle->arm_od_sph : '0.00' }}</td>
                    <td>{{ ($spectacle->arm_od_cyl) ? $spectacle->arm_od_cyl : '0.00' }}</td>
                    <td>{{ ($spectacle->arm_od_axis) ? $spectacle->arm_od_axis : '0.00' }}</td>
                    <td>{{ ($spectacle->pgp_od_sph) ? $spectacle->pgp_od_sph : '0.00' }}</td>
                    <td>{{ ($spectacle->pgp_od_cyl) ? $spectacle->pgp_od_cyl : '0.00' }}</td>
                    <td>{{ ($spectacle->pgp_od_axis) ? $spectacle->pgp_od_axis : '0.00' }}</td>
                    <td>{{ ($spectacle->pgp_od_add) ? $spectacle->pgp_od_add : '--' }}</td>
                    <td>{{ ($spectacle->pgp_od_vision) ? $spectacle->pgp_od_vision : '--' }}</td>
                    <td>{{ ($spectacle->pgp_od_nv) ? $spectacle->pgp_od_nv : '--' }}</td>
                </tr>
                <tr>
                    <td>OS</td>
                    <td>{{ ($spectacle->le_iop) ? $spectacle->le_iop : '--' }}</td>
                    <td>{{ ($spectacle->iop_at_l) ? $spectacle->iop_at_l : '--' }}</td>
                    <td>{{ ($spectacle->arm_os_sph) ? $spectacle->arm_os_sph : '0.00' }}</td>
                    <td>{{ ($spectacle->arm_os_cyl) ? $spectacle->arm_os_cyl : '0.00' }}</td>
                    <td>{{ ($spectacle->arm_os_axis) ? $spectacle->arm_os_axis : '0.00' }}</td>
                    <td>{{ ($spectacle->pgp_os_sph) ? $spectacle->pgp_os_sph : '0.00' }}</td>
                    <td>{{ ($spectacle->pgp_os_cyl) ? $spectacle->pgp_os_cyl : '0.00' }}</td>
                    <td>{{ ($spectacle->pgp_os_axis) ? $spectacle->pgp_os_axis : '0.00' }}</td>
                    <td>{{ ($spectacle->pgp_os_add) ? $spectacle->pgp_os_add : '--' }}</td>
                    <td>{{ ($spectacle->pgp_os_vision) ? $spectacle->pgp_os_vision : '--' }}</td>
                    <td>{{ ($spectacle->pgp_os_nv) ? $spectacle->pgp_os_nv : '--' }}</td>
                </tr>
                <tr>
                    <td>Time</td>
                    <td>{{ ($spectacle->iop_nct_time) ? $spectacle->iop_nct_time : '--' }}</td>
                    <td>{{ ($spectacle->iop_at_time) ? $spectacle->iop_at_time : '--' }}</td>
                    <td colspan="9"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <br />
    <div class="row">
        <table width="100%" class="bordered text-center" cellspacing="0" cellpadding="0">
            <thead class="bordered">
                <tr>
                    <th colspan="7">Dilated Refraction</th>
                    <th colspan="5">CONTACT LENS PRESCRIPTION</th>
                </tr>
                <tr>
                    <td></td>
                    <td>SPH</td>
                    <td>CYL</td>
                    <td>AXIS</td>
                    <td>ADD</td>
                    <td>VISION</td>
                    <td>NV</td>
                    <td>BASE CURVE</td>
                    <td>DIAMETER</td>
                    <td>SPH</td>
                    <td>CYL</td>
                    <td>AXIS</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>OD</td>
                    <td>{{ ($spectacle->dr_od_sph) ? $spectacle->dr_od_sph : '0.00' }}</td>
                    <td>{{ ($spectacle->dr_od_cyl) ? $spectacle->dr_od_cyl : '0.00' }}</td>
                    <td>{{ ($spectacle->dr_od_axis) ? $spectacle->dr_od_axis : '0.00' }}</td>
                    <td>{{ ($spectacle->dr_od_add) ? $spectacle->dr_od_add : '--' }}</td>
                    <td>{{ ($spectacle->dr_od_vision) ? $spectacle->dr_od_vision : '--' }}</td>
                    <td>{{ ($spectacle->dr_od_nv) ? $spectacle->dr_od_nv : '--' }}</td>
                    <td>{{ ($spectacle->re_base_curve) ? $spectacle->re_base_curve : '--' }}</td>
                    <td>{{ ($spectacle->re_dia) ? $spectacle->re_dia : '--' }}</td>
                    <td>{{ ($spectacle->re_sph) ? $spectacle->re_sph : '0.00' }}</td>
                    <td>{{ ($spectacle->re_cyl) ? $spectacle->re_cyl : '0.00' }}</td>
                    <td>{{ ($spectacle->re_axis) ? $spectacle->re_axis : '0.00' }}</td>
                </tr>

                <tr>
                    <td>OS</td>
                    <td>{{ ($spectacle->dr_os_sph) ? $spectacle->dr_os_sph : '0.00' }}</td>
                    <td>{{ ($spectacle->dr_os_cyl) ? $spectacle->dr_os_cyl : '0.00' }}</td>
                    <td>{{ ($spectacle->dr_os_axis) ? $spectacle->dr_os_axis : '0.00' }}</td>
                    <td>{{ ($spectacle->dr_os_add) ? $spectacle->dr_os_add : '--' }}</td>
                    <td>{{ ($spectacle->dr_os_vision) ? $spectacle->dr_os_vision : '--' }}</td>
                    <td>{{ ($spectacle->dr_os_nv) ? $spectacle->dr_os_nv : '--' }}</td>
                    <td>{{ ($spectacle->le_base_curve) ? $spectacle->le_base_curve : '--' }}</td>
                    <td>{{ ($spectacle->le_dia) ? $spectacle->le_dia : '--' }}</td>
                    <td>{{ ($spectacle->le_sph) ? $spectacle->le_sph : '0.00' }}</td>
                    <td>{{ ($spectacle->le_cyl) ? $spectacle->le_cyl : '0.00' }}</td>
                    <td>{{ ($spectacle->le_axis) ? $spectacle->le_axis : '0.00' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <br />
    <p>Remarks: {{ $spectacle->remarks }}
    <p>Advice: {{ $spectacle->advice }}
        @if($spectacle->review_date)
    <p>Advised a further examination not later: {{ ($spectacle->review_date) ? date('d/M/Y', strtotime($spectacle->review_date)) : '' }}
        @endif
    <div class="text-right">
        <img src="data:image/png;base64, {!! $qrcode !!}">
        <p class="text-small">To book an appointment, scan this QR code.</p>
    </div>
</body>

</html>