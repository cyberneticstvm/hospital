@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Edit Ceritificate consultation</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('certificate.update', $certificate->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <input type='hidden' name='medical_record_id' value="{{ $certificate->medical_record_id }}" />
                            <input type='hidden' name='patient_certificate_id' value="{{ $certificate->id }}" />
                            <input type='hidden' name='mrn' value="{{ $certificate->mrn }}" />
                            <div class="row g-4">
                                <div class="col-sm-4">Patient Name: <h5 class="text-primary">{{ $certificate->patient_name }}</h5></div>
                                <div class="col-sm-4">Patient ID: <h5 class="text-primary">{{ $certificate->patient_id }}</h5></div>
                                <div class="col-sm-4">Doctor Name: <h5 class="text-primary">{{ $certificate->doctor_name }}</h5></div>
                                <div class="col-sm-12 table-responsive">
                                    <table class="table table-bordered table-sm"><thead><tr><th>Certificate Name</th><th>Fee</th><th>Status</th><th>Notes</th></tr></thead><tbody>
                                    @if(count($details) > 0)
                                        @forelse($details as $key => $data)
                                            <tr>
                                            <td>
                                                {{ DB::table('certificate_types')->where('id', $data->certificate_type)->value('name') }}
                                                <input type="hidden" name="certificate_type[]" value="{{ $data->certificate_type }}" />
                                            </td>
                                            <td>
                                                {{ DB::table('certificate_types')->where('id', $data->certificate_type)->value('fee') }}
                                                <input type="hidden" name="fee[]" value="{{ $data->fee }}" />
                                            </td>
                                            <td>
                                                <select class="form-control form-control-sm" name="status[]">
                                                    <option value="N" {{ ($data->status == 'N') ? 'selected' : '' }}>Not Issued / Not Required</option>
                                                    <option value="I" {{ ($data->status == 'I') ? 'selected' : '' }}>Issued</option>
                                                    <option value="R" {{ ($data->status == 'R') ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm" name="notes[]" value=" {{ $data->notes }}"></td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    @else
                                        @forelse($ctypes as $key => $ctype)
                                            <tr>
                                            <td>
                                                {{ $ctype->name }}
                                                <input type="hidden" name="certificate_type[]" value="{{ $ctype->id }}" />
                                            </td>
                                            <td>
                                                {{ $ctype->fee }}
                                                <input type="hidden" name="fee[]" value="{{ $ctype->fee }}" />
                                            </td>
                                            <td>
                                                <select class="form-control form-control-sm" name="status[]" required="required">
                                                    <option value="">Select</option>
                                                    <option value="N">Not Issued / Not Required</option>
                                                    <option value="I">Issued</option>
                                                    <option value="R">Rejected</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm" name="notes[]"></td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    @endif
                                    </tbody></table>
                                </div>
                                <div class="col-md-12"><a href="/spectacle/fetch/">Spectacle Entry</a></div>
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