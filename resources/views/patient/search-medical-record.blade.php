@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Search Medical Record</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('patient-medical-record.fetch') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-2">
                                    <label class="form-label">Patient ID<sup class="text-danger">*</sup></label>
                                    <input type="number" value="{{ $search_term }}" name="search_term" class="form-control form-control-md" placeholder="Patient ID">
                                    @error('search_term')
                                    <small class="text-danger">{{ $errors->first('search_term') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-submit w-100">Search</button>
                                </div>
                            </div>
                            @if (count($errors) > 0)
                            <div role="alert" class="text-danger mt-3">
                                @foreach ($errors->all() as $error)
                                {{ $error }}
                                @endforeach
                            </div>
                            @endif
                            @if (session('success'))
                            <div class="alert alert-success" style="margin-top: 0.2rem;">
                                {{ session('success') }}
                            </div>
                            @endif
                            @if (session('error'))
                            <div class="alert alert-danger" style="margin-top: 0.2rem;">
                                {{ session('error') }}
                            </div>
                            @endif
                        </form>
                        <div class="mt-5"></div>
                        <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SL No.</th>
                                    <th>MR.ID</th>
                                    <th>Patient Name</th>
                                    <th>Patient ID</th>
                                    <th>Mobile Number</th>
                                    <th>Doctor</th>
                                    <th>Reg.Date</th>
                                    <th>Medical Record</th>
                                    <th>Review Date</th>
                                    <th>WA</th>
                                    <th>E-mail</th>
                                    <th>Edit</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach($records as $record)
                                <tr class="{{ ($record->status == 0) ? 'text-decoration-line-through' : '' }} {{ ($i == 1) ? 'text-highlight' : '' }}">
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $record->id }}</td>
                                    <td>{{ $record->patient_name }}</td>
                                    <td>{{ $record->patient_id }}</td>
                                    <td>{{ $record->mobile_number }}</td>
                                    <td>{{ $record->doctor_name }}</td>
                                    <td>{{ $record->rdate }}</td>
                                    <td class="text-center"><a href="/generate-medical-record/{{ $record->id }}/" target="_blank"><i class="fa fa-file-o text-primary"></i></a></td>
                                    <td>{{ $record->review_date }}</td>
                                    <td class="text-center"><a href="javascript:void(0)" data-mobile="{{ $record->mobile_number }}" class="sendDocs" data-pname="{{ $record->patient_name }}" data-mrid="{{ $record->id }}" data-type="wa" data-bs-toggle="modal" data-modal="waModal" data-bs-target="#waModal" data-title="Send Docs via WA"><i class="fa fa-whatsapp text-success fa-lg"></i></a></td>
                                    <td class="text-center"><a href="javascript:void(0)" data-email="{{ $record->email }}" class="sendDocs" data-pname="{{ $record->patient_name }}" data-mrid="{{ $record->id }}" data-type="email" data-bs-toggle="modal" data-modal="emailModal" data-bs-target="#emailModal" data-title="Send Docs via Email"><i class="fa fa-envelope text-success fa-lg"></i></a></td>
                                    @if($record->status == 1)
                                    <td><a class='btn btn-link' href="{{ route('medical-records.edit', $record->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                    @else
                                    <td></td>
                                    @endif
                                    <td>
                                        <form method="post" action="{{ route('medical-records.delete', $record->id) }}">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Record?');"><i class="fa fa-trash text-danger"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
<div class="modal fade" id="waModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-vertical modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">Send Docs via WhatsApp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">
                <form action="{{ route('send.docs.wa') }}" method="post">
                    @csrf
                    <input type="hidden" class="mrid" name="mrid" value="" />
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label">Patient Name<sup class="text-danger">*</sup></label>
                            <input type="text" value="" name="patient_name" class="form-control form-control-md pName" placeholder="Patient Name">
                            @error('patient_name')
                            <small class="text-danger">{{ $errors->first('patient_name') }}</small>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">WhatsApp Number<sup class="text-danger">*</sup></label>
                            <input type="text" value="" name="mobile_number" maxlength="10" class="form-control form-control-md dType" placeholder="Mobile Number" required>
                            @error('mobile_number')
                            <small class="text-danger">{{ $errors->first('mobile_number') }}</small>
                            @enderror
                        </div>
                    </div>
                    <h5 class="mt-3 mb-3 text-primary">Documents</h5>
                    <div class="row">
                        <div class="col-sm-4 form-check form-check-inline">
                            <label class="form-check-label" for="flexCheckDefault">Medical Record</label>{{ Form::checkbox('medical_record', 1, false, array('class' => 'name, form-check-input')) }}
                        </div>
                        <div class="col-sm-4 form-check form-check-inline">
                            <label class="form-check-label" for="flexCheckDefault">Patient History</label>{{ Form::checkbox('patient_history', 1, false, array('class' => 'name, form-check-input')) }}
                        </div>
                        <div class="col-sm-4 form-check form-check-inline">
                            <label class="form-check-label" for="flexCheckDefault">Spectacle Prescription</label>{{ Form::checkbox('spectacle_prescription', 1, false, array('class' => 'name, form-check-input')) }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-primary btn-submit">Send</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="emailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-vertical modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">Send Docs via Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom_scroll">
                <form action="{{ route('send.docs.email') }}" method="post">
                    @csrf
                    <input type="hidden" class="mrid" name="mrid" value="" />
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label">Patient Name<sup class="text-danger">*</sup></label>
                            <input type="text" value="" name="patient_name" class="form-control form-control-md pName" placeholder="Patient Name">
                            @error('patient_name')
                            <small class="text-danger">{{ $errors->first('patient_name') }}</small>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Email<sup class="text-danger">*</sup></label>
                            <input type="email" value="" name="email" class="form-control form-control-md dType" placeholder="Email" required>
                            @error('email')
                            <small class="text-danger">{{ $errors->first('email') }}</small>
                            @enderror
                        </div>
                    </div>
                    <h5 class="mt-3 mb-3 text-primary">Documents</h5>
                    <div class="row">
                        <div class="col-sm-4 form-check form-check-inline">
                            <label class="form-check-label" for="flexCheckDefault">Medical Record</label>{{ Form::checkbox('medical_record', 1, false, array('class' => 'name, form-check-input')) }}
                        </div>
                        <div class="col-sm-4 form-check form-check-inline">
                            <label class="form-check-label" for="flexCheckDefault">Patient History</label>{{ Form::checkbox('patient_history', 1, false, array('class' => 'name, form-check-input')) }}
                        </div>
                        <div class="col-sm-4 form-check form-check-inline">
                            <label class="form-check-label" for="flexCheckDefault">Spectacle Prescription</label>{{ Form::checkbox('spectacle_prescription', 1, false, array('class' => 'name, form-check-input')) }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-primary btn-submit">Send</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection