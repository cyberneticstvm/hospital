@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Update Customer</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        @if (session('error'))
                        <div class="alert alert-danger" style="margin-top: 0.2rem;">
                            {{ session('error') }}
                        </div>
                        @endif
                        <form action="{{ route('customer.update', encrypt($customer->id)) }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-4">
                                    <label class="form-label">Customer Name<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $customer->name }}" name="name" class="form-control form-control-md" placeholder="Customer Name">
                                    @error('name')
                                    <small class="text-danger">{{ $errors->first('name') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Contact Number<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $customer->contact_number }}" name="contact_number" class="form-control form-control-md" placeholder="Contact Number">
                                    @error('contact_number')
                                    <small class="text-danger">{{ $errors->first('contact_number') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Address<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $customer->address }}" name="address" class="form-control form-control-md" placeholder="Address">
                                    @error('address')
                                    <small class="text-danger">{{ $errors->first('address') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">GSTIN</label>
                                    <input type="text" name="gstin" value="{{ $customer->gstin }}" class="form-control form-control-md" placeholder="GSTIN">
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Opening Balance</label>
                                    <input type="number" name="opening_balance" value="{{ $customer->opening_balance }}" class="form-control form-control-md" placeholder="0.00">
                                    @error('opening_balance')
                                    <small class="text-danger">{{ $errors->first('opening_balance') }}</small>
                                    @enderror
                                </div>
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