@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Customer Account Register</h5>
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
        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
            <thead>
                <tr>
                    <th>SL No.</th>
                    <th>Pay. Date</th>
                    <th>Customer Name</th>
                    <th>Amount</th>
                    <th>Notes</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $key => $payment)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $payment->pdate->format('d.M.Y') }}</td>
                    <td>{{ $payment->customer->name }}</td>
                    <td>{{ $payment->amount }}</td>
                    <td>{{ $payment->notes }}</td>
                    <td>{!! $payment->delStatus() !!}</td>
                    <td><a class='btn btn-link' href="{{ route('customer.account.edit', encrypt($payment->id)) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <td>
                        <form method="post" action="{{ route('customer.account.delete', encrypt($payment->id)) }}">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this record?');"><i class="fa fa-trash text-danger"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection