@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">New Appointment</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('appointment.create') }}" method="post">
                            @csrf
                            <input type="hidden" name="patient_id" value="{{ ($patient && $patient->id) ? $patient->id : 0 }}" />
                            <input type="hidden" name="status" value="1" />
                            <div class="row g-4">
                                <div class="col-sm-4">
                                    <label class="form-label">Patient Name<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ ($patient && $patient->patient_name) ? $patient->patient_name : old('patient_name') }}" name="patient_name" class="form-control form-control-md" placeholder="Patient Name">
                                    @error('patient_name')
                                    <small class="text-danger">{{ $errors->first('patient_name') }}</small>
                                    @enderror
                                </div>                                
                                <div class="col-sm-3">
                                    <label class="form-label">Gender<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md" data-placeholder="Select" name="gender">
                                        <option value="">Select</option>
                                        <option value="male" {{ ($patient && $patient->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ ($patient && $patient->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ ($patient && $patient->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                    <small class="text-danger">{{ $errors->first('gender') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Age<sup class="text-danger">*</sup></label>
                                    <input type="number" value="{{ ($patient && $patient->age) ? $patient->age : old('age') }}" name="age" class="form-control form-control-md" placeholder="0">
                                    @error('age')
                                    <small class="text-danger">{{ $errors->first('age') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Mobile Number<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ ($patient && $patient->mobile_number) ? $patient->mobile_number : old('mobile_number') }}" name="mobile_number" maxlength="10" class="form-control form-control-md" placeholder="Mobile Number">
                                    @error('mobile_number')
                                    <small class="text-danger">{{ $errors->first('mobile_number') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Address <sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ ($patient && $patient->address) ? $patient->address : old('address') }}" name="address" class="form-control form-control-md" placeholder="Address">
                                    @error('address')
                                    <small class="text-danger">{{ $errors->first('address') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Appointment Date <sup class="text-danger">*</sup></label>
                                    <fieldset class="form-icon-group left-icon position-relative appo">
                                        <input type="text" value="{{ old('appointment_date') }}" name="appointment_date" class="form-control form-control-md dtpicker" placeholder="dd/mm/yyyy">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('appointment_date')
                                    <small class="text-danger">{{ $errors->first('appointment_date') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Branch<sup class="text-danger">*</sup></label>
                                    <select class="form-control select2 appo br" name="branch">
                                        <option value="">Select</option>
                                        @forelse($branches as $key => $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Doctor<sup class="text-danger">*</sup></label>
                                    <select class="form-control select2 appo dr" name="doctor">
                                        <option value="">Select</option>
                                        @forelse($doctors as $key => $doctor)
                                        <option value="{{ $doctor->id }}">{{ $doctor->doctor_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>                        
                                <div class="col-sm-2">
                                    <label class="form-label">Time<sup class="text-danger">*</sup></label>
                                    <select class="form-control select2 atime" name="appointment_time">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col-sm-5">
                                    <label class="form-label">Notes </label>
                                    <input type="text" name="notes" class="form-control form-control-md" placeholder="Notes">                                   
                                </div>
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
@endsection