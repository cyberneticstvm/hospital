@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 mb-5">
                                <h5 class="">Stock Status</h5>
                                <form action="{{ route('stock.status.update') }}" method="post">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-sm-3">
                                            <label class="form-label">Branch<sup class="text-danger">*</sup></label>
                                            {{ Form::select('branch_id', $branches, old('branch_id') ?? $inputs[0], ['class' => 'form-control form-control-md show-tick ms select2', 'placeholder' => 'Select']) }}
                                            @error('branch_id')
                                            <small class="text-danger">{{ $errors->first('branch_id') }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="form-label">Product<sup class="text-danger">*</sup></label>
                                            {{ Form::select('product_id', $products, old('product_id') ?? $inputs[1], ['class' => 'form-control form-control-md show-tick ms select2', 'placeholder' => 'Select']) }}
                                            @error('product_id')
                                            <small class="text-danger">{{ $errors->first('product_id') }}</small>
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
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <h5 class="">Stock Status Register</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SL No.</th>
                                    <th>Pid</th>
                                    <th>Product</th>
                                    <th>Batch No.</th>
                                    <th>Purchased</th>
                                    <th>Tr. In</th>
                                    <th>Tr. Out</th>
                                    <th>SReturn</th>
                                    <th>PReturn</th>
                                    <th>Billed</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stock as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->pid }}</td>
                                    <td>{{ $item->pname }}</td>
                                    <td>{{ $item->batch_number }}</td>
                                    <td>{{ $item->purchased_qty }}</td>
                                    <td>{{ $item->transfer_in }}</td>
                                    <td>{{ $item->transfer_out }}</td>
                                    <td>{{ $item->sreturn }}</td>
                                    <td></td>
                                    <td>{{ $item->billed }}</td>
                                    <td>{{ $item->balance_qty }}</td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection