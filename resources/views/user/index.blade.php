@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">User Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <p class="text-right my-3"><a href="/user/create/"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>SL No.</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Mobile Access</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Cancel</th>
                </tr>
            </thead>
            <tbody>
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
                        <span class="badge bg-success">{{ $v }}</span>
                        @endforeach
                        @endif
                    </td>
                    <td class="text-center">{!! ($user->mobile_device == '0') ? "<i class='fa fa-times text-danger'></i>" : "<i class='fa fa-check text-primary'></i>" !!}</td>
                    <td class="text-center">{!! ($user->deleted_at) ? "<i class='fa fa-times text-danger'></i>" : "<i class='fa fa-check text-primary'></i>" !!}</td>
                    <td><a class='btn btn-link' href="{{ route('user.edit', $user->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <td>
                        <form method="post" action="{{ route('user.delete', $user->id) }}">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to Cancel this User?');"><i class="fa fa-trash text-danger"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection