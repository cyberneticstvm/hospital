@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Product Transfer Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <p class= "text-right my-3"><a href="/product-transfer/create/"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
            <thead><tr><th>SL No.</th><th>Transfer ID</th><th>From Branch</th><th>To Branch</th><th>Transfer Date</th><th>Transfer Note</th><th>Print</th><th>Edit</th><!--<th>Remove</th>--></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($transfers as $tr)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $tr->id }}</td>
                    <td>{{ $tr->from_branch }}</td>
                    <td>{{ $tr->to_branch }}</td>
                    <td>{{ date('d/M/Y', strtotime($tr->tdate)) }}</td>
                    <td>{{ $tr->tnote }}</td>
                    <td><a class='btn btn-link' target="_blank" href="/product-transfer/bill/{{$tr->id}}/"><i class="fa fa-file-o text-info"></i></a></td>
                    <td><a class='btn btn-link' href="{{ route('product-transfer.edit', $tr->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <!--<td>
                        <form method="post" action="{{ route('product-transfer.delete', $tr->id) }}">
                            @csrf 
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Record?');"><i class="fa fa-trash text-danger"></i></button>
                        </form>
                    </td>-->
                </tr>
            @endforeach
        </tbody></table>
    </div>
</div>
@endsection