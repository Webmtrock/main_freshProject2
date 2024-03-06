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
                        Create User
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" id="basic-form">
                            @csrf
                            <input type="hidden" name="id" id="id" value="{{ isset($data) ? $data->id : '' }}">
                            <input type="hidden" name="driver_id" id="driver_id" value="{{ (isset($data) && isset($data->driver)) ? $data->driver->id : '' }}">
                            <input type="hidden" name="vendor_id" id="vendor_id" value="{{ (isset($data) && isset($data->vendor)) ? $data->vendor->id : '' }}">
                            <input type="hidden" name="bank_account_id" id="bank_account_id" value="{{ (isset($data) && isset($data->bank_account)) ? $data->bank_account->id : '' }}">
                            
                            <h5 class="fw-bolder">{{ 'Basic Information' }}</h5>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name" class="mt-2"> Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Name" value="{{ old('name', isset($data) ? $data->name : '') }}" >
                                    @error('name')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="name" class="mt-2"> Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email', isset($data) ? $data->email : '') }}" >
                                    @error('email')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6 mt-auto">
                                    <label for="name" class="mt-2"> Phone <span class="text-danger">*</span></label>
                                    <input type="number" name="phone" class="form-control spin @error('phone') is-invalid @enderror" placeholder="Phone" value="{{ old('phone', isset($data) ? $data->phone : '') }}" min="0" minlength="10" maxlength="10" required>
                                    @error('phone')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    @if(!empty($data->profile_image))
                                        <div class="mt-3">
                                            <span class="pip" data-title="{{$data->profile_image}}">
                                                <img src="{{ url(config('app.profile_image')).'/'.$data->profile_image ?? '' }}" alt="" width="150" height="100">
                                            </span>
                                        </div>
                                    @endif
                                    <label for="name" class="mt-2"> Profile Image <span class="text-danger info">(Only jpeg, png, jpg files allowed)</span></label>
                                    <input type="file" name="profileImage" class="form-control @error('profileImage') is-invalid @enderror" accept="image/jpeg,image/png">
                                    <input type="hidden" class="form-control" name="profileImageOld" value="{{ isset($data) ? $data->profile_image : ''}}">
                                    @error('profileImage')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name" class="mt-2"> Location</label>
                                    <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" placeholder="Location" value="{{ old('location', isset($data) ? $data->location : '') }}">
                                    @error('location')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="name" class="mt-2"> Latitude </label>
                                    <input type="text" name="latitude" id="latitude" class="form-control @error('latitude') is-invalid @enderror" placeholder="Latitude" value="{{ old('latitude', isset($data) ? $data->latitude : '') }}" readonly>
                                    @error('latitude')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name" class="mt-2"> Longitude </label>
                                    <input type="text" name="longitude" id="longitude" class="form-control @error('longitude') is-invalid @enderror" placeholder="Longitude" value="{{ old('longitude', isset($data) ? $data->longitude : '') }}" readonly>
                                    @error('longitude')
                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-2">Role <span class="text-danger">*</span></label>
                                    <select name="role[]" class="form-control role select2 form-select @error('role') is-invalid @enderror" multiple required>
                                        @foreach($roles as $key => $value) 
                                            <option value="{{ $key }}" {{ (in_array($key, old('role', [])) || isset($data) && $data->roles->contains($key)) ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach

                                    </select>
                                    @error('role')
                                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            @if(isset($data))
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_fund_modal">
                                                Add Fund
                                            </button>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#revoke_fund_modal">
                                                Revoke Fund
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <div class="form-group col-md-6 password-field hide @error('password') show @enderror">
                                    <label for="name" class="mt-2"> Password  <span class="text-danger">{{ isset($data) && isset($data->id) ? '' : '*' }}</span> <i class="mdi mdi-information-outline" data-toggle="tooltip" data-placement="right" title="Password must contain atleast one Lower case letter, atleast one Upper case letter, atleast one Number and atleast one Special character."></i></label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" minlength="8" {{ isset($data) ? '' : 'required' }}>
                                    @error('password')
                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                                <div class="staff_section hide" id="staff_section">
                                    <div class="form-group col-md-6 ">
                                        <label for="name" class="mt-2"> Staff permissions  <span class="text-danger"></span> </label>
                                       <select name="staff_permissions[]" id="" class="form-control  select2 form-select" multiple>
                                        @foreach($staffPermissonsArray as $item)
                                        <option value="{{$item}}" @if(isset($data) && in_array($item,$staffPermissionsData)) selected @endif>{{$item}}</option>
                                        
                                        @endforeach
                                       </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="driverInfoSection hide" id="driverInfoSection">
                                <h5 class="fw-bolder">{{ 'Driver Information' }}</h5>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="name" class="mt-2"> Date Of Birth <span class="text-danger">*</span></label>
                                        <input type="date" name="dob" class="form-control is_required @error('dob') is-invalid @enderror" placeholder="Date Of Birth" value="{{ old('dob', (isset($data) && isset($data->driver)) ? $data->driver->dob : '') }}" max="{{ date('Y-m-d', strtotime(' - 18 years')); }}">
                                        @error('dob')
                                            <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="name" class="mt-2"> Aadhar Number <span class="text-danger">*</span></label>
                                        <input type="number" name="driverAadhar" class="form-control spin is_required @error('driverAadhar') is-invalid @enderror" placeholder="Aadhar Number" value="{{ old('driverAadhar', (isset($data) && isset($data->driver)) ? $data->driver->aadhar_no : '') }}" minlength="12" maxlength="12">
                                        @error('driverAadhar')
                                            <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="name" class="mt-2"> Pan Card Number <span class="text-danger">*</span></label>
                                        <input type="text" name="driverPanCard" class="form-control is_required @error('driverPanCard') is-invalid @enderror" placeholder="Pan Card Number" value="{{ old('driverPanCard', (isset($data) && isset($data->driver)) ? $data->driver->pan_no : '') }}" oninput="this.value = this.value.toUpperCase()">
                                        @error('driverPanCard')
                                            <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="name" class="mt-2"> Vehicle Number <span class="text-danger">*</span></label>
                                        <input type="text" name="driverVehicle" class="form-control is_required @error('driverVehicle') is-invalid @enderror" placeholder="Vehicle Number" value="{{ old('driverVehicle', (isset($data) && isset($data->driver)) ? $data->driver->vehicle_no : '') }}" oninput="this.value = this.value.toUpperCase()">
                                        @error('driverVehicle')
                                            <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6 {{ isset($data) ? 'mt-auto' : '' }}">
                                        <label for="name" class="mt-2"> Driving Licence Number <span class="text-danger">*</span></label>
                                        <input type="text" name="driverDrivingLicence" class="form-control is_required @error('driverDrivingLicence') is-invalid @enderror" placeholder="Driving License Number" value="{{ old('driverDrivingLicence', (isset($data) && isset($data->driver)) ? $data->driver->licence_no : '') }}">
                                        @error('driverDrivingLicence')
                                            <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        @if(!empty($data->driver->bank_statement))
                                            <div class="">
                                                <span class="pip" data-title="{{$data->driver->bank_statement}}">
                                                    <img src="{{ url(config('app.driver_document')).'/'.$data->driver->bank_statement ?? "" }}" alt="" width="150" height="100">
                                                </span>
                                            </div>
                                        @endif
                                        <label for="name" class="mt-2"> Bank Statement & Cancel Cheque <span class="text-danger">*</span> <span class="text-danger info">(Only jpeg, png, jpg files allowed)</span></label>
                                        <input type="file" name="driverStatement" class="form-control is_required @error('driverStatement') is-invalid @enderror" accept="image/jpeg,image/png">
                                        <input type="hidden" class="form-control" name="driverStatementOld" value="{{ (isset($data) && isset($data->driver->bank_statement)) ? $data->driver->bank_statement : ''}}">
                                        @error('driverStatement')
                                            <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        @if(!empty($data->driver->licence_front_image))
                                            <div class="mt-3">
                                                <span class="pip" data-title="{{$data->driver->licence_front_image}}">
                                                    <img src="{{ url(config('app.driver_document')).'/'.$data->driver->licence_front_image ?? "" }}" alt="" width="150" height="100">
                                                </span>
                                            </div>
                                        @endif
                                        <label for="name" class="mt-2"> Licence Front Image <span class="text-danger">*</span> <span class="text-danger info">(Only jpeg, png, jpg files allowed)</span></label>
                                        <input type="file" name="driverLicenceFront" class="form-control is_required @error('driverLicenceFront') is-invalid @enderror" accept="image/png,image/jpeg">
                                        <input type="hidden" class="form-control" name="driverLicenceFrontOld" value="{{ (isset($data) && isset($data->driver->licence_front_image)) ? $data->driver->licence_front_image : ''}}">
                                        @error('driverLicenceFront')
                                            <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        @if(!empty($data->driver->licence_back_image))
                                            <div class="mt-3">
                                                <span class="pip" data-title="{{$data->driver->licence_back_image}}">
                                                    <img src="{{ url(config('app.driver_document')).'/'.$data->driver->licence_back_image ?? "" }}" alt="" width="150" height="100">
                                                </span>
                                            </div>
                                        @endif
                                        <label for="name" class="mt-2"> Licence Back Image <span class="text-danger">*</span> <span class="text-danger info">(Only jpeg, png, jpg files allowed)</span></label>
                                        <input type="file" name="driverLicenceBack" class="form-control is_required @error('driverLicenceBack') is-invalid @enderror" accept="image/png,image/jpeg">
                                        <input type="hidden" class="form-control" name="driverLicenceBackOld" value="{{ (isset($data) && isset($data->driver->licence_back_image)) ? $data->driver->licence_back_image : ''}}">
                                        @error('driverLicenceBack')
                                            <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        @if(!empty($data->driver->aadhar_front_image))
                                            <div class="even mt-3">
                                                <span class="pip" data-title="{{$data->driver->aadhar_front_image}}">
                                                    <img src="{{ url(config('app.driver_document')).'/'.$data->driver->aadhar_front_image ?? "" }}" alt="" width="150" height="100">
                                                </span>
                                            </div>
                                        @endif
                                        <label for="name" class="mt-2"> Aadhar Card Front <span class="text-danger">*</span> <span class="text-danger info">(Only jpeg, png, jpg files allowed)</span></label>
                                        <input type="file" name="driverAadharFront" class="form-control is_required @error('driverAadharFront') is-invalid @enderror" accept="image/png,image/jpeg">
                                        <input type="hidden" class="form-control" name="driverAadharFrontOld" value="{{ (isset($data) && isset($data->driver->aadhar_front_image)) ? $data->driver->aadhar_front_image : ''}}">
                                        @error('driverAadharFront')
                                            <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        @if(!empty($data->driver->aadhar_back_image))
                                            <div class="even mt-3">
                                                <span class="pip" data-title="{{$data->driver->aadhar_back_image}}">
                                                    <img src="{{ url(config('app.driver_document')).'/'.$data->driver->aadhar_back_image ?? "" }}" alt="" width="150" height="100">
                                                </span>
                                            </div>
                                        @endif
                                        <label for="name" class="mt-2"> Aadhar Card Back <span class="text-danger">*</span> <span class="text-danger info">(Only jpeg, png, jpg files allowed)</span></label>
                                        <input type="file" name="driverAadharBack" class="form-control is_required @error('driverAadharBack') is-invalid @enderror" accept="image/png,image/jpeg">
                                        <input type="hidden" class="form-control" name="driverAadharBackOld" value="{{ (isset($data) && isset($data->driver->aadhar_back_image)) ? $data->driver->aadhar_back_image : ''}}">
                                        @error('driverAadharBack')
                                            <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        @if(!empty($data->driver->pan_card_image))
                                            <div class="even mt-3">
                                                <span class="pip" data-title="{{$data->driver->pan_card_image}}">
                                                    <img src="{{ url(config('app.driver_document')).'/'.$data->driver->pan_card_image ?? "" }}" alt="" width="150" height="100">
                                                </span>
                                            </div>
                                        @endif
                                        <label for="name" class="mt-2"> Pan Card Image <span class="text-danger">*</span> <span class="text-danger info">(Only jpeg, png, jpg files allowed)</span></label>
                                        <input type="file" name="driverPanImage" class="form-control is_required @error('driverPanImage') is-invalid @enderror" accept="image/png,image/jpeg">
                                        <input type="hidden" class="form-control" name="driverPanImageOld" value="{{ (isset($data) && isset($data->driver->pan_card_image)) ? $data->driver->pan_card_image : ''}}">
                                        @error('driverPanImage')
                                            <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6 pt-5 {{ isset($data) ? 'mt-auto' : '' }}">
                                        <div class="row">
                                            <div class="form-group col-md-6 {{ isset($data) ? 'mb-0 mt-auto' : '' }}">
                                                <div class="form-check">
                                                    <label class="form-check-label text-muted">
                                                        <input class="form-check-input" type="checkbox" name="driverVerify" {{ old('driverVerify') ? 'checked' : (isset($data) ? ($data->as_driver_verified ? 'checked' : '' ) : '' ) }} value="1">
                                                        {{ __('Approve as Driver') }}
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6 {{ isset($data) ? 'mb-0 mt-auto' : '' }}">
                                                <div class="form-check">
                                                    <label class="form-check-label text-muted">
                                                        <input class="form-check-input" type="checkbox" name="driverMode"  {{ old('driverMode') ? 'checked' : (isset($data) ? ($data->is_driver_online ? 'checked' : '' ) : '' ) }} value="1">
                                                        {{ __('Delivery Mode On/Off') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="vendorInfoSection hide" id="vendorInfoSection">
                                <h5 class="fw-bolder">{{ 'Vendor Information' }}</h5>

                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="vendor-info-tab" data-bs-toggle="tab" data-bs-target="#vendorInfo" type="button" role="tab" aria-controls="vendorInfo" aria-selected="true">Vendor Info</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="store-info-tab" data-bs-toggle="tab" data-bs-target="#storeInfo" type="button" role="tab" aria-controls="storeInfo" aria-selected="false">Store Info</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="vendorInfo" role="tabpanel" aria-labelledby="vendor-info-tab">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="name" class="mt-2"> Aadhar Number <span class="text-danger">*</span></label>
                                                <input type="number" name="aadharNumber" class="form-control spin is_required @error('aadharNumber') is-invalid @enderror" placeholder="Aadhar Number" value="{{ old('aadharNumber', (isset($data) && isset($data->vendor)) ? $data->vendor->aadhar_no : '') }}" minlength="12" maxlength="12">
                                                @error('aadharNumber')
                                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="name" class="mt-2"> Pan Card Number <span class="text-danger">*</span></label>
                                                <input type="text" name="panCardNumber" class="form-control is_required @error('panCardNumber') is-invalid @enderror" placeholder="Pan Card Number" value="{{ old('panCardNumber', (isset($data) && isset($data->vendor)) ? $data->vendor->pan_no : '') }}" oninput="this.value = this.value.toUpperCase()">
                                                @error('panCardNumber')
                                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                @if(!empty($data->vendor->bank_statement))
                                                    <div class="even mt-3">
                                                        <span class="pip" data-title="{{$data->vendor->bank_statement}}">
                                                            <img src="{{ url(config('app.vendor_document')).'/'.$data->vendor->bank_statement ?? "" }}" alt="" width="150" height="100">
                                                        </span>
                                                    </div>
                                                @endif
                                                <label for="name" class="mt-2"> Bank Statement & Cancel Cheque <span class="text-danger">*</span> <span class="text-danger info">(Only jpeg, png, jpg files allowed)</span></label>
                                                <input type="file" name="bankStatement" class="form-control is_required @error('bankStatement') is-invalid @enderror" accept="image/png,image/jpeg">
                                                <input type="hidden" class="form-control" name="bankStatementOld" value="{{ (isset($data) && isset($data->vendor->bank_statement)) ? $data->vendor->bank_statement : ''}}">
                                                @error('bankStatement')
                                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                @if(!empty($data->vendor->pan_card_image))
                                                    <div class="mt-3">
                                                        <span class="pip" data-title="{{$data->vendor->pan_card_image}}">
                                                            <img src="{{ url(config('app.vendor_document')).'/'.$data->vendor->pan_card_image ?? "" }}" alt="" width="150" height="100">
                                                        </span>
                                                    </div>
                                                @endif
                                                <label for="name" class="mt-2"> Pan Card Image <span class="text-danger">*</span> <span class="text-danger info">(Only jpeg, png, jpg files allowed)</span></label>
                                                <input type="file" name="panCardImage" class="form-control is_required @error('panCardImage') is-invalid @enderror" accept="image/png,image/jpeg">
                                                <input type="hidden" class="form-control" name="panCardImageOld" value="{{ (isset($data) && isset($data->vendor->pan_card_image)) ? $data->vendor->pan_card_image : ''}}">
                                                @error('panCardImage')
                                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                @if(!empty($data->vendor->aadhar_front_image))
                                                    <div class="even mt-3">
                                                        <span class="pip" data-title="{{$data->vendor->aadhar_front_image}}">
                                                            <img src="{{ url(config('app.vendor_document')).'/'.$data->vendor->aadhar_front_image ?? "" }}" alt="" width="150" height="100">
                                                        </span>
                                                    </div>
                                                @endif
                                                <label for="name" class="mt-2"> Aadhar Card Front <span class="text-danger">*</span> <span class="text-danger info">(Only jpeg, png, jpg files allowed)</span></label>
                                                <input type="file" name="aadharCardFront" class="form-control is_required @error('aadharCardFront') is-invalid @enderror" accept="image/png,image/jpeg">
                                                <input type="hidden" class="form-control" name="aadharCardFrontOld" value="{{ (isset($data) && isset($data->vendor->aadhar_front_image)) ? $data->vendor->aadhar_front_image : ''}}">
                                                @error('aadharCardFront')
                                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                @if(!empty($data->vendor->aadhar_back_image))
                                                    <div class="mt-3">
                                                        <span class="pip" data-title="{{$data->vendor->aadhar_back_image}}">
                                                            <img src="{{ url(config('app.vendor_document')).'/'.$data->vendor->aadhar_back_image ?? "" }}" alt="" width="150" height="100">
                                                        </span>
                                                    </div>
                                                @endif
                                                <label for="name" class="mt-2"> Aadhar Card Back <span class="text-danger">*</span> <span class="text-danger info">(Only jpeg, png, jpg files allowed)</span></label>
                                                <input type="file" name="aadharCardBack" class="form-control is_required @error('aadharCardBack') is-invalid @enderror" accept="image/png,image/jpeg">
                                                <input type="hidden" class="form-control" name="aadharCardBackOld" value="{{ (isset($data) && isset($data->vendor->aadhar_back_image)) ? $data->vendor->aadhar_back_image : ''}}">
                                                @error('aadharCardBack')
                                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="mt-2"> Delivery Range <span class="text-danger">*</span></label>
                                                <select name="deliveryRange" class="form-control is_required form-select @error('deliveryRange') is-invalid @enderror">
                                                    <option value="" {{ old('deliveryRange') ? ((old('deliveryRange') == '') ? 'selected' : '' ) : ( (isset($data) && $data->delivery_range == 0) ? 'selected' : '' ) }} >Select Range</option>
                                                    @foreach($range as $key => $value) 
                                                        <option value={{$key}} {{ old('deliveryRange') ? ((old('deliveryRange') == $key) ? 'selected' : '' ) : ( (isset($data) && $data->delivery_range == $key) ? 'selected' : '' ) }} >{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                                @error('deliveryRange')
                                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="name" class="mt-2"> Admin Commission <span class="text-danger">*</span></label>
                                                <input type="text" name="admin_commission" class="form-control is_required @error('admin_commission') is-invalid @enderror" placeholder="Admin Commission" value="{{ old('admin_commission', isset($data) ? $data->admin_commission : '') }}">
                                                @error('admin_commission')
                                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 {{ isset($data) ? 'mt-auto' : '' }}">
                                            <div class="row">
                                                <div class="form-group col-md-6 {{ isset($data) ? 'mb-0 mt-auto' : '' }}">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" type="checkbox" name="vendorVerify" {{ old('vendorVerify') ? 'checked' : (isset($data) ? ($data->as_vendor_verified ? 'checked' : '' ) : '' ) }} value="1">
                                                            {{ __('Approve as Vendor') }}
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6 {{ isset($data) ? 'mb-0 mt-auto' : '' }}">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" type="checkbox" name="featured_store" {{ old('featured_store') ? 'checked' : (isset($data) ? ($data->featured_store ? 'checked' : '' ) : '' ) }} value="1">
                                                            {{ __('Featured store') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="storeInfo" role="tabpanel" aria-labelledby="store-info-tab">
                                        <div class="row">
                                            <div class="form-group col-md-6 mt-auto">
                                                <label for="name" class="mt-2">Store Name <span class="text-danger">*</span></label>
                                                <input type="text" name="store_name" class="form-control is_required @error('store_name') is-invalid @enderror" placeholder="Store Name" value="{{ old('store_name', (isset($data) && isset($data->vendor)) ? $data->vendor->store_name : '') }}" >
                                                @error('store_name')
                                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                @if(!empty($data->vendor->store_image))
                                                    <div class="mt-3">
                                                        <span class="pip" data-title="{{$data->vendor->store_image}}">
                                                            <img src="{{ url(config('app.vendor_document')).'/'.$data->vendor->store_image ?? "" }}" alt="" width="150" height="100">
                                                        </span>
                                                    </div>
                                                @endif
                                                <label for="name" class="mt-2"> Store Image <span class="text-danger">*</span> <span class="text-danger info">(Only jpeg, png, jpg files allowed)</span></label>
                                                <input type="file" name="storeImage" class="form-control is_required @error('storeImage') is-invalid @enderror" accept="image/png,image/jpeg">
                                                <input type="hidden" class="form-control" name="storeImageOld" value="{{ (isset($data) && isset($data->vendor->store_image)) ? $data->vendor->store_image : ''}}">
                                                @error('storeImage')
                                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group">
                                                <label for="name" class="mt-2">Store Address</label>
                                                <input type="text" name="store_address" id="store_address" class="form-control @error('store_address') is-invalid @enderror" placeholder="Store Address" value="{{ old('store_address', (isset($data) && isset($data->vendor)) ? $data->vendor->address : '') }}">
                                                @error('store_address')
                                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="name" class="mt-2">Store Location</label>
                                                <input type="text" name="store_location" id="store_location" class="form-control @error('store_location') is-invalid @enderror" placeholder="Store Location" value="{{ old('store_location', (isset($data) && isset($data->vendor)) ? $data->vendor->location : '') }}">
                                                @error('store_location')
                                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="name" class="mt-2"> Latitude </label>
                                                <input type="text" name="store_latitude" id="store_latitude" class="form-control @error('store_latitude') is-invalid @enderror" placeholder="Store Latitude" value="{{ old('store_latitude', (isset($data) && isset($data->vendor)) ? $data->vendor->lat : '') }}" readonly>
                                                @error('store_latitude')
                                                    <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="name" class="mt-2"> Longitude </label>
                                                <input type="text" name="store_longitude" id="store_longitude" class="form-control @error('store_longitude') is-invalid @enderror" placeholder="Store Longitude" value="{{ old('store_longitude', (isset($data) && isset($data->vendor)) ? $data->vendor->long : '') }}" readonly>
                                                @error('store_longitude')
                                                <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                    {{ $message }}
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6 pt-5 {{ isset($data) ? 'mt-auto' : '' }}">
                                                <div class="row">
                                                    <div class="form-group col-md-6 {{ isset($data) ? 'mb-0 mt-auto' : '' }}">
                                                        <div class="form-check">
                                                            <label class="form-check-label text-muted">
                                                                <input class="form-check-input" type="checkbox" name="storeOpen" {{ old('storeOpen') ? 'checked' : (isset($data) ? ($data->is_vendor_online ? 'checked' : '' ) : '' ) }} value="1">
                                                                {{ __('Store Open') }}
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6 {{ isset($data) ? 'mb-0 mt-auto' : '' }}">
                                                        <div class="form-check">
                                                            <label class="form-check-label text-muted">
                                                                <input class="form-check-input" type="checkbox" name="self_delivery" {{ old('self_delivery') ? 'checked' : (isset($data) ? ($data->self_delivery ? 'checked' : '' ) : '' ) }} value="1">
                                                                {{ __('Self Delivery') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="mt-2"> Store Timing </label>
                                            @if(isset($data) && (count($data->vendor_availability)>0))
                                                @foreach($data->vendor_availability as $value)
                                                    <input type="hidden" name="vendor_available_id[]" value="{{ $value->id }}">

                                                    <div class="row">
                                                        <div class="form-group col-md-3">
                                                            <div class="form-check">
                                                                <label class="form-check-label text-muted">
                                                                    <input class="form-check-input " type="checkbox" name="weekday[{{$value->week_day}}]" {{ $value->status == 1 ? 'checked' : '' }} value="1"> {{ $week_arr[$value->week_day] }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-md-3">
                                                            <input type="time" name="start_time[{{$value->week_day}}]" class="form-control" value="{{ $value->start_time }}">
                                                        </div>

                                                        <div class="form-group col-md-3">
                                                            <input type="time" name="end_time[{{$value->week_day}}]" class="form-control" value="{{ $value->end_time }}">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                @for($i=1; $i<=7; $i++)
                                                    <div class="row">
                                                        <div class="form-group col-md-3">
                                                            <div class="form-check">
                                                                <label class="form-check-label text-muted">
                                                                    <input class="form-check-input " type="checkbox" name="weekday[{{$i}}]" {{ old('weekday[$i]') ? 'checked' : '' }} value="1"> {{ $week_arr[$i] }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-md-3">
                                                            <input type="time" name="start_time[{{$i}}]" class="form-control" value="09:00">
                                                        </div>

                                                        <div class="form-group col-md-3">
                                                            <input type="time" name="end_time[{{$i}}]" class="form-control" value="17:00">
                                                        </div>
                                                    </div>
                                                @endfor
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accountDetailSection hide mt-3" id="accountDetailSection">
                                <h5 class="fw-bolder">{{ 'Account Details' }}</h5>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="mt-2">Bank</label>
                                        <select name="bank" class="form-control form-select @error('bank') is-invalid @enderror">
                                            <option value="" {{ old('bank') ? ((old('bank') == '') ? 'selected' : '' ) : ( (isset($data) && $data->delivery_range == 0) ? 'selected' : '' ) }} >Select Bank</option>
                                            @foreach($banks as $key => $value) 
                                                <option value={{$key}} {{ old('bank') ? ((old('bank') == $key) ? 'selected' : '' ) : ((isset($data) && isset($data->bank_account)) ? (($data->bank_account->bank_id == $key) ? 'selected' : '' ) : '' ) }} >{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        @error('bank')
                                            <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="name" class="mt-2">Account Holder Name</label>
                                        <input type="text" name="account_name" class="form-control @error('account_name') is-invalid @enderror" placeholder="Account Holder Name" value="{{ old('account_name', (isset($data) && isset($data->bank_account)) ? $data->bank_account->account_name : '') }}">
                                        @error('account_name')
                                            <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="name" class="mt-2">Bank Account Number</label>
                                        <input type="number" name="account_no" class="form-control spin @error('account_no') is-invalid @enderror" placeholder="Bank Account Number" value="{{ old('account_no', (isset($data) && isset($data->bank_account)) ? $data->bank_account->account_no : '') }}">
                                        @error('account_no')
                                            <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="name" class="mt-2">IFSC Code</label>
                                        <input type="text" name="ifsc_code" class="form-control @error('ifsc_code') is-invalid @enderror" placeholder="IFSC Code" value="{{ old('ifsc_code', (isset($data) && isset($data->bank_account)) ? $data->bank_account->ifsc_code : '') }}" oninput="this.value = this.value.toUpperCase()">
                                        @error('ifsc_code')
                                            <span class="invalid-feedback form-invalid fw-bold" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="marketingManagerInfoSection hide" id="marketingManagerInfoSection">
                                <h5 class="fw-bolder">{{ 'Marketing Manager' }}</h5>
                                <div class="row">
                                    <div class="form-group col-md-6 pt-2">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <label class="form-check-label text-muted">
                                                        <input class="form-check-input" type="checkbox" name="marketingManagerVerify" {{ old('marketingManagerVerify') ? 'checked' : (isset($data) ? ($data->as_marketing_manager_verified ? 'checked' : '' ) : '' ) }} value="1">
                                                        {{ __('Approve as Marketing Manager') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

<!-- Modal -->
<div class="modal fade" id="add_fund_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.users.add-fund', $data->id ?? '') }}" method="POST" enctype="multipart/form-data" id="add-fund-basic-form">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="mt-2"> Amount <span class="text-danger">*</span></label>
                        <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="Enter Amount" value="{{ old('amount', isset($data) ? $data->amount : '') }}" required>
                        @error('amount')
                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name" class="mt-2">Remark <span class="text-danger">*</span></label>
                        <textarea name="remark" class="form-control @error('remark') is-invalid @enderror w-100" rows="4" placeholder="Enter Remark" required>{{ old('remark', '') }}</textarea>
                        @error('remark')
                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="revoke_fund_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.users.revoke-fund', $data->id ?? '') }}" method="POST" enctype="multipart/form-data" id="revoke-fund-basic-form">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="mt-2"> Amount <span class="text-danger">*</span></label>
                        <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="Enter Amount" value="{{ old('amount', isset($data) ? $data->amount : '') }}" required>
                        @error('amount')
                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name" class="mt-2">Remark <span class="text-danger">*</span></label>
                        <textarea name="remark" class="form-control @error('remark') is-invalid @enderror w-100" rows="4" placeholder="Enter Remark" required>{{ old('remark', '') }}</textarea>
                        @error('remark')
                        <span class="invalid-feedback form-invalid fw-bold" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Revoke</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts') 
<script async
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_GEOCODE_API_KEY') }}&libraries=places&callback=initMap">
</script>
<script>
function initMap() {
    window.addEventListener('load', initialize);
    function initialize() {
        var input = document.getElementById('location');
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            
            document.getElementById("latitude").value = place.geometry['location'].lat();
            document.getElementById("longitude").value = place.geometry['location'].lng();
        });

        var input_1 = document.getElementById('store_location');
        var autocomplete_1 = new google.maps.places.Autocomplete(input_1);
        autocomplete_1.addListener('place_changed', function () {
            var place_1 = autocomplete_1.getPlace();

            document.getElementById("store_latitude").value = place_1.geometry['location'].lat();
            document.getElementById("store_longitude").value = place_1.geometry['location'].lng();
        });
    }
}
function roleFunction(roles) {
    var is_password = 0;
    var is_driver = 0;
    var is_vendor = 0;
    var is_staff = 0;
    var is_marketing_manager = 0;

    if(($.inArray('1', roles) !== -1) || ($.inArray('5', roles) !== -1)) {
        is_password = 1;
    }
    if($.inArray('3', roles) !== -1) {
        is_driver = 1;
    }
    if($.inArray('4', roles) !== -1) {
        is_vendor = 1;
    }
    if($.inArray('5', roles) !== -1) {
        var is_staff = 1;
    }
    if($.inArray('7', roles) !== -1) {
        var is_marketing_manager = 1;
    }
    if(is_password) {
        $('.password-field').removeClass("hide");
    }
    else {
        $('.password-field').addClass("hide");
    }
    if(is_driver) {
        $('.driverInfoSection').removeClass("hide");
        console.log('driver');
        if($('#id').val() == "") {
            $('.driverInfoSection .is_required').attr('required',"required");
        }
    }
    else {
        $('.driverInfoSection').addClass("hide");
        console.log('driver-1');
        $('.driverInfoSection .is_required').removeAttr('required');
    }
    if(is_staff) {
        $('.staff_section').removeClass("hide");
    }
    else {
        $('.staff_section').addClass("hide");
    }
    if(is_vendor) {
        $('.vendorInfoSection').removeClass("hide");
        if($('#id').val() == "") {
            $('.vendorInfoSection .is_required').attr('required',"required");
        }
    }
    else {
        $('.vendorInfoSection').addClass("hide");
        $('.vendorInfoSection .is_required').removeAttr('required');
    }
    if((is_driver) || (is_vendor)) {
        $('.accountDetailSection').removeClass("hide");
    }
    else {
        $('.accountDetailSection').addClass("hide");
    }
    if(is_marketing_manager) {
        $('.marketingManagerInfoSection').removeClass("hide");
    }
    else {
        $('.marketingManagerInfoSection').addClass("hide");
    }
}
    
$(document).ready(function(){

    $(document).on('change', '.role', function(){
        var roles = $(this).val();

        roleFunction(roles);
    });

    $(document).on('click', '.selectAll', function(){
        $(".permissions > option").prop("selected", true);
        $(".permissions").trigger("change");
    });

    $(document).on('click', '.deselectAll', function(){
        $(".permissions > option").prop("selected", false);
        $(".permissions").trigger("change");
    });

    var roles = $('.role').val();
    roleFunction(roles);

    $('#add-fund-basic-form').validate();
    $('#revoke-fund-basic-form').validate();

});
</script>
@endsection



