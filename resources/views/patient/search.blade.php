@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Search Patient</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('patient.fetch') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">Patient ID/Mobile/Name<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $search_term }}" name="search_term" class="form-control form-control-md" placeholder="Patient ID/Mobile/Name">
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
                        <table id="dataTbl" class="table display dataTable table-striped table-sm table-hover align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SL No.</th>
                                    <th>Patient Name</th>
                                    <th>Patient ID</th>
                                    <th>Phone Number</th>
                                    <th>Address</th>
                                    <th>History</th>
                                    <th>Assign Doctor</th>
                                    <th>Reg.Date</th>
                                    <th>Re-open</th>
                                    <th>Edit</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 0; @endphp
                                @forelse($records as $key => $patient)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $patient->patient_name }}</td>
                                    <td>{{ $patient->patient_id }}</td>
                                    <td>{{ $patient->mobile_number }}</td>
                                    <td>{{ $patient->address }}</td>
                                    <td><a href="/patient-history/{{ $patient->id }}/" target="_blank"><i class="fa fa-file-o text-info"></i></a></td>
                                    <td>@if(!$patient->is_doctor_assigned)<a href='/consultation/create-patient-reference/{{ $patient->id }}/'>Assign</a>@endif</td>
                                    <td>{{ $patient->rdate }}</td>
                                    <td>@if($patient->is_doctor_assigned)<a href='/consultation/reopen/{{ $patient->id }}/0/'>Re-open</a>@endif</td>
                                    <td><a class='btn btn-link' href="{{ route('patient.edit', $patient->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                    <td>
                                        <form method="post" action="{{ route('patient.delete', $patient->id) }}">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Patient?');"><i class="fa fa-trash text-danger"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <a href="{{ route('appointment.create', ['mobile' => 0]) }}">Register</a>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection