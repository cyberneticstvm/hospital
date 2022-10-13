@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Edit Admission</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admission.update', $admission->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <input type='hidden' name='medical_record_id' value="{{ $admission->medical_record_id }}" />
                            <input type='hidden' name='doctor_id' value="{{ $admission->doctor_id }}" />
                            <input type='hidden' name='patient_id' value="{{ $admission->patient_id }}" />
                            <input type='hidden' name='branch' value="{{ $admission->branch }}" />
                            <div class="row g-4">
                                <div class="col-sm-4">Patient Name: <h5 class="text-primary">{{ $patient->patient_name }}</h5></div>
                                <div class="col-sm-4">Patient ID: <h5 class="text-primary">{{ $patient->patient_id }}</h5></div>
                                <div class="col-sm-4">Doctor Name: <h5 class="text-primary">{{ $doctor->doctor_name }}</h5></div>
                                <div class="col-sm-3">
                                    <label class="form-label">Room Type <sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="room_type" >
                                        <option value="">Select</option>
                                        @foreach($rtypes as $rtype)
                                        <option value="{{ $rtype->id }}" {{ ($rtype->id == $admission->room_type) ? 'selected' : '' }}>{{ $rtype->room_type }}</option>
                                        @endforeach
                                    </select>
                                    @error('room_type')
                                    <small class="text-danger">{{ $errors->first('room_type') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Room / Ward Number <sup class="text-danger">*</sup></label>
                                    <input type="number" value="{{ $admission->room_number }}" name="room_number" class="form-control form-control-md" placeholder="Room Number">
                                    @error('room_number')
                                    <small class="text-danger">{{ $errors->first('room_number') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Admission Date <sup class="text-danger">*</sup></label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ ($admission->admission_date) ? date('d/M/Y', strtotime($admission->admission_date)) : '' }}" name="admission_date" class="form-control form-control-md dtpicker" placeholder="dd/mm/yyyy">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('admission_date')
                                    <small class="text-danger">{{ $errors->first('admission_date') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Bystander Name <sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $admission->bystander_name }}" name="bystander_name" class="form-control form-control-md" placeholder="Bystander Name">
                                    @error('bystander_name')
                                    <small class="text-danger">{{ $errors->first('bystander_name') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Bystander Contact No. <sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $admission->bystander_contact_number }}" 
                                    name="bystander_contact_number" class="form-control form-control-md" placeholder="Bystander Contact No.">
                                    @error('bystander_contact_number')
                                    <small class="text-danger">{{ $errors->first('bystander_contact_number') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Patient - Bystander Relation <sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $admission->patient_bystander_relation }}" name="patient_bystander_relation" class="form-control form-control-md" placeholder="Patient - Bystander Relation">
                                    @error('patient_bystander_relation')
                                    <small class="text-danger">{{ $errors->first('patient_bystander_relation') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Remarks </label>
                                    <input type="text" value="{{ $admission->remarks }}" name="remarks" class="form-control form-control-md" placeholder="Remarks">
                                    @error('remarks')
                                    <small class="text-danger">{{ $errors->first('remarks') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-12 text-right">
                                    <button type="button" onClick="history.back()"  class="btn btn-danger">Cancel</button>
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