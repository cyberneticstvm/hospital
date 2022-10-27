<!DOCTYPE html>
<html>
<head>
    <title>Devi Eye Clinic & Opticians</title>
    <style>
        .table-bordered th, .table-bordered td{
            border: 1px solid #e6e6e6;
            font-size: 12px;
            padding: 5px;
        }
        .text-right{
            text-align: right;
        }
        hr{
            border-top: 1px dashed red;
            border-bottom: 0px;
        }
        .bold{
            font-weight: bold;
            font-size: 14px;
            color: #696969;
        }
        .fw-bold{
            font-weight: bold;
        }
        .bordered td, .bordered th{
            border: 1px solid #e6e6e6;
            padding: 5px;
        }
        .text-green, p{
            color: #00bdaa;
        }
        .br{
            margin: 10px 0px;
        }
        .signs th, .signs.td{
            /*border: 1px solid #000;*/
        }
    </style>
</head>
<body>
<center>
    <img src="./images/assets/Devi-Logo-Transparent.jpg" width="15%"/><br/>
</center>
<center><h5>PATIENT MEDICAL HISTORY</h5></center>
<table width="100%">
    <tr><td>Patient Name: {{ strtoupper($patient->patient_name) }}</td><td>Patient ID: {{ $patient->patient_id }}</td><td class="text-right">Age / Sex: {{ $patient->age }} / {{ strtoupper($patient->gender) }}</td></tr>
