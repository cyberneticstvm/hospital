@extends("templates.base")
@section("content")
@php
use App\Models\Product;
@endphp
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
                                <h5 class="">Product Details</h5>
                            </div>
                        </div>
                    </div>
                    <form method="post" action="{{ route() }}">
                        @csrf
                        <input type="hidden" name="source" value="{{ $source }}" />
                        <div class="card-body table-responsive">
                            <table id="" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL No.</th>
                                        <th>Product</th>
                                        <th>Batch Number</th>
                                        <th>Ord. Qty</th>
                                        <th>Ret. Qty</th>
                                        <th>Bill Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sales as $key => $sale)
                                    <input type="hidden" name="product_id[]" value="{{ $sale->product }}" />
                                    <input type="hidden" name="batch_number[]" value="{{ $sale->batch_number }}" />
                                    <input type="hidden" name="qty[]" value="{{ $sale->qty }}" />
                                    <input type="hidden" name="total[]" value="{{ $sale->total }}" />
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ Product::find($sale->product)->product_name }}</td>
                                        <td>{{ $sale->batch_number }}</td>
                                        <td>{{ $sale->qty }}</td>
                                        <td>
                                            <input type="number" name="ret_qty[]" min="1" max="{{ $sale->qty }}" step="1" class="form-control" placeholder="0" />
                                        </td>
                                        <td class="text-end">{{ $sale->total }}</td>
                                    </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-5">
                                <textarea class="form-control" placeholder="Notes / Remarks"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection