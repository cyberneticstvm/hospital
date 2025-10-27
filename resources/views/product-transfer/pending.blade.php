@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row">
            <div class="d-flex flex-wrap justify-content-between align-items-end">
                <div class="mb-3">
                    <h5 class="mb-0">Product Transfer Pending Register</h5>
                    <span class="text-muted"></span>
                </div>
            </div>
            <div class="card mb-4 border-0">
                <div class="card-body">
                    @if (count($errors) > 0)
                    <div role="alert" class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                        {{ $error }}
                        @endforeach
                    </div>
                    @endif
                    <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>SL No.</th>
                                <th>Transfer ID</th>
                                <th>From Branch</th>
                                <th>To Branch</th>
                                <th>Transfer Date</th>
                                <th>Transfer Note</th>
                                <th>Print</th>
                                <th>Edit</th><!--<th>Remove</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transfers as $key => $transfer)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $transfer->id }}</td>
                                <td>{{ $transfer->fromBr?->branch_name ?? 'Main Branch' }}</td>
                                <td>{{ $transfer->toBr->branch_name }}</td>
                                <td>{{ date('d/M/Y', strtotime($transfer->transfer_date)) }}</td>
                                <td>{{ $transfer->transfer_note }}</td>
                                <td><a class='btn btn-link' target="_blank" href="/product-transfer/bill/{{$transfer->id}}/"><i class="fa fa-file-o text-info"></i></a></td>
                                <td><a class='btn btn-link' href="{{ route('product.transfer.pending.edit', encrypt($transfer->id)) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                <!--<td>
                        <form method="post" action="{{ route('product-transfer.delete', $transfer->id) }}">
                            @csrf 
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Record?');"><i class="fa fa-trash text-danger"></i></button>
                        </form>
                    </td>-->
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection