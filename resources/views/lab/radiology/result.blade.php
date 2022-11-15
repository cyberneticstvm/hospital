@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Update Lab Result - Radiology</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('lab.radiology.result.update', $mrecord->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="medical_record_id" value="{{ $mrecord->id }}" />
                            <input type="hidden" id="age" value="{{ $age }}" />
                            <div class="row g-4">
                                <div class="col-sm-3">Medical Record No: <h5 class="text-primary">{{ $mrecord->id }}</h5></div>
                                <div class="col-sm-3">Patient Name / Age: <h5 class="text-primary">{{ $patient->patient_name }} / {{ $age }}</h5></div>
                                <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ $patient->patient_id }}</h5></div>
                                <div class="col-sm-3">Doctor Name: <h5 class="text-primary">{{ $doctor->doctor_name }}</h5></div>
                            </div>
                            @php $c = 0; @endphp
                            @foreach($lab_records as $lab_record)
                            @php $c++; @endphp
                            <div class="row mt-3">
                                <input type="hidden" name="lab_id[]" value="{{ $lab_record->id }}"/>
                                <div class="col-sm-3">
                                    <label class="form-label">Test<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2 selLabTest" data-placeholder="Select" name="test_id[]" disabled>
                                    <option value="">Select</option>
                                        @foreach($labtests as $test)
                                            <option value="{{ $test->id }}" {{ $lab_record->lab_type_id == $test->id ? 'selected' : '' }}>{{ $test->lab_type_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('test_id')
                                    <small class="text-danger">{{ $errors->first('test_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Test From<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2" data-placeholder="Select" name="tested_from[]" disabled>
                                    <option value="">Select</option>
                                        <option value="1" {{ $lab_record->tested_from == 1 ? 'selected' : '' }}>Own Laboratory</option>
                                        <option value="0" {{ $lab_record->tested_from == 0 ? 'selected' : '' }}>Outside Laboratory</option>
                                    </select>
                                    @error('tested_from')
                                    <small class="text-danger">{{ $errors->first('tested_from') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Result Date<sup class="text-danger">*</sup></label>
                                    <fieldset class="form-icon-group left-icon position-relative">
                                        <input type="text" value="{{ ($lab_record && $lab_record->result_date) ? date('d/M/Y', strtotime($lab_record->result_date)) : '' }}" name="result_date" class="form-control form-control-md dtpicker" placeholder="dd/mm/yyyy">
                                        <div class="form-icon position-absolute">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                                <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </fieldset>
                                    @error('result_date')
                                    <small class="text-danger">{{ $errors->first('result_date') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Upload Result Doc.</label>
                                    <input class="form-control" type="file" name="docs[]" />
                                    <small>Uploaded Doc: <a href="{{ public_path().'/storage/'.$lab_record->doc_path }}" target="_blank">{{ ($lab_record->doc_path) ? 'View' : '' }}</a></small>
                                </div>                              
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12">
                                    <label class="form-label">Test Result<sup class="text-danger">*</sup></label>
                                    <textarea class="form-control" name="lab_result[]" rows="5">{{ $lab_record->lab_result }}</textarea>
                                </div>
                            </div>
                            <div class="row g-4 mt-1">
                                <label class="form-label">Image Upload</label>
                                <div class="col-sm-6">                                    
                                    <label class="form-label">OD</label>
                                    <input type="file" class="form-control retina_od" name="retina_od" id="retina_od" data-container="retina_od_container_{{ $lab_record->id }}" data-type="od" data-labid="{{ $lab_record->id }}">
                                    <div class="retina_od_container_{{ $lab_record->id }} mt-3 mb-3">
                                        @if($retina_od)
                                            @foreach($retina_od as $retina)
                                                @if($lab_record->id == $retina->lab_test_id)
                                                <div class='imgrow'><img src="{{ public_path().'/storage/'.$retina->img }}" class='img-fluid mt-1 mb-1' alt=''/><div class='row '><div class='col-sm-10'><input type='text' class='form-control' name='retina_desc[]' value="{{ $retina->description }}" placeholder='Description'><input type='hidden' name='retina_img[]' value="{{ ($retina->img) ? base64_encode(file_get_contents(storage_path('app/public/'.$retina->img))) : '' }}"><input type='hidden' name='retina_type[]' value="{{ $retina->type }}"><input type='hidden' name='lab_test_id[]' value="{{ $lab_record->id }}"></div><div class='col-sm-2 '><a href='javascript:void(0)'><i class='fa fa-trash text-danger removeImg'></i></a></div></div></div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">                                    
                                    <label class="form-label">OS</label>
                                    <input type="file" class="form-control retina_os" name="retina_os" id="retina_os" data-container="retina_os_container_{{ $lab_record->id }}" data-type="os" data-labid="{{ $lab_record->id }}">
                                    <div class="retina_os_container_{{ $lab_record->id }} mt-3 mb-3">
                                        @if($retina_os)
                                            @foreach($retina_os as $retina)
                                                @if($lab_record->id == $retina->lab_test_id)
                                                <div class='imgrow'><img src="{{ public_path().'/storage/'.$retina->img }}" class='img-fluid mt-1 mb-1' alt=''/><div class='row '><div class='col-sm-10'><input type='text' class='form-control' name='retina_desc[]' value="{{ $retina->description }}" placeholder='Description'><input type='hidden' name='retina_img[]' value="{{ ($retina->img) ? base64_encode(file_get_contents(storage_path('app/public/'.$retina->img))) : '' }}"><input type='hidden' name='retina_type[]' value="{{ $retina->type }}"><input type='hidden' name='lab_test_id[]' value="{{ $lab_record->id }}"></div><div class='col-sm-2 '><a href='javascript:void(0)'><i class='fa fa-trash text-danger removeImg'></i></a></div></div></div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
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