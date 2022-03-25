@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Add New Patient</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('patient.create') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-4">
                                    <label class="form-label">Patient Name<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ old('patient_name') }}" name="patient_name" class="form-control form-control-md" placeholder="Patient Name">
                                    @error('patient_name')
                                    <small class="text-danger">{{ $errors->first('patient_name') }}</small>
                                    @enderror
                                </div>                                
                                <div class="col-sm-2">
                                    <label class="form-label">Gender<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md" data-placeholder="Select" name="gender">
                                        <option value="">Select</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                    <small class="text-danger">{{ $errors->first('gender') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Date of Birth<sup class="text-danger">*</sup></label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ old('dob') }}" name="dob" class="form-control form-control-md dtpicker" placeholder="dd/mm/yyyy">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('dob')
                                    <small class="text-danger">{{ $errors->first('dob') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Mobile Number<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ old('mobile_number') }}" name="mobile_number" class="form-control form-control-md" placeholder="Mobile Number">
                                    @error('mobile_number')
                                    <small class="text-danger">{{ $errors->first('mobile_number') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" value="{{ old('email') }}" name="email" class="form-control form-control-md" placeholder="Email">
                                    @error('email')
                                    <small class="text-danger">{{ $errors->first('email') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Contact Person Name</label>
                                    <input type="text" value="{{ old('contact_person_name') }}" name="contact_person_name" class="form-control form-control-md" placeholder="Contact Person Name">
                                    @error('contact_person_name')
                                    <small class="text-danger">{{ $errors->first('contact_person_name') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Address<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ old('address') }}" name="address" class="form-control form-control-md" placeholder="Address">
                                    @error('address')
                                    <small class="text-danger">{{ $errors->first('address') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">City<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2" data-placeholder="Select" name="city">
                                    <option value="">Select</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city') == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>
                                    @endforeach
                                    </select>
                                    @error('city')
                                    <small class="text-danger">{{ $errors->first('city') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">State<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2" data-placeholder="Select" name="state">
                                    <option value="">Select</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}" {{ old('state') == $state->id ? 'selected' : '' }}>{{ $state->state_name }}</option>
                                    @endforeach
                                    </select>
                                    @error('state')
                                    <small class="text-danger">{{ $errors->first('state') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Country<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2" data-placeholder="Select" name="country">
                                    <option value="">Select</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ old('country') == $country->id ? 'selected' : '' }}>{{ $country->country_name }}</option>
                                    @endforeach
                                    </select>
                                    @error('country')
                                    <small class="text-danger">{{ $errors->first('country') }}</small>
                                    @enderror
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