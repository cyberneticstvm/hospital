<!DOCTYPE html>
<html>
<head>
    <title>Devi Eye Clinic & Opticians</title>
    <style>
        table{
            border: 1px solid #e6e6e6;
            font-size: 15px;
        }
        thead{
            border-bottom: 1px solid #e6e6e6;
        }
        table thead th, table tbody td, table thead th{
            padding: 10px;
        }
        .bordered td{
            border: 1px solid #e6e6e6;
        }
        .text-right{
            text-align: right;
        }
        .text-end{
            text-align: right;
        }
        table tfoot td{
            padding: 10px;
        }
        .fw-bold{
            font-weight: bold;
        }
    </style>
</head>
<body>
    <center>
        <img src="./images/assets/Devi-Logo-Transparent.jpg" width="15%"/><br/>
            {{ $branch->address }}, Phone:
            {{ $branch->contact_number }}
    </center>
    <br/>
    <table width="100%" class="bordered" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td>Patient Name</td>
                <td>{{ strtoupper($patient->patient_name) }}</td>
                <td>Age / Gender</td>
                <td>{{ $patient->age }} / {{ $patient->gender }}</td>
                <td>Patient ID</td>
                <td>{{ $patient->patient_id }}</td>
            </tr>
            <tr>
                <td>Contact Number</td><td>{{ $patient->mobile_number }}</td>
                <td>Address</td><td colspan="3">{{ $patient->address }}</td>
            </tr>
        </tbody>
    </table>
    <br/>
    <center><h5>STATEMENT OF ACCOUNTS</h5></center>
    <table class="bordered" width="100%" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th width="10%">SLNo.</th>
                <th width="30%">Medical Record No.</th>
                <th width="15%">Date</th>
                <th width="15%">Amount Charged</th>
                <th width="15%">Amount Paid</th>
                <th width="15%">Balance</th>
            </tr>
        </thead>
        <tbody>
            @php $owedtot = 0; $baltot = 0; $paidtot = 0; @endphp
            @forelse($mrns as $key => $mrn)
                @php
                    $owed = App\Helper\Helper::getOwedTotal($mrn->id);
                    $paid = App\Helper\Helper::getPaidTotal($mrn->id);
                    $owedtot += $owed; $paidtot += $paid; $baltot += $owed - $paid;
                @endphp
                <tr>
                    <td>{{ $key + 1}}</td>
                    <td>{{ $mrn->id }}</td>
                    <td>{{ $mrn->created_at->format('d/M/Y') }}</td>
                    <td class="text-end">{{  number_format($owed, 2) }}</td>
                    <td class="text-end">{{  number_format($paid, 2) }}</td>
                    <td class="text-end">{{ number_format($owed-$paid, 2) }}</td>
                </tr>
            @empty
            @endforelse
        </tbody>
        <tfoot><tr><td colspan="3" class="text-end fw-bold">Total</td><td class="text-end fw-bold">{{  number_format($owedtot, 2) }}</td><td class="text-end fw-bold">{{ number_format($paidtot, 2) }}</td><td class="text-end fw-bold text-danger">{{  number_format($baltot, 2) }}</td></tr></tfoot>
    </table>
</body>
</html>