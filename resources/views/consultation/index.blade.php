@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Patient Medical Record Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
            <thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Doctor</th><th>Reg.Date</th><th>Medical Record</th><th>Review Date</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($medical_records as $record)
                <tr class="{{ ($record->status == 0) ? 'text-decoration-line-through' : '' }}">
                    <td>{{ ++$i }}</td>
                    <td>{{ $record->id }}</td>
                    <td>{{ $record->patient_name }}</td>
                    <td>{{ $record->patient_id }}</td>
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
@endsection