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
                        {{ isset($data) && isset($data->id) ? 'Edit Coupon' : 'Create Coupon' }}
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.coupons.store') }}" method="POST" enctype="multipart/form-data" id="basic-form">
                            @csrf
                            <input type="hidden" name="id" id="id" value="{{ isset($data) ? $data->id : '' }}">
                            
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="mt-2"> Vendors <span class="text-danger">*</span></label>
                                    <span class="badge badge-opacity-primary selectAll">Select All</span>
                                    <span class="badge badge-opacity-primary deselectAll">Deselect All</span>
                                    <select name="vendor[]" class="form-control form-select @error('vendor') is-invalid @enderror select2 vendors" multiple>
                                        @foreach($vendors as $key => $value) 
                                            <option value={{$key}} {{ old('vendor') ? ((old('vendor') == $key) ? 'selected' : '' ) : ( (isset($data) && in_array($key, explode(',', $data->vendor_id))) ? 'selected' : '' ) }} >{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('vendor')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="name" class="mt-2"> Coupon Code <span class="text-danger">*</span></label>
                                    <input type="text" name="coupon_code" class="form-control @error('coupon_code') is-invalid @enderror coupon_code" placeholder="Coupon Code" value="{{ old('coupon_code', isset($data) && isset($data->coupon_code) ? $data->coupon_code : '') }}" required minlength="10" maxlength="10">
                                    @error('coupon_code')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="mt-2"> Content <span class="text-danger">*</span></label>
                                <textarea name="content" class="ckeditor @error('content') is-invalid @enderror" id="ckeditor" required="required">{{ empty(old('content')) ? (isset($data) ? $data->coupon_details : '') : old('content') }}</textarea>
                                @error('content')
                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name" class="mt-2"> Valid From <span class="text-danger">*</span></label>
                                    <input type="date" name="valid_from" class="form-control @error('valid_from') is-invalid @enderror valid_from" placeholder="Valid From" value="{{ old('valid_from', isset($data) && isset($data->valid_from) ? $data->valid_from : '') }}" min="{{ isset($data) && isset($data->valid_from) ? $data->valid_from : date('Y-m-d'); }}" required>
                                    @error('valid_from')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="name" class="mt-2"> Valid Upto <span class="text-danger">*</span></label>
                                    <input type="date" name="valid_to" class="form-control @error('valid_to') is-invalid @enderror valid_to" placeholder="valid_to" value="{{ old('valid_to', isset($data) && isset($data->valid_to) ? $data->valid_to : '') }}" min="{{ date('Y-m-d'); }}" required>
                                    <p class="valid_to_error text-danger"></p>
                                    @error('valid_to')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="mt-2"> Discount Type <span class="text-danger">*</span></label>
                                    <select name="discount_type" class="form-control form-select @error('discount_type') is-invalid @enderror" required>
                                        <option value="" {{ old('discount_type') ? ((old('discount_type') == '') ? 'selected' : '' ) : ( (isset($data) && $data->discount_type == 0) ? 'selected' : '' ) }} >Select Discount Type</option>
                                        <option value="P" {{ old('discount_type') ? ((old('discount_type') == 'P') ? 'selected' : '' ) : ( (isset($data) && $data->discount_type == 'P') ? 'selected' : '' ) }} >Percentage</option>
                                        <option value="F" {{ old('discount_type') ? ((old('discount_type') == 'F') ? 'selected' : '' ) : ( (isset($data) && $data->discount_type == 'F') ? 'selected' : '' ) }} >Fixed</option>
                                    </select>
                                    @error('discount_type')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="name" class="mt-2"> Discount <span class="text-danger">*</span><span class="info">(Enter Percent value when select Percentage otherwise enter amount)</span></label>
                                    <input type="number" name="discount" class="form-control @error('discount') is-invalid @enderror discount" placeholder="Discount" value="{{ old('discount', isset($data) && isset($data->amount) ? $data->amount : '') }}" min="0" required>
                                    @error('discount')
                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name" class="mt-2"> Maximum Reedem <span class="text-danger">*</span><span class="info">(Single User)</span></label>
                                    <input type="number" name="max_reedem" class="form-control @error('max_reedem') is-invalid @enderror max_reedem" placeholder="Maximum Reedem" value="{{ old('max_reedem', isset($data) && isset($data->max_reedem) ? $data->max_reedem : '') }}" min="0" required>
                                    @error('max_reedem')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="name" class="mt-2"> Max no. of User <span class="text-danger">*</span></label>
                                    <input type="number" name="max_user" class="form-control @error('max_user') is-invalid @enderror max_user" placeholder="Maximum number of User" value="{{ old('max_user', isset($data) && isset($data->max_user) ? $data->max_user : '') }}" min="0" required>
                                    @error('max_user')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name" class="mt-2"> Max Discount <span class="text-danger">*</span></label>
                                    <input type="number" name="max_discount" class="form-control @error('max_discount') is-invalid @enderror max_discount" placeholder="Maximum Discount" value="{{ old('max_discount', isset($data) && isset($data->max_discount) ? $data->max_discount : '') }}" min="0" required>
                                    @error('max_discount')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="name" class="mt-2"> Min Order Value <span class="text-danger">*</span></label>
                                    <input type="number" name="min_order_value" class="form-control @error('min_order_value') is-invalid @enderror min_order_value" placeholder="Minimum Order Value" value="{{ old('min_order_value', isset($data) && isset($data->min_order_value) ? $data->min_order_value : '') }}" min="0" required>
                                    @error('min_order_value')
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
    $(document).ready(function(){
        $(document).on('click', '.selectAll', function(){
            $(".vendors > option").prop("selected", true);
            $(".vendors").trigger("change");
        });

        $(document).on('click', '.deselectAll', function(){
            $(".vendors > option").prop("selected", false);
            $(".vendors").trigger("change");
        });
    });
</script>
@endsection