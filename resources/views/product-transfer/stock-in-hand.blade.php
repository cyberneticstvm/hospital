@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Fetch Stock in Hand</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('stock-in-hand.fetch') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">Branch<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md select2" name="branch">
                                        <option value="">Select</option>
                                        <option value="0" {{ ($input && $input[0] == 0) ? 'selected' : '' }}>Main Branch</option>
                                        @forelse($branches as $key => $branch)
                                            <option value="{{ $branch->id }}" {{ ($input && $input[0] == $branch->id) ? 'selected' : '' }}>{{ $branch->branch_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @error('branch')
                                    <small class="text-danger">{{ $errors->first('branch') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Product<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md select2" name="product">
                                        <option value="">Select</option>
                                        <option value="0" {{ ($input && $input[1] == 0) ? 'selected' : '' }}>All Products</option>
                                        @forelse($products as $key => $product)
                                            <option value="{{ $product->id }}" {{ ($input && $input[1] == $product->id) ? 'selected' : '' }}>{{ $product->product_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @error('product')
                                    <small class="text-danger">{{ $errors->first('product') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-submit w-100">Fetch</button>
                                </div>
                            </div>                            
                        </form>
                    </div>
                </div>
                <h5 class="mb-3 mt-3">Stock in Hand Register</h5>
                <div class="card">
                    <div class="card-body table-responsive">
                        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                        <thead><tr><th>SL No.</th><th>Branch</th><th>Product</th><th>Pid</th><th>Batch No.</th><th>Stock In</th><th>Stock Out</th><th>Balance</th></tr></thead><tbody>
                        @php $i = 1; @endphp
                        @forelse($inventory as $key => $stock)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $stock->branch }}</td>
                                <td>{{ $stock->product_name }}</td>
                                <td>{{ $stock->product }}</td>
                                <td>{{ $stock->batch_number }}</td>
                                <td>{{ $stock->purchased }}</td>
                                <td>{{ $stock->transferred }}</td>
                                <td>{{ $stock->balance_qty }}</td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody></table>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection