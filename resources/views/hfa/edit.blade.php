@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Create HFA</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body table-responsive">
                        <div class="row g-4 mb-3">
                            <div class="col-sm-3">MR.ID: <h5 class="text-primary">{{ $mrecord->id }}</h5></div>
                            <div class="col-sm-3">Patient Name: <h5 class="text-primary">{{ ($patient) ? $patient->patient_name : '' }}</h5></div>
                            <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ ($patient) ? $patient->patient_id : '' }}</h5></div>
                        </div>
                        <form action="{{ route('hfa.update', $hfa->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="medical_record_id" value="{{ $mrecord->id }}" />
                            <input type="hidden" name="patient_id" value="{{ ($patient) ? $patient->id : 0 }}" />
                            <input type="hidden" name="branch" value="{{ $mrecord->branch }}" />
                            <div class="row g-4">                                
                                <div class="col-sm-6">
                                    <label class="form-label">Upload document</label>
                                    <input type="file" name="document" class="form-control form-control-md" />
                                    <small><a href="{{ ($hfa->document) ? public_path().'/storage/'.$hfa->document : '#' }}" target="_blank">Document Uploaded</a></small>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Notes</label>
                                    <input type="text" name="notes" value="{{ $hfa->notes }}" class="form-control form-control-md" />
                                </div>
                            </div>
                            <div class="row g-4 mt-3">                                
                                <div class="col-sm-6">
                                    <label class="form-label">Procedures<sup class="text-danger">*</sup> <small class="text-info">(Multiple selection enabled)</small></label>
                                    <select class="form-control form-control-md show-tick ms select2" multiple data-placeholder="Select" name="procedure[]" required="required">
                                    <option value="">Select</option>
                                    @foreach($procedures as $proc)
                                        @php $selected = ''; @endphp
                                        @foreach($advised as $key => $value)
                                            @if($proc->id == $value->procedure)
                                                {{ $selected = 'selected' }}
                                            @endif
                                        @endforeach
                                        <option value="{{ $proc->id }}" {{ $selected }}>{{ $proc->name }}</option>
                                    @endforeach
                                    </select>
                                    @error('procedure')
                                    <small class="text-danger">{{ $errors->first('procedure') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Test Status <sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md select2" data-placeholder="Select" name="status" >
                                        <option value="">Select</option>
                                        @foreach($status as $st)
                                        <option value="{{ $st->id }}" {{ ($st->id == $hfa->status) ? 'selected' : '' }}>{{ $st->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                    <small class="text-danger">{{ $errors->first('status') }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
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