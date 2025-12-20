@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Customer Register</h5>
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
        <p class="text-right my-3"><a href="{{ route('customer.create') }}"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
            <thead>
                <tr>
                    <th>SL No.</th>
                    <th>Customer Name</th>
                    <th>Contact Number</th>
                    <th>Address</th>
                    <th>GSTIN</th>
                    <th>Opening Balance</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $key => $customer)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td><a href="{{ route('customer.account.create', encrypt($customer->id)) }}">{{ $customer->name }}</a></td>
                    <td>{{ $customer->contact_number }}</td>
                    <td>{{ $customer->address }}</td>
                    <td>{{ $customer->gstin }}</td>
                    <td>{{ $customer->opening_balance }}</td>
                    <td>{!! $customer->delStatus() !!}</td>
                    <td><a class='btn btn-link' href="{{ route('customer.edit', encrypt($customer->id)) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <td>
                        <form method="post" action="{{ route('customer.delete', encrypt($customer->id)) }}">
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