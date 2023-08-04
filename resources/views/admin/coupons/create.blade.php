@extends('admin.template.layout')
@section('header')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop
@section('content')
    <div class="card mb-5">
        <div class="card-body">


            <form method="post" id="admin-form" action="{{ url('admin/coupons') }}" enctype="multipart/form-data"
                data-parsley-validate="true">
                <div class="row">
                    <input type="hidden" name="id"
                        value="{{ empty($datamain->coupon_id) ? '' : $datamain->coupon_id }}">
                    @csrf()
                    <div class="col-md-6 form-group">
                        <label>Coupon Code<b class="text-danger">*</b></label>
                        <input type="text" name="coupone_code" class="form-control" required
                            data-parsley-required-message="Enter Coupon Code"
                            value="{{ empty($datamain->coupon_code) ? '' : $datamain->coupon_code }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Coupon Amount<b class="text-danger">*</b></label>
                        <input type="text" name="coupone_amount" class="form-control" required
                            data-parsley-required-message="Enter Coupon Amount" maxlength="5"
                            value="{{ empty($datamain->coupon_amount) ? '' : $datamain->coupon_amount }}"
                            data-parsley-type="number">
                    </div>
                    <div class="col-md-2 form-group">
                        <label>Type<b class="text-danger">*</b></label>
                        <select name="amount_type" class="form-control" required
                            data-parsley-required-message="Select Coupon Type">
                            @foreach ($amounttype as $data)
                                <option value="{{ $data->id }}"
                                    @if (!empty($datamain->amount_type)) {{ $datamain->amount_type == $data->id ? 'selected' : null }} @endif>
                                    {{ $data->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Applied To</label>
                        <select name="applies_to" class="form-control" id="applies_to">
                            <option value="1" selected="" data-show="#browse_category"
                                @if (!empty($datamain->applied_to)) {{ $datamain->applied_to == 1 ? 'selected' : null }} @endif>
                                Category</option>
                            <option value="2" data-show="#browse_product"
                                @if (!empty($datamain->applied_to)) {{ $datamain->applied_to == 2 ? 'selected' : null }} @endif>
                                Product</option>
                        </select>
                    </div>

                    <div class="col-md-6 form-group applies_to_select" id="browse_category">
                        <label>Category</label>
                        <select class="form-control jqv-input product_catd select2" data-jqv-required="true"
                            name="category_ids[]" data-role="select2" data-placeholder="Select Categories"
                            data-allow-clear="true" multiple="multiple">

                            @foreach ($categories as $key => $val)
                                <option value="<?php echo $val->id; ?>" <?php echo in_array($val->id, $category_ids) ? 'selected' : ''; ?>>
                                    <?php echo str_repeat('&nbsp;', 4) . $val->name; ?>
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Title<b class="text-danger">*</b></label>
                        <input type="text" name="title" class="form-control" required
                            data-parsley-required-message="Enter Title"
                            value="{{ empty($datamain->coupon_title) ? '' : $datamain->coupon_title }}">
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Description<b class="text-danger">*</b></label>
                        <input type="text" name="description" class="form-control" required
                            data-parsley-required-message="Enter Description"
                            value="{{ empty($datamain->coupon_description) ? '' : $datamain->coupon_description }}">
                    </div>

                    <div class="col-md-6 form-group">

                        <label>Start date <span style="color:red;">*<span></span></span></label>
                        <input type="text" class="form-control flatpickr-input" data-date-format="yyyy-mm-dd"
                            name="startdate"
                            value="{{ empty($datamain->start_date) ? '' : date('Y-m-d', strtotime($datamain->start_date)) }}"
                            required data-parsley-required-message="Select Start date">

                    </div>


                    <div class="col-md-6 form-group">

                        <label>Expiry date <span style="color:red;">*<span></span></span></label>
                        <input type="text" class="form-control flatpickr-input" data-date-format="yyyy-mm-dd"
                            name="expirydate"
                            value="{{ empty($datamain->coupon_end_date) ? '' : date('Y-m-d', strtotime($datamain->coupon_end_date)) }}"
                            required data-parsley-required-message="Select Expiry date">

                    </div>


                    <div class="col-md-6 form-group">
                        <label>Minimum Amount</label>
                        <input type="text" name="minimum_amount" class="form-control" maxlength="5"
                            value="{{ empty($datamain->minimum_amount) ? '' : $datamain->minimum_amount }}" data-parsley-type="number">
                    </div>

                    {{-- <div class="col-md-6 form-group">
                        <label>Usage limit per Coupon</label>
                        <input type="number" name="coupon_usage_percoupon" class="form-control" maxlength="5" value="{{empty($datamain->coupon_usage_percoupon) ? '': $datamain->coupon_usage_percoupon}}">
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Usage limit per User</label>
                        <input type="number" name="coupon_usage_peruser" class="form-control" maxlength="5" value="{{empty($datamain->coupon_usage_peruser) ? '': $datamain->coupon_usage_peruser}}">
                    </div> --}}

                    <div class="col-md-6 form-group">
                        <label>Status</label>
                        <select name="active" class="form-control">
                            <option
                                @if (!empty($datamain->coupon_status)) {{ $datamain->coupon_status == 1 ? 'selected' : null }} @endif
                                value="1">Active</option>
                            <option
                                @if (!empty($datamain->coupon_status)) {{ $datamain->coupon_status == 0 ? 'selected' : null }} @endif
                                value="0">Inactive</option>
                        </select>
                    </div>



                </div>
                <div class="row">


                    <div class="col-md-6 form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>

            <div class="col-xs-12 col-sm-6">

            </div>
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
                            window.location.href = App.siteUrl('/admin/coupons');
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
        $(".datepicker").datepicker({
            minDate: 0
        });
        $(document).delegate("#applies_to", "change", function() {
            $(".applies_to_select").css("display", "none");
            var show = $('option:selected', this).attr('data-show');
            $(show).css("display", "block");
        });

        $('#applies_to').trigger('change');
    </script>
@stop
