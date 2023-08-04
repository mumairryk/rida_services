@extends("admin.template.layout")

@section('header')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop


@section('content')
    <div class="card mb-5">
      
        <div class="card-body">
            <form method="post" action="{{ url('/admin/banner/update') }}" id="admin-form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{$banner->id}}">
                <div class="row  d-flex justify-content-between align-items-center">
                    <div class="col-md-6 form-group select-category-form-group">
                        <label>Category</label>
                        <select class="form-control select2"
                            name="category_id" data-role="category-change" data-input-prd="prd-id">
                            <option value="">Select Category</option>
                            @foreach($categories as $key => $val)
                                <option @if($val->id==$banner->category_id) selected @endif value="{{$val->id}}">{{$val->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Product</label>
                        <select name="product_id" class="form-control select2" id="prd-id">
                            <option value="">Select Product</option>
                            @foreach($prds as $key => $val)
                                <option @if($val->id==$banner->product_id) selected @endif value="{{$val->id}}">{{$val->product_name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Status</label>
                        <select name="active" class="form-control">
                            <option <?= $banner->active == 1 ? 'selected' : '' ?> value="1">Active</option>
                            <option <?= $banner->active == 0 ? 'selected' : '' ?> value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-12 form-group" >
                        <img id="image-preview" style="width:192px; height:108px;" class="img-responsive mb-1"  data-image="{{$banner->banner_image}}" src="{{$banner->banner_image}}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Upload Banner</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input jqv-input" name="banner"
                                data-role="file-image"  data-preview="image-preview"  name="upload_image" id="banner" data-parsley-imagedimensionsss="1920X1079" data-parsley-trigger="change" data-parsley-fileextension="jpg,png,gif,jpeg"
                                data-parsley-fileextension-message="Only files with type jpg,png,gif,jpeg are supported" data-parsley-max-file-size="5120" data-parsley-max-file-size-message="Max file size should be 5MB" accept="image/*">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                        {{-- <small class="text-muted">
                            Upload Image With Dimension 1920X1079
                        </small> --}}
                    </div>
                    
                 
                    <div class="col-md-12 text-center mt-3">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        App.initFormView();
        $(document).ready(function() {
            $('.select2').select2();
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
                            window.location.href = App.siteUrl('/admin/banners');
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
