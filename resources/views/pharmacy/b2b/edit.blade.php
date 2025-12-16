@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Pharmacy B2B Update</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('pharmacy.b2b.update', encrypt($pharmacy->id)) }}" method="post">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="" class="selFromBranch" value="{{ session()->get('branch') }}" />
                            <input type="hidden" name="" class="medical_record_id" value="{{ $pharmacy->medical_record_id }}" />
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">Buyer Name<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $pharmacy->patient_name }}" name="patient_name" class="form-control form-control-md" placeholder="Buyer Name" required>
                                    @error('patient_name')
                                    <small class="text-danger">{{ $errors->first('patient_name') }}</small>
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
                                    <input type="number" value="{{ $pharmacy->addition }}" name="addition" class="form-control form-control-md addition" placeholder="0.00">
                                </div>
                                <div class="col-sm-5">
                                    <label class="form-label">Buyer Address</label>
                                    <input type="text" value="{{ $pharmacy->other_info }}" name="other_info" class="form-control form-control-md" placeholder="Buyer Address" required>
                                    @error('other_info')
                                    <small class="text-danger">{{ $errors->first('other_info') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Contact</label>
                                    <input type="text" value="{{ $pharmacy->contact }}" name="contact" class="form-control form-control-md" placeholder="Contact" required>
                                    @error('contact')
                                    <small class="text-danger">{{ $errors->first('contact') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">GSTIN</label>
                                    <input type="text" value="{{ $pharmacy->gstin }}" name="gstin" class="form-control form-control-md" placeholder="GSTIN">
                                    @error('gstin')
                                    <small class="text-danger">{{ $errors->first('gstin') }}</small>
                                    @enderror
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
                                            @forelse($records as $key => $record)
                                            @php
                                            $bnos = Helper::getStock($record->product, Session::get('branch'), $record->qty);
                                            @endphp
                                            <tr>
                                                <td>
                                                    <select class="form-control form-control-sm show-tick ms select2 selProductForTransfer selProductForPurchase" data-placeholder="Select" name="product[]" required='required'>
                                                        <option value="">Select</option>
                                                        @foreach($products as $product)
                                                        <option value="{{ $product->id }}" {{ $record->product == $product->id ? 'selected' : '' }}>{{ $product->product_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><select class="form-control form-control-sm select2 bno" name="batch_number[]" required='required'>
                                                        <option value="">Select</option>
                                                        @forelse($bnos as $key => $bno)
                                                        <option value="{{ $bno->batch_number }}" {{ $record->batch_number == $bno->batch_number ? 'selected' : '' }}>{{ $bno->batch_number .' ('.$bno->balance_qty.' Qty in Hand)' }}</option>
                                                        @empty
                                                        <option value="NRF">No Batch Number</option>
                                                        @endforelse
                                                    </select></td>
                                                <td><input type="number" class="form-control form-control-sm text-end qty" step="any" min="1" name="qty[]" placeholder="0" value="{{ $record->qty  }}" required='required' /></td>
                                                <td>
                                                    <input type="number" class="form-control form-control-md text-right mrp" placeholder="0" name="mrp[]" step="any" value="{{ $record->mrp }}" required='required' />
                                                </td>
                                                <td><input type="number" class="form-control form-control-sm text-end discount" step="any" name="discount[]" value="{{ $record->discount  }}" placeholder="0.00" /></td>
                                                <td><input type="number" class="form-control form-control-sm text-end tax" step="any" name="tax[]" value="{{ $record->tax  }}" placeholder="0%" /></td>
                                                <td><input type="number" class="form-control form-control-sm text-end tax_amount" step="any" name="tax_amount[]" value="{{ $record->tax_amount  }}" placeholder="0.00" /></td>
                                                <td>
                                                    <input type="number" class="form-control form-control-md text-right price" placeholder="0" name="price[]" step="any" value="{{ $record->price }}" required='required' />
                                                </td>
                                                <td><input type="number" class="form-control form-control-sm text-end total" step="any" name="total[]" value="{{ $record->total  }}" placeholder="0.00" required='required' /></td>
                                                @if($key == 1)
                                                <td></td>
                                                @else
                                                <td class="text-center"><a href='javascript:void(0)' onClick='$(this).parent().parent().remove()'><i class='fa fa-trash text-danger'></i></a></td>
                                                @endif
                                            </tr>
                                            @empty
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="8" class="text-end">Total</td>
                                                <td class="text-end fw-bold gtot">>{{ number_format($records->sum('total'), 2) }}</td>
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