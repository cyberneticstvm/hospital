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
        Registered Office: Maithanam, Varkala, Thiruvananthapuram. Phone: {{ $branch->contact_number }}<br>
        Branch: Thekkumbagom, Paravur, Kollam.<br>
        DL Number: RLF21KL2022000540, RLF20KL2022000543
    </center>
    <br />
    <table width="100%">
        <thead>
            <tr>
                <th text-align="center" colspan="4">MEDICINE PRESCRIPTION</th>
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
                <td>{{ $medical_record->id }}</td>
            </tr>
            <tr>
                <td>DOCTOR NAME</td>
                <td>{{ $doctor->doctor_name }}</td>
                <td>DATE</td>
                <td>{{ ($medical_record->created_at) ? date('d/M/Y h:i:A', strtotime($medical_record->created_at)) : '' }}</td>
            </tr>
        </tbody>
    </table>
    <br />
    <table class="bordered" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td>SLNo.</td>
                <td>TYPE</td>
                <td>MEDICINE</td>
                <td>DOSAGE</td>
                <td>DURATION</td>
                <td>EYE</td>
                <td>QTY</td>
                <td>NOTES</td>
            </tr>
            @php $c =1 @endphp
            @foreach($medicines as $medicine)
            <tr>
                <td>{{ $c++ }}</td>
                <td>{{ $medicine->name }}</td>
                <td>{{ $medicine->product_name }}</td>
                <td>{{ $medicine->dosage }}</td>
                <td>{{ $medicine->duration }}</td>
                <td>{{ $medicine->eye }}</td>
                <td class="text-right">{{ $medicine->qty }}</td>
                <td>{{ $medicine->notes }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br />
    <div class="text-right">{{ $doctor->doctor_name }}</div>
    <div class="text-right">{{ $doctor->designation }}</div>
</body>

</html>