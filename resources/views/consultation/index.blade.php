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
        <p class= "text-right my-3"><a href="/user/create/"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
            <thead><tr><th>SL No.</th><th>MRN</th><th>Patient Name</th><th>Patient ID</th><th>Doctor</th><th>Medical history</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($medical_records as $record)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><a class='btn btn-link' href="{{ route('medical-records.edit', $record->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
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