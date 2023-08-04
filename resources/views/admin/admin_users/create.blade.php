@extends('admin.template.layout')

@section('content')
@if(!empty($datamain->vendordatils)) 
@php
 $vendor     = $datamain->vendordatils;
 $bankdata   = $datamain->bankdetails;
@endphp
@endif
    <div class="card mb-5">
                <!--<div class="card p-4">-->
                    <form method="post" id="admin-form" action="{{ url('admin/admin_users') }}" enctype="multipart/form-data"
                    data-parsley-validate="true">
                    <input type="hidden" name="id" value="{{ $id }}">
                    @csrf()
                    <div class="">
                              <div class="card-body">
                              
                                <div class="row">
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>First Name <span style="color:red;">*<span></span></span></label>
                                            <input type="text" class="form-control" data-jqv-maxlength="100" name="first_name" value="{{empty($datamain->first_name) ? '': $datamain->first_name}}" required
                            data-parsley-required-message="Enter First Name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Last Name <span style="color:red;">*<span></span></span></label>
                                            <input type="text" class="form-control" data-jqv-maxlength="100" name="last_name" value="{{empty($datamain->last_name) ? '': $datamain->last_name}}" required
                            data-parsley-required-message="Enter Last Name">
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Username <span style="color:red;">*<span></span></span></label>
                                            <input type="text" class="form-control" data-jqv-maxlength="100" name="username" value="{{empty($datamain->name) ? '': $datamain->name}}" required
                            data-parsley-required-message="Enter Username">
                                        </div>
                                    </div>


                                    
                                </div>
                                 <div class="row">
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Email <span style="color:red;">*<span></span></span></label>
                                            <input type="email" class="form-control" name="email" data-jqv-maxlength="50" value="{{empty($datamain->email) ? '': $datamain->email}}" required
                            data-parsley-required-message="Enter Email" autocomplete="off" autocomplete="new-password">
                                            
                                        </div>
                                    </div>
                                   
                                    
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Password @if(!$id)<span style="color:red;">*<span></span></span> @endif</label>
                                            {{-- <input type="password" class="form-control" id="password" name="password" data-jqv-maxlength="50" value="" data-parsley-minlength="8" autocomplete="new-password"
                                            > --}}

                                            <div class="input-group mb-3">
                                      
                                                <input type="password" class="form-control password" id="password" name="password" data-jqv-maxlength="50" value="" data-parsley-minlength="8" autocomplete="new-password" @if(!$id) required data-parsley-required-message="Enter password" @endif
                                                >
                                                <div class="input-group-append" style="cursor: pointer">
                                                    <span class="input-group-text" onclick="password_show_hide();">
                                                      <i class="fas fa-eye" id="show_eye"></i>
                                                      <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                                                    </span>
                                                  </div>
                                            </div>
                                          
                                           
                                        </div>
                                    </div>

                                   

                                    <div class="col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Confirm Password @if(!$id)<span style="color:red;">*<span></span></span>@endif</label>
                                            {{-- <input type="password" class="form-control" name="confirm_password" data-jqv-maxlength="50" value="" data-parsley-minlength="8"
                                            data-parsley-equalto="#password" autocomplete="off"
                                            data-parsley-required-message="Please re-enter your new password."
                                            data-parsley-required-if="#password"> --}}

                                            <div class="input-group mb-3">
                                      
                                                <input type="password" @if(!$id) required data-parsley-required-message="Enter Confirm password" @endif class="form-control" name="confirm_password" data-jqv-maxlength="50" value="" data-parsley-minlength="8"
                                            data-parsley-equalto="#password" autocomplete="off"
                                            data-parsley-required-message="Please re-enter your new password."
                                            data-parsley-required-if="#password" id="password2">

                                                <div class="input-group-append" style="cursor: pointer">
                                                    <span class="input-group-text" onclick="password_show_hide2();">
                                                      <i class="fas fa-eye" id="show_eye2"></i>
                                                      <i class="fas fa-eye-slash d-none" id="hide_eye2"></i>
                                                    </span>
                                                  </div>
                                            </div>
                                           
                                        </div>
                                    </div>
                                     
                                   
                                   
                                    
                                  
                                </div>

                                <div class="row">
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Designation <span style="color:red;">*<span></span></span></label>
                                            <select name="designation" class="form-control select2" required
                        data-parsley-required-message="Select Designation" id="designation">
                                            <option value="">Select</option>
                            @foreach ($designation as $cnt)
                                <option @if(!empty($datamain->designation_id)) {{$datamain->designation_id==$cnt->id ? "selected" : null}} @endif value="{{ $cnt->id }}">
                                    {{ $cnt->name }}</option>
                            @endforeach;
                        </select>
                                            
                                        </div>
                                    </div>
                                   
                                      <div class="col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="active" class="form-control status-selection">
                                                <option @if(!empty($datamain)) {{$datamain->active==1 ? "selected" : null}} @endif value="1">Active</option>
                                                <option @if(!empty($datamain)) {{$datamain->active==0 ? "selected" : null}} @endif value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>      
                                   
                                    
                                    
                                   
                                    
                                  
                                </div>

                                <div class="row mt-2">
                                    <div class="col-sm-4 col-xs-12 other_docs" id="certificate_product_registration_div" >
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        
                            
                            <!-- <div class="card-body">
                                
                               
                                <div class="row">
                                    
                                  




                                    
                                                                                                                                                    
                                </div>
                            </div>
                         -->


                           
                        
                    </div>
                </form>
                </div>
@stop

@section('script')
    <script>
        App.initFormView();
        $(document).ready(function() {
            $(".status-selection").select2();
        });

        $(document).ready(function() {
            $('#designation').select2();
            
        });
        $('body').off('submit', '#admin-form');
        $('body').on('submit', '#admin-form', function(e) {
            e.preventDefault();
            $(".invalid-feedback").remove();
            var $form = $(this);
            var formData = new FormData(this);

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
                        App.alert(res['message']);
                        setTimeout(function() {
                            window.location.href = App.siteUrl('/admin/admin_users');
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
    <script>
     
    function password_show_hide() {
        var x = document.getElementById("password");
        var show_eye = document.getElementById("show_eye");
        var hide_eye = document.getElementById("hide_eye");
        hide_eye.classList.remove("d-none");
        if (x.type === "password") {
            x.type = "text";
            show_eye.style.display = "none";
            hide_eye.style.display = "block";
        } else {
            x.type = "password";
            show_eye.style.display = "block";
            hide_eye.style.display = "none";
        }
    }

    function password_show_hide2() {
        var x2 = document.getElementById("password2");
        var show_eye2 = document.getElementById("show_eye2");
        var hide_eye2 = document.getElementById("hide_eye2");
        hide_eye2.classList.remove("d-none");
        if (x2.type === "password") {
            x2.type = "text";
            show_eye2.style.display = "none";
            hide_eye2.style.display = "block";
        } else {
            x2.type = "password";
            show_eye2.style.display = "block";
            hide_eye2.style.display = "none";
        }
    }
    </script>

@stop
