@extends('layouts.master')
@section('content')
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        @if(Session::has('success'))
            @section('scripts')
                <script>swal("Good job!", "{{ Session::get('success') }}", "success");</script>
            @endsection
        @endif

        @if(Session::has('error'))
            @section('scripts')
                <script>swal("Oops...", "{{ Session::get('error') }}", "error");</script>
            @endsection
        @endif
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header border-bottom">
                        {{ isset($data) && isset($data->id) ? 'Edit Product' : 'Create Product' }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.admin-products.store') }}" method="POST" enctype="multipart/form-data" id="basic-form">
                            @csrf
                            <input type="hidden" name="id" id="id" value="{{ isset($data) ? $data->id : '' }}">
                            
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="mt-2"> Category <span class="text-danger">*</span></label>
                                    <select name="category" class="form-control form-select @error('category') is-invalid @enderror category" required>
                                        <option value="" {{ old('category') ? ((old('category') == '') ? 'selected' : '' ) : ( (isset($data) && $data->category_id == 0) ? 'selected' : '' ) }} >Select Category</option>
                                        @foreach($categories as $key => $value) 
                                            <option value={{$key}} {{ old('category') ? ((old('category') == $key) ? 'selected' : '' ) : ( (isset($data) && $data->category_id == $key) ? 'selected' : '' ) }} >{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="name" class="mt-2"> Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="Title" value="{{ old('title', isset($data) ? $data->name : '') }}" required>
                                    @error('title')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    @if(!empty($data->image))
                                        <div class="mt-3">
                                            <span class="pip" data-title="{{$data->image}}">
                                                <img src="{{ url(config('app.product_image')).'/'.$data->image ?? '' }}" alt="" width="150" height="100">
                                            </span>
                                        </div>
                                    @endif
                                    <label for="name" class="mt-2"> Image <span class="text-danger">*</span> <span class="text-danger info">(Only jpeg, png, jpg files allowed)</span></label>
                                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/jpeg,image/png" {{ isset($data) && isset($data->id) ? '' : 'required' }}>
                                    <input type="hidden" class="form-control" name="imageOld" value="{{ isset($data) ? $data->image : ''}}">
                                    @error('image')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-6 mt-auto">
                                    <label for="name" class="mt-2"> SKU <span class="text-danger">*</span></label>
                                    <input type="text" name="SKU" class="form-control @error('SKU') is-invalid @enderror" placeholder="SKU" value="{{ old('SKU', isset($data) && isset($data->SKU) ? $data->SKU : '') }}" required>
                                    @error('SKU')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="name" class="mt-2"> Quantity <span class="text-danger">*</span></label>
                                    <input type="number" name="qty" class="form-control @error('qty') is-invalid @enderror" placeholder="Quantity" value="{{ old('qty', isset($data) && isset($data->qty) ? $data->qty : '') }}" required min="0">
                                    @error('qty')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <label class="mt-2"> Quantity Type <span class="text-danger">*</span></label>
                                    <select name="qty_type" class="form-control form-select @error('qty_type') is-invalid @enderror" required>
                                        <option value="" {{ old('qty_type') ? ((old('qty_type') == '') ? 'selected' : '' ) : ( (isset($data) && $data->qty_type == 0) ? 'selected' : '' ) }} >Select Quantity Type</option>
                                        @foreach($units as $key => $value) 
                                            <option value={{$key}} {{ old('qty_type') ? ((old('qty_type') == $key) ? 'selected' : '' ) : ( (isset($data) && $data->qty_type == $key) ? 'selected' : '' ) }} >{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('qty_type')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-1">
                                    <label for="name" class="mt-2">Min<span class="text-danger">*</span></label>
                                    
                                    <input type="number" name="min_qty" class="form-control @error('min_qty') is-invalid @enderror min_qty" value="{{ old('min_qty', isset($data) && isset($data->min_qty) ? $data->min_qty : '') }}" required min="1">
                                    @error('min_qty')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-1">
                                    <label for="name" class="mt-2">Max<span class="text-danger">*</span></label>
                                    
                                    <input type="number" name="max_qty" class="form-control @error('max_qty') is-invalid @enderror max_qty" value="{{ old('max_qty', isset($data) && isset($data->max_qty) ? $data->max_qty : '') }}" required min="1">
                                    @error('max_qty')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="name" class="mt-2"> Market Price <span class="text-danger">*</span></label>
                                    <input type="number" name="market_price" class="form-control @error('market_price') is-invalid @enderror market_price" placeholder="Market Price" value="{{ old('market_price', isset($data) && isset($data->market_price) ? $data->market_price : '') }}" required min="0" max="9999.99" step="0.01">
                                    @error('market_price')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="name" class="mt-2"> Regular Price <span class="text-danger">*</span></label>
                                    <input type="number" name="regular_price" class="form-control @error('regular_price') is-invalid @enderror regular_price" placeholder="Regular Price" value="{{ old('regular_price', isset($data) && isset($data->regular_price) ? $data->regular_price : '') }}" required min="0" max="9999.99" step="0.01">
                                    @error('regular_price')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="mt-2"> Content </label>
                                <textarea name="content" class="ckeditor @error('content') is-invalid @enderror" id="ckeditor">{{ empty(old('content')) ? (isset($data) ? $data->content : '') : old('content') }}</textarea>
                                @error('content')
                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name" class="mt-2"> Tax 1 (%)</label>
                                    <select name="tax_percent" class="form-control tax_percent form-select @error('tax_percent') is-invalid @enderror">
                                        <option value="" >Select Tax</option>
                                        @foreach($tax as $value)
                                            <option value="{{ $value->id }}" {{ old('tax_percent') ? ((old('tax_percent') == $value->id) ? 'selected' : '' ) : ((isset($data) && isset($data->tax)) ? ($data->tax->id == $value->id ? 'selected' : '' ) : '') }} >{{$value->title .' ('.$value->tax_percent.' %)'}}</option>
                                        @endforeach
                                    </select>
                                    @error('tax_percent')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="name" class="mt-2"> Tax 2 (%)</label>
                                    <select name="tax_percent_2" class="form-control tax_percent_2 form-select @error('tax_percent_2') is-invalid @enderror">
                                        <option value="" >Select Tax</option>
                                        @foreach($tax as $value)
                                            <option value="{{ $value->id }}" {{ old('tax_percent_2') ? ((old('tax_percent_2') == $value->id) ? 'selected' : '' ) : ((isset($data) && isset($data->tax2)) ? ($data->tax2->id == $value->id ? 'selected' : '' ) : '') }} >{{$value->title .' ('.$value->tax_percent.' %)'}}</option>
                                        @endforeach
                                    </select>
                                    @error('tax_percent_2')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="mt-2"> Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control form-select @error('status') is-invalid @enderror" required>
                                        <option value="" {{ old('status') ? ((old('status') == '') ? 'selected' : '' ) : ( (isset($data) && $data->status == 0) ? 'selected' : '' ) }} >Select Status</option>
                                        <option value="1" {{ old('status') ? ((old('status') == 1) ? 'selected' : '' ) : ( (isset($data) && $data->status == 1) ? 'selected' : '' ) }} >Active</option>
                                        <option value="0" {{ old('status') ? ((old('status') == 0) ? 'selected' : '' ) : ( (isset($data) && $data->status == 0) ? 'selected' : '' ) }} >In-Active</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <input class="btn btn-primary" type="submit" value="{{ isset($data) && isset($data->id) ? 'Update' : 'Save' }}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>

function getCatgeoryTax(category_id) {
    var route = "{{ url('admin/admin-products/categories-tax') }}/"+category_id;

    $.ajax({
        method: 'GET',
        url: route,
        success: function(response){
            if(response.success == true) {
                return $('.tax_percent').val(response.output);
            }
        },
    });
}
$(document).ready(function(){

    $(document).on('change', '.category', function(){
        var category_id = $(this).val();

        if(category_id == '') {
            category_id = 0;
        }
        getCatgeoryTax(category_id); 
    });

    $(document).on('change', '.min_qty', function(){
        var min_qty = $(this).val();
        var max_qty = $(this).closest('.row').find('.max_qty').val();
        if((min_qty != '') && (max_qty != '')) {
            if(min_qty > max_qty) {
                $(this).attr('max', max_qty);
            }
        }
    });

    $(document).on('change', '.max_qty', function(){
        var max_qty = $(this).val();
        var min_qty = $(this).closest('.row').find('.min_qty').val();
        if((min_qty != '') && (max_qty != '')) {
            if(min_qty > max_qty) {
                $(this).attr('min', min_qty);
            }
        }
    });

    $(document).on('change', '.regular_price', function(){
        var regular_price = $(this).val();
        var market_price = $(this).closest('.row').find('.market_price').val();
        if((regular_price != '') && (market_price != '')) {
            if(regular_price > market_price) {
                $(this).attr('max', market_price);
            }
        }
    });

    $(document).on('change', '.market_price', function(){
        var market_price = $(this).val();
        var regular_price = $(this).closest('.row').find('.regular_price').val();
        if((regular_price != '') && (market_price != '')) {
            if(regular_price > market_price) {
                $(this).attr('min', regular_price);
            }
        }
    });
});
</script>
@endsection