@extends('admin.template.layout')

@section('header')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin_assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin_assets/plugins/table/datatable/custom_dt_customer.css">
@stop


@section('content')
    <div class="order-detail-page">
        <div class="dataTables_wrapper container-fluid dt-bootstrap4">
            {{-- <form action="" method="get">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Date From</label>
                            <input type="text" name="from" class="form-control datepicker" autocomplete="off" value="">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Date To</label>
                            <input type="text" name="to" class="form-control datepicker" autocomplete="off" value="">
                        </div> 
                        <div class="col-md-4 form-group">
                            <label>Search Order ID</label>
                            <input type="text" name="search_key" class="form-control" autocomplete="off"
                                value="{{ $search_key }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Customer Name</label>
                            <input type="text" name="search_key" class="form-control" autocomplete="off"
                                value="{{ $search_key }}">
                        </div>
                        <button type="submit" class="btn btn-warning mb-4 mr-2 btn-rounded">Search</button>
                    </div>
                </form> --}}




            <!-- <div class="row mt-3">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length" id="column-filter_length">
                            </div>
                        </div>


                    </div> -->

            <div class="order-totel-details">
                <div class="card">
                    <div class="card-body">
                        <div class="col-sm-12">
                            <?php $ordernumber = config('global.sale_order_prefix') . date(date('Ymd', strtotime($list[0]->created_at))) . $list[0]->order_id; ?>
                            <h4>Order NO: {{ $ordernumber }} </h4>
                            <div class="table-responsive">
                                <table width="100%">
                                    <thead>
                                        <tr>
                                            <th>Order No.</th>
                                            <th>: {{ $ordernumber }}</th>
                                            <th>Customer</th>
                                            <th>: {{ $list[0]->name ?? $list[0]->customer_name }}</th>
                                        </tr>
                                        <tr>
                                            <th>Bill No.</th>
                                            <th>: {{ $list[0]->invoice_id }}</th>
                                            <th>Delivery Address</th>
                                            <th>
                                                @if (!empty($list[0]->shipping_address))
                                                    <?php $shipdata = $list[0]->shipping_address;
                                                    ?>
                                                    Building: {{ $shipdata->building }}, <br>
                                                    Apartment: {{ $shipdata->apartment }},<br>
                                                    Street: {{ $shipdata->street }},<br>
                                                    Location: {{ $shipdata->location }},<br>
                                                    Landmark: {{ $shipdata->land_mark }}<br>
                                                @endif
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Created on.</th>
                                            <th>: {{ web_date_in_timezone($list[0]->created_at, 'd-M-Y h:i A') }} </th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        <tr>

                                            <th>Sale Amount</th>
                                            <th>: {{ number_format($list[0]->total, 2, '.', '') }}</th>
                                            <th>VAT</th>
                                            <th>: {{ number_format($list[0]->vat, 2, '.', '') }}</th>
                                        </tr>
                                        {{--  <tr>
                            <th>VAT</th>
                            <th>: {{number_format($list[0]->vat, 2, '.', '')}}</th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>Coupon Discount</th>
                            <th>: {{number_format('0', 2, '.', '')}}</th>
                            <th></th>
                            <th></th>
                        </tr> --}}

                                        <tr>
                                            <th>Sub Total </th>
                                            <th>: {{ number_format($list[0]->total + $list[0]->vat, 2, '.', '') }}</th>
                                            <th>Discount</th>
                                            <th>: {{ number_format($list[0]->discount, 2, '.', '') }}
                                                @if ($list[0]->coupon_code)
                                                    (Coupon : {{ $list[0]->coupon_code }})
                                                @endif
                                            </th>
                                        </tr>
                                        {{-- <tr>
                            <th>Shipping Charge</th>
                            <th>: {{number_format('0', 2, '.', '')}}</th>
                            <th>Service Charge</th>
                            <th>: {{number_format('0', 2, '.', '')}}</th>
                        </tr> --}}
                                        <tr>
                                            <th>Grand Total</th>
                                            <th>: {{ number_format($list[0]->grand_total, 2, '.', '') }} </th>
                                            <th>Payment Mode</th>
                                            <th>:

                                                @if($list[0]->payment_mode==2)
                                                    Card
                                                @endif  
                                                @if($list[0]->payment_mode==3)
                                                    Apple Pay
                                                @endif 
                                                @if($list[0]->payment_mode==4)
                                                    Google Pay
                                                @endif 

                                            </th>
                                        </tr>
                                    </thead>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="order-totel-details">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <h4>Order Status

                                    @if($list[0]->status == config('global.order_status_pending'))
                                        <!-- <li class="accepted @if($list[0]->status >= config('global.order_status_accepted')) active @endif"> -->
                                            <button @if($list[0]->status < config('global.order_status_accepted')) data-role="status-change" data-st="{{config('global.order_status_accepted')}}" @endif class="btn btn-warning mb-4 ml-2 btn-rounded">Accept</button>
                                        <!-- </li> -->
                                    @endif
                                    @if($list[0]->status == config('global.order_status_accepted'))
                                        <!-- <li class="ready-for-delivery @if($list[0]->status >= config('global.order_status_ready_for_delivery')) active @endif"> -->
                                            <button  @if($list[0]->status < config('global.order_status_ready_for_delivery')) data-role="status-change" data-st="{{config('global.order_status_ready_for_delivery')}}" @endif class="btn btn-warning mb-4 ml-2 btn-rounded">Ready For Delivery</button>
                                        <!-- </li> -->
                                    @endif
                                    @if($list[0]->status == config('global.order_status_ready_for_delivery'))
                                    <!-- <li class="dispatched @if($list[0]->status >= config('global.order_status_dispatched')) active @endif"> -->
                                        <button @if($list[0]->status < config('global.order_status_dispatched')) data-role="status-change" data-st="{{config('global.order_status_dispatched')}}" @endif  class="btn btn-warning mb-4 ml-2 btn-rounded">Dispatch</button>
                                    <!-- </li> -->
                                    @endif
                                    @if($list[0]->status == config('global.order_status_dispatched'))
                                    <!-- <li class="delivered @if($list[0]->status >= config('global.order_status_delivered')) active @endif"> -->
                                        <button @if($list[0]->status < config('global.order_status_delivered')) data-role="status-change" data-st="{{config('global.order_status_delivered')}}" @endif class="btn btn-warning mb-4 ml-2 btn-rounded">Deliver</button>
                                    <!-- </li> -->
                                    @endif

                                </h4>
                                
                            <div class="delivery-status-block">
                                <ul class="list-unstyled ord_list" data-href="{{url('admin/order/change_status')}}" data-detailsid="{{$list[0]->order_id}}">
                                    <li class="pending @if($list[0]->status >= config('global.order_status_pending')) active @endif">
                                        <button @if($list[0]->status != config('global.order_status_pending'))  data-st="{{config('global.order_status_pending')}}" @endif  class="btn-design">Pending</button>
                                    </li>
                                
                                    <li class="accepted @if($list[0]->status >= config('global.order_status_accepted')) active @endif">
                                        <button @if($list[0]->status < config('global.order_status_accepted')) data-role="status-change" data-st="{{config('global.order_status_accepted')}}" @endif class="btn-design">Accepted</button>
                                    </li>
                                    <li class="ready-for-delivery @if($list[0]->status >= config('global.order_status_ready_for_delivery')) active @endif">
                                        <button  @if($list[0]->status < config('global.order_status_ready_for_delivery')) data-role="status-change" data-st="{{config('global.order_status_ready_for_delivery')}}" @endif class="btn-design">Ready For Delivery</button>
                                    </li>
                                    <li class="dispatched @if($list[0]->status >= config('global.order_status_dispatched')) active @endif">
                                        <button @if($list[0]->status < config('global.order_status_dispatched')) data-role="status-change" data-st="{{config('global.order_status_dispatched')}}" @endif  class="btn-design">Dispatched</button>
                                    </li>
                                    <li class="delivered @if($list[0]->status >= config('global.order_status_delivered')) active @endif">
                                        <button @if($list[0]->status < config('global.order_status_delivered')) data-role="status-change" data-st="{{config('global.order_status_delivered')}}" @endif class="btn-design">Delivered</button>
                                    </li>
                                    {{-- <li class="delivered @if($list[0]->status >= config('global.order_status_cancelled')) active @endif">
                                        <button @if($list[0]->status != config('global.order_status_cancelled')) data-role="status-change" data-st="{{config('global.order_status_cancelled')}}" @endif class="btn-design">Cancelled</button>
                                    </li> --}}
                
                                </ul>
                           </div>
    
                        </div>
                           
                        </div>

                        <div class="order-page-infomatics">
                            @if ($show_cancel)
                                {{-- <div class="cancel_btn">
                                    <button class="cancel-selection" data-role="cancel-order"
                                        href="{{ url('admin/order/cancel_order') }}"
                                        order_id="{{ $list[0]->order_id }}">Cancel Order</button>
                                </div> --}}
                            @endif
                            <?php if(sizeof($list[0]->order_products)) { ?>
                            <form>
                                <div class="action-divs d-flex align-items-center">
                                    {{-- <div class="edit-order_btn">
                                <a href="{{ url('admin/order_edit/1')}}" class="edit-btn">edit</a>
                            </div> --}}
                                </div>
                                <div class="product-order-details-div">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="product-headeing-title">
                                                <h4>Products</h4>
                                            </div>
                                        </div>
                                    <?php foreach($list[0]->order_products as $datavalue) { ?>
                                    
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
                                            
                                            <div class="product_details-flex d-flex">
                                                <div class="producT_img">
                                                    @if ($datavalue->prod_image)
                                                        <img src="{{ $datavalue->prod_image }}"
                                                            style="width:100px;height:100px;object-fit:cover;">
                                                    @endif
                                                </div>
                                                <div class="product_content">
                                                    <h4 class="product-name">{{ $datavalue->product_name }}</h4>

                                                    <p><strong>Vendor: </strong> {{ $datavalue->vendor }}</p>
                                                    <p><strong>Quantity: </strong> {{ $datavalue->quantity }}</p>
                                                    <p><strong>Price: </strong> {{ $datavalue->price }}</p>
                                                    <p><strong>Total: </strong> {{ $datavalue->total }}</p>
                                                    <p><strong>Discount: </strong> {{ $datavalue->discount }}</p>
                                                    <p><strong>Grand Total: </strong> {{ $datavalue->grand_total }}</p>
                                                   
                                                    @if ($datavalue->is_returned)
                                                        <h5 class="product-name">Return Details</h5>
                                                        <p><strong>Date: </strong>
                                                            {{ web_date_in_timezone($datavalue->returned_on, 'd M Y') }}</p>
                                                        <p><strong>Reason: </strong> {{ $datavalue->ret_reason }}</p>
                                                        <p><strong>Status: </strong>
                                                            @if (!$datavalue->ret_status)
                                                                Pending
                                                            @endif
                                                            @if ($datavalue->ret_status == 1)
                                                                Returned
                                                            @endif
                                                            @if ($datavalue->ret_status == 2)
                                                                Return Rejected
                                                            @endif
                                                        </p>
                                                        @if ($datavalue->ret_status)
                                                            <p><strong>Status Changed On:
                                                                    {{ web_date_in_timezone($datavalue->ret_status_changed_on, 'd M Y h:i A') }}</strong>
                                                            </p>
                                                        @endif

                                                    @endif
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
                                            <div class="product-headeing-title">
                                                <h4>Delivery Status</h4>
                                            </div>
                                            <div class="delivery-status-block">
                                                <ul class="list-unstyled">
                                                    <li class="pending @if ($datavalue->order_status >= config('global.order_status_pending')) active @endif">
                                                        <button class="btn-design">Pending</button></li>
                                                    @if ($datavalue->order_status != config('global.order_status_cancelled'))
                                                        <li
                                                            class="accepted @if ($datavalue->order_status >= config('global.order_status_accepted')) active @endif">
                                                            <button class="btn-design">Accepted</button></li>
                                                        <li
                                                            class="ready-for-delivery @if ($datavalue->order_status >= config('global.order_status_ready_for_delivery')) active @endif">
                                                            <button class="btn-design">Ready For Delivery</button></li>
                                                        <li
                                                            class="dispatched @if ($datavalue->order_status >= config('global.order_status_dispatched')) active @endif">
                                                            <button class="btn-design">Dispatched</button></li>
                                                        <li
                                                            class="delivered @if ($datavalue->order_status >= config('global.order_status_delivered')) active @endif">
                                                            <button class="btn-design">Delivered</button></li>
                                                        @if ($datavalue->is_returned)
                                                            <li
                                                                class="delivered @if ($datavalue->order_status >= config('global.order_status_returned')) active @endif">
                                                                <button class="btn-design">

                                                                    @if (!$datavalue->ret_status)
                                                                        Return Pending
                                                                    @endif
                                                                    @if ($datavalue->ret_status == 1)
                                                                        Returned
                                                                    @endif
                                                                    @if ($datavalue->ret_status == 2)
                                                                        Return Rejected
                                                                    @endif

                                                                </button></li>
                                                        @endif
                                                    @else
                                                        <li
                                                            class="delivered @if ($datavalue->order_status >= config('global.order_status_cancelled')) active @endif">
                                                            <button class="btn-design">Cancelled</button></li>
                                                    @endif

                                                </ul>
                                            </div>

                                            <select class="form-control" data-role="status-change"
                                                href="{{ url('admin/order/change_status') }}"
                                                detailsid="{{ $datavalue->id }}" style="display: none;">
                                                <option value="{{ config('global.order_status_pending') }}"
                                                    @if (!empty($datavalue->order_status)) {{ $datavalue->order_status == config('global.order_status_pending') ? 'selected' : null }} @endif>
                                                    Pending</option>
                                                <option value="{{ config('global.order_status_accepted') }}"
                                                    @if (!empty($datavalue->order_status)) {{ $datavalue->order_status == config('global.order_status_accepted') ? 'selected' : null }} @endif>
                                                    Accepted</option>
                                                <option value="{{ config('global.order_status_ready_for_delivery') }}"
                                                    @if (!empty($datavalue->order_status)) {{ $datavalue->order_status == config('global.order_status_ready_for_delivery') ? 'selected' : null }} @endif>
                                                    Ready for Delivery</option>
                                                <option value="{{ config('global.order_status_dispatched') }}"
                                                    @if (!empty($datavalue->order_status)) {{ $datavalue->order_status == config('global.order_status_dispatched') ? 'selected' : null }} @endif>
                                                    Dispatched</option>
                                                <option value="{{ config('global.order_status_delivered') }}"
                                                    @if (!empty($datavalue->order_status)) {{ $datavalue->order_status == config('global.order_status_delivered') ? 'selected' : null }} @endif>
                                                    Delivered</option>
                                                <option value="{{ config('global.order_status_cancelled') }}"
                                                    @if (!empty($datavalue->order_status)) {{ $datavalue->order_status == config('global.order_status_cancelled') ? 'selected' : null }} @endif>
                                                    Cancelled</option>
                                            </select>
                                        </div> --}}
                                    
                                    <?php } ?>
                                </div>
                                </div>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{ asset('') }}admin_assets/plugins/table/datatable/datatables.js"></script>
    <script>
        $('body').off('click', '[data-role="status-change"]');
        $('body').on('click', '[data-role="status-change"]', function(e) {
            e.preventDefault();
            var msg = $(this).data('message') || 'Are you sure that you want to change status?';
            var href = $('.ord_list').attr('data-href');
            var detailsid = $('.ord_list').attr('data-detailsid');
            var statusid = $(this).attr('data-st');
            var title = $(this).data('title') || 'Confirm Status Change';

            App.confirm(title, msg, function() {
                var ajxReq = $.ajax({
                    url: href,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "detailsid": detailsid,
                        "statusid": statusid,
                    },
                    success: function(res) {
                        if (res['status'] == 1) {
                            App.alert(res['message'] || 'Status changed successfully',
                                'Success!');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);

                        } else {
                            App.alert(res['message'] || 'Unable to change the record.',
                                'Failed!');
                        }
                    },
                    error: function(jqXhr, textStatus, errorMessage) {

                    }
                });
            });

        });


        $('body').off('click', '[data-role="cancel-order"]');
        $('body').on('click', '[data-role="cancel-order"]', function(e) {
            e.preventDefault();
            var msg = $(this).data('message') || 'Are you sure that you want to cancel this order?';
            var href = $(this).attr('href');
            var order_id = $(this).attr('order_id');
            var title = 'Confirm Cancel Order';

            App.confirm(title, msg, function() {
                var ajxReq = $.ajax({
                    url: href,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "order_id": order_id,
                    },
                    success: function(res) {
                        if (res['status'] == 1) {
                            App.alert(res['message'], 'Success!');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);

                        } else {
                            App.alert(res['message'],
                                'Failed!');
                        }
                    },
                    error: function(jqXhr, textStatus, errorMessage) {

                    }
                });
            });

        });
    </script>
@stop
