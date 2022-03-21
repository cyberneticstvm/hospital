@extends("templates.base")
@section("edit-roles")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Edit Role</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('roles.update', $role->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="" value="web">
                            <div class="row g-4">
                                <div class="col-sm-4">
                                    <label class="form-label">Role Name<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $role->name }}" name="name" class="form-control form-control-md" placeholder="Role Name">
                                    @error('name')
                                    <small class="text-danger">{{ $errors->first('name') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-8"></div>
                                <label class="form-label"><strong>Permission</strong><sup class="text-danger">*</sup></label>
                                <div class="row mx-1">
                                    @foreach($permission as $value)
                                        <div class="col-sm-2 form-check form-check-inline">
                                                <label class="form-check-label" for="flexCheckDefault">{{ $value->name }}</label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name, form-check-input')) }}
                                        </div>
                                    @endforeach
                                    @error('permission')
                                    <small class="text-danger">{{ $errors->first('permission') }}</small>
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