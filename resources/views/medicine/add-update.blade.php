@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Edit Patient Medicine Record</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('medicine.add.update.save', $medical_record->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            @if($medicine_record->isEmpty())
                            <div class="row mb-3">
                                <input type='hidden' name='price[]' value='0.00' />
                                <input type='hidden' name='discount[]' value='0.00' />
                                <input type='hidden' name='tax_amount[]' value='0.00' />
                                <input type='hidden' name='tax_percentage[]' value='0.00' />
                                <input type='hidden' name='total[]' value='0.00' />
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
                            </div>
                            @else
                            @php $c = 0; @endphp
                            @foreach($medicine_record as $pmr)
                            @php $c++; @endphp
                            <div class="row mb-3">
                                <input type='hidden' name='price[]' value='{{ $pmr->price }}' />
                                <input type='hidden' name='discount[]' value='{{ $pmr->discount }}' />
                                <input type='hidden' name='tax_amount[]' value='{{ $pmr->tax_amount }}' />
                                <input type='hidden' name='tax_percentage[]' value='{{ $pmr->tax_percentage }}' />
                                <input type='hidden' name='total[]' value='{{ $pmr->total }}' />
                                <div class="col-sm-2">
                                    @if($c == 1)<label class="form-label">Medicine Type</label>@endif
                                    <select class="form-control form-control-md select2 medType" name="medicine_type[]">
                                        <option value="0">Select</option>
                                        @foreach($mtypes as $medt)
                                        <option value="{{ $medt->id }}" {{ $pmr->medicine_type == $medt->id ? 'selected' : '' }}>{{ $medt->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('medicine_type')
                                    <small class="text-danger">{{ $errors->first('medicine_type') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    @if($c == 1)<label class="form-label">Medicine Advised</label>@endif
                                    <select class="form-control form-control-md select2 medAdvised" name="medicine_id[]">
                                        <option value="">Select</option>
                                        @foreach($medicines as $med)
                                        <option value="{{ $med->id }}" {{ $pmr->medicine == $med->id ? 'selected' : '' }}>{{ $med->product_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('medicine_id')
                                    <small class="text-danger">{{ $errors->first('medicine_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-1">
                                    @if($c == 1)<label class="form-label">Dosage</label>@endif
                                    <input type="text" name="dosage[]" class="form-control form-control-md dos" value="{{ $pmr->dosage }}" placeholder="Eg: Daily 3 Drops" />
                                </div>
                                <div class="col-sm-1">
                                    @if($c == 1)<label class="form-label">Duration</label>@endif
                                    <input type="text" name="duration[]" class="form-control form-control-md dos" value="{{ $pmr->duration }}" placeholder="" />
                                </div>
                                <div class="col-sm-1">
                                    @if($c == 1)<label class="form-label"><label class="form-label">Eye</label>@endif
                                        <select class="form-control form-control-md" name="eye[]">
                                            <option value="B" {{ ($pmr->eye == 'B') ? 'selected' : '' }}>Both</option>
                                            <option value="R" {{ ($pmr->eye == 'R') ? 'selected' : '' }}>RE</option>
                                            <option value="L" {{ ($pmr->eye == 'L') ? 'selected' : '' }}>LE</option>
                                            <option value="L" {{ ($pmr->eye == 'O') ? 'selected' : '' }}>Oral</option>
                                        </select>
                                </div>
                                <div class="col-sm-1">
                                    @if($c == 1)<label class="form-label">Qty / NOs.</label>@endif
                                    <input type='number' class='form-control form-control-md qty' name='qty[]' placeholder='0' value="{{ $pmr->qty }}" />
                                </div>
                                <div class="col-sm-2">
                                    @if($c == 1)<label class="form-label">Notes.</label>@endif
                                    <input type='text' class='form-control form-control-md' name='notes[]' value="{{ $pmr->notes }}" placeholder='Notes' />
                                </div>
                                @if($c == 1)
                                <div class="col-sm-1">
                                    <a class="medicineAdvise" href="javascript:void(0)"><i class="fa fa-plus fa-lg text-success"></i></a>
                                </div>
                                @else
                                <div class="col-sm-1"><i class="fa fa-trash text-danger" style="cursor:pointer" onClick="$(this).parent().parent().remove();"></i></div>
                                @endif
                            </div>
                            @endforeach
                            @endif
                            <div class="medicineAdviseContainer"></div>

                            <div class="col-sm-12 text-right">
                                <button type="button" onClick="history.back()" class="btn btn-danger">Cancel</button>
                                <button type="reset" class="btn btn-warning">Reset</button>
                                <button type="submit" class="btn btn-primary btn-submit">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection