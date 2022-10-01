@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Patient Certificates Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
            <thead><tr><th>SL No.</th><th>Patient Name</th><th>Patient ID</th><th>Doctor</th><th>Branch</th><th>Date</th><th>Vision</th><th>Medical</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($records as $record)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $record->patient_name }}</td>
                    <td>{{ $record->patient_id }}</td>
                    <td>{{ $record->doctor_name }}</td>
                    <td>{{ $record->branch_name }}</td>
                    <td>{{ $record->cdate }}</td>
                    <td class="text-center"><a href="/license/vision/{{ $record->medical_record_id }}/" target="_blank"><i class="fa fa-file-pdf-o text-danger"></i></a></td>
                    <td class="text-center"><a href="/license/medical/{{ $record->medical_record_id }}/" target="_blank"><i class="fa fa-file-pdf-o text-danger"></i></a></td>
                    <td><a class='btn btn-link' href="{{ route('certificate.edit', $record->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <td>
                        <form method="post" action="{{ route('certificate.delete', $record->id) }}">
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
@endsection