@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Surgery Medicine Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <!--<p class= "text-right my-3"><a href="#"><i class="fa fa-plus fa-lg text-success"></i></a></p>-->
        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
            <thead><tr><th>SL No.</th><th>MR ID.</th><th>Patient Name</th><th>Patient ID</th><th>Bill</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($postops as $pop)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $pop->medical_record_id }}</td>
                    <td>{{ $pop->patient_name }}</td>
                    <td>{{ $pop->patient_id }}</td>
                    <td class="text-center"><a href="" target="_blank"><i class="fa fa-file-o text-info"></i></a></td>
                    <td><a class='btn btn-link' href="{{ route('surgery.medicine.update', $pop->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <td>
                        <form method="post" action="{{ route('surgery.medicine.delete', $pop->id) }}">
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