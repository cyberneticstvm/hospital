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
    </style>
</head>

<body>
    <center>
        @if(Helper::subdomain() == 'emrsas')
        <img src="./images/assets/devi-sas-logo.png" width="35%" /><br />
        @else
        <img src="./images/assets/Devi-Logo-Transparent.jpg" width="15%" /><br />
        @endif
        {{ $branch->address }}, Phone:
        {{ $branch->contact_number }}
    </center>
    <br />
    <table width="100%">
        <thead>
            <tr>
                <th text-align="center" colspan="4">PACHYMETRY REPORT</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Patient Name / ID / MR.ID</td>
                <td>{{ $patient->patient_name }} / {{ $patient->patient_id }} / {{ $pachymetry->medical_record_id }}</td>
                <td>Date</td>
                <td>{{ date('d/M/Y', strtotime($pachymetry->created_at)) }}</td>
            </tr>
        </tbody>
    </table>
    <br><br>
    <table border="1" width="100%" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th class="text-center" colspan="4">OD</th>
                <th class="text-center" colspan="3">OS</th>
            </tr>
            <tr>
                <th></th>
                <th>IOP</th>
                <th>CCT</th>
                <th>CIOP</th>
                <th>IOP</th>
                <th>CCT</th>
                <th>CIOP</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>AVG</td>
                <td class="text-center">{{ $pachymetry->od_iop }}</td>
                <td class="text-center">{{ $pachymetry->od_cct }}</td>
                <td class="text-center">{{ $pachymetry->od_ciop }}</td>
                <td class="text-center">{{ $pachymetry->os_iop }}</td>
                <td class="text-center">{{ $pachymetry->os_cct }}</td>
                <td class="text-center">{{ $pachymetry->os_ciop }}</td>
            </tr>
        </tbody>
    </table>
    <br><br>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td width="50%">
                    {{ ($pachymetry->img1) ? 'PCY (OD)' : '' }}
                    <img src="{{ ($pachymetry->img1) ? './storage/'.$pachymetry->img1 : '' }}" width="100%" /><br />
                    {{ $pachymetry->img1_value }}
                </td>
                <td>
                    {{ ($pachymetry->img2) ? 'PCY (OS)' : '' }}
                    <img src="{{ ($pachymetry->img2) ? './storage/'.$pachymetry->img2 : '' }}" width="100%" /><br />
                    {{ $pachymetry->img2_value }}
                </td>
            </tr>
            <tr>
                <td width="50%">
                    {{ ($pachymetry->img3) ? 'ACA (OD)' : '' }}
                    <img src="{{ ($pachymetry->img3) ? './storage/'.$pachymetry->img3 : '' }}" width="100%" /><br />
                    {{ $pachymetry->img3_value }}
                </td>
                <td>
                    {{ ($pachymetry->img4) ? 'ACA (OS)' : '' }}
                    <img src="{{ ($pachymetry->img4) ? './storage/'.$pachymetry->img4 : '' }}" width="100%" /><br />
                    {{ $pachymetry->img4_value }}
                </td>
            </tr>
        </tbody>
    </table>
    <br><br>
    <div class="text-right">Authorized Signatory</div>
</body>

</html>