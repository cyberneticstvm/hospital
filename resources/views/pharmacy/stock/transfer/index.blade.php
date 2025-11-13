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
                                <h5 class="">Product Transfer Register</h5>
                            </div>
                            <div class="col-lg-6 text-end">
                                <p><a href="{{ route('product-transfer.create') }}"><i class="fa fa-plus fa-lg text-success"></i></a></p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SL No.</th>
                                    <th>Transfer No.</th>
                                    <th>Tr. Date</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Approved</th>
                                    <th>Bill</th>
                                    <th>Status</th>
                                    <th>Edit</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 0; @endphp
                                @foreach($transfers as $key => $transfer)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $transfer->id }}</td>
                                    <td>{{ $transfer->transfer_date->format('d.M.Y') }}</td>
                                    <td>{{ $transfer->fromBr->branch_name }}</td>
                                    <td>{{ $transfer->toBr->branch_name }}</td>
                                    <td>{{ $transfer->approved ? 'Yes' : 'No' }}</td>
                                    <td class="text-center"><a href="/product-transfer/bill/{{ $transfer->id}}" target="_blank"><i class="fa fa-file-pdf-o text-danger"></i></a></td>
                                    <td class="text-center">{!! $transfer->status() !!}</td>
                                    <td><a class='btn btn-link' href="{{ route('product-transfer.edit', encrypt($transfer->id)) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                    <td>
                                        <form method="post" action="{{ route('product-transfer.delete', encrypt($transfer->id)) }}">
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