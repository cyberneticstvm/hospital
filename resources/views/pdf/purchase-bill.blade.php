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
        .bordered th {
            border: 1px solid #e6e6e6;
        }

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
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
    </center>
    <br />
    <table width="100%" class="bordered" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th text-align="center" colspan="4">PURCHASE BILL ON {{ date('d/M/Y', strtotime($purchase->delivery_date)) }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Supplier</td>
                <td>{{ $supplier->name }}</td>
                <td>Supplier Invoice No.</td>
                <td>{{ $purchase->invoice_number }}</td>
            </tr>
            <tr>
                <td>Bill No.</td>
                <td>{{ $purchase->id }}</td>
                <td>Oder Date</td>
                <td>{{ date('d/M/Y', strtotime($purchase->order_date)) }}</td>
            </tr>
        </tbody>
    </table>
    <br><br>
    <table width="100%" class="bordered" cellpadding="0" cellspacing="0">
        <thead>
            <th>SL No.</th>
            <th>Product</th>
            <th>Batch</th>
            <th>Qty</th>
            <th>MRP</th>
            <th>Tax%</th>
            <th>Pur.Price</th>
            <th>CGST</th>
            <th>SGST</th>
            <th>Total</th>
        </thead>
        <tbody>
            @php
            use App\Models\Product;
            use App\Models\ProductCategory;
            $c = 1; $tax_tot = 0; $pp = 0;
            @endphp
            @forelse($purchases as $key => $pur)
            @php
            $tax_tot += ($pur->tax_amount * $pur->qty) / 2;
            $pp += $pur->purchase_price;
            $pc = ProductCategory::where('id', Product::where('id', $pur->pid)->first()->category_id)->first();
            @endphp
            <tr>
                <td>{{ $c++ }}</td>
                <td>{{ $pur->product_name }}</td>
                <td>{{ $pur->batch_number }}</td>
                <td class="text-right">{{ $pur->qty }}</td>
                <td class="text-right">{{ number_format($pur->mrp, 2) }}</td>
                <td class="text-right">{{ $pc->tax_percentage }}</td>
                <td class="text-right">{{ number_format($pur->purchase_price, 2) }}</td>
                <td class="text-right">{{ number_format(($pur->tax_amount * $pur->qty) / 2, 2) }}</td>
                <td class="text-right">{{ number_format(($pur->tax_amount * $pur->qty) / 2, 2) }}</td>
                <td class="text-right">{{ number_format($pur->total, 2) }}</td>
            </tr>
            @empty
            @endforelse
            <tr>
                <td colspan="6" class="text-right fw-bold">Total</td>
                <td class="text-right fw-bold">{{ number_format($pp, 2) }}</td>
                <td class="text-right fw-bold">{{ number_format($tax_tot, 2) }}</td>
                <td class="text-right fw-bold">{{ number_format($tax_tot, 2) }}</td>
                <td class="text-right fw-bold">{{ number_format($tot, 2) }}</td>
        </tbody>
    </table>
</body>

</html>