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
                        <form action="{{ route('medical-records.create') }}" method="post">
                            @csrf
                            <input type="hidden" name="mrn" value="{{ $reference->id }}"/>
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}"/>
                            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}"/>
                            <div class="row g-4">
                                <div class="col-sm-3">MRN: <h5 class="text-primary">{{ $reference->id }}</h5></div>
                                <div class="col-sm-3">Patient Name: <h5 class="text-primary">{{ $patient->patient_name }}</h5></div>
                                <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ $patient->patient_id }}</h5></div>
                                <div class="col-sm-3">Doctor Name: <h5 class="text-primary">{{ $doctor->doctor_name }}</h5></div>
                                <!--<div class="col-sm-12"><strong>Patient Complaints:</strong> <p class="text-justify">{{ $reference->symptoms }}</p></div>
                                <div class="col-sm-12"><strong>Special Notes:</strong> <p class="text-justify">{{ $reference->notes }}</p></div>-->
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
                                    <label class="form-label">Patient Complaints / Symptoms<sup class="text-danger">*</sup></label>
                                    <textarea class="form-control form-control-md" name="patient_complaints" rows="5" placeholder="Patient Complaints / Symptoms">{{ old('patient_complaints') }}</textarea>
                                    @error('patient_complaints')
                                    <small class="text-danger">{{ $errors->first('patient_complaints') }}</small>
                                    @enderror
                                </div>
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
                                    <label class="form-label">Doctor Findings / Diagnosis<sup class="text-danger">*</sup></label>
                                    <textarea class="form-control form-control-md" name="doctor_findings" rows="5" placeholder="Doctor Findings / Diagnosis">{{ old('doctor_findings') }}</textarea>
                                    @error('doctor_findings')
                                    <small class="text-danger">{{ $errors->first('doctor_findings') }}</small>
                                    @enderror
                                </div>  
                                <div class="col-sm-12">
                                    <label class="form-label">Doctor Recommondations / Advise<sup class="text-danger">*</sup></label>
                                    <textarea class="form-control form-control-md" name="doctor_recommondations" rows="5" placeholder="Doctor Recommondations / Advise">{{ old('doctor_recommondations') }}</textarea>
                                    @error('doctor_recommondations')
                                    <small class="text-danger">{{ $errors->first('doctor_recommondations') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
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
                                <div class="col-sm-4">
                                    <label class="form-label">Dosage</label>
                                    <input type="text" name="dosage[]" class="form-control form-control-md" placeholder="Eg: Daily 3 Drops"/>
                                </div>
                                <div class="col-sm-3">
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
                                <div class="col-sm-1">
                                    <a class="medicineAdvise" href="javascript:void(0)"><i class="fa fa-plus fa-lg text-success"></i><a>                                    
                                </div>
                                <div class="medicineAdviseContainer">

                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">Medicine List<sup class="text-danger">*</sup></label>
                                    <textarea class="form-control form-control-md" name="medicine_list" rows="5" placeholder="Medicine List">{{ old('medicine_list') }}</textarea>
                                    @error('medicine_list')
                                    <small class="text-danger">{{ $errors->first('medicine_list') }}</small>
                                    @enderror
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
                                    <button type="submit" class="btn btn-primary btn-submit">Save</button>
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
@endsection