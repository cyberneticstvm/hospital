@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Patient Surgery Consumable Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <p class= "text-right my-3"><a href="/patient/surgery/consumable/create"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
            <thead><tr><th>SL No.</th><th>Patient Name</th><th>Patient ID</th><th>MR Id</th><th>Surgery Name</th><th>Receipt</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($pscls as $sc)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $sc->patient->patient_name }}</td>
                    <td>{{ $sc->patient->patient_id }}</td>
                    <td>{{ $sc->medicalrecord->id }}</td>
                    <td>{{ $sc->surgery->surgery_name }}</td>
                    <td><a href="/surgery/consumable/receipt/{{$sc->id}}" target="_blank"><i class="fa fa-file-pdf-o text-danger"></i></a></td>
                    <td><a class='btn btn-link' href="{{ route('patient.surgey.consumable.edit', $sc->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <td>
                        <form method="post" action="{{ route('patient.surgey.consumable.delete', $sc->id) }}">
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