@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Income Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <p class= "text-right my-3"><a href="/income/create/"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        <table id="dataTbl" class="table table-striped table-hover align-middle table-sm" style="width:100%">
            <thead><tr><th>SL No.</th><th>Branch Name</th><th>Description</th><th>Amount</th><th>Head</th><th>Date</th><th>Edit</th><!--<th>Remove</th>--></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($incomes as $income)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $income->branch_name }}</td>
                    <td>{{ $income->description }}</td>
                    <td>{{ $income->amount }}</td>
                    <td>{{ $income->head }}</td>
                    <td>{{ $income->edate }}</td>
                    <td><a class='btn btn-link' href="{{ route('income.edit', $income->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <!--<td>
                        <form method="post" action="{{ route('income.delete', $income->id) }}">
                            @csrf 
                            @method("DELETE")
                            <button type="submit" class="btn btn-link" onclick="javascript: return confirm('Are you sure want to delete this Record?');"><i class="fa fa-trash text-danger"></i></button>
                        </form>
                    </td>-->
                </tr>
            @endforeach
        </tbody></table>
    </div>
</div>
@endsection