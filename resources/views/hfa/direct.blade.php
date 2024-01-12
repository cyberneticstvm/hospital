@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h5 class="mb-3 mt-3">HFA Direct Patient Register</h5>
                <div class="card">
                    <div class="card-body table-responsive">
                        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SL No.</th>
                                    <th>MR.ID</th>
                                    <th>Patient Name</th>
                                    <th>Patient ID</th>
                                    <th>Receipt</th>
                                    <th>Created By</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Edit</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 0; @endphp
                                @foreach($hfas as $hfa)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $hfa->medical_record_id }}</td>
                                    <td>{{ $hfa->patient_name }}</td>
                                    <td>{{ $hfa->patient_id }}</td>
                                    <td><a href="/hfa/receipt/{{ $hfa->id }}/" target="_blank"><i class="fa fa-file-o text-danger"></i></a></td>
                                    <td>{{ $hfa->user->name }}</td>
                                    <td>{{ $hfa->created_at->format('d/M/Y') }}</td>
                                    <td>{{ $hfa->created_at->format('h:i a') }}</td>
                                    <td><a class='btn btn-link' href="{{ route('hfa.edit', $hfa->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                    <td>
                                        <form method="post" action="{{ route('hfa.delete', $hfa->id) }}">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this record?');"><i class="fa fa-trash text-danger"></i></button>
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
@endsection