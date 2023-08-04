@extends('admin.template.layout')

@section('content')
<link href="{{ asset('') }}admin-assets/jquery.timepicker.min.css" rel="stylesheet" type="text/css" />
<style>

 

  #calendar {
    max-width: 1100px;
    margin: 0 auto;
  }
.fc-event-time{
    display: none!important;
}
</style>
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

    .parsley-errors-list>.parsley-pattern {
        margin-top: 10px;
    }
    </style>
    <form method="post" id="admin-form" action="{{ url('admin/doctors') }}" enctype="multipart/form-data"
        data-parsley-validate="true">
        <input type="hidden" name="id" value="{{ $id }}">
        <input type="hidden" name="device_id" id="device_id" value="{{$un_id}}">
        @csrf()
        <div class="">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Name <span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control"  maxlength="600" name="name"
                                    value="{{empty($datamain->name) ? '': $datamain->name}}" required
                                    data-parsley-required-message="Enter Name">
                            </div>
                        </div>

                        

                        <div class="col-sm-6 col-xs-12 d-none">
                            <div class="form-group">
                                <label>Vendor <span style="color:red;">*<span></span></span></label>
                                <select class="form-control" name="vendor" 
                                            data-parsley-required-message="Select Vendor">
                                            <option value="">Select</option>
                                            @foreach ($users as $vnd)
                                            <option selected
                                                 value="{{ $vnd->id }}">{{ $vnd->name }}</option>
                                            @endforeach;
                                            
                                        </select>

                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Type <span style="color:red;">*<span></span></span></label>
                                <select name="type" class="form-control" required
                                data-parsley-required-message="Select Type">
                                    <option value="">Select</option>
                                    <option @if($id) @if($datamain->type==1) selected @endif @endif value="1">Head Doctor</option>
                                    <option @if($id) @if($datamain->type==2) selected @endif @endif value="2">Visiting Doctor</option>
                                    <option @if($id) @if($datamain->type==3) selected @endif @endif value="3">Resident Doctor</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Qualification <span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control"  maxlength="1000" name="qualification"
                                    value="{{empty($datamain->qualification) ? '': $datamain->qualification}}" required
                                    data-parsley-required-message="Enter Qualification">
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group d-flex align-items-center">
                                <div>
                                    <label>Upload Image (gif,jpg,png,jpeg) <span
                                            style="color:red;">*<span></span></span></label>
                                    <input type="file" class="form-control jqv-input" name="image" data-role="file-image"
                                        data-preview="image-preview" value="" @if(empty($id)) requiredd
                                        data-parsley-required-message="image is required" @endif
                                        data-parsley-imagedimensionsss="200x200" data-parsley-trigger="change" data-parsley-fileextension="jpg,png,gif,jpeg"
                                        data-parsley-fileextension-message="Only files with type jpg,png,gif,jpeg are supported" data-parsley-max-file-size="5120" data-parsley-max-file-size-message="Max file size should be 5MB" accept="image/*">
                                    <p class="text-muted mt-2"></p>
                                </div>
                                <img id="image-preview" class="img-thumbnail w-50"
                                    style="margin-left: 5px; height:75px; width:75px !important;"
                                    @if($id && $image) src="{{$image}}" @endif>
                            </div>
                        </div>


                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group d-flex align-items-center">
                                <div>
                                    <label>Upload Document (jpg,png,jpeg,pdf) <span
                                            style="color:red;">*<span></span></span></label>
                                    <input type="file" class="form-control jqv-input" name="document" value="" @if(empty($id)) requiredd
                                        data-parsley-required-message="image is required" @endif
                                        data-parsley-imagedimensionsss="200x200" data-parsley-trigger="change" data-parsley-fileextension="jpg,png,jpeg,pdf"
                                        data-parsley-fileextension-message="Only files with type jpg,png,jpeg,pdf are supported" data-parsley-max-file-size="5120" data-parsley-max-file-size-message="Max file size should be 5MB" accept="image/*,application/pdf">
                                    <p class="text-muted mt-2"></p>
                                    @if($id && $document) <a href="{{$document}}" target="_blank" rel="noopener noreferrer">View document</a> @endif
                                    
                                </div>
                                
                            </div>
                        </div>



                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="active" class="form-control">
                                    <option @if($id) @if($datamain->active==1) selected @endif @endif value="1">Active</option>
                                    <option @if($id) @if(!$datamain->active) selected @endif @endif value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                       
                    </div>



                    <div class="card mt-4 weekly d-none" style="border-radius: 5px; overflow: hidden;" >
                        <div class="card-body">
                            <h4><b>Availability</b></h4>
                            <table class="table table-condensed pl-2 mt-2 workinghours" >
                                @php $days = Config('global.days');  @endphp
                                    @foreach($days as $key => $val)
                                        @php $st = $key.'_from'; $ed = $key.'_to';  @endphp
                                        <tr>
                                            <td>
                                            <input type="checkbox" class="week_days" id="day_{{$val}}"  name="{{$val}}" value="1" @if( $id && $datamain->{$val} == 1) checked @endif> &nbsp;
                                            <label for="day_{{$val}}"> {{ucfirst($val)}}</label>
                    
                                            </td>
                                            <td>
                                                <input type="text" @if( $id && $datamain->{$val} == 1) checked @else disabled @endif class="time form-control"  name="{{$key}}_from" value="@if($id && $datamain->$st!='' &&  $datamain->{$val} == 1){{$datamain->$st}}@endif" placeholder="Start Time">
                                            </td>
                                            <td>
                                                <input type="text" @if( $id && $datamain->{$val} == 1) checked @else disabled @endif class="time form-control"  name="{{$key}}_to" value="@if($id && $datamain->$ed!='' &&  $datamain->{$val} == 1){{$datamain->$ed}}@endif" placeholder="End Time">
                                            </td>
                                        </tr>
                                    @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 card mt-4 weekly" style="border-radius: 5px; overflow: hidden;" >
                            <div class="card-body">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-sm-6 col-xs-12 other_docs m-3" id="certificate_product_registration_div">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    

           
        </div>
