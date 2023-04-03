<!DOCTYPE html>
<html>
<head>
    <title>Devi Eye Clinic & Opticians</title>
    <style>
        .text-blue{
            color: blue;
        }
        .text-medium{
            font-size: 12px;
        }
        .text-small{
            font-size: 10px;
        }
        .text-large{
            font-size: 15px;
        }
        table tr td{
            height: 25px;
            padding-left: 5px;
        }
        .bordered th, .bordered td{
            border: 1px solid #000;
        }
        .text-right{
            text-align: right;
        }
        .fw-bold{
            font-weight: bold;
        }
        .text-center{
            text-align: center;
        }
    </style>
</head>
<body>
    <center>
    <img src="./images/assets/Devi-Logo-Transparent.jpg" height='100' width='115'/>
    <p>{{ $psc->branches->branch_name }}, {{ $psc->branches->address }}, {{ $psc->branches->contact_number }}</p>
    <p>DL Number: RLF21KL2022000540, RLF20KL2022000543</p>
    <h3>Surgery Bill</h3>
    </center>
    <table width="100%" cellpadding='0' cellspacing='0' class="text-large bordered">
        <tr>
            <td>MR ID</td><td>{{ $psc->medical_record_id }}</td>
            <td>Patient ID</td><td>{{ $psc->patient->patient_id }}</td>
            <td>Name/Age/Gender</td><td>{{ $psc->patient->patient_name }} / {{ $psc->patient->age }} / {{ $psc->patient->gender }}</td>
        </tr>
        <tr>
            <td>Address</td><td colspan="2">{{ $psc->patient->address }}</td><td></td><td colspan="2"></td>
        </tr>
    </table>
    <br>
    <table width="100%" border="1" cellpadding='1' cellspacing='0' class="text-large">
    <tr>
        <th width="10%">SL No</th><th width="70%">Consumable Name</th><th class="text-center">Qty</th><th>Price</th><th>Total</th>
    </tr>
    @php $c = 1; @endphp
    @foreach($psc->psclist as $key => $val)
    <tr>
        <td>{{ $c++ }}</th><td>{{ $val->consumable->name }}</td><td class="text-center">{{ $val->qty }}</td><td class="text-right">{{ $val->cost }}</td><td class="text-right">{{ $val->total }}</td>
    </tr>
    @endforeach
    <tr><td colspan="4" class="text-right fw-bold">Total</td><td class="text-right fw-bold">{{ $psc->psclist()->sum('total') }}</td></tr>
    </table>
    <p class='text-right'>Printed On: {{ date('d/M/Y H:i:A') }}</p>
    <p class='text-right'>Authorized Signatory</p>
</body>
</html>