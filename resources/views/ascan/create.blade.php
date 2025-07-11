@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Create A-Scan</h5>
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
                            <div class="col-sm-3">Age: <h5 class="text-primary">{{ ($age) ? $age : '' }}</h5>
                            </div>
                            @if($pref->rc_type && $pref->rc_number)
                            <div class="col-sm-2 text-end">
                                <label>Royalty Card Applied</label>
                                <img src="/public/images/rc-card.jpg" width="50%" />
                            </div>
                            @endif
                        </div>
                        <form action="{{ route('ascan.save') }}" method="post">
                            @csrf
                            <input type="hidden" name="medical_record_id" value="{{ $mrecord->id }}" />
                            <input type="hidden" name="patient_id" value="{{ ($patient) ? $patient->id : 0 }}" />
                            <input type="hidden" name="branch" value="{{ $mrecord->branch }}" />
                            <div class="row g-4">
                                <div class="col-sm-12 table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>AXL
                                                <th>ACD</th>
                                                <th>LENS</th>
                                                <th>A-CONST.</th>
                                                <th>IOL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>OD</td>
                                                <td><input type="text" name="od_axl" class="form-control" placeholder="Axl" /></td>
                                                <td><input type="text" name="od_acd" class="form-control" placeholder="Acd" /></td>
                                                <td><input type="text" name="od_lens" class="form-control" placeholder="Lens" /></td>
                                                <td><input type="text" name="od_a_constant" class="form-control" placeholder="A-Constant" /></td>
                                                <td><input type="number" step="any" name="od_iol_power" class="form-control" placeholder="IOL Power" /></td>
                                            </tr>
                                            <tr>
                                                <td>OS</td>
                                                <td><input type="text" name="os_axl" class="form-control" placeholder="Axl" /></td>
                                                <td><input type="text" name="os_acd" class="form-control" placeholder="Acd" /></td>
                                                <td><input type="text" name="os_lens" class="form-control" placeholder="Lens" /></td>
                                                <td><input type="text" name="os_a_constant" class="form-control" placeholder="A-Constant" /></td>
                                                <td><input type="number" step="any" name="os_iol_power" class="form-control" placeholder="IOL Power" /></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row g-4">
                                    <div class="col-sm-2">
                                        <label class="form-label">Eye <sup class="text-danger">*</sup></label>
                                        <input type="text" name="eye" class="form-control" placeholder="Eye" />
                                    </div>
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
                                <div class="col-sm-12 table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="text-center">
                                            <tr>
                                                <th colspan="2">OD</th>
                                                <th colspan="2">OS</th>
                                            </tr>
                                            <tr>
                                                <th>A.const</th>
                                                <th>IOL</th>
                                                <th>A.const</th>
                                                <th>IOL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="text" name="aconst_od1" class="form-control" placeholder="OD" /></td>
                                                <td><input type="text" name="iol_od1" class="form-control" placeholder="OD" /></td>
                                                <td><input type="text" name="aconst_os1" class="form-control" placeholder="OS" /></td>
                                                <td><input type="text" name="iol_os1" class="form-control" placeholder="OS" /></td>
                                            </tr>
                                            <tr>
                                                <td><input type="text" name="aconst_od2" class="form-control" placeholder="OD" /></td>
                                                <td><input type="text" name="iol_od2" class="form-control" placeholder="OD" /></td>
                                                <td><input type="text" name="aconst_os2" class="form-control" placeholder="OS" /></td>
                                                <td><input type="text" name="iol_os2" class="form-control" placeholder="OS" /></td>
                                            </tr>
                                            <tr>
                                                <td><input type="text" name="aconst_od3" class="form-control" placeholder="OD" /></td>
                                                <td><input type="text" name="iol_od3" class="form-control" placeholder="OD" /></td>
                                                <td><input type="text" name="aconst_os3" class="form-control" placeholder="OS" /></td>
                                                <td><input type="text" name="iol_os3" class="form-control" placeholder="OS" /></td>
                                            </tr>
                                        </tbody>
                                    </table>
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