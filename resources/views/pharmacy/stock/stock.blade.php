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
                                    <th>Pur.</th>
                                    <th>Tr. In</th>
                                    <th>Tr. Out</th>
                                    <th>S.Ret</th>
                                    <th>P.Ret</th>
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
                                    <td><a class="inventory text-danger" href="javascript:void(0)" data-bs-toggle="modal" data-modal="purchaseModal" data-bs-target="#purchaseModal" data-title="Purchase Detailed" data-branch="{{ $inputs[0] }}" data-product="{{ $item->pid }}" data-type="purchase" data-batchno="{{ $item->batch_number }}">{{ $item->purchased_qty }}</a></td>
                                    <td><a class="inventory text-danger" href="javascript:void(0)" data-bs-toggle="modal" data-modal="transferInModal" data-bs-target="#transferInModal" data-title="Transfer In Detailed" data-branch="{{ $inputs[0] }}" data-product="{{ $item->pid }}" data-type="stockin" data-batchno="{{ $item->batch_number }}">{{ $item->transfer_in }}</a></td>
                                    <td><a class="inventory text-danger" href="javascript:void(0)" data-bs-toggle="modal" data-modal="transferOutModal" data-bs-target="#transferOutModal" data-title="Transfer Out Detailed" data-branch="{{ $inputs[0] }}" data-product="{{ $item->pid }}" data-type="stockout" data-batchno="{{ $item->batch_number }}">{{ $item->transfer_out }}</a></td>
                                    <td>{{ $item->sreturn }}</td>
                                    <td>{{ $item->preturn }}</td>
                                    <td><a class="inventory text-danger" href="javascript:void(0)" data-bs-toggle="modal" data-modal="billedModal" data-bs-target="#billedModal" data-title="Billed Detailed" data-branch="{{ $inputs[0] }}" data-product="{{ $item->pid }}" data-type="billed" data-batchno="{{ $item->batch_number }}">{{ $item->billed }}</a></td>
                                    <td>{{ $item->balanceQty }}</td>
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
<div class="modal fade" id="purchaseModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-vertical modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">
                <div class="row">
                    <div class="col-md-12 table-responsive inventoryDetailed"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="transferInModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-vertical modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">
                <div class="row">
                    <div class="col-md-12 table-responsive inventoryDetailed"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="transferOutModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-vertical modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">
                <div class="row">
                    <div class="col-md-12 table-responsive inventoryDetailed"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="billedModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-vertical modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">
                <div class="row">
                    <div class="col-md-12 table-responsive inventoryDetailed"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection