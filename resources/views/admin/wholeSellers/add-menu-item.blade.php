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

    .form-group.d-flex.align-items-center>div {
        flex: 1;
    }
    </style>
    <form method="post" id="admin-form" action="{{ url('admin/menu/save-item') }}" enctype="multipart/form-data"
        data-parsley-validate="true">
        <input type="hidden" name="id" value="{{$id}}">
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        @csrf()
        <div class="">

            <div class="card mb-2">
                <h5 class="card-body card-title">Menu Item Details</h5>
                <div class="row m-3">

                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Title <span style="color:red;">*<span></span></span></label>
                            <input type="text" class="form-control" data-jqv-maxlength="100" name="title"
                                   value="{{empty($item->title) ? '': $item->title}}" required
                                   data-parsley-required-message="Item Title">
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group d-flex align-items-center">
                            <div>
                                <label>Upload Picture (gif,jpg,png,jpeg) <span
                                        style="color:red;">*<span></span></span></label>
                                <input type="file" class="form-control jqv-input" name="image"
                                    data-role="file-image" data-preview="commercial_license-preview" value=""
                                    @if(empty($id)) required data-parsley-required-message="image is required" @endif
                                    data-parsley-trigger="change">
                            </div>
                            <img id="commercial_license-preview" class="img-thumbnail w-50"
                                style="margin-left: 5px; height:75px; width:75px !important;"
                                src="{{empty($item->image) ? asset('admin-assets/assets/img/placeholder.jpg'): asset($item->image) }}">
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>Description <span style="color:red;">*<span></span></span></label>
                            <textarea class="form-control" name="description" required
                                data-parsley-required-message="Description is required"
                                data-parsley-trigger="change">{{empty($item->description) ? '': $item->description}}</textarea>
                        </div>
                        <span id="mob_err"></span>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Item Type<b class="text-danger">*</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend w-100">
                                    <select class="form-control " name="menu_item_type_id" required
                                            data-parsley-required-message="">
                                        <option value="">Item Type</option>
                                        @foreach ($itemTypes ?? [] as $itemType)
                                            <option @if($id) @if($item->menu_item_type_id==$itemType->id) selected
                                                    @endif @endif value="{{ $itemType->id }}"> {{ $itemType->title }}
                                            </option>
                                        @endforeach;
                                    </select>
                                </div>
                            </div>
                            <span id="mob_err"></span>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Price <span style="color:red;">*<span></span></span></label>
                            <input type="text" class="form-control" data-jqv-maxlength="100" name="price"
                                   value="{{empty($item->price) ? '': $item->price}}" required
                                   data-parsley-required-message="Item Title">
                        </div>
                    </div>

                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Quantity <span style="color:red;">*<span></span></span></label>
                            <input type="text" class="form-control" data-jqv-maxlength="100" name="quantity"
                                   value="{{empty($item->quantity) ? '': $item->quantity}}" required
                                   data-parsley-required-message="Item Title">
                        </div>
                    </div>


                </div>

                <div class="row ">
                    <div class="col-sm-4 col-xs-12 other_docs m-3" id="certificate_product_registration_div">
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
                    window.location.href = App.siteUrl('/admin/menu?vendor={{$user->id}}');
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