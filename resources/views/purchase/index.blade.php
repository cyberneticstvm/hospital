@extends("templates.base")
@section("content")
<div class="d-flex flex-wrap justify-content-between align-items-end">
    <div class="mb-3">
        <h5 class="mb-0">Purchase Register</h5>
        <span class="text-muted"></span>
    </div>
</div>
<div class="card mb-4 border-0">
    <div class="card-body">
        <p class= "text-right my-3"><a href="/purchase/create/"><i class="fa fa-plus fa-lg text-success"></i></a></p>
        <table id="dataTbl" class="table display dataTable table-striped table-sm table-hover align-middle" style="width:100%">
            <thead><tr><th>SL No.</th><th>Supplier</th><th>Invoice Number</th><th>Order On</th><th>Delivery On</th><th>Bill</th><th>Edit</th><!--<th>Remove</th>--></tr></thead><tbody>
            @php $i = 0; @endphp
            @foreach($purchases as $purchase)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $purchase->name }}</td>                    
                    <td>{{ $purchase->invoice_number }}</td>
                    <td>{{ ($purchase->order_date) ? date('d/M/Y', strtotime($purchase->order_date)) : '' }}</td>
                    <td>{{ ($purchase->delivery_date) ? date('d/M/Y', strtotime($purchase->delivery_date)) : '' }}</td>
                    <td><a class='btn btn-link' href="/purchase/bill/{{ $purchase->id }}/" target="_blank"><i class="fa fa-file-o text-info"></i></a></td>
                    <td><a class='btn btn-link' href="{{ route('purchase.edit', $purchase->id) }}"><i class="fa fa-pencil text-warning"></i></a></td>
                    <!--<td>
                        <form method="post" action="{{ route('purchase.delete', $purchase->id) }}">
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