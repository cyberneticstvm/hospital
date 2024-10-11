@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Create Spectacle Prescription</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('spectacle.save') }}" method="post">
                            @csrf
                            <input type="hidden" name="medical_record_id" value="{{ $mrecord->id }}" />
                            <input type="hidden" id="age" value="{{ $age }}" />
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}" />
                            <input type="hidden" name="ctype" value="{{ $pref->consultation_type }}" />
                            <div class="row g-4">
                                <div class="col-sm-3">Medical Record No: <h5 class="text-primary">{{ $mrecord->id }}</h5>
                                </div>
                                <div class="col-sm-3">Patient Name / Age: <h5 class="text-primary">{{ $patient->patient_name }} / {{ $age }}</h5>
                                </div>
                                <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ $patient->patient_id }}</h5>
                                </div>
                                <div class="col-sm-3">Doctor Name: <h5 class="text-primary">{{ $doctor->doctor_name }}</h5>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <p class="fw-bold">EYE GLASS PRESCRIPTION</p>
                                <div class="col-sm-6 table-responsive mb-3">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th colspan="4">OD - Uncorrected Visual Acuity</th>
                                                <th colspan="2"><input type="text" class="form-control form-control-md" name="vbr" placeholder="0" /></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="col-sm-6 table-responsive mb-3">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th colspan="4">OS - Uncorrected Visual Acuity</th>
                                                <th colspan="2"><input type="text" class="form-control form-control-md" name="vbl" placeholder="0" /></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="col-sm-6 table-responsive">
                                    <table class="table spectacle">
                                        <thead>
                                            <tr>
                                                <th>RE</th>
                                                <th>SPH</th>
                                                <th>CYL</th>
                                                <th>AXIS</th>
                                                <th>BCV</th>
                                                <th>PRISM</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbodyre">
                                            <tr>
                                                <td>DIST.</td>
                                                <td>
                                                    <select name="re_dist_sph" class="select2 form-control re_dist_sph">
                                                        @foreach($powers->where('name', 'sph') as $key => $p)
                                                        <option value="{{ $p->value }}" {{ ($p->default) ? 'selected' : '' }}>{{ $p->value }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="re_dist_cyl" class="select2 form-control re_dist_cyl">
                                                        @foreach($powers->where('name', 'cyl') as $key => $p)
                                                        <option value="{{ $p->value }}" {{ ($p->default) ? 'selected' : '' }}>{{ $p->value }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="re_dist_axis" class="select2 form-control re_dist_axis">
                                                        @foreach($powers->where('name', 'axis') as $key => $p)
                                                        <option value="{{ $p->value }}" {{ ($p->default) ? 'selected' : '' }}>{{ $p->value }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control form-control-md re_dist_va text-uppercase" name="re_dist_va" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="re_dist_prism" placeholder="0" /></td>
                                            </tr>
                                            <tr>
                                                <td>INT.</td>
                                                <td><input type="text" class="form-control form-control-md re_int_sph" name="re_int_sph" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md re_int_cyl" name="re_int_cyl" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md re_int_axis" name="re_int_axis" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md text-uppercase" name="re_int_va" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="re_int_prism" placeholder="0" /></td>
                                            </tr>
                                            <tr>
                                                <td>NEAR.</td>
                                                <td><input type="text" class="form-control form-control-md re_near_sph" name="re_near_sph" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md re_near_cyl" name="re_near_cyl" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md re_near_axis" name="re_near_axis" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md re_near_va text-uppercase" name="re_near_va" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="re_near_prism" placeholder="0" /></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">ADD</td>
                                                <td><!--<input type="text" class="form-control form-control-md re_dist_add" name="re_dist_add" placeholder="0"/>-->
                                                    <select class="form-control re_dist_add" name="re_dist_add">
                                                        <option value="">Select</option>
                                                        @foreach($reading_adds as $key => $radd)
                                                        <option value="{{ $radd->value }}">{{ $radd->value }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="fw-bold text-right">INT ADD</td>
                                                <td>
                                                    <select name="re_int_add" class="select2 form-control re_int_add">
                                                        @foreach($powers->where('name', 'intad') as $key => $p)
                                                        <option value="{{ $p->value }}" {{ ($p->default) ? 'selected' : '' }}>{{ $p->value }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="text-right fw-bold">RE => LE</td>
                                                <td><input type="checkbox" class="chkREtoLE"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-6 table-responsive">
                                    <table class="table spectacle">
                                        <thead>
                                            <tr>
                                                <th>LE</th>
                                                <th>SPH</th>
                                                <th>CYL</th>
                                                <th>AXIS</th>
                                                <th>BCV</th>
                                                <th>PRISM</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbodyle">
                                            <tr>
                                                <td>DIST.</td>
                                                <td>
                                                    <select name="le_dist_sph" class="select2 form-control le_dist_sph">
                                                        @foreach($powers->where('name', 'sph') as $key => $p)
                                                        <option value="{{ $p->value }}" {{ ($p->default) ? 'selected' : '' }}>{{ $p->value }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="le_dist_cyl" class="select2 form-control le_dist_cyl">
                                                        @foreach($powers->where('name', 'cyl') as $key => $p)
                                                        <option value="{{ $p->value }}" {{ ($p->default) ? 'selected' : '' }}>{{ $p->value }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="le_dist_axis" class="select2 form-control le_dist_axis">
                                                        @foreach($powers->where('name', 'axis') as $key => $p)
                                                        <option value="{{ $p->value }}" {{ ($p->default) ? 'selected' : '' }}>{{ $p->value }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control form-control-md le_dist_va text-uppercase" name="le_dist_va" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="le_dist_prism" placeholder="0" /></td>
                                            </tr>
                                            <tr>
                                                <td>INT.</td>
                                                <td><input type="text" class="form-control form-control-md le_int_sph" name="le_int_sph" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md le_int_cyl" name="le_int_cyl" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md le_int_axis" name="le_int_axis" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md text-uppercase" name="le_int_va" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="le_int_prism" placeholder="0" /></td>
                                            </tr>
                                            <tr>
                                                <td>NEAR.</td>
                                                <td><input type="text" class="form-control form-control-md le_near_sph" name="le_near_sph" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md le_near_cyl" name="le_near_cyl" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md le_near_axis" name="le_near_axis" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md le_near_va text-uppercase" name="le_near_va" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="le_near_prism" placeholder="0" /></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">ADD</td>
                                                <td><!--<input type="text" class="form-control form-control-md le_dist_add" name="le_dist_add" placeholder="0"/>-->
                                                    <select class="form-control le_dist_add" name="le_dist_add">
                                                        <option value="">Select</option>
                                                        @foreach($reading_adds as $key => $radd)
                                                        <option value="{{ $radd->value }}">{{ $radd->value }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="fw-bold text-right">INT ADD</td>
                                                <td>
                                                    <select name="le_int_add" class="select2 form-control le_int_add">
                                                        @foreach($powers->where('name', 'intad') as $key => $p)
                                                        <option value="{{ $p->value }}" {{ ($p->default) ? 'selected' : '' }}>{{ $p->value }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-12 table-responsive">
                                    <table class="table spectacle">
                                        <thead>
                                            <tr>
                                                <th>VD</th>
                                                <th>IPD</th>
                                                <th>NPD</th>
                                                <th>RPD</th>
                                                <th>LPD</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="text" class="form-control form-control-md" name="vd" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md ipd" name="ipd" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md npd" name="npd" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md rpd" name="rpd" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md lpd" name="lpd" placeholder="0" /></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr class="bg-primary" style="height: 10px;" />
                            <div class="row mt-3">
                                <!--<div class="col-sm-3 table-responsive">
                                    <p class="fw-bold">IOP</p>
                                    <table class="table spectacle">
                                        <thead><tr><th></th><th>NCT</th><th>AT</th></tr></thead>
                                        <tbody class="">
                                            <tr>
                                                <td>R</td>
                                                <td><input type="text" maxlength="7" value="{{ old('re_iop') }}" name="re_iop" class="form-control form-control-md" placeholder="0" tabindex="1"></td>
                                                <td><input type="text" maxlength="7" name="iop_at_r" class="form-control form-control-md" placeholder="0" tabindex="4"></td>
                                            </tr>
                                            <tr>
                                                <td>L</td>
                                                <td><input type="text" maxlength="7" value="{{ old('le_iop') }}" name="le_iop" class="form-control form-control-md" placeholder="0" tabindex="2"></td>
                                                <td><input type="text" maxlength="7" name="iop_at_l" class="form-control form-control-md" placeholder="0" tabindex="5"></td>
                                            </tr>
                                            <tr>
                                                <td>Time</td>
                                                <td><input type="text" maxlength="10" name="iop_nct_time" class="form-control form-control-md text-uppercase" placeholder="0" tabindex="3"></td>
                                                <td><input type="text" maxlength="10" name="iop_at_time" class="form-control form-control-md text-uppercase" placeholder="0" tabindex="6"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>-->
                                <div class="col-sm-5 table-responsive">
                                    <p class="fw-bold">ARM VALUE</p>
                                    <table class="table spectacle">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>SPH</th>
                                                <th>CYL</th>
                                                <th>AXIS</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            <tr>
                                                <td>OD</td>
                                                <td><input type="text" class="form-control form-control-md" name="arm_od_sph" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="arm_od_cyl" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="arm_od_axis" placeholder="0" /></td>
                                            </tr>
                                            <tr>
                                                <td>OS</td>
                                                <td><input type="text" class="form-control form-control-md" name="arm_os_sph" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="arm_os_cyl" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="arm_os_axis" placeholder="0" /></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-7 table-responsive">
                                    <p class="fw-bold">PGP</p>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>SPH</th>
                                                <th>CYL</th>
                                                <th>AXIS</th>
                                                <th>ADD</th>
                                                <th>VISION</th>
                                                <th>NV</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            <tr>
                                                <td>OD</td>
                                                <td><input type="text" class="form-control form-control-md" name="pgp_od_sph" placeholder="0" value="{{ $previous?->re_dist_sph }}" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="pgp_od_cyl" value="{{ $previous?->re_dist_cyl }}" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="pgp_od_axis" value="{{ $previous?->re_dist_axis }}" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md text-uppercase" name="pgp_od_add" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="pgp_od_vision" value="{{ $previous?->re_dist_va }}" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="pgp_od_nv" value="{{ $previous?->re_near_va }}" placeholder="0" /></td>
                                            </tr>
                                            <tr>
                                                <td>OS</td>
                                                <td><input type="text" class="form-control form-control-md" name="pgp_os_sph" value="{{ $previous?->le_dist_sph }}" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="pgp_os_cyl" value="{{ $previous?->le_dist_cyl }}" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="pgp_os_axis" value="{{ $previous?->le_dist_axis }}" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md text-uppercase" name="pgp_os_add" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="pgp_os_vision" value="{{ $previous?->le_dist_va }}" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="pgp_os_nv" value="{{ $previous?->le_near_va }}" placeholder="0" /></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr class="bg-primary" style="height: 10px;" />
                            <div class="row mt-3">
                                <div class="col-sm-6 table-responsive">
                                    <p class="fw-bold">Dilated Refraction</p>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>SPH</th>
                                                <th>CYL</th>
                                                <th>AXIS</th>
                                                <th>ADD</th>
                                                <th>VISION</th>
                                                <th>NV</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            <tr>
                                                <td>OD</td>
                                                <td><input type="text" class="form-control form-control-md" name="dr_od_sph" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="dr_od_cyl" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="dr_od_axis" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md text-uppercase" name="dr_od_add" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="dr_od_vision" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="dr_od_nv" placeholder="0" /></td>
                                            </tr>
                                            <tr>
                                                <td>OS</td>
                                                <td><input type="text" class="form-control form-control-md" name="dr_os_sph" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="dr_os_cyl" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="dr_os_axis" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md text-uppercase" name="dr_os_add" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="dr_os_vision" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="dr_os_nv" placeholder="0" /></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-6 table-responsive">
                                    <p class="fw-bold">CONTACT LENS PRESCRIPTION</p>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>BASE CURVE</th>
                                                <th>DIAMETER</th>
                                                <th>SPH</th>
                                                <th>CYL</th>
                                                <th>AXIS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>RE</td>
                                                <td><input type="text" class="form-control form-control-md" name="re_base_curve" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="re_dia" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="re_sph" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="re_cyl" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="re_axis" placeholder="0" /></td>
                                            </tr>
                                            <tr>
                                                <td>LE</td>
                                                <td><input type="text" class="form-control form-control-md" name="le_base_curve" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="le_dia" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="le_sph" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="le_cyl" placeholder="0" /></td>
                                                <td><input type="text" class="form-control form-control-md" name="le_axis" placeholder="0" /></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--<div class="row mt-3">
                                <div class="col-sm-12 table-responsive">
                                    <p class="fw-bold">Biometry</p>
                                    <table class="table">
                                        <thead><tr><th></th><th>K1(A)</th><th>K2(A)</th><th>K1(M)</th><th>K2(M)</th><th>AXL</th><th>ACD</th><th>LENS</th><th>K-VALUE(AVG)</th><th>IOL POWER</th></tr></thead>
                                        <tbody class="">
                                            <tr>
                                                <td>OD</td>
                                                <td><input type="text" class="form-control form-control-md bm_k1_od_a" name="bm_k1_od_a" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md bm_k2_od_a" name="bm_k2_od_a" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" name="bm_k1_od_m" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" name="bm_k2_od_m" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" name="bm_od_axl" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" name="bm_od_acd" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" name="bm_od_lens" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md bm_od_kvalue_a" name="bm_od_kvalue_a" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" name="bm_od_iol" placeholder="0"/></td>
                                            </tr>
                                            <tr>
                                                <td>OS</td>
                                                <td><input type="text" class="form-control form-control-md bm_k1_os_a" name="bm_k1_os_a" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md bm_k2_os_a" name="bm_k2_os_a" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" name="bm_k1_os_m" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" name="bm_k2_os_m" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" name="bm_os_axl" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" name="bm_os_acd" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" name="bm_os_lens" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md bm_os_kvalue_a" name="bm_os_kvalue_a" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" name="bm_os_iol" placeholder="0"/></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>                                
                            </div>-->
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">Remarks</label>
                                    <select class="form-control form-control-md" name="remarks">
                                        <option value="">Select</option>
                                        <option value="BIFOCAL/PROGRESSIVE LENSES" {{ (old('remarks') == 'BIFOCAL/PROGRESSIVE LENSES') ? 'selected' : '' }}>BIFOCAL/PROGRESSIVE LENSES</option>
                                        <option value="FOR CONSTSNT USE" {{ (old('remarks') == 'FOR CONSTSNT USE') ? 'selected' : '' }}>FOR CONSTSNT USE</option>
                                        <option value="FOR DV ONLY" {{ (old('remarks') == 'FOR DV ONLY') ? 'selected' : '' }}>FOR DV ONLY</option>
                                        <option value="FOR NV ONLY" {{ (old('remarks') == 'FOR NV ONLY') ? 'selected' : '' }}>FOR NV ONLY</option>
                                        <option value="SAME AS PG" {{ (old('remarks') == 'SAME AS PG') ? 'selected' : '' }}>SAME AS PG</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Advice</label>
                                    <input type="text" value="{{ old('advice') }}" name="advice" class="form-control form-control-md" placeholder="Advice">
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Glasses Prescribed?</label>
                                    <select class="form-control form-control-md" name="glasses_prescribed" required>
                                        <option value="yes" {{ (old('glasses_prescribed') == 'yes') ? 'selected' : '' }}>Glasses Prescribed</option>
                                        <option value="no" {{ (old('glasses_prescribed') == 'no') ? 'selected' : '' }}>Glasses Not recommended</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Advised a further examination not later</label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ old('review_date') }}" name="review_date" class="form-control form-control-md dtpicker" placeholder="dd/mm/yyyy">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                            </svg>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
                                <div class="col-sm-12 text-right">
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