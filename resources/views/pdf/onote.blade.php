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
    <table width="100%">
        <thead>
            <tr>
                <th text-align="center" colspan="4">Operation Notes</th>
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
                <td>MR ID</td>
                <td>{{ $onote->medical_record_id }}</td>
            </tr>
            <tr>
                <td>PATIENT ADDRESS</td>
                <td colspan="3">{{ $patient->address }}</td>
            </tr>
            <tr>
                <td>DOCTOR NAME</td>
                <td>{{ $doctor?->doctor_name }}</td>
                <td>DATE</td>
                <td>{{ ($onote->created_at) ? date('d/M/Y h:i:A', strtotime($onote->created_at)) : '' }}</td>
            </tr>
        </tbody>
    </table>
    <br />
    <br />
    <table style="width: 100%; border: 0px">
        <tr>
            <th style="text-align: left;">Eye</th>
            <th style="text-align: left;">Surgeon</th>
            <th style="text-align: left;">Surgery Date</th>
            <th style="text-align: left;">Test Dose Time</th>
            <th style="text-align: left;">Test Dose Result</th>
            <th style="text-align: left;">Blood Pressure</th>
            <th style="text-align: left;">GRBS</th>
        </tr>
        <tr>
            <td>{{ $onote->eye }}</td>
            <td>{{ $onote->surgeond?->doctor_name }}</td>
            <td>{{ $onote->date_of_surgery?->format('d.M.Y') }}</td>
            <td>{{ $onote->test_dose_time?->format('h:i A') }}</td>
            <td>{{ $onote->test_dose_result }}</td>
            <td>{{ $onote->blood_pressure_mm }}/{{ $onote->blood_pressure_hg }} mmHg</td>
            <td>{{ $onote->grbs }} mg/dL</td>
        </tr>
    </table>
    <br />
    <br />
    <strong>Procedure</strong>
    <br />
    {!! nl2br($onote->procedures) !!}
    <br />
    <br />
    <strong>Procedure Details</strong>
    <br />
    {!! nl2br($onote->notes) !!}
    <br />
    <br />
    <strong>Post-operative Advice</strong>
    <br />
    {!! nl2br($onote->post_operative_advice) !!}
    <br />
    <br />
    <strong>Medications Prescribed</strong>
    <br />
    {!! nl2br($onote->medications_prescribed) !!}
    <br />
    <br />
    Surgeon<br />
    {{ $onote->surgeond?->doctor_name }}
</body>

</html>