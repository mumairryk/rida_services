@extends('admin.template.layout')

@section('content')
<link href="{{ asset('') }}admin-assets/jquery.timepicker.min.css" rel="stylesheet" type="text/css" />
@if(!empty($datamain->vendordatils))
@php
// $vendor = $datamain->vendordatils;
$bankdata = $datamain->bankdetails;
@endphp
@endif
<div class="mb-5">
    <style>
    #parsley-id-15,
    #parsley-id-23 {
        bottom: auto;
    }

    #parsley-id-33 {
        bottom: -10px
    }

    .parsley-errors-list>.parsley-pattern {
        margin-top: 10px;
    }
    </style>
    <form method="post" id="admin-form" action="{{ url('admin/service_quotes') }}" enctype="multipart/form-data"
        data-parsley-validate="true">
        <input type="hidden" name="id" value="{{ $id }}">
        @csrf()
        <div class="">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Name <span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control"  maxlength="600" name="name"
                                    value="{{empty($datamain->name) ? '': $datamain->name}}" required
                                    data-parsley-required-message="Enter Name">
                            </div>
                        </div>

                        

                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Vendor <span style="color:red;">*<span></span></span></label>
                                <select class="form-control" name="vendor" required
                                            data-parsley-required-message="Select Vendor">
                                            <option value="">Select</option>
                                            @foreach ($users as $vnd)
                                            <option selected
                                                 value="{{ $vnd->id }}">{{ $vnd->name }}</option>
                                            @endforeach;
                                            
                                        </select>

                            </div>
                        </div>



                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="active" class="form-control">
                                    <option @if($id) @if($datamain->active==1) selected @endif @endif value="1">Active</option>
                                    <option @if($id) @if(!$datamain->active) selected @endif @endif value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                       
                    </div>



                    <div class="card mt-4 weekly" style="border-radius: 5px; overflow: hidden;" >
                        <div class="card-body">
                            <h4><b>Availability</b></h4>
                            <table class="table table-condensed pl-2 mt-2 workinghours" >
                                @php $days = Config('global.days');  @endphp
                                    @foreach($days as $key => $val)
                                        @php $st = $key.'_from'; $ed = $key.'_to';  @endphp
                                        <tr>
                                            <td>
                                            <input type="checkbox" class="week_days" id="day_{{$val}}"  name="{{$val}}" value="1" @if( $id && $datamain->{$val} == 1) checked @endif> &nbsp;
                                            <label for="day_{{$val}}"> {{ucfirst($val)}}</label>
                    
                                            </td>
                                            <td>
                                                <input type="text" @if( $id && $datamain->{$val} == 1) checked @else disabled @endif class="time form-control"  name="{{$key}}_from" value="@if($id && $datamain->$st!='' &&  $datamain->{$val} == 1){{$datamain->$st}}@endif" placeholder="Start Time">
                                            </td>
                                            <td>
                                                <input type="text" @if( $id && $datamain->{$val} == 1) checked @else disabled @endif class="time form-control"  name="{{$key}}_to" value="@if($id && $datamain->$ed!='' &&  $datamain->{$val} == 1){{$datamain->$ed}}@endif" placeholder="End Time">
                                            </td>
                                        </tr>
                                    @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-sm-6 col-xs-12 other_docs m-3" id="certificate_product_registration_div">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
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
                    window.location.href = App.siteUrl('/admin/service_quotes');
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