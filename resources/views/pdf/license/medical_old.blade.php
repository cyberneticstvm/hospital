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
        .w-40{
            width: 50%;
        }
        .w-150{
            width: 150px;
        }
        .p-5 td, .p-5 th{
            padding: 5px;
        }
        .w-75{
            width: 75%;
        }
        .text-center{
            text-align: center;
        }
    </style>
</head>
<body>
    <center>
        <p><b>FORM 1-A</b></p>
        <p><b>[See Rules 5(1), (3), (7), 10(a), 14(d) and 18(d)]</b></p>
        <p><b>MEDICAL CERTIFICATE</b></p>
    </center>
    <br/><br/>
    <table class="no-border" width="100%">
    <tr>
        <td class="w-75"></td>
        <td>
            <table class="no-border" cellpadding="0" cellspacing="0" border="1px"><tr><td class="h-150 w-150 text-center">space for<br/>Passport size<br/>Photograph</td></tr></table>
        </td>
    </tr>
    </table>
    <p>[To be filled in by a registered medical practitioner appointed for the purpose by the State Government or person
    authorised in this behalf by the State Government referred to under sub-section (3) of Section 8.]</p>
    1. Name of the Applicant: <b>{{ $patient->patient_name }}</b><br/>
    2. Identification marks: <br/>
    (1): ...............................................................................................................................<br/><br/>
    (2): ...............................................................................................................................<br/>
    <p>Declaration</p>
    3.  <table class="no-border" width="100%">
        <tr><td width="5%">(a)</td><td width="80%">Does the applicant, to the best of your judgment, suffer from any defect of vision? If so, has it been corrected by suitable Spectacles?</td><td>Yes / No</td></tr>
        <tr><td width="5%">(b)</td><td width="80%"> Can the applicant, to the best of your judgment, readily distinguish the pigmentary colours, red and green?</td><td>Yes / No</td></tr>
        <tr><td width="5%">(c)</td><td width="80%">In your opinion, is he able to distinguish with his eyesight at a distance of 25 metres in good day light a motor car number plate?</td><td>Yes / No</td></tr>
        <tr><td width="5%">(d)</td><td width="80%">In your opinion, does the applicant suffer from a degree of deafness which would prevent his hearing the ordinary sound signals?</td><td>Yes / No</td></tr>
        <tr><td width="5%">(e)</td><td width="80%">In your opinion, does the applicant suffer from night blindness?</td><td>Yes / No</td></tr>
        <tr><td width="5%">(f)</td><td width="80%">Has the applicant any defect or deformity or loss of member which would interfere with the
        efficient performance of his duties as a driver? If so, give your reasons in detail.</td><td>Yes / No</td></tr>
        <tr><td width="5%">(g)</td><td width="80%">Optional<br/>(a) Blood Group of the applicant (if the applicant so desires that the information may be noted in his driving licence),<br/>(b) RH factor of the applicant (if the applicant so desires that the information may be noted in his driving licence).</td><td></td></tr>
        </table>
    <center><p><b>Declaration made by the applicant in Form-1 as to his physical fitness is attached</b></p></center><br/><br/>
    <center>
        <p><b>Certificate of Medical Fitness</b></p>
    </center>
    <p>I certify that:</p>
    (i) I have personally examined the applicant Shri/Smt/Kum <b>{{ $patient->patient_name }}</b><br/><br/>
    (ii) that while examining the applicant I have directed special attention to his/her distant vision;<br/><br/>
    (iii) while examining the applicant, I have directed special attention to his/her hearing ability, the condition of the
    arms, legs, hands and joints of both extremities of the applicant; and<br/><br/>
    (iv) I have personally examined the applicant for reaction time, side vision and glare recovery, (applicable in case of
    persons applying for a licence to drive goods carriage carrying goods of dangerous or hazardous nature to human life).<br/>
    <p>And, therefore, I certify that, to the best of my judgment, he is medically fit/not fit to hold a driving licence.</p>
    <p>The applicant is not medically fit to hold a licence for the following reasons:-</p>
    <pre>

    <table class="no-border" width="100%">
    <tr>
        <td class="w-40">
            
        <td>
        <td>
            <p>Signature:</p>
            <p>1. Name and designation of the Medical Officer/Practitioner</p><br/>
            <p class="text-center">Seal</p><br/>
            <p>2. Registration Number of Medical Officer</p><br/>
        </td>
    </tr>
    <tr>
        <td class="w-40">
            <p>Date:</p>
        <td>
        <td>
            <p>Signature or thumb impression of the candidate</p>
        </td>
    </tr>
    </table>
    <p>Note 1. - The medical officer shall affix his signature over the photograph affixed in such a manner that part of his
    signature is upon the photograph and part on the certificate.]</p>
    <p>2. Dumb persons without deafness may be granted a valid certificate of driving licence for non-transport vehicle.</p>
</body>
</html>