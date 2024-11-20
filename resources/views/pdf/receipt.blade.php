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
        {{ $branch->address }}, Phone:
        {{ $branch->contact_number }}
    </center>
    <br />
    @php
    $reg_fee = ($reference->review == 'no') ? $patient->registration_fee : 0;
    $doc_fee = ($reference->doctor_fee > 0 && $reference?->rc_type != null && rc_number != null) ? 0 : $reference->doctor_fee;
    @endphp
    <table width="100%">
        <thead>
            <tr>
                <th text-align="center" colspan="4">REGISTRATION</th>
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
                <td>{{ $reference->medical_record_id }}</td>
            </tr>
            <tr>
                <td>DOCTOR NAME</td>
                <td>{{ $doctor->doctor_name }}</td>
                <td>RECEIPT DATE</td>
                <td>{{ ($reference->created_at) ? date('d/M/Y h:i:A', strtotime($reference->created_at)) : '' }}</td>
            </tr>
        </tbody>
    </table>
    <table class="bordered" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td width="10%">SLNo.</td>
                <td width="60%">Particulars</td>
                <td width="10%">Qty</td>
                <td width="10%">Amount</td>
                <td width="10%">Discount</td>
                <td width="10%">Total</td>
            </tr>
            <tr>
                <td>1</td>
                <td>Consultation Fee</td>
                <td class="text-right">1</td>
                <td class="text-right">{{ $reference->doctor_fee }}</td>
                <td class="text-right">{{ $doc_fee > 0 ? 0 : $doc_fee }}</td>
                <td class="text-right">{{ $doc_fee }}</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Registration Fee</td>
                <td class="text-right">1</td>
                <td class="text-right">{{ $reg_fee }}</td>
                <td class="text-right">0.00</td>
                <td class="text-right">{{ $reg_fee }}</td>
            </tr>
            @if($procedure)
            <tr>
                <td>3</td>
                <td>Procedures Fee ({{ $procedure->procs }})</td>
                <td class="text-right">1</td>
                <td class="text-right">{{ $procedure->fee + $procedure->discount }}</td>
                <td class="text-right">{{ $procedure->discount }}</td>
                <td class="text-right">{{ $procedure->fee }}</td>
            </tr>
            @endif
            <tr>
                <td colspan="5" class="text-right">Total</td>
                <td class="text-right">{{ number_format($reg_fee + $doc_fee + $procedure->fee, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>