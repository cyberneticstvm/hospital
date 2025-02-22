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
        <img src="./images/assets/Devi-Logo-Transparent.jpg" width="15%" /><br />
        {{ $branch->address }}, Phone:
        {{ $branch->contact_number }}
    </center>
    <br />
    <table width="100%">
        <thead>
            <tr>
                <th text-align="center" colspan="4">OCT BILL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Hospital Id</td>
                <td>{{ $branch->hospital_id }}</td>
                <td>Bill Number</td>
                <td>{{ $oct->receipt_number }}</td>
            </tr>
            <tr>
                <td>Patient Name / ID / MR.ID</td>
                <td>{{ $patient->patient_name }} / {{ $patient->patient_id }} / {{ $oct->medical_record_id }}</td>
                <td>Date</td>
                <td>{{ date('d/M/Y', strtotime($oct->created_at)) }}</td>
            </tr>
            <tr>
                <td>Advised By</td>
                <td>{{ $doctor->doctor_name }}</td>
                <td>Advised On</td>
                <td>{{ $mrecord->created_at->format('d/M/Y') }}</td>
            </tr>
        </tbody>
    </table>
    <br />
    <table class="bordered" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td width="5%">SLNo.</td>
                <td width="60%">PROCEDURE</td>
                <td width="5%">QTY</td>
                <td width="10%">PRICE</td>
                <td width="10%">DISCOUNT</td>
                <td width="10%">TOTAL</td>
            </tr>
            @php $c = 1; $tot = 0.00; @endphp
            @foreach($procedures as $key => $proc)
            <tr>
                <td>{{ $c++ }}</td>
                <td>{{ $proc->name }}</td>
                <td class="text-right">1</td>
                <td class="text-right">{{ $proc->fee + $proc->discount }}</td>
                <td class="text-right">{{ $proc->discount }}</td>
                <td class="text-right">{{ $proc->fee }}</td>
            </tr>
            @php $tot += $proc->fee @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right"><strong>Total</strong></td>
                <td class="text-right"><strong>{{ number_format($tot, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
    <p class='text-right'>Authorized Signatory</p><br>
</body>

</html>