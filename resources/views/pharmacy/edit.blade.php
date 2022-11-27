@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container-fluid">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Pharmacy Update</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('pharmacy.update', $pharmacy->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <div class="row g-4">
                                <div class="col-sm-4">
                                    <label class="form-label">Patient Name / MR.ID / Patient ID<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $pharmacy->patient_name }}" name="patient_name" class="form-control form-control-md" placeholder="Patient Name / MR.ID / Patient ID">
                                    @error('patient_name')
                                    <small class="text-danger">{{ $errors->first('patient_name') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-8">
                                    <label class="form-label">Age / Address / Phone number If any.</label>
                                    <input type="text" value="{{ $pharmacy->other_info }}" name="other_info" class="form-control form-control-md" placeholder="Age / Address / Phone number If any.">
                                    @error('other_info')
                                    <small class="text-danger">{{ $errors->first('other_info') }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
                                <div class="col-sm-12 table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="text-center"><tr><th width="20%">Product</th><th>Batch No.</th><th>Qty</th><th>Dosage</th><th>Price</th><th>Discount</th><th>Tax%</th><th>Tax Amount</th><th>total</th><th></th></tr></thead>
                                        <tbody class="tblPharmacy">
                                            @php $c = 1; @endphp
                                            @forelse($records as $key => $record)
                                            <tr>
                                                <td>
                                                    <select class="form-control form-control-sm show-tick ms select2 selProductForPurchase" data-placeholder="Select" name="product[]" required='required'>
                                                    <option value="">Select</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" {{ $record->product == $product->id ? 'selected' : '' }}>{{ $product->product_name }}</option>
                                                    @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control form-control-sm" name="batch_number[]" placeholder="Batch No." value="{{ $record->batch_number  }}" required='required'/></td>
                                                <td><input type="number" class="form-control form-control-sm text-end qty" step="any" min="1" name="qty[]" placeholder="0" value="{{ $record->qty  }}" required='required'/></td>
                                                <td><input type='text' class='form-control form-control-sm' name='dosage[]' value="{{ $record->dosage  }}" placeholder='Dosage'/></td>
                                                <td><input type="number" class="form-control form-control-sm text-end price" step="any" name="price[]" placeholder="0.00" value="{{ $record->price  }}" required='required'/></td>
                                                <td><input type="number" class="form-control form-control-sm text-end discount" step="any" name="discount[]" value="{{ $record->discount  }}" placeholder="0.00" /></td>
                                                <td><input type="number" class="form-control form-control-sm text-end tax" step="any" name="tax[]" value="{{ $record->tax  }}" placeholder="0%" /></td>
                                                <td><input type="number" class="form-control form-control-sm text-end tax_amount" step="any" name="tax_amount[]" value="{{ $record->tax_amount  }}" placeholder="0.00" /></td>
                                                <td><input type="number" class="form-control form-control-sm text-end total" step="any" name="total[]" value="{{ $record->total  }}" placeholder="0.00" required='required' /></td>
                                                @if($c ==1)
                                                <td></td>
                                                @else
                                                <td class="text-center"><a href='javascript:void(0)' onClick='$(this).parent().parent().remove()'><i class='fa fa-trash text-danger'></i></a></td>
                                                @endif
                                            </tr>
                                            @php $c++ @endphp
                                            @empty
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                        <tr><td colspan="8" class="text-end">Total</td><td class="text-end">0.00</td></tr>
                                        <tr><td colspan="9" class="text-center"><a class="btn btn-info text-white addPharmacyRow">ADD MORE</a></td></tr></tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
                                <div class="col-sm-12 text-right">
                                    <button type="button" onClick="history.back()"  class="btn btn-danger">Cancel</button>
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