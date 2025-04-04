@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Patient Medical Record Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <div class="row mb-3">
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
            <div class="col-md-4">
                <h5 class="text-primary">Total Consultation: {{ $ccount+$ccount1 }}</h5>
            </div>
            <div class="col-md-4 text-center">
                <h5 class="text-warning">Consultation Completed: {{ $ccount1 }}</h5>
            </div>
            <div class="col-md-4 text-end">
                <h5 class="text-danger">Consultation Pending: {{ $ccount }}</h5>
            </div>
        </div>
        <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>SL No.</th>
                    <th>MR.ID</th>
                    <th>Patient Name</th>
                    <th>Patient ID</th>
                    <th>Age</th>
                    <th>Doctor</th>
                    <th>Reg.Date</th>
                    <th>Diagnosis</t>
                    <th>Medical Record</th>
                    <th>Medicine</th>
                    <th>Review Date</th>
                    <th>Status</th>
                    <th>Edit</th><!--<th>Remove</th>-->
                </tr>
            </thead>
            <tbody>
                @php $i = 0; @endphp
                @foreach($medical_records as $record)
                @php
                $diagnosis = explode(',', $record->diagnosis);
                @endphp
                <tr class="{{ ($record->status == 0) ? 'text-decoration-line-through' : '' }}">
                    <td>{{ ++$i }}</td>
                    <td>{{ $record->id }}</td>
                    <td>{{ $record->patient_name }}</td>
                    <td>{{ $record->patient_id }}</td>
                    <td>{{ $record->age }}</td>
                    <td>{{ $record->doctor_name }}</td>
                    <td>{{ $record->rdate }}</td>
                    <td>{{ DB::table('diagnosis')->select(DB::raw("IFNULL(group_concat(diagnosis_name), 'Na') as names"))->whereIn('id', $diagnosis)->value('names'); }}</td>
                    <td class="text-center"><a href="/generate-medical-record/{{ $record->id }}/" target="_blank"><i class="fa fa-file-o text-primary"></i></a></td>
                    @if(App\Models\PatientMedicineRecord::where('medical_record_id', $record->id)->exists())
                    <td>
                        <a href="{{ route('medicine.add.update', encrypt($record->id)) }}">Edit</a>
                    </td>
                    @else
                    <td><a href="{{ route('medicine.create', encrypt($record->id)) }}">Medicine</a></td>
                    @endif
                    <td>{{ $record->review_date }}</td>
                    <td><i class="{{ ($record->cstatus == 'no') ? 'fa fa-times text-danger' : 'fa fa-check text-primary' }}"></i></td>
                    @if($record->status == 1)
                    <td><a class='btn btn-link' href="{{ route('medical-records.edit', $record->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    @else
                    <td></td>
                    @endif
                    <!--<td>
                        <form method="post" action="{{ route('medical-records.delete', $record->id) }}">
                            @csrf 
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Record?');"><i class="fa fa-trash text-danger"></i></button>
                        </form>
                    </td>-->
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection