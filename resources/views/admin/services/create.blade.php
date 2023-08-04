@extends('admin.template.layout')

@section('content')

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
    <form method="post" id="admin-form" action="{{ url('admin/services') }}" enctype="multipart/form-data"
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sort Order<span style="color:red;">*<span></label>
                                <input type="number" min="0" name="sort_order" class="form-control" required
                                    data-parsley-required-message="Enter Sort Order" value="{{ $sort_order ?? 0 }}">
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


                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group d-flex align-items-center">
                                <div>
                                    <label>Upload Service Image (gif,jpg,png,jpeg) <span
                                            style="color:red;">*<span></span></span></label>
                                    <input type="file" class="form-control jqv-input" name="image" data-role="file-image"
                                        data-preview="image-preview" value="" @if(empty($id)) requiredd
                                        data-parsley-required-message="image is required" @endif
                                        data-parsley-imagedimensionsss="200x200" data-parsley-trigger="change" data-parsley-fileextension="jpg,png,gif,jpeg"
                                        data-parsley-fileextension-message="Only files with type jpg,png,gif,jpeg are supported" data-parsley-max-file-size="5120" data-parsley-max-file-size-message="Max file size should be 5MB" accept="image/*">
                                    <p class="text-muted mt-2"></p>
                                </div>
                                <img id="image-preview" class="img-thumbnail w-50"
                                    style="margin-left: 5px; height:75px; width:75px !important;"
                                    @if($id && $image) src="{{$image}}" @endif>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group d-flex align-items-center">
                                <div>
                                    <label>Upload Service Background Image (gif,jpg,png,jpeg) <span
                                            style="color:red;">*<span></span></span></label>
                                    <input type="file" class="form-control jqv-input" name="background_image" data-role="file-image"
                                        data-preview="image-preview1" value="" @if(empty($id)) requiredd
                                        data-parsley-required-message="image is required" @endif
                                        data-parsley-imagedimensionsss="200x200" data-parsley-trigger="change" data-parsley-fileextension="jpg,png,gif,jpeg"
                                        data-parsley-fileextension-message="Only files with type jpg,png,gif,jpeg are supported" data-parsley-max-file-size="5120" data-parsley-max-file-size-message="Max file size should be 5MB" accept="image/*">
                                    <p class="text-muted mt-2"></p>
                                </div>
                                <img id="image-preview1" class="img-thumbnail w-50"
                                    style="margin-left: 5px; height:75px; width:75px !important;"
                                    @if($id && $background_image) src="{{$background_image}}" @endif>
                            </div>
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
<script>
App.initFormView();


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
                    window.location.href = App.siteUrl('/admin/services');
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