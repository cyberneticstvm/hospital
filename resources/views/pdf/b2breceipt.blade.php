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
        Registered Office: Maithanam, Varkala, Thiruvananthapuram. Phone: {{ $branch->contact_number }}<br>
        Branch: Thekkumbagom, Paravur, Kollam.<br>
        DL Number: RLF21KL2022000540, RLF20KL2022000543<br>
        GST: 32AHTPC8761A1ZH
    </center>
    <br />
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th text-align="center" colspan="4">PHARMACY SALE BILL</th>
            </tr>
        </thead>
        <tbody class="bordered">
            <tr>
                <td>Customer Name</td>
                <td colspan="3">{{ $customer?->name }}</td>
            </tr>
            <tr>
                <td>Address</td>
                <td colspan="3">{{ nl2br(wordwrap($customer?->address, 50, '\n', true)) }}</td>
            </tr>
            <tr>
                <td>Bill No. / Date</td>
                <td colspan="3">{{ $pharmacy->id }} | {{ $pharmacy->created_at->format('d.M.Y') }}</td>
            </tr>
        </tbody>
    </table>
    <br />
    <table class="bordered" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td width="5%">SLNo.</td>
                <td width="20%">MEDICINE</td>
                <td width="10%">BATCH</td>
                <td width="5%">QTY</td>
                <td width="10%">PRICE</td>
                <td width="10%">DISCOUNT</td>
                <td width="10%">TAX%</td>
                <td width="10%">TAX</td>
                <td width="10%">Taxable Value</td>
                <td width="10%">TOTAL</td>
            </tr>
            @php $c = 1; $tot = 0.00; $ttot = 0; @endphp
            @foreach($medicines as $medicine)
            <tr>
                <td>{{ $c++ }}</td>
                <td>{{ $medicine->product_name }}</td>
                <td>{{ $medicine->batch_number }}</td>
                <td class="text-right">{{ $medicine->qty }}</td>
                <td class="text-right">{{ $medicine->price }}</td>
                <td class="text-right">{{ $medicine->discount }}</td>
                <td class="text-right">{{ $medicine->tax }}</td>
                <td class="text-right">{{ $medicine->tax_amount }}</td>
                <td class="text-right">{{ $medicine->price * $medicine->qty }}</td>
                <td class="text-right">{{ number_format($medicine->total, 2) }}</td>
            </tr>
            @php $tot += $medicine->total; $ttot += $medicine->price * $medicine->qty; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8" class="text-right"><strong>Total</strong></td>
                <td class="text-right"><strong>{{ number_format($ttot, 2) }}</strong></td>
                <td class="text-right"><strong>{{ number_format($tot, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>