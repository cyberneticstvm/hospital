@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Update Operation Note</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body table-responsive">
                        <div class="row g-4 mb-3">
                            <div class="col-sm-3">MR.ID: <h5 class="text-primary">{{ $mrecord->id }}</h5>
                            </div>
                            <div class="col-sm-3">Patient Name: <h5 class="text-primary">{{ ($patient) ? $patient->patient_name : '' }}</h5>
                            </div>
                            <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ ($patient) ? $patient->patient_id : '' }}</h5>
                            </div>
                            <div class="col-sm-3">Branch: <h5 class="text-primary">{{ $branch->branch_name }}</h5>
                            </div>
                        </div>
                        <form action="{{ route('onote.update', $onote->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="medical_record_id" value="{{ $mrecord->id }}" />
                            <input type="hidden" name="patient_id" value="{{ ($patient) ? $patient->id : 0 }}" />
                            <input type="hidden" name="branch" value="{{ $mrecord->branch }}" />
                            <div class="row g-4">
                                <div class="col-sm-2">
                                    <label class="form-label">Eye</label>
                                    <select class="form-control form-control-md" name="eye">
                                        <option value="">Select</option>
                                        <option value="Right" {{ $onote->eye == 'Right' ? 'selected' : '' }}>Right</option>
                                        <option value="Left" {{ $onote->eye == 'Left' ? 'selected' : '' }}>Left</option>
                                    </select>
                                    @error('eye')
                                    <small class="text-danger">{{ $errors->first('eye') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Surgeon<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2" data-placeholder="Select" name="surgeon" required="required">
                                        <option value="">Select</option>
                                        @foreach($doctors as $doc)
                                        <option value="{{ $doc->id }}" {{ $onote->surgeon == $doc->id ? 'selected' : '' }}>{{ $doc->doctor_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('surgeon')
                                    <small class="text-danger">{{ $errors->first('surgeon') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Surgery Date<sup class="text-danger">*</sup></label>
                                    <input type="date" name="date_of_surgery" class="form-control form-control-md" value="{{ $onote->date_of_surgery?->format('Y-m-d') }}" />
                                    @error('date_of_surgery')
                                    <small class="text-danger">{{ $errors->first('date_of_surgery') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Test Dose Time<sup class="text-danger">*</sup></label>
                                    <input type="time" name="test_dose_time" class="form-control form-control-md" value="{{ $onote?->test_dose_time->format('H:i') }}" />
                                    @error('test_dose_time')
                                    <small class="text-danger">{{ $errors->first('test_dose_time') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-5">
                                    <label class="form-label">Test Dose Result<sup class="text-danger">*</sup></label>
                                    <input type="text" name="test_dose_result" class="form-control form-control-md" value="{{ $onote->test_dose_result }}" placeholder="Test Dose Result" />
                                    @error('test_dose_result')
                                    <small class="text-danger">{{ $errors->first('test_dose_result') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Blood Pressure<sup class="text-danger">*</sup></label>
                                    <div class="input-group">
                                        <input type="text" name="blood_pressure_mm" class="form-control form-control-md" value="{{ $onote->blood_pressure_mm }}" maxlength="3" placeholder="00" />
                                        <span class="input-group-text">/</span>
                                        <input type="text" name="blood_pressure_hg" class="form-control form-control-md" value="{{ $onote->blood_pressure_hg }}" maxlength="3" placeholder="00" />
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">GRBS<sup class="text-danger">*</sup></label>
                                    <input type="text" name="grbs" class="form-control form-control-md" value="{{ $onote->grbs }}" maxlength="3" placeholder="00" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">IOL Power</label>
                                    <input type="text" name="iol_power" class="form-control form-control-md" value="{{ $onote->iol_power }}" maxlength="5" placeholder="00.00" />
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">Procedure<sup class="text-danger">*</sup></label>
                                    <textarea name="procedures" class="form-control" rows="5" placeholder="Procedures">{{ $onote->procedures }}</textarea>
                                    @error('procedures')
                                    <small class="text-danger">{{ $errors->first('procedures') }}</small>
                                    @enderror
                                </div>

                                <div class="col-sm-12">
                                    <label class="form-label">Procedure Details<sup class="text-danger">*</sup></label>
                                    <textarea name="notes" class="form-control" rows="5" placeholder="Procedure Details">{{ $onote->notes }}</textarea>
                                    @error('notes')
                                    <small class="text-danger">{{ $errors->first('notes') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">Post-operative Advice<sup class="text-danger">*</sup></label>
                                    <textarea name="post_operative_advice" class="form-control" rows="5" placeholder="Post-operative Advice">{{ $onote->post_operative_advice }}</textarea>
                                    @error('post_operative_advice')
                                    <small class="text-danger">{{ $errors->first('post_operative_advice') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">Medications Prescribed<sup class="text-danger">*</sup></label>
                                    <textarea name="medications_prescribed" class="form-control" rows="5" placeholder="Medications Prescribed">{{ $onote->medications_prescribed }}</textarea>
                                    @error('medications_prescribed')
                                    <small class="text-danger">{{ $errors->first('medications_prescribed') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Emergency Contact Number<sup class="text-danger">*</sup></label>
                                    <input type="text" name="emergency_contact_number" maxlength="10" class="form-control form-control-md" value="{{ $onote->emergency_contact_number }}" />
                                    @error('emergency_contact_number')
                                    <small class="text-danger">{{ $errors->first('emergency_contact_number') }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
                                <div class="col-sm-12 text-end">
                                    <button type="button" onClick="history.back()" class="btn btn-danger">Cancel</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-primary btn-submit">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection