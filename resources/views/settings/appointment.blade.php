@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Appointment Settings</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('settings.appointment.update') }}" method="post">
                            @csrf
                            @method("PUT")
                            <div class="row g-4">
                                <div class="col-md-12">
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
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col-sm-2">
                                    <label class="form-label">From Time <sup class="text-danger">*</sup> <small></small></label>
                                    <select class="form-control select2" name="appointment_from_time">
                                        <option value="">Select</option>
                                        @php $from = $start @endphp
                                        @while($from <= $end)                                            
                                            <option value="{{ date('h:i A', $from) }}" {{ ($settings->appointment_from_time == date('h:i A', $from)) ? 'selected' : '' }}>{{ date('h:i A', $from) }}</option>
                                            @php $from = strtotime('+30 minutes', $from); @endphp
                                        @endwhile
                                    </select>
                                    @error('appointment_from_time')
                                    <small class="text-danger">{{ $errors->first('appointment_from_time') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">To Time<sup class="text-danger">*</sup> <small></small></label>
                                    <select class="form-control select2" name="appointment_to_time">
                                        <option value="">Select</option>
                                        @php $to = $start @endphp
                                        @while($to <= $end)                                            
                                            <option value="{{ date('h:i A', $to) }}" {{ ($settings->appointment_to_time == date('h:i A', $to)) ? 'selected' : '' }}>{{ date('h:i A', $to) }}</option>
                                            @php $to = strtotime('+30 minutes', $to); @endphp
                                        @endwhile
                                    </select>
                                    @error('appointment_to_time')
                                    <small class="text-danger">{{ $errors->first('appointment_to_time') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Interval<sup class="text-danger">*</sup> <small></small></label>
                                    <select class="form-control select2" name="appointment_interval">
                                        <option value="">Select</option>
                                        <option value="10" {{ ($settings->appointment_interval == 10) ? 'selected' : '' }}>10 Minutes</option>
                                        <option value="15" {{ ($settings->appointment_interval == 15) ? 'selected' : '' }}>15 Minutes</option>
                                        <option value="20" {{ ($settings->appointment_interval == 20) ? 'selected' : '' }}>20 Minutes</option>
                                        <option value="25" {{ ($settings->appointment_interval == 25) ? 'selected' : '' }}>25 Minutes</option>
                                        <option value="30" {{ ($settings->appointment_interval == 30) ? 'selected' : '' }}>30 Minutes</option>
                                    </select>
                                    @error('appointment_interval')
                                    <small class="text-danger">{{ $errors->first('appointment_interval') }}</small>
                                    @enderror
                                </div>                                
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