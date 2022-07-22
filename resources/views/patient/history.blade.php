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
                        <h5 class="mb-0">Medical Record ID: {{ $mrecord->id }}, Date: {{ date('d/M/Y', strtotime($mrecord->created_at)) }}</h5>
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
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->re_dist_sph : '' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->re_dist_cyl : '' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->re_dist_axis : '' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" vvalue="{{ ($spectacle) ? $spectacle->re_dist_add : '' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->vbr : '' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" vvalue="{{ ($spectacle) ? $spectacle->re_near_va : '' }}" placeholder="0" readonly="true" /></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center fw-bold">LE/OS</td>                                            
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->le_dist_sph : '' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->le_dist_cyl : '' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->le_dist_axis : '' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->le_dist_add : '' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->vbl : '' }}" placeholder="0" readonly="true" /></td>
                                            <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->le_near_va : '' }}" placeholder="0" readonly="true" /></td>
                                        </tr>
                                        <tr><td colspan="4" class="fw-bold text-center">IOP-OD</td><td colspan="3" class="fw-bold text-center">IOP-OS</td></tr>
                                        <tr><td colspan="4" class="fw-bold text-center"><input class="form-control form-control-md" type="text" maxlength="7" value="{{ ($spectacle) ? $spectacle->re_iop : '' }}" placeholder="0" readonly="true" /></td><td colspan="3" class="fw-bold text-center"><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->le_iop : '' }}" maxlength="7" placeholder="0" readonly="true" /></td></tr>
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
                                        <tr><td>Extraocular Movements</td><td></td><td></td></tr>
                                        <tr><td>Orbital Margins</td><td></td><td></td></tr>
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
                        <div class="row mt-3">
                            <div class="col-sm-12 table-responsive">
                                <table class="table table-bordered">
                                    <thead><tr><th></th><th>OD</th><th>OS</th></tr></thead>
                                    <tbody>
                                        <tr><td>LID and Adnexa</td><td></td><td></td></tr>
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
                        <div class="row mt-3">
                            <div class="col-sm-12 table-responsive">
                                <table class="table table-bordered">
                                    <thead><tr><th></th><th>OD</th><th>OS</th></tr></thead>
                                    <tbody>
                                        <tr><td>Conjunctiva</td><td></td><td></td></tr>
                                        <tr><td>Sclera</td><td></td><td></td></tr>
                                        <tr><td>Cornea</td><td></td><td></td></tr>
                                        <tr><td>Anterior Chamber</td><td></td><td></td></tr>
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
                        <div class="row mt-3">
                            <div class="col-sm-12 table-responsive">
                                <table class="table table-bordered">
                                    <thead><tr><th></th><th>OD</th><th>OS</th></tr></thead>
                                    <tbody>
                                        <tr><td>Iris</td><td></td><td></td></tr>
                                        <tr><td>Pupil</td><td></td><td></td></tr>
                                        <tr><td>Lens</td><td></td><td></td></tr>
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
                        <div class="row mt-3">
                            <div class="col-sm-12 table-responsive">
                                <table class="table table-bordered">
                                    <thead><tr><th></th><th>OD</th><th>OS</th></tr></thead>
                                    <tbody>
                                        <tr><td>AVR</td><td></td><td></td></tr>
                                        <tr><td>Fundus</td><td></td><td></td></tr>
                                        <tr><td>Media</td><td></td><td></td></tr>
                                        <tr><td>Disc Margins</td><td></td><td></td></tr>
                                        <tr><td>CDR</td><td></td><td></td></tr>
                                        <tr><td>NRR</td><td></td><td></td></tr>
                                        <tr><td>AV Ratio & Bloodvessels</td><td></td><td></td></tr>
                                        <tr><td>FR</td><td></td><td></td></tr>
                                        <tr><td>Background Retina & Periphery</td><td></td><td></td></tr>
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