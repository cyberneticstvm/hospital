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
        Registered Office: Maithanam, Varkala, Thiruvananthapuram. Phone: {{ $branch?->contact_number }}<br>
        Branch: Thekkumbagom, Paravur, Kollam.<br>
        DL Number: RLF21KL2022000540, RLF20KL2022000543
    </center>
    <br />
    <table width="100%">
        <thead>
            <tr>
                <th text-align="center" colspan="4">PHARMACY CASH BILL</th>
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
                <td>RECEIPT NUMBER</td>
                <td>{{ $medical_record->id }}</td>
            </tr>
            <tr>
                <td>DOCTOR NAME</td>
                <td>{{ $doctor->doctor_name }}</td>
                <td>RECEIPT DATE</td>
                <td>{{ ($medical_record->created_at) ? date('d/M/Y h:i:A', strtotime($medical_record->created_at)) : '' }}</td>
            </tr>
        </tbody>
    </table>
    <table class="bordered" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td>SLNo.</td>
                <td width="20%">ITEM NAME</td>
                <td>HSN</td>
                <td>BATCH</td>
                <td>EXPIRY</td>
                <td>QTY</td>
                <td>MRP</td>
                <td>Discount</td>
                <td>Tax Amount</td>
                <td>Rate</td>
                <td>Total</td>
            </tr>
            @php $c = 1; $net_total = 0; $tax_total = 0; @endphp
            @foreach($medicines as $medicine)
            @php $tot = $medicine->total + ($medicine->total*$medicine->tax_percentage)/100 @endphp
            <tr>
                <td>{{ $c++ }}</td>
                <td>{{ $medicine->product_name }}</td>
                <td>{{ $medicine->hsn }}</td>
                <td>{{ $medicine->batch_number }}</td>
                <td>{{ ($medicine->expiry_date) ? date('d/M/Y', strtotime($medicine->expiry_date)) : '' }}</td>
                <td class="text-right">{{ $medicine->qty }}</td>
                <td class="text-right">{{ number_format($medicine->mrp * $medicine->qty, 2) }}</td>
                <td class="text-right">{{ number_format($medicine->discount, 2) }}</td>
                <td class="text-right">{{ number_format($medicine->tax_amount, 2) }}</td>
                <td class="text-right">{{ number_format($medicine->price * $medicine->qty, 2) }}</td>
                <td class="text-right">{{ number_format($medicine->total, 2) }}</td>
                {{ $tax_total += $medicine->tax_amount }}
                {{ $net_total += $medicine->total }}
            </tr>
            @endforeach
            <tr>
                <td colspan="8" class="text-right">Total</td>
                <td class="text-right">{{ number_format($tax_total, 2) }}</td>
                <td></td>
                <td class="text-right">{{ number_format($net_total, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>