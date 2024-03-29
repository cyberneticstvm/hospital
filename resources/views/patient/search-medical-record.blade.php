@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container-fluid">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Search Medical Record</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('patient-medical-record.fetch') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-2">
                                    <label class="form-label">Patient ID<sup class="text-danger">*</sup></label>
                                    <input type="number" value="{{ $search_term }}" name="search_term" class="form-control form-control-md" placeholder="Patient ID">
                                    @error('search_term')
                                    <small class="text-danger">{{ $errors->first('search_term') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-submit w-100">Search</button>
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
                        <div class="mt-5"></div>
                        <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
                        <thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Mobile Number</th><th>Doctor</th><th>Reg.Date</th><th>Medical Record</th><th>Review Date</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
                        @php $i = 1; @endphp
                        @foreach($records as $record)
                            <tr class="{{ ($record->status == 0) ? 'text-decoration-line-through' : '' }} {{ ($i == 1) ? 'text-highlight' : '' }}">
                                <td>{{ $i++ }}</td>
                                <td>{{ $record->id }}</td>
                                <td>{{ $record->patient_name }}</td>
                                <td>{{ $record->patient_id }}</td>
                                <td>{{ $record->mobile_number }}</td>
                                <td>{{ $record->doctor_name }}</td>
                                <td>{{ $record->rdate }}</td>
                                <td class="text-center"><a href="/generate-medical-record/{{ $record->id }}/" target="_blank"><i class="fa fa-file-o text-primary"></i></a></td>
                                <td>{{ $record->review_date }}</td>
                                @if($record->status == 1)
                                <td><a class='btn btn-link' href="{{ route('medical-records.edit', $record->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                @else
                                <td></td>
                                @endif                    
                                <td>
                                    <form method="post" action="{{ route('medical-records.delete', $record->id) }}">
                                        @csrf 
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Record?');"><i class="fa fa-trash text-danger"></i></button>
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