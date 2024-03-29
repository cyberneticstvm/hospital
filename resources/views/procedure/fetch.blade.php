@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Fetch Medical Record</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('procedure.show') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">Medical Record ID (MR.ID)<sup class="text-danger">*</sup></label>
                                    <input type="number" value="{{ old('medical_record_number') }}" name="medical_record_number" class="form-control form-control-md" placeholder="Mediical Record Number" required="required">
                                    @error('medical_record_number')
                                    <small class="text-danger">{{ $errors->first('medical_record_number') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-submit w-100">Fetch</button>
                                </div>
                            </div>
                            @if (count($errors) > 0)
                            <div role="alert" class="text-danger mt-3">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
                <h5 class="mb-3 mt-3">Procedure Advised List</h5>
                <div class="card">
                    <div class="card-body table-responsive">
                        <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
                        <thead><tr><th>SL No.</th><th>MR. ID</th><th>Patient Name</th><th>Patient ID</th><th></th><th>Fee</th><th>Receipt</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
                        @php $i = 0; @endphp
                        @forelse($procs as $key => $procedure)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $procedure->medical_record_id }}</td>
                                <td>{{ $procedure->patient_name }}</td>
                                <td>{{ $procedure->patient_id }}</td>
                                <td>{{ $procedure->procs }}</td>
                                <td class="text-right">{{ $procedure->fee }}</td>
                                <td></td>
                                <td><a class='btn btn-link' href="{{ route('procedure.editadvise', $procedure->medical_record_id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                <td>
                                    <form method="post" action="{{ route('procedure.destroyadvise', $procedure->medical_record_id) }}">
                                        @csrf 
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Procedure?');"><i class="fa fa-trash text-danger"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody></table>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection