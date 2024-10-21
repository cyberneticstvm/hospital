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
                        <form action="{{ route('medicine.create') }}" method="post">
                            @csrf
                            <input type="hidden" name="mid" value="{{ $medical_record->id }}" />
                            <input type="hidden" name="mrn" value="{{ $medical_record->mrn }}" />
                            <input type="hidden" name="" class="selFromBranch" value="{{ session()->get('branch') }}" />
                            <div class="row g-4">
                                <div class="col-sm-3">MRN: <h5 class="text-primary">{{ $medical_record->mrn }}</h5>
                                </div>
                                <div class="col-sm-3">Patient Name: <h5 class="text-primary">{{ $patient->patient_name }}</h5>
                                </div>
                                <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ $patient->patient_id }}</h5>
                                </div>
                                <div class="col-sm-3">Doctor Name: <h5 class="text-primary">{{ $doctor->doctor_name }}</h5>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Medicine Advice</label>
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
                                <div class="col-sm-2">
                                    <label class="form-label">Dosage</label>
                                    <input type="text" name="dosage[]" class="form-control form-control-md" placeholder="Eg: Daily 3 Drops" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Qty / NOs.</label>
                                    <input type='number' class='form-control form-control-md' name='qty[]' placeholder='0' />
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Notes.</label>
                                    <input type='text' class='form-control form-control-md' name='notes[]' placeholder='Notes' />
                                </div>
                                <div class="col-sm-1">
                                    <a class="medicineAdvise" href="javascript:void(0)"><i class="fa fa-plus fa-lg text-success"></i><a>
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