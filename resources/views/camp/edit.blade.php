@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Update Patient (Camp)</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('camp.update', $camp->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="camp_id" value="{{ $campm->id }}" />
                            <div class="row g-4">
                                <div class="col-sm-4">Camp ID: <h5 class="text-primary">{{ $campm->camp_id }}</h5></div>
                                <div class="col-sm-4">Venue: <h5 class="text-primary">{{ $campm->venue }}</h5></div>
                                <div class="col-sm-4">Address: <h5 class="text-primary">{{ $campm->address }}</h5></div>
                                <div class="col-sm-3">
                                    <label class="form-label">Date<sup class="text-danger">*</sup></label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ date('d/M/Y', strtotime($camp->camp_date)) }}" name="camp_date" class="form-control form-control-md dtpicker" placeholder="dd/mm/yyyy">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('camp_date')
                                    <small class="text-danger">{{ $errors->first('camp_date') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-5">
                                    <label class="form-label">Patient Name<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $camp->patient_name }}" name="patient_name" class="form-control form-control-md" placeholder="Patient Name">
                                    @error('patient_name')
                                    <small class="text-danger">{{ $errors->first('patient_name') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Age<sup class="text-danger">*</sup></label>
                                    <input type="number" value="{{ $camp->age }}" name="age" class="form-control form-control-md" placeholder="0">
                                    @error('age')
                                    <small class="text-danger">{{ $errors->first('age') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Gender</label>
                                    <select class="form-control" name="gender">
                                        <option value="M" {{ ($camp->gender == 'M') ? 'selected' : '' }}>Male</option>
                                        <option value="F" {{ ($camp->gender == 'F') ? 'selected' : '' }}>Female</option>                                        
                                        <option value="O" {{ ($camp->gender == 'O') ? 'selected' : '' }}>Other</option>                                        
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" value="{{ $camp->phone_number }}" maxlength="10" name="phone_number" class="form-control form-control-md" placeholder="Phone Number">
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" value="{{ $camp->address }}" name="address" class="form-control form-control-md" placeholder="Address">
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Further Investigation Required</label>
                                    <select class="form-control" name="treatment_required">
                                        <option value="0" {{ ($camp->treatment_required == 0) ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ ($camp->treatment_required == 1) ? 'selected' : '' }}>Yes</option>                                        
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Glasses Required</label>
                                    <select class="form-control" name="specs_required">
                                        <option value="0" {{ ($camp->specs_required == 0) ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ ($camp->specs_required == 1) ? 'selected' : '' }}>Yes</option>                                        
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Yearly Eye Test Advised</label>
                                    <select class="form-control" name="specs_required">
                                        <option value="0" {{ ($camp->yearly_test_advised == 0) ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ ($camp->yearly_test_advised == 1) ? 'selected' : '' }}>Yes</option>                                        
                                    </select>
                                </div>
                                <div class="col-sm-5">
                                    <label class="form-label">Notes</label>
                                    <input type="text" value="{{ $camp->notes }}" name="notes" class="form-control form-control-md" placeholder="Notes">
                                </div>
                                <div class="col-sm-12 table-responsive">
                                    <table class="table table-bordered">
                                        <thead><tr><th>Eye</th><th>VB</th><th>Sph</th><th>Cyl</th><th>Axis</th><th>Add</th><th>VA</th></tr></thead>
                                        <tbody>
                                            <tr>
                                                <td>RE</td>
                                                <td><input class="form-control form-control-sm" type="text" value="{{ $camp->re_vb }}" name="re_vb" placeholder="0/0" /></td>
                                                <td><input class="form-control form-control-sm" value="{{ $camp->re_sph }}" type="text" name="re_sph" placeholder="0.00" /></td>
                                                <td><input class="form-control form-control-sm" type="text" value="{{ $camp->re_cyl }}" name="re_cyl" placeholder="0.00" /></td>
                                                <td><input class="form-control form-control-sm" type="text" value="{{ $camp->re_axis }}" name="re_axis" placeholder="0" /></td>
                                                <td><input class="form-control form-control-sm" type="text" value="{{ $camp->re_add }}" name="re_add" placeholder="0.00" /></td>                                                
                                                <td><input class="form-control form-control-sm" type="text" value="{{ $camp->re_va }}" name="re_va" placeholder="0/0" /></td>
                                            </tr>
                                            <tr>
                                                <td>LE</td>
                                                <td><input class="form-control form-control-sm" type="text" value="{{ $camp->le_vb }}" name="le_vb" placeholder="0/0" /></td>
                                                <td><input class="form-control form-control-sm" type="text" value="{{ $camp->le_sph }}" name="le_sph" placeholder="0.00" /></td>
                                                <td><input class="form-control form-control-sm" type="text" value="{{ $camp->le_cyl }}" name="le_cyl" placeholder="0.00" /></td>
                                                <td><input class="form-control form-control-sm" type="text" value="{{ $camp->le_axis }}" name="le_axis" placeholder="0" /></td>
                                                <td><input class="form-control form-control-sm" type="text" value="{{ $camp->le_add }}" name="le_add" placeholder="0.00" /></td>                                                
                                                <td><input class="form-control form-control-sm" type="text" value="{{ $camp->le_va }}" name="le_va" placeholder="0/0" /></td>
                                            </tr>
                                        </tbody>
                                    </table>
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