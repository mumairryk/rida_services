@extends("admin.template.layout")

@section('header')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin_assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin_assets/plugins/table/datatable/custom_dt_customer.css">
@stop


@section('content')
    <div class="order-detail-page">
            <div class="dataTables_wrapper container-fluid dt-bootstrap4">
            
                
                <div class="order-totel-details">
                    <div class="card">
                    <div class="card-body">
                    <div class="col-sm-12">
                        <?php $ordernumber = config('global.quote_prefix').date(date('Ymd', strtotime($datamain->created_at))).$datamain->id; ?>
                <h4>@if($datamain->service_id == 5)Booking @else Quote @endif No: {{$ordernumber}} </h4>
                <div class="table-responsive">
                    @if($datamain->status == config('global.service_status_pending'))
                        @if($datamain->service_id != 5)
                        <p>
                            <span class="btn btn-success" style="border-color:#e9ecef" data-roleee="return-status-change" data-toggle="modal" data-target="#accept_modal">Send Quote</span>

                            <span class="btn btn-danger"  style="border-color:#e9ecef!important" data-roleee="return-status-change" data-toggle="modal" data-target="#reject_modal">Reject Request</span>
                        </p>
                        @endif
                    @endif
                <table width="100%" class="table table-bordered">
                    <thead>
                        <tr>
                            <th> @if($datamain->service_id == 5)Booking @else Quote @endif No.</th>
                            <th>: {{$ordernumber}}</th>
                            <th>Service</th>
                            <th>: {{$datamain->service}}</th>
                            <th>Customer</th>
                            <th>: {{$datamain->name??$datamain->customer}}</th>
                        </tr>
                        <tr>
                            <th> @if($datamain->service_id == 5)Booking @else Quote @endif Status  </th>
                            <th @if($datamain->status == config('global.service_status_pending')) colspan="5" @endif>: {{service_status($datamain->status)}}</th>
                            @if($datamain->status == config('global.service_status_rejected'))
                                <th >Reason  </th>
                                <th colspan="3">: {{$datamain->reject_reason}}</th>
                            @endif
                            @if($datamain->status == config('global.service_quote_sent'))
                                <th >Quote Price  </th>
                                <th >: {{$datamain->quote_price}}</th>
                            @endif
                            @if($datamain->status != config('global.service_status_pending') && $datamain->status != config('global.service_status_rejected'))
                                <th >Quote Doc  </th>
                                <th >: 
                                    @if($datamain->quote_document)
                                        <a href="{{$datamain->quote_document}}" target="_blank" rel="noopener noreferrer">View Document</a>
                                    @endif
                                </th>
                            @endif
                        </tr>

                        @if($datamain->service_id==1)
                        <tr>
                            <th>Doctor</th>
                            <th>: {{$datamain->doctor->name}}</th>
                            <th>Appointment Type</th>
                            <th>: {{$datamain->appointment_types->name}}</th>
                            <th>Date & Time</th>
                            <th>: {{$datamain->date.' - '.date('h:i A',strtotime($datamain->time))}}</th>
                        </tr>
                        @endif

                        @if($datamain->service_id==2)
                        <tr>
                            <th>Groomer</th>
                            <th>: {{$datamain->groomer->name}}</th>
                            <th>Date & Time</th>
                            <th colspan="3">: {{$datamain->date.' - '.date('h:i A',strtotime($datamain->time))}}</th>
                        </tr>
                        <tr>
                            <th>Grooming Type</th>
                            <th colspan="5">: {{$datamain->grooming_type->name}}</th>
                        </tr>
                        @endif


                        @if($datamain->service_id==3 || $datamain->service_id==4)
                        <tr>
                            <th>Drop Off Date & Time  </th>
                            <th>: {{$datamain->drop_off_date.' - '.date('h:i A',strtotime($datamain->drop_off_time))}}</th>
                            <th>Pick-up Date & Time  </th>
                            <th>: {{$datamain->pick_up_date.' - '.date('h:i A',strtotime($datamain->pick_up_time))}}</th>
                            <th>Feeding Schedule</th>
                            <th>: {{$datamain->feeding_schedules->name ?? ''}}</th>
                        </tr>

                        <tr>
                            <th>Selected Foods  </th>
                            <th> 
                                @if($seleted_foods)
                                {{$seleted_foods}}
                                @endif
                            </th>
                            <th>Entered Foods  </th>
                            <th>{{$datamain->food}}</th>
                            <th >Need Specific Medication  </th>
                            <th>: @if($datamain->specific_medication) Yes @else No @endif</th>
                        </tr>

                        <tr>
                            <th>Additional Notes  </th>
                            <th colspan="5">: {{$datamain->notes}}</th>
                            
                        </tr>


                        @endif

                        @if($datamain->service_id==5)
                        <tr>
                            <th>Date  </th>
                            <th>: {{$datamain->date}}</th>
                            
                            <th>Seats</th>
                            <th colspan="3">: {{$datamain->seats  ?? ''}}</th>
                        </tr>

                        <tr>
                            <th>Price  </th>
                            <th colspan="5">: AED {{$datamain->grand_total ?? ''}}</th>
                        </tr>

                        <tr>
                            <th>Additional Notes  </th>
                            <th colspan="5">: {{$datamain->notes}}</th>
                            
                        </tr>


                        @endif
                        
                       
                    </thead>

                </table>
                <br>
                @if($datamain->pets)
                <h5>Pet Details </h5>
                <div class="table-responsive">
                    <table width="100%" class="table table-bordered">
                        <thead>
                            @foreach($datamain->pets as $pets)
                            <tr>
                                <th>Name</th>
                                <th>: {{$pets->pets->name}}</th>
                                <th>Species</th>
                                <th>: {{$pets->pets->sps->name}}</th>
                                <th>Breed</th>
                                <th>: 
                                    @if(isset($pets->pets->breed->name))
                                    {{$pets->pets->breed->name}}
                                    @endif
                                </th>
                            </tr>

                            <tr>
                                <th>Sex</th>
                                <th>: @if($pets->pets->sex==1) Male @else Female @endif </th>
                                <th>DOB</th>
                                <th>: {{$pets->pets->dob}}</th>
                                <th>Weight (lbs)</th>
                                <th>: {{$pets->pets->weight}}</th>
                            </tr>

                            <tr>
                                <th>Food</th>
                                <th>: {{$pets->pets->food}} </th>
                                <th>Additonal Notes</th>
                                <th colspan="3">: {{$pets->pets->additional_notes}}</th>
                                
                            </tr>
                            @endforeach
                        </thead>

                    </table>
                </div>
                @endif
                </div>
                    </div>
                    </div>
                    </div>
                </div>
             
            </div>
    </div>

    <div class="modal" tabindex="-1"  id="reject_modal" role="dialog">
        <div class="modal-dialog" role="document">
           
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Reject Request</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="post" id="admin-form" action="{{url('admin/service_quotes/change_quote_status')}}" enctype="multipart/form-data" data-parsley-validate="true">
            <div class="modal-body">
                    @csrf()
                    <input type="hidden" name="statusid" value="{{config('global.service_status_rejected')}}">
                    <input type="hidden" name="detailsid" value="{{$datamain->id}}">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label>Reject Reason<b class="text-danger">*</b></label>
                                <textarea rows="5" name="reject_reason" class="form-control" required
                        data-parsley-required-message="Enter Reject Reason"></textarea>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button  type="submit"  class="btn btn-success" style="float: left;">Submit</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              
            </div>
        </form>
          </div>
       
        </div>
      </div>

      <div class="modal" tabindex="-1"  id="accept_modal" role="dialog">
        <div class="modal-dialog" role="document">
           
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Send Quote</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="post" id="admin-form" action="{{url('admin/service_quotes/change_quote_status')}}" enctype="multipart/form-data" data-parsley-validate="true">
            <div class="modal-body">
                    @csrf()
                    <input type="hidden" name="statusid" value="{{config('global.service_quote_sent')}}">
                    <input type="hidden" name="detailsid" value="{{$datamain->id}}">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label>Quote Price<b class="text-danger">*</b></label>
                                <input type="text" name="quote_price" class="form-control" required
                        data-parsley-required-message="Enter Quote Price" data-parsley-type="number" min="0">
                            </div>
                        </div>

                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label>Quote Document</label>
                                <input type="file" name="quote_doc" class="form-control" data-parsley-trigger="change"
                                    data-parsley-fileextension="pdf,doc,docx"
                                    data-parsley-fileextension-message="Only files with type pdf,doc are supported" data-parsley-max-file-size="5120" data-parsley-max-file-size-message="Max file size should be 5MB" >
                            </div>
                        </div>

                        
                    </div>
            </div>
            <div class="modal-footer">
                <button  type="submit"  class="btn btn-success" style="float: left;">Submit</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              
            </div>
        </form>
          </div>
       
        </div>
      </div>
@stop

@section('script')
    <script>
        $('body').off('submit', '#admin-form');
        $('body').on('submit', '#admin-form', function(e) {
            e.preventDefault();
            var $form = $(this);
            var formData = new FormData(this);
            $(".invalid-feedback").remove();

            App.loading(true);
            $form.find('button[type="submit"]')
                .text('Submit')
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
                        var m = res['message'];
                            App.alert(m, 'Oops!');
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
                            window.location.reload();
                        }, 1500);
                    }

                    $form.find('button[type="submit"]')
                        .text('Submit')
                        .attr('disabled', false);
                },
                error: function(e) {
                    App.loading(false);
                    $form.find('button[type="submit"]')
                        .text('Submit')
                        .attr('disabled', false);
                    App.alert(e.responseText, 'Oops!');
                }
            });
        });
    </script>
@stop
