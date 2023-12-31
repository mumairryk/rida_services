@extends("admin.template.layout")

@section('header')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin_assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin_assets/plugins/table/datatable/custom_dt_customer.css">
@stop


@section('content')
    <div class="card mb-5">
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
                        <div class="col-md-3 form-group">
                            <label>Vendor Name</label>
                            <input type="text" name="vendor_name" class="form-control" autocomplete="off" value="{{$vendor_name}}">
                        </div>
                        <div class="col-md-5 form-group mt-4">
                        <button type="submit" class="btn btn-warning mb-4 ml-2 btn-rounded">Search</button>
                        <input type="submit" name="excel" value="Export" class="btn btn-warning mb-4 ml-2 btn-rounded">
                        <a href="{{url('admin/orders')}}" class="btn btn-primary mb-4 ml-2 btn-rounded">Clear</a>
                    </div>
                    </div>
                </form>
                

                    

                    <div class="row mt-3">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length" id="column-filter_length">
                            </div>
                        </div>

                        
                    </div>
                    <div class="table-responsive">
                    <table class="table table-condensed table-striped display nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order No</th>
                                <th>Invoice ID</th>
                                <th>Vendor</th>
                                <th>Customer </th>
                                <th>Total</th>
                                <th>Admin Commission</th>
                                <th>Payment Mode</th>
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
                                        <td> <?php echo config('global.sale_order_prefix').date(date('Ymd', strtotime($item->created_at))).$item->id; ?></td>
                                        <td>{{ $item->order_number }}</td>
                                        <td>{{ $item->vendor }}</td>
                                        <td>{{$item->customer }}</td>
                                   
                                        <td>{{ $item->total_amount }}</td>
                                        <td>{{ $item->admin_commission }}</td>
                                        
                                        <td>
                                            @if($item->payment_method==1)
                                                Wallet
                                            @endif  
                                            @if($item->payment_method==2)
                                                Card
                                            @endif  
                                            @if($item->payment_method==3)
                                                Apple Pay
                                            @endif 
                                        </td>
                                        <td>
                                            {{web_date_in_timezone($item->booking_date,'d-M-Y h:i A')}}
                                        </td>
                                    </tr>
                                @endforeach
                                 @else
                                 <tr><td colspan="12" align="center" class="pt-2 p-0">
                            
                            <div class="alert alert-warning">
                                <p>No Details found</p>
                            </div>
                        </td>
                    </tr>
                        @endif
                            </tbody>
                       
                    </table>
                </div>


                    <div class="col-sm-12 col-md-12 pull-right">
                        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                            {!! $list->links('admin.template.pagination') !!}
                        </div>
                    </div>

                
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{ asset('') }}admin_assets/plugins/table/datatable/datatables.js"></script>
     <script>
     $(document).ready(function() { $('#example').DataTable( { dom: 'Bfrtip', buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print' ] } ); } );
 </script>
@stop