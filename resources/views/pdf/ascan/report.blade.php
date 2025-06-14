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

        .bg-info {
            background-color: lightskyblue;
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
                <th text-align="center" colspan="4">A-SCAN REPORT</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Patient Name / ID / MR.ID</td>
                <td>{{ $patient->patient_name }} / {{ $patient->patient_id }} / {{ $ascan->medical_record_id }}</td>
                <td>Date</td>
                <td>{{ date('d/M/Y', strtotime($ascan->created_at)) }}</td>
            </tr>
            <tr>
                <td>Address</td>
                <td colspan="3">{{ $patient->address }}</td>
            </tr>
        </tbody>
    </table>
    <br />
    <h3>Keratometry</h3>
    @if($keratometry)
    <table class="bordered" width="100%" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th></th>
                <th>K1(A)</th>
                <th>AXIS</th>
                <th>K2(A)</th>
                <th>AXIS</th>
                <th>K1(M)</th>
                <th>AXIS</th>
                <th>K2(M)</th>
                <th>AXIS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>OD</td>
                <td>{{ $keratometry->k1_od_auto }}</td>
                <td>{{ $keratometry->k1_od_axis_a }}</td>
                <td>{{ $keratometry->k2_od_auto }}</td>
                <td>{{ $keratometry->k2_od_axis_a }}</td>
                <td>{{ $keratometry->k1_od_manual }}</td>
                <td>{{ $keratometry->k1_od_axis_m }}</td>
                <td>{{ $keratometry->k2_od_manual }}</td>
                <td>{{ $keratometry->k2_od_axis_m }}</td>
            </tr>
            <tr>
                <td>OS</td>
                <td>{{ $keratometry->k1_os_auto }}</td>
                <td>{{ $keratometry->k1_os_axis_a }}</td>
                <td>{{ $keratometry->k2_os_auto }}</td>
                <td>{{ $keratometry->k2_os_axis_a }}</td>
                <td>{{ $keratometry->k1_os_manual }}</td>
                <td>{{ $keratometry->k1_os_axis_m }}</td>
                <td>{{ $keratometry->k2_os_manual }}</td>
                <td>{{ $keratometry->k2_os_axis_m }}</td>
            </tr>
        </tbody>
    </table>
    @else
    --
    @endif
    <br>
    <h3>A-Scan</h3>
    <table class="bordered" width="100%" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th></th>
                <th>AXL</th>
                <th>ACD</th>
                <th>LENS</th>
                <th>A-CONST.</th>
                <th>IOL</th>
            </tr>
        </thead>
        <tbody>
            <tr class="{{ ($ascan?->eye == 'OD' || $ascan?->eye == 'od') ? 'bg-info' : '' }}">
                <td>OD</td>
                <td>{{ $ascan->od_axl }}</td>
                <td>{{ $ascan->od_acd }}</td>
                <td>{{ $ascan->od_lens }}</td>
                <td>{{ $ascan->od_a_constant }}</td>
                <td>{{ $ascan->od_iol_power }}</td>
            </tr>
            <tr class="{{ ($ascan?->eye == 'OS' || $ascan?->eye == 'os') ? 'bg-info' : '' }}">
                <td>OS</td>
                <td>{{ $ascan->os_axl }}</td>
                <td>{{ $ascan->os_acd }}</td>
                <td>{{ $ascan->os_lens }}</td>
                <td>{{ $ascan->os_a_constant }}</td>
                <td>{{ $ascan->os_iol_power }}</td>
            </tr>
        </tbody>
    </table>
    <h5>A-CONST & IOL</h5>
    <table class="bordered" width="100%" cellspacing="0" cellpadding="0">
        <thead class="text-center">
            <tr>
                <th colspan="2">OD</th>
                <th colspan="2">OS</th>
            </tr>
            <tr>
                <th>A const</th>
                <th>IOL</th>
                <th>A const</th>
                <th>IOL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $ascan->aconst_od1 }}</td>
                <td>{{ $ascan->iol_od1 }}</td>
                <td>{{ $ascan->aconst_os1 }}</td>
                <td>{{ $ascan->iol_os1 }}</td>
            </tr>
            <tr>
                <td>{{ $ascan->aconst_od2 }}</td>
                <td>{{ $ascan->iol_od2 }}</td>
                <td>{{ $ascan->aconst_os2 }}</td>
                <td>{{ $ascan->iol_os2 }}</td>
            </tr>
            <tr>
                <td>{{ $ascan->aconst_od3 }}</td>
                <td>{{ $ascan->iol_od3 }}</td>
                <td>{{ $ascan->aconst_os3 }}</td>
                <td>{{ $ascan->iol_os3 }}</td>
            </tr>
        </tbody>
    </table>
    <p>Procedures: {{ $procs->whereIn('id', $procedures->pluck('procedure'))->pluck('name')->implode(',') }}</p>
    <h3>Sx Eye: {{ $ascan->eye }} - {{ ($ascan?->eye == 'OD' || $ascan?->eye == 'od') ? $ascan?->od_iol_power : $ascan?->os_iol_power }}</h3>
</body>

</html>