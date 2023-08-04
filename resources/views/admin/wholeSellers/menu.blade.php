@extends('admin.template.layout')

@section('content')
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

            .parsley-errors-list > .parsley-pattern {
                margin-top: 10px;
            }

            .form-group.d-flex.align-items-center > div {
                flex: 1;
            }

            * {
                padding: 0;
                margin: 0
            }


            .card-item {
                background-color: #f5f5f5;
                border-radius: 20px;
                position: relative
            }

            .card-item .offer {
                width: 150px;
                height: 40px;
                background-color: red;
                position: absolute;
                top: 28px;
                left: -14px;
                border-radius: 20px;
                border-bottom-left-radius: 0px;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 15px;
                font-weight: 700;
                color: #fff
            }

            .card-item span:before {
                position: absolute;
                content: '';
                background-color: red;
                height: 30px;
                width: 17px;
                top: 20px;
                left: 0px;
                border-radius: 10px;
                z-index: -10
            }

            .card-item span:after {
                position: absolute;
                content: '';
                background-color: #d70303;
                height: 30px;
                width: 17px;
                top: 30px;
                left: 0px;
                border-top-left-radius: 10px;
                border-bottom-left-radius: 20px;
                z-index: -10
            }

            .card-item .item-image {
                width: 100%;
                height: 200px;
                overflow: hidden;
                border-top-left-radius: 20px;
                border-top-right-radius: 20px
            }

            .card-item .item-image img {
                width: 100%;
                height: 100%;
                object-fit: cover
            }

            .menu-item-content {
                padding: 10px
            }

            .menu-item-content h3 {
                font-size: 20px
            }

            .menu-item-content p {
                font-size: 15px;
                font-weight: 500
            }

            .menu-item-price {
                margin-top: 10px;
                display: flex;
                justify-content: space-between
            }
        </style>
        <div class="">
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">{{$user->name}}</h5>
                    <div class="row">
                        <div class="col-lg-12">
                            <h6 class="card-subtitle mb-2 text-muted">About</h6>
                            <form method="post" id="admin-form" action="{{ url('admin/menu/update') }}">
                                @csrf()
                                <input type="hidden" name="user_id" value="{{$user->id}}">
                                <textarea class="form-control" name="about" id="about" rows="3"
                                          placeholder="About">@if(isset($menu->about)){{$menu->about}}@endif</textarea>
                                <div class="text-right">
                                <button type="submit" class="btn btn-primary mt-2" id="aboutBtn">Update</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title text-right">
                        <a class="btn btn-primary pull-right" href="{{route('admin.menu.add-item',$user->id)}}">Add
                            Menu</a>
                    </h5>
                    <div class="row">
                        @forelse($menu->items ?? [] as $item)
                            <div class="col-md-3 col-sm-6">
                                <a href="{{route('admin.menu.edit-item',$item->id)}}">
                                    <div class="card-item"><span class="offer">{{$item->itemType->title}}</span>
                                        <div class="item-image"><img src="{{asset($item->image)}}"></div>
                                        <div class="menu-item-content"><h3>{{$item->title}}</h3>
                                            <p>@if(strlen($item->description) > 30)
                                                    {{ substr($item->description,0,27)}}...
                                                @else
                                                    {{$item->description}}
                                                @endif
                                            </p>
                                            <div class="item-price"><span>Price: {{$item->price}} SAR</span> <span> Quantity: {{$item->quantity}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="text-center mt-3">
                                <h5>No Items Found</h5>
                            </div>
                        @endforelse


                    </div>
                </div>
            </div>
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