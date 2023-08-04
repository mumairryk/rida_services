@extends('front_end.template.layout')
@section('header')
<style>
    .list {
    max-height: 300px;
    overflow-y: scroll !important;
    }
    .parsley-errors-list{
        position: absolute;
        bottom: 0
    }
</style>
@stop

@section('content')
    <div class="inner-about-us-area"
        style="background: url('{{ asset('') }}admin-assets/assets/img/bg-1920x1080.jpg'); background-size: cover; background-position: center bottom; background-repeat: no-repeat;"">

        <div class="container">
            <div class="row justify-content-center" data-aos="fade-up" data-aos-duration="800">
                <div class="col-11 col-sm-10 col-md-10 col-lg-12 text-center p-0 mt-3 mb-2">
                    <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
                        <h2 id="heading">Vendor Registration</h2>
                        <p class="text-white">Fill all form field to go to next step</p>
                        <form id="msform" class="reg_form" data-parsley-validate="true" action="{{ url('save_vendor') }}" enctype="multipart/form-data" method="post">
                            <!-- progressbar -->
                            <ul id="progressbar">
                                <li class="active" id="contact"><strong>Business Information</strong></li>
                                <li id="restaurent"><strong>Basic Details & Bank Info:</strong></li>
                                <li id="documents"><strong>Documents & More:</strong></li>
                                <li id="confirm"><strong>Finish</strong></li>
                            </ul>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div> <br> <!-- fieldsets -->
                            @csrf()
                            <fieldset class="fld_set">
                                <div class="form-card" >
                                    <div class="row">
                                        <div class="col-7">
                                            <h2 class="fs-title">Business Information:</h2>
                                        </div>
                                        <div class="col-5">
                                            <h2 class="steps">Step 1 - 4</h2>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Company Legal Name: *</label>
                                            <input type="text" name="company_legal_name" placeholder="Company Legal Name" required data-parsley-required-message="Enter Company Legal Name" data-parsley-group="tb1"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Name: *</label>
                                            <input type="text" name="name" placeholder="Name" required data-parsley-required-message="Enter Name" data-parsley-group="tb1"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Company Brand Name: *</label>
                                            <input type="text" name="company_brand_name" placeholder="Company Brand Name" required data-parsley-required-message="Enter Company Brand Name" data-parsley-group="tb1"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Legal Status: *</label>
                                            <select class="form-control" name="legal_status" id="">
                                                <option selected="" value="2">Sole Propriotorship</option>
                                                <option value="3">Partnership</option>
                                                <option value="4">Limited Liability Company</option>
                                                <option value="6">I am an individual</option>
                                                <option value="5">Branch of a foreign Company</option>
                                                <option value="7">Others</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Business Registration Date: *</label>
                                            <input type="text" name="business_registration_date" class="flat-picker-input"
                                                placeholder="Business Registration Date" required data-parsley-required-message="Enter Business Registration Date" data-parsley-group="tb1"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Trade Licence Number: *</label>
                                            <input type="text" name="trade_licene_number" placeholder="Trade Licence Number" required data-parsley-required-message="Enter Trade Licence Number" data-parsley-group="tb1"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Trade License Expiry: *</label>
                                            <input type="text" name="trade_licene_expiry" class="flat-picker-input"
                                                placeholder="Trade License Expiry" required data-parsley-required-message="Enter Trade Licence Expiry" data-parsley-group="tb1"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Vat Registration Number: *</label>
                                            <input type="text" name="vat_registration_number" placeholder="Vat Registration Number" required data-parsley-required-message="Enter Vat Registration Number" data-parsley-group="tb1"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Vat Expiry Date: *</label>
                                            <input type="text" name="vat_expiry_date" class="flat-picker-input"
                                                placeholder="Vat Expiry Date" required data-parsley-required-message="Enter Vat Expiry Date" data-parsley-group="tb1"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Country: *</label>
                                            <select class="form-control" name="country_id" required
                                            data-parsley-required-message="Select Country" data-role="country-change" id="country" data-input-state="city-state-id" data-parsley-group="tb1">
                                                <option value="">Select Country</option>
                                                @foreach ($countries as $cnt)
                                                    <option value="{{ $cnt->id }}">
                                                        {{ $cnt->name }}</option>
                                                @endforeach;
                                            </select>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Address Line 1: *</label>
                                            <input type="text" name="address1" placeholder="Address Line 1" required
                                            data-parsley-required-message="Enter Address Line 1" data-parsley-group="tb1"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Address Line 2:</label>
                                            <input type="text" name="address2" placeholder="Address Line 2" data-parsley-group="tb1"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Street Name/No: *</label>
                                            <input type="text" name="street" placeholder="Street Name/No" required
                                            data-parsley-required-message="Enter Street Name/No" data-parsley-group="tb1"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">State/Province: *</label>
                                            <select class="form-control" name="state_id"  required
                                            data-parsley-required-message="Select State/Province" id="city-state-id" data-role="state-change" data-input-city="city-id" data-parsley-group="tb1">
                                                <option value="">Select </option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">City: *</label>
                                            <select class="form-control" name="city_id"  required
                                            data-parsley-required-message="Select City" id="city-id" data-parsley-group="tb1">
                                                <option value="">Select</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Zip Code: *</label>
                                            <input type="text" name="zip" placeholder="Zip Code" required
                                            data-parsley-required-message="Enter Zip code" data-parsley-group="tb1"/>
                                        </div>
                                    </div>
                                    <!-- <label class="fieldlabels">Confirm Password: *</label>
                                  <input type="password" name="cpwd" placeholder="Confirm Password" /> -->
                                </div> <input type="button" name="next" class="next action-button btnNextTab" value="Next" data-grp="tb1" />
                            </fieldset>
                            <fieldset class="fld_set">
                                <div class="form-card">
                                    <div class="row">
                                        <div class="col-7">
                                            <h2 class="fs-title">Basic Details & Bank Info:</h2>
                                        </div>
                                        <div class="col-5">
                                            <h2 class="steps">Step 2 - 4</h2>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Logo: *</label>
                                            <input type="file" name="logo" placeholder="" required
                                            data-parsley-required-message="Logo is required" ata-parsley-imagedimensionsss="200x200" data-parsley-trigger="change" data-parsley-fileextension="jpg,png,gif,jpeg"
                                            data-parsley-fileextension-message="Only files with type jpg,png,gif,jpeg are supported" data-parsley-group="tb2"/>
                                            <p style="color: white">Allowed Dim 200x200(px)<p>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Image: *</label>
                                            <input type="file" name="image" placeholder="" data-parsley-imagedimensionsss="600x400" data-parsley-trigger="change" data-parsley-required-message="Image is required"  data-parsley-fileextension="jpg,png,gif,jpeg"
                                            data-parsley-fileextension-message="Only files with type jpg,png,gif,jpeg are supported" data-parsley-group="tb2"/>
                                            <p class="text-muted">Allowed Dim 600x400(px)</p>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Industry Type: *</label>
                                            <select class="form-control" name="industrytype" id="" required
                                            data-parsley-required-message="Select Industry Type" data-parsley-group="tb2">
                                                <option value="">Select Industry</option>
                                                @foreach ($industry as $cnt)
                                                <option  value="{{ $cnt->id }}">
                                                    {{ $cnt->name }}</option>
                                                @endforeach;
                                            </select>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">No of branches: *</label>
                                            <input type="text" name="no_of_branches" placeholder="No of branches" required data-parsley-type="digits"
                                            data-parsley-required-message="Enter No of branches" data-parsley-group="tb2"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Password: *</label>
                                            <input type="password"id="password" name="password" placeholder="Password " data-parsley-minlength="8" autocomplete="off"  required
                                            data-parsley-required-message="Enter Password" data-parsley-group="tb2"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Confirm Password: *</label>
                                            <input type="password" name="confirm_password" placeholder="Confirm Password " data-parsley-minlength="8"
                                            data-parsley-equalto="#password" autocomplete="off" required data-parsley-required-message="Please Confirm Password" data-parsley-group="tb2"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Email: *</label>
                                            <input type="email" name="email" placeholder="Email" required
                                            data-parsley-required-message="Enter Email" autocomplete="off" data-parsley-group="tb2" data-parsley-trigger="change" data-parsley-remote="{{ url('checkAvailability') }}" data-parsley-remote-options='{ "type": "POST","data": { "field": "email","exclude" : "","_token":"<?=csrf_token()?>" } }' data-parsley-remote-message="Email already exists"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <div class="row">
                                                <label class="fieldlabels">Phone Number: *</label>
                                                <div class="col-lg-3 col-md-4">
                                                    <select class="form-control" name="dial_code" id="" required
                                                    data-parsley-required-message="" data-parsley-group="tb2">
                                                        <option value="">Select</option>
                                                        @foreach ($countries as $cnt)
                                                            <option value="{{ $cnt->dial_code }}">
                                                                {{ $cnt->name }} +{{$cnt->dial_code}}</option>
                                                        @endforeach;
                                                    </select>
                                                </div>
                                                <div class="col-lg-9 col-md-8">
                                                    <input type="text" name="phone" placeholder="Phone Number" required data-parsley-required-message="" data-parsley-type="digits" data-parsley-minlength="5"  data-parsley-maxlength="12" data-parsley-trigger="keyup" data-parsley-group="tb2" data-parsley-remote="{{ url('checkAvailability') }}" data-parsley-remote-options='{ "type": "POST","data": { "field": "phone","exclude" : "","_token":"<?=csrf_token()?>" } }' data-parsley-remote-message="Phone Number already exists"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <h4 class="text-white">Bank Information</h4>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Country: *</label>
                                            <!-- <input type="text" name="country" placeholder="Country" />  -->
                                            <select class="form-control" name="bankcountry" id="bankcountry" required
                                            data-parsley-required-message="Select Country" data-parsley-group="tb2">
                                                <option value="">Select</option>
                                                @foreach ($countries as $cnt)
                                                    <option value="{{ $cnt->id }}">
                                                        {{ $cnt->name }}</option>
                                                @endforeach;
                                            </select>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Bank: *</label>
                                            <!-- <input type="text" name="country" placeholder="Country" />  -->
                                            <select class="form-control" name="bank_id" required
                                            data-parsley-required-message="Select Bank" data-parsley-group="tb2">
                                                <option value="">Select Bank</option>
                                                @foreach ($banks as $cnt)
                                                    <option value="{{ $cnt->id }}">
                                                    {{ $cnt->name }}</option>
                                                @endforeach;
                                            </select>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Company Account: *</label>
                                            <input type="text" name="company_account" placeholder="Company Account" required
                                            data-parsley-required-message="Enter Company account" data-parsley-group="tb2"/>
                                        </div>


                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Bank Code Type: *</label>
                                            <select class="form-control" name="bank_code_type" id="" required
                                            data-parsley-required-message="Select code type" data-parsley-group="tb2">
                                                <option value="">Select Bank Code Type</option>
                                                @foreach ($banks_codes as $cnt)
                                                    <option value="{{ $cnt->id }}">
                                                        {{ $cnt->name }}</option>
                                                @endforeach;
                                            </select>
                                        </div>

                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Bank Account Number: *</label>
                                            <input type="text" name="bank_account_number" placeholder="Bank Account Number" required
                                            data-parsley-required-message="Enter Bank Account Number" data-parsley-group="tb2"/>
                                        </div>

                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Bank Branch Code: *</label>
                                            <input type="text" name="bank_branch_code" placeholder="Bank Branch Code" required
                                            data-parsley-required-message="Enter Bank Branch code" data-parsley-group="tb2"/>
                                        </div>


                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Branch Name: *</label>
                                            <input type="text" name="branch_name" placeholder="Branch Name" required
                                            data-parsley-required-message="Enter Bank Branch name" data-parsley-group="tb2"/>
                                        </div>

                                        {{-- <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Emirate/Province: *</label>
                                            <!-- <input type="text" name="emr-pr" placeholder="Emirate/Province" />  -->
                                            <select name="" id="">
                                                <option value="">Select Emirate</option>
                                            </select>
                                        </div> --}}
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Bank Statement:</label>
                                            <input type="file" name="bank_statement" placeholder="Bank Statement" data-parsley-group="tb2"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Credit Card Statement:</label>
                                            <input type="file" name="credit_card_statement"
                                                placeholder="Credit Card Statement" data-parsley-group="tb2"/>
                                        </div>
                                    </div>
                                </div> <input type="button" name="next" class="next action-button btnNextTab" value="Next" data-grp="tb2"/>
                                <input type="button" name="previous" class="previous action-button-previous"
                                    value="Previous" />
                            </fieldset>
                            <fieldset class="fld_set">
                                <div class="form-card">
                                    <div class="row">
                                        <div class="col-7">
                                            <h2 class="fs-title">Documents & More:</h2>
                                        </div>
                                        <div class="col-5">
                                            <h2 class="steps">Step 3 - 4</h2>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <h4 class="text-white">Other Documents</h4>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Trade license: </label>
                                            <input type="file" name="trade_licence" placeholder="" />
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Chamber of Commerce: </label>
                                            <input type="file"
                                                placeholder="Chamber of Commerce" name="chamber_of_commerce" accept="image/png, image/jpeg, image/jpg, .pdf" data-parsley-group="tb3"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Share Certificate: </label>
                                            <input type="file" id="share_certificate" name="share_certificate" accept="image/png, image/jpeg, image/jpg, .pdf" placeholder="" data-parsley-group="tb3"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Power attorney: </label>
                                            <input type="file" name="power_of_attorney" accept="image/png, image/jpeg, image/jpg, .pdf"" placeholder="" />
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Vat Registration Certificate: </label>
                                            <input type="file"  id="vat_registration" name="vat_registration" accept="image/png, image/jpeg, image/jpg, .pdf" placeholder="" data-parsley-group="tb3"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Signed Agreement: </label>
                                            <input type="file" id="signed_agrement" name="signed_agrement" accept="image/png, image/jpeg, image/jpg, .pdf" placeholder="" data-parsley-group="tb3"/>
                                        </div>
                                    </div>


                                    <div class="row mb-4">
                                        <h4 class="text-white">Proof of Identity</h4>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Identity Type: *</label>
                                            <select class="form-control" name="identity_file_name_1" id="" data-parsley-group="tb3">
                                                <option value="">Select</option>
                                                    <option selected="" value="Passport with Valid Visa">Passport with Valid Visa</option>
                                                    <option value="Emirates ID (front and back)">Emirates ID (front and back)</option>
                                                    <option value="Passport Copy of Local Sponsor ">Passport Copy of Local Sponsor </option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">File: *</label>
                                            <input type="file" name="identity_file_value_1" placeholder="" accept="image/png, image/jpeg, image/jpg, .pdf"/>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Identity Type: *</label>
                                            <select class="form-control" name="identity_file_name_2" id="" data-parsley-group="tb3">
                                                <option value="">Select</option>
                                                    <option value="Passport with Valid Visa">Passport with Valid Visa</option>
                                                    <option selected="" value="Emirates ID (front and back)">Emirates ID (front and back)</option>
                                                    <option value="Passport Copy of Local Sponsor">Passport Copy of Local Sponsor </option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">File: *</label>
                                            <input type="file" name="identity_file_value_2" placeholder="" accept="image/png, image/jpeg, image/jpg, .pdf" data-parsley-group="tb3"/>
                                        </div>
                                    </div>



                                    <div class="row mb-4">
                                        <h4 class="text-white">Company Proof of Address</h4>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Identity Type:</label>
                                            <select class="form-control" name="company_identity_value" id="" data-parsley-group="tb3">
                                                <option value="">Select</option>
                                                <option selected="" value="Lease Agreement">Lease Agreement</option>
                                                <option value="Utility Bills (DEWA, Etisalat, DU)">Utility Bills (DEWA, Etisalat, DU)</option>
                                                <option value="Bank Statement">Bank Statement</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">File: </label>
                                            <input type="file" name="company_identity_file" accept="image/png, image/jpeg, image/jpg, .pdf" placeholder="" data-parsley-group="tb3"/>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <h4 class="text-white">Authorize Signatory Residential Proof of Address</h4>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">Identity Type: *</label>
                                            <select class="form-control" name="residential_proff_value" id="" data-parsley-group="tb3">
                                                <option value="">Select Identity Type</option>
                                                <option selected="" value="1">Passport with Valid Visa</option>
                                                    <option value="2">Emirates ID (front and back)</option>
                                                    <option value="3">Passport Copy of Local Sponsor </option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label class="fieldlabels">File: *</label>
                                            <input type="file" name="residential_proff_file" accept="image/png, image/jpeg, image/jpg, .pdf" placeholder="" data-parsley-group="tb3"/>
                                        </div>
                                    </div>
                                </div> <input type="submit" name="next" class="action-button btnNextTab" value="Submit" data-grp="tb3"/>

                                <input type="button" name="next" class="next action-button sh_msg d-none" value="Submit" data-grp="tb5" />

                                <input type="button" name="previous" class="previous action-button-previous"
                                    value="Previous" />
                            </fieldset>
                            <fieldset class="fld_set_show">
                                <div class="form-card">
                                    <div class="row">
                                        <div class="col-7">
                                            <h2 class="fs-title">Finish:</h2>
                                        </div>
                                        <div class="col-5">
                                            <h2 class="steps">Step 4 - 4</h2>
                                        </div>
                                    </div> <br><br>
                                    <h2 class="purple-text text-center"><strong>Thank you for registration!</strong></h2>
                                    <br>
                                    <div class="row justify-content-center">
                                        <div class="col-3"> <img src="{{ asset('') }}front_end/image/correct.gif" class="fit-image"> </div>
                                    </div> <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-7 text-center">
                                            <h5 class="purple-text text-center">Our team will connect with you shortly.
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
@stop

@section('script')

<script>

    $('body').off('submit', '#msform');
        $('body').on('submit', '#msform', function(e) {
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
                            jQuery.each(res['errors'], function(e_field, e_message) {
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
                        $(".fld_set").addClass('d-none');
                        $(".sh_msg").trigger('click');
                        // App.alert(res['message']);
                        // setTimeout(function() {
                        //     window.location.href = App.siteUrl('/admin/vendors');
                        // }, 1500);
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
