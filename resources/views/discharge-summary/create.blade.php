@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Create Discharge Summary</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body table-responsive">
                        <form action="{{ route('dsummary.save') }}" method="post">
                            @csrf
                            <input type="hidden" name="branch" value="{{ $mrecord->branch }}" />
                            <input type="hidden" name="medical_record_id" value="{{ $mrecord->id }}" />
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}" />
                            <div class="row g-4 mt-3">
                                <div class="col-sm-4">
                                    <label class="form-label">Name</label>
                                    <input class="form-control" type="text" value="{{ $patient->patient_name }}" readonly/>
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Age / Gender</label>
                                    <input class="form-control" type="text" value="{{ $patient->age.' / '.$patient->gender }}" readonly/>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">D.O.A</label>
                                    <input class="form-control" type="date" name="doa" value="{{ $mrecord->created_at->format('Y-m-d') }}"/>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">D.O.S</label>
                                    <input class="form-control" type="date" name="dos" value="{{ $mrecord->created_at->format('Y-m-d') }}"/>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">D.O.D</label>
                                    <input class="form-control" type="date" name="dod" value="{{ $mrecord->created_at->format('Y-m-d') }}"/>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Branch</label>
                                    <input class="form-control" type="text" value="{{ $mrecord->branches->branch_name }}" readonly/>
                                </div>  
                                <div class="col-sm-6">
                                    <label class="form-label">Reason for Admission</label>
                                    <input class="form-control" type="text" value="" name="reason_for_admission" placeholder="Reason for Admission"/>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Findings</label>
                                    <textarea class="form-control" name="findings" placeholder="Findings"></textarea>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Investigation Results</label>
                                    <textarea class="form-control" name="investigation_result" placeholder="Investigation Results"></textarea>
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">General Examination</label>
                                    <input class="form-control" type="text" name="general_examination" placeholder="General Examination"/>
                                </div>                              
                                <div class="col-sm-12">
                                    @php $olds = explode(',', $mrecord->diagnosis); @endphp
                                    <label class="form-label">Diagnosis<sup class="text-danger">*</sup> <small class="text-info">(Multiple selection enabled)</small></label>
                                    {!! Form::select('diagnosis[]', $diagnosis,  $olds, ['class' => 'form-control select2', 'multiple']) !!}
                                    @error('diagnosis')
                                    <small class="text-danger">{{ $errors->first('diagnosis') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">Procedures<sup class="text-danger">*</sup><small class="text-info">(Multiple selection enabled)</small></label>
                                    {!! Form::select('procedures[]', $procedures,  $mrecord->procedures()->pluck('procedure')->toArray(), ['class' => 'form-control select2', 'multiple']) !!}
                                    @error('procedures')
                                    <small class="text-danger">{{ $errors->first('procedures') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">Condition at Discharge</label>
                                    <input class="form-control" type="text" name="discharge_condition" placeholder="Condition at Discharge"/>
                                </div>
                                <div class="col-sm-9">
                                    <label class="form-label">Medication</label>
                                    <select class="form-control" name="medication" required>
                                        <option value="">Select</option>
                                        <option value="Left Eye Only">Left Eye Only</option>
                                        <option value="Right Eye Only">Right Eye Only</option>
                                        <option value="Both">Both</option>
                                    </select>
                                    @error('medication')
                                    <small class="text-danger">{{ $errors->first('medication') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3 text-end">
                                    <label class="form-label"><a href="javascript:void(0)" class="addMedication"><i class="fa fa-plus fa-lg text-primary"></i></a></label>
                                </div>
                            </div>
                            <div class="row g-4 mt-1 medication">
                                <div class="col-sm-4">
                                    <label class="form-label">Medication</label>
                                    {!! Form::select('product_id[]', $medicines,  '', ['class' => 'form-control select2', 'placeholder' => 'Select', 'required' => 'required']) !!}
                                </div>
                                <div class="col-sm-7">
                                    <label class="form-label">Notes</label>
                                    <input type="text" class="form-control" placeholder="Notes" name="notes[]" required/>
                                </div> 
                            </div>
                            <div class="row g-4 mt-3">
                                <div class="col-sm-12">
                                    <label class="form-label">Post-operative Instruction<small class="text-info">(Multiple selection enabled)</small></label>
                                    {!! Form::select('instructions[]', $postinstructions->pluck('name', 'id'),  $postinstructions->pluck('id')->toArray(), ['class' => 'form-control select2', 'multiple']) !!}
                                    @error('post')
                                    <small class="text-danger">{{ $errors->first('post') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">First Review Date</label>
                                    <input type="date" name="review_date[]" class="form-control">
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">First Review Time</label>
                                    <input type="text" name="review_time[]" class="form-control" placeholder="First Review Time">
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Second Review Date</label>
                                    <input type="date" name="review_date[]" class="form-control">
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Second Review Time</label>
                                    <input type="text" name="review_time[]" class="form-control" placeholder="Second Review Time">
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Third Review Date</label>
                                    <input type="date" name="review_date[]" class="form-control">
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Third Review Time</label>
                                    <input type="text" name="review_time[]" class="form-control" placeholder="Third Review Time">
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">Special Instructions</label>
                                    <textarea class="form-control" name="special_instruction" rows="10">Contact immediately if there is, 1). Loss of vision 2). Increasing pain or redness 3). Pus discharge from the eye
                                    </textarea>
                                </div>
                            </div>
                            <div class="row g-4 mt-1">
                                <div class="col-sm-12 text-end">
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