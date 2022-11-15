@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Camp Master</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body table-responsive">
                        @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                        @endif
                        <p class= "text-right my-3"><a href="/campmaster/create/"><i class="fa fa-plus fa-lg text-success"></i></a></p>
                        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                        <thead><tr><th>SL No.</th><th>Camp ID</th><th>Venue</th><th>Address</th><th>From Date</th><th>To Date</th><th>Branch</th><th>Camp Type</th><th>Print</th><th>Edit</th><!--<th>Remove</th>--></tr></thead><tbody>
                        @php $i = 0; @endphp
                        @foreach($camps as $key => $camp)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td><a href="/camp/create/{{ $camp->id }}/">{{ $camp->camp_id }}</a></td>
                                <td>{{ $camp->venue }}</td>
                                <td>{{ $camp->address }}</td>
                                <td>{{ $camp->fdate }}</td>
                                <td>{{ $camp->tdate }}</td>
                                <td>{{ $camp->branch_name }}</td>
                                <td>{{ $camp->type_name }}</td>
                                <td class="text-center"><a href="/campmaster/print/{{ $camp->id }}/" target="_blank"><i class="fa fa-file-o text-info"></i></a></td>
                                <td><a class='btn btn-link' href="{{ route('campmaster.edit', $camp->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                <!--<td>
                                    <form method="post" action="{{ route('campmaster.delete', $camp->id) }}">
                                        @csrf 
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this record?');"><i class="fa fa-trash text-danger"></i></button>
                                    </form>
                                </td>-->
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