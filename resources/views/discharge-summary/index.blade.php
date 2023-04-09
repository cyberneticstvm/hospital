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
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    @if(session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session()->get('error') }}
                        </div>
                    @endif
                    <div class="card-body">
                        <form action="{{ route('dsummary.show') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">Medical Record ID (MR.ID)<sup class="text-danger">*</sup></label>
                                    <input type="number" value="{{ old('medical_record_id') }}" name="medical_record_id" class="form-control form-control-md" placeholder="Mediical Record ID">
                                    @error('medical_record_id')
                                    <small class="text-danger">{{ $errors->first('medical_record_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-submit w-100">Fetch</button>
                                </div>
                            </div>
                            @if (count($errors) > 0)
                            <div role="alert" class="text-danger mt-3">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
                <h5 class="mb-3 mt-3">Discharge Summary Register</h5>
                <div class="card">
                    <div class="card-body table-responsive">
                        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                        <thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Report</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
                        @php $i = 0; @endphp
                        @foreach($records as $record)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $record->medical_record_id }}</td>
                                <td>{{ $record->patient->patient_name }}</td>
                                <td>{{ $record->patient->patient_id }}</td>
                                <td><a href="/dsummary/report/{{ $record->id }}" target="_blank"><i class="fa fa-file-pdf-o text-danger"></i></a></td>
                                <td><a class='btn btn-link' href="{{ route('dsummary.edit', $record->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                <td>
                                    <form method="post" action="{{ route('dsummary.delete', $record->id) }}">
                                        @csrf 
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this record?');"><i class="fa fa-trash text-danger"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody></table>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection