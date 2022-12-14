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
        .bordered td, .bordered th{
            border: 1px solid #e6e6e6;
        }
        .text-right{
            text-align: right;
        }
        .fw-bold{
            font-weight: bold;
        }
    </style>
</head>
<body>
    <center>
        <img src="./images/assets/Devi-Logo-Transparent.jpg" width="15%"/><br/>
    </center>
    <br/>
    <table width="100%" class="bordered" cellpadding="0" cellspacing="0">
        <thead><tr><th text-align="center" colspan="4">PURCHASE BILL ON {{ date('d/M/Y', strtotime($purchase->delivery_date)) }}</th></tr></thead>
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
        <thead><th>SL No.</th><th>Product</th><th>Batch</th><th>Qty</th><th>MRP</th><th>Price</th><th>Total</th></thead><tbody>
        @php $c = 1; @endphp
        @forelse($purchases as $key => $pur)
        <tr>
            <td>{{ $c++ }}</td>
            <td>{{ $pur->product_name }}</td>
            <td>{{ $pur->batch_number }}</td>
            <td class="text-right">{{ $pur->qty }}</td>
            <td class="text-right">{{ number_format($pur->mrp, 2) }}</td>
            <td class="text-right">{{ number_format($pur->price, 2) }}</td>
            <td class="text-right">{{ number_format($pur->total, 2) }}</td>
        </tr>
        @empty
        @endforelse
        <tr><td colspan="6" class="text-right fw-bold">Total</td><td class="text-right fw-bold">{{ number_format($tot, 2) }}</td>        
    </tbody></table>
</body>
</html>