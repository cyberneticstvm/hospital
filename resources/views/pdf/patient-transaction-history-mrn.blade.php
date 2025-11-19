<!DOCTYPE html>
<html>

<head>
    <title>Devi Eye Clinic & Opticians</title>
    <style>
        table {
            border: 1px solid #e6e6e6;
            font-size: 15px;
        }

        thead {
            border-bottom: 1px solid #e6e6e6;
        }

        table thead th,
        table thead th {
            padding: 10px;
        }

        .bordered td {
            border: 1px solid #e6e6e6;
        }

        .text-right {
            text-align: right;
        }

        .text-end {
            text-align: right;
        }

        table tbody td,
        table tfoot th {
            padding: 7px;
        }

        .fw-bold {
            font-weight: bold;
        }

        .text-danger {
            color: red;
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
    <br />
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
                <td>Contact Number</td>
                <td>{{ $patient->mobile_number }}</td>
                <td>Address</td>
                <td colspan="3">{{ $patient->address }}</td>
            </tr>
            <tr>
                <td>Medical Record No.</td>
                <td>{{ $mrn->id }}</td>
                <td>Date</td>
                <td colspan="3">{{ $mrn->created_at->format('d/M/Y') }}</td>
            </tr>
        </tbody>
    </table>
    <br />
    @php
    $fee = App\Helper\Helper::getOwedTotalForStatement($mrn->id);
    $paid = App\Helper\Helper::getPaidTotal($mrn->id);
    $tot = array_sum($fee);
    @endphp
    <center>
        <h5>STATEMENT OF ACCOUNTS DETAILED</h5>
    </center>
    <table class="bordered" width="100%" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th width="10%">SLNo.</th>
                <th width="30%">Particulars</th>
                <th width="15%">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Registration</td>
                <td class="text-end">{{ number_format($fee['registration'], 2) }}</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Consultation</td>
                <td class="text-end">{{ number_format($fee['consultation'], 2) }}</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Procedures</td>
                <td class="text-end">{{ number_format($fee['procedure'], 2) }}</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Certificates</td>
                <td class="text-end">{{ number_format($fee['certificate'], 2) }}</td>
            </tr>
            <tr>
                <td>5</td>
                <td>Pharmacy</td>
                <td class="text-end">{{ number_format($fee['pharmacy'], 2) }}</td>
            </tr>
            <tr>
                <td>6</td>
                <td>Vision</td>
                <td class="text-end">{{ number_format($fee['vision'], 2) }}</td>
            </tr>
            <tr>
                <td>7</td>
                <td>Lab Clinic</td>
                <td class="text-end">{{ number_format($fee['clinic'], 2) }}</td>
            </tr>
            <tr>
                <td>8</td>
                <td>Lab Radiology</td>
                <td class="text-end">{{ number_format($fee['radiology'], 2) }}</td>
            </tr>
            <tr>
                <td>9</td>
                <td>Surgery Medicine</td>
                <td class="text-end">{{ number_format($fee['surgerymed'], 2) }}</td>
            </tr>
            <tr>
                <td>10</td>
                <td>Surgery Consumables</td>
                <td class="text-end">{{ number_format($fee['surgeryconsumable'], 2) }}</td>
            </tr>
            <tr>
                <td>11</td>
                <td>PostOp Medicine</td>
                <td class="text-end">{{ number_format($fee['postop'], 2) }}</td>
            </tr>
            <tr>
                <td>12</td>
                <td>Pharmacy (Direct)</td>
                <td class="text-end">{{ number_format($fee['medicine'], 2) }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-end">Total</th>
                <th class="text-end">{{ number_format($tot, 2) }}</th>
            </tr>
            <tr>
                <th colspan="2" class="text-end">Paid</th>
                <th class="text-end">{{ number_format($paid, 2) }}</th>
            </tr>
            <tr>
                <th colspan="2" class="text-end">Balance</th>
                <th class="text-end text-danger">{{ number_format($tot - $paid, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</body>

</html>