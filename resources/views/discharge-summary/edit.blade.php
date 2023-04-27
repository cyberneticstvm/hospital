@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Update Discharge Summary</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body table-responsive">
                        <form action="{{ route('dsummary.update', $ds->id) }}" method="post">
                            @csrf
                            @method("PUT")
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
                                    <input class="form-control" type="date" name="doa" value="{{ $ds->doa->format('Y-m-d') }}"/>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">D.O.S</label>
                                    <input class="form-control" type="date" name="dos" value="{{ $ds->dos->format('Y-m-d') }}"/>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">D.O.D</label>
                                    <input class="form-control" type="date" name="dod" value="{{ $ds->dod->format('Y-m-d') }}"/>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Branch</label>
                                    <input class="form-control" type="text" value="{{ $mrecord->branches->branch_name }}" readonly/>
                                </div>  
                                <div class="col-sm-6">
                                    <label class="form-label">Reason for Admission</label>
                                    <input class="form-control" type="text" value="{{ $ds->reason_for_admission }}" name="reason_for_admission" placeholder="Reason for Admission"/>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Findings</label>
                                    <textarea class="form-control" name="findings" value="{{ $ds->findings }}" placeholder="Findings"></textarea>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Investigation Results</label>
                                    <textarea class="form-control" name="investigation_result" value="{{ $ds->investigation_result }}" placeholder="Investigation Results"></textarea>
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">General Examination</label>
                                    <input class="form-control" type="text" value="{{ $ds->general_examination }}" name="general_examination" placeholder="General Examination"/>
                                </div>                              
                                <div class="col-sm-11">
                                    @php $olds = explode(',', $mrecord->diagnosis); @endphp
                                    <label class="form-label">Diagnosis<small class="text-info">(Multiple selection enabled)</small></label>
                                    {!! Form::select('diagnosis[]', $diagnosis,  $ds->diagnosis()->pluck('diagnosis')->toArray(), ['class' => 'form-control select2', 'multiple', 'id' => 'diagnosisSelect']) !!}
                                    @error('diagnosis')
                                    <small class="text-danger">{{ $errors->first('diagnosis') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-1">
                                    <a data-bs-toggle="modal" href="#diagnosisModal"><i class="fa fa-plus fa-lg text-success"></i></a>
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">Procedures<small class="text-info">(Multiple selection enabled)</small></label>
                                    {!! Form::select('procedures[]', $procedures,  $ds->procedures()->pluck('procedure')->toArray(), ['class' => 'form-control select2', 'multiple']) !!}
                                    @error('procedures')
                                    <small class="text-danger">{{ $errors->first('procedures') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">Condition at Discharge</label>
                                    <input class="form-control" type="text" value="{{ $ds->discharge_condition }}" name="discharge_condition" placeholder="Condition at Discharge"/>
                                </div>
                                <div class="col-sm-9">
                                    <label class="form-label">Medication<sup class="text-danger">*</sup></label>
                                    <select class="form-control" name="medication" required>
                                        <option value="">Select</option>
                                        <option value="Left Eye Only" {{ ($ds->medication == 'Left Eye Only') ? 'selected' : '' }}>Left Eye Only</option>
                                        <option value="Right Eye Only" {{ ($ds->medication == 'Right Eye Only') ? 'selected' : '' }}>Right Eye Only</option>
                                        <option value="Both" {{ ($ds->medication == 'Both') ? 'selected' : '' }}>Both</option>
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
                                @forelse($ds->medicines as $key => $value)
                                <div class="row mt-3">
                                    <div class="col-sm-3">
                                        @if($key == 0)<label class="form-label">Type<sup class="text-danger">*</sup></label>@endif
                                        {!! Form::select('medicine_type[]', $types,  $value->type, ['class' => 'form-control select2 medType', 'placeholder' => 'Select', 'required' => 'required']) !!}
                                    </div>
                                    <div class="col-sm-3">
                                        @if($key == 0)<label class="form-label">Medicine<sup class="text-danger">*</sup></label>@endif
                                        {!! Form::select('product_id[]', $medicines,  $value->medicine, ['class' => 'form-control select2 medAdvised', 'placeholder' => 'Select', 'required' => 'required']) !!}
                                    </div>                                    
                                    <div class="col-sm-3">
                                        @if($key == 0)<label class="form-label">Dosage<sup class="text-danger">*</sup></label>@endif
                                        <input type="text" class="form-control" placeholder="Dosage" value="{{ $value->notes }}" name="notes[]" required/>
                                    </div>
                                    <div class="col-sm-2">
                                        @if($key == 0)<label class="form-label">Notes</label>@endif
                                        <input type="text" class="form-control" placeholder="Notes" name="qty[]" value="{{ $value->qty }}" required/>
                                    </div>
                                    <div class='col-sm-1'><a href='javascript:void(0)' onClick="$(this).parent().parent().remove()"><i class='fa fa-trash text-danger'></i></a></div>
                                </div>
                                @empty
                                @endforelse 
                            </div>
                            <div class="row g-4 mt-3">
                                <div class="col-sm-12">
                                    <label class="form-label">Post-operative Instruction<small class="text-info">(Multiple selection enabled)</small></label>
                                    {!! Form::select('instructions[]', $postinstructions->pluck('name', 'id'),  $ds->instructions()->pluck('instruction_id')->toArray(), ['class' => 'form-control select2', 'multiple']) !!}
                                    @error('post')
                                    <small class="text-danger">{{ $errors->first('post') }}</small>
                                    @enderror
                                </div>
                                @forelse($ds->reviews as $key => $value)
                                <div class="col-sm-3">
                                    <label class="form-label">{{ $key+1 }} Review Date</label>
                                    <input type="date" name="review_date[]" value="{{ ($value && $value->review_date) ? $value->review_date->format('Y-m-d') : '' }}" class="form-control">
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">{{ $key+1 }} Review Time</label>
                                    <input type="text" name="review_time[]" value="{{ $value->review_time }}" class="form-control" placeholder="First Review Time">
                                </div>
                                @empty
                                @endforelse
                                <div class="col-sm-12">
                                    <label class="form-label">Special Instructions</label>
                                    <textarea class="form-control" name="special_instruction" rows="10">{{ $ds->special_instruction }}
                                    </textarea>
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Doctor<sup class="text-danger">*</sup></label>
                                    {!! Form::select('doctor', $doctors->pluck('doctor_name', 'id'),  $ds->doctor, ['class' => 'form-control select2', 'placeholder' => 'Select', 'required' => 'required']) !!}
                                </div>
                            </div>
                            <div class="row g-4 mt-1">
                                <div class="col-sm-12 text-end">
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
<div class="modal fade" id="diagnosisModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form name="frm-diagnosis" id="frm-diagnosis" action="/symptom/create/">
                <input type="hidden" class="ddl" value="diagnosisSelect" />
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="exampleModalLiveLabel">Add Diagnosis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col">
                            <label class="form-label">Diagnosis Name<sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control form-control-md" name="diagnosis_name" placeholder="Diagnosis Name"/>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col message text-success"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ajax-submit btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection