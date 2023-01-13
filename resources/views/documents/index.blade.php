@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Fetch Medical Record</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('documents.show') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">Medical Record ID (MR.ID)<sup class="text-danger">*</sup></label>
                                    <input type="number" value="{{ old('medical_record_number') }}" name="medical_record_number" class="form-control form-control-md" placeholder="Mediical Record Number" required="required">
                                    @error('medical_record_number')
                                    <small class="text-danger">{{ $errors->first('medical_record_number') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-submit w-100">Fetch</button>
                                </div>
                                @if (count($errors) > 0)
                                <div role="alert" class="text-danger mt-3">
                                    @foreach ($errors->all() as $error)
                                        {{ $error }}
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        @if($mref)
                            <div class="row g-4">
                                <div class="col-sm-4">Patient Name: <h5 class="text-primary">{{ $patient->patient_name }}</h5></div>
                                <div class="col-sm-4">Patient ID: <h5 class="text-primary">{{ $patient->patient_id }}</h5></div>
                                <div class="col-sm-4">Doctor: <h5 class="text-primary">{{ $doctor->doctor_name }}</h5></div>
                                <div class="col-sm-12 text-center"><a class='btn btn-outline-info' href="/documents/create/{{ $mref->id }}/">Proceed >></a></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection