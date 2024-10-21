@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Create Patient Medicine</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('medicine.create', $medical_record->id) }}" method="post">
                            @csrf
                            <input type="hidden" name="mid" value="{{ $medical_record->id }}" />
                            <input type="hidden" name="mrn" value="{{ $medical_record->mrn }}" />
                            <input type="hidden" name="" class="selFromBranch" value="{{ session()->get('branch') }}" />
                            <input type='hidden' name='price[]' value='0.00' />
                            <input type='hidden' name='discount[]' value='0.00' />
                            <input type='hidden' name='tax_amount[]' value='0.00' />
                            <input type='hidden' name='tax_percentage[]' value='0.00' />
                            <input type='hidden' name='total[]' value='0.00' />
                            <div class="row g-4">
                                <div class="col-sm-3">MRN: <h5 class="text-primary">{{ $medical_record->mrn }}</h5>
                                </div>
                                <div class="col-sm-3">Patient Name: <h5 class="text-primary">{{ $patient->patient_name }}</h5>
                                </div>
                                <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ $patient->patient_id }}</h5>
                                </div>
                                <div class="col-sm-3">Doctor Name: <h5 class="text-primary">{{ $doctor->doctor_name }}</h5>
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Medicine Type</label>
                                    <select class="form-control form-control-md select2 medType" name="medicine_type[]">
                                        <option value="0">Select</option>
                                        @foreach($mtypes as $medt)
                                        <option value="{{ $medt->id }}" {{ old('type') == $medt->id ? 'selected' : '' }}>{{ $medt->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('medicine_type')
                                    <small class="text-danger">{{ $errors->first('medicine_type') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3 asd">
                                    <label class="form-label">Medicine Advised</label>
                                    <select class="form-control form-control-md select2 medAdvised" name="medicine_id[]">
                                        <option value="">Select</option>

                                    </select>
                                    @error('medicine_id')
                                    <small class="text-danger">{{ $errors->first('medicine_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-1">
                                    <label class="form-label">Dosage</label>
                                    <input type="text" name="dosage[]" class="form-control form-control-md dos" placeholder="Eg: Daily 3 Drops" />
                                </div>
                                <div class="col-sm-1">
                                    <label class="form-label">Duration</label>
                                    <input type="text" name="duration[]" class="form-control form-control-md dos" placeholder="Duration" />
                                </div>
                                <div class="col-sm-1">
                                    <label class="form-label">Eye</label>
                                    <select class="form-control form-control-md" name="eye[]">
                                        <option value="B">Both</option>
                                        <option value="R">RE</option>
                                        <option value="L">LE</option>
                                        <option value="O">Oral</option>
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <label class="form-label">Qty / NOs.</label>
                                    <input type='number' class='form-control form-control-md qty' name='qty[]' placeholder='0' />
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Notes.</label>
                                    <input type='text' class='form-control form-control-md' name='notes[]' placeholder='Notes' />
                                </div>
                                <div class="col-sm-1">
                                    <a class="medicineAdvise" href="javascript:void(0)"><i class="fa fa-plus fa-lg text-success"></i></a>
                                </div>
                                <div class="medicineAdviseContainer">

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