</div>
</form>
</div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        
      </div>
      <div class="modal-body">
        <form method="post" action="" id="event-from">
            <div class="from-group">
                
                <input type="hidden" name="event_date" id="event_date" class="formc-control">
                <input type="hidden" name="unique_id" id="unique_id" value="{{$un_id}}">
                <input type="hidden" name="doctor_id" value="{{ $id }}">
                <input type="hidden" name="edit_uid" id="edit_uid" value="0">
                <input type="hidden" name="edit_type" id="edit_type" value=''>
            </div>
            <div class="form-group d-none">
                <label>Title</label>
                <input type="text" name="event_title" id="event_title" class="form-control">
            </div>
            <div class="form-group">
                <label>Start Time</label>
                <input type="text" name="event_start_time" id="event_start_time" class="form-control time">
            </div>
            <div class="form-group">
                <label>End Time</label>
                <input type="text" name="event_end_time" id="event_end_time" class="form-control time">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
      </div>
      
    </div>

  </div>
</div>


<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        
      </div>
      <div class="modal-body">
        <input type="hidden" name="ev_id" id="ev_id" value="">
        <input type="hidden" name="ev_type" id="ev_type" value="">
        <h3>Select an option</h3>
        <div class="form-group">
            <button class="btn btn-primary" id="edBtn">Edit</button>
            <button class="btn btn-danger" id="dlBtn">Delete</button>
        </div>
      </div>
      
    </div>

  </div>
</div>
@stop

@section('script')
<script src="//jonthornton.github.io/jquery-timepicker/jquery.timepicker.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.0.0/index.global.min.js'></script>
<script>
App.initFormView();


    $('.time').timepicker({timeFormat:'h:i a'});

$(".week_days").change(function(e){
  
  if( $(this)[0].checked ) {
      $(this).parent().parent().find("input[type='text']").removeAttr("disabled")
  }
  else {
      $(this).parent().parent().find("input[type='text']").attr("disabled", "disabled")
      $(this).parent().parent().find("input[type='text']").val("")
  }
})

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

                    

                    jQuery.each(res['errors'][0], function(e_field, e_message) {
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
                    window.location.href = App.siteUrl('/admin/doctors');
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
<script>
    let event_list = [
        
      ];
    let singleEvent = {};

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      navLinks: true, // can click day/week names to navigate views
      selectable: true,
      selectMirror: true,
      dropAccept:false,
      select: function(arg) {
        console.log(arg);
        $('#edit_uid').val(0);
        $('#event_date').val(arg.startStr);
        $('#myModal').modal("show");
        // var title = prompt('Event Title:');
        // if (title) {
        //   calendar.addEvent({
        //     title: title,
        //     start: arg.start,
        //     end: arg.end,
        //     allDay: arg.allDay
        //   })
        // }
        // calendar.unselect()
      },
      eventClick: function(arg) {
        $('#ev_id').val(arg.event.extendedProps.event_uid);
        $('#ev_type').val(arg.event.extendedProps.type);
        $('#myModal2').modal("show");
        singleEvent = arg.event.extendedProps;
        
        // if (confirm('Are you sure you want to delete this event?')) {
        //   //arg.event.remove()
        //   $.ajax(
        //     {
        //         type:"POST",
        //         url: '{{url("admin/doctors/remove_event")}}',
        //         data: {id: arg.event.extendedProps.event_uid,'type':arg.event.extendedProps.type},
        //         dataType: "json",
        //         success: function (data) {
        //             calendar.refetchEvents()
        //         }
             
        //     }
        // );

        // }
      },
      editable: true,
      dayMaxEvents: true, // allow "more" link when too many events
      //events: event_list
      eventSources:[
        {
            url: '{{url("admin/doctors/get_events")}}',
            method: 'POST',
            extraParams: {
                un_id: '{{$un_id}}',
                'doctor_id':'{{ $id }}'
            },
            failure: function() {
                alert('there was an error while fetching events!');
            }
        }
        
      ]
    });
    $('#dlBtn').click(function(){
        $.ajax(
            {
                type:"POST",
                url: '{{url("admin/doctors/remove_event")}}',
                data: {id: $('#ev_id').val(),'type':$('#ev_type').val()},
                dataType: "json",
                success: function (data) {
                    
                    calendar.refetchEvents()
                    $('#myModal2').modal("hide");
                }
             
            }
        );
    });
    $('#edBtn').click(function(){
        $('#myModal2').modal("hide");
        console.warn(singleEvent);
        $('#event_title').val(singleEvent.event_title);
        $('#event_start_time').val(singleEvent.event_start_time);
        $('#event_end_time').val(singleEvent.event_end_time);
        $('#edit_uid').val(singleEvent.event_uid);
        $('#edit_type').val(singleEvent.type);
        //alert(singleEvent.event_start_time);
        $('#myModal').modal("show");
    });
    $('#event-from').submit(function(e){
        e.preventDefault();

        $.ajax({
             url:"{{url('admin/doctors/add_event')}}",
             type:"post",
             data:new FormData(this),
             processData:false,
             contentType:false,
             cache:false,
             dataType: "json",
             async:false,
            success: function(data){
                if(data.status ==  1){
                    calendar.refetchEvents()
                    $('#event-from')[0].reset();
                    //calendar.getEventSources().refetch();
                }else{
                    App.alert(data.message,'Oops');
                }
                    //calendar.addEvent(data.event)
            }
         });


        // let event_title = $("#event_title").val();
        // let event_start_time = $("#event_start_time").val();
        // let event_end_time = $("#event_end_time").val();
        // let event_date = $("#event_date").val();

        // if (event_title) {
        // calendar.addEvent({
        //     title: event_title,
        //     start: event_date + " "+event_start_time,
        //     end: event_date + " "+event_end_time,
        //     allDay: false
        // })
        // }
        
        $('#myModal').modal("hide");
        calendar.unselect()
    })

    calendar.render();
  });



</script>
@stop