@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container-fluid">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Search Spectacle Record</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('spectacle.fetch') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <label class="form-label">MR.ID / Patient Name / Patient ID / Patient Mobile No.<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $search_term }}" name="search_term" class="form-control form-control-md" placeholder="MR.ID / Patient Name / Patient ID / Patient Mobile No.">
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
                        </form>
                        <div class="mt-5"></div>
                        <table id="dataTbl" class="table display table-sm dataTable table-striped table-hover align-middle" style="width:100%">
                            <thead><tr><th>SL No.</th><th>MR.ID</th><th>Patient Name</th><th>Patient ID</th><th>Optometrist</th><th>Date</th><th>Prescription</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
                            @php $i = 0; @endphp
                            @foreach($spectacles as $spectacle)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $spectacle->medical_record_id }}</td>
                                    <td>{{ $spectacle->patient_name }}</td>
                                    <td>{{ $spectacle->patient_id }}</td>
                                    <td>{{ $spectacle->optometrist }}</td>
                                    <td>{{ $spectacle->pdate }}</td>
                                    <td><a href="/generate-spectacle-prescription/{{ $spectacle->id }}/" target="_blank"><i class="fa fa-file-o text-info"></i></a></td>
                                    <td><a class='btn btn-link' href="{{ route('spectacle.edit', $spectacle->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                    <td>
                                        <form method="post" action="{{ route('spectacle.delete', $spectacle->id) }}">
                                            @csrf 
                                            @method("DELETE")
                                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Record?');"><i class="fa fa-trash text-danger"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody></table>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->
    </div>
</div>
@endsection