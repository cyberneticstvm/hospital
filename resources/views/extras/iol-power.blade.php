@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">IOL Power</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
                @endif
                @if(session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
                @endif
                <div class="row">
                    <div class="card col-6">
                        <div class="card-body table-responsive">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-primary">OD</h5>
                                    <form class="mt-3" method="post" action="{{ route('iol.power.calculate') }}" id="frmIolPowerOd">
                                        @csrf
                                        <input type="hidden" name="type" value="OD" />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="form-label">Formula<sup class="text-danger">*</sup></label>
                                                <select class="form-control" name="formula">
                                                    <option value="0">Let system decide</option>
                                                    <option value="1">Hoffer Q</option>
                                                    <option value="2" disabled>Haigis</option>
                                                    <option value="3">Holladay 2</option>
                                                    <option value="4">SRK/T</option>
                                                    <option value="5">Barrett Universal II</option>
                                                    <option value="6" disabled>Kane</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <label class="form-label">A-Constant</label>
                                                <input type="text" value="{{ old('a_constant') }}" name="a_constant" class="form-control form-control-md" placeholder="0">
                                                @error('a_constant')
                                                <small class="text-danger">{{ $errors->first('a_constant') }}</small>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Axial Length<sup class="text-danger">*</sup></label>
                                                <input type="text" value="{{ old('axial_length') }}" name="axial_length" class="form-control form-control-md" placeholder="0">
                                                @error('axial_length')
                                                <small class="text-danger">{{ $errors->first('axial_length') }}</small>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">ACD</label>
                                                <input type="text" value="{{ old('acd') }}" name="acd" class="form-control form-control-md" placeholder="0">
                                                @error('acd')
                                                <small class="text-danger">{{ $errors->first('acd') }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <label class="form-label">K1</label>
                                                <input type="text" value="{{ old('k1') }}" name="k1" class="form-control form-control-md" placeholder="0">
                                                @error('k1')
                                                <small class="text-danger">{{ $errors->first('k1') }}</small>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">K2</label>
                                                <input type="text" value="{{ old('k2') }}" name="k2" class="form-control form-control-md" placeholder="0">
                                                @error('k2')
                                                <small class="text-danger">{{ $errors->first('k2') }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row mt-3">
                                            <div class="col-12">Applicable for Haigis Formula</div>
                                            <div class="col-md-6">
                                                <label class="form-label">Desired postoperative refraction (R)</label>
                                                <input type="text" value="{{ old('target_refraction') }}" name="target_refraction" class="form-control form-control-md" placeholder="0">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">A0</label>
                                                <input type="text" value="{{ old('a0') }}" name="a0" class="form-control form-control-md" placeholder="0">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">A1</label>
                                                <input type="text" value="{{ old('a1') }}" name="a1" class="form-control form-control-md" placeholder="0">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">A2</label>
                                                <input type="text" value="{{ old('a2') }}" name="a2" class="form-control form-control-md" placeholder="0">
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row mt-3">
                                            <div class="col-12">Applicable for Holladay 2 Formula</div>
                                            <div class="col-md-6">
                                                <label class="form-label">Preoperative Refraction (R)</label>
                                                <input type="text" value="{{ old('target_refraction') }}" name="target_refraction" class="form-control form-control-md" placeholder="0">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Lens Thick</label>
                                                <input type="text" value="{{ old('lens_thick') }}" name="lens_thick" class="form-control form-control-md" placeholder="0">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">WTW</label>
                                                <input type="text" value="{{ old('wtw') }}" name="wtw" class="form-control form-control-md" placeholder="0">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Age</label>
                                                <input type="text" value="{{ old('age') }}" name="age" class="form-control form-control-md" placeholder="0">
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row mt-3">
                                            <div class="col-md-6 fw-bold">
                                                <div class="odIolPower"></div>
                                                <div class="odMsg"></div>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <button type="button" class="btn btn-primary btnIolPower">Calculate</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card col-6">
                        <div class="card-body table-responsive">
                            <h5 class="text-primary">OS</h5>
                            <form class="mt-3" method="post" action="{{ route('iol.power.calculate') }}" id="frmIolPowerOs">
                                @csrf
                                <input type="hidden" name="type" value="OS" />
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Formula<sup class="text-danger">*</sup></label>
                                        <select class="form-control" name="formula">
                                            <option value="0">Let system decide</option>
                                            <option value="1">Hoffer Q</option>
                                            <option value="2" disabled>Haigis</option>
                                            <option value="3">Holladay 2</option>
                                            <option value="4">SRK/T</option>
                                            <option value="5">Barrett Universal II</option>
                                            <option value="6" disabled>Kane</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <label class="form-label">A-Constant</label>
                                        <input type="text" value="{{ old('a_constant') }}" name="a_constant" class="form-control form-control-md" placeholder="0">
                                        @error('a_constant')
                                        <small class="text-danger">{{ $errors->first('a_constant') }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Axial Length<sup class="text-danger">*</sup></label>
                                        <input type="text" value="{{ old('axial_length') }}" name="axial_length" class="form-control form-control-md" placeholder="0">
                                        @error('axial_length')
                                        <small class="text-danger">{{ $errors->first('axial_length') }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">ACD</label>
                                        <input type="text" value="{{ old('acd') }}" name="acd" class="form-control form-control-md" placeholder="0">
                                        @error('acd')
                                        <small class="text-danger">{{ $errors->first('acd') }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <label class="form-label">K1</label>
                                        <input type="text" value="{{ old('k1') }}" name="k1" class="form-control form-control-md" placeholder="0">
                                        @error('k1')
                                        <small class="text-danger">{{ $errors->first('k1') }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">K2</label>
                                        <input type="text" value="{{ old('k2') }}" name="k2" class="form-control form-control-md" placeholder="0">
                                        @error('k2')
                                        <small class="text-danger">{{ $errors->first('k2') }}</small>
                                        @enderror
                                    </div>

                                </div>
                                <hr />
                                <div class="row mt-3">
                                    <div class="col-12">Applicable for Haigis Formula</div>
                                    <div class="col-md-6">
                                        <label class="form-label">Desired postoperative refraction (R)</label>
                                        <input type="text" value="{{ old('target_refraction') }}" name="target_refraction" class="form-control form-control-md" placeholder="0">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">A0</label>
                                        <input type="text" value="{{ old('a0') }}" name="a0" class="form-control form-control-md" placeholder="0">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">A1</label>
                                        <input type="text" value="{{ old('a1') }}" name="a1" class="form-control form-control-md" placeholder="0">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">A2</label>
                                        <input type="text" value="{{ old('a2') }}" name="a2" class="form-control form-control-md" placeholder="0">
                                    </div>
                                </div>
                                <hr />
                                <div class="row mt-3">
                                    <div class="col-12">Applicable for Holladay 2 Formula</div>
                                    <div class="col-md-6">
                                        <label class="form-label">Preoperative Refraction (R)</label>
                                        <input type="text" value="{{ old('target_refraction') }}" name="target_refraction" class="form-control form-control-md" placeholder="0">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Lens Thick</label>
                                        <input type="text" value="{{ old('lens_thick') }}" name="lens_thick" class="form-control form-control-md" placeholder="0">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">WTW</label>
                                        <input type="text" value="{{ old('wtw') }}" name="wtw" class="form-control form-control-md" placeholder="0">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Age</label>
                                        <input type="text" value="{{ old('age') }}" name="age" class="form-control form-control-md" placeholder="0">
                                    </div>
                                </div>
                                <hr />
                                <div class="row mt-3">
                                    <div class="col-md-6 fw-bold">
                                        <div class="osIolPower"></div>
                                        <div class="osMsg"></div>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" class="btn btn-primary btnIolPower">Calculate</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection