@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Create Keratometry</h5>
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
                        <form action="{{ route('keratometry.save') }}" method="post">
                            @csrf
                            <input type="hidden" name="medical_record_id" value="{{ $mrecord->id }}" />
                            <input type="hidden" name="patient_id" value="{{ ($patient) ? $patient->id : 0 }}" />
                            <input type="hidden" name="branch" value="{{ $mrecord->branch }}" />
                            <div class="row g-4">
                                <div class="col-sm-12 table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="20%"></th>
                                                <th width="10%">K1 (A)</th>
                                                <th width="10%">AXIS</th>
                                                <th width="10%">K2 (A)</th>
                                                <th width="10%">AXIS</th>
                                                <th width="10%">K1 (M)</th>
                                                <th width="10%">AXIS</th>
                                                <th width="10%">K2 (M)</th>
                                                <th width="10%">AXIS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>OD</td>
                                                <td>
                                                    <select class="form-control form-control-sm select2" name="k1_od_auto">
                                                        <option value="">Sel</option>
                                                        @forelse($powers as $key => $power)
                                                        <option value="{{ $power->value }}">{{ $power->value }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="k1_od_axis_a" max="999" step="1" placeholder="0" />
                                                </td>
                                                <td>
                                                    <select class="form-control form-control-sm select2" name="k2_od_auto">
                                                        <option value="">Sel</option>
                                                        @forelse($powers as $key => $power)
                                                        <option value="{{ $power->value }}">{{ $power->value }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="k2_od_axis_a" max="999" step="1" placeholder="0" />
                                                </td>
                                                <td>
                                                    <select class="form-control form-control-sm select2" name="k1_od_manual">
                                                        <option value="">Sel</option>
                                                        @forelse($powers as $key => $power)
                                                        <option value="{{ $power->value }}">{{ $power->value }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="k1_od_axis_m" max="999" step="1" placeholder="0" />
                                                </td>
                                                <td>
                                                    <select class="form-control form-control-sm select2" name="k2_od_manual">
                                                        <option value="">Sel</option>
                                                        @forelse($powers as $key => $power)
                                                        <option value="{{ $power->value }}">{{ $power->value }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="k2_od_axis_m" max="999" step="1" placeholder="0" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>OS</td>
                                                <td>
                                                    <select class="form-control form-control-sm select2" name="k1_os_auto">
                                                        <option value="">Sel</option>
                                                        @forelse($powers as $key => $power)
                                                        <option value="{{ $power->value }}">{{ $power->value }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="k1_os_axis_a" max="999" step="1" placeholder="0" />
                                                </td>
                                                <td>
                                                    <select class="form-control form-control-sm select2" name="k2_os_auto">
                                                        <option value="">Sel</option>
                                                        @forelse($powers as $key => $power)
                                                        <option value="{{ $power->value }}">{{ $power->value }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="k2_os_axis_a" max="999" step="1" placeholder="0" />
                                                </td>
                                                <td>
                                                    <select class="form-control form-control-sm select2" name="k1_os_manual">
                                                        <option value="">Sel</option>
                                                        @forelse($powers as $key => $power)
                                                        <option value="{{ $power->value }}">{{ $power->value }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="k1_os_axis_m" max="999" step="1" placeholder="0" />
                                                </td>
                                                <td>
                                                    <select class="form-control form-control-sm select2" name="k2_os_manual">
                                                        <option value="">Sel</option>
                                                        @forelse($powers as $key => $power)
                                                        <option value="{{ $power->value }}">{{ $power->value }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="k2_os_axis_m" max="999" step="1" placeholder="0" />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row g-4">
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