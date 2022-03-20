@extends("templates.base")
@section("roles")
<div class="card mb-4 border-0">
    <div class="card-body">
        @can('role-create')
        <p class= "text-right my-3"><a href="/roles/create/"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        @endcan
        <table id="dataTbl" class="table display dataTable table-hover align-middle" style="width:100%">
            <thead><tr><th>SL No.</th><th>Role</th><th>Edit</th><th>Delete</th></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($roles as $role)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $role->name }}</td>
                    <td><a class='btn btn-link' href="/roles/{{ $role->id }}/edit/">Edit</a></td>
                    <td>
                        <form method="post" action="{{ route('roles.delete', $role->id) }}">
                            @csrf 
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Role?');">Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody></table>
    </div>
</div>
@endsection