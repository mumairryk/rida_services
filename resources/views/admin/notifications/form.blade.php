@extends('admin.template.layout')
@section('header')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop
@section('content')

    <div class="card mb-5">
        <div class="card-body">

            <div class="row layout-spacing ">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissable custom-danger-box" style="margin: 15px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong> {{ session('error') }} </strong>
                    </div>
                @endif

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="">

                        <div class="">
                            <form method="post" action="{{ route('admin.notifications.save') }}" id="admin-form"
                                enctype="multipart/form-data" data-parsley-validate="true">
                                @csrf
                                <div class="row  d-flex justify-content-between align-items-center">
                                    {{-- <div class="col-md-6 form-group">
                                <label>Vendor<b class="text-danger">*</b></label>
                                <select class="form-control jqv-input select2" name="seller_id" required
                                    data-parsley-required-message="Select a vendor" data-role="vendor-change" data-input-store="store-id">
                                    <option value="">Select Vendor</option>
                                    @foreach ($sellers as $sel)
                                        <option value="{{$sel->id }}">{{ $sel->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Store </label>
                                <select class="form-control select2" name="store_id" required
                                data-parsley-required-message="Select a Store" id="store-id">
                                <option value="">Select Store</option>
                               
                                
                                </select>
                            </div> --}}
                                    <div class="col-lg-6 form-group">
                                        <label>Title *</label>
                                        <input type="text" name="title" class="form-control" value=""
                                            placeholder="Title" required data-parsley-required-message="Enter title">
                                    </div>

                                    <div class="col-lg-6 form-group">
                                        <label>Image</label>
                                        <input type="file" class="form-control " name="image" id="product-image"
                                            accept=".jpg, .jpeg, .png">

                                    </div>

                                    <div class="col-md-12 form-group">
                                        <label>Description *</label>
                                        <textarea type="text" name="description" class="form-control " cols="30" rows="6" value=""
                                            placeholder="Description" required data-parsley-required-message="Enter description"></textarea>
                                    </div>



                                    <div class="col-md-12 mt-3">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".select2").select2();
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
                        if (typeof res['errors'] !== 'undefined' && res['errors'].length > 0) {
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
                            window.location.href = App.siteUrl('/admin/notifications');
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

@endsection