</table>
<hr />
@forelse($mrecords as $key => $mrecord)
    @php
        $reference = DB::table('patient_references')->where('id', $mrecord->mrn)->first();
        $branch = DB::table('branches')->find($reference->branch);
        $department = DB::table('departments')->find($reference->department_id);
        $doctor = DB::table('doctors')->find($reference->doctor_id);
        $sympt = explode(',', $mrecord->symptoms);
        $diag = explode(',', $mrecord->diagnosis);
        $symptoms = DB::table('symptoms')->whereIn('id', $sympt)->get();
        $diagnosis = DB::table('diagnosis')->whereIn('id', $diag)->get();
        $spectacle = DB::table('spectacles')->where('medical_record_id', $mrecord->id)->first();
        $tonometry = DB::table('tonometries')->where('medical_record_id', $mrecord->id)->first();
        $keratometry = DB::table('keratometries')->where('medical_record_id', $mrecord->id)->first();
        $ascan = DB::table('ascans')->where('medical_record_id', $mrecord->id)->first();
        $medicines = DB::table('patient_medicine_records as m')->leftJoin('products as p', 'm.medicine', '=', 'p.id')->leftJoin('medicine_types as t', 'p.medicine_type', 't.id')->select('p.product_name', 'm.qty', 'm.dosage', 'm.duration', 'm.notes', 't.name', DB::raw("CASE WHEN m.eye='L' THEN 'Left Eye Only' WHEN m.eye='R' THEN 'Right Eye Only' ELSE 'Both' END AS eye"))->where('m.medical_record_id', $mrecord->id)->get();
        
        $labc = DB::table('lab_clinics as lc')->leftJoin('lab_types as lt', 'lc.lab_type_id', '=', 'lt.id')->where('lc.medical_record_id', $mrecord->id)->select('lt.lab_type_name', 'lc.notes', 'lc.lab_result', 'lc.tested_from')->get();
        $labr = DB::table('lab_radiologies as lr')->leftJoin('lab_types as lt', 'lr.lab_type_id', '=', 'lt.id')->where('lr.medical_record_id', $mrecord->id)->select('lt.lab_type_name', 'lr.notes', 'lr.lab_result', 'lr.tested_from')->get();

        $retina_od = DB::table('patient_medical_records_retina')->select('retina_img', 'description')->where('medical_record_id', $mrecord->id)->where('retina_type', 'od')->get()->toArray();
        $retina_os = DB::table('patient_medical_records_retina')->select('retina_img', 'description')->where('medical_record_id', $mrecord->id)->where('retina_type', 'os')->get()->toArray();
        $v_od_1 = DB::table('patient_medical_records_vision')->select(DB::raw("IFNULL(group_concat(description), 'Na') as names"))->where('img_type', 'vision_od_img1')->where('medical_record_id', $mrecord->id)->value('names');
        $v_os_1 = DB::table('patient_medical_records_vision')->select(DB::raw("IFNULL(group_concat(description), 'Na') as names"))->where('img_type', 'vision_os_img1')->where('medical_record_id', $mrecord->id)->value('names');
        $v_od_2 = DB::table('patient_medical_records_vision')->select(DB::raw("IFNULL(group_concat(description), 'Na') as names"))->where('img_type', 'vision_od_img2')->where('medical_record_id', $mrecord->id)->value('names');
        $v_os_2 = DB::table('patient_medical_records_vision')->select(DB::raw("IFNULL(group_concat(description), 'Na') as names"))->where('img_type', 'vision_os_img2')->where('medical_record_id', $mrecord->id)->value('names');
        $v_od_3 = DB::table('patient_medical_records_vision')->select(DB::raw("IFNULL(group_concat(description), 'Na') as names"))->where('img_type', 'vision_od_img3')->where('medical_record_id', $mrecord->id)->value('names');
        $v_os_3 = DB::table('patient_medical_records_vision')->select(DB::raw("IFNULL(group_concat(description), 'Na') as names"))->where('img_type', 'vision_os_img3')->where('medical_record_id', $mrecord->id)->value('names');
        $v_od_4 = DB::table('patient_medical_records_vision')->select(DB::raw("IFNULL(group_concat(description), 'Na') as names"))->where('img_type', 'vision_od_img4')->where('medical_record_id', $mrecord->id)->value('names');
        $v_os_4 = DB::table('patient_medical_records_vision')->select(DB::raw("IFNULL(group_concat(description), 'Na') as names"))->where('img_type', 'vision_os_img4')->where('medical_record_id', $mrecord->id)->value('names');

        $sel_1_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_1_od))->value('names');
        $sel_1_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_1_os))->value('names');
        $sel_2_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_2_od))->value('names');
        $sel_2_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_2_os))->value('names');
        $sel_3_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_3_od))->value('names');
        $sel_3_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_3_os))->value('names');
        $sel_4_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_4_od))->value('names');
        $sel_4_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_4_os))->value('names');
        $sel_5_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_5_od))->value('names');
        $sel_5_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_5_os))->value('names');
        $sel_6_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_6_od))->value('names');
        $sel_6_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_6_os))->value('names');
        $sel_7_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_7_od))->value('names');
        $sel_7_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_7_os))->value('names');
        $sel_8_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_8_od))->value('names');
        $sel_8_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_8_os))->value('names');
        $sel_9_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_9_od))->value('names');
        $sel_9_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_9_os))->value('names');
        $sel_10_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_10_od))->value('names');
        $sel_10_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_10_os))->value('names');
        $sel_11_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_11_od))->value('names');
        $sel_11_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_11_os))->value('names');
        $sel_12_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_12_od))->value('names');
        $sel_12_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_12_os))->value('names');
        $sel_13_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_13_od))->value('names');
        $sel_13_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_13_os))->value('names');
        $sel_14_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_14_od))->value('names');
        $sel_14_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_14_os))->value('names');
        $sel_15_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_15_od))->value('names');
        $sel_15_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_15_os))->value('names');
        $sel_16_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_16_od))->value('names');
        $sel_16_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_16_os))->value('names');
        $sel_17_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_17_od))->value('names');
        $sel_17_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_17_os))->value('names');
        $sel_18_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_18_od))->value('names');
        $sel_18_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_18_os))->value('names');
        $sel_19_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_19_od))->value('names');
        $sel_19_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_19_os))->value('names');
        $sel_20_od = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_20_od))->value('names');
        $sel_20_os = DB::table('vision_extras')->select(DB::raw('group_concat(name) as names'))->whereIn('id', explode(',', $mrecord->sel_20_os))->value('names');
    @endphp
    <table width="100%" class="">
        <tr><td>Date: {{ ($mrecord->created_at) ? date('d/M/Y', strtotime($mrecord->created_at)) : '' }}</td><td class="text-right">Medical Record Number: {{ $mrecord->id }}</td></tr>
        <tr><td>Doctor Name: {{ $doctor->doctor_name }} ({{ $department->department_name }})</td><td class="text-right">Branch: {{ $branch->branch_name }}</td></tr>
    </table>
    @if(!$symptoms->isEmpty() || $mrecord->symptoms_other)
    <p> Symptoms</p>
        @foreach($symptoms as $sympt)
            {{ $sympt->symptom_name }}, 
        @endforeach 
        {{ ($mrecord->symptoms_other) ? $mrecord->symptoms_other : '' }}
    <br/>
    @endif
    @if($mrecord->history)
    <p> Patient History</p>
        {{ ($mrecord->history) ? $mrecord->history : '' }}
    <br/>
    @endif
    @if($spectacle && ($spectacle->re_dist_sph || $spectacle->re_dist_cyl || $spectacle->re_dist_axis || $spectacle->re_dist_add || $spectacle->vbr || $spectacle->re_near_va || $mrecord->va_od || $spectacle->le_dist_sph || $spectacle->le_dist_cyl || $spectacle->le_dist_axis || $spectacle->le_dist_add || $spectacle->vbl || $spectacle->le_near_va || $mrecord->va_os))
    <p>Vision</p>
    <table width="100%" class="table-bordered" cellspacing="0" cellpadding="0">
        <thead class=""><tr><th>&nbsp;</th><th>SPH</th><th>CYL</th><th>AXIS</th><th>ADD</th><th></th><th>VA</th><th></th></tr></thead>
        <tbody>
            <tr>
                <td class="">RE/OD</td>                                            
                <td>{{ ($spectacle) ? $spectacle->re_dist_sph : '0.00' }}</td>
                <td>{{ ($spectacle) ? $spectacle->re_dist_cyl : '0.00' }}</td>
                <td>{{ ($spectacle) ? $spectacle->re_dist_axis : '0.00' }}</td>
                <td>{{ ($spectacle) ? $spectacle->re_dist_add : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->re_near_va : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->re_dist_va : '--' }}</td>
                <td>{{ ($mrecord->va_od) ? $mrecord->va_od : '--' }}</td>
            </tr>
            <tr>
                <td class="">LE/OS</td>                                            
                <td>{{ ($spectacle) ? $spectacle->le_dist_sph : '0.00' }}</td>
                <td>{{ ($spectacle) ? $spectacle->le_dist_cyl : '0.00' }}</td>
                <td>{{ ($spectacle) ? $spectacle->le_dist_axis : '0.00' }}</td>
                <td>{{ ($spectacle) ? $spectacle->le_dist_add : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->le_near_va : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->le_dist_va : '--' }}</td>
                <td>{{ ($mrecord->va_os) ? $mrecord->va_os : '--' }}</td>
            </tr>
        </tbody>
    </table>
    <br/>
    @endif
    
    @if($keratometry)
        <p>Keratometry</p>
        <table width="100%" class="table-bordered" cellspacing="0" cellpadding="0">
            <thead><tr><th></th><th>K1 (A)</th><th>AXIS</th><th>K2 (A)</th><th>AXIS</th><th>K1 (M)</th><th>AXIS</th><th>K2 (M)</th><th>AXIS</th></tr></thead><tbody>
                <tr>
                    <td>OD</td>
                    <td>{{ $keratometry->k1_od_auto }}</td>
                    <td>{{ $keratometry->k1_od_axis_a }}</td>
                    <td>{{ $keratometry->k2_od_auto }}</td>
                    <td>{{ $keratometry->k2_od_axis_a }}</td>
                    <td>{{ $keratometry->k1_od_manual }}</td>
                    <td>{{ $keratometry->k1_od_axis_m }}</td>
                    <td>{{ $keratometry->k2_od_manual }}</td>
                    <td>{{ $keratometry->k2_od_axis_m }}</td>
                </tr>
                <tr>
                    <td>OS</td>
                    <td>{{ $keratometry->k1_os_auto }}</td>
                    <td>{{ $keratometry->k1_os_axis_a }}</td>
                    <td>{{ $keratometry->k2_os_auto }}</td>
                    <td>{{ $keratometry->k2_os_axis_a }}</td>
                    <td>{{ $keratometry->k1_os_manual }}</td>
                    <td>{{ $keratometry->k1_os_axis_m }}</td>
                    <td>{{ $keratometry->k2_os_manual }}</td>
                    <td>{{ $keratometry->k2_os_axis_m }}</td>
                </tr>
            </tbody>
        </table>
        <br/>
    @endif
    
    @if($tonometry)
        <p>Tonometry</p>
        <table style="width:50%; margin:0 auto;" class="table-bordered" cellspacing="0" cellpadding="0">
            <thead><tr><th></th><th>NCT</th><th>AT</th></tr></thead><tbody>
                <tr>
                    <td>OD</td>
                    <td>{{ $tonometry->nct_od }} {{ ($tonometry->nct_od) ? 'mmHg' : '' }}</td>
                    <td>{{ $tonometry->at_od }} {{ ($tonometry->at_od) ? 'mmHg' : '' }}</td>
                </tr>
                <tr>
                    <td>OS</td>
                    <td>{{ $tonometry->nct_os }} {{ ($tonometry->nct_os) ? 'mmHg' : '' }}</td>
                    <td>{{ $tonometry->at_os }} {{ ($tonometry->at_os) ? 'mmHg' : '' }}</td>
                </tr>
                <tr>
                    <td>Time</td>
                    <td>{{ ($tonometry->nct_od || $tonometry->at_od) ? $tonometry->nct_time : '' }}</td>
                    <td>{{ ($tonometry->nct_os || $tonometry->at_os) ? $tonometry->at_time : '' }}</td>
                </tr>
            </tbody>
        </table>
        <br/>
    @endif    
    
    @if($ascan)
        <p>A-Scan</p>
        <table style="width:50%; margin:0 auto;" class="table-bordered" cellspacing="0" cellpadding="0">
            <thead><tr><th>AXL<th>ACD</th><th>LENS</th><th>A-CONST.</th><th>IOL</th></tr></thead><tbody>
                <tr>
                    <td>{{ $ascan->axl }}</td>
                    <td>{{ $ascan->acd }}</td>
                    <td>{{ $ascan->lens }}</td>
                    <td>{{ $ascan->a_constant }}</td>
                    <td>{{ $ascan->iol_power }}</td>
                </tr>
            </tbody>
        </table>
        <br/>
    @endif
    @if($v_od_1 != 'Na' || $v_os_1 != 'Na')
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td width="50%">
                    @if($v_od_1 != 'Na')
                    <img src="{{ $mrecord->vision_od_img1 }}" width="50%" alt=""/><br/>{{ $v_od_1 }}
                    @endif            
                </td>
                <td>
                    @if($v_os_1 != 'Na')
                    <img src="{{ $mrecord->vision_os_img1 }}" width="50%" alt=""/><br/>{{ $v_os_1 }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    <br/>
    @endif
    <table width="100%" cellspacing="0" cellpadding="0" class="table-bordered">
        <tbody>
        <tr><th>Signs</th><th>OD</th><th>OS</th></tr>
            @if($sel_1_od || $sel_1_os)
            <tr><td>Appearance</td><td>{{ $sel_1_od }}</td><td>{{ $sel_1_os }}</td></tr>
            @endif
            @if($sel_2_od || $sel_2_os)
            <tr><td>Extraocular Movements</td><td>{{ $sel_2_od }}</td><td>{{ $sel_2_os }}</td></tr>
            @endif
            @if($sel_3_od || $sel_3_os)
            <tr><td>Orbital Margins</td><td>{{ $sel_3_od }}</td><td>{{ $sel_3_os }}</td></tr>
            @endif
            @if($sel_4_od || $sel_4_os)
            <tr><td>LID and Adnexa</td><td>{{ $sel_4_od }}</td><td>{{ $sel_4_os }}</td></tr>
            @endif
            @if($sel_5_od || $sel_5_os)
            <tr><td>Conjunctiva</td><td>{{ $sel_5_od }}</td><td>{{ $sel_5_os }}</td></tr>
            @endif
            @if($sel_6_od || $sel_6_os)
            <tr><td>Sclera</td><td>{{ $sel_6_od }}</td><td>{{ $sel_6_os }}</td></tr>
            @endif
            @if($sel_7_od || $sel_7_os)
            <tr><td>Cornea</td><td>{{ $sel_7_od }}</td><td>{{ $sel_7_os }}</td></tr>
            @endif
            @if($sel_8_od || $sel_8_os)
            <tr><td>Anterior Chamber</td><td>{{ $sel_8_od }}</td><td>{{ $sel_8_os }}</td></tr>
            @endif
            @if($sel_9_od || $sel_9_os)
            <tr><td>Iris</td><td>{{ $sel_9_od }}</td><td>{{ $sel_9_os }}</td></tr>
            @endif
            @if($sel_10_od || $sel_10_os)
            <tr><td>Pupil</td><td>{{ $sel_10_od }}</td><td>{{ $sel_10_os }}</td></tr>
            @endif
            @if($sel_11_od || $sel_11_os)
            <tr><td>Lens</td><td>{{ $sel_11_od }}</td><td>{{ $sel_11_os }}</td></tr>
            @endif
            @if($sel_12_od || $sel_12_os)
            <tr><td>AVR</td><td>{{ $sel_12_od }}</td><td>{{ $sel_12_os }}</td></tr>
            @endif
            @if($sel_13_od || $sel_13_os)
            <tr><td>Fundus</td><td>{{ $sel_13_od }}</td><td>{{ $sel_13_os }}</td></tr>
            @endif
            @if($sel_14_od || $sel_14_os)
            <tr><td>Media</td><td>{{ $sel_14_od }}</td><td>{{ $sel_14_os }}</td></tr>
            @endif
            @if($sel_15_od || $sel_15_os)
            <tr><td>Disc Margins</td><td>{{ $sel_15_od }}</td><td>{{ $sel_15_os }}</td></tr>
            @endif
            @if($sel_16_od || $sel_16_os)
            <tr><td>CDR</td><td>{{ $sel_16_od }}</td><td>{{ $sel_16_os }}</td></tr>
            @endif
            @if($sel_17_od || $sel_17_os)
            <tr><td>NRR</td><td>{{ $sel_17_od }}</td><td>{{ $sel_17_os }}</td></tr>
            @endif
            @if($sel_18_od || $sel_18_os)
            <tr><td>AV Ratio & Bloodvessels</td><td>{{ $sel_18_od }}</td><td>{{ $sel_18_os }}</td></tr>
            @endif
            @if($sel_19_od || $sel_19_os)
            <tr><td>FR</td><td>{{ $sel_19_od }}</td><td>{{ $sel_19_os }}</td></tr>
            @endif
            @if($sel_20_od || $sel_20_os)
            <tr><td>Background Retina & Periphery</td><td>{{ $sel_20_od }}</td><td>{{ $sel_20_os }}</td></tr>
            @endif
        </tbody>
    </table>
    <br><br>
    @if($v_od_2 != 'Na' || $v_os_2 != 'Na')
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td width="50%">
                    @if($v_od_2 != 'Na')
                    <img src="{{ $mrecord->vision_od_img2 }}" width="50%" alt=""/><br/>{{ $v_od_2 }}
                    @endif            
                </td>
                <td>
                    @if($v_os_2 != 'Na')
                    <img src="{{ $mrecord->vision_os_img2 }}" width="50%" alt=""/><br/>{{ $v_os_2 }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    </br>
    @endif
    @if($v_od_3 != 'Na' || $v_os_3 != 'Na')
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td width="50%">
                    @if($v_od_3 != 'Na')
                    <img src="{{ $mrecord->vision_od_img3 }}" width="50%" alt=""/><br/>{{ $v_od_3 }}
                    @endif            
                </td>
                <td>
                    @if($v_os_3 != 'Na')
                    <img src="{{ $mrecord->vision_os_img3 }}" width="50%" alt=""/><br/>{{ $v_os_3 }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    </br>
    @endif
    @if($v_od_4 != 'Na' || $v_os_4 != 'Na')
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td width="50%">
                    @if($v_od_4 != 'Na')
                    <img src="{{ $mrecord->vision_od_img4 }}" width="50%" alt=""/><br/>{{ $v_od_4 }}
                    @endif            
                </td>
                <td>
                    @if($v_os_4 != 'Na')
                    <img src="{{ $mrecord->vision_os_img4 }}" width="50%" alt=""/><br/>{{ $v_os_4 }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    </br>
    @endif
    @if($mrecord->gonio_od_top || $mrecord->gonio_od_left || $mrecord->gonio_od_right || $mrecord->gonio_od_bottom || $mrecord->gonio_os_top || $mrecord->gonio_os_left || $mrecord->gonio_os_right || $mrecord->gonio_os_bottom || $mrecord->gonio_od || $mrecord->gonio_os)
    <p>Gonioscopy</p>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td style="background:url(./images/assets/x-png-30.png); background-repeat: no-repeat; background-size: auto;" width="50%">
                    <table cellpadding="0" cellspacing="0" width="100%" style="border: 0px;">
                        <tr><td colspan="3" class="text-center" style="padding-bottom:75px; border: 0px;">{{ $mrecord->gonio_od_top }}</td></tr>
                        <tr><td style="border: 0px;">{{ $mrecord->gonio_od_left }}</td><td style="border: 0px;"></td><td class="text-right" style="border: 0px;">{{ $mrecord->gonio_od_right }}</td></tr>
                        <tr><td colspan="3" class="text-center" style="padding-top:75px; border: 0px;">{{ $mrecord->gonio_od_bottom }}</td></tr>
                    </table>
                </td>
                <td style="background:url(./images/assets/x-png-30.png); background-repeat: no-repeat; background-size: auto; height:20%" width="50%">
                    <table cellpadding="0" cellspacing="0" width="100%" style="border: 0px;">
                        <tr><td colspan="3" class="text-center" style="padding-bottom:75px; border: 0px;">{{ $mrecord->gonio_os_top }}</td></tr>
                        <tr><td style="border: 0px;">{{ $mrecord->gonio_os_left }}</td><td style="border: 0px;"></td><td class="text-right" style="border: 0px;">{{ $mrecord->gonio_os_right }}</td></tr>
                        <tr><td colspan="3" class="text-center" style="padding-top:75px; border: 0px;">{{ $mrecord->gonio_os_bottom }}</td></tr>
                    </table>
                </td>
            </tr>
            <tr><td class="text-center">{{ $mrecord->gonio_od }}</td><td class="text-center">{{ $mrecord->gonio_os }}</td></tr>
        </tbody>
    </table>
    </br>
    @endif
    @if($mrecord->signs)
    <p> Signs</p>
        {{ $mrecord->signs }}
    </br>
    @endif
    @if(($retina_od && $retina_od[0]->retina_img) || ($retina_os && $retina_os[0]->retina_img))
    @php
        $retinas = (count($retina_od) > count($retina_os)) ? $retina_od : $retina_os;
    @endphp
    <table border="0" width="100%" cellspacing="1px" cellpadding="">
        <tbody>
            @forelse($retinas as $key => $retina)
                <tr>
                    @if($retina_od && !empty($retina_od[$key]))
                        <td width="50%"><img src="{{ ($retina_od && $retina_od[$key]->retina_img) ? 'https://hospital.speczone.net/public/storage/'.$retina_od[$key]->retina_img : '' }}" width='100%' /><br>{{ ($retina_od && $retina_od[$key]->description) ? $retina_od[$key]->description : '' }}</td>
                        @else
                        <td width="50%"></td>
                        @endif
                        @if($retina_os && !empty($retina_os[$key]))
                        <td width="50%"><img src="{{ ($retina_os && $retina_os[$key]->retina_img) ?  'https://hospital.speczone.net/public/storage/'.$retina_os[$key]->retina_img : '' }}" width='100%' /><br>{{ ($retina_os && $retina_os[$key]->description) ? $retina_os[$key]->description : '' }}</td>
                        @else
                        <td width="50%"></td>
                    @endif
                </tr>
            @empty
            @endforelse
        </tbody>
    </table>
    <br>
    @endif
    @if(count($diagnosis) > 0)
        <p> Diagnosis</p>
        @foreach($diagnosis as $diag)
            {{ $diag->diagnosis_name }}, 
        @endforeach
    <br>
    @endif
    @if($mrecord->doctor_recommondations)
        <p>Doctor Recommendations</p>
        {{ $mrecord->doctor_recommondations }}
    <br>
    @endif
    @if(count($medicines) > 0)
    <p> Medicine / Lab Advised</p>
    <table width="100%" class="table-bordered" cellspacing="0" cellpadding="0">
    <thead><th>SL No.</th><th>Type</th><th>Medicine Name</th><th>Dosage</th><th>Duration</th><th>Eye</th><th>Qty</th><th>Notes</th></thead>
        <tbody>
        @php $c = 1 @endphp
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
    <br>
    @endif
    <table width="100%" class="no-border">
        <tr><th>{{ ($mrecord->is_patient_admission == 'sel') ? '' : 'Admission Advised:' }}</th><th>{{ ($mrecord->is_patient_surgery == 'sel') ? '' : 'Surgery Advised:' }} </th><th class="text-right">Review Date: </th></tr>
        <tr><th>{{ ($mrecord->is_patient_admission == 'sel') ? '' : $mrecord->is_patient_admission }}</th><th>{{ ($mrecord->is_patient_surgery == 'sel') ? '' : $mrecord->is_patient_surgery }}</th><th class="text-right">{{ ($mrecord->review_date) ? date('d/M/Y', strtotime($mrecord->review_date)) : '' }}</th></tr>
    </table>
    <br />
    <hr />
    <br />
@empty
@endforelse
</body>
</html>