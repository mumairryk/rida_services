@extends("admin.template.layout")

@section('header')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
@stop


@section('content')

<style>
    .text-muted {
        color: #181722 !important;
        font-size: 12px;
    }

    .uploaded-prev-imd {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        align-items: center;
        margin: 10px 0px;
    }

    .del-product-img {
        margin-left: 5px;
        color: #007bff;
        font-size: 14px;
        font-weight: 600;
    }

    .del-product-img:hover {
        color: #ff3743;
    }

    .select2-container .select2-selection--multiple {
        min-height: 44px;
    }

    #product-single-variant legend {
        font-size: 15px;
        color: #000;
        font-weight: 600;
        margin-bottom: 5px;
    }

    #product-single-variant hr {
        display: none;
    }

    .select-category-form-group .parsley-required {
        position: absolute;
        bottom: -20px
    }

    .default_attribute_id {
        width: auto;
        margin-right: 5px;
    }
</style>
<div class="mb-5">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="">
        <form id="admin-form" method="post" action="{{ route('admin.food_product.add_product') }}"
            enctype="multipart/form-data" data-parsley-validate="true">
            <input type="hidden" name="id" value="{{ $id }}">
            @csrf

            <div class="card mb-2">
                <div class="card-body">

                    <div class="row">

                        {{-- <div class="col-lg-4 col-md-6 col-12">
                            <div class="form-group">
                                <label>Vendor<b class="text-danger">*</b></label>
                                <select class="form-control jqv-input select2" name="vendor_id" id="vendor_id"
                                    data-parsley-required="true" data-parsley-required-message="Select Vendor">
                                    <option value="">Select vendor</option>
                                    @forelse ($sellers as $item)
                                    <option {{ $product->vendor_id == $item->id ? 'selected' : '' }}
                        value="{{ $item->id }}">{{ $item->name }}</option>
                        @empty
                        <option disabled>No vendors found</option>
                        @endforelse
                        </select>
                    </div>
                </div> --}}

                {{-- <div class="col-lg-4 col-md-6 col-12">
                    <div class="form-group">
                        <label>Share item to all outlets<b class="text-danger">*</b></label>
                        <select class="form-control jqv-input select2" name="shared_product" id="shared_product"
                            data-parsley-required="true" data-parsley-required-message="Please select an option">
                            <option {{ $product->shared_product == 0 ? 'selected' : '' }} value="0">No</option>
                            <option {{ $product->shared_product == 1 ? 'selected' : '' }} value="1">Yes</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12" id="editable-div" style="display: none">
                    <div class="form-group">
                        <label>Allow edit option for outlets?<b class="text-danger">*</b></label>
                        <select class="form-control jqv-input select2" name="is_editable_by_outlets"
                            id="is_editable_by_outlets"
                            required data-parsley-required-message="Please select an option"
                            >
                            <option {{ $product->is_editable_by_outlets == 0 ? 'selected' : '' }} value="0">No</option>
                            <option {{ $product->is_editable_by_outlets == 1 ? 'selected' : '' }} value="1">Yes</option>
                        </select>
                        <small class="text-danger">If allowed, outlets can have different prices.</small>
                    </div>
                </div> --}}

                <div class="col-lg-4 col-md-6 col-12" id="store-div">
                    <div class="form-group">
                        <label>Stores<b class="text-danger">*</b></label>
                        <select class="form-control jqv-input select2" name="store_id" id="store_id"
                        data-parsley-required="true" data-parsley-required-message="Select a Store">
                            <option value="">Select Stores</option>
                            @foreach ($stores as $sel)
                            <option value="{{$sel->id }}" {{ ($sel->id == ($product->store_id)) ? 'selected' : '' }}>
                                {{ $sel->store_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12">
                    <div class="form-group">
                        <label>Product Name<b class="text-danger">*</b></label>
                        <input type="text" name="product_name" class="form-control jqv-input"
                            data-parsley-required="true" data-parsley-required-message="Enter Product Name"
                            value="{{ $product->product_name }}">
                    </div>
                </div>







                <div class="col-lg-4 col-md-6 col-12">
                    <div class="form-group">
                        <label>Category<b class="text-danger">*</b></label>
                        <select class="form-control jqv-input product_catd select2" data-jqv-required="true"
                            name="category_ids[]" data-role="select2" data-placeholder="Select Categories"
                            data-allow-clear="true" multiple="multiple" required
                            data-parsley-required-message="Select Category">
                            @foreach($categories as $key => $val)
                            @if ($val->sub->count() > 0)
                            <optgroup label="{{  $val->name }}">
                                @foreach($val->sub as $sub)
                                <option data-style="background-color: #ff0000;" value="{{ $sub->id }}"
                                    {{ in_array($sub->id, $category_ids) ? 'selected' : '' }}>
                                    {!! str_repeat('&nbsp;', 4) !!} {{ $sub->name }}
                                </option>
                                @endforeach
                            </optgroup>
                            @else
                            <option  value="{{ $val->id }}"
                                {{ in_array($val->id, $category_ids) ? 'selected' : '' }}>
                                {{ $val->name }}
                            </option>                          
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12">
                    <div class="form-group">
                        <label>Menu<b class="text-danger">*</b></label>
                        <select class="form-control jqv-input product_catd select2" data-jqv-required="true"
                            name="menu_ids[]" data-role="select2" data-placeholder="Select Menu(s)"
                            data-allow-clear="true" multiple="multiple" required
                            data-parsley-required-message="Select Menu(s)">
                            <option value="">Select menu </option>
                            <option {{ in_array('1',$menu_ids) ? 'selected' : '' }} value="1">Menu 1</option>
                            <option {{ in_array('2',$menu_ids) ? 'selected' : '' }} value="2">Menu 2</option>
                            <option {{ in_array('3',$menu_ids) ? 'selected' : '' }} value="3">Menu 3</option>
                            <option {{ in_array('4',$menu_ids) ? 'selected' : '' }} value="4">Menu 4</option>
                            <option {{ in_array('5',$menu_ids) ? 'selected' : '' }} value="5">Menu 5</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12">
                    <div class="form-group">
                        <label>Regular Price<b class="text-danger">*</b></label>
                        <input type="number" step="0.01" name="regular_price"
                            class="form-control jqv-input select-on-focus" data-parsley-required="true"
                            data-parsley-required-message="Enter Price" value="{{ $product->regular_price ?? '0.00'}}"
                            data-parsley-number="true" data-parsley-min="1">
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12">
                    <div class="form-group">
                        <label>Sale Price<b class="text-danger">*</b></label>
                        <input type="number" step="0.01" name="sale_price"
                            class="form-control jqv-input select-on-focus" data-parsley-required="true"
                            data-parsley-required-message="Enter Price" value="{{ $product->sale_price ?? '0.00'}}"
                            data-parsley-number="true" data-parsley-min="1">
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="form-group">
                        <label>Pieces (if any)</label>
                        <input type="number" name="pieces"
                            class="form-control jqv-input select-on-focus" data-parsley-required="false"
                            value="{{ $product->pieces ?? '0'}}"
                            data-parsley-number="true" data-parsley-min="0">
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12">
                    <div class="form-group">
                        <label>Veg / Non-Veg<b class="text-danger">*</b></label>
                        <select class="form-control jqv-input select2" name="is_veg" id="is_veg"
                            data-parsley-required="true" data-parsley-required-message="Please select an option">
                            <option value="0">Non-Veg</option>
                            <option {{ $product->is_veg == 1 ? 'selected' : '' }} value="1">Veg</option>
                            <option {{ $product->is_veg == 2 ? 'selected' : '' }} value="2">Egg</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12">
                    <div class="form-group">
                        <label>Promotion</label>
                        <select class="form-control jqv-input select2" name="promotion" id="promotion"
                            data-parsley-required="false">
                            <option value="">Select an option</option>
                            <option {{ $product->promotion == 1 ? 'selected' : '' }} value="1">Buy 1 Get 1</option>
                            <option {{ $product->promotion == 2 ? 'selected' : '' }} value="2">Buy 2 Get 1</option>
                        </select>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group upload-product-img">
                        <label for="" class="">Upload Images (Maximum 5 images)</label>
                        <div id="product-simple-images" class="upload-img-product-items">
                            <?php if (! empty($product_images) ): ?>
                            <?php foreach ($product_images as $t_name): ?>
                            <?php
                                             if ( !empty($t_name) && file_exists(config('global.upload_path') . "/" . config('global.product_image_upload_dir'). "/{$t_name}") )
                                             {
                                                $t_img = url(config('global.upload_path') . "/" . config('global.product_image_upload_dir')."/".$t_name) ;
            
                                            } else {
                                                $t_img = url('assets_v2/images/placeholder.png');
                                            }
                                        ?>
                            <div class="uploaded-prev-imd">
                                <img src="<?php echo $t_img ?>" alt="" />
                                <div class="del-product-img" data-role="product-img-trash"
                                    data-image-file="<?php echo $t_name ?>"
                                    <?php echo ($readonly ? 'data-disabled="1"' : '') ?>><i
                                        class="far fa-trash-alt"></i> Delete
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>

                            @if(!empty($product->product_images))
                            <?php  
                                    $imageList = $product->product_images; ?>
                            @if(!empty($imageList))
                            @foreach ($imageList as $key => $value)
                            <img src="{{url(config('global.upload_path') . '/' . config('global.product_image_upload_dir').$value)}}"
                                width="100" height="100">
                            <div class="del-product-img" data-role="product-img-trash" data-image-file="{{$value}}"
                                <?php echo ($readonly ? 'data-disabled="1"' : '') ?>><i class="far fa-trash-alt"></i>
                                Delete
                            </div>
                            @endforeach
                            @endif
                            @endif

                            <div class="uploaded-prev-imd" <?php echo ($readonly ? 'style="display:none;"' : '') ?>>

                                <div class="image_wrap">
                                    <label class="Pic_upload">
                                        <input counter="0" type="file" name="product_images[]" class="upload_pro"
                                            data-role="product-img-upload" multiple />
                                        <i class="ti-plus"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <small class="text-info">
                            Maximum size should be 2MB. Maximum dimension allowed is 1024 x 1024.<br>
                            Allowed types are jpg,
                            jpeg, png and gif.
                        </small>
                    </div>
                    <input type="hidden" name="image_counter" value="0" id="image_counter">
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label>Description<b class="text-danger">*</b></label>
                        <textarea name="description" class="form-control jqv-input select-on-focus"
                            data-parsley-required="true" data-parsley-required-message="Product Description"
                            rows="8">{{ $product->description }}</textarea>
                    </div>
                </div>

            </div>

            <div id="combo-row-container" class="col-12 p-0">

            </div>

            <div class="col-12 ">
                <button type="button" class="btn btn-primary" data-role="add-combo-row">Add Row</button>
            </div>
            <div class="col-md-12 text-center mt-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
    </div>
    </form>
</div>
</div>

<div class="modal fade" id="modal-add-combo-attribute">
    <div class="modal-dialog modal-md">
        <form class="add-heading-form" method="post" action="{{ route('admin.food_product.heading.store') }}" data-select="select2-heading">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Heading</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                </div>
                <div class="modal-body modal-tab-container">
                    <input type="text" class="form-control jqv-input" name="heading_name" value=""
                        placeholder="Enter heading..." />
                </div>
                <div class="modal-footer">
                    <button type="submit" id="submit-btn" class="btn btn-primary save-btn">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal-add-item-attribute">
    <div class="modal-dialog modal-md">
        <form class="add-heading-form" method="post" action="{{ route('admin.food_product.items.store') }}" data-select="select2-items">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                </div>
                <div class="modal-body modal-tab-container">
                    <input type="text" class="form-control jqv-input" name="item_name" value=""
                        placeholder="Enter item name..." />
                </div>
                <div class="modal-footer">
                    <button type="submit" id="submit-btn" class="btn btn-primary save-btn">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

<div class="modal fade" id="crop_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Crop Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8">
                            <img id="image_crop_section" src="">
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="crop">Crop</button>
            </div>
        </div>
    </div>
</div>



@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    App.initFormView();

    let itemsArr = [];

    @if ($mode == 'edit' && $product->foodProductCombo->count() > 0)
    let parentCounter = parseInt('{{ $product->foodProductCombo->count() - 1 ?? "0" }}') || 0;
    const itemsCounter = JSON.parse("{{ $itemsCounter }}")

    for (let Indx = 0; Indx <= parentCounter; Indx++) {
        itemsArr[Indx] = itemsCounter[Indx] > 0 ? itemsCounter[Indx] - 1 : 0;
    }
    @else
    let parentCounter = 0;
    itemsArr[0] = 0;
    @endif
    

        $(document).ready(function() {
            $('.select2').select2();

            if ("{{ $mode }}" === "edit" && (parseInt('{{ $product->foodProductCombo->count() ?? "0" }}')) > 0) {
                $.ajax({
                    url: '{{ route('admin.food_product.combo.row') }}?counter='+parentCounter+'&product_id={{ $product->id }}',
                    success: function(data){
                        $('#combo-row-container').append(data);
                        $('#combo-row-container .is_required').trigger('change');

                    }
                })
            }

            $('body').on('click','[data-role="add-combo-row"]',function(){
                $.ajax({
                    url: '{{ route('admin.food_product.combo.row') }}?counter='+parentCounter++,
                    success: function(data){
                        $('#combo-row-container').append(data);
                    }
                })

                itemsArr[parentCounter - 1] = 0;
            })
            
            $('body').on('click','[data-role="add_item_row"]',function(){
                itemsArr[$(this).data('parent-counter')]++;
                const tbody = $(this).parent().parent().find('tbody');
                $.ajax({
                    url: '{{ route('admin.food_product.item.row') }}?counter='+$(this).data('parent-counter')+'&item='+itemsArr[$(this).data('parent-counter')],
                    success: function(data){
                       tbody.append(data);
                    }
                })
            })

            $('body').on('click','[data-role="delete-combo-block"]',function(){
                $(this).closest('.jumbotron').remove();
                parentCounter--;
            });

            $('body').on('change','.is_required', function(){
            
                const combo_quantity = $(this).closest('.jumbotron').find('.combo_quantity');
            
                if($(this).val()==1){
                    combo_quantity.show()
                }else{
                    combo_quantity.hide()
                }
            });

            $('.add-heading-form').on('submit',function(e){
                e.preventDefault();
                var form = $(this);

                const submit_btn = form.find('#submit-btn');
                const modal = form.closest('.modal');
                const select = $('[data-role="'+form.data('select')+'"]');
                const data = form.serialize()

                submit_btn.attr('disabled',true);
                submit_btn.html('Saving <i class="fa fa-spinner fa-spin"></i>');


                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        modal.find('.invalid-feedback').remove();
                        modal.find('.is-invalid').removeClass('is-invalid');

                        if (response.status == 1) {
                            modal.modal('hide');
                            modal.find('input').val('')
                            var html = '<option value="' + response.data.id + '">' + response.data.name +
                                '</option>';
                            select.append(html);
                        }else if (response.status == 0 && typeof response.errors !== 'undefined') {
                            jQuery.each(response.errors, function(e_field, e_message) {
                                if (e_message != '') {
                                    $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '"]').eq(0));
                                }
                            });
                        } else{
                            modal.modal('hide');
                            App.alert(response.message, 'Oops!');
                        }

                        submit_btn.attr('disabled',false);
                        submit_btn.html('Save');
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        submit_btn.attr('disabled',false);
                        submit_btn.html('Save');
                    }
                });
            });
        });

        var form_uploaded_images = {};
        
        $('body').off('submit', '#admin-form');
        $('body').on('submit', '#admin-form', function(e) {
            e.preventDefault();
            var $form = $(this);
            var formData = new FormData(this);
            var i = 0;
            $(".invalid-feedback").remove();
            
            $.each(form_uploaded_images, function (k, v) { 
                formData.delete('product_images[]');
                i = 0;
                $.each(v, function (k1, v1) {
                    formData.append('product_image_'+i, v1);
                    i++;
                });
            });
            
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
                                    let eq = 0;
                                    if (e_field.indexOf('.') !== -1) {
                                        var parts = e_field.split('.');
                                        eq = parts[1];
                                        e_field = parts[0] +'['+eq+']';
                                    }
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
                                if(error.offset()){
                                    $('html, body').animate({
                                    scrollTop: (error.offset().top - 100),
                                }, 500);
                                }
                            });
                        } else {
                            var m = res['message'];
                            App.alert(m, 'Oops!');
                        }
                    } else {
                        App.alert(res['message']);
                        setTimeout(function() {
                            window.location.href = App.siteUrl('/admin/food_products');
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
        $(".product_cat").change(function(){
            $(".slrs").attr('disabled','');
            _cat = $(this).val();
            html = '<option value="">Select Seller</option>';
            $(".slrs").html(html);
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: $(this).data('url'),
                data: {
                    "id" :$(this).data('id'),
                    'cat': _cat,
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(res) {
                    for (var i=0; i < res['data'].length; i++) {
                        html += '<option value="'+ res['data'][i]['id'] +'">'+ res['data'][i]['business_name'] +'</option>';
                    }
                    $(".slrs").html(html);
                    $(".slrs").removeAttr('disabled');
                    $(".slrs").change();
                },
                error: function(e) {
                    App.alert(e.responseText, 'Oops!');
                }
            });
        })

        

  var $modal = $('#crop_modal');
      var image = document.getElementById('image_crop_section');
      var cropper;
      $("body").on("change", ".crop_image", function (e) {
         var files = e.target.files;

            var  fileType = files[0]['type'];
            var validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
            if (!validImageTypes.includes(fileType)) {
                return false;
            }

         var done = function (url) {
            image.src = url;
            $modal.modal('show');
         };
         var reader;
         var file;
         var url;
         if (files && files.length > 0) {
            file = files[0];


            if (URL) {
               done(URL.createObjectURL(file));
            } else if (FileReader) {
               reader = new FileReader();
               reader.onload = function (e) {
                  done(reader.result);
               };
               reader.readAsDataURL(file);
            }
         }
      });
      $modal.on('shown.bs.modal', function () {
        // var finalCropWidth = 320;
        // var finalCropHeight = 200;
        // var finalAspectRatio = finalCropWidth / finalCropHeight;
        //  cropper = new Cropper(image, {
        //     // aspectRatio: finalAspectRatio,
        //     aspectRatio: 1,
        //     viewMode: 3,
        //     preview: '.crop_image_preview_section',
        //  });


        // $('#crop_image').cropper('destroy')
  cropper = new Cropper(image, {
    aspectRatio: 1,
    autoCropArea: 0.7,
    viewMode: 1,
    center: true,
    dragMode: 'move',
    movable: true,
    scalable: true,
    guides: true,
    zoomOnWheel: true,
    cropBoxMovable: true,
    wheelZoomRatio: 0.1,
    ready: function () {
      //Should set crop box data first here
      cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
    }
  })


      }).on('hidden.bs.modal', function () {
         cropper.destroy();
         cropper = null;
      });
      $("#crop").click(function () {
         canvas = cropper.getCroppedCanvas({
            // width: 900,
            // height: 'auto',
         });
         canvas.toBlob(function (blob) {
            url = URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function () {
               var base64data = reader.result;
               $("#cropped_upload_image").val(base64data);
               $("#image-preview").attr('src',base64data);
               $modal.modal('hide');
            }
         });
      })


         var config = {
        'list_page_url': 'vendor_products',
        'rateYo': {
            // rating: 4.5,
            readOnly: true,
            starWidth: "20px",
            normalFill: "#e0e0e0",
            ratedFill: "#d2a07a"
        },
        'max_image_uploads': 5,
        'image_allowed_types': [
            'image/gif',
            'image/png',
            'image/jpg',
            'image/jpeg',
            'image/svg+xml'
        ],
    }
    var file_upload_index = 1;
         // Preview of uploaded image
        $('body').off('change', '[data-role="product-img-upload"]');
        $('body').on('change', '[data-role="product-img-upload"]', function (e) {
            e.preventDefault();            
            var _URL = window.URL || window.webkitURL;
            
            var $parent = $(this).closest('div.uploaded-prev-imd');            
            var $imgBox = $('<div class="uploaded-prev-imd"><img /><a href="javascript:void(0)" class="del-product-img" data-role="product-img-trash" data-image-file=""><i class="flaticon-delete"></i> Delete</a></div>');
            var image_key = App.makeSafeName($(this).attr('name'), '-');
            var countval = $(this).attr('counter');
            var counter = $parent.siblings('div.uploaded-prev-imd').length;
            var vval = 0;
            for (var i = 0; i < (this.files).length; i++) {
                if ( counter >= config.max_image_uploads ) {
                    return false;
                }
                counter++;
                (function(file) { 
                    var img = new Image();
                    img.src = _URL.createObjectURL(file);
                    img.onload = function() { 
                    var maxwidth = '<?php echo  config('global.product_image_width')?>';
                    var maxheight = '<?php echo config('global.product_image_height')?>';  
                       
                        if(this.width > maxwidth || this.height > maxheight){
                            App.alert("Maximum Image upload size issue","Opss");

                            return;
                        }else{
                            if( $.inArray(file['type'], config.image_allowed_types) == -1 ) {
                                swal('Oops!', 'Please upload image files (gif, png or jpg)', 'warning');
                                return false;
                            }
                            var reader  = new FileReader();
                            reader.onloadend = function () { 
                                var $clone = $imgBox.clone();
                                $clone.append('<img src="'+reader.result+'" width="100" height="100">');
                                $clone.data('file-uid', file_upload_index);
                                //$clone.find('img').attr('src', reader.result);
                                $clone.insertBefore($parent);
                                if ( $parent.siblings('div.uploaded-prev-imd').length == config.max_image_uploads ) {
                                    $parent.hide();
                                }
                                if ( typeof(form_uploaded_images[image_key]) === 'undefined' ) {
                                    form_uploaded_images[image_key] = {};
                                }
                                vval = $('#image_counter_'+countval).val();
                                vval++;
                                $('#image_counter_'+countval).val(vval);
                                form_uploaded_images[image_key][file_upload_index] = file;
                                $('#image_counter').val(file_upload_index);
                                file_upload_index++;

                            };

                            reader.readAsDataURL(file);
                        }
                    };
                            
                })(this.files[i]);
            }
        });

      

    
    $(document).on('click','.del-product-img',function(){ 
        var image = $(this).attr('data-image-file');
        var $imgList = $(this).closest('div.upload-img-product-items');
        var $target = $(this).closest('div.uploaded-prev-imd');
        var product_id = $('[name="id"]').val();
        if(image!="") { 
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                enctype: 'multipart/form-data',
                url: '{{ url("admin/food_products/removeProductImage")}}',
                data: { 'image': image, 'product_id':product_id },
                dataType: 'json',
                success: function(data) {
                    if ( data['status'] == 0 ) {
                            var msg = data['message']||'Unable to remove attribute. Please try again later.';
                            App.alert([msg, 'warning']);
                        } else {
                            App.alert(['Done! Image removed successfully.']);
                            if(true){
                                location.reload();
                            }
                            $(this).parent().find('.uploaded-prev-imd').remove();
                            $target.remove();
                            
                        }
                }
            }) 
        }else { 
            if(true){
                location.reload();
            }
            $target.remove();
            $(this).parent().find('.uploaded-prev-imd').remove();
        }  
    })

</script>

<script>
    // $(document).ready(function(){
    //     $('#shared_product').on('change',function(){
    //         if($(this).val() == 1){
    //             $('#editable-div').show();
    //             $('#store-div').hide();
    //         }else{
    //             $('#editable-div').hide();
    //             $('#store-div').show();
    //         }
    //     });
    // });
</script>
@stop