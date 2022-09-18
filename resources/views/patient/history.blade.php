@extends("templates.base")

@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Patient Medical History</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-sm-6">Patient Name: <h5 class="text-primary">{{ $patient->patient_name }}</h5></div>
                            <div class="col-sm-6">Patient ID: <h5 class="text-primary">{{ $patient->patient_id }}</h5></div>
                        </div>
                    </div>
                </div>
            </div>
            @foreach($mrecords as $mrecord)
            @php 
                $doctor = DB::table('doctors')->find($mrecord->doctor_id);
                $sympt = explode(',', $mrecord->symptoms);
                $diag = explode(',', $mrecord->diagnosis);
                $symptoms = DB::table('symptoms')->whereIn('id', $sympt)->get();
                $diagnosis = DB::table('diagnosis')->whereIn('id', $diag)->get();
                $spectacle = DB::table('spectacles')->where('medical_record_id', $mrecord->id)->first();
                $retina_od = DB::table('patient_medical_records_retina')->where('medical_record_id', $mrecord->id)->where('retina_type', 'od')->get();
                $retina_os = DB::table('patient_medical_records_retina')->where('medical_record_id', $mrecord->id)->where('retina_type', 'os')->get();
                $medicine_record = DB::table('patient_medicine_records as m')->leftJoin('products as p', 'm.medicine', '=', 'p.id')->where('m.medical_record_id', $mrecord->id)->select('p.product_name', 'm.dosage', 'm.qty', 'm.notes')->get();
                $labc = DB::table('lab_clinics as lc')->leftJoin('lab_types as lt', 'lc.lab_type_id', '=', 'lt.id')->where('lc.medical_record_id', $mrecord->id)->select('lt.lab_type_name', 'lc.notes', 'lc.lab_result', 'lc.tested_from')->get();
                $labr = DB::table('lab_radiologies as lr')->leftJoin('lab_types as lt', 'lr.lab_type_id', '=', 'lt.id')->where('lr.medical_record_id', $mrecord->id)->select('lt.lab_type_name', 'lr.notes', 'lr.lab_result', 'lr.tested_from')->get();
            @endphp
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Medical Record ID: {{ $mrecord->id }}, Date: {{ ($mrecord->created_at) ? date('d/M/Y', strtotime($mrecord->created_at)) : '' }}</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-sm-12">Doctor Name: <h5 class="text-primary">{{ $doctor->doctor_name }}</h5></div>
                        </div>
                        <div class="row mt-3">
                            <strong>Symptoms</strong>
                            <div class="col-sm-12">
                                @foreach($symptoms as $sympt)
                                    {{ $sympt->symptom_name }}, 
                                @endforeach
                            </div>
                        </div>
                        <div class="row mt-3">
                            <strong>Diagnosis</strong>
                            <div class="col-sm-12">
                            @foreach($diagnosis as $diag)
                                {{ $diag->diagnosis_name }}, 
                            @endforeach
                            </div>
                        </div>
                        <div class="row mt-3">                            
                            <div class="col-sm-6">
                                <strong>Vision</strong>
                                <table class="table table-bordered">
                                    <thead class="text-center"><tr><th>&nbsp;</th><th><!--VB--></th><th><!--SPH--></th><th><!--CYL--></th><th><!--AXIS--></th><th><!--ADD--></th><th><!--VA--></th></tr></thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center fw-bold">RE/OD</td>                                            
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->re_dist_sph : '0.00' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->re_dist_cyl : '0.00' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->re_dist_axis : '0.00' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->re_dist_add : '--' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->vbr : '--' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->re_near_va : '--' }}" placeholder="0" readonly="true" /></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center fw-bold">LE/OS</td>                                            
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->le_dist_sph : '0.00' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->le_dist_cyl : '0.00' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->le_dist_axis : '0.00' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->le_dist_add : '--' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->vbl : '--' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->le_near_va : '--' }}" placeholder="0" readonly="true" /></td>
                                        </tr>
                                        <!--<tr><td colspan="4" class="fw-bold text-center">IOP-OD</td><td colspan="3" class="fw-bold text-center">IOP-OS</td></tr>
                                        <tr><td colspan="4" class="fw-bold text-center"><input class="form-control form-control-md" type="text" maxlength="7" value="{{ ($spectacle) ? $spectacle->re_iop : '' }}" placeholder="0" readonly="true" /></td><td colspan="3" class="fw-bold text-center"><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->le_iop : '' }}" maxlength="7" placeholder="0" readonly="true" /></td></tr>-->
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-6">
                                <strong>Biometry</strong>
                                <table class="table table-bordered" style="">
                                    <thead class="text-center">
                                        <tr><th></th><th>K1 (Auto)</th><th>K2 (Auto)</th><th>K1 (Manual)</th><th>K2 (Manual)</th><th>AXL</th></tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                            <td class="text-center fw-bold">OD</td>
                                            <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->bm_k1_od_a : '--' }}" name="k1_od_auto" maxlength="6" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->bm_k2_od_a : '--' }}" name="k2_od_auto" maxlength="6" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->bm_k1_od_m : '--' }}" name="k1_od_manual" maxlength="6" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->bm_k2_od_m : '--' }}" name="k2_od_manual" maxlength="6" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" maxlength="7" name="axl" value="{{ ($spectacle) ? $spectacle->bm_od_axl : '--' }}" placeholder="0" readonly="true" /></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center fw-bold">OS</td>
                                            <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->bm_k1_os_a : '--' }}" name="k1_os_auto" maxlength="6" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->bm_k2_os_a : '--' }}" name="k2_os_auto" maxlength="6" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->bm_k1_os_m : '--' }}" name="k1_os_manual" maxlength="6" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->bm_k2_os_m : '--' }}" name="k2_os_manual" maxlength="6" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" maxlength="7" name="axl" value="{{ ($spectacle) ? $spectacle->bm_os_axl : '--' }}" placeholder="0" readonly="true" /></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @php
                        $sel_1_od = explode(',', $mrecord->sel_1_od);
                        $sel_1_os = explode(',', $mrecord->sel_1_os);
                        $appearance_od = DB::table('vision_extras')->whereIn('id', $sel_1_od)->get();
                        $appearance_os = DB::table('vision_extras')->whereIn('id', $sel_1_os)->get();
                        $sel_2_od = explode(',', $mrecord->sel_2_od);
                        $sel_2_os = explode(',', $mrecord->sel_2_os);
                        $em_od = DB::table('vision_extras')->whereIn('id', $sel_2_od)->get();
                        $em_os = DB::table('vision_extras')->whereIn('id', $sel_2_os)->get();
                        $sel_3_od = explode(',', $mrecord->sel_3_od);
                        $sel_3_os = explode(',', $mrecord->sel_3_os);
                        $om_od = DB::table('vision_extras')->whereIn('id', $sel_3_od)->get();
                        $om_os = DB::table('vision_extras')->whereIn('id', $sel_3_os)->get();
                        @endphp
                        <div class="row mt-3">
                            <div class="col-sm-12 table-responsive">
                                <table class="table table-bordered">
                                    <thead><tr><th></th><th>OD</th><th>OS</th></tr></thead>
                                    <tbody>
                                        <tr>
                                            <td>Appearance</td>
                                            <td>
                                                @foreach($appearance_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($appearance_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr><td>Extraocular Movements</td>
                                            <td>
                                                @foreach($em_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($em_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr><td>Orbital Margins</td>
                                            <td>
                                                @foreach($om_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($om_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-6 text-center">
                                <strong>Vision OD</strong><br>
                                <img src="{{ $mrecord->vision_od_img1 }}" alt=""/>
                            </div>
                            <div class="col-sm-6 text-center">
                                <strong>Vision OS</strong><br>
                                <img src="{{ $mrecord->vision_os_img1 }}" alt=""/>
                            </div>
                        </div>
                        @php
                        $sel_4_od = explode(',', $mrecord->sel_4_od);
                        $sel_4_os = explode(',', $mrecord->sel_4_os);
                        $la_od = DB::table('vision_extras')->whereIn('id', $sel_4_od)->get();
                        $la_os = DB::table('vision_extras')->whereIn('id', $sel_4_os)->get();
                        @endphp
                        <div class="row mt-3">
                            <div class="col-sm-12 table-responsive">
                                <table class="table table-bordered">
                                    <thead><tr><th></th><th>OD</th><th>OS</th></tr></thead>
                                    <tbody>
                                        <tr><td>LID and Adnexa</td>
                                            <td>
                                                @foreach($la_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($la_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-6 text-center">
                                <strong>Vision OD</strong><br>
                                <img src="{{ $mrecord->vision_od_img2 }}" alt=""/>
                            </div>
                            <div class="col-sm-6 text-center">
                                <strong>Vision OS</strong><br>
                                <img src="{{ $mrecord->vision_os_img2 }}" alt=""/>
                            </div>
                        </div>
                        @php
                        $sel_5_od = explode(',', $mrecord->sel_5_od);
                        $sel_5_os = explode(',', $mrecord->sel_5_os);
                        $conj_od = DB::table('vision_extras')->whereIn('id', $sel_5_od)->get();
                        $conj_os = DB::table('vision_extras')->whereIn('id', $sel_5_os)->get();
                        $sel_6_od = explode(',', $mrecord->sel_6_od);
                        $sel_6_os = explode(',', $mrecord->sel_6_os);
                        $sclera_od = DB::table('vision_extras')->whereIn('id', $sel_6_od)->get();
                        $sclera_os = DB::table('vision_extras')->whereIn('id', $sel_6_os)->get();
                        $sel_7_od = explode(',', $mrecord->sel_7_od);
                        $sel_7_os = explode(',', $mrecord->sel_7_os);
                        $cornea_od = DB::table('vision_extras')->whereIn('id', $sel_7_od)->get();
                        $cornea_os = DB::table('vision_extras')->whereIn('id', $sel_7_os)->get();
                        $sel_8_od = explode(',', $mrecord->sel_8_od);
                        $sel_8_os = explode(',', $mrecord->sel_8_os);
                        $ac_od = DB::table('vision_extras')->whereIn('id', $sel_8_od)->get();
                        $ac_os = DB::table('vision_extras')->whereIn('id', $sel_8_os)->get();
                        @endphp
                        <div class="row mt-3">
                            <div class="col-sm-12 table-responsive">
                                <table class="table table-bordered">
                                    <thead><tr><th></th><th>OD</th><th>OS</th></tr></thead>
                                    <tbody>
                                        <tr><td>Conjunctiva</td>
                                            <td>
                                                @foreach($conj_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($conj_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr><td>Sclera</td>
                                            <td>
                                                @foreach($sclera_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($sclera_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr><td>Cornea</td>
                                         <td>
                                                @foreach($cornea_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($cornea_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr><td>Anterior Chamber</td>
                                            <td>
                                                @foreach($ac_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($ac_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-6 text-center">
                                <strong>Vision OD</strong><br>
                                <img src="{{ $mrecord->vision_od_img3 }}" alt=""/>
                            </div>
                            <div class="col-sm-6 text-center">
                                <strong>Vision OS</strong><br>
                                <img src="{{ $mrecord->vision_os_img3 }}" alt=""/>
                            </div>
                        </div>
                        @php
                        $sel_9_od = explode(',', $mrecord->sel_9_od);
                        $sel_9_os = explode(',', $mrecord->sel_9_os);
                        $iris_od = DB::table('vision_extras')->whereIn('id', $sel_9_od)->get();
                        $iris_os = DB::table('vision_extras')->whereIn('id', $sel_9_os)->get();
                        $sel_10_od = explode(',', $mrecord->sel_10_od);
                        $sel_10_os = explode(',', $mrecord->sel_10_os);
                        $pupil_od = DB::table('vision_extras')->whereIn('id', $sel_10_od)->get();
                        $pupil_os = DB::table('vision_extras')->whereIn('id', $sel_10_os)->get();
                        $sel_11_od = explode(',', $mrecord->sel_11_od);
                        $sel_11_os = explode(',', $mrecord->sel_11_os);
                        $lens_od = DB::table('vision_extras')->whereIn('id', $sel_11_od)->get();
                        $lens_os = DB::table('vision_extras')->whereIn('id', $sel_11_os)->get();
                        @endphp
                        <div class="row mt-3">
                            <div class="col-sm-12 table-responsive">
                                <table class="table table-bordered">
                                    <thead><tr><th></th><th>OD</th><th>OS</th></tr></thead>
                                    <tbody>
                                        <tr><td>Iris</td>
                                            <td>
                                                @foreach($iris_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($iris_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr><td>Pupil</td>
                                            <td>
                                                @foreach($pupil_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($pupil_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr><td>Lens</td>
                                            <td>
                                                @foreach($lens_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($lens_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-6 text-center">
                                <strong>Vision OD</strong><br>
                                <img src="{{ $mrecord->vision_od_img4 }}" alt=""/>
                            </div>
                            <div class="col-sm-6 text-center">
                                <strong>Vision OS</strong><br>
                                <img src="{{ $mrecord->vision_os_img4 }}" alt=""/>
                            </div>
                        </div>
                        @php
                        $sel_12_od = explode(',', $mrecord->sel_12_od);
                        $sel_12_os = explode(',', $mrecord->sel_12_os);
                        $avr_od = DB::table('vision_extras')->whereIn('id', $sel_12_od)->get();
                        $avr_os = DB::table('vision_extras')->whereIn('id', $sel_12_os)->get();
                        $sel_13_od = explode(',', $mrecord->sel_13_od);
                        $sel_13_os = explode(',', $mrecord->sel_13_os);
                        $fundus_od = DB::table('vision_extras')->whereIn('id', $sel_13_od)->get();
                        $fundus_os = DB::table('vision_extras')->whereIn('id', $sel_13_os)->get();
                        $sel_14_od = explode(',', $mrecord->sel_14_od);
                        $sel_14_os = explode(',', $mrecord->sel_14_os);
                        $media_od = DB::table('vision_extras')->whereIn('id', $sel_14_od)->get();
                        $media_os = DB::table('vision_extras')->whereIn('id', $sel_14_os)->get();
                        $sel_15_od = explode(',', $mrecord->sel_15_od);
                        $sel_15_os = explode(',', $mrecord->sel_15_os);
                        $dm_od = DB::table('vision_extras')->whereIn('id', $sel_15_od)->get();
                        $dm_os = DB::table('vision_extras')->whereIn('id', $sel_15_os)->get();
                        $sel_16_od = explode(',', $mrecord->sel_16_od);
                        $sel_16_os = explode(',', $mrecord->sel_16_os);
                        $cdr_od = DB::table('vision_extras')->whereIn('id', $sel_16_od)->get();
                        $cdr_os = DB::table('vision_extras')->whereIn('id', $sel_16_os)->get();
                        $sel_17_od = explode(',', $mrecord->sel_17_od);
                        $sel_17_os = explode(',', $mrecord->sel_17_os);
                        $nrr_od = DB::table('vision_extras')->whereIn('id', $sel_17_od)->get();
                        $nrr_os = DB::table('vision_extras')->whereIn('id', $sel_17_os)->get();
                        $sel_18_od = explode(',', $mrecord->sel_18_od);
                        $sel_18_os = explode(',', $mrecord->sel_18_os);
                        $arb_od = DB::table('vision_extras')->whereIn('id', $sel_18_od)->get();
                        $arb_os = DB::table('vision_extras')->whereIn('id', $sel_18_os)->get();
                        $sel_19_od = explode(',', $mrecord->sel_19_od);
                        $sel_19_os = explode(',', $mrecord->sel_19_os);
                        $fr_od = DB::table('vision_extras')->whereIn('id', $sel_19_od)->get();
                        $fr_os = DB::table('vision_extras')->whereIn('id', $sel_19_os)->get();
                        $sel_20_od = explode(',', $mrecord->sel_20_od);
                        $sel_20_os = explode(',', $mrecord->sel_20_os);
                        $brp_od = DB::table('vision_extras')->whereIn('id', $sel_20_od)->get();
                        $brp_os = DB::table('vision_extras')->whereIn('id', $sel_20_os)->get();
                        @endphp
                        <div class="row mt-3">
                            <div class="col-sm-12 table-responsive">
                                <table class="table table-bordered">
                                    <thead><tr><th></th><th>OD</th><th>OS</th></tr></thead>
                                    <tbody>
                                        <tr><td>AVR</td>
                                            <td>
                                                @foreach($avr_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($avr_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr><td>Fundus</td>
                                            <td>
                                                @foreach($fundus_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($fundus_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr><td>Media</td>
                                            <td>
                                                @foreach($media_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($media_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr><td>Disc Margins</td>
                                            <td>
                                                @foreach($dm_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($dm_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr><td>CDR</td>
                                            <td>
                                                @foreach($cdr_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($cdr_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr><td>NRR</td>
                                            <td>
                                                @foreach($nrr_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($nrr_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr><td>AV Ratio & Bloodvessels</td>
                                            <td>
                                                @foreach($arb_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($arb_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr><td>FR</td>
                                            <td>
                                                @foreach($fr_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($fr_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr><td>Background Retina & Periphery</td>
                                            <td>
                                                @foreach($brp_od as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($brp_os as $record)
                                                    <span class="badge bg-info">{{ $record->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-6">
                                <h5>Gonioscopy OD</h5>
                                <table class="table table-borderless" style="background:url({{ public_path().'/images/assets/x-png-30.png' }}); background-repeat: no-repeat; background-position: center;">
                                    <tr><td></td><td width="20%"><input type="text" class="form-control" name="gonio_od_top" value="{{ $mrecord->gonio_od_top }}" placeholder="0" readonly/></td><td></td></tr>
                                    <tr><td><input type="text" class="form-control" name="gonio_od_left" value="{{ $mrecord->gonio_od_left }}" placeholder="0" readonly/></td><td class="text-center"></td><td><input type="text" class="form-control" name="gonio_od_right" value="{{ $mrecord->gonio_od_right }}" placeholder="0" readonly/></td></tr>
                                    <tr><td></td><td><input type="text" class="form-control" name="gonio_od_bottom" value="{{ $mrecord->gonio_od_bottom }}" placeholder="0" readonly/></td><td></td></tr>
                                </table>
                                <input type="text" class="form-control" name="gonio_od" value="{{ $mrecord->gonio_od }}"  placeholder="0" readonly/>
                            </div>
                            <div class="col-sm-6">
                                <h5>Gonioscopy OS</h5>
                                <table class="table table-borderless" style="background:url({{ public_path().'/images/assets/x-png-30.png' }}); background-repeat: no-repeat; background-position: center;">
                                    <tr><td></td><td width="20%"><input type="text" class="form-control" name="gonio_os_top" value="{{ $mrecord->gonio_os_top }}" placeholder="0" readonly/></td><td></td></tr>
                                    <tr><td><input type="text" class="form-control" name="gonio_os_left" value="{{ $mrecord->gonio_os_left }}" placeholder="0" readonly/></td><td class="text-center"></td><td><input type="text" class="form-control" name="gonio_os_right" value="{{ $mrecord->gonio_os_right }}" placeholder="0" readonly/></td></tr>
                                    <tr><td></td><td><input type="text" class="form-control" name="gonio_os_bottom" value="{{ $mrecord->gonio_os_bottom }}" placeholder="0" readonly/></td><td></td></tr>
                                </table>
                                <input type="text" class="form-control" name="gonio_os" value="{{ $mrecord->gonio_os }}"  placeholder="0" readonly/>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <strong>Signs</strong><br>
                                {{ $mrecord->signs }}
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-6 text-center">
                                <strong>Retina OD</strong><br>
                                @if($retina_od)
                                    @foreach($retina_od as $retina)
                                        <img src="{{ public_path().'/storage/'.$retina->retina_img }}" class='img-fluid mt-1 mb-1' alt=""/>
                                    @endforeach
                                @endif
                            </div>
                            <div class="col-sm-6 text-center">
                                <strong>Retina OS</strong><br>
                                @if($retina_os)
                                    @foreach($retina_os as $retina)
                                        <img src="{{ public_path().'/storage/'.$retina->retina_img }}" class='img-fluid mt-1 mb-1' alt=""/>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <strong>Doctor Recommendations</strong><br>
                                {{ $mrecord->doctor_recommondations }}
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <strong>Medicines Advised</strong><br>
                                <table class="table table-bordered">
                                    <thead class="text-center">
                                        <tr><th>Medicine</th><th>Qty</th><th>Dosage</th><th>Notes</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach($medicine_record as $medicine)
                                            <tr><td>{{ $medicine->product_name }}</td><td>{{ $medicine->qty }}</td><td>{{ $medicine->dosage }}</td><td>{{ $medicine->notes }}</td></tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <strong>Lab Records</strong><br>
                                <table class="table table-bordered">
                                    <thead class="text-center">
                                        <tr><th>Lab Test</th><th>Notes</th><th>Result</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach($labc as $lab)
                                            <tr><td>{{ $lab->lab_type_name }}</td><td>{{ $lab->notes }}</td><td>{{ $lab->lab_result }}</td></tr>
                                        @endforeach
                                        @foreach($labr as $lab)
                                            <tr><td>{{ $lab->lab_type_name }}</td><td>{{ $lab->notes }}</td><td>{{ $lab->lab_result }}</td></tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-4">
                                <strong>Admission Advised</strong><br>
                                {{ ($mrecord->is_patient_admission == 'Y') ? 'Yes' : 'No' }}
                            </div>
                            <div class="col-sm-4">
                                <strong>Surgery Advised</strong><br>
                                {{ ($mrecord->is_patient_surgery == 'Y') ? 'Yes' : 'No' }}
                            </div>
                            <div class="col-sm-4">
                                <strong>Review Date</strong><br>
                                {{ date('d/M/Y', strtotime($mrecord->review_date)) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection