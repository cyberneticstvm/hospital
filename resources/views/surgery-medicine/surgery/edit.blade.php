@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Update Surgery Medicine</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row g-4 mb-3">
                            <div class="col-sm-3">MR.ID: <h5 class="text-primary">{{ $medicine->medical_record_id }}</h5>
                            </div>
                            <div class="col-sm-3">Patient Name: <h5 class="text-primary">{{ ($patient) ? $patient->patient_name : '' }}</h5>
                            </div>
                            <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ ($patient) ? $patient->patient_id : '' }}</h5>
                            </div>
                        </div>
                        <form action="{{ route('surgery.medicine.update', $medicine->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="surgery_id" value="{{ $medicine->surgery_id }}" />
                            <input type="hidden" name="medical_record_id" value="{{ $medicine->medical_record_id }}" />
                            <input type="hidden" name="branch" class="selFromBranch" value="{{ $medicine->branch }}" />
                            <input type="hidden" name="patient" value="{{ $medicine->patient }}" />
                            <input type="hidden" name="type" value="surgery" />
                            <div class="row g-4 mt-3">
                                <div class="col-sm-12 table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="text-center">
                                            <tr>
                                                <th width="20%">Product</th>
                                                <th>Batch No.</th>
                                                <th>Qty</th>
                                                <th>Dosage</th>
                                                <th>Price</th>
                                                <th>Discount</th>
                                                <th>Tax%</th>
                                                <th>Tax Amount</th>
                                                <th>total</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="tblPharmacy">
                                            @forelse($medicines as $key => $med)
                                            <tr>
                                                <td>
                                                    <select class="form-control form-control-sm show-tick ms select2 selProductForPurchase selProductForTransfer" data-placeholder="Select" name="product[]" required='required'>
                                                        <option value="">Select</option>
                                                        @foreach($products as $product)
                                                        <option value="{{ $product->id }}" {{ $med->product == $product->id ? 'selected' : '' }}>{{ $product->product_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><select class="form-control form-control-sm select2 bno" name="batch_number[]" data-type="" required='required'>
                                                        <option value=''>Select</option>
                                                    </select></td>
                                                <td><input type="number" class="form-control form-control-sm text-end qty" step="any" min="1" name="qty[]" placeholder="0" value="{{ $med->qty }}" required='required' /></td>
                                                <td><input type='text' class='form-control form-control-sm' name='dosage[]' placeholder='Dosage' value="{{ $med->dosage }}" /></td>
                                                <td><input type="number" class="form-control form-control-sm text-end price" step="any" name="price[]" placeholder="0.00" value="{{ $med->price }}" required='required' /></td>
                                                <td><input type="number" class="form-control form-control-sm text-end discount" step="any" name="discount[]" value="{{ $med->discount }}" placeholder="0.00" /></td>
                                                <td><input type="number" class="form-control form-control-sm text-end tax" step="any" name="tax[]" value="{{ $med->tax }}" placeholder="0%" /></td>
                                                <td><input type="number" class="form-control form-control-sm text-end" step="any" name="tax_amount[]" value="{{ $med->tax_amount }}" placeholder="0.00" /></td>
                                                <td><input type="number" class="form-control form-control-sm text-end total" step="any" name="total[]" value="{{ $med->total }}" placeholder="0.00" required='required' /></td>
                                                <td></td>
                                            </tr>
                                            @empty
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="8" class="text-end">Total</td>
                                                <td class="text-end fw-bold gtot">0.00</td>
                                            </tr>
                                            <tr>
                                                <td colspan="9" class="text-center"><a class="btn btn-info text-white addPharmacyRow">ADD MORE</a></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
                                <div class="col-sm-6">
                                    <label class="form-label">Status</label>
                                    <select class="form-control form-control-md">
                                        <option value="1" {{ ($medicine->status == 1) ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ ($medicine->status == 0) ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
                                <div class="col-sm-6">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control form-control-md" rows="5" placeholder="Notes">{{ $medicine->notes }}</textarea>
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
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
        </div> <!-- .row end -->
    </div>
</div>
@endsection