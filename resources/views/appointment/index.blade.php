@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Fetch Patient</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('appointment.show') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-3">
                                    <label class="form-label">Patient ID<sup class="text-danger">*</sup></label>
                                    <input type="number" value="{{ old('patient_id') }}" name="patient_id" class="form-control form-control-md" placeholder="Patient ID">
                                    @error('patient_id')
                                    <small class="text-danger">{{ $errors->first('patient_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-submit w-100">Fetch</button>
                                </div>
                            </div>
                            @if(count($errors) > 0)
                            <div role="alert" class="text-danger mt-3">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
                <h5 class="mb-3 mt-3">Appointment Register</h5>
                <div class="card">                    
                    <div class="card-body table-responsive">
                        <p class= "text-right my-3"><a href="/appointment/create/"><i class="fa fa-plus fa-lg text-success"></i></a></p>
                        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                        <thead><tr><th>SL No.</th><th>Patient Name</th><th>Age</th><th>Contact Number</th><th>Address</th><th>Date</th><th>Edit</th><!--<th>Remove</th>--></tr></thead><tbody>
                        @php $i = 0; @endphp
                        @forelse($appointments as $key => $ap)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td><a href="/appointment/patient/create/{{ $ap->id }}/">{{ $ap->patient_name }}</a></td>
                                <td>{{ $ap->age }}</td>
                                <td>{{ $ap->mobile_number }}</td>
                                <td>{{ $ap->address }}</td>
                                <td>{{ $ap->adate }}</td>
                                <td><a class='btn btn-link' href="{{ route('appointment.edit', $ap->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                <!--<td>
                                    <form method="post" action="{{ route('appointment.delete', $ap->id) }}">
                                        @csrf 
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this record?');"><i class="fa fa-trash text-danger"></i></button>
                                    </form>
                                </td>-->
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