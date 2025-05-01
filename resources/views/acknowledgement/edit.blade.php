@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Create Patient Acknoledgement</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body table-responsive">
                        <div class="row g-4 mb-3">
                            <div class="col-sm-3">MRN: <h5 class="text-primary">{{ $ack->medical_record_id }}</h5>
                            </div>
                            <div class="col-sm-4">Patient Name: <h5 class="text-primary">{{ $ack->patient->patient_name }}</h5>
                            </div>
                            <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ $ack->patient_id }}</h5>
                            </div>
                        </div>
                        <form action="{{ route('patient.ack.update', $ack->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="medical_record_id" value="{{ $ack->medical_record_id }}" />
                            <input type="hidden" name="patient_id" value="{{ $ack->patient_id }}" />
                            <input type="hidden" name="branch_id" value="{{ $ack->branch_id }}" />
                            <div class="row mx-1">
                                @foreach($procs as $value)
                                <div class="col-sm-2 form-check form-check-inline">
                                    <label class="form-check-label" for="flexCheckDefault">{{ $value->name }}</label>{{ Form::checkbox('procs[]', $value->id, in_array($value->id, $ackproc) ? true : false, array('class' => 'name, form-check-input')) }}
                                </div>
                                @endforeach
                                @error('procs')
                                <small class="text-danger">{{ $errors->first('procs') }}</small>
                                @enderror
                            </div>
                            <div class="row mt-5">
                                <div class="col-sm-12">
                                    <label class="form-label">Notes</label>
                                    <textarea class="form-control form-control-md" name="notes" rows="5" placeholder="Notes">{{ $ack->notes ?? old('notes') }}</textarea>
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
                                <div class="col-sm-12 text-end">
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