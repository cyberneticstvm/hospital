@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 mb-5">
                                <h5 class="">Update Purchase</h5>
                            </div>
                            <form action="{{ route('purchase.update', encrypt($purchase->id)) }}" method="POST">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-sm-3">
                                        <label class="form-label">Order Date<sup class="text-danger">*</sup></label>
                                        {{ Form::date('order_date', $purchase->order_date->format('Y-m-d'), ['class' => 'form-control']) }}
                                        @error('order_date')
                                        <small class="text-danger">{{ $errors->first('order_date') }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="form-label">Delivery Date<sup class="text-danger">*</sup></label>
                                        {{ Form::date('delivery_date', $purchase->delivery_date->format('Y-m-d'), ['class' => 'form-control']) }}
                                        @error('delivery_date')
                                        <small class="text-danger">{{ $errors->first('delivery_date') }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="form-label">To Branch<sup class="text-danger">*</sup></label>
                                        {{ Form::select('branch_id', $branches, $purchase->branch_id, ['class' => 'form-control form-control-md show-tick ms select2', 'placeholder' => 'Select']) }}
                                        @error('branch_id')
                                        <small class="text-danger">{{ $errors->first('branch_id') }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="form-label">Supplier<sup class="text-danger">*</sup></label>
                                        {{ Form::select('supplier', $suppliers, $purchase->supplier, ['class' => 'form-control form-control-md show-tick ms select2', 'placeholder' => 'Select']) }}
                                        @error('supplier')
                                        <small class="text-danger">{{ $errors->first('supplier') }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="form-label">Supplier Invoice<sup class="text-danger">*</sup></label>
                                        {{ Form::text('invoice_number', $purchase->invoice_number, ['class' => 'form-control', 'placeholder' => 'Supplier Invoice']) }}
                                        @error('invoice_number')
                                        <small class="text-danger">{{ $errors->first('invoice_number') }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Notes / Remarks</label>
                                        {{ Form::text('notes', $purchase->notes, ['class' => 'form-control', 'placeholder' => 'Notes / Remarks']) }}
                                        @error('notes')
                                        <small class="text-danger">{{ $errors->first('notes') }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-md-6">
                                        <h6>Product Details</h6>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <p class="text-right"><a href="javascript:void(0)"><i class="fa fa-plus fa-lg text-success addPurchaseRow"></i></a></p>
                                    </div>
                                    <div class="col-md-12 table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Qty</th>
                                                    <th>Batch</th>
                                                    <th>Expiry</th>
                                                    <th>P. Price</th>
                                                    <th>S. Price</th>
                                                    <th>MRP</th>
                                                    <th>Disc.</th>
                                                    <th>Adjust</th>
                                                    <th>Remove</th>
                                                </tr>
                                            </thead>
                                            <tbody class="purchase purchaseRow">
                                                @forelse($purchase->details as $key => $item)
                                                <tr>
                                                    <td width="20%">
                                                        {{ Form::select('product[]', $products, $item->product, ['id' => 'pdct_'.$item->id, 'class' => 'form-control form-control-md show-tick ms select2 selProductForPurchase', 'placeholder' => 'Select', 'required' => 'required']) }}
                                                        @error('product')
                                                        <small class="text-danger">{{ $errors->first('product') }}</small>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        {{ Form::number('qty[]', $item->qty, ['min' => 1,  'max' => '', 'step' => 1, 'class' => 'form-control form-control-md calcTot qty', 'placeholder' => '0', 'required' => 'required']) }}
                                                        @error('qty')
                                                        <small class="text-danger">{{ $errors->first('qty') }}</small>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        {{ Form::text('batch_number[]', $item->batch_number, ['class' => 'form-control form-control-md bno', 'placeholder' => 'Batch', 'required' => 'required']) }}
                                                        @error('batch_number')
                                                        <small class="text-danger">{{ $errors->first('batch_number') }}</small>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        {{ Form::date('expiry_date[]', $item->expiry_date->format('Y-m-d'), ['class' => 'form-control', 'required' => 'required']) }}
                                                        @error('expiry_date')
                                                        <small class="text-danger">{{ $errors->first('expiry_date') }}</small>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        {{ Form::number('purchase_price[]', $item->purchase_price, ['min' => 1,  'max' => '', 'step' => '', 'class' => 'form-control form-control-md calcTot purchasePrice', 'placeholder' => '0.00', 'required' => 'required']) }}
                                                        @error('purchase_price')
                                                        <small class="text-danger">{{ $errors->first('purchase_price') }}</small>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        {{ Form::number('price[]', $item->price, ['min' => 1,  'max' => '', 'step' => '', 'class' => 'form-control form-control-md calcTot', 'placeholder' => '0.00', 'required' => 'required']) }}
                                                        @error('price')
                                                        <small class="text-danger">{{ $errors->first('price') }}</small>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        {{ Form::number('mrp[]', $item->mrp, ['min' => 1,  'max' => '', 'step' => '', 'class' => 'form-control form-control-md calcTot', 'placeholder' => '0.00', 'required' => 'required']) }}
                                                        @error('mrp')
                                                        <small class="text-danger">{{ $errors->first('mrp') }}</small>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        {{ Form::number('discount[]', $item->discount, ['min' => '',  'max' => '', 'step' => '', 'class' => 'form-control form-control-md calcTot', 'placeholder' => '0.00']) }}
                                                        @error('discount')
                                                        <small class="text-danger">{{ $errors->first('discount') }}</small>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        {{ Form::number('adjustment[]', $item->adjustment, ['min' => 0,  'max' => '', 'step' => '', 'class' => 'form-control form-control-md adjust calcTot', 'placeholder' => '0.00', 'required' => 'required']) }}
                                                        @error('adjustment')
                                                        <small class="text-danger">{{ $errors->first('adjustment') }}</small>
                                                        @enderror
                                                    </td>
                                                    @if($key == 0)
                                                    <td></td>
                                                    @else
                                                    <td><a href='javascript:void(0)' onClick='$(this).parent().parent().remove();'><i class='fa fa-trash text-danger calcTot'></i></a></td>
                                                    @endif
                                                </tr>
                                                @empty
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12 text-right">
                                        <button type="button" onClick="history.back()" class="btn btn-danger">Cancel</button>
                                        <button type="reset" class="btn btn-warning">Reset</button>
                                        <button type="submit" class="btn btn-primary btn-submit">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection