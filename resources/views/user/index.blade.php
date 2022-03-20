@extends("templates.base")
@section("user-list")
<div class="card mb-4 border-0">
    <div class="card-body">
        <p class= "text-right my-3"><a href="/user/create/"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        <table id="dataTbl" class="table display dataTable table-hover align-middle" style="width:100%">
            <thead><tr><th>SL No.</th><th>Name</th><th>Username</th><th>Email</th><th>Roles</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($users as $user)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                    @if(!empty($user->getRoleNames()))
                        @foreach($user->getRoleNames() as $v)
                        <span class="badge bg-primary">{{ $v }}</span>
                        @endforeach
                    @endif
                    </td>
                    <td><a class='btn btn-link' href="/user/{{ $user->id }}/edit/">Edit</a></td>
                    <td>
                        <form method="post" action="/user/{{ $user->id }}/delete/">
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