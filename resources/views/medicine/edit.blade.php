@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Edit Patient Medicine Record</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('medicine.update', $medical_record->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="mid" value="{{ $medical_record->id }}"/>
                            <input type="hidden" name="mrn" value="{{ $medical_record->mrn }}"/>
                            <div class="row g-4">
                                <div class="col-sm-3">MRN: <h5 class="text-primary">{{ $medical_record->mrn }}</h5></div>
                                <div class="col-sm-3">Patient Name: <h5 class="text-primary">{{ $patient->patient_name }}</h5></div>
                                <div class="col-sm-3">Patient ID: <h5 class="text-primary">{{ $patient->patient_id }}</h5></div>
                                <div class="col-sm-3">Doctor Name: <h5 class="text-primary">{{ $doctor->doctor_name }}</h5></div>
                                <div class="col-sm-12">
                                    <!--<p class= "text-right my-3"><a href="javascript:void(0)"><i class="fa fa-plus fa-lg text-success medicineRow"></i></a></p>-->
                                    <table class="tblMedicine table table-bordered">
                                        <thead><tr><th width='30%'>Product</th><th>Batch No.</th><th>Dosage</th><th>Qty</th><th>Price</th><th>Total</th><th>Remove</th></tr></thead>
                                        <tbody>
                                            @foreach($medicines as $medicine)
                                            <input type='hidden' name='dosage1[]' value="{{ $medicine->dosage1 }}"/>
                                            <input type='hidden' name='notes[]' value="{{ $medicine->notes }}"/>
                                            <tr>
                                                <td>
                                                <select class="form-control form-control-md select2" data-placeholder="Select" name="medicine[]" required='required'>
                                                <option value="">Select</option>
                                                @foreach($products as $prod)
                                                    <option value="{{ $prod->id }}" {{ $medicine->medicine == $prod->id ? 'selected' : '' }}>{{ $prod->product_name }}</option>
                                                @endforeach
                                                </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-md" placeholder="Batch Number" name="batch_number[]" value="{{ $medicine->batch_number }}" />
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-md" placeholder="Dosage" name="dosage[]" value="{{ $medicine->dosage }}" />
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-md text-right" placeholder="0" name="qty[]" value="{{ $medicine->qty }}" required='required' />
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-md text-right" placeholder="0" name="price[]" value="{{ $medicine->price }}" required='required' />
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-md text-right" placeholder="0" name="total[]" value="{{ $medicine->total }}" required='required' />
                                                </td>
                                                <td>
                                                    <button class="btn btn-link dlt" data-url="/consultation/medicinesingle/delete/{{ $medicine->id }}/" onclick="javascript: return confirm('Are you sure want to delete this Medicine?');"><i class="fa fa-trash text-danger"></i></button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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