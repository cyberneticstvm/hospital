@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Lab Clinic Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <p class= "text-right my-3"><a href="/lab/clinic/fetch/"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
            <thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Doctor</th><th>Date</th><th>Result</th><th>Report</th><th>Prescription</th><th>Bill</th><th>Edit</th><!--<th>Remove</th>--></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($labs as $lab)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $lab->medical_record_id }}</td>
                    <td>{{ $lab->patient_name }}</td>
                    <td>{{ $lab->patient_id }}</td>
                    <td>{{ $lab->doctor_name }}</td>
                    <td>{{ $lab->ldate }}</td>
                    <td><a href="/lab/clinic/result/{{ $lab->medical_record_id }}/" target="_blank"><i class="fa fa-pencil text-muted"></i></a></td>
                    <td><a href="/lab/clinic/report/{{ $lab->medical_record_id }}/" target="_blank"><i class="fa fa fa-file-pdf-o text-primary"></i></a></td>
                    <td><a href="/lab/clinic/prescription/{{ $lab->medical_record_id }}/" target="_blank"><i class="fa fa fa-file-pdf-o text-danger"></i></a></td>
                    <td><a href="/lab/clinic/bill/{{ $lab->medical_record_id }}/" target="_blank"><i class="fa fa-file-pdf-o text-success"></i></a></td>
                    <td><a class='btn btn-link' href="{{ route('lab.clinic.edit', $lab->medical_record_id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <!--<td>
                        <form method="post" action="{{ route('lab.clinic.delete', $lab->medical_record_id) }}">
                            @csrf 
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Record?');"><i class="fa fa-trash text-danger"></i></button>
                        </form>
                    </td>-->
                </tr>
            @endforeach
        </tbody></table>
    </div>
</div>
@endsection