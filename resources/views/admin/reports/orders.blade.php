@extends("admin.template.layout")

@section('header')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin_assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin_assets/plugins/table/datatable/custom_dt_customer.css">
@stop


@section('content')
    <div class="card">
        <div class="card-body">
            <div class="dataTables_wrapper container-fluid dt-bootstrap4">
                <form action="" method="get">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>Date From</label>
                            <input type="text" name="from" class="form-control flatpickr-input" autocomplete="off" value="{{ $from??'' }}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Date To</label>
                            <input type="text" name="to" class="form-control flatpickr-input" autocomplete="off" value="{{ $to??'' }}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Search Invoice ID</label>
                            <input type="text" name="order_id" class="form-control" autocomplete="off" value="{{ $order_id }}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Customer Name</label>
                            <input type="text" name="name" class="form-control" autocomplete="off" value="{{$name}}">
                        </div>
                        <button type="submit" class="btn btn-warning mb-4 ml-2 btn-rounded">Search</button>
                        <button type="submit" value="Export" name="excel" class="btn btn-warning mb-4 ml-2 btn-rounded">Export</button>
                        <a href="{{url('admin/report/orders')}}" class="btn btn-primary mb-4 ml-2 btn-rounded">Clear</a>
                    </div>
                </form>
                

                    

                    <div class="row mt-3 d-none">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length" id="column-filter_length">
                            </div>
                        </div>

                        
                    </div>
                    <div class="table-responsive">
                    <table class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order No</th>
                                <th>Customer </th>
                                {{--<th>Discount</th>
                                <th>VAT</th>--}}
                                {{-- <th>Admin Commission</th>
                                <th>Vendor Earning</th> --}}
                                <th>Total</th>
                                <th>Payment Mode</th> 
                                {{-- <th>Status</th>  --}}
                                <th>Order Date</th>
                               
                            </tr>
                        </thead>
                         <tbody>
                        @if ($list->total() > 0)
                       

                            <?php $i = $list->perPage() * ($list->currentPage() - 1); ?>
                            @foreach ($list as $item)
                                <?php   $i++; ?>
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td> <?php echo config('global.sale_order_prefix').date(date('Ymd', strtotime($item->created_at))).$item->order_id; ?></td>
                                    <td>{{ $item->name??$item->customer_name }}</td>
                                   {{-- <td>{{ $item->discount }}</td>
                                    <td>{{ $item->vat }}</td>--}}
                                    {{-- <td>{{ $item->admin_commission }}</td>
                                    <td>{{ $item->vendor_commission }}</td> --}}
                                    <td>{{ $item->grand_total }}</td>
                                    <td> 
                                        @if($item->payment_mode==2)
                                            Card
                                        @endif  
                                        @if($item->payment_mode==3)
                                            Apple Pay
                                        @endif 
                                        @if($item->payment_mode==4)
                                            Google Pay
                                        @endif 
                                    </td>
                                    {{-- <td>{{ order_status$item->status }}</td> --}}
                                    <td>
                                        {{web_date_in_timezone($item->booking_date,'d-M-Y h:i A')}}
                                    </td>
                                    
                                </tr>
                            @endforeach
                             @else
                             <tr><td colspan="12" align="center" class="pt-2 p-0">
                        
                        <div class="alert alert-warning">
                            <p>No Orders found</p>
                        </div>
                    </td>
                </tr>
                    @endif
                        </tbody>
                       
                    </table>
                </div>


                    <div class="col-sm-12 col-md-12 pull-right">
                        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                            {!! $list->appends(request()->all())->links('admin.template.pagination') !!}
                        </div>
                    </div>

                
            </div>
        </div>
    </div>

   
@stop

@section('script')
    <script src="{{ asset('') }}admin_assets/plugins/table/datatable/datatables.js"></script>
    <script>
        
    </script>
@stop