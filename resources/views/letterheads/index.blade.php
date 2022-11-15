@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Letterhead Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <p class= "text-right my-3"><a href="/letterhead/create/"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
            <thead><tr><th>SL No.</th><th>Branch Name</th><th>From</th><th>To</th><th>Subject</th><th>Date</th><th>Print</th><th>Edit</th><!--<th>Remove</th>--></tr></thead><tbody>
            @php $i = 0; @endphp
            @forelse($matters as $key => $matter)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $matter->branch_name }}</td>
                    <td>{{ $matter->from }}</td>
                    <td>{{ $matter->to }}</td>
                    <td>{{ $matter->subject }}</td>
                    <td>{{ $matter->rdate }}</td>
                    <td><a href="/printletterhead/{{ $matter->id }}/" target="_blank"><i class="fa fa-file-o text-info"></i></a></td>
                    <td><a class='btn btn-link' href="{{ route('letterhead.edit', $matter->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <!--<td>
                        <form method="post" action="{{ route('letterhead.delete', $matter->id) }}">
                            @csrf 
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Record?');"><i class="fa fa-trash text-danger"></i></button>
                        </form>
                    </td>-->
                </tr>
            @empty
            @endforelse
        </tbody></table>
    </div>
</div>
@endsection