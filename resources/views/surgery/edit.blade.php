@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Edit Surgery</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('surgery.update', $surgery->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <input type='hidden' name='medical_record_id' value="{{ $surgery->medical_record_id }}" />
                            <input type='hidden' name='doctor_id' value="{{ $surgery->doctor_id }}" />
                            <input type='hidden' name='patient_id' value="{{ $surgery->patient_id }}" />
                            <div class="row g-4">
                                <div class="col-sm-4">Patient Name: <h5 class="text-primary">{{ $patient->patient_name }}</h5></div>
                                <div class="col-sm-4">Patient ID: <h5 class="text-primary">{{ $patient->patient_id }}</h5></div>
                                <div class="col-sm-4">Doctor Name: <h5 class="text-primary">{{ $doctor->doctor_name }}</h5></div>
                                <div class="col-sm-6">
                                    <label class="form-label">Surgery Type <sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="surgery_type" >
                                        <option value="">Select</option>
                                        @foreach($stypes as $stype)
                                        <option value="{{ $stype->id }}" {{ ($stype->id == $surgery->surgery_type) ? 'selected' : '' }}>{{ $stype->surgery_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('surgery_type')
                                    <small class="text-danger">{{ $errors->first('surgery_type') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Surgery Date <sup class="text-danger">*</sup></label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ ($surgery->surgery_date) ? date('d/M/Y', strtotime($surgery->surgery_date)) : '' }}" name="surgery_date" class="form-control form-control-md dtpicker" placeholder="dd/mm/yyyy">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('surgery_date')
                                    <small class="text-danger">{{ $errors->first('surgery_date') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Surgeon <sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="surgeon" >
                                        <option value="">Select</option>
                                        @foreach($doctors as $doc)
                                        <option value="{{ $doc->id }}" {{ ($doc->id == $surgery->surgeon) ? 'selected' : '' }}>{{ $doc->doctor_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('surgeon')
                                    <small class="text-danger">{{ $errors->first('surgeon') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Eye <sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="eye" >
                                        <option value="NA" {{ ($surgery->eye == 'NA') ? 'selected' : '' }}>Select</option>
                                        <option value="right" {{ ($surgery->eye == 'right') ? 'selected' : '' }}>Right</option>
                                        <option value="left" {{ ($surgery->eye == 'left') ? 'selected' : '' }}>Left</option>
                                        <option value="both" {{ ($surgery->eye == 'both') ? 'selected' : '' }}>Both</option>
                                    </select>
                                    @error('eye')
                                    <small class="text-danger">{{ $errors->first('eye') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Surgery Fee </label>
                                    <input type="number" value="{{ $surgery->surgery_fee }}" name="surgery_fee" class="form-control form-control-md" placeholder="0.00">
                                    @error('surgery_fee')
                                    <small class="text-danger">{{ $errors->first('surgery_fee') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Surgery Status <sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="status" >
                                        <option value="">Select</option>
                                        @foreach($status as $st)
                                        <option value="{{ $st->id }}" {{ ($st->id == $surgery->status) ? 'selected' : '' }}>{{ $st->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                    <small class="text-danger">{{ $errors->first('status') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">Notes / Remarks </label>
                                    <textarea name="remarks" class="form-control form-control-md" placeholder="Remarks" rows="5">{{ $surgery->remarks }}</textarea>
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