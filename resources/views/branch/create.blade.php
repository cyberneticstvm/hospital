@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Add New Branch</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('branch.create') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-4">
                                    <label class="form-label">Branch Name<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ old('branch_name') }}" name="branch_name" class="form-control form-control-md" placeholder="Branch Name">
                                    @error('branch_name')
                                    <small class="text-danger">{{ $errors->first('branch_name') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Contact Number<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ old('contact_number') }}" name="contact_number" class="form-control form-control-md" placeholder="Contact Number" required="required">
                                    @error('contact_number')
                                    <small class="text-danger">{{ $errors->first('contact_number') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-5">
                                    <label class="form-label">Address<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ old('address') }}" name="address" class="form-control form-control-md" placeholder="Address" required="required">
                                    @error('address')
                                    <small class="text-danger">{{ $errors->first('address') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Registration Fee<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ old('registration_fee') }}" name="registration_fee" class="form-control form-control-md" placeholder="0.00" required="required">
                                    @error('registration_fee')
                                    <small class="text-danger">{{ $errors->first('registration_fee') }}</small>
                                    @enderror
                                </div>
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