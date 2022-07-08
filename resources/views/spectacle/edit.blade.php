@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Update Spectacle Prescription</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('spectacle.update', $spectacle->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="medical_record_id" value="{{ $spectacle->medical_record_id }}" />
                            <input type="hidden" id="age" value="{{ $age }}" />
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}" />
                            <div class="row g-4">
                                <div class="col-sm-3">Medical Record No: <h5 class="text-primary">{{ $mrecord->id }}</h5></div>
                                <div class="col-sm-3">Patient Name / Age: <h5 class="text-primary">{{ $patient->patient_name }} / {{ $age }}</h5></div>
                                <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ $patient->patient_id }}</h5></div>
                                <div class="col-sm-3">Doctor Name: <h5 class="text-primary">{{ $doctor->doctor_name }}</h5></div>
                            </div>
                            <div class="row mt-3">
                                <p class="fw-bold">EYE GLASS PRESCRIPTION</p>
                                <div class="col-sm-6 table-responsive">
                                    <table class="table spectacle">
                                        <thead><tr><th>RE</th><th>SPH</th><th>CYL</th><th>AXIS</th><th>VA</th><th>PRISM</th></tr></thead>
                                        <tbody class="tbodyre">
                                            <tr>
                                                <td>DIST.</td>
                                                <td><input type="text" class="form-control form-control-md re_dist_sph" name="re_dist_sph" value="{{ $spectacle->re_dist_sph }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md re_dist_cyl" name="re_dist_cyl" value="{{ $spectacle->re_dist_cyl }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md re_dist_axis" name="re_dist_axis" value="{{ $spectacle->re_dist_axis }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md re_dist_va text-uppercase" name="re_dist_va" value="{{ $spectacle->re_dist_va }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" name="re_dist_prism" value="{{ $spectacle->re_dist_prism }}" placeholder="0"/></td>
                                            </tr>
                                            <tr>
                                                <td>INT.</td>
                                                <td><input type="text" class="form-control form-control-md re_int_sph" name="re_int_sph" value="{{ $spectacle->re_int_sph }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md re_int_cyl" name="re_int_cyl" value="{{ $spectacle->re_int_cyl }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md re_int_axis" value="{{ $spectacle->re_int_axis }}" name="re_int_axis" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md text-uppercase" name="re_int_va" value="{{ $spectacle->re_int_va }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->re_int_prism }}" name="re_int_prism" placeholder="0"/></td>
                                            </tr>
                                            <tr>
                                                <td>NEAR.</td>
                                                <td><input type="text" class="form-control form-control-md re_near_sph" value="{{ $spectacle->re_near_sph }}" name="re_near_sph" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md re_near_cyl" value="{{ $spectacle->re_near_cyl }}" name="re_near_cyl" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md re_near_axis" value="{{ $spectacle->re_near_axis }}" name="re_near_axis" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md text-uppercase" name="re_near_va" value="{{ $spectacle->re_near_va }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->re_near_prism }}" name="re_near_prism" placeholder="0"/></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">ADD</td><td><input type="text" class="form-control form-control-md re_dist_add" name="re_dist_add" value="{{ $spectacle->re_dist_add }}" placeholder="0"/></td><td class="fw-bold text-right">INT ADD</td><td><input type="text" class="form-control form-control-md re_int_add" value="{{ $spectacle->re_int_add }}" name="re_int_add" placeholder="0"/></td><td class="text-right fw-bold">RE => LE</td><td><input type="checkbox" class="chkREtoLE"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-6 table-responsive">
                                    <table class="table spectacle">
                                        <thead><tr><th>LE</th><th>SPH</th><th>CYL</th><th>AXIS</th><th>VA</th><th>PRISM</th></tr></thead>
                                        <tbody class="tbodyle">
                                            <tr>
                                                <td>DIST.</td>
                                                <td><input type="text" class="form-control form-control-md le_dist_sph" name="le_dist_sph" value="{{ $spectacle->le_dist_sph }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md le_dist_cyl" name="le_dist_cyl" value="{{ $spectacle->le_dist_cyl }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md le_dist_axis" name="le_dist_axis" value="{{ $spectacle->le_dist_axis }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md le_dist_va text-uppercase" name="le_dist_va" value="{{ $spectacle->le_dist_va }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" name="le_dist_prism" value="{{ $spectacle->le_dist_prism }}" placeholder="0"/></td>
                                            </tr>
                                            <tr>
                                                <td>INT.</td>
                                                <td><input type="text" class="form-control form-control-md" name="le_int_sph" value="{{ $spectacle->le_int_sph }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md le_int_cyl" name="le_int_cyl" value="{{ $spectacle->le_int_cyl }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md le_int_axis" name="le_int_axis" value="{{ $spectacle->le_int_axis }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md le_int_va text-uppercase" name="le_int_va" value="{{ $spectacle->le_int_va }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" name="le_int_prism" value="{{ $spectacle->le_int_prism }}" placeholder="0"/></td>
                                            </tr>
                                            <tr>
                                                <td>NEAR.</td>
                                                <td><input type="text" class="form-control form-control-md le_near_sph" name="le_near_sph" value="{{ $spectacle->le_near_sph }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md le_near_cyl" name="le_near_cyl" value="{{ $spectacle->le_near_cyl }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md le_near_axis" name="le_near_axis" value="{{ $spectacle->le_near_axis }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md le_near_va text-uppercase" name="le_near_va" value="{{ $spectacle->le_near_va }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" name="le_near_prism" value="{{ $spectacle->le_near_prism }}" placeholder="0"/></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">ADD</td><td><input type="text" class="form-control form-control-md le_dist_add" name="le_dist_add" value="{{ $spectacle->le_dist_add }}" placeholder="0"/></td><td class="fw-bold text-right">INT ADD</td><td><input type="text" class="form-control form-control-md le_int_add" value="{{ $spectacle->le_int_add }}" name="le_int_add" placeholder="0"/></td><td colspan="2"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-12 table-responsive">
                                    <table class="table spectacle">
                                        <thead><tr><th>VD</th><th>IPD</th><th>NPD</th><th>RPD</th><th>LPD</th><th>OD-BC</th><th>OS-BC</th></tr></thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="text" class="form-control form-control-md" name="vd" value="{{ $spectacle->vd }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md ipd" name="ipd" value="{{ $spectacle->ipd }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md npd" name="npd" value="{{ $spectacle->npd }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md rpd" name="rpd" value="{{ $spectacle->rpd }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md lpd" name="lpd" value="{{ $spectacle->lpd }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" name="vbr" value="{{ $spectacle->vbr }}" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" name="vbl" value="{{ $spectacle->vbl }}" placeholder="0"/></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr class="bg-primary" style="height: 10px;" />
                            <div class="row mt-3">
                                <div class="col-sm-3 table-responsive">
                                    <p class="fw-bold">IOP</p>
                                    <table class="table spectacle">
                                        <thead><tr><th></th><th>NCT</th><th>AT</th></tr></thead>
                                        <tbody class="">
                                            <tr>
                                                <td>R</td>
                                                <td><input type="text" maxlength="7" value="{{ $spectacle->re_iop }}" name="re_iop" class="form-control form-control-md" placeholder="0"></td>
                                                <td><input type="text" maxlength="7" class="form-control form-control-md" placeholder="0"></td>
                                            </tr>
                                            <tr>
                                                <td>L</td>
                                                <td><input type="text" maxlength="7" value="{{ $spectacle->le_iop }}" name="le_iop" class="form-control form-control-md" placeholder="0"></td>
                                                <td><input type="text" maxlength="7" class="form-control form-control-md" placeholder="0"></td>
                                            </tr>
                                            <tr>
                                                <td>Time</td>
                                                <td><input type="text" maxlength="10" name="nct_time" class="form-control form-control-md" placeholder="0"></td>
                                                <td><input type="text" maxlength="10" name="at_time" class="form-control form-control-md" placeholder="0"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>                                
                                <div class="col-sm-3 table-responsive">
                                    <p class="fw-bold">ARM VALUE</p>
                                    <table class="table">
                                        <thead><tr><th></th><th>SPH</th><th>CYL</th><th>AXIS</th></tr></thead>
                                        <tbody class="">
                                            <tr>
                                                <td>OD</td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->arm_od_sph }}" name="arm_od_sph" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->arm_od_cyl }}"name="arm_od_cyl" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->arm_od_axis }}" name="arm_od_axis" placeholder="0"/></td>
                                            </tr>
                                            <tr>
                                                <td>OS</td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->arm_os_sph }}" name="arm_os_sph" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->arm_os_cyl }}" name="arm_os_cyl" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->arm_os_axis }}" name="arm_os_axis" placeholder="0"/></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-6 table-responsive">
                                    <p class="fw-bold">PGP</p>
                                    <table class="table">
                                        <thead><tr><th></th><th>SPH</th><th>CYL</th><th>AXIS</th><th>ADD</th><th>VISION</th><th>NV</th></tr></thead>
                                        <tbody class="">
                                            <tr>
                                                <td>OD</td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->pgp_od_sph }}" name="pgp_od_sph" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->pgp_od_cyl }}" name="pgp_od_cyl" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->pgp_od_axis }}" name="pgp_od_axis" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md text-uppercase" value="{{ $spectacle->pgp_od_add }}" name="pgp_od_add" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->pgp_od_vision }}" name="pgp_od_vision" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->pgp_od_nv }}" name="pgp_od_nv" placeholder="0"/></td>
                                            </tr>
                                            <tr>
                                                <td>OS</td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->pgp_os_sph }}" name="pgp_os_sph" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->pgp_os_cyl }}" name="pgp_os_cyl" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->pgp_os_axis }}" name="pgp_os_axis" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md text-uppercase" value="{{ $spectacle->pgp_os_add }}" name="pgp_os_add" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->pgp_os_vision }}" name="pgp_os_vision" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->pgp_os_nv }}" name="pgp_os_nv" placeholder="0"/></td>
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
                                        <thead><tr><th></th><th>SPH</th><th>CYL</th><th>AXIS</th><th>ADD</th><th>VISION</th><th>NV</th></tr></thead>
                                        <tbody class="">
                                            <tr>
                                                <td>OD</td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->dr_od_sph }}" name="dr_od_sph" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->dr_od_cyl }}" name="dr_od_cyl" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->dr_od_axis }}" name="dr_od_axis" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md value="{{ $spectacle->dr_od_add }}" text-uppercase" name="dr_od_add" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->dr_od_vision }}" name="dr_od_vision" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->dr_od_nv }}" name="dr_od_nv" placeholder="0"/></td>
                                            </tr>
                                            <tr>
                                                <td>OS</td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->dr_os_sph }}" name="dr_os_sph" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->dr_os_cyl }}" name="dr_os_cyl" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->dr_os_axis }}" name="dr_os_axis" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md text-uppercase" value="{{ $spectacle->dr_os_add }}" name="dr_os_add" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->dr_os_vision }}" name="dr_os_vision" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->dr_os_nv }}" name="dr_os_nv" placeholder="0"/></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>                                
                                <div class="col-sm-6 table-responsive">
                                    <p class="fw-bold">CONTACT LENS PRESCRIPTION</p>
                                    <table class="table">
                                    <thead><tr><th></th><th>BASE CURVE</th><th>DIAMETER</th><th>SPH</th><th>CYL</th><th>AXIS</th></tr></thead>
                                    <tbody>
                                        <tr>
                                            <td>RIGHT</td>
                                            <td><input type="text" class="form-control form-control-md" name="re_base_curve" value="{{ $spectacle->re_base_curve }}" placeholder="0"/></td>
                                            <td><input type="text" class="form-control form-control-md" name="re_dia" value="{{ $spectacle->re_dia }}" placeholder="0"/></td>
                                            <td><input type="text" class="form-control form-control-md" name="re_sph" value="{{ $spectacle->re_sph }}" placeholder="0"/></td>
                                            <td><input type="text" class="form-control form-control-md" name="re_cyl" value="{{ $spectacle->re_cyl }}" placeholder="0"/></td>
                                            <td><input type="text" class="form-control form-control-md" name="re_axis" value="{{ $spectacle->re_axis }}" placeholder="0"/></td>
                                        </tr>
                                        <tr>
                                            <td>LEFT</td>
                                            <td><input type="text" class="form-control form-control-md" name="le_base_curve" value="{{ $spectacle->le_base_curve }}" placeholder="0"/></td>
                                            <td><input type="text" class="form-control form-control-md" name="le_dia" value="{{ $spectacle->le_dia }}" placeholder="0"/></td>
                                            <td><input type="text" class="form-control form-control-md" name="le_sph" value="{{ $spectacle->le_sph }}" placeholder="0"/></td>
                                            <td><input type="text" class="form-control form-control-md" name="le_cyl" value="{{ $spectacle->le_cyl }}" placeholder="0"/></td>
                                            <td><input type="text" class="form-control form-control-md" name="le_axis" value="{{ $spectacle->le_axis }}" placeholder="0"/></td>
                                        </tr>
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12 table-responsive">
                                    <p class="fw-bold">Biometry</p>
                                    <table class="table">
                                        <thead><tr><th></th><th>K1(A)</th><th>K2(A)</th><th>K1(M)</th><th>K2(M)</th><th>AXL</th><th>ACD</th><th>LENS</th><th>K-VALUE(A)</th><th>IOL POWER</th></tr></thead>
                                        <tbody class="">
                                            <tr>
                                                <td>OD</td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->bm_k1_od_a }}" name="bm_k1_od_a" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->bm_k2_od_a }}" name="bm_k2_od_a" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->bm_k1_od_m }}" name="bm_k1_od_m" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->bm_k2_od_m }}" name="bm_k2_od_m" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->bm_od_axl }}" name="bm_od_axl" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->bm_od_acd }}" name="bm_od_acd" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->bm_od_lens }}" name="bm_od_lens" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->bm_od_kvalue_a }}" name="bm_od_kvalue_a" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->bm_od_iol }}" name="bm_od_iol" placeholder="0"/></td>
                                            </tr>
                                            <tr>
                                                <td>OS</td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->bm_k1_os_a }}" name="bm_k1_os_a" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->bm_k2_os_a }}" name="bm_k2_os_a" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->bm_k1_os_m }}" name="bm_k1_os_m" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->bm_k2_os_m }}" name="bm_k2_os_m" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->bm_os_axl }}" name="bm_os_axl" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->bm_os_acd }}" name="bm_os_acd" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->bm_os_lens }}" name="bm_os_lens" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->bm_os_kvalue_a }}" name="bm_os_kvalue_a" placeholder="0"/></td>
                                                <td><input type="text" class="form-control form-control-md" value="{{ $spectacle->bm_os_iol }}" name="bm_os_iol" placeholder="0"/></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>                                
                            </div>
                            <div class="row g-4">
                                <div class="col-sm-4">
                                    <label class="form-label">Remarks</label>
                                    <input type="text" value="{{ $spectacle->remarks }}" name="remarks" class="form-control form-control-md" placeholder="Remarks">
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Advice</label>
                                    <input type="text" value="{{ $spectacle->advice }}" name="advice" class="form-control form-control-md" placeholder="Advice">
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Advised a further examination not later</label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ ($spectacle->review_date) ? date('d/M/Y', strtotime($spectacle->review_date)) : '' }}" name="review_date" class="form-control form-control-md dtpicker" placeholder="dd/mm/yyyy">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
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