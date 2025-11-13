@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mt-3">
                @if (Session::has('error'))
                <div class="text-danger text-center mt-2">
                    <h5>{{ Session::get('error') }}</h5>
                </div>
                @endif
                @if (Session::has('success'))
                <div class="text-success text-center mt-2">
                    <h5>{{ Session::get('success') }}</h5>
                </div>
                @endif
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <h5 class="">Purchase Register</h5>
                            </div>
                            <div class="col-lg-6 text-end">
                                <p><a href="{{ route('purchase.create') }}"><i class="fa fa-plus fa-lg text-success"></i></a></p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SL No.</th>
                                    <th>Purchase No.</th>
                                    <th>Ord. Date</th>
                                    <th>Del. Date</th>
                                    <th>Supplier</th>
                                    <th>Sup. Invoice</th>
                                    <th>Bill</th>
                                    <th>Status</th>
                                    <th>Edit</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 0; @endphp
                                @foreach($purchases as $purchase)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $purchase->id }}</td>
                                    <td>{{ $purchase->order_date->format('d.M.Y') }}</td>
                                    <td>{{ $purchase->delivery_date->format('d.M.Y') }}</td>
                                    <td>{{ $purchase->supplierr->name }}</td>
                                    <td>{{ $purchase->invoice_number }}</td>
                                    <td class="text-center"><a href="/purchase/bill/{{ $purchase->id }}" target="_blank"><i class="fa fa-file-pdf-o text-danger"></i></a></td>
                                    <td class="text-center">{!! $purchase->status() !!}</td>
                                    <td><a class='btn btn-link' href="{{ route('purchase.edit', encrypt($purchase->id)) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                    <td>
                                        <form method="post" action="{{ route('purchase.delete', encrypt($purchase->id)) }}">
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
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection