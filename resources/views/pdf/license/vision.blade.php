<!DOCTYPE html>
<html>
<head>
    <title>Vision Certificate</title>
    <style>
        .text-end{
            text-align: right;
        }
        .h-150{
            height: 150px;
        }
        .w-50{
            width: 50%;
        }
        .w-150{
            width: 150px;
        }
        .p-5 td, .p-5 th{
            padding: 5px;
        }
    </style>
</head>
<body>
    <center>
        <p><b>CERTIFICATE FOR VISUAL STANDARDS FOR DRIVING</b></p>
        <i>(see instructions overleaf before filling up the certificate)</i>
    </center>
    <br/><br/>
    <p>I have examined Shri/Smt <strong>{{ $patient->patient_name }}</strong> aged <strong>{{ $patient->age }}</strong> and his/her visual standards are as follows:</p>
    <table class="no-border" width="100%">
    <tr>
        <td class="w-50">
            <p>Photograph of the candidate</p>
            <i>(To be signed upon by the Ophthalmologist)</i>
        <td>
        <td>
            <table class="no-border" cellpadding="0" cellspacing="0" border="1px"><tr><td class="h-150 w-150">&nbsp;</td></tr></table>
        </td>
    </tr>
    </table>
    <ol type="I">
        <li>Visual Acuity</li><br/>
        <table class="no-border p-5" cellpadding="0" cellspacing="0" border="1px" width="100%">
            <thead><tr><th>Visual Acuity</th><th>A<br/>Unaided</th><th>B<br/>Corrected</th><th>Sph</th><th>Cyl</th><th>Axis</th><th>C<br/>Binocular Corrected</th></tr></thead>
            <tbody>
                <tr><td>RE</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                <tr><td>LE</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            </tbody>
        </table><br/>
        <li>Night blindness ........................................................................................</li><br/>
        <li>Squint .................................................................................................</li><br/>
        <li>Field(Degrees)Horizontal .....................................Vertical...................................</li><br/>
        <li>Fundus ................................RE..............................LE................................</li><br/>
    </ol>
    <p>Any other significant ocular morbidity ............................................................................</p>
    <p>Candidate is Fit/Unfit to drive a Category I/II vehicle.</p>
    <p>Unfit due to criteria ............................................................................. mentioned above.</p>
    <p>(Category-I means Non Transport Vehicles which include Motor Cycles, Motor Cars, etc. specified as such
    in Central Government Notification No.S.O.1248(E)dated 5th November 2004 as non-transport vehicles)</p>
    <p>(Category-II means Transport vehicles which include Autorickshaws, Taxis, Stage carriages, Contract
    Carriages, Goods carriages, Private Service Vehicles etc. specified as such in the said Notification.)</p><br/>
    <table class="no-border" width="100%">
    <tr>
        <td class="w-50">
            <p>Signature of the candidate:</p>
            <p>Place:</p>
            <p>Date:</p>
        <td>
        <td>
            <p>Signature of Ophthalmologist</p>
            <p>Seal</p>
        </td>
    </tr>
    </table>
</body>
</html>