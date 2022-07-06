@extends("templates.base")

@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Consultation (Medical Records)</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('medical-records.create') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="mrid" value="0"/>
                            <input type="hidden" name="mrn" value="{{ $reference->id }}"/>
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}"/>
                            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}"/>
                            <input type="hidden" id="btn_text" value="Save"/>
                            <div class="row g-4">
                                <div class="col-sm-3">MRN: <h5 class="text-primary">{{ $reference->id }}</h5></div>
                                <div class="col-sm-3">Patient Name: <h5 class="text-primary">{{ $patient->patient_name }}</h5></div>
                                <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ $patient->patient_id }}</h5></div>
                                <div class="col-sm-3">Doctor Name: <h5 class="text-primary">{{ $doctor->doctor_name }}</h5></div>
                                <!--<div class="col-sm-12"><strong>Patient Complaints:</strong> <p class="text-justify">{{ $reference->symptoms }}</p></div>
                                <div class="col-sm-12"><strong>Special Notes:</strong> <p class="text-justify">{{ $reference->notes }}</p></div>-->
                                <div class="col-sm-12 text-center">
                                    <p><a href="{{ route('patient.history', $patient->id) }}" target="_blank">VIEW PATINET MEDICAL HISTORY</a></p>
                                </div>
                                <div class="col-sm-11">
                                    <label class="form-label">Symptoms<sup class="text-danger">*</sup> <small class="text-info">(Multiple selection enabled)</small></label>
                                    <select class="form-control form-control-md show-tick ms select2" multiple data-placeholder="Select" name="symptom_id[]" id="symptomSelect">
                                    <option value="">Select</option>
                                    @foreach($symptoms as $sympt)
                                        <option value="{{ $sympt->id }}" {{ old('symptom_id') == $sympt->id ? 'selected' : '' }}>{{ $sympt->symptom_name }}</option>
                                    @endforeach
                                    </select>
                                    @error('symptom_id')
                                    <small class="text-danger">{{ $errors->first('symptom_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-1">
                                    <a data-bs-toggle="modal" href="#symptomModal"><i class="fa fa-plus fa-lg text-success"></i></a>
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">Symptoms</label>
                                    <textarea class="form-control form-control-md" name="symptoms_other" rows="5" placeholder="Symptoms">{{ old('symptoms_other') }}</textarea>
                                    @error('symptoms_other')
                                    <small class="text-danger">{{ $errors->first('symptoms_other') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">Patient History</label>
                                    <textarea class="form-control form-control-md" name="history" rows="5" placeholder="Patient History">{{ old('history') }}</textarea>
                                    @error('history')
                                    <small class="text-danger">{{ $errors->first('history') }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-6 table-responsive">
                                    <label class="form-label">Vision</label>
                                    <table class="table table-bordered">
                                        <thead class="text-center"><tr><th>&nbsp;</th><th><!--VB--></th><th><!--SPH--></th><th><!--CYL--></th><th><!--AXIS--></th><th><!--ADD--></th><th><!--VA--></th></tr></thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center fw-bold">RE/OD</td>
                                                <td><input class="form-control form-control-md" type="text" placeholder="0" readonly="true" /></td>
                                                <td><input class="form-control form-control-md" type="text" placeholder="0" readonly="true" /></td>
                                                <td><input class="form-control form-control-md" type="text" placeholder="0" readonly="true" /></td>
                                                <td><input class="form-control form-control-md" type="text" placeholder="0" readonly="true" /></td>
                                                <td><input class="form-control form-control-md" type="text" placeholder="0" readonly="true" /></td>
                                                <td><input class="form-control form-control-md" type="text" placeholder="0"readonly="true" /></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center fw-bold">LE/OS</td>
                                                <td><input class="form-control form-control-md" type="text" placeholder="0" readonly="true" /></td>
                                                <td><input class="form-control form-control-md" type="text" placeholder="0" readonly="true" /></td>
                                                <td><input class="form-control form-control-md" type="text" placeholder="0"readonly="true" /></td>
                                                <td><input class="form-control form-control-md" type="text" placeholder="0" readonly="true" /></td>
                                                <td><input class="form-control form-control-md" type="text" placeholder="0" readonly="true" /></td>
                                                <td><input class="form-control form-control-md" type="text" placeholder="0" readonly="true" /></td>
                                            </tr>
                                            <tr><td colspan="4" class="fw-bold text-center">IOP-OD</td><td colspan="3" class="fw-bold text-center">IOP-OS</td></tr>
                                            <tr><td colspan="4" class="fw-bold text-center"><input class="form-control form-control-md" type="text" maxlength="7" placeholder="0" readonly="true" /></td><td colspan="3" class="fw-bold text-center"><input class="form-control form-control-md" type="text" maxlength="7" placeholder="0" readonly="true" /></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-6 table-responsive">
                                    <label class="form-label">Biometry</label>
                                    <table class="table table-bordered" style="">
                                        <thead class="text-center">
                                            <tr><th></th><th>K1 (Auto)</th><th>K2 (Auto)</th><th>K1 (Manual)</th><th>K2 (Manual)</th></tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center fw-bold">OD</td>
                                                <td><input class="form-control form-control-md" type="text" name="k1_od_auto" maxlength="6" placeholder="0"/></td>
                                                <td><input class="form-control form-control-md" type="text" name="k2_od_auto" maxlength="6" placeholder="0"/></td>
                                                <td><input class="form-control form-control-md" type="text" name="k1_od_manual" maxlength="6" placeholder="0"/></td>
                                                <td><input class="form-control form-control-md" type="text" name="k2_od_manual" maxlength="6" placeholder="0"/></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center fw-bold">OS</td>
                                                <td><input class="form-control form-control-md" type="text" name="k1_os_auto" maxlength="6" placeholder="0"/></td>
                                                <td><input class="form-control form-control-md" type="text" name="k2_os_auto" maxlength="6" placeholder="0"/></td>
                                                <td><input class="form-control form-control-md" type="text" name="k1_os_manual" maxlength="6" placeholder="0"/></td>
                                                <td><input class="form-control form-control-md" type="text" name="k2_os_manual" maxlength="6" placeholder="0"/></td>
                                            </tr>
                                            <tr><td colspan="5" class="fw-bold text-center">AXL</td></tr>
                                            <tr><td colspan="5" class="fw-bold text-center"><input class="form-control form-control-md" type="text" name="axl" maxlength="7" placeholder="0"/></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--<div class="row g-4">
                                <div class="col-sm-6">
                                    <label class="form-label">OD</label>
                                    <img src="{{ public_path().'/storage/assets/images/eye-re.jpg' }}" class="img-fluid" alt="Right Eye">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">OS</label>
                                    <img src="{{ public_path().'/storage/assets/images/eye-le.jpg' }}" class="img-fluid" alt="Left Eye">
                                </div>
                            </div>-->
                            <div class="row g-4">
                                <div class="col-sm-1">
                                    <label class="form-label">Color Picker</label>
                                    <input type="color" class="form-control form-control-md" id="favcolor" name="favcolor" value="#ff0000">
                                </div>
                            </div>
                            <div class="row g-4 mt-1">
                                <div class="col-sm-6">
                                    <label class="form-label">OD</label>
                                    <img id="imgreye" src="{{ public_path().'/storage/assets/images/eye-re.jpg' }}" class="d-none">
                                    <canvas id="re_eye" style="border: 1px solid #000;"></canvas>
                                    <div class="odpoints"></div>
                                    <a href="javascript:void(0)" id='odclear' class="btn btn-secondary mt-1">Clear</a>
                                    <a href="javascript:void(0)" id='odundo' class="btn btn-warning mt-1">Undo</a>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">OS</label>
                                    <img id="imgleye" src="{{ public_path().'/storage/assets/images/eye-le.jpg' }}" class="d-none">
                                    <canvas id="le_eye" style="border: 1px solid #000;"></canvas>
                                    <div class="ospoints"></div>
                                    <a href="javascript:void(0)" id='osclear' class="btn btn-secondary mt-1">Clear</a>
                                    <a href="javascript:void(0)" id='osundo' class="btn btn-warning mt-1">Undo</a>
                                </div>
                            </div>
                            <div class="row g-4 mt-1">
                                <div class="col-sm-6">
                                    <label class="form-label">OD</label>
                                    <img id="imgreye1" src="{{ public_path().'/storage/assets/images/right-eye-od.jpg' }}" class="d-none">
                                    <canvas id="re_eye1" style="border: 1px solid #000;"></canvas>
                                    <div class="odpoints1"></div>
                                    <a href="javascript:void(0)" id='odclear1' class="btn btn-secondary mt-1">Clear</a>
                                    <a href="javascript:void(0)" id='odundo1' class="btn btn-warning mt-1">Undo</a>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">OS</label>
                                    <img id="imgleye1" src="{{ public_path().'/storage/assets/images/left-eye-os.jpg' }}" class="d-none">
                                    <canvas id="le_eye1" style="border: 1px solid #000;"></canvas>
                                    <div class="ospoints1"></div>
                                    <a href="javascript:void(0)" id='osclear1' class="btn btn-secondary mt-1">Clear</a>
                                    <a href="javascript:void(0)" id='osundo1' class="btn btn-warning mt-1">Undo</a>
                                </div>
                            </div>
                            <div class="row g-4 mt-1">
                                <div class="col-sm-11">
                                    <label class="form-label">Signs</label>
                                    <textarea class="form-control form-control-md" name="signs" rows="5" placeholder="Signs">{{ old('signs') }}</textarea>
                                    @error('signs')
                                    <small class="text-danger">{{ $errors->first('signs') }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-4 mt-1">
                                <label class="form-label">Retina Wellness Report</label>
                                <div class="col-sm-6">                                    
                                    <label class="form-label">OD</label>
                                    <input type="file" class="form-control retina_od" name="retina_od" id="retina_od" data-container="retina_od_container" data-type="od" data-labid="0">
                                    <div class="retina_od_container mt-3 mb-3"></div>
                                </div>
                                <div class="col-sm-6">                                    
                                    <label class="form-label">OS</label>
                                    <input type="file" class="form-control retina_os" name="retina_os" id="retina_os" data-container="retina_os_container" data-type="os" data-labid="0">
                                    <div class="retina_os_container mt-3 mb-3"></div>
                                </div>
                            </div>                           
                            <div class="row g-4 mt-1">
                                <div class="col-sm-11">
                                    <label class="form-label">Dignosis<sup class="text-danger">*</sup> <small class="text-info">(Multiple selection enabled)</small></label>
                                    <select class="form-control form-control-md show-tick ms select2" multiple data-placeholder="Select" name="diagnosis_id[]" id="diagnosisSelect">
                                    <option value="">Select</option>
                                    @foreach($diagnosis as $dia)
                                        <option value="{{ $dia->id }}" {{ old('diagnosis_id') == $dia->id ? 'selected' : '' }}>{{ $dia->diagnosis_name }}</option>
                                    @endforeach
                                    </select>
                                    @error('diagnosis_id')
                                    <small class="text-danger">{{ $errors->first('diagnosis_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-1">
                                    <a data-bs-toggle="modal" href="#diagnosisModal"><i class="fa fa-plus fa-lg text-success"></i></a>
                                </div> 
                                <div class="col-sm-12">
                                    <label class="form-label">Doctor Recommondations / Advise<sup class="text-danger">*</sup></label>
                                    <textarea class="form-control form-control-md" name="doctor_recommondations" rows="5" placeholder="Doctor Recommondations / Advise" required="required">{{ old('doctor_recommondations') }}</textarea>
                                    @error('doctor_recommondations')
                                    <small class="text-danger">{{ $errors->first('doctor_recommondations') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Medicine Advise</label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="medicine_id[]">
                                    <option value="">Select</option>
                                    @foreach($medicines as $med)
                                        <option value="{{ $med->id }}" {{ old('medicine_id') == $dia->id ? 'selected' : '' }}>{{ $med->product_name }}</option>
                                    @endforeach
                                    </select>
                                    @error('medicine_id')
                                    <small class="text-danger">{{ $errors->first('medicine_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Dosage</label>
                                    <input type="text" name="dosage[]" class="form-control form-control-md" placeholder="Eg: Daily 3 Drops"/>
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Dosage</label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="dosage1[]">
                                    <option value="">Select</option>
                                    @foreach($dosages as $dos)
                                        <option value="{{ $dos->id }}" {{ old('dosage_id') == $dia->id ? 'selected' : '' }}>{{ $dos->dosage }}</option>
                                    @endforeach
                                    </select>
                                    @error('dosage_id')
                                    <small class="text-danger">{{ $errors->first('dosage_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Qty / NOs.</label>
                                    <input type='number' class='form-control form-control-md' name='qty[]' placeholder='0' />
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Notes.</label>
                                    <input type='text' class='form-control form-control-md' name='notes[]' placeholder='Notes' />
                                </div>
                                <div class="col-sm-1">
                                    <a class="medicineAdvise" href="javascript:void(0)"><i class="fa fa-plus fa-lg text-success"></i><a>                                    
                                </div>
                                <div class="medicineAdviseContainer">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2 mt-3">
                                    <label class="form-label">Admission Advised?</label>
                                    <select class="form-control form-control-md" name="is_patient_admission" data-placeholder='Select'>
                                        <option value='N'>No</option>
                                        <option value='Y'>Yes</option>
                                    </select>
                                </div>
                                <div class="col-sm-2 mt-3">
                                    <label class="form-label">Surgery Advised?</label>
                                    <select class="form-control form-control-md" name="is_patient_surgery" data-placeholder='Select'>
                                        <option value="N">No</option>
                                        <option value="Y">Yes</option>
                                    </select>
                                </div>
                                <div class="col-sm-3 mt-3">
                                    <label class="form-label">Review Date</label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ old('review_date') }}" name="review_date" class="form-control form-control-md dtpicker" placeholder="dd/mm/yyyy">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('review_date')
                                    <small class="text-danger">{{ $errors->first('review_date') }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-3">                            
                                <div class="col-sm-12 text-right">
                                    <button type="button" onClick="history.back()"  class="btn btn-danger">Cancel</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="button" class="btn btn-primary btn-consultation">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
<div class="modal fade" id="symptomModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form name="frm-symptom" id="frm-symptom" action="/symptom/create/">
                <input type="hidden" class="ddl" value="symptomSelect" />
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="exampleModalLiveLabel">Add Symptom</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col">
                            <label class="form-label">Symptom Name<sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control form-control-md" name="symptom_name" placeholder="Symptom Name"/>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col message text-success"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ajax-submit btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="diagnosisModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form name="frm-diagnosis" id="frm-diagnosis" action="/symptom/create/">
                <input type="hidden" class="ddl" value="diagnosisSelect" />
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="exampleModalLiveLabel">Add Diagnosis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col">
                            <label class="form-label">Diagnosis Name<sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control form-control-md" name="diagnosis_name" placeholder="Diagnosis Name"/>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col message text-success"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ajax-submit btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade visionModal" id="visionModal" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalCenterTitle" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Description</h5>
                <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <div class="col">
                        <label class="form-label">Description <sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control form-control-md vision_description" name="vision_description" placeholder="Description"/>
                        <input type="hidden" class="vision_canvas" value=""/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
                <button type="button" class="btn btn-primary btnaddpoints">Add</button>
            </div>
        </div>
    </div>
</div>
@endsection