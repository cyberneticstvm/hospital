@extends("templates.base")
@section("content")
<div class="card mb-4 border-0">
    <div class="card-body">
        <p class= "text-right my-3"><a href="/patient/create/"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        <table id="dataTbl" class="table display dataTable table-striped table-hover align-middle" style="width:100%">
            <thead><tr><th>SL No.</th><th>Patient Name</th><th>Phone Number</th><th>Address</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($patients as $patient)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $patient->patient_name }}</td>
                    <td>{{ $patient->mobile_number }}</td>
                    <td>{{ $patient->address }}</td>
                    <td><a class='btn btn-link' href="{{ route('patient.edit', $patient->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <td>
                        <form method="post" action="{{ route('patient.delete', $patient->id) }}">
                            @csrf 
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Patient?');"><i class="fa fa-trash text-danger"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody></table>
    </div>
</div>
@endsection