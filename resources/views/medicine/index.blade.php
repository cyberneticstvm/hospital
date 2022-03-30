@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Patient Medicine Record Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
            <thead><tr><th>SL No.</th><th>MRN</th><th>Patient Name</th><th>Patient ID</th><th>Doctor</th><th>Medicine Record</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($medicines as $med)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $med->mrn }}</td>
                    <td>{{ $med->patient_name }}</td>
                    <td>{{ $med->patient_id }}</td>
                    <td>{{ $med->doctor_name }}</td>
                    <td class="text-center"><a href=""><i class="fa fa-eye text-primary"></i></a></td>
                    <td><a class='btn btn-link' href="{{ route('medicine.edit', $med->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <td>
                        <form method="post" action="{{ route('medicine.delete', $med->id) }}">
                            @csrf 
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Medicine Record?');"><i class="fa fa-trash text-danger"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody></table>
    </div>
</div>
@endsection