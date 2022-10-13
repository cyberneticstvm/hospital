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
        .text-justify{
            text-align: justify;
        }
    </style>
</head>
<body>
    <center>
        <b>FORM 1-A</b><br/>
        <b>MEDICAL CERTIFICATE</b><br/>
        <b>[See Rules 5(1), (3), (7), 10(a), 14(b) and 18(d)]</b>        
    </center>
    [To be filled in by a registered medical practitioner appointed for the purpose by the State Government or person
    authorised in this behalf by the State Government referred to under sub-section (3) of Section 8.]<br/><br/>
    1. Name of the Applicant: <b>{{ $patient->patient_name }}</b><br/>
    2. Identification marks: (1) ................................................................................................<br/><br/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(2) .................................................................................................<br/>
    3.  <table class="no-border" width="100%">
        <tr><td width="5%">(a)</td><td width="80%">Does the applicant, to the best of your judgment, suffer from any defect of vision? If so, has it been corrected by suitable Spectacles?</td><td>Yes / No</td></tr>
        <tr><td width="5%">(b)</td><td width="80%"> Can the applicant, to the best of your judgment, readily distinguish the pigmentary colours, red and green?</td><td>Yes / No</td></tr>
        <tr><td width="5%">(c)</td><td width="80%">In your opinion, is he able to distinguish with his eyesight at a distance of 25 metres in good day light a motor car number plate?</td><td>Yes / No</td></tr>
        <tr><td width="5%">(d)</td><td width="80%">In your opinion, does the applicant suffer from a degree of deafness which would prevent his hearing the ordinary sound signals?</td><td>Yes / No</td></tr>
        <tr><td width="5%">(e)</td><td width="80%">In your opinion, does the applicant suffer from night blindness?</td><td>Yes / No</td></tr>
        <tr><td width="5%">(f)</td><td width="80%">Has the applicant any defect or deformity or loss of member which would interfere with the
        efficient performance of his duties as a driver? If so, give your reasons in detail.</td><td>Yes / No</td></tr>
        <tr><td width="5%">(g)</td><td width="80%">OPTIONAL<br/>(a) Blood Group of the applicant (if the applicant so desires that the information may be noted in his driving licence),<br/>(b) RH factor of the applicant (if the applicant so desires that the information may be noted in his driving licence).</td><td></td></tr>
        </table>
    <center>Declaration made by the applicant in Form-1 as to his physical fitness is attached</center>
    <div class="text-justify">I certify that I have personally examined the applicant. I also certify that while examining the applicant I have directed special attention to the distant vision and hearing ability, the condition of the arms, legs, hands and joints of both extremities of the candidate and to the best of my judgment, he is medically fit/not fit to hold a driving licence.<br/>
    The applicant is not medically fit to hold a licence for the following reasons:-</div>
    <table class="no-border" width="100%">
    <tr>
        <td>
            <table class="no-border" cellpadding="0" cellspacing="0" border="1px"><tr><td class="h-150 w-150 text-center"></td></tr></table>
        </td>
        <td class="w-40"></td>
        <td class="w-75">
            <p>Signature:</p>
            <p>1. Name and designation of the Medical Officer/Practitioner</p>
            <p class="text-center">Seal</p>
            <p>2. Registration Number of Medical Officer</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Date:</p>
        <td>
        <td class="w-75">
            <p>Signature or thumb impression of the candidate</p>
        </td>
    </tr>
    </table>
    <p>Note:- The medical officer shall affix his signature over the photograph affixed manner that part of his
    signature is upon the photograph and part on the certificate.</p>
    <center>
        <b>FORM 1</b><br/>
        <b>[See Rules 5(2)]</b><br/>
        <b>APPLICATION CUM -DECLARATION AS TO BE PHYSICAL FITNESS</b><br/><br/>
    </center>
    1. Name of the Applicant    : <b>{{ $patient->patient_name }}</b><br/><br/>
    2. Son/Wife/Daughter of     :<br/><br/>
    3. Permanent Address    :<br/><br/>
    4. Temporary Address/Official Address (if any)     :<br/><br/>
    5. (a) Date of Birth     :<br/>
    &nbsp;&nbsp;&nbsp;&nbsp;(b) Age on date of application     :<br/><br/>
    6. Identification Marks (1)     :<br/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(2)     :<br/><br/>
    <table class="no-border" width="100%">
    <tr><td width="5%">(a)</td><td width="80%">Do you suffer from epilepsy or from sudden attacks of loss of consciousness or giddiness from any cause?</td><td>Yes / No</td></tr>
    <tr><td width="5%">(b)</td><td width="80%"> Are you able to distinguish with each eye (or if you have held a driving licence to drive a motor vehicle for a period of not less than five years and if you have lost the sight of one eye after the said period of five years and if the application is for driving a light motor vehicle other than a transport vehicle fitted with an outside mirror on the steering wheel side) or with one eye, at a distance of 25 metres in good day light (with glasses, if worn) a motor car number plate? </td><td>Yes / No</td></tr>
    <tr><td width="5%">(c)</td><td width="80%">Have you lost either hand or foot or are you suffering from any defect of muscular power of either arm or leg?</td><td>Yes / No</td></tr>
    <tr><td width="5%">(d)</td><td width="80%">Can you readily distinguish the pigmentary colours, red and green?</td><td>Yes / No</td></tr>
    <tr><td width="5%">(e)</td><td width="80%">Do you suffer from night blindness?</td><td>Yes / No</td></tr>
    <tr><td width="5%">(f)</td><td width="80%">Are you so deaf so as to be unable to hear (and if the application is for driving a light motor vehicle, with or without hearing aid) the ordinary sound signal?</td><td>Yes / No</td></tr>
    <tr><td width="5%">(g)</td><td width="80%">Do you suffer from any other disease or disability likely to cause your driving of a motor vehicle to be a source of danger to the public, if so, give details. </td><td></td></tr>
    </table>
    <p>I hereby declare that, to the best of my knowledge and belief, the particulars given above and the declaration
    made therein are true.</p>
    <pre/><pre/>
    <div class="text-end">(Signature or thumb impression of the Applicant)</div>
    Note:-
    <p>1. An applicant who answers "Yes" to any of the questions (a), (c), (e), (f) and (g) or "No" to either of
        the questions (b) and (d) should amplify his answers with full particulars, and may be required to
        give further information relating thereto.</p>
    <p>2. This declaration is to be submitted invariably with medical certificate in Form 1 A. </p>
</body>
</html>