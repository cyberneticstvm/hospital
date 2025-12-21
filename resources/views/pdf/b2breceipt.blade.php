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
        DL Number: RLF21KL2022000540, RLF20KL2022000543
    </center>
    <br />
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th text-align="center" colspan="6">PHARMACY SALE BILL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Customer Name</td>
                <td>{{ $customer?->name }}</td>
                <td>Address</td>
                <td>{{ $customer?->address }}</td>
                <td>Bill/Receipt No.</td>
                <td>{{ $pharmacy->id }}</td>
            </tr>
        </tbody>
    </table>
    <br />
    <table class="bordered" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td width="5%">SLNo.</td>
                <td width="30%">MEDICINE</td>
                <td width="10%">BATCH</td>
                <td width="5%">QTY</td>
                <td width="10%">PRICE</td>
                <td width="10%">DISCOUNT</td>
                <td width="10%">TAX%</td>
                <td width="10%">TAX AMOUNT</td>
                <td width="10%">TOTAL</td>
            </tr>
            @php $c = 1; $tot = 0.00; @endphp
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
                <td class="text-right">{{ $medicine->total }}</td>
            </tr>
            @php $tot += $medicine->total @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8" class="text-right"><strong>Total</strong></td>
                <td class="text-right"><strong>{{ number_format($tot, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>