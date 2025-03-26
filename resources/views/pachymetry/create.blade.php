@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Create Pachymetry</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body table-responsive">
                        <div class="row g-4 mb-3">
                            <div class="col-sm-2">MRN: <h5 class="text-primary">{{ $mrecord->id }}</h5>
                            </div>
                            <div class="col-sm-3">Patient Name: <h5 class="text-primary">{{ ($patient) ? $patient->patient_name : '' }}</h5>
                            </div>
                            <div class="col-sm-2">Patient ID: <h5 class="text-primary">{{ ($patient) ? $patient->patient_id : '' }}</h5>
                            </div>
                            @if($pref->rc_type && $pref->rc_number)
                            <div class="col-sm-2 text-end">
                                <label>Royalty Card Applied</label>
                                <img src="/public/images/rc-card.jpg" width="50%" />
                            </div>
                            @endif
                        </div>
                        <form action="{{ route('pachymetry.save') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="medical_record_id" value="{{ $mrecord->id }}" />
                            <input type="hidden" name="patient_id" value="{{ ($patient) ? $patient->id : 0 }}" />
                            <input type="hidden" name="branch" value="{{ $mrecord->branch }}" />
                            <div class="row g-4">
                                <div class="col-sm-12 table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center" colspan="4">OD</th>
                                                <th class="text-center" colspan="3">OS</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th>IOP</th>
                                                <th>CCT</th>
                                                <th>CIOP</th>
                                                <th>IOP</th>
                                                <th>CCT</th>
                                                <th>CIOP</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>AVG</td>
                                                <td><input type="text" name="od_iop" class="form-control" placeholder="OD-IOP"></td>
                                                <td><input type="text" name="od_cct" class="form-control" placeholder="OD-CCT"></td>
                                                <td><input type="text" name="od_ciop" class="form-control" placeholder="OD-CIOP"></td>
                                                <td><input type="text" name="os_iop" class="form-control" placeholder="OS-IOP"></td>
                                                <td><input type="text" name="os_cct" class="form-control" placeholder="OS-CCT"></td>
                                                <td><input type="text" name="os_ciop" class="form-control" placeholder="OS-CIOP"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <label class="form-label">PCY (OD)</label>
                                    <input type="file" name="img1" class="form-control form-control-md pachy" />
                                    <input type="text" name="img1_value" placeholder="Description" class="form-control form-control-md" />
                                    <div class="container"><img src="" class="img1 img-fluid" /></div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">PCY (OS)</label>
                                    <input type="file" name="img2" class="form-control form-control-md pachy" />
                                    <input type="text" name="img2_value" placeholder="Description" class="form-control form-control-md" />
                                    <div class="container"><img src="" class="img2 img-fluid" /></div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">ACA (OD)</label>
                                    <input type="file" name="img3" class="form-control form-control-md pachy" />
                                    <input type="text" name="img3_value" placeholder="Description" class="form-control form-control-md" />
                                    <div class="container"><img src="" class="img3 img-fluid" /></div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">ACA (OS)</label>
                                    <input type="file" name="img4" class="form-control form-control-md pachy" />
                                    <input type="text" name="img4_value" placeholder="Description" class="form-control form-control-md" />
                                    <div class="container"><img src="" class="img4 img-fluid" /></div>
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
                                <div class="col-sm-6">
                                    <label class="form-label">Procedures<sup class="text-danger">*</sup> <small class="text-info">(Multiple selection enabled)</small></label>
                                    <select class="form-control form-control-md show-tick ms select2" multiple data-placeholder="Select" name="procedure[]" required="required">
                                        <option value="">Select</option>
                                        @foreach($procedures as $proc)
                                        <option value="{{ $proc->id }}" {{ old('procedure') == $proc->id ? 'selected' : '' }}>{{ $proc->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('procedure')
                                    <small class="text-danger">{{ $errors->first('procedure') }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
                                <div class="col-sm-12 text-end">
                                    <button type="button" onClick="history.back()" class="btn btn-danger">Cancel</button>
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