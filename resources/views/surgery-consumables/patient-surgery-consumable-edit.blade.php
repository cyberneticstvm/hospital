@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Update Surgery Consumables</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('patient.surgey.consumable.update', $psc->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="medical_record_id" value="{{ $psc->medical_record_id }}" />
                            <input type="hidden" name="branch" value="{{ $psc->branch }}" />
                            <input type="hidden" name="patient_id" value="{{ $psc->patient_id }}" />
                            <div class="row g-4">
                                <div class="col-sm-3">Medical Record No: <h5 class="text-primary">{{ $psc->medical_record_id }}</h5></div>
                                <div class="col-sm-3">Patient Name: <h5 class="text-primary">{{ $psc->patient->patient_name }} </h5></div>
                                <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ $psc->patient->patient_id }}</h5></div>
                            </div>                            
                            <div class="row mt-3">
                                <div class="col-sm-3">
                                    <label class="form-label">Surgery Type</label>
                                    <select class="form-control form-control-md show-tick ms select2 surgeryConsumable" name="surgery_id" required>
                                    <option value="">Select</option>
                                        @foreach($stypes as $stype)
                                            <option value="{{ $stype->id }}" {{ $psc->surgery_id == $stype->id ? 'selected' : '' }}>{{ $stype->surgery_name }}</option>
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
                            @forelse($psc->psclist as $key => $val)
                            <div class="row mt-3">
                                <div class="col-sm-3">
                                    <label class="form-label">Consumable</label>
                                    <select class="form-control form-control-md show-tick ms select2 surgeryConsumable" name="consumable_id[]" required>
                                    <option value="">Select</option>
                                        @foreach($consumables as $key => $co)
                                            <option value="{{ $co->id }}" {{ ($val->consumable_id == $co->id) ? 'selected' : '' }}>{{ $co->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('consumable_id')
                                    <small class="text-danger">{{ $errors->first('consumable_id') }}</small>
                                    @enderror
                                </div>
                                <div class='col-sm-2'>
                                    <label class="form-label">Qty<sup class="text-danger">*</sup></label>
                                    <input type='number' name='qty[]' class='form-control' value="{{ $val->qty }}" placeholder='0' />
                                    @error('qty')
                                    <small class="text-danger">{{ $errors->first('qty') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-1 mt-3">
                                    <a href='javascript:void(0)' onClick='$(this).parent().parent().remove()'><i class='fa fa-trash text-danger'></i></a>                                    
                                </div>
                            </div>
                            @empty
                            @endforelse
                            <div class="consumablesRow"></div>
                            <div class="consumablesRow1"></div>
                            <div class="row mt-5">
                                <div class="col-sm-8">
                                    <label class="form-label">Notes / Remarks</label>
                                    <input class="form-control" type="text" name="notes" value="{{ $psc->notes }}" placeholder="Notes / Remarks">
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Discount if any</label>
                                    <input class="form-control" type="number" name="discount" value="{{ $psc->discount }}" placeholder="0.00" >
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
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