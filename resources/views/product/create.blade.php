@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Add New Product</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('product.create') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-4">
                                    <label class="form-label">Product Name<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ old('product_name') }}" name="product_name" class="form-control form-control-md" placeholder="Product Name">
                                    @error('product_name')
                                    <small class="text-danger">{{ $errors->first('product_name') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Product Category<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md" data-placeholder="Select" name="category_id">
                                    <option value="">Select</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                    @endforeach
                                    </select>
                                    @error('category_id')
                                    <small class="text-danger">{{ $errors->first('category_id') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Type<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md" data-placeholder="Select" name="medicine_type">
                                    @foreach($med_types as $type)
                                        <option value="{{ $type->id }}" {{ old('medicine_type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                    </select>
                                    @error('medicine_type')
                                    <small class="text-danger">{{ $errors->first('medicine_type') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Manufacturer<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md" data-placeholder="Select" name="manufacturer">
                                    <option value="">Select</option>
                                    @foreach($mans as $man)
                                        <option value="{{ $man->id }}" {{ old('manufacturer') == $man->id ? 'selected' : '' }}>{{ $man->name }}</option>
                                    @endforeach
                                    </select>
                                    @error('manufacturer')
                                    <small class="text-danger">{{ $errors->first('manufacturer') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">HSN<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ old('hsn') }}" name="hsn" class="form-control form-control-md" placeholder="HSN">
                                    @error('hsn')
                                    <small class="text-danger">{{ $errors->first('hsn') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Tax %<sup class="text-danger">*</sup></label>
                                    <select class="form-control form-control-md" data-placeholder="Select" name="tax_percentage">
                                    <option value="">Select</option>
                                    @foreach($taxes as $tax)
                                        <option value="{{ $tax->tax_percentage }}" {{ old('tax_percentage') == $tax->tax_percentage ? 'selected' : '' }}>{{ $tax->tax_percentage }}</option>
                                    @endforeach
                                    </select>
                                    @error('tax_percentage')
                                    <small class="text-danger">{{ $errors->first('tax_percentage') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Available for Consultation</label></br>
                                    <input type="checkbox" value="1" name="available_for_consultation" class="form-check-input">
                                </div>
                                <div class="col-sm-12 text-right">
                                    <button type="button" onClick="history.back()"  class="btn btn-danger">Cancel</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-primary btn-submit">Save</button>
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