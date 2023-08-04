@extends("admin.template.layout")

@section('header')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
<style>
    .progress {
        position: relative;
        width: 100%;
    }

    .bar {
        background-color: #00ff00;
        width: 0%;
        height: 20px;
    }

    .percent {
        position: absolute;
        display: inline-block;
        left: 50%;
        color: #040608;
    }
</style>
@stop


@section('content')
<div class="card mb-5">
    @if($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
    @endif
    @if($message = Session::get('error'))
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
    @endif
    @if(check_permission('food_products','Create') || check_permission('food_products','ImportExport') )
    <div class="card-header">
        <form action="{{route('admin.product.export')}}" method="get" class="justify-content-end">
            <div class="status d-flex justify-content-end ">
                @if(check_permission('food_products','Create'))
                @if($store_id != null)
                <a href="{{ url('admin/food_product/create?store_id='.$store_id) }}" class="btn-custom btn mr-2 mt-2 mb-2"><i
                        class="fa-solid fa-plus"></i> Create Product</a>
                @else
                <a href="{{ url('admin/food_product/create') }}" class="btn-custom btn mr-2 mt-2 mb-2"><i
                        class="fa-solid fa-plus"></i> Create Product</a>
                @endif

                @endif
                @if(check_permission('food_products','ImportExport'))
                <a href="{{ url('admin/import_export') }}" class="btn-custom btn mr-2 mt-2 mb-2"><i
                        class="fa-solid fa-download"></i> Import</a>
                @endif
                <!--  <a href="javascript:void(0);" class="btn btn-info mr-2 btn-rounded"  data-toggle="modal" data-target="#exampleModal" > Import</a> -->
                <input type="hidden" name="from" value="{{$from}}">
                <!-- <button type="submit" class="btn btn-danger mr-2 btn-rounded">Export</button>  -->
            </div>
        </form>
    </div>

    @endif
    <style>
        .form-control {
            height: 38px;
        }

        .btn-secondary:hover,
        .btn-secondary:focus {
            color: #fff !important;
            background-color: #714cbd;
            box-shadow: none;
            border-color: #714cbd;
        }
    </style>

    <div class="card-body">
        <div class="">
            <div class="dataTables_wrapper container-fluid dt-bootstrap4 table-responsive">
                @include('admin/food_product/search_form')

                <div class="row mt-1">
                    <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length" id="column-filter_length">
                        </div>
                    </div>


                </div>
                <table class="table table-condensed table-striped ">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="20%">Product Name</th>
                            {{-- <th width="15%">Seller</th> --}}
                            <th width="15%">Store</th>
                            <th width="15%">Is Active</th>
                            <th width="15%">Created Date</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($list->total() > 0)
                        <?php $i = 0; ?>
                        @foreach ($list as $item)
                        <?php $i++; ?>
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->store->name ?? '-' }}</td>
                            {{-- <td>{{$item->shared_product ==1 ? 'Shared with outlets' :'Not Shared'}}</td> --}}
                            <td>
                                <label class="switch s-icons s-outline  s-outline-warning  mb-2 mt-2 mr-2">
                                    <input type="checkbox" class="change_status" data-id="{{$item->id}}"
                                        data-url="{{ url('admin/food_products/change_status') }}" @if ($item->product_status)
                                    checked @endif>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>{{web_date_in_timezone($item->created_at,'d-M-Y h:i A')}}</td>
                            <td class="text-center">
                                <div class="dropdown custom-dropdown">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <i class="flaticon-dot-three"></i>
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink7">
                                        @if(check_permission('food_products','Edit'))
                                        <a class="dropdown-item" href="{{ url('admin/food_products/edit/' . $item->id) }}"><i
                                                class="flaticon-pencil-1"></i> Edit</a>
                                        @endif
                                        @if(check_permission('food_products','Delete'))
                                        <a class="dropdown-item" data-role="unlink"
                                            data-message="Do you want to remove this product?"
                                            href="{{ url('admin/food_products/delete/' . $item->id) }}"><i
                                                class="flaticon-delete-1"></i> Delete</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        @else
                        <tr>
                            <td colspan="7" align="center">No products found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>


                <div class="col-sm-12 col-md-12 pull-right">
                    <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                        {!! $list->appends(['store_id' => request()->store_id])->links('admin.template.pagination') !!}
                    </div>
                </div>




            </div>
        </div>
    </div>
</div>
@stop
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
<script>
    var bar = $('.bar');
        var percent = $('.percent');
        $('#fileUploadForm1').ajaxForm({
            beforeSend: function() {
                var percentVal = '0%';
                bar.width(percentVal)
                percent.html(percentVal);
            },
            uploadProgress: function(event, position, total, percentComplete) {
                var percentValue = percentComplete + '%';
                $(".bar").animate({
                        width: '' + percentValue + ''
                    }, {
                        duration: 5000,
                        easing: "linear",
                        step: function (x) {
                        percentText = Math.round(x * 100 / percentComplete);
                            $(".percent").text(percentText + "%");
                        
                        }
                    });
                var percentVal = percentComplete + '%';
                bar.width(percentVal)
                percent.html(percentVal);
            },
            success: function(response) {
                bar.css('background-color','#00ff00');
                if (response.success == 0) {
                     App.alert([response.message, 'error']);
                    bar.css('background-color','red');
                    bar.width('100%');
                    percent.html('');
                  //  window.location.reload();
                } else {
                     App.alert([response.message, 'success']);
                }
                bar.width('0%');
                percent.html('');
            },
            complete: function(xhr) {
                $('#fileUploadForm1').trigger('reset');
                
            },error: function(xhr, status, error) {
                bar.width('0%')
                percent.html('error occured, try again');
}
        });
        $('#fileUploadForm2').ajaxForm({
            beforeSend: function() {
                $('.percent').html('0%');
                var percentVal = '0%';
                bar.width(percentVal)
                percent.html(percentVal);
            },
            uploadProgress: function(event, position, total, percentComplete) {
                var percentValue = percentComplete + '%';
                $(".bar").animate({
                        width: '' + percentValue + ''
                    }, {
                        duration: 5000,
                        easing: "linear",
                        step: function (x) {
                        percentText = Math.round(x * 100 / percentComplete);
                            $(".percent").text(percentText + "%");
                        
                        }
                    });
                var percentVal = percentComplete + '%';
                bar.width(percentVal)
                percent.html(percentVal);
            },
            success: function(response) {
                bar.css('background-color','#00ff00');
                if (response.success == 0) {
                    App.alert([response.message, 'error']);
                    bar.css('background-color','red');
                    bar.width('100%');
                    percent.html('');
                    
                } else {
                     App.alert([response.message, 'success']);
                   
                    
                }
                bar.width('0%');
                percent.html('');
                  $('#fileUploadForm2').trigger('reset');
                  setTimeout("window.location=''",3000);
            },
            error: function(xhr, status, error) {
                bar.width('0%')
                percent.html('error occured, try again');
}
        });
</script>
@stop