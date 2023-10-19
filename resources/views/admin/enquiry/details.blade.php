@extends('admin.template.layout')

@section('header')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin_assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin_assets/plugins/table/datatable/custom_dt_customer.css">
@stop


@section('content')
    <div class="order-detail-page">
        <div class="dataTables_wrapper container-fluid dt-bootstrap4">
          



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
                            
                            <h4>Enquiry No: {{ $datamain->id }} </h4>
                            <div class="table-responsive">
                                <table width="100%">
                                    <thead>
                                        <tr>
                                            <th>Enquiry No.</th>
                                            <th>: {{ $datamain->id }}</th>
                                            <th>Customer</th>
                                            <th>: {{ $datamain->customer->name??'' }}</th>
                                        </tr>
                                        
                                        <tr>
                                            <th>Created on.</th>
                                            <th>: {{ web_date_in_timezone($datamain->created_at, 'd-M-Y h:i A') }} </th>
                                            <th>Phone</th>
                                            <th>: {{ $datamain->customer->dial_code??'' }} {{ $datamain->customer->phone??'' }}</th>
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
                                <h4>Questionnaire</h4>

                                @foreach($datamain->enquiery_details as $key=> $details)
                                @php $details_sub = json_decode($details->question);
                          
                                 @endphp
                                <span><strong>{{$key+1}}. {{$details_sub->question??''}}</strong><span><br>
                                 <span>Ans: {{$details->answers??''}}</span> <br>  
                                @endforeach
                           
                        </div>

                        <div class="order-page-infomatics">
                            
                            
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
