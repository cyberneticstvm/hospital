@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Lab Test Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <p class= "text-right my-3"><a href="/lab-test-type/create/"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
            <thead><tr><th>SL No.</th><th>Lab Type Name</th><th>Category</th><th>Description</th><th>Fee</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($ltypes as $ltype)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $ltype->lab_type_name }}</td>
                    <td>{{ $ltype->category }}</td>
                    <td>{{ $ltype->description }}</td>
                    <td class="text-right">{{ $ltype->fee }}</td>
                    <td><a class='btn btn-link' href="{{ route('ltype.edit', $ltype->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <td>
                        <form method="post" action="{{ route('ltype.delete', $ltype->id) }}">
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