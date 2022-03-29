@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Patient Reference Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
            <thead><tr><th>SL No.</th><th>MRN</th><th>Patient Name</th><th>Patient ID</th><th>Doctor</th><th>Token</th><th>Prescription</th><th>Receipt</th><th>Edit</th><th>Delete</th></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($patients as $patient)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $patient->reference_id }}</td>
                    <td><a href="/consultation/medical/{{ $patient->reference_id }}">{{ $patient->pname }}</a></td>
                    <td><a href="/consultation/medical/{{ $patient->reference_id }}">{{ $patient->pno }}</a></td>
                    <td>{{ $patient->doctor_name }}</td>
                    <td><a href='/generate-token/{{ $patient->reference_id }}/' target='_blank'><i class="fa fa-file text-info"></i></a></td>
                    <td><a href='/generate-prescription/{{ $patient->reference_id }}/' target='_blank'><i class="fa fa-file text-primary"></i></a></td>
                    <td><a href=''><i class="fa fa-file text-success"></i></a></td>
                    <td><a href=""><i class="fa fa-pencil text-warning"></i></a></td>
                    <td><a href=""><i class="fa fa-trash text-danger"></i></a></td>
                </tr>
            @endforeach
        </tbody></table>
    </div>
</div>
@endsection