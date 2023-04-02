@extends("templates.base")
@section("content")
<div class="body d-flex">
    <div class="container">        
        <div class="row g-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-between align-items-end">
                    <div class="mb-3">
                        <h5 class="mb-0">Update Surgery Consumable</h5>
                        <span class="text-muted"></span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('surgery.consumable.edit', $sc->id) }}" method="post">
                            @csrf
                            @method("PUT")
                            <div class="row g-4">
                                <div class="col-sm-4">
                                    <label class="form-label">Consumable Name<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{ $sc->name }}" name="name" class="form-control form-control-md" placeholder="Consumable Name">
                                    @error('name')
                                    <small class="text-danger">{{ $errors->first('name') }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Decription</label>
                                    <input type="text" value="{{ $sc->description }}" name="description" class="form-control form-control-md" placeholder="Description">
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Price<sup class="text-danger">*</sup></label>
                                    <input type="number" value="{{ $sc->price }}" name="price" class="form-control form-control-md" placeholder="0.0">
                                    @error('price')
                                    <small class="text-danger">{{ $errors->first('price') }}</small>
                                    @enderror
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