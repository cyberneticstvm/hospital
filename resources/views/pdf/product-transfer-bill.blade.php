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
        <thead><tr><th text-align="center" colspan="4">PRODUCT TRANSFER ON {{ $transfer->tdate }}</th></tr></thead>
        <tbody>
            <tr>
                <td>From Branch</td>
                <td>{{ $transfer->from_branch }}</td>
                <td>To Branch</td>
                <td>{{ $transfer->to_branch }}</td>
            </tr>
            <tr>
                <td>Transfer No.</td>
                <td>{{ $transfer->id }}</td>
                <td>Transfer note</td>
                <td>{{ $transfer->transfer_note }}</td>
            </tr>
        </tbody>
    </table>
    <br><br>
    <table width="100%" class="bordered" cellpadding="0" cellspacing="0">
        <thead><th>SL No.</th><th>Product</th><th>Batch</th><th>Qty</th></thead><tbody>
        @php $c = 1; @endphp
        @forelse($tdetails as $key => $tr)
        <tr>
            <td>{{ $c++ }}</td>
            <td>{{ $tr->product_name }}</td>
            <td>{{ $tr->batch_number }}</td>
            <td class="text-right">{{ $tr->qty }}</td>
        </tr>
        @empty
        @endforelse      
    </tbody></table>
</body>
</html>