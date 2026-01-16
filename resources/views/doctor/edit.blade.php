@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Edit Doctor</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('doctor.update', $doctor->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <div class="row g-4">
                                <div class="col-sm-4">
                                    <label class="form-label">Doctor Name<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $doctor->doctor_name }}" name="doctor_name" class="form-control form-control-md" placeholder="Doctor Name">
                                    @error('doctor_name')
                                    <small class="text-danger">{{ $errors->first('doctor_name') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Designation</label>
                                    <input type="text" value="{{ $doctor->designation }}" name="designation" class="form-control form-control-md" placeholder="Designation">
                                    @error('designation')
                                    <small class="text-danger">{{ $errors->first('designation') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Add. Qualification</label>
                                    <input type="text" value="{{ $doctor->additional_qualification }}" name="additional_qualification" class="form-control form-control-md" placeholder="Add. Qualification">
                                    @error('additional_qualification')
                                    <small class="text-danger">{{ $errors->first('additional_qualification') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Registration No.</label>
                                    <input type="text" value="{{ $doctor->reg_no }}" name="reg_no" class="form-control form-control-md" placeholder="Registration No.">
                                    @error('reg_no')
                                    <small class="text-danger">{{ $errors->first('reg_no') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Date of Join<sup class="text-danger">*</sup></label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ date('d/M/Y', strtotime($doctor->date_of_join)) }}" name="date_of_join" class="form-control form-control-md dtpicker" placeholder="dd/mm/yyyy">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('date_of_join')
                                    <small class="text-danger">{{ $errors->first('date_of_join') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Type<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2" data-placeholder="Select" name="doc_type">
                                        <option value="">Select</option>
                                        @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ $doctor->doc_type == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('doc_type')
                                    <small class="text-danger">{{ $errors->first('doc_type') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Doctor Fee<sup class="text-danger">*</sup></label>
                                    <input type="number" value="{{ $doctor->doctor_fee }}" name="doctor_fee" class="form-control form-control-md" placeholder="0.00">
                                    @error('doctor_fee')
                                    <small class="text-danger">{{ $errors->first('doctor_fee') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Department<sup class="text-danger">*</sup> <small class="text-info">(Multiple selection enabled)</small></label>
                                    <select class="form-control form-control-md show-tick ms select2" multiple data-placeholder="Select" name="department_id[]">
                                        <option value="">Select</option>
                                        @foreach($departments as $dept)
                                        @php $selected = '' @endphp
                                        @foreach($doctor_depts as $docdept)
                                        @if($docdept->department_id == $dept->id)
                                        {{ $selected = 'selected' }}
                                        @endif
                                        @endforeach
                                        <option value="{{ $dept->id }}" {{ $selected }}>{{ $dept->department_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                    <small class="text-danger">{{ $errors->first('department_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-12 text-right">
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