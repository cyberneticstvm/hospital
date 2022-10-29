@extends("templates.base")
@section("content")
<div class="body-header d-flex py-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <h1 class="fs-4 mt-1 mb-0">Welcome {{ Auth::user()->name }}!</h1>
                <small class="text-muted">You are viewing Devi Eye Hospital's Interactive Dashboard.</small>
            </div>
            <div class="col d-flex justify-content-lg-end mt-2 mt-md-0">
                <div class="p-2 me-md-3">
                    <div><span class="h6 mb-0 fw-bold">{{ number_format($day_tot_income, 2) }}</span> <small class="text-success"><i class="fa fa-angle-up"></i></small></div>
                    <small class="text-muted text-uppercase">Income Today</small>
                </div>
                <div class="p-2 me-md-3">
                    <div><span class="h6 mb-0 fw-bold">{{ number_format($day_tot_exp, 2) }}</span> <small class="text-danger"><i class="fa fa-angle-down"></i></small></div>
                    <small class="text-muted text-uppercase">Expense Today</small>
                </div>
                <div class="p-2 pe-lg-0">
                    <div><span class="h6 mb-0 fw-bold">{{ number_format($day_tot_income-$day_tot_exp, 2) }}</span> <small class="text-success"><i class="fa fa-angle-up"></i></small></div>
                    <small class="text-muted text-uppercase">Revenue Today</small>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="body d-flex py-lg-4 py-3">
    <div class="container">
        <div class="row g-3 clearfix">
        </div>
    </div>
</div>
<!-- Modal -->
<input type="hidden" id="branch_selector" value="{{ Session::get('branch') }}">
<div class="modal fade branchSelector" id="staticBackdropLive" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="{{ route('store_branch_session') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLiveLabel">Branch Selector</h5>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <label class="form-label">Select Branch<sup class="text-danger">*</sup></label>
                        <select class="form-control form-control-md show-tick ms" data-placeholder="Select" name="branch_id">
                        <option value="">Select</option>
                        @isset($branches)
                            @foreach($branches as $br)
                                <option value="{{ $br->id }}" {{ old('branch_id') == $br->id ? 'selected' : '' }}>{{ $br->branch_name }}</option>
                            @endforeach
                        @endisset;
                        </select>
                        @error('branch_id')
                        <small class="text-danger">{{ $errors->first('branch_id') }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="/logout/" class="btn btn-danger">Close</a>
                    <button type="submit" class="btn btn-submit btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
    
@endsection