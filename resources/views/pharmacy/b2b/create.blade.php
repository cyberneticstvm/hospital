@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Pharmacy B2B</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('pharmacy.b2b.store') }}" method="post">
                            @csrf
                            <input type="hidden" name="" class="selFromBranch" value="{{ session()->get('branch') }}" />
                            <input type="hidden" name="" class="medical_record_id" value="{{ NULL }}" />
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">Customer<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2" data-placeholder="Select" name="customer_id">
                                        <option value="">Select</option>
                                        @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                    <small class="text-danger">{{ $errors->first('customer_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Used For<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md" name="used_for">
                                        <option value="B2B">B2B</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Addition% / Qty<sup class="text-danger">*</sup></label>
                                    <input type="number" value="0" name="addition" class="form-control form-control-md addition" placeholder="0.00">
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
                                <div class="col-sm-12 table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="text-center">
                                            <tr>
                                                <th width="20%">Product</th>
                                                <th>Batch No.</th>
                                                <th>Qty</th>
                                                <th>MRP/Qty</th>
                                                <th>Discount</th>
                                                <th>Tax%</th>
                                                <th>Tax Amount</th>
                                                <th>Price/Qty</th>
                                                <th>total</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="tblPharmacy">
                                            <tr>
                                                <td>
                                                    <select class="form-control form-control-sm show-tick ms select2 selProductForTransfer selProductForPurchase" data-placeholder="Select" name="product[]" required='required'>
                                                        <option value="">Select</option>
                                                        @foreach($products as $product)
                                                        <option value="{{ $product->id }}" {{ old('product') == $product->id ? 'selected' : '' }}>{{ $product->product_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><select class="form-control form-control-sm select2 bno" name="batch_number[]" data-type="b2b" required='required'>
                                                        <option value="">Select</option>
                                                    </select></td>
                                                <td><input type="number" class="form-control form-control-sm text-end qty" step="any" min="1" name="qty[]" placeholder="0" value="1" required='required' /></td>
                                                <td>
                                                    <input type="number" class="form-control form-control-md text-right mrp" placeholder="0.00" name="mrp[]" step="any" value="" required='required' />
                                                </td>
                                                <td><input type="number" class="form-control form-control-sm text-end disc" step="any" name="discount[]" placeholder="0.00" /></td>
                                                <td><input type="number" class="form-control form-control-sm text-end taxp" step="any" name="tax[]" placeholder="0%" /></td>
                                                <td><input type="number" class="form-control form-control-sm text-end taxa" step="any" name="tax_amount[]" placeholder="0.00" /></td>
                                                <td>
                                                    <input type="number" class="form-control form-control-md text-right price" placeholder="0" name="price[]" step="any" value="" required='required' />
                                                </td>
                                                <td><input type="number" class="form-control form-control-sm text-end total" step="any" name="total[]" placeholder="0.00" required='required' /></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="8" class="text-end">Total</td>
                                                <td class="text-end fw-bold gtot">0.00</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="10" class="text-center"><a class="btn btn-info text-white addPharmacyRowB2B">ADD MORE</a></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
                                <div class="col-sm-12 text-right">
                                    <button type="button" onClick="history.back()" class="btn btn-danger">Cancel</button>
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