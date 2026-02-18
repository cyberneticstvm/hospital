@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Patient Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <p class="text-right my-3"><a href="{{ route('patient.create', ['mobile' => 0]) }}"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        <table id="dataTbl" class="table display dataTable table-striped table-sm table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>SL No.</th>
                    <th>Patient Name</th>
                    <th>Patient ID</th>
                    <th>Address</th>
                    <th>History</th>
                    <th>Assign Doctor</th>
                    <th>Reg.Date</th>
                    <th>Re-open</th>
                    <th>Edit</th><!--<th>Remove</th>-->
                </tr>
            </thead>
            <tbody>
                @php $i = 0; @endphp
                @foreach($patients as $patient)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $patient->patient_name }}</td>
                    <td>{{ $patient->patient_id }}</td>
                    <td>{{ $patient->address }}</td>
                    <td><a href="/patient-history/{{ $patient->id }}/" target="_blank"><i class="fa fa-file-o text-info"></i></a></td>
                    <td>@if(!$patient->is_doctor_assigned)<a href='/consultation/create-patient-reference/{{ $patient->id }}/'>Assign</a>@endif</td>
                    <td>{{ $patient->rdate }}</td>
                    <td>@if($patient->is_doctor_assigned)<a href='/consultation/reopen/{{ $patient->id }}/0/'>Re-open</a>@endif</td>
                    <td><a class='btn btn-link' href="{{ route('patient.edit', $patient->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <!--<td>
                        <form method="post" action="{{ route('patient.delete', $patient->id) }}">
                            @csrf 
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Patient?');"><i class="fa fa-trash text-danger"></i></button>
                        </form>
                    </td>-->
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection