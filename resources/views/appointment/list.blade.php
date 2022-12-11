@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h5 class="mb-3 mt-3">Appointments Active List</h5>
                <div class="card">                    
                    <div class="card-body table-responsive">
                        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                        <thead><tr><th>SL No.</th><th>Patient Name</th><th>Age</th><th>Contact Number</th><th>Address</th><th>Date</th><th>Time</th><th>Branch</th><th>Notes</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
                        @php $i = 0; @endphp
                        @forelse($appointments as $key => $ap)
                            <tr>
                                <td>{{ ++$i }}</td>
                                @if($ap->patient_id > 0):
                                <td><a href="/consultation/reopen/{{ $ap->patient_id }}/{{ $ap->id }}/">{{ $ap->patient_name }}</a></td>
                                @else
                                <td><a href="/appointment/patient/create/{{ $ap->id }}/">{{ $ap->patient_name }}</a></td>
                                @endif
                                <td>{{ $ap->age }}</td>
                                <td>{{ $ap->mobile_number }}</td>
                                <td>{{ $ap->address }}</td>
                                <td>{{ $ap->adate }}</td>
                                <td>{{ $ap->atime }}</td>
                                <td>{{ $ap->branch_name }}</td>
                                <td>{{ $ap->notes }}</td>
                                <td><a class='btn btn-link' href="{{ route('appointment.edit', $ap->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                <td>
                                    <form method="post" action="{{ route('appointment.delete', $ap->id) }}">
                                        @csrf 
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this record?');"><i class="fa fa-trash text-danger"></i></button>
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