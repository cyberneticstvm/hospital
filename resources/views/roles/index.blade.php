@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Role Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        @can('role-create')
        <p class= "text-right my-3"><a href="/roles/create/"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        @endcan
        <table id="dataTbl" class="table display table-striped dataTable table-hover align-middle" style="width:100%">
            <thead><tr><th>SL No.</th><th>Role</th><th>Edit</th><!--<th>Remove</th>--></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($roles as $role)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $role->name }}</td>
                    <td><a class='btn btn-link' href="{{ route( 'roles.edit', $role->id) }}"><i class='fa fa-pencil text-warning'></i></a></td>
                    <!--<td>
                        <form method="post" action="{{ route('roles.delete', $role->id) }}">
                            @csrf 
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Role?');"><i class="fa fa-trash text-danger"></i></button>
                        </form>
                    </td>-->
                </tr>
            @endforeach
        </tbody></table>
    </div>
</div>
@endsection