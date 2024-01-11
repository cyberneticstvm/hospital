@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Assign Optometrist to Doctor</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                        @endif
                        @if(session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session()->get('error') }}
                        </div>
                        @endif
                        <form action="{{ route('doc.opto.assign') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-4">
                                    <label class="form-label">Doctor<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2" data-placeholder="Select" name="doctor_id">
                                        <option value="">Select</option>
                                        @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>{{ $doctor->doctor_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('doctor_id')
                                    <small class="text-danger">{{ $errors->first('doctor_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Optometrist<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md show-tick ms select2" data-placeholder="Select" name="optometrist_id">
                                        <option value="">Select</option>
                                        @foreach($optos as $opto)
                                        <option value="{{ $opto->id }}" {{ old('optometrist_id') == $opto->id ? 'selected' : '' }}>{{ $opto->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('optometrist_id')
                                    <small class="text-danger">{{ $errors->first('optometrist_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-12 text-right">
                                    <button type="button" onClick="history.back()" class="btn btn-danger">Cancel</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-primary btn-submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SL No.</th>
                                    <th>Doctor</th>
                                    <th>Optometrist</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $record)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $record->doctor->doctor_name }}</td>
                                    <td>{{ $record->optometrist->name }}</td>
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
@endsection