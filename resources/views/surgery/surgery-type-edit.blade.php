@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Edit Surgery Type</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('stype.update', $stype->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <label class="form-label">Surgery Type Name<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $stype->surgery_name }}" name="surgery_name" class="form-control form-control-md" placeholder="Surgery Type Name">
                                    @error('surgery_name')
                                    <small class="text-danger">{{ $errors->first('surgery_name') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Description</label>
                                    <input type="text" value="{{ $stype->description }}" name="description" class="form-control form-control-md" placeholder="Description">
                                    @error('description')
                                    <small class="text-danger">{{ $errors->first('description') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Fee<sup class="text-danger">*</sup></label>
                                    <input type="number" step="any" value="{{ $stype->fee }}" name="fee" class="form-control form-control-md" placeholder="0.00">
                                    @error('fee')
                                    <small class="text-danger">{{ $errors->first('fee') }}</small>
                                    @enderror
                                </div>
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