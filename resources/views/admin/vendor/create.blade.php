@extends('admin.template.layout')

@section('content')
<link href="{{ asset('') }}admin-assets/jquery.timepicker.min.css" rel="stylesheet" type="text/css" />

@if(!empty($datamain->vendordatils)) 
@php
 $vendor     = $datamain->vendordatils;
 $bankdata   = $datamain->bankdetails;
@endphp
@endif
    <div class="mb-5">

    <style>
        #parsley-id-23{
            bottom:0 !important
        }
        #parsley-id-66, #parsley-id-60, #parsley-id-21{
            position: absolute;
            bottom: -20px;
        }
        .custom-checkbox{
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 3px;
        }

        .custom-checkbox input{
            width: auto;
        }

        .custom-checkbox label{
            margin: 0
        }

    </style>
                <!--<div class="card p-4">-->
                    <form method="post" id="admin-form" action="{{ url('admin/vendors') }}" enctype="multipart/form-data"
                    data-parsley-validate="true">
                    <input type="hidden" name="id" value="{{ $id }}">
                    @csrf()
                    <div class="">

                            <div class="card mb-2">
                                <div class="card-body">
                                <h6 class="text-xl mb-2">Business Information</h6>
                                    <div class="row">
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Company Legal Name <span style="color:red;">*<span></span></span></label>
                                                <input type="text" class="form-control" data-jqv-maxlength="100" name="company_legal_name" value="{{empty($vendor->company_name) ? '': $vendor->company_name}}" required data-parsley-required-message="Enter Company Legal Name">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Company Brand Name <span style="color:red;">*<span></span></span></label>
                                                <input type="text" class="form-control" data-jqv-maxlength="100" name="company_brand_name" value="{{empty($vendor->company_brand) ? '': $vendor->company_brand}}" required data-parsley-required-message="Enter Company Brand Name">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Legal Status</label>
                                                <select name="legal_status" class="form-control jqv-input" data-jqv-required="true"><option value="">Select</option>
                                                    <option selected="" value="2">Sole Propriotorship</option>
                                                            <option value="3">Partnership</option>
                                                            <option value="4">Limited Liability Company</option>
                                                            <option value="6">I am an individual</option>
                                                            <option value="5">Branch of a foreign Company</option>
                                                            <option value="7">Others</option>
                                                    </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Business Registration Date <span style="color:red;">*<span></span></span></label>
                                                <input type="text" class="form-control flatpickr-input" data-date-format="yyyy-mm-dd" name="business_registration_date" value="{{empty($vendor->reg_date) ? '': date('Y-m-d', strtotime($vendor->reg_date))}}" required data-parsley-required-message="Enter Business Registration Date">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Trade Licence Number <span style="color:red;">*<span></span></span></label>
                                                <input type="text" class="form-control jqv-input" data-jqv-required="true" name="trade_licene_number" data-jqv-maxlength="100" value="{{empty($vendor->trade_license) ? '': $vendor->trade_license}}" required data-parsley-required-message="Enter Trade Licence Number">
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Trade Licence Expiry <span style="color:red;">*<span></span></span></label>
                                                <input type="text" class="form-control flatpickr-input" data-date-format="yyyy-mm-dd" name="trade_licene_expiry" value="{{empty($vendor->trade_license_expiry) ? '': date('Y-m-d', strtotime($vendor->trade_license_expiry))}}" required data-parsley-required-message="Enter Trade Licence Expiry" data-parsley-dategttoday data-parsley-trigger="change">
                                            </div>
                                        </div>              
                                    
                                        <!-- <div class="col-sm-4 col-xs-12">
                                            <label>Trade Licence  <a target="_blank" href="https://dx.co.ae/vhelp/vhelp_shop/uploads/company/b9e8b90b461e2aaa34da67c19ae29061.jpg"><i class="fa fa-eye"></i></a> </label>
                                            <input type="file" name="trade_licence" class="form-control jqv-input"  >
                                        </div> -->
                                        <div class="col-sm-4 col-xs-12" style="display: block">
                                            <div class="form-group">
                                                <label>Vat Registration Number <span style="color:red;">*<span></span></span></label>
                                                <input type="text" class="form-control jqv-input" name="vat_registration_number" data-jqv-maxlength="100" value="{{empty($vendor->vat_reg_number) ? '': $vendor->vat_reg_number}}" required data-parsley-required-message="Enter Vat Registration Number">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-xs-12" style="display: block">
                                            <div class="form-group">
                                                <label>Vat Expiry Date <span style="color:red;">*<span></span></span></label>
                                                <input type="text" class="form-control flatpickr-input" data-date-format="yyyy-mm-dd" name="vat_expiry_date" value="{{empty($vendor->vat_reg_expiry) ? '': date('Y-m-d', strtotime($vendor->vat_reg_expiry))}}" required data-parsley-required-message="Enter Vat Expiry Date" data-parsley-dategttoday data-parsley-trigger="change">
                                            </div>
                                        </div> 
                                        
                                        <!-- <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Vat Registration File  <a target="_blank" href="https://dx.co.ae/vhelp/vhelp_shop/uploads/company/1cdfe65492e33490fc06bdb98d7cd319.png"><i class="fa fa-eye"></i></a> </label>
                                                <input type="file" name="vat_registration" class="form-control jqv-input" >
                                            </div>
                                        </div> -->

                                         {{-- <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                <label>Store type<b class="text-danger">*</b></label>
                                <select name="store_type" class="form-control select2" required
                                data-parsley-required-message="Select Store type" >
                                    <option value="">Select</option>
                                    @foreach ($storetype as $cnt)
                                        <option <?php if(!empty($vendor->store_type)) { ?> {{$vendor->store_type == $cnt->id ? 'selected' : '' }} <?php } ?> value="{{ $cnt->id }}">
                                            {{ $cnt->name }}</option>
                                    @endforeach;
                                </select>
                            </div>
                            </div> --}}
                            <input type="hidden" name="store_type" value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-2">
                                <div class="card-body">
                                        <div class="col-xs-12">
                                            
                                            <div class="form-group">
                                                <!--<h4 >Registred Business Address</h4>-->
                                                <!-- <div class="card-title mt-3">Registred Business Address</div> -->
                                                <h6 class="text-xl mb-2">Registred Business Address</h6>
                                                <!--<div class="col-sm-12">-->
                                                    <div class="row">

                                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                <label>Country<b class="text-danger">*</b></label>
                                <select name="country_id" class="form-control select2" required
                                data-parsley-required-message="Select Country" data-role="country-change" id="country" data-input-state="city-state-id">
                                    <option value="">Select</option>
                                    @foreach ($countries as $cnt)
                                        <option <?php if(!empty($datamain->country_id)) { ?> {{$datamain->country_id == $cnt->id ? 'selected' : '' }} <?php } ?> value="{{ $cnt->id }}">
                                            {{ $cnt->name }}</option>
                                    @endforeach;
                                </select>
                            </div>
                            </div>
                                                        <div class="col-lg-4 col-md-4 col-12">
                                                            <label>Address Line 1 <span style="color:red;">*<span></span></span></label>
                                                            <input type="text" class="form-control" name="address1" value="{{empty($vendor->address1) ? '': $vendor->address1}}" data-jqv-maxlength="100" required
                                    data-parsley-required-message="Enter Address Line 1" >
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-12">
                                                            <label>Address Line 2</label>
                                                            <input type="text" class="form-control" name="address2" value="{{empty($vendor->address2) ? '': $vendor->address2}}" data-jqv-maxlength="100">
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-12">
                                                            <label>Street Name/No <span style="color:red;">*<span></span></span></label>
                                                            <input type="text" class="form-control" name="street" value="{{empty($vendor->street) ? '': $vendor->street}}" data-jqv-maxlength="100" required
                                    data-parsley-required-message="Enter Street Name/No">
                                                        </div>

                                                    
                                                        
                                                    <div class="form-group col-md-4">
                                    <label>State/Province<b class="text-danger">*</b></label>
                                    <select name="state_id" class="form-control" required
                                    data-parsley-required-message="Select State/Province" id="city-state-id" data-role="state-change" data-input-city="city-id">
                                        <option value="">Select</option>
                                        @foreach ($states as $st)
                                            <option  @if($id) @if($datamain->state_id==$st->id) selected @endif @endif value="{{$st->id}}">{{$st->name}}</option>
                                        @endforeach
                                    
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>City<b class="text-danger">*</b></label>
                                    <select name="city_id" class="form-control" required
                                    data-parsley-required-message="Select City" id="city-id">
                                        <option value="">Select</option>

                                        @foreach ($cities as $ct)
                                            <option  @if($id) @if($datamain->city_id==$ct->id) selected @endif @endif value="{{$ct->id}}">{{$ct->name}}</option>
                                        @endforeach
                                        
                                    </select>
                                </div>
                                                        
                                                        <div class="col-lg-4 col-md-4 col-12">
                                                            <label>Zip <span style="color:red;">*<span></span></span></label>
                                                            <input type="text" class="form-control" name="zip" value="{{empty($vendor->zip) ? '': $vendor->zip}}" data-jqv-maxlength="10" required
                                    data-parsley-required-message="Enter Zip code">
                                                            <div class="error"></div>
                                                        </div>
                                                    </div>
                                                <!--</div>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-2">
                            <div class="card-body">
                            <h6 class="text-xl mb-2">Basic Details</h6>
                                <div class="row">
                                    <div class="col-sm-4 col-xs-12" style="display:none;">
                                            <div class="form-group">
                                                <label>Have own delivery</label>
                                                <div class="col-sm-12">
                                                    <div class="form-check form-check-inline">
                                                        <label class="form-check-label">
                                                            <input type="radio" class="form-check-input" name="has_own_delivery" value="1" @php if(!empty($vendor->homedelivery)) { @endphp {{$vendor->homedelivery == '1' ? 'checked' : '' }} @php } else { echo "checked";} @endphp>Yes  <i class="input-helper"></i>
                                                        <i class="input-helper"></i></label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <label class="form-check-label">
                                                            <input type="radio" class="form-check-input" name="has_own_delivery" value="0" @php if(!empty($vendor)) { @endphp {{$vendor->homedelivery == '0' ? 'checked' : '' }} @php } @endphp>No<i class="input-helper"></i>
                                                        <i class="input-helper"></i></label>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="form-group d-flex align-items-center">
                                            <div>
                                            <label>Logo <span style="color:red;">*<span></span></span></label>
                                            <input type="file" class="form-control jqv-input" name="logo" data-role="file-image" data-preview="logo-preview" value="" @if(empty($id)) required
                            data-parsley-required-message="Logo is required" @endif data-parsley-imagedimensionsssss="200x200" data-parsley-trigger="change" data-parsley-fileextension="jpg,png,gif,jpeg"
                            data-parsley-fileextension-message="Only files with type jpg,png,gif,jpeg are supported" data-parsley-max-file-size="5120" data-parsley-max-file-size-message="Max file size should be 5MB" accept="image/*"
>
                                            <p class="text-muted">Allowed Dim 200x200(px)</p>
                                            </div>
                                                                                            <img id="logo-preview" class="img-thumbnail w-50" style="margin-left: 5px; height:50px; width:50px !important;" src="{{empty($vendor->logo) ? asset('uploads/place_holder.jpg'): $vendor->logo}}">
                                                                                    </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="form-group d-flex align-items-center">
                                            <div>
                                            <label>Image <span style="color:red;">*<span></span></span></label>
                                            <input type="file" class="form-control jqv-input" name="image" data-role="file-image" data-parsley-imagedimensionssssss="600x400" data-parsley-trigger="change" data-parsley-fileextension="jpg,png,gif,jpeg"
                                            data-parsley-fileextension-message="Only files with type jpg,png,gif,jpeg are supported" data-parsley-max-file-size="5120" data-parsley-max-file-size-message="Max file size should be 5MB" accept="image/*"
 data-preview="image-preview" @if(empty($id)) required
                            data-parsley-required-message="Image is required" @endif>
                                            <p class="text-muted">Max dim 600x400 (pix). Min dim 500x330 (pix).</p>
                                            </div>
                                                                                            <img id="image-preview" class="img-thumbnail w-50" style="margin-left: 5px; height:50px; width:50px !important;" src="{{empty($datamain->user_image) ? asset('uploads/place_holder.jpg'): $user_image}}">
                                                                                    </div>
                                    </div>
                                </div>
                                <div class="row">
                                   
                                    
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Name <span style="color:red;">*<span></span></span></label>
                                            <input type="text" class="form-control" name="name" data-jqv-maxlength="50" value="{{empty($datamain->name) ? '': $datamain->name}}" required
                            data-parsley-required-message="Enter Name">
                                        </div>
                                    </div>
                                     <div class="col-sm-4 col-xs-12" style="display:none;">
                                        <div class="form-group">
                                            <label>Industry Type<span style="color:red;">*<span></span></span></label>
                                            
                                                <select name="industrytype" class="form-control jqv-input industrytype-select" data-jqv-required="true"
                                                required
                            data-parsley-required-message="Enter Name" >

                                                    <option value="">Select</option>
                            @foreach ($industry as $cnt)
                                <option  value="{{ $cnt->id }}" selected @if(!empty($vendor)) {{$vendor->industry_type==$cnt->id ? "selected" : null}} @endif>
                                    {{ $cnt->name }}</option>
                            @endforeach;
                                                </select>
                                                
                                            
                                        </div>
                                    </div>
                                    <input type="hidden" name="no_of_branches" value="0">

                                    {{-- <div class="col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>No of branches <span style="color:red;">*<span></span></span></label>
                                            <input type="number" class="form-control" name="no_of_branches" value="{{empty($vendor->branches) ? '': $vendor->branches}}"  data-jqv-maxlength="100" >
                                            <div class="error"></div>
                                        </div>
                                    </div> --}}
                                    
                                </div>

                                <div class="row">
                                   
                                    
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Password </label>
                                            <input type="password" class="form-control" id="password" name="password" data-jqv-maxlength="50" value="" data-parsley-minlength="8" autocomplete="off"
                                            >
                                           
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Confirm Password </label>
                                            <input type="password" class="form-control" name="confirm_password" data-jqv-maxlength="50" value="" data-parsley-minlength="8"
                                            data-parsley-equalto="#password" autocomplete="off"
                                            data-parsley-required-message="Please re-enter your new password."
                                            data-parsley-required-if="#password">
                                           
                                        </div>
                                    </div>
                                     <div class="col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Email <span style="color:red;">*<span></span></span></label>
                                            <input type="email" class="form-control" name="email" data-jqv-maxlength="50" value="{{empty($datamain->email) ? '': $datamain->email}}" required
                            data-parsley-required-message="Enter Email" autocomplete="off">
                                            
                                        </div>
                                    </div>
                                   
                                   
                                    
                                  
                                </div>
                                <div class="row">

                                     <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                        <label>Dial Code<b class="text-danger">*</b></label>
                        <select name="dial_code" class="form-control select2 dial_code-select" required
                        data-parsley-required-message="Select Dial Code">
                            <option value="">Select</option>
                            @foreach ($countries as $cnt)
                                <option <?php if(!empty($datamain->dial_code)) { ?> {{$datamain->dial_code == $cnt->dial_code ? 'selected' : '' }} <?php } ?> value="{{ $cnt->dial_code }}">
                                    {{ $cnt->name }} +{{$cnt->dial_code}}</option>
                            @endforeach;
                        </select>
                    </div>
                    </div>
                                   
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Phone Number <span style="color:red;">*<span></span></span></label>
                                            <input type="number" class="form-control" name="phone" value="{{empty($datamain->phone) ? '': $datamain->phone}}" data-jqv-required="true" required
                            data-parsley-required-message="Enter Phone number" data-parsley-type="digits" data-parsley-minlength="5" 
    data-parsley-maxlength="12" data-parsley-trigger="keyup">
                                        </div>
                                    </div>
                                    
                                </div>
                                
                            </div>
                            
                            </div>


                            {{-- <div class="card mb-2">
                                <div class="card-body">
                                    <h6 class="text-xl mb-2">Bank Information</h6>
                                    <div class="row">
                                        <div class="col-sm-4 col-xs-12">
                                        <div class="form-group">
                            <label>Country<b class="text-danger">*</b></label>
                            <select name="bankcountry" class="form-control select2" required
                            data-parsley-required-message="Select Country" id="bankcountry">
                                <option value="">Select</option>
                                @foreach ($countries as $cnt)
                                    <option <?php if(!empty($bankdata->country)) { ?> {{$bankdata->country == $cnt->id ? 'selected' : '' }} <?php } ?> value="{{ $cnt->id }}">
                                        {{ $cnt->name }}</option>
                                @endforeach;
                            </select>
                        </div>
                        </div>
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Bank <span style="color:red;">*<span></span></span></label>
                                                <select class="form-control bank_id-select" name="bank_id" required
                            data-parsley-required-message="Select Bank">
                                                <option value="">Select</option>
                                                    @foreach ($banks as $cnt)
                                                <option <?php if(!empty($bankdata->bank_name)) { ?> {{$bankdata->bank_name == $cnt->id ? 'selected' : '' }} <?php } ?> value="{{ $cnt->id }}">
                                        {{ $cnt->name }}</option>
                                        @endforeach;
                                            </select>             
                                                
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Company Account <span style="color:red;">*<span></span></span></label>
                                                <input type="text" name="company_account" class="form-control" value="{{empty($bankdata->company_account) ? '': $bankdata->company_account}}" required
                            data-parsley-required-message="Enter Company account">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Bank Code Type <span style="color:red;">*<span></span></span></label>
                                                <select name="bank_code_type" class="form-control" required
                            data-parsley-required-message="Select code type">
                                                    <option value="">Select</option>
                                @foreach ($banks_codes as $cnt)
                                    <option <?php if(!empty($bankdata->code_type)) { ?> {{$bankdata->code_type == $cnt->id ? 'selected' : '' }} <?php } ?> value="{{ $cnt->id }}">
                                        {{ $cnt->name }}</option>
                                @endforeach;
                                            </select>
                                            </div>
                                        </div>

                                        
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Bank Account Number <span style="color:red;">*<span></span></span></label>
                                                <input type="text" name="bank_account_number" class="form-control" value="{{empty($bankdata->account_no) ? '': $bankdata->account_no}}" required
                            data-parsley-required-message="Enter Bank account number">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Bank Branch Code <span style="color:red;">*<span></span></span></label>
                                                <input type="text" name="bank_branch_code" class="form-control" value="{{empty($bankdata->branch_code) ? '': $bankdata->branch_code}}" required
                            data-parsley-required-message="Enter Bank Branch code">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Branch Name <span style="color:red;">*<span></span></span></label>
                                                <input type="text" name="branch_name" class="form-control" value="{{empty($bankdata->branch_name) ? '': $bankdata->branch_name}}" required
                            data-parsley-required-message="Enter Bank Branch name">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Bank Statement @php if(!empty($bankdata->bank_statement_doc)) { @endphp <a href='{{asset($bankdata->bank_statement_doc)}}' target='_blank'><strong>View</strong></a>@php }  @endphp</label>
                                                <input type="file" name="bank_statement" class="form-control jqv-input">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Credit Card Statement @php if(!empty($bankdata->credit_card_sta_doc)) { @endphp <a href='{{asset($bankdata->credit_card_sta_doc)}}' target='_blank'><strong>View</strong></a>@php }  @endphp</label>
                                                <input type="file" name="credit_card_statement" class="form-control jqv-input">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}



                            {{-- <div class="card mb-2">
                                <div class="card-body">
                                    <!-- <div class="card-title">Other Documents</div> -->
                                    <h6 class="text-xl mb-2">Other Documents</h6>
                                    <div class="row">
                                                                                <!-- doc_solo_properatorship -->
                                            <div class="col-sm-4 col-xs-12">
                                                <div class="form-group">
                                                    <label>Trade License @php if(!empty($vendor->trade_license_doc)) { @endphp <a href='{{asset($vendor->trade_license_doc)}}' target='_blank'><strong>View</strong></a>@php }  @endphp</label>
                                                    <input type="file" name="trade_licence" class="form-control jqv-input">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-xs-12">
                                                <div class="form-group">
                                                <label>Chamber of Commerce @php if(!empty($vendor->chamber_of_commerce_doc)) { @endphp <a href='{{asset($vendor->chamber_of_commerce_doc)}}' target='_blank'><strong>View</strong></a>@php }  @endphp  </label>
                                                    <input data-upload-status="0" type="file" class="form-control jqv-input" id="chamber_of_commerce" name="chamber_of_commerce" accept="image/png, image/jpeg, image/jpg, .pdf">
                                                
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-xs-12">
                                                <div class="form-group">
                                                <label>Share Certificate @php if(!empty($vendor->share_certificate_doc)) { @endphp <a href='{{asset($vendor->share_certificate_doc)}}' target='_blank'><strong>View</strong></a>@php }  @endphp </label>
                                                    <input data-upload-status="0" type="file" class="form-control jqv-input" id="share_certificate" name="share_certificate" accept="image/png, image/jpeg, image/jpg, .pdf">
                                                
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-12">
                                                <div class="form-group">
                                                <label>Power attorney @php if(!empty($vendor->power_attorny_doc)) { @endphp <a href='{{asset($vendor->power_attorny_doc)}}' target='_blank'><strong>View</strong></a>@php }  @endphp</label>
                                                    <input data-upload-status="0" type="file" class="form-control jqv-input" id="power_of_attorney" name="power_of_attorney" accept="image/png, image/jpeg, image/jpg, .pdf">
                                                
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-xs-12">
                                                <div class="form-group">
                                                <label>Vat Registration Certificate @php if(!empty($vendor->vat_reg_doc)) { @endphp <a href='{{asset($vendor->vat_reg_doc)}}' target='_blank'><strong>View</strong></a>@php }  @endphp</label>
                                                    <input data-upload-status="0" type="file" class="form-control jqv-input" id="vat_registration" name="vat_registration" accept="image/png, image/jpeg, image/jpg, .pdf">
                                                
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-xs-12">
                                                <div class="form-group">
                                                <label>Signed Agreement @php if(!empty($vendor->signed_agreement_doc)) { @endphp <a href='{{asset($vendor->signed_agreement_doc)}}' target='_blank'><strong>View</strong></a>@php }  @endphp </label>
                                                    <input data-upload-status="0" type="file" class="form-control jqv-input" id="signed_agrement" name="signed_agrement" accept="image/png, image/jpeg, image/jpg, .pdf">
                                                
                                                </div>
                                            </div>
                                                                                                                                                   
                                    </div>
                                </div>
                            </div> --}}

                            {{-- <div class="card mb-2">
                                <div class="card-body">
                                    <div class="row">
                                    <div class="col-sm-12">
                                                <!--<h4 class="mt-3">PROOF OF IDENTITY</h4>-->
                                                <h6 class="text-xl mb-3">Proof of Identity</h6>
                                                <!-- <div class="card-title mt-3">PROOF OF IDENTITY</div> -->
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label>Identity Type</label>
                                                <div class="custom-file">
                                                <select name="identity_file_name_1" id="identity_file_name_1" class="form-control jqv-input" data-jqv-required="true">
                                                    <option value="">Select</option>
                                                    <option selected="" value="1">Passport with Valid Visa</option>
                                                    <option value="2">Emirates ID (front and back)</option>
                                                    <option value="3">Passport Copy of Local Sponsor </option>
                                                </select>
                                                </div>
                                            </div>
                                            </div>
                                            <div class="col-sm-3 col-xs-12">
                                                <div class="form-group">
                                                <label>File @php if(!empty($vendor->identy1_doc)) { @endphp <a href='{{asset($vendor->identy1_doc)}}' target='_blank'><strong>View</strong></a>@php }  @endphp </label>
                                                    <input data-upload-status="0" type="file" class="form-control jqv-input" id="identity_file_value_1" name="identity_file_value_1" accept="image/png, image/jpeg, image/jpg, .pdf">
                                                
                                                </div>
                                            </div>
                                           
                                    </div>
                                </div>
                            </div> --}}

                            {{-- <div class="card mb-2">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                                    <!--<h4 class="mt-3">Company Proof of Address</h4>-->
                                                    <h6 class="text-xl mb-3">Company Proof of Address</h6>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label>Identity Type</label>
                                                    <div class="custom-file">
                                                    <select name="company_identity_value" id="company_identity_value" class="form-control jqv-input">
                                                        <option value="">Select</option>
                                                        <option selected="" value="1">Lease Agreement</option>
                                                        <option value="2">Utility Bills (DEWA, Etisalat, DU)</option>
                                                        <option value="3">Bank Statement</option>
                                                    </select>
                                                    </div>
                                                </div>
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                    <label>File  @php if(!empty($vendor->company_identy1_doc)) { @endphp <a href='{{asset($vendor->company_identy1_doc)}}' target='_blank'><strong>View</strong></a>@php }  @endphp </label>
                                                        <input data-upload-status="0" type="file" class="form-control jqv-input" id="company_identity_file" name="company_identity_file" accept="image/png, image/jpeg, image/jpg, .pdf">
                                                    
                                                    </div>
                                                </div>

                                    </div>
                                </div>
                            </div> --}}

                            <div class="card">
                                <div class="card-body">
                                    {{-- <div class="row">
                                        
                                    <div class="col-sm-12">
                                                    <!--<h4 class="mt-3">Authorize Signatory Residential Proof of Address</h4>-->
                                                    <!-- <div class="card-title mt-3">Authorize Signatory Residential Proof of Address</div> -->
                                                    <h6 class="text-xl mb-2">Authorize Signatory Residential Proof of Address</h6>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label>Identity Type</label>
                                                    <div class="custom-file">
                                                    <select name="residential_proff_value" id="residential_proff_value" class="form-control jqv-input">
                                                        <option value="">Select</option>
                                                        <option selected="" value="1">Lease Agreement</option>
                                                        <option value="2">Utility Bills (DEWA, Etisalat, DU)</option>
                                                        <option value="3">Bank Statement</option>
                                                    </select>
                                                    </div>
                                                </div>
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                    <label>File @php if(!empty($vendor->company_identy2_doc)) { @endphp <a href='{{asset($vendor->company_identy2_doc)}}' target='_blank'><strong>View</strong></a>@php }  @endphp</label>
                                                        <input data-upload-status="0" type="file" class="form-control jqv-input" id="residential_proff_file" name="residential_proff_file" accept="image/png, image/jpeg, image/jpg, .pdf">
                                                    
                                                    </div>
                                                </div>
                                                                                    
                                        </div> --}}
                                        @php $days = Config('global.days');  @endphp
                                       
                                       


                                        <div class="row">
                                            
                                            {{-- <div class="col-sm-4 col-xs-12 other_docs" id="halal_div" style="display:none;">
                                                <div class="form-group">
                                                    <label>Halal Certificate </label>
                                                    <input type="file" name="halal_certificate" class="form-control jqv-input">
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-12 other_docs" id="licence_from_food_safety_div" style="display:none;">
                                                <div class="form-group">
                                                    <label>Food License from Food Safety Department </label>
                                                    <input type="file" name="licence_from_food_safety" class="form-control jqv-input">
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-12 other_docs" id="pharmacy_licence_div" style="display:none;">
                                                <div class="form-group">
                                                    <label>Pharmacy License from MOHAP </label>
                                                    <input type="file" name="pharmacy_licence" class="form-control jqv-input">
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-12 other_docs" id="licence_to_operate_div" style="display:none;">
                                                <div class="form-group">
                                                    <label>License to Operate (LTO) from FDA </label>
                                                    <input type="file" name="licence_to_operate" class="form-control jqv-input">
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-12 other_docs" id="certificate_product_registration_div" style="display:none;">
                                                <div class="form-group">
                                                    <label>Certificate of Product Registration (CPR) from FDA</label>
                                                    <input type="file" name="certificate_product_registration" class="form-control jqv-input">
                                                </div>
                                            </div> --}}




                                            <div class="col-sm-4 col-xs-12 other_docs mt-3" id="certificate_product_registration_div" >
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                </form>
                </div>
@stop

@section('script')
<script src="//jonthornton.github.io/jquery-timepicker/jquery.timepicker.js"></script>
    <script>
        $(".flatpickr-multiple").flatpickr({
            // enableTime: true,
            dateFormat: "Y-m-d",
            mode: 'multiple',
            minDate: new Date()
        });

        App.initFormView();


        $('.time').timepicker({timeFormat:'H:i'});

        $(".week_days").change(function(e){
        
        if( $(this)[0].checked ) {
            $(this).parent().parent().find("input[type='text']").removeAttr("disabled")
        }
        else {
            $(this).parent().parent().find("input[type='text']").attr("disabled", "disabled")
            $(this).parent().parent().find("input[type='text']").val("")
        }
        })

        // $(document).ready(function() {
        //     $('select').select2();
        // });
        $(document).ready(function() {
            $('#country').select2();
        });
        
        $(document).ready(function() {
            $('#city-state-id').select2();
        });
        $(document).ready(function() {
            $('#city-id').select2();
        });
        $(document).ready(function() {
            $('.industrytype-select').select2();
        });
        $(document).ready(function() {
            $('#bankcountry').select2();
        });
        $(document).ready(function() {
            $('.dial_code-select').select2();
        });
        $(document).ready(function() {
            $('.bank_id-select').select2();
        });
        $(document).ready(function() {
            $('#identity_file_name_1').select2();
        });
        $(document).ready(function() {
            $('#identity_file_name_2').select2();
        });
        $(document).ready(function() {
            $('#company_identity_value').select2();
        });
        $(document).ready(function() {
            $('#residential_proff_value').select2();
        });
        $('body').off('submit', '#admin-form');
        $('body').on('submit', '#admin-form', function(e) {
            e.preventDefault();
            var $form = $(this);
            var formData = new FormData(this);
            $(".invalid-feedback").remove();

            App.loading(true);
            $form.find('button[type="submit"]')
                .text('Saving')
                .attr('disabled', true);

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: $form.attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                dataType: 'json',
                timeout: 600000,
                success: function(res) {
                    App.loading(false);

                    if (res['status'] == 0) {
                        if (typeof res['errors'] !== 'undefined') {
                            var error_def = $.Deferred();
                            var error_index = 0;
                            jQuery.each(res['errors'][0], function(e_field, e_message) {
                                if (e_message != '') {
                                    $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '"]').eq(0));
                                    if (error_index == 0) {
                                        error_def.resolve();
                                    }
                                    error_index++;
                                }
                            });
                            error_def.done(function() {
                                var error = $form.find('.is-invalid').eq(0);
                                $('html, body').animate({
                                    scrollTop: (error.offset().top - 100),
                                }, 500);
                            });
                        } else {
                            var m = res['message'];
                            App.alert(m, 'Oops!');
                        }
                    } else {
                        App.alert(res['message']);
                        setTimeout(function() {
                            window.location.href = App.siteUrl('/admin/vendors');
                        }, 1500);
                    }

                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                },
                error: function(e) {
                    App.loading(false);
                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                    App.alert(e.responseText, 'Oops!');
                }
            });
        });


    </script>

@stop
