@extends("templates.base")

@section("content")
    <h1>This is dash page</h1>
    <!-- Modal -->
    <input type="hidden" id="branch_selector" value="{{ Session::get('branch') }}">
    <div class="modal fade branchSelector" id="staticBackdropLive" data-backdrop="static" data-keyboard="false" tabindex="-1" data-keyboard="false">
        <div class="modal-dialog">
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