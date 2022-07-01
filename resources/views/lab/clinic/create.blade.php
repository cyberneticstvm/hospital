@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Create Lab Test - Clinic</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('lab.clinic.save') }}" method="post">
                            @csrf
                            <input type="hidden" name="medical_record_id" value="{{ $mrecord->id }}" />
                            <input type="hidden" id="age" value="{{ $age }}" />
                            <div class="row g-4">
                                <div class="col-sm-3">Medical Record No: <h5 class="text-primary">{{ $mrecord->id }}</h5></div>
                                <div class="col-sm-3">Patient Name / Age: <h5 class="text-primary">{{ $patient->patient_name }} / {{ $age }}</h5></div>
                                <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ $patient->patient_id }}</h5></div>
                                <div class="col-sm-3">Doctor Name: <h5 class="text-primary">{{ $doctor->doctor_name }}</h5></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-8">
                                    <label class="form-label">Tests<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2 selLabTest" data-placeholder="Select" name="test_id[]" required="required">
                                    <option value="">Select</option>
                                        @foreach($labtests as $test)
                                            <option value="{{ $test->id }}" {{ old('test_id') == $test->id ? 'selected' : '' }}>{{ $test->lab_type_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('test_id')
                                    <small class="text-danger">{{ $errors->first('test_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Test From<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2" data-placeholder="Select" name="tested_from[]">
                                    <option value="">Select</option>
                                        <option value="1" {{ old('tested_from') == 1 ? 'selected' : '' }}>Devi Laboratory</option>
                                        <option value="0" {{ old('tested_from') == 0 ? 'selected' : '' }}>Outside Laboratory</option>
                                    </select>
                                    @error('tested_from')
                                    <small class="text-danger">{{ $errors->first('tested_from') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-1">
                                    <a class="addLabTest" data-category="clinic" href="javascript:void(0)"><i class="fa fa-plus fa-lg text-success"></i><a>                                    
                                </div>
                                <div class="labtestRow"></div>
                            </div>
                            <div class="row g-4 mt-3">
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