@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Spectacle Prescription Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <p class= "text-right my-3"><a href="/spectacle/fetch/"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
            <thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Optometrist</th><th>Date</th><th>Receipt</th><th>Prescription</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($spectacles as $spectacle)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $spectacle->medical_record_id }}</td>
                    <td>{{ $spectacle->patient_name }}</td>
                    <td>{{ $spectacle->patient_id }}</td>
                    <td>{{ $spectacle->optometrist }}</td>
                    <td>{{ $spectacle->pdate }}</td>
                    @if($spectacle->consultation_type == 5)
                    <td><a href="/vision-receipt/{{ $spectacle->id }}/" target="_blank"><i class="fa fa-file-o text-primary"></i></a></td>
                    @else
                    <td></td>
                    @endif
                    <td><a href="/generate-spectacle-prescription/{{ $spectacle->id }}/" target="_blank"><i class="fa fa-file-o text-info"></i></a></td>
                    <td><a class='btn btn-link' href="{{ route('spectacle.edit', $spectacle->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <td>
                        <form method="post" action="{{ route('spectacle.delete', $spectacle->id) }}">
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