@extends("templates.base")
@section("content")
<div class="body-header d-flex py-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <h1 class="fs-4 mt-1 mb-0">Welcome {{ Auth::user()->name }}!</h1>
                <small class="text-muted">You are viewing Devi Eye Hospital's Interactive Dashboard.</small>
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