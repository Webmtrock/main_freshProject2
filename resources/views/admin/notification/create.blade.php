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
                        Notification
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.notifications.store') }}" method="POST" enctype="multipart/form-data" id="basic-form">
                            @csrf

                            <div class="form-group">
                                <label for="address" class="mt-2">Address <span class="text-danger">*</span></label>
                                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Address" value="{{ old('address','') }}" required>
                                @error('address')
                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="mt-2">Range <span class="text-danger">*</span></label>
                                <select name="range" class="form-control form-select @error('range') is-invalid @enderror" required>
                                    <option value="">Select Range</option>
                                    @foreach($range as $key => $value) 
                                        <option value={{$key}} {{ old('range') ? ( old('range' == $key) ? 'selected' : '' ) : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('range')
                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="name" class="mt-2">Message <span class="text-danger">*</span></label>
                                <textarea name="message" class="@error('message') is-invalid @enderror w-100" rows="4" required="required">{{ old('message', '') }}</textarea>
                                @error('message')
                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="mt-3">
                                <input class="btn btn-primary" type="submit" value="Send">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
