@extends("templates.base")
@section("content")
<div class="card mb-4 border-0">
    <div class="card-body">
        <p class= "text-right my-3"><a href="/doctor/create/"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        <table id="dataTbl" class="table display dataTable table-striped table-hover align-middle" style="width:100%">
            <thead><tr><th>SL No.</th><th>Doctor Name</th><th>Designation</th><th>Departments</th><th>Fee</th><th>Joined Date</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($doctors as $doctor)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $doctor->doctor_name }}</td>
                    <td>{{ $doctor->designation }}</td>
                    <td>
                        @foreach($departments as $dept)
                            @foreach($doctor_depts as $docdept)
                                @if($dept->id == $docdept->department_id)
                                    @if($docdept->doctor_id == $doctor->id)
                                        <span class="badge bg-success">{{ $dept->department_name }}</span>
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                    </td>
                    <td>{{ $doctor->doctor_fee }}</td>
                    <td>{{ date('d/M/Y', strtotime($doctor->date_of_join)) }}</td>
                    <td><a class='btn btn-link' href="{{ route('doctor.edit', $doctor->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <td>
                        <form method="post" action="{{ route('doctor.delete', $doctor->id) }}">
                            @csrf 
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Doctor?');"><i class="fa fa-trash text-danger"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody></table>
    </div>
</div>
@endsection