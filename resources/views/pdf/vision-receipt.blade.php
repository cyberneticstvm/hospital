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
        @if(Helper::subdomain() == 'emrsas')
        <img src="./images/assets/devi-sas-logo.png" width="35%" /><br />
        @else
        <img src="./images/assets/Devi-Logo-Transparent.jpg" width="15%" /><br />
        @endif
        {{ $branch->address }}, Phone:
        {{ $branch->contact_number }}
    </center>
    <br>
    <table width="100%">
        <thead>
            <tr>
                <th text-align="center" colspan="4">VISION EXAMINATION BILL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Patient Name / ID / MR.ID</td>
                <td>{{ $patient->patient_name }} / {{ $patient->patient_id }} / {{ $spectacle->medical_record_id }}</td>
                <td>Date</td>
                <td>{{ date('d/M/Y', strtotime($spectacle->created_at)) }}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <table class="bordered" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td width="5%">SLNo.</td>
                <td width="80%">PARTICULARS</td>
                <td width="5%">QTY</td>
                <td width="10%">PRICE</td>
            </tr>
            <tr>
                <td>1</td>
                <td>Vision Examination</td>
                <td class='text-right'>1</td>
                <td class='text-right'>{{ $spectacle->fee }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>Total</strong></td>
                <td class="text-right"><strong>{{ number_format($spectacle->fee, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>

</html>