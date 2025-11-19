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
        @if(Helper::subdomain() == 'emrsas')
        <img src="./images/assets/devi-sas-logo.png" width="35%" /><br />
        @else
        <img src="./images/assets/Devi-Logo-Transparent.jpg" width="15%" /><br />
        @endif

        {{ $branch->address }}, Phone:
        {{ $branch->contact_number }}
    </center>
    <br />
    <table width="100%">
        <thead>
            <tr>
                <th text-align="center" colspan="4">CERTIFICATE RECEIPT</th>
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
                <td>MR.ID</td>
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
                <td width="80%">Particulars</td>
                <td width="10%">Qty</td>
                <td width="10%">Amount</td>
            </tr>
            @php $c =1; $tot = 0; @endphp
            @forelse($cert_details as $key => $cert)
            <tr>
                <td>{{ $c++ }}</td>
                <td>{{ $cert->name }}</td>
                <td class="text-right">1</td>
                <td class="text-right">{{ $cert->fee }}</td>
            </tr>
            @php $tot += $cert->fee; @endphp
            @empty
            @endforelse
            <tr>
                <td colspan="3" class="text-right">Total</td>
                <td class="text-right">{{ number_format($tot, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>