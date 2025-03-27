@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Create Patient Consultation</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('patient_reference.create') }}" method="post">
                            @csrf
                            <input type="hidden" name="pid" value="{{ $patient->id }}" />
                            <input type="hidden" name="review" value="{{ $review }}" />
                            <input type="hidden" name="appointment_id" value="{{ $appid }}" />
                            <div class="row g-4">
                                <div class="col-sm-6">Patient Name: <h5 class="text-primary">{{ $patient->patient_name }}</h5>
                                </div>
                                <div class="col-sm-6">Patient ID: <h5 class="text-primary">{{ $patient->patient_id }}</h5>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Department<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2" data-placeholder="Select" name="department_id">
                                        <option value="">Select</option>
                                        @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                    <small class="text-danger">{{ $errors->first('department_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Doctor<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2" data-placeholder="Select" name="doctor_id">
                                        <option value="">Select</option>
                                        @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>{{ $doctor->doctor_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('doctor')
                                    <small class="text-danger">{{ $errors->first('doctor') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Purpose of Visit<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2" data-placeholder="Select" name="consultation_type">
                                        <option value="">Select</option>
                                        @foreach($ctypes as $ctype)
                                        <option value="{{ $ctype->id }}" {{ old('consultation_type') == $ctype->id ? 'selected' : '' }}>{{ $ctype->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('consultation_type')
                                    <small class="text-danger">{{ $errors->first('consultation_type') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Camp Type<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2" data-placeholder="Select" name="camp_id">
                                        <option value="0">Select</option>
                                        @foreach($camps as $camp)
                                        <option value="{{ $camp->id }}" {{ $campid == $camp->id ? 'selected' : '' }}>{{ $camp->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('camp_id')
                                    <small class="text-danger">{{ $errors->first('camp_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Royalty Card Type</label>
                                    <select class="form-control form-control-md show-tick ms select2" data-placeholder="Select" name="rc_type">
                                        <option value="">Select</option>
                                        @foreach($rcards as $card)
                                        <option value="{{ $card->id }}">{{ $card->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('rc_type')
                                    <small class="text-danger">{{ $errors->first('rc_type') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Royalty Card Number</label>
                                    <input type="text" class="form-control" name="rc_number" value="{{ old('rc_number') }}" placeholder="Royalty Card Number" />
                                    @error('rc_number')
                                    <small class="text-danger">{{ $errors->first('rc_number') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Notes</label>
                                    <textarea class='form-control' name="notes" placeholder="Notes"></textarea>
                                </div>
                                <div class="col-sm-12 text-right">
                                    <button type="button" onClick="history.back()" class="btn btn-danger">Cancel</button>
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