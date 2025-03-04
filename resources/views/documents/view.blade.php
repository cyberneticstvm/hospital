@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Documents</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif
                        <form action="{{ route('documents.save') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="medical_record_id" value="{{ $mref->id }}" />
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}" />
                            <div class="row g-4">
                                <div class="col-sm-4">Patient Name: <h5 class="text-primary">{{ $patient->patient_name }}</h5>
                                </div>
                                <div class="col-sm-4">Patient ID: <h5 class="text-primary">{{ $patient->patient_id }}</h5>
                                </div>
                                <div class="col-sm-4">Doctor: <h5 class="text-primary">{{ $doctor->doctor_name }}</h5>
                                </div>
                                <div class="col-sm-5">
                                    <label class="form-label">Document<sup class="text-danger">*</sup></label>
                                    <input type="file" value="{{ old('doc') }}" name="doc" class="form-control form-control-md" placeholder="Document Name">
                                    @error('doc')
                                    <small class="text-danger">{{ $errors->first('doc') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-7">
                                    <label class="form-label">Description <sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ old('description') }}" name="description" class="form-control form-control-md" placeholder="Description">
                                    @error('description')
                                    <small class="text-danger">{{ $errors->first('description') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-12 text-right">
                                    <button type="button" onClick="history.back()" class="btn btn-danger">Cancel</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-primary btn-submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row g-4">
                            <h5 class="text-primary">Documents Register</h5>
                            @forelse($docs as $key => $doc)
                            <div class="col-sm-3">
                                <div class="card p-3">
                                    <i class="fa fa-file-o fa-2x text-info"></i>
                                    <div class="mt-3">
                                        <h5>{{ $doc->name }}</h5>
                                        <div class="d-flex text-muted flex-wrap justify-content-between small text-uppercase">Type: <span>{{ $doc->type }}</span></div>
                                        <div class="d-flex text-muted flex-wrap justify-content-between small text-uppercase">Description: <span>{{ $doc->description }}</span></div>
                                        <div class="d-flex text-muted flex-wrap justify-content-between small text-uppercase">Uploaded On: <span>{{ date('d/M/Y h:i:A', strtotime($doc->created_at)) }}</span></div>
                                        <div class="d-flex text-muted flex-wrap justify-content-between small text-uppercase">Download: <a href="{{ public_path().'/storage/patient/'.$doc->medical_record_id.'/'.$doc->name }}" target="_blank"><i class="fa fa-download fa-lg"></i></a></div>
                                    </div>
                                </div>
                            </div>

                            @empty
                            @endforelse
                        </div>
                        <div class="row g-4 mt-5">
                            <h5 class="text-primary">OCT Documents</h5>
                            @forelse($octs as $key => $oct)
                            <div class="col-sm-3">
                                <div class="card p-3">
                                    <i class="fa fa-file-o fa-2x text-info"></i>
                                    <div class="mt-3">
                                        <h5>Document</h5>
                                        <div class="d-flex text-muted flex-wrap justify-content-between small text-uppercase">Type: <span>{{ $oct->doc_type }}</span></div>
                                        <div class="d-flex text-muted flex-wrap justify-content-between small text-uppercase">Description: <span>NA</span></div>
                                        <div class="d-flex text-muted flex-wrap justify-content-between small text-uppercase">Uploaded On: <span>{{ date('d/M/Y h:i:A', strtotime($oct->created_at)) }}</span></div>
                                        <div class="d-flex text-muted flex-wrap justify-content-between small text-uppercase">Download: <a href="{{ ($oct->doc_url) ? public_path().'/storage/'.$oct->doc_url : '#' }}" target="_blank"><i class="fa fa-download fa-lg"></i></a></div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection