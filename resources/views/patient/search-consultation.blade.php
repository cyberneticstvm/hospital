@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container-fluid">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Search Consultation</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('patient-consultation.fetch') }}" method="post">
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
                        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                        <thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Mobile No.</th><th>Doctor</th><th>Reg.Date</th><th>Token</th><th>Prescription</th><th>Receipt</th><th>Edit</th><th>Delete</th></tr></thead><tbody>
                        @php $i = 0; @endphp
                        @foreach($records as $patient)
                            <tr class="{{ ($patient->status == 0) ? 'text-decoration-line-through' : '' }}">
                                <td>{{ ++$i }}</td>
                                <td>{{ $patient->medical_record_id }}</td>
                                <td>{{ $patient->pname }}</td>
                                <td>{{ $patient->pno }}</td>
                                <td>{{ $patient->mobile_number }}</td>
                                <td>{{ $patient->doctor_name }}</td>
                                <td>{{ $patient->rdate }}</td>
                                <td><a href='/generate-token/{{ $patient->reference_id }}/' target='_blank'><i class="fa fa-file text-info"></i></a></td>
                                <td><a href='/generate-prescription/{{ $patient->reference_id }}/' target='_blank'><i class="fa fa-file text-primary"></i></a></td>
                                <td><a href='/generate-receipt/{{ $patient->reference_id }}/' target="_blank"><i class="fa fa-file text-success"></i></a></td>
                                <td><a class='btn btn-link' href="{{ route('patient_reference.edit', $patient->reference_id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                <td>
                                    <form method="post" action="{{ route('patient_reference.delete', $patient->reference_id) }}">
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