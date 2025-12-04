@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Surgery Consumable Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        @if (session('error'))
        <div class="alert alert-danger" style="margin-top: 0.2rem;">
            {{ session('error') }}
        </div>
        @endif
        @if (session('success'))
        <div class="alert alert-success" style="margin-top: 0.2rem;">
            {{ session('success') }}
        </div>
        @endif
        <p class="text-right my-3"><a href="/inventory/surgery-consumables/create"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>SL No.</th>
                    <th>Consumable Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Edit</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 0; @endphp
                @foreach($scs as $sc)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $sc->name }}</td>
                    <td>{{ $sc->description }}</td>
                    <td>{{ $sc->price }}</td>
                    <td><a class='btn btn-link' href="{{ route('surgery.consumable.edit', $sc->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <td>
                        <form method="post" action="{{ route('surgery.consumable.delete', $sc->id) }}">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Record?');"><i class="fa fa-trash text-danger"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection