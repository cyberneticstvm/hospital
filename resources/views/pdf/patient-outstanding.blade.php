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
        <thead><tr><th text-align="center" colspan="4">Receipt</th></tr></thead>
        <tbody>
            <tr>
                <td>Patient</td>
                <td>{{ $patient->patient_name }}</td>
                <td>Patient ID</td>
                <td>{{ $patient->patient_id }}</td>
            </tr>
            <tr>
                <td>MRN</td>
                <td>{{ $payment->medical_record_id }}</td>
                <td>Oder Date</td>
                <td>{{ date('d/M/Y', strtotime($payment->created_at)) }}</td>
            </tr>
        </tbody>
    </table>
    <br><br>
    <table width="100%" class="bordered" cellpadding="0" cellspacing="0">
        <thead><th>SL No.</th><th>Particulars</th><th>Amount</th></thead><tbody>
        <tr><td>1</td><td>Outstanding Payment</td><td>{{ $payment->amount }}</td></tr>
        <tr><td colspan="2" class="text-right fw-bold">Total</td><td class="text-right fw-bold">{{ $payment->amount }}</td>        
    </tbody></table>
</body>
</html>