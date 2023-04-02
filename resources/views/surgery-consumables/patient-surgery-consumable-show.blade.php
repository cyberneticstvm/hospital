@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Create Surgery Consumables</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('patient.surgey.consumable.save') }}" method="post">
                            @csrf
                            <input type="hidden" name="medical_record_id" value="{{ $mrecord->id }}" />
                            <input type="hidden" name="branch" value="{{ $mrecord->branch }}" />
                            <input type="hidden" name="patient_id" value="{{ $mrecord->patient_id }}" />
                            <div class="row g-4">
                                <div class="col-sm-3">Medical Record No: <h5 class="text-primary">{{ $mrecord->id }}</h5></div>
                                <div class="col-sm-3">Patient Name: <h5 class="text-primary">{{ $patient->patient_name }} </h5></div>
                                <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ $patient->patient_id }}</h5></div>
                            </div>                            
                            <div class="row mt-3">
                                <div class="col-sm-3">
                                    <label class="form-label">Surgery Type</label>
                                    <select class="form-control form-control-md show-tick ms select2 surgeryConsumable" name="surgery_id" required>
                                    <option value="">Select</option>
                                        @foreach($stypes as $stype)
                                            <option value="{{ $stype->id }}" {{ old('stype') == $stype->id ? 'selected' : '' }}>{{ $stype->surgery_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('surgery_id')
                                    <small class="text-danger">{{ $errors->first('surgery_id') }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12 text-end">
                                    <a class="addSurgeryConsumable" data-category="consumable" href="javascript:void(0)"><i class="fa fa-plus fa-lg text-success"></i></a>                                    
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-3">
                                    <label class="form-label">Consumable</label>
                                    <select class="form-control form-control-md show-tick ms select2 surgeryConsumable" name="consumable_id[]" required>
                                    <option value="">Select</option>
                                        @foreach($consumables as $key => $co)
                                            <option value="{{ $co->id }}" {{ old('consumable_id') == $co->id ? 'selected' : '' }}>{{ $co->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('consumable_id')
                                    <small class="text-danger">{{ $errors->first('consumable_id') }}</small>
                                    @enderror
                                </div>
                                <div class='col-sm-2'>
                                    <label class="form-label">Qty<sup class="text-danger">*</sup></label>
                                    <input type='number' name='qty[]' class='form-control' placeholder='0' />
                                    @error('qty')
                                    <small class="text-danger">{{ $errors->first('qty') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-1 mt-3">
                                    <a href='javascript:void(0)' onClick='$(this).parent().parent().remove()'><i class='fa fa-trash text-danger'></i></a>                                    
                                </div>
                            </div>
                            <div class="consumablesRow"></div>
                            <div class="consumablesRow1"></div>
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