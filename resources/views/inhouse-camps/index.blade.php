@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Inhouse Camp Register</h5>
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
                        <p class= "text-right my-3"><a href="/inhousecamp/create/"><i class="fa fa-plus fa-lg text-success"></i></a></p>
                        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
                        <thead><tr><th>SL No.</th><th>Camp Name</th><th>From Date</th><th>To Date</th><th>Procedures</th><th>Edit</th><th>Remove</th></tr></thead><tbody>
                        @php $i = 0; @endphp
                        @foreach($camps as $key => $camp)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $camp->name }}</td>
                                <td>{{ date('d/M/Y', strtotime($camp->from_date)) }}</td>
                                <td>{{ date('d/M/Y', strtotime($camp->to_date)) }}</td>
                                <td>{{ $procedures->whereIn('id', $camp->procedures->pluck('procedure'))->pluck('name')->implode(',') }}</td>
                                <td><a class='btn btn-link' href="{{ route('inhousecamp.edit', $camp->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                                <td>
                                    <form method="post" action="{{ route('inhousecamp.delete', $camp->id) }}">
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