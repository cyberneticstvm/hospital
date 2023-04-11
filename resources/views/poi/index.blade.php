@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Post-operative Instruction Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <p class= "text-right my-3"><a href="/post-oprerative-instruction/create"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
        <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
            <thead><tr><th>SL No.</th><th>Instruction Name</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($records as $record)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $record->name }}</td>
                    <td><a class='btn btn-link' href="{{ route('poi.edit', $record->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <td>
                        <form method="post" action="{{ route('poi.delete', $record->id) }}">
                            @csrf 
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Role?');"><i class="fa fa-trash text-danger"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody></table>
    </div>
</div>
@endsection