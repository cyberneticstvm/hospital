@extends("templates.base")
@section("edit-user")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Update User</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('user.update', $user->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <div class="row g-4">
                                <div class="col-sm-4">
                                    <label class="form-label">Full Name<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $user->name }}" name="name" class="form-control form-control-md" placeholder="Full Name">
                                    @error('name')
                                    <small class="text-danger">{{ $errors->first('name') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Username<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $user->username }}" name="username" class="form-control form-control-md" placeholder="Userame">
                                    @error('username')
                                    <small class="text-danger">{{ $errors->first('username') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Email<sup class="text-danger">*</sup></label>
                                    <input type="email" value="{{ $user->email }}" name="email" class="form-control form-control-md" placeholder="Email">
                                    @error('email')
                                    <small class="text-danger">{{ $errors->first('email') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Password <span class="small text-info">(Leave it blank if okay with old pwd)</span></label>
                                    <input type="password" value="" name="password" class="form-control form-control-md" placeholder="Password">
                                    @error('password')
                                    <small class="text-danger">{{ $errors->first('password') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Role<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md" data-placeholder="Select" name="roles">
                                    <option value="">Select</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role }}" {{ $role === reset($userRole) ? 'selected' : '' }}>{{ $role }}</option>
                                    @endforeach
                                    </select>
                                    @error('roles')
                                    <small class="text-danger">{{ $errors->first('roles') }}</small>
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