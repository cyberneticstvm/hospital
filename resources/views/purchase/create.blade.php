@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Add New Purchase</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('purchase.save') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <label class="form-label">Supplier<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2" data-placeholder="Select" name="supplier">
                                    <option value="">Select</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                    @endforeach
                                    </select>
                                    @error('supplier')
                                    <small class="text-danger">{{ $errors->first('supplier') }}</small>
                                    @enderror
                                </div>                                
                                <div class="col-sm-3">
                                    <label class="form-label">Order Date<sup class="text-danger">*</sup></label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ old('order_date') }}" name="order_date" class="form-control form-control-md dtpicker" placeholder="dd/mm/yyyy">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('order_date')
                                    <small class="text-danger">{{ $errors->first('order_date') }}</small>
                                    @enderror
                                </div>                                
                                <div class="col-sm-3">
                                    <label class="form-label">Delivery Date<sup class="text-danger">*</sup></label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ old('delivery_date') }}" name="delivery_date" class="form-control form-control-md dtpicker" placeholder="dd/mm/yyyy">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('delivery_date')
                                    <small class="text-danger">{{ $errors->first('delivery_date') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Invoice Number<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ old('invoice_number') }}" name="invoice_number" class="form-control form-control-md" placeholder="Invoice Number">
                                    @error('invoice_number')
                                    <small class="text-danger">{{ $errors->first('invoice_number') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-9 text-right">
                                    <p class= "text-right my-3"><a href="javascript:void(0)"><i class="fa fa-plus fa-lg text-success addPurchaseRow"></i></a></p>
                                </div>
                            </div>
                            <div class="row mt-3 purchase">
                                <div class="col-sm-2">
                                    <label class="form-label">Product<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2 selProductForPurchase" data-placeholder="Select" name="product[]" required='required'>
                                    <option value="">Select</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product') == $product->id ? 'selected' : '' }}>{{ $product->product_name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Batch Number<sup class="text-danger">*</sup></label>
                                    <input type="text" name="batch_number[]" class="form-control form-control-md" placeholder="Batch Number" required='required'>
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Expiry Date<sup class="text-danger">*</sup></label>
                                    <input type="date" name="expiry_date[]" class="form-control form-control-md" placeholder="dd/mm/yyyy" required='required'>
                                </div>
                                <div class="col-sm-1">
                                    <label class="form-label">Qty<sup class="text-danger">*</sup></label>
                                    <input type="number" name="qty[]" class="form-control form-control-md calcTot qty" placeholder="0" required='required'>
                                </div>
                                <div class="col-sm-1">
                                    <label class="form-label">Purch.Price</label>
                                    <input type="number" name="purchase_price[]" step="any" class="form-control form-control-md calcTot purchasePrice" placeholder="0.00">
                                </div>
                                <div class="col-sm-1">
                                    <label class="form-label">MRP<sup class="text-danger">*</sup></label>
                                    <input type="number" name="mrp[]" step="any" class="form-control form-control-md calcTot" placeholder="0.00" required='required'>
                                </div>
                                <div class="col-sm-1">
                                    <label class="form-label" title="Selling Price">Price<sup class="text-danger">*</sup></label>
                                    <input type="number" name="price[]" step="any" class="form-control form-control-md calcTot" placeholder="0.00" required='required'>
                                </div>
                                <div class="col-sm-1">
                                    <label class="form-label" title="Adjustment">Adjust.</label>
                                    <input type="number" name="adjustment[]" step="any" class="form-control form-control-md adjust calcTot" placeholder="0.00">
                                </div>
                            </div>
                            <div class="purchaseRow purchase"></div>
                            <div class="row mt-3">
                                <div class="col-sm-11 text-end">Total: <span class="purchase_total text-primary fw-bold">0.00</span></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12 text-right">
                                    <button type="button" onClick="history.back()"  class="btn btn-danger">Cancel</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-primary btn-submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection