@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Update Pachymetry</h5>
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
                        <form action="{{ route('pachymetry.update', $pachymetry->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="medical_record_id" value="{{ $mrecord->id }}" />
                            <input type="hidden" name="patient_id" value="{{ ($patient) ? $patient->id : 0 }}" />
                            <input type="hidden" name="branch" value="{{ $mrecord->branch }}" />
                            <div class="row g-4">                                
                                <div class="col-sm-6">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="img1" class="form-control form-control-md pachy" />
                                    <input type="text" name="img1_value" placeholder="Description" value="{{ $pachymetry->img1_value }}" class="form-control form-control-md" />
                                    <div class="container"><img src="{{ ($pachymetry->img1) ? public_path().'/storage/'.$pachymetry->img1 : '' }}" class="img1 img-fluid"/></div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="img2" class="form-control form-control-md pachy" />
                                    <input type="text" name="img2_value" placeholder="Description" value="{{ $pachymetry->img2_value }}" class="form-control form-control-md" />
                                    <div class="container"><img src="{{ ($pachymetry->img2) ? public_path().'/storage/'.$pachymetry->img2 : '' }}" class="img2 img-fluid"/></div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="img3" class="form-control form-control-md pachy" />
                                    <input type="text" name="img3_value" placeholder="Description" value="{{ $pachymetry->img3_value }}" class="form-control form-control-md" />
                                    <div class="container"><img src="{{ ($pachymetry->img3) ? public_path().'/storage/'.$pachymetry->img3 : '' }}" class="img3 img-fluid"/></div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="img4" class="form-control form-control-md pachy" />
                                    <input type="text" name="img4_value" placeholder="Description" value="{{ $pachymetry->img4_value }}" class="form-control form-control-md" />
                                    <div class="container"><img src="{{ ($pachymetry->img4) ? public_path().'/storage/'.$pachymetry->img4 : '' }}" class="img4 img-fluid"/></div>
                                </div>
                            </div>
                            <div class="row g-4 mt-3">                                
                            <div class="col-sm-6">
                                    <label class="form-label">Procedures<sup class="text-danger">*</sup> <small class="text-info">(Multiple selection enabled)</small></label>
                                    <select class="form-control form-control-md show-tick ms select2" multiple data-placeholder="Select" name="procedure[]">
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
                            </div>
                            <div class="row g-4 mt-3">
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
@endsection