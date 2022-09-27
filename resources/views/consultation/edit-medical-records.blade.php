@extends("templates.base")

@section("content")
<div class="body d-flex">
    <div class="container-fluid">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Edit Consultation (Medical Records)</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('medical-records.update', $record->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="mrid" value="{{ $record->id }}"/>
                            <input type="hidden" name="mrn" value="{{ $record->mrn }}"/>
                            <input type="hidden" name="patient_id" value="{{ $record->patient_id }}"/>
                            <input type="hidden" name="doctor_id" value="{{ $record->doctor_id }}"/>
                            <input type="hidden" id="btn_text" value="Update"/>
                            <div class="row g-4 mb-3">
                                <div class="col-sm-3">MRN: <h5 class="text-primary">{{ $record->mrn }}</h5></div>
                                <div class="col-sm-3">Patient Name: <h5 class="text-primary">{{ $patient->patient_name }}</h5></div>
                                <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ $patient->patient_id }}</h5></div>
                                <div class="col-sm-3">Doctor Name: <h5 class="text-primary">{{ $doctor->doctor_name }}</h5></div>
                                <div class="col-sm-12 text-center">
                                    <p><a href="{{ route('patienthistory', $patient->id) }}" target="_blank">VIEW PATINET MEDICAL HISTORY</a></p>
                                </div>
                                <div class="col-sm-11">
                                    @php $olds = explode(',', $record->symptoms); @endphp
                                    <label class="form-label">Symptoms<sup class="text-danger">*</sup> <small class="text-info">(Multiple selection enabled)</small></label>
                                    <select class="form-control form-control-md show-tick ms select2" multiple data-placeholder="Select" name="symptom_id[]" id="symptomSelect">
                                    <option value="">Select</option>
                                    @foreach($symptoms as $sympt)
                                        @php $selected = ''; @endphp
                                        @foreach($olds as $key => $value)
                                            @if($sympt->id == $value)
                                                {{ $selected = 'selected' }}
                                            @endif
                                        @endforeach
                                        <option value="{{ $sympt->id }}" {{ $selected }}>{{ $sympt->symptom_name }}</option>
                                    @endforeach
                                    </select>
                                    @error('symptom_id')
                                    <small class="text-danger">{{ $errors->first('symptom_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-1">
                                    <a data-bs-toggle="modal" href="#symptomModal"><i class="fa fa-plus fa-lg text-success"></i></a>
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">Symptoms</label>
                                    <textarea class="form-control form-control-md" name="symptoms_other" rows="5" placeholder="Symptoms">{{ $record->symptoms_other }}</textarea>
                                    @error('symptoms_other')
                                    <small class="text-danger">{{ $errors->first('symptoms_other') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">Patient History</label>
                                    <textarea class="form-control form-control-md" name="history" rows="5" placeholder="Patient History">{{ $record->history }}</textarea>
                                    @error('history')
                                    <small class="text-danger">{{ $errors->first('history') }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-6 table-responsive">
                                    <label class="form-label">Vision</label>
                                    <table class="table table-bordered">
                                        <thead class="text-center"><tr><th>&nbsp;</th><th>SPH</th><th>CYL</th><th>AXIS</th><th>ADD</th><th>VA</th><th>NV</th><th></th></tr></thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center fw-bold">RE/OD</td>
                                                <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->re_dist_sph : '0.00' }}" placeholder="0" readonly="true" /></td>
                                                <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->re_dist_cyl : '0.00' }}" placeholder="0" readonly="true" /></td>
                                                <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->re_dist_axis : '0.00' }}" placeholder="0" readonly="true" /></td>
                                                <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->re_dist_add : '--' }}" placeholder="0" readonly="true" /></td>
                                                <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->vbr : '--' }}" placeholder="0" readonly="true" /></td>
                                                <!--<td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->re_near_va : '--' }}" placeholder="0" readonly="true" /></td>-->
                                                <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->re_dist_va : '--' }}" placeholder="0" readonly="true" /></td>
                                                <td><input class="form-control form-control-md" name="va_od" type="text" maxlength="6" value="{{ $record->va_od }}" placeholder="" /></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center fw-bold">LE/OS</td>                                                
                                                <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->le_dist_sph : '0.00' }}" placeholder="0" readonly="true" /></td>
                                                <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->le_dist_cyl : '0.00' }}" placeholder="0" readonly="true" /></td>
                                                <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->le_dist_axis : '0.00' }}" placeholder="0" readonly="true" /></td>
                                                <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->le_dist_add : '--' }}" placeholder="0" readonly="true" /></td>
                                                <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->vbl : '--' }}" placeholder="0" readonly="true" /></td>
                                                <!--<td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->le_near_va : '--' }}" placeholder="0" readonly="true" /></td>-->
                                                <td><input class="form-control form-control-md" type="text" maxlength="6" value="{{ ($spectacle) ? $spectacle->le_dist_va : '--' }}" placeholder="0" readonly="true" /></td>
                                                <td><input class="form-control form-control-md" name="va_os" type="text" maxlength="6" value="{{ $record->va_os }}" placeholder="" /></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-6 table-responsive">
                                    <label class="form-label">Biometry</label>
                                    <table class="table table-bordered" style="">
                                        <thead class="text-center">
                                            <tr><th></th><th>K1 (Auto)</th><th>K2 (Auto)</th><th>K1 (Manual)</th><th>K2 (Manual)</th><th>AXL</th></tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center fw-bold">OD</td>
                                                <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->bm_k1_od_a : '--' }}" name="k1_od_auto" maxlength="6" placeholder="0" readonly="true"/></td>
                                                <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->bm_k2_od_a : '--' }}" name="k2_od_auto" maxlength="6" placeholder="0" readonly="true"/></td>
                                                <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->bm_k1_od_m : '--' }}" name="k1_od_manual" maxlength="6" placeholder="0" readonly="true"/></td>
                                                <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->bm_k2_od_m : '--' }}" name="k2_od_manual" maxlength="6" placeholder="0" readonly="true"/></td>
                                                <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->bm_od_axl : '--' }}" placeholder="0" readonly="true"/></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center fw-bold">OS</td>
                                                <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->bm_k1_os_a : '--' }}" name="k1_os_auto" maxlength="6" placeholder="0" readonly="true"/></td>
                                                <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->bm_k2_os_a : '--' }}" name="k2_os_auto" maxlength="6" placeholder="0" readonly="true"/></td>
                                                <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->bm_k1_os_m : '--' }}" name="k1_os_manual" maxlength="6" placeholder="0" readonly="true"/></td>
                                                <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->bm_k2_os_m : '--' }}" name="k2_os_manual" maxlength="6" placeholder="0" readonly="true"/></td>
                                                <td><input class="form-control form-control-md" type="text" value="{{ ($spectacle) ? $spectacle->bm_os_axl : '--' }}" placeholder="0" readonly="true"/></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col-sm-6 text-center">
                                    <label class="form-label">OD</label>                                    
                                </div>
                                <div class="col-sm-6 text-center">
                                    <label class="form-label">OS</label>
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_1" multiple data-placeholder="Select" name="sel_1_od[]">
                                        @php $olds = explode(',', $record->sel_1_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 1)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">Appearance<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_1"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_1" multiple data-placeholder="Select" name="sel_1_os[]">
                                        @php $olds = explode(',', $record->sel_1_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 1)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_2" multiple data-placeholder="Select" name="sel_2_od[]">
                                        @php $olds = explode(',', $record->sel_2_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 2)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">Extraocular Movements<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_2"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_2" multiple data-placeholder="Select" name="sel_2_os[]">
                                        @php $olds = explode(',', $record->sel_2_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 2)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_3" multiple data-placeholder="Select" name="sel_3_od[]">
                                        @php $olds = explode(',', $record->sel_3_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 3)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">Orbital Margins<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_3"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_3" multiple data-placeholder="Select" name="sel_3_os[]">
                                        @php $olds = explode(',', $record->sel_3_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 3)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>
                            </div>
                            <div class="row g-4  mt-3">
                                <div class="col-sm-1">
                                    <label class="form-label">Color Picker</label>
                                    <input type="color" class="form-control form-control-md" id="favcolor" name="favcolor" value="#ff0000">
                                </div>
                            </div>
                            <div class="row g-4 mt-1">
                                <div class="col-sm-6">
                                    <label class="form-label">OD</label>
                                    <img id="imgreye" src="{{ ($record->vision_od_img1) ? $record->vision_od_img1 : public_path().'/storage/assets/images/eye-re.jpg' }}" class="d-none">
                                    <img id="imgreye_1" src="{{ public_path().'/storage/assets/images/eye-re.jpg' }}" class="d-none">
                                    <canvas id="re_eye" style="border: 1px solid #000;"></canvas>
                                    <div class="odpoints">
                                        @if($vision)
                                            @foreach($vision as $v)
                                                @if($v->img_type == 'vision_od_img1')
                                                    <span class='badge bg-light' data-color="{{ $v->color }}" data-itype="{{ $v->img_type }}" style="color: {{ $v->color }}">{{ $v->description }}</span>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    <a href="javascript:void(0)" id='odclear' class="btn btn-secondary mt-1">Clear</a>
                                    <a href="javascript:void(0)" id='odundo' class="btn btn-warning mt-1 d-none">Undo</a>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">OS</label>
                                    <img id="imgleye" src="{{ ($record->vision_os_img1) ? $record->vision_os_img1 : public_path().'/storage/assets/images/eye-le.jpg' }}" class="d-none">
                                    <img id="imgleye_1" src="{{ public_path().'/storage/assets/images/eye-le.jpg' }}" class="d-none">
                                    <canvas id="le_eye" style="border: 1px solid #000;"></canvas>
                                    <div class="ospoints">
                                        @if($vision)
                                            @foreach($vision as $v)
                                                @if($v->img_type == 'vision_os_img1')
                                                    <span class='badge bg-light' data-color="{{ $v->color }}" data-itype="{{ $v->img_type }}" style="color: {{ $v->color }}">{{ $v->description }}</span>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    <a href="javascript:void(0)" id='osclear' class="btn btn-secondary mt-1">Clear</a>
                                    <a href="javascript:void(0)" id='osundo' class="btn btn-warning mt-1 d-none">Undo</a>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col-sm-4 text-center">
                                    <label class="form-label">OD</label>                                    
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label"></label>                                    
                                </div>
                                <div class="col-sm-4 text-center">
                                    <label class="form-label">OS</label>
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_4" multiple data-placeholder="Select" name="sel_4_od[]">
                                        @php $olds = explode(',', $record->sel_4_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 4)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">LID and Adnexa<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_4"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_4" multiple data-placeholder="Select" name="sel_4_os[]">
                                        @php $olds = explode(',', $record->sel_4_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 4)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>
                            </div>
                            <div class="row g-4 mt-1">
                                <div class="col-sm-6">
                                    <label class="form-label">OD</label>
                                    <img id="imgreye1" src="{{ ($record->vision_od_img2) ? $record->vision_od_img2 : public_path().'/storage/assets/images/od_lens.jpg' }}" class="d-none">
                                    <img id="imgreye1_1" src="{{ public_path().'/storage/assets/images/od_lens.jpg' }}" class="d-none">
                                    <canvas id="re_eye1" style="border: 1px solid #000;"></canvas>
                                    <div class="odpoints1">
                                        @if($vision)
                                            @foreach($vision as $v)
                                                @if($v->img_type == 'vision_od_img2')
                                                    <span class='badge bg-light' data-color="{{ $v->color }}" data-itype="{{ $v->img_type }}" style="color: {{ $v->color }}">{{ $v->description }}</span>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    <a href="javascript:void(0)" id='odclear1' class="btn btn-secondary mt-1">Clear</a>
                                    <a href="javascript:void(0)" id='odundo1' class="btn btn-warning mt-1 d-none">Undo</a>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">OS</label>
                                    <img id="imgleye1" src="{{ ($record->vision_os_img2) ? $record->vision_os_img2 : public_path().'/storage/assets/images/os_lens.jpg' }}" class="d-none">
                                    <img id="imgleye1_1" src="{{ public_path().'/storage/assets/images/os_lens.jpg' }}" class="d-none">
                                    <canvas id="le_eye1" style="border: 1px solid #000;"></canvas>
                                    <div class="ospoints1">
                                        @if($vision)
                                            @foreach($vision as $v)
                                                @if($v->img_type == 'vision_os_img2')
                                                    <span class='badge bg-light' data-color="{{ $v->color }}" data-itype="{{ $v->img_type }}" style="color: {{ $v->color }}">{{ $v->description }}</span>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    <a href="javascript:void(0)" id='osclear1' class="btn btn-secondary mt-1">Clear</a>
                                    <a href="javascript:void(0)" id='osundo1' class="btn btn-warning mt-1 d-none">Undo</a>
                                </div>
                            </div>
                            <div class="row g-4 mt-1">
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_5" multiple data-placeholder="Select" name="sel_5_od[]">
                                        @php $olds = explode(',', $record->sel_5_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 5)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">Conjunctiva<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_5"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_5" multiple data-placeholder="Select" name="sel_5_os[]">
                                        @php $olds = explode(',', $record->sel_5_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 5)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_6" multiple data-placeholder="Select" name="sel_6_od[]">
                                        @php $olds = explode(',', $record->sel_6_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 6)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">Sclera<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_6"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_6" multiple data-placeholder="Select" name="sel_6_os[]">
                                        @php $olds = explode(',', $record->sel_6_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 6)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_7" multiple data-placeholder="Select" name="sel_7_od[]">
                                        @php $olds = explode(',', $record->sel_7_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 7)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">Cornea<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_7"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_7" multiple data-placeholder="Select" name="sel_7_os[]">
                                        @php $olds = explode(',', $record->sel_7_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 7)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_8" multiple data-placeholder="Select" name="sel_8_od[]">
                                        @php $olds = explode(',', $record->sel_8_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 8)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">Anterior Chamber<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_8"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_8" multiple data-placeholder="Select" name="sel_8_os[]">
                                        @php $olds = explode(',', $record->sel_8_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 8)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>
                            </div>
                            <div class="row g-4 mt-1">
                                <div class="col-sm-6">
                                    <label class="form-label">OD</label>
                                    <img id="imgreye2" src="{{ ($record->vision_od_img3) ? $record->vision_od_img3 : public_path().'/storage/assets/images/img-round-od.jpg' }}" class="d-none">
                                    <img id="imgreye2_1" src="{{ public_path().'/storage/assets/images/img-round-od.jpg' }}" class="d-none">
                                    <canvas id="re_eye2" style="border: 1px solid #000;"></canvas>
                                    <div class="odpoints2">
                                        @if($vision)
                                            @foreach($vision as $v)
                                                @if($v->img_type == 'vision_od_img3')
                                                    <span class='badge bg-light' data-color="{{ $v->color }}" data-itype="{{ $v->img_type }}" style="color: {{ $v->color }}">{{ $v->description }}</span>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    <a href="javascript:void(0)" id='odclear2' class="btn btn-secondary mt-1">Clear</a>
                                    <a href="javascript:void(0)" id='odundo2' class="btn btn-warning mt-1 d-none">Undo</a>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">OS</label>
                                    <img id="imgleye2" src="{{ ($record->vision_os_img3) ? $record->vision_os_img3 : public_path().'/storage/assets/images/img-round-os.jpg' }}" class="d-none">
                                    <img id="imgleye2_1" src="{{ public_path().'/storage/assets/images/img-round-os.jpg' }}" class="d-none">
                                    <canvas id="le_eye2" style="border: 1px solid #000;"></canvas>
                                    <div class="ospoints2">
                                        @if($vision)
                                            @foreach($vision as $v)
                                                @if($v->img_type == 'vision_os_img3')
                                                    <span class='badge bg-light' data-color="{{ $v->color }}" data-itype="{{ $v->img_type }}" style="color: {{ $v->color }}">{{ $v->description }}</span>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    <a href="javascript:void(0)" id='osclear2' class="btn btn-secondary mt-1">Clear</a>
                                    <a href="javascript:void(0)" id='osundo2' class="btn btn-warning mt-1 d-none">Undo</a>
                                </div>
                            </div>
                            <div class="row g-4 mt-1">
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_9" multiple data-placeholder="Select" name="sel_9_od[]">
                                        @php $olds = explode(',', $record->sel_9_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 9)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">Iris<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_9"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_9" multiple data-placeholder="Select" name="sel_9_os[]">
                                        @php $olds = explode(',', $record->sel_9_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 9)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_10" multiple data-placeholder="Select" name="sel_10_od[]">
                                        @php $olds = explode(',', $record->sel_10_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 10)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">Pupil<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_10"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_10" multiple data-placeholder="Select" name="sel_10_os[]">
                                        @php $olds = explode(',', $record->sel_10_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 10)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_11" multiple data-placeholder="Select" name="sel_11_od[]">
                                        @php $olds = explode(',', $record->sel_11_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 11)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">Lens<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_11"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_11" multiple data-placeholder="Select" name="sel_11_os[]">
                                        @php $olds = explode(',', $record->sel_11_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 11)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>
                            </div>
                            <div class="row g-4 mt-1">
                                <div class="col-sm-6">
                                    <label class="form-label">OD</label>
                                    <img id="imgreye3" src="{{ ($record->vision_od_img4) ? $record->vision_od_img4 : public_path().'/storage/assets/images/right-eye-od.jpg' }}" class="d-none">
                                    <img id="imgreye3_1" src="{{ public_path().'/storage/assets/images/right-eye-od.jpg' }}" class="d-none">
                                    <canvas id="re_eye3" style="border: 1px solid #000;"></canvas>
                                    <div class="odpoints3">
                                        @if($vision)
                                            @foreach($vision as $v)
                                                @if($v->img_type == 'vision_od_img4')
                                                    <span class='badge bg-light' data-color="{{ $v->color }}" data-itype="{{ $v->img_type }}" style="color: {{ $v->color }}">{{ $v->description }}</span>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    <a href="javascript:void(0)" id='odclear3' class="btn btn-secondary mt-1">Clear</a>
                                    <a href="javascript:void(0)" id='odundo3' class="btn btn-warning mt-1 d-none">Undo</a>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">OS</label>
                                    <img id="imgleye3" src="{{ ($record->vision_os_img4) ? $record->vision_os_img4 : public_path().'/storage/assets/images/left-eye-os.jpg' }}" class="d-none">
                                    <img id="imgleye3_1" src="{{ public_path().'/storage/assets/images/left-eye-os.jpg' }}" class="d-none">
                                    <canvas id="le_eye3" style="border: 1px solid #000;"></canvas>
                                    <div class="ospoints3">
                                        @if($vision)
                                            @foreach($vision as $v)
                                                @if($v->img_type == 'vision_os_img4')
                                                    <span class='badge bg-light' data-color="{{ $v->color }}" data-itype="{{ $v->img_type }}" style="color: {{ $v->color }}">{{ $v->description }}</span>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    <a href="javascript:void(0)" id='osclear3' class="btn btn-secondary mt-1">Clear</a>
                                    <a href="javascript:void(0)" id='osundo3' class="btn btn-warning mt-1 d-none">Undo</a>
                                </div>
                            </div>
                            <div class="row g-4 mt-1">
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_12" multiple data-placeholder="Select" name="sel_12_od[]">
                                        @php $olds = explode(',', $record->sel_12_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 12)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">AVF<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_12"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_12" multiple data-placeholder="Select" name="sel_12_os[]">
                                        @php $olds = explode(',', $record->sel_12_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 12)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_13" multiple data-placeholder="Select" name="sel_13_od[]">
                                        @php $olds = explode(',', $record->sel_13_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 13)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">Fundus<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_13"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_13" multiple data-placeholder="Select" name="sel_13_os[]">
                                        @php $olds = explode(',', $record->sel_13_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 13)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>
                            </div>
                            <div class="row g-4 mt-1">
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_14" multiple data-placeholder="Select" name="sel_14_od[]">
                                        @php $olds = explode(',', $record->sel_14_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 14)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">Media<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_14"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_14" multiple data-placeholder="Select" name="sel_14_os[]">
                                        @php $olds = explode(',', $record->sel_14_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 14)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_15" multiple data-placeholder="Select" name="sel_15_od[]">
                                        @php $olds = explode(',', $record->sel_15_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 15)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">Disc Margins<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_15"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_15" multiple data-placeholder="Select" name="sel_15_os[]">
                                        @php $olds = explode(',', $record->sel_15_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 15)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>

                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_16" multiple data-placeholder="Select" name="sel_16_od[]">
                                        @php $olds = explode(',', $record->sel_16_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 16)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">CDR<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_16"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_16" multiple data-placeholder="Select" name="sel_16_os[]">
                                        @php $olds = explode(',', $record->sel_16_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 16)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_17" multiple data-placeholder="Select" name="sel_17_od[]">
                                        @php $olds = explode(',', $record->sel_17_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 17)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">NRR<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_17"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_17" multiple data-placeholder="Select" name="sel_17_os[]">
                                        @php $olds = explode(',', $record->sel_17_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 17)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>

                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_18" multiple data-placeholder="Select" name="sel_18_od[]">
                                        @php $olds = explode(',', $record->sel_18_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 18)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">AV Ratio & Bloodvessels<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_18"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_18" multiple data-placeholder="Select" name="sel_18_os[]">
                                        @php $olds = explode(',', $record->sel_18_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 18)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>

                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_19" multiple data-placeholder="Select" name="sel_19_od[]">
                                        @php $olds = explode(',', $record->sel_19_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 19)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">FR<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_19"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_19" multiple data-placeholder="Select" name="sel_19_os[]">
                                        @php $olds = explode(',', $record->sel_19_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 19)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>

                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_20" multiple data-placeholder="Select" name="sel_20_od[]">
                                        @php $olds = explode(',', $record->sel_20_od); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 20)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">Background Retina & Periphery<br/><a href="javascript:void(0)" class="vEModal" data-ddl="sel_20"><i class="fa fa-plus fa-lg text-success"></i></a></label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-md show-tick ms select2 sel_20" multiple data-placeholder="Select" name="sel_20_os[]">
                                        @php $olds = explode(',', $record->sel_20_os); @endphp
                                        @foreach($vextras as $v)
                                            @php $selected = ''; @endphp
                                            @foreach($olds as $key => $value)
                                                @if($v->id == $value)
                                                    {{ $selected = 'selected' }}
                                                @endif
                                            @endforeach
                                            @if($v->cat_id == 20)
                                            <option value="{{ $v->id }}" {{ $selected }}>{{ $v->name }}</option>
                                            @endif
                                        @endforeach
                                    </select> 
                                </div>                                
                            </div>
                            <div class="row g-4 mt-3">
                                <div class="col-sm-5">
                                    <table class="table table-borderless" style="background:url({{ public_path().'/images/assets/x-png-30.png' }}); background-repeat: no-repeat; background-position: center;">
                                        <tr><td></td><td width="20%"><input type="text" class="form-control" name="gonio_od_top" value="{{ $record->gonio_od_top }}" placeholder="0" /></td><td></td></tr>
                                        <tr><td><input type="text" class="form-control" name="gonio_od_left" value="{{ $record->gonio_od_left }}" placeholder="0" /></td><td class="text-center"></td><td><input type="text" class="form-control" name="gonio_od_right" value="{{ $record->gonio_od_right }}" placeholder="0" /></td></tr>
                                        <tr><td></td><td><input type="text" class="form-control" name="gonio_od_bottom" value="{{ $record->gonio_od_bottom }}" placeholder="0" /></td><td></td></tr>
                                    </table>
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">Gonioscopy</label>                                    
                                </div>
                                <div class="col-sm-5">
                                    <table class="table table-borderless" style="background:url({{ public_path().'/images/assets/x-png-30.png' }}); background-repeat: no-repeat; background-position: center;">
                                        <tr><td></td><td width="20%"><input type="text" class="form-control" name="gonio_os_top" value="{{ $record->gonio_os_top }}" placeholder="0" /></td><td></td></tr>
                                        <tr><td><input type="text" class="form-control" name="gonio_os_left" value="{{ $record->gonio_os_left }}" placeholder="0" /></td><td class="text-center"></td><td><input type="text" class="form-control" name="gonio_os_right" value="{{ $record->gonio_os_right }}" placeholder="0" /></td></tr>
                                        <tr><td></td><td><input type="text" class="form-control" name="gonio_os_bottom" value="{{ $record->gonio_os_bottom }}" placeholder="0" /></td><td></td></tr>
                                    </table>
                                </div>
                                <div class="col-sm-5 text-center">
                                    <input type="text" class="form-control" name="gonio_od" value="{{ $record->gonio_od }}"  placeholder="0" />                                 
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="form-label">&nbsp;</label>                                    
                                </div>
                                <div class="col-sm-5 text-center">
                                    <input type="text" class="form-control" name="gonio_os" value="{{ $record->gonio_os }}"  placeholder="0" /> 
                                </div>
                            </div>
                            <div class="row g-4 mt-1">
                                <div class="col-sm-11">
                                    <label class="form-label">Signs</label>
                                    <textarea class="form-control form-control-md" name="signs" rows="5" placeholder="Signs">{{ $record->signs }}</textarea>
                                    @error('signs')
                                    <small class="text-danger">{{ $errors->first('signs') }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-4 mt-1">
                                <div class="col-sm-6">                                    
                                    <label class="form-label">OD</label>
                                    <input type="file" class="form-control retina_od" name="retina_od" id="retina_od" data-container="retina_od_container" data-type="od">
                                    <div class="retina_od_container mt-3 mb-3">
                                        @if($retina_od)
                                            @foreach($retina_od as $retina)
                                                <div class='imgrow'><img src="{{ ($retina->retina_img) ? public_path().'/storage/'.$retina->retina_img : '' }}" class='img-fluid mt-1 mb-1' alt=''/><div class='row '><div class='col-sm-10'><input type='text' class='form-control' name='retina_desc[]' value="{{ $retina->description }}" placeholder='Description'><input type='hidden' name='retina_img[]' value="{{ ($retina->retina_img) ? base64_encode(file_get_contents(storage_path('app/public/'.$retina->retina_img))) : '' }}"><input type='hidden' name='retina_type[]' value="{{ $retina->retina_type }}"><input type='hidden' name='lab_test_id[]' value='0'></div><div class='col-sm-2 '><a href='javascript:void(0)'><i class='fa fa-trash text-danger removeImg'></i></a></div></div></div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">                                    
                                    <label class="form-label">OS</label>
                                    <input type="file" class="form-control retina_os" name="retina_os" id="retina_os" data-container="retina_os_container" data-type="os">
                                    <div class="retina_os_container mt-3 mb-3">
                                        @if($retina_os)
                                            @foreach($retina_os as $retina)
                                                <div class='imgrow'><img src="{{ public_path().'/storage/'.$retina->retina_img }}" class='img-fluid mt-1 mb-1' alt=''/><div class='row '><div class='col-sm-10'><input type='text' class='form-control' name='retina_desc[]' value="{{ $retina->description }}" placeholder='Description'><input type='hidden' name='retina_img[]' value="{{ ($retina->retina_img) ? base64_encode(file_get_contents(storage_path('app/public/'.$retina->retina_img))) : ''}}"><input type='hidden' name='retina_type[]' value="{{ $retina->retina_type }}"><input type='hidden' name='lab_test_id[]' value='0'></div><div class='col-sm-2 '><a href='javascript:void(0)'><i class='fa fa-trash text-danger removeImg'></i></a></div></div></div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row g-4 mb-3 mt-3">
                                <div class="col-sm-11">
                                    @php $olds = explode(',', $record->diagnosis); @endphp
                                    <label class="form-label">Diagnosis<sup class="text-danger">*</sup> <small class="text-info">(Multiple selection enabled)</small></label>
                                    <select class="form-control form-control-md show-tick ms select2" multiple data-placeholder="Select" name="diagnosis_id[]" id="diagnosisSelect">
                                    <option value="">Select</option>
                                    @foreach($diagnosis as $dia)
                                        @php $selected = '' @endphp
                                         @foreach($olds as $key => $value)
                                            @if($dia->id == $value)
                                                {{ $selected = 'selected' }}
                                            @endif
                                        @endforeach
                                        <option value="{{ $dia->id }}" {{ $selected }}>{{ $dia->diagnosis_name }}</option>
                                    @endforeach
                                    </select>
                                    @error('diagnosis_id')
                                    <small class="text-danger">{{ $errors->first('diagnosis_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-1">
                                    <a data-bs-toggle="modal" href="#diagnosisModal"><i class="fa fa-plus fa-lg text-success"></i></a>
                                </div> 
                                <div class="col-sm-12">
                                    <label class="form-label">Doctor Recommendations / Advice<sup class="text-danger">*</sup></label>
                                    <textarea class="form-control form-control-md" name="doctor_recommondations" rows="5" placeholder="Doctor Recommondations / Advise" required="required">{{ $record->doctor_recommondations }}</textarea>
                                    @error('doctor_recommondations')
                                    <small class="text-danger">{{ $errors->first('doctor_recommondations') }}</small>
                                    @enderror
                                </div>
                            </div>
                                @if($medicine_record->isEmpty())
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <label class="form-label">Medicine Adviced</label>
                                            <select class="form-control form-control-md select2" data-placeholder="Select" name="medicine_id[]">
                                            <option value="">Select</option>
                                            @foreach($medicines as $med)
                                                <option value="{{ $med->id }}" {{ old('medicine_id') == $dia->id ? 'selected' : '' }}>{{ $med->product_name }}</option>
                                            @endforeach
                                            </select>
                                            @error('medicine_id')
                                            <small class="text-danger">{{ $errors->first('medicine_id') }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="form-label">Dosage</label>
                                            <input type="text" name="dosage[]" class="form-control form-control-md" placeholder="Eg: Daily 3 Drops"/>
                                        </div>
                                        <div class="col-sm-1">
                                            <label class="form-label">Qty / NOs.</label>
                                            <input type='number' class='form-control form-control-md' name='qty[]' placeholder='0' />
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="form-label">Notes.</label>
                                            <input type='text' class='form-control form-control-md' name='notes[]' placeholder='Notes' />
                                        </div>
                                        <div class="col-sm-1">
                                            <a class="medicineAdvise" href="javascript:void(0)"><i class="fa fa-plus fa-lg text-success"></i></a>                                    
                                        </div>
                                    </div>
                                @else
                                    @php $c = 0; @endphp
                                    @foreach($medicine_record as $pmr)
                                        @php $c++; @endphp
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                        @if($c == 1)<label class="form-label">Medicine Advised</label>@endif
                                            <select class="form-control form-control-md select2" data-placeholder="Select" name="medicine_id[]">
                                            <option value="">Select</option>
                                            @foreach($medicines as $med)
                                            <option value="{{ $med->id }}" {{ $pmr->medicine == $med->id ? 'selected' : '' }}>{{ $med->product_name }}</option>
                                            @endforeach
                                            </select>
                                            @error('medicine_id')
                                            <small class="text-danger">{{ $errors->first('medicine_id') }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-sm-2">
                                        @if($c == 1)<label class="form-label">Dosage</label>@endif
                                            <input type="text" name="dosage[]" class="form-control form-control-md" value="{{ $pmr->dosage }}" placeholder="Eg: Daily 3 Drops"/>
                                        </div>
                                        <div class="col-sm-1">
                                        @if($c == 1)<label class="form-label">Qty / NOs.</label>@endif
                                            <input type='number' class='form-control form-control-md' name='qty[]' placeholder='0' value="{{ $pmr->qty }}" />
                                        </div>
                                        <div class="col-sm-2">
                                        @if($c == 1)<label class="form-label">Notes.</label>@endif
                                            <input type='text' class='form-control form-control-md' name='notes[]' value="{{ $pmr->notes }}" placeholder='Notes' />
                                        </div>
                                        @if($c == 1)
                                            <div class="col-sm-1">
                                                <a class="medicineAdvise" href="javascript:void(0)"><i class="fa fa-plus fa-lg text-success"></i></a>                                    
                                            </div>
                                        @else
                                            <div class="col-sm-1"><i class="fa fa-trash text-danger" style="cursor:pointer" onClick="$(this).parent().parent().remove();"></i></div>  
                                        @endif
                                    </div>  
                                    @endforeach
                                @endif
                            <div class="medicineAdviseContainer"></div>
                            <div class="row">
                                <div class="col-sm-2 mt-3">
                                    <label class="form-label">Admission Advised?</label>
                                    <select class="form-control form-control-md" name="is_patient_admission" data-placeholder='Select'>
                                        <option value='sel'>Select</option>
                                        <option value='no' {{ ($record->is_patient_admission == 'no') ? 'selected' : '' }}>No</option>
                                        <option value='yes' {{ ($record->is_patient_admission == 'yes') ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </div>
                                <div class="col-sm-2 mt-3">
                                    <label class="form-label">Surgery Advised?</label>
                                    <select class="form-control form-control-md" name="is_patient_surgery" data-placeholder='Select'>
                                        <option value='sel'>Select</option>
                                        <option value='no' {{ ($record->is_patient_surgery == 'no') ? 'selected' : '' }}>No</option>
                                        <option value='yes' {{ ($record->is_patient_surgery == 'yes') ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </div>
                                <div class="col-sm-3 mt-3">
                                    <label class="form-label">Review Date</label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ ($record->review_date) ? date('d/M/Y', strtotime($record->review_date)) : '' }}" name="review_date" class="form-control form-control-md dtpicker" placeholder="dd/mm/yyyy">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('review_date')
                                    <small class="text-danger">{{ $errors->first('review_date') }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-3">                            
                                <div class="col-sm-12 text-right">
                                    <button type="button" onClick="history.back()"  class="btn btn-danger">Cancel</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="button" class="btn btn-primary btn-consultation">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
<div class="modal fade" id="symptomModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form name="frm-symptom" id="frm-symptom" action="/symptom/create/">
                <input type="hidden" class="ddl" value="symptomSelect" />
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="exampleModalLiveLabel">Add Symptom</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col">
                            <label class="form-label">Symptom Name<sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control form-control-md" name="symptom_name" placeholder="Symptom Name"/>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col message text-success"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ajax-submit btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="diagnosisModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form name="frm-diagnosis" id="frm-diagnosis" action="/symptom/create/">
                <input type="hidden" class="ddl" value="diagnosisSelect" />
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="exampleModalLiveLabel">Add Diagnosis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col">
                            <label class="form-label">Diagnosis Name<sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control form-control-md" name="diagnosis_name" placeholder="Diagnosis Name"/>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col message text-success"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ajax-submit btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade visionModal" id="visionModal" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalCenterTitle" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Description</h5>
                <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <div class="col">
                        <label class="form-label">Description <sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control form-control-md vision_description" name="vision_description" placeholder="Description"/>
                        <input type="hidden" class="vision_canvas" value=""/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
                <button type="button" class="btn btn-primary btnaddpoints">Add</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="visionExtrasModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form name="frm-vision-extras" id="frm-vision-extras" action="/symptom/create/">
                <input type="hidden" class="ddl" />
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="exampleModalLiveLabel">Add Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col">
                            <label class="form-label">Name<sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control form-control-md" name="name" placeholder="Name"/>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col message text-success"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ajax-submit btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection