@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Camp Register</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body table-responsive">
                        <p class= "text-right my-3"><a href="/camp/create/"><i class="fa fa-plus fa-lg text-success"></i></a></p>
                        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                        <thead><tr><th>SL No.</th><th>Patient Name</th><th>Age</th><th>Std.</th><th>Date</th><th>Branch</th><th>Print</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
                        @php $i = 0; @endphp
                        @foreach($camps as $key => $camp)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $camp->patient_name }}</td>
                                <td>{{ $camp->age }}</td>
                                <td>{{ $camp->standard }}</td>
                                <td>{{ date('d/M/Y', strtotime($camp->camp_date)) }}</td>
                                <td>{{ $camp->branch_name }}</td>
                                <td class="text-center"><a href="/camp/print/{{ $camp->id }}/" target="_blank"><i class="fa fa-file-o text-info"></i></a></td>
                                <td><a class='btn btn-link' href="{{ route('camp.edit', $camp->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                <td>
                                    <form method="post" action="{{ route('camp.delete', $camp->id) }}">
                                        @csrf 
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this record?');"><i class="fa fa-trash text-danger"></i></button>
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