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

        /*.signs th, .signs td{
            border: 1px solid #000;
        }*/
        tbody td {
            border: 1px solid #e6e6e6;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
            font-size: 14px;
            color: #696969;
        }

        table.no-border {
            border: 0px;
        }

        .text-green,
        p {
            color: #00bdaa;
        }

        .tbl {
            border-style: none;
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
    <center>
        <h5>PATIENT MEDICAL RECORD (MRN: {{ $record->mrn }})</h5>
    </center>
    <table width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td>Patient Name</td>
                <td>{{ $patient->patient_name }}</td>
                <td>Patient ID</td>
                <td>{{ $patient->patient_id }}</td>
                <td>AGE / SEX</td>
                <td>{{ $patient->age }} {{ ($patient->new_born_baby == 1) ? 'Months' : '' }} / {{ $patient->gender }}</td>
            </tr>
            <tr>
                <td>Address</td>
                <td colspan="3">{{ $patient->address }}</td>
                <td>Optometrist</td>
                <td>{{ ($spectacle && $spectacle->updateduser) ? $spectacle->updateduser->name : '' }}</td>
            </tr>
            <tr>
                <td>Doctor Name</td>
                <td>{{ $doctor->doctor_name }}</td>
                <td>Medical Record Number</td>
                <td>{{ $record->id }}</td>
                <td>Date</td>
                <td>{{ ($record->created_at) ? date('d/M/Y h:i A', strtotime($record->created_at)) : '' }}</td>
            </tr>
        </tbody>
    </table>
    @if(!$symptoms->isEmpty() || $record->symptoms_other)
    <p>Symptoms</p>
    @foreach($symptoms as $sympt)
    {{ $sympt->symptom_name }},
    @endforeach
    {{ $record->symptoms_other }}
    <br>
    @endif

    @if($record->history)
    <p> Patient History</p>
    {{ $record->history }}
    <br>
    @endif
    @if($record->allergic_drugs)
    <p> Allergic to Drugs</p>
    {{ ($record->allergic_drugs) ? $record->allergic_drugs : '' }}
    <br>
    @endif
    @if($spectacle && ($spectacle->re_dist_sph || $spectacle->re_dist_cyl || $spectacle->re_dist_axis || $spectacle->re_dist_add || $spectacle->vbr || $spectacle->re_near_va || $record->va_od || $spectacle->le_dist_sph || $spectacle->le_dist_cyl || $spectacle->le_dist_axis || $spectacle->le_dist_add || $spectacle->vbl || $spectacle->le_near_va || $record->va_os || $spectacle->vbr || $spectacle->vbl))
    <p>Vision</p>
    <table width="100%" cellspacing="0" cellpadding="0">
        <thead class="">
            <tr>
                <th>&nbsp;</th>
                <th>SPH</th>
                <th>CYL</th>
                <th>AXIS</th>
                <th>ADD</th>
                <th></th>
                <th>VA</th>
                <th></th>
                <th>UCV</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center fw-bold">RE/OD</td>
                <td>{{ ($spectacle) ? $spectacle->re_dist_sph : '0.00' }}</td>
                <td>{{ ($spectacle) ? $spectacle->re_dist_cyl : '0.00' }}</td>
                <td>{{ ($spectacle) ? $spectacle->re_dist_axis : '0.00' }}</td>
                <td>{{ ($spectacle) ? $spectacle->re_dist_add : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->re_near_va : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->re_dist_va : '--' }}</td>
                <td>{{ ($record->va_od) ? $record->va_od : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->vbr : '--' }}</td>
            </tr>
            <tr>
                <td class="text-center fw-bold">LE/OS</td>
                <td>{{ ($spectacle) ? $spectacle->le_dist_sph : '0.00' }}</td>
                <td>{{ ($spectacle) ? $spectacle->le_dist_cyl : '0.00' }}</td>
                <td>{{ ($spectacle) ? $spectacle->le_dist_axis : '0.00' }}</td>
                <td>{{ ($spectacle) ? $spectacle->le_dist_add : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->le_near_va : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->le_dist_va : '--' }}</td>
                <td>{{ ($record->va_os) ? $record->va_os : '--' }}</td>
                <td>{{ ($spectacle) ? $spectacle->vbl : '--' }}</td>
            </tr>
        </tbody>
    </table>
    @endif
    <br>
    @if($keratometry)
    <p>Keratometry</p>
    <table width="100%" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th></th>
                <th>K1 (A)</th>
                <th>AXIS</th>
                <th>K2 (A)</th>
                <th>AXIS</th>
                <th>K1 (M)</th>
                <th>AXIS</th>
                <th>K2 (M)</th>
                <th>AXIS</th>
            </tr>
        </thead>
        <tbody>
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
    <br>
    @endif
    @if($tonometry)
    <div>
        <p>Tonometry</p>
        <table width="50%" style="margin:0 auto;" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th></th>
                    <th>NCT</th>
                    <th>AT</th>
                </tr>
            </thead>
            <tbody>
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
                    <td>{{ ($tonometry->nct_od > 0 || $tonometry->nct_os > 0) ? $tonometry->nct_time : '' }}</td>
                    <td>{{ ($tonometry->at_od > 0 || $tonometry->at_os > 0) ? $tonometry->at_time : '' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    @endif
    @if($ascan)
    <div>
        <p>A-Scan</p>
        <table width="50%" cellspacing="0" cellpadding="0" style="margin:0 auto;">
            <thead>
                <tr>
                    <th></th>
                    <th>AXL
                    <th>ACD</th>
                    <th>LENS</th>
                    <th>A-CONST.</th>
                    <th>IOL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>OD</td>
                    <td>{{ $ascan->od_axl }}</td>
                    <td>{{ $ascan->od_acd }}</td>
                    <td>{{ $ascan->od_lens }}</td>
                    <td>{{ $ascan->od_a_constant }}</td>
                    <td>{{ $ascan->od_iol_power }}</td>
                </tr>
                <tr>
                    <td>OS</td>
                    <td>{{ $ascan->os_axl }}</td>
                    <td>{{ $ascan->os_acd }}</td>
                    <td>{{ $ascan->os_lens }}</td>
                    <td>{{ $ascan->os_a_constant }}</td>
                    <td>{{ $ascan->os_iol_power }}</td>
                </tr>
            </tbody>
        </table>
        <p>A-CONST & IOL</p>
        <table class="bordered" width="50%" cellspacing="0" cellpadding="0" style="margin:0 auto;">
            <thead class="text-center">
                <tr>
                    <th colspan="2">A.CONST</th>
                    <th colspan="2">IOL</th>
                </tr>
                <tr>
                    <th>OD</th>
                    <th>OS</th>
                    <th>OD</th>
                    <th>OS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $ascan->aconst_od1 }}</td>
                    <td>{{ $ascan->aconst_os1 }}</td>
                    <td>{{ $ascan->iol_od1 }}</td>
                    <td>{{ $ascan->iol_os1 }}</td>
                </tr>
                <tr>
                    <td>{{ $ascan->aconst_od2 }}</td>
                    <td>{{ $ascan->aconst_os2 }}</td>
                    <td>{{ $ascan->iol_od2 }}</td>
                    <td>{{ $ascan->iol_os2 }}</td>
                </tr>
                <tr>
                    <td>{{ $ascan->aconst_od3 }}</td>
                    <td>{{ $ascan->aconst_os3 }}</td>
                    <td>{{ $ascan->iol_od3 }}</td>
                    <td>{{ $ascan->iol_os3 }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    @endif
    @if($pachymetry)
    <div>
        <p>Pachymetry</p>
        <table style="width:75%; margin:0 auto;" class="table-bordered" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th class="text-center" colspan="4">OD</th>
                    <th class="text-center" colspan="3">OS</th>
                </tr>
                <tr>
                    <th></th>
                    <th>IOP</th>
                    <th>CCT</th>
                    <th>CIOP</th>
                    <th>IOP</th>
                    <th>CCT</th>
                    <th>CIOP</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>AVG</td>
                    <td class="text-center">{{ $pachymetry->od_iop }}</td>
                    <td class="text-center">{{ $pachymetry->od_cct }}</td>
                    <td class="text-center">{{ $pachymetry->od_ciop }}</td>
                    <td class="text-center">{{ $pachymetry->os_iop }}</td>
                    <td class="text-center">{{ $pachymetry->os_cct }}</td>
                    <td class="text-center">{{ $pachymetry->os_ciop }}</td>
                </tr>
            </tbody>
        </table>
        <br>
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <td width="50%">
                        {{ ($pachymetry->img1) ? 'PCY (OD)' : '' }}
                        <img src="{{ ($pachymetry->img1) ? './storage/'.$pachymetry->img1 : '' }}" width="100%" /><br />
                        {{ $pachymetry->img1_value }}
                    </td>
                    <td>
                        {{ ($pachymetry->img2) ? 'PCY (OS)' : '' }}
                        <img src="{{ ($pachymetry->img2) ? './storage/'.$pachymetry->img2 : '' }}" width="100%" /><br />
                        {{ $pachymetry->img2_value }}
                    </td>
                </tr>
                <tr>
                    <td width="50%">
                        {{ ($pachymetry->img3) ? 'ACA (OD)' : '' }}
                        <img src="{{ ($pachymetry->img3) ? './storage/'.$pachymetry->img3 : '' }}" width="100%" /><br />
                        {{ $pachymetry->img3_value }}
                    </td>
                    <td>
                        {{ ($pachymetry->img4) ? 'ACA (OS)' : '' }}
                        <img src="{{ ($pachymetry->img4) ? './storage/'.$pachymetry->img4 : '' }}" width="100%" /><br />
                        {{ $pachymetry->img4_value }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    @endif
    @if($v_od_1 != 'Na' || $v_os_1 != 'Na')
    <br>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td width="50%">
                    @if($v_od_1 != 'Na')
                    <img src="{{ $record->vision_od_img1 }}" width="50%" alt="" /><br />{{ $v_od_1 }}
                    @endif
                </td>
                <td>
                    @if($v_os_1 != 'Na')
                    <img src="{{ $record->vision_os_img1 }}" width="50%" alt="" /><br />{{ $v_os_1 }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    @endif
    <br>
    <table width="100%" cellspacing="0" cellpadding="0">
        <tbody class="signs">
            <tr>
                <th>Signs</th>
                <th>OD</th>
                <th>OS</th>
            </tr>
            @if($sel_1_od || $sel_1_os)
            <tr>
                <td>Appearance</td>
                <td>{{ $sel_1_od }}</td>
                <td>{{ $sel_1_os }}</td>
            </tr>
            @endif
            @if($sel_2_od || $sel_2_os)
            <tr>
                <td>Extraocular Movements</td>
                <td>{{ $sel_2_od }}</td>
                <td>{{ $sel_2_os }}</td>
            </tr>
            @endif
            @if($sel_3_od || $sel_3_os)
            <tr>
                <td>Orbital Margins</td>
                <td>{{ $sel_3_od }}</td>
                <td>{{ $sel_3_os }}</td>
            </tr>
            @endif
            @if($sel_4_od || $sel_4_os)
            <tr>
                <td>LID and Adnexa</td>
                <td>{{ $sel_4_od }}</td>
                <td>{{ $sel_4_os }}</td>
            </tr>
            @endif
            @if($sel_5_od || $sel_5_os)
            <tr>
                <td>Conjunctiva</td>
                <td>{{ $sel_5_od }}</td>
                <td>{{ $sel_5_os }}</td>
            </tr>
            @endif
            @if($sel_6_od || $sel_6_os)
            <tr>
                <td>Sclera</td>
                <td>{{ $sel_6_od }}</td>
                <td>{{ $sel_6_os }}</td>
            </tr>
            @endif
            @if($sel_7_od || $sel_7_os)
            <tr>
                <td>Cornea</td>
                <td>{{ $sel_7_od }}</td>
                <td>{{ $sel_7_os }}</td>
            </tr>
            @endif
            @if($sel_8_od || $sel_8_os)
            <tr>
                <td>Anterior Chamber</td>
                <td>{{ $sel_8_od }}</td>
                <td>{{ $sel_8_os }}</td>
            </tr>
            @endif
            @if($sel_9_od || $sel_9_os)
            <tr>
                <td>Iris</td>
                <td>{{ $sel_9_od }}</td>
                <td>{{ $sel_9_os }}</td>
            </tr>
            @endif
            @if($sel_10_od || $sel_10_os)
            <tr>
                <td>Pupil</td>
                <td>{{ $sel_10_od }}</td>
                <td>{{ $sel_10_os }}</td>
            </tr>
            @endif
            @if($sel_11_od || $sel_11_os)
            <tr>
                <td>Lens</td>
                <td>{{ $sel_11_od }}</td>
                <td>{{ $sel_11_os }}</td>
            </tr>
            @endif
            @if($sel_12_od || $sel_12_os)
            <tr>
                <td>AVR</td>
                <td>{{ $sel_12_od }}</td>
                <td>{{ $sel_12_os }}</td>
            </tr>
            @endif
            @if($sel_13_od || $sel_13_os)
            <tr>
                <td>Fundus</td>
                <td>{{ $sel_13_od }}</td>
                <td>{{ $sel_13_os }}</td>
            </tr>
            @endif
            @if($sel_14_od || $sel_14_os)
            <tr>
                <td>Media</td>
                <td>{{ $sel_14_od }}</td>
                <td>{{ $sel_14_os }}</td>
            </tr>
            @endif
            @if($sel_15_od || $sel_15_os)
            <tr>
                <td>Disc Margins</td>
                <td>{{ $sel_15_od }}</td>
                <td>{{ $sel_15_os }}</td>
            </tr>
            @endif
            @if($sel_16_od || $sel_16_os)
            <tr>
                <td>CDR</td>
                <td>{{ $sel_16_od }}</td>
                <td>{{ $sel_16_os }}</td>
            </tr>
            @endif
            @if($sel_17_od || $sel_17_os)
            <tr>
                <td>NRR</td>
                <td>{{ $sel_17_od }}</td>
                <td>{{ $sel_17_os }}</td>
            </tr>
            @endif
            @if($sel_18_od || $sel_18_os)
            <tr>
                <td>AV Ratio & Bloodvessels</td>
                <td>{{ $sel_18_od }}</td>
                <td>{{ $sel_18_os }}</td>
            </tr>
            @endif
            @if($sel_19_od || $sel_19_os)
            <tr>
                <td>FR</td>
                <td>{{ $sel_19_od }}</td>
                <td>{{ $sel_19_os }}</td>
            </tr>
            @endif
            @if($sel_20_od || $sel_20_os)
            <tr>
                <td>Background Retina & Periphery</td>
                <td>{{ $sel_20_od }}</td>
                <td>{{ $sel_20_os }}</td>
            </tr>
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
                    <img src="{{ $record->vision_od_img2 }}" width="50%" alt="" /><br />{{ $v_od_2 }}
                    @endif
                </td>
                <td>
                    @if($v_os_2 != 'Na')
                    <img src="{{ $record->vision_os_img2 }}" width="50%" alt="" /><br />{{ $v_os_2 }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    @endif
    @if($v_od_3 != 'Na' || $v_os_3 != 'Na')
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td width="50%">
                    @if($v_od_3 != 'Na')
                    <img src="{{ $record->vision_od_img3 }}" width="50%" alt="" /><br />{{ $v_od_3 }}
                    @endif
                </td>
                <td>
                    @if($v_os_3 != 'Na')
                    <img src="{{ $record->vision_os_img3 }}" width="50%" alt="" /><br />{{ $v_os_3 }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    @endif
    @if($v_od_4 != 'Na' || $v_os_4 != 'Na')
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td width="50%">
                    @if($v_od_4 != 'Na')
                    <img src="{{ $record->vision_od_img4 }}" width="50%" alt="" /><br />{{ $v_od_4 }}
                    @endif
                </td>
                <td>
                    @if($v_os_4 != 'Na')
                    <img src="{{ $record->vision_os_img4 }}" width="50%" alt="" /><br />{{ $v_os_4 }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    @endif
    @if($record->gonio_od_top || $record->gonio_od_left || $record->gonio_od_right || $record->gonio_od_bottom || $record->gonio_os_top || $record->gonio_os_left || $record->gonio_os_right || $record->gonio_os_bottom || $record->gonio_od || $record->gonio_os)
    <p>Gonioscopy</p>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td style="background:url(./images/assets/x-png-30.png); background-repeat: no-repeat; background-size: auto;" width="50%">
                    <table cellpadding="0" cellspacing="0" width="100%" style="border: 0px;">
                        <tr>
                            <td colspan="3" class="text-center" style="padding-bottom:75px; border: 0px;">{{ $record->gonio_od_top }}</td>
                        </tr>
                        <tr>
                            <td style="border: 0px;">{{ $record->gonio_od_left }}</td>
                            <td style="border: 0px;"></td>
                            <td class="text-right" style="border: 0px;">{{ $record->gonio_od_right }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-center" style="padding-top:75px; border: 0px;">{{ $record->gonio_od_bottom }}</td>
                        </tr>
                    </table>
                </td>
                <td style="background:url(./images/assets/x-png-30.png); background-repeat: no-repeat; background-size: auto; height:20%" width="50%">
                    <table cellpadding="0" cellspacing="0" width="100%" style="border: 0px;">
                        <tr>
                            <td colspan="3" class="text-center" style="padding-bottom:75px; border: 0px;">{{ $record->gonio_os_top }}</td>
                        </tr>
                        <tr>
                            <td style="border: 0px;">{{ $record->gonio_os_left }}</td>
                            <td style="border: 0px;"></td>
                            <td class="text-right" style="border: 0px;">{{ $record->gonio_os_right }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-center" style="padding-top:75px; border: 0px;">{{ $record->gonio_os_bottom }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="text-center">{{ $record->gonio_od }}</td>
                <td class="text-center">{{ $record->gonio_os }}</td>
            </tr>
        </tbody>
    </table>
    <br>
    @endif
    @if($record->signs)
    <p> Signs</p>
    {{ $record->signs }}
    <br>
    @endif
    @if(($retina_od && $retina_od[0]->retina_img) || ($retina_os && $retina_os[0]->retina_img))
    @php
    $retinas = (count($retina_od) > count($retina_os)) ? $retina_od : $retina_os;
    @endphp
    <table boredr="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
            @forelse($retinas as $key => $retina)
            <tr>
                @if($retina_od && !empty($retina_od[$key]))
                <td width="50%"><img src="{{ ($retina_od && $retina_od[$key]->retina_img) ? 'https://emr.devihospitals.in/public/storage/'.$retina_od[$key]->retina_img : '' }}" width='100%' /><br>{{ ($retina_od && $retina_od[$key]->description) ? $retina_od[$key]->description : '' }}</td>
                @else
                <td width="50%"></td>
                @endif
                @if($retina_os && !empty($retina_os[$key]))
                <td width="50%"><img src="{{ ($retina_os && $retina_os[$key]->retina_img) ?  'https://emr.devihospitals.in/public/storage/'.$retina_os[$key]->retina_img : '' }}" width='100%' /><br>{{ ($retina_os && $retina_os[$key]->description) ? $retina_os[$key]->description : '' }}</td>
                @else
                <td width="50%"></td>
                @endif
            </tr>
            @empty
            @endforelse
        </tbody>
    </table>
    @endif
    @if(count($diagnosis) > 0)
    <p> Diagnosis</p>
    @foreach($diagnosis as $diag)
    {{ $diag->diagnosis_name }},
    @endforeach
    <br>
    @endif
    @if($onotes && $onotes->notes)
    <p>Operation Notes</p>
    {!! nl2br($onotes->notes) !!}
    <br>
    @endif
    @if($record->doctor_recommondations)
    <p>Doctor Recommendations</p>
    {{ $record->doctor_recommondations }}
    <br>
    @endif
    @if(count($medicines) > 0)
    <p> Medicine / Lab Advised</p>
    <table width="100%" cellspacing="0" cellpadding="0">
        <thead>
            <th>SL No.</th>
            <th>Type</th>
            <th>Medicine Name</th>
            <th>Dosage</th>
            <th>Duration</th>
            <th>Eye</th>
            <th>Qty</th>
            <th>Notes</th>
        </thead>
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
        <tr>
            <th>{{ ($record->is_patient_admission == 'sel') ? '' : 'Admission Advised:' }}</th>
            <th>{{ ($record->is_patient_surgery == 'sel') ? '' : 'Surgery Advised:' }} </th>
            <th class="text-right">{{ ($record->review_date) ? 'Review Date:' : '' }}</th>
        </tr>
        <tr>
            <th>{{ ($record->is_patient_admission == 'sel') ? '' : $record->is_patient_admission }}</th>
            <th>{{ ($record->is_patient_surgery == 'sel') ? '' : $record->is_patient_surgery }}</th>
            <th class="text-right">{{ ($record->review_date) ? date('d/M/Y', strtotime($record->review_date)) : '' }}</th>
        </tr>
    </table>
    <br>
    <div class="text-right">{{ $doctor->doctor_name }}<br />{{ $doctor->designation }}<br />Reg. No: {{ $doctor->reg_no }}</div>
    <pre />
    <img src="data:image/png;base64, {!! $qrcode !!}">
    <p class="text-small">To book an appointment, scan this QR code.</p>
</body>

</html>