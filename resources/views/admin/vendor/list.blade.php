@extends("admin.template.layout")

@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop


@section("content")

<style>
    #example2_info{
        display: none
    }
</style>
<div class="card mb-5">
    @if(check_permission('vendor','Create'))
     <div class="card-header"><a href="{{url('admin/vendors/create')}}" class="btn-custom btn mr-2 mt-2 mb-2"><i class="fa-solid fa-plus"></i> Create Vendors</a></div>
    @endif
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-condensed table-striped" id="example2">
            <thead>
                <tr>
                <th>#</th>
                <th>Vendor Info</th>
                <!-- <th>Image</th>
                <th>Name</th>
                <th>Industry Type</th> -->
                <!-- <th>Commission Type</th>
                <th>Commission Preference</th>
                <th>Ownership</th>
                <th>Drived status</th>

                <th>Region</th> -->
                <th>Is verified</th>
                <th>Active</th>
                <th>Created</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; ?>
                @foreach($datamain as $datarow) 
                    <?php $i++ ?>
                    <tr>
                        <td>{{$i}}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span>
                                    <img src="{{asset($datarow->logo)}}" style="width:60px;height:60px;object-fit:cover;" class="rounded-circle" alt="User">
                                </span>
                                <span class="ml-3">
                                        <a href="#" class="yellow-color">{{$datarow->name}}</a>
                                        <div class="">
                                            @if($datarow->industry) {{$datarow->industry}} <br> @endif
                                            {{$datarow->email}} <br>
                                            +{{$datarow->dial_code}} {{$datarow->phone}}
                                        </div>
                                </span>
                            </div>
                        </td>
                        <td>
                            <label class="switch s-icons s-outline  s-outline-warning  mb-4 mr-2">
                                        <input type="checkbox" class="change_status" data-id="{{ $datarow->id }}"
                                            data-url="{{ url('admin/vendors/verify') }}"
                                            @if ($datarow->verified) checked @endif>
                                        <span class="slider round"></span>
                            </label>
                        </td>
                        <td>
                            <label class="switch s-icons s-outline  s-outline-warning  mb-4 mr-2">
                                        <input type="checkbox" class="change_status" data-id="{{ $datarow->id }}"
                                            data-url="{{ url('admin/vendors/change_status') }}"
                                            @if ($datarow->active) checked @endif>
                                        <span class="slider round"></span>
                            </label>
                        </td>
                        <td>{{web_date_in_timezone($datarow->u_created_at,'d-M-Y h:i A')}}</td>
                        <td class="text-center">
                            <div class="dropdown custom-dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="flaticon-dot-three"></i>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink7">

                                    @if(check_permission('vendor','Edit'))
                                    <a class="dropdown-item" href="{{url('admin/vendors/'.$datarow->id.'/edit')}}">
                                        <i class="flaticon-pencil-1"></i> Edit</a>
                                    @endif

                                    @if(check_permission('vendor','ChangePassword'))
                                    {{-- <a class="dropdown-item" userid="{{$datarow->id}}" data-role="change_password" >
                                        <i class="flaticon-plus"></i> Change Password</a> --}}
                                    @endif

                                   

                                
                                    @if(check_permission('vendor','Delete'))

                                    {{-- <a class="dropdown-item" data-role="unlink"
                                    data-message="Do you want to remove this Vendor?"
                                    href="{{ url('admin/vendors/' . $datarow->id) }}"><i
                                        class="flaticon-delete-1"></i> Delete</a> --}}
                                    @endif


                                    @if(check_permission('doctors','View'))
                                    <a class="dropdown-item" href="{{url('admin/doctors?vendor='.$datarow->id)}}"><i class="flaticon-view"></i> Doctors</a>    
                                    @endif

                                    @if(check_permission('groomers','View'))
                                    <a class="dropdown-item" href="{{url('admin/groomers?vendor='.$datarow->id)}}"><i class="flaticon-view"></i> Groomers</a>    
                                    @endif

                                    @if(check_permission('products','View'))
                                    <a class="dropdown-item" href="{{url('admin/products')}}"><i class="flaticon-view"></i> Products</a>    
                                    @endif

                                    @if(check_permission('playtime_staffs','View'))
                                    <button class="dropdown-item" onclick="loadDatesModal({{$datarow->id}}, '{{route('admin.vendor.doggy.dates',$datarow->id)}}')">
                                        <i class="fa fa-calendar-alt"></i> Play Time Dates</button>
                                    {{--<a class="dropdown-item" href="{{url('admin/playtime_staffs?vendor='.$datarow->id)}}"><i class="flaticon-view"></i> Playtime Staffs</a> --}}   
                                    @endif


                                </div>
                            </div>
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>
<form action="{{route('admin.vendor.add-dates')}}" method="post" id="addDatesForm">
    <div class="modal fade" id="add-date-modal" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="date_add_edit_modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="date_add_edit_modal">Add Date(s) for Doggy Play time</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="vendor_id" id="add_date_vendor_id">
                    <input type="hidden" name="date_id" id="modal_date_id">
                    <!-- <label for="add_date">Add Date(s)</label>
                    <input onchange="CheckEvent()" type="text" id="modal_dates"
                           class="form-control"
                           placeholder="YYYY-MM-DD" value="" name="dates_m" required/>
                    <input type="hidden" id="loadDatesUrl"> -->

                    <div class="row">
                        <div class="col-xs-12 col-md-12 col-sm-6 col-lg-12">
                            <div class="form-group" id="">
                                <label for="add_date">Add Date(s) 
                                    <span class="colorred">*</span>
                                </label>
                                <input onchange="CheckEvent()" type="text" id="modal_dates"
                                   class="form-control"
                                   placeholder="YYYY-MM-DD"  name="dates_m" required/>
                            <input type="hidden" id="loadDatesUrl">
                            <br>
                                <strong><label id="modal_dates_label"></label></strong>

                            </div>
                        </div>
                        {{--<div class="col-lg-12 col-md-6">
                            <div class="form-group">
                                <label for="menu">Start Time
                                    <span class="colorred">*</span></label>
                                <input   type="time" class="form-control"
                                    name="start_time" id="model_start_time" autocomplete="off" required 

                                     />
                                <span class="colorred error-message"></span>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-6">
                            <div class="form-group">
                                <label for="menu">End Time
                                    <span class="colorred">*</span></label>
                                <input   type="time" class="form-control" name="end_time" required
                                    id="model_end_time" autocomplete="off"
                                    />
                                <span class="colorred error-message"></span>
                            </div>
                        </div>--}}
                        <div class="col-md-6 col-sm-6 col-lg-12">
                            <div class="form-group">
                                <label for="price">Price <span class="colorred">*</span></label>
                                <input type="number" class="form-control " name="price" placeholder="Enter the price " 
                                 id="model_price" min="0" />
                                <span class="colorred error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-12">
                            <div class="form-group">
                                <label for="total_seats">Total Number Of Seats <span class="colorred">*</span></label>
                                <input type="number" class="form-control " name="total_seats" placeholder="Enter the total number of seats " 
                                 id="model_total_seats" min="0" />
                                <span class="colorred error-message"></span>
                            </div>
                        </div>

                        {{--<div class="col-md-6 col-sm-6 col-lg-12">
                            <div class="form-group">
                                <label for="seats">No Of Seats Allocated To splidu <span class="colorred">*</span></label>
                                <input type="number" class="form-control " name="seats"  placeholder="Enter the number of seats allocated to splidu" 
                                       id="model_seats" min="0" />
                                <span class="colorred error-message"></span>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6 col-lg-12 d-none">
                            <div class="form-group">
                                <label for="guests_booking">Maximum Guests Per Booking <span
                                        class="colorred">*</span></label>
                                <input type="number" class="form-control" name="guests_booking"
                                placeholder="Enter the maximum guests per booking" 
                                 id="model_guests_booking"/>
                                <span class="colorred error-message"></span>
                            </div>
                        </div>--}}

                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="modal fade" id="doggy_dates_modal" tabindex="-1" role="dialog" aria-labelledby="rejectDiningLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectDiningLabel"><span id="dog_dates_list">Doggy Play time</span> dates list
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.vendor.delete-date')}}" method="post" id='vendor_dates_form'>
                @csrf
                <div class="modal-body">
                    <p>
                        <!-- To cancel the experience on a specific date, please select the date and submit.<br> -->
                        <!-- <small class="text-muted">Note: You can only cancel the experience that have no bookings. If
                            there are bookings on the
                            date you selected, the experience cancellation request will be send to the splidu for
                            approval.</small> -->
                    </p>

                    <button type="button" class="btn btn-primary btn-sm mb-2" onclick="addNewDate()"
                            data-toggle="modal"
                            data-target="#add-date-modal">Add New Date
                    </button>
                    <input type="hidden" name="vendor_id" id="modal_vendor_id">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Sr#</th>
                            <th>Date</th>
                            <th>Price</th>
                            <th>Total Seats</th>
                            <th>Status</th>
                            <th><input type="checkbox" name="check_all" id="check_all_ids" value="1"> All</th>
                        </tr>
                        </thead>
                        <tbody id="dates_table">

                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="delete_date_submit" disabled class="btn btn-primary">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@section("script")
<script src="{{asset('')}}admin-assets/plugins/table/datatable/datatables.js"></script>

<script type="text/javascript">
    function loadDatesModal(vendor_id, url) {

        $.ajax({
            url: url,
            type: "GET",
            data: {
                vendor_id: vendor_id,
                _token: '{{csrf_token()}}'
            },
            success: function (data) {

                $('#dates_table').html(data[0]);
                $('#modal_vendor_id').val(vendor_id);
                $('#add_date_vendor_id').val(vendor_id);
                $('#loadDatesUrl').val(url);
                add_date_picker.config.disable = data[1];

                if(data[2] != undefined){
                    // $('#dog_dates_list').html(data[2].title);
                    // // console.log(data[2],data[2].seats)
                    // $('#model_price').val(data[2].price);
                    // $('#model_seats').val(data[2].seats);
                    // $('#model_start_time').val(data[2].time_start);
                    // $('#model_end_time').val(data[2].time_end);
                    // $('#model_total_seats').val(data[2].total_seats);
                    // $('#model_guests_booking').val(data[2].guests_booking);
                }

                $('#doggy_dates_modal').modal('show');
                add_date_picker.redraw();
                $("#check_all_ids").click(function () {
                    $('.delete-date-checkbox').prop('checked', $(this).prop('checked'));
                    $("#delete_date_submit").prop("disabled", !$(this).prop('checked'));
                });
                $(".delete-date-checkbox").on('change', function () {
                    let is_check = true;
                    $('.delete-date-checkbox').each(function () {
                        if (!this.checked) {
                            $("#check_all_ids").prop("checked", false);
                        } else {
                            is_check = false;
                        }
                    });
                    $("#delete_date_submit").prop("disabled", is_check);
                });
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
    function CheckEvent() {

        var event_title = $("#title").val();
        var event_date = $("#dates").val();
        var date_type = 'multiple';
        var add_date_vendor_id = $("#add_date_vendor_id").val();

        if (date_type == "multiple") {
            var event_date = $("#fp-multiple").val();
        }

        $.ajax({
            type: 'POST',
            url: "{{ route('admin.check_exciting_event')}}",
            data: {
                "add_date_vendor_id": add_date_vendor_id,
                "date_type": date_type,
                "dates": event_date,
                'event_title': event_title,
                "_token": '{{csrf_token()}}'
            },
            success: function (response) {

                if (response == 1) {
                    $("#myModal").modal('show');
                }
            }
        });

    }

    let add_date_picker = $('#modal_dates').flatpickr({
        mode: 'multiple',
        minDate: 'today',
        disable: [],
        dateFormat: "d-m-Y",
    });
    function editDate(date,price,seats_count,total_seats,seats,vendor_id,date_id,s_time,e_time) {
        $('#date_add_edit_modal').html('Update details of date');

        $('#modal_dates').addClass('d-none');
        $('#modal_dates_label').html(date);

        $('#model_price').val(price);
        $('#model_total_seats').val(total_seats);
        $('#model_seats').val(seats);

        $('#model_total_seats').attr('min',seats_count);
        $('#model_seats').attr('min',seats_count);

        
        $('#modal_date_id').val(date_id);
        $('#model_start_time').val(s_time);
        $('#model_end_time').val(e_time);

        $('#add-date-modal').modal('show');
        $('#doggy_dates_modal').modal('hide');
    }
    function addNewDate() {
        $('#date_add_edit_modal').html('Add Date(s) to Service');

        $('#modal_dates').val('');
        $('#modal_date_id').val('');

        $('#model_total_seats').attr('min',0);
        $('#model_seats').attr('min',0);

        // $('#model_seats').val('');
        // $('#model_start_time').val('');
        // $('#model_end_time').val('');

        $('#modal_dates').removeClass('d-none');
        $('#modal_dates_label').html('');

        $('#doggy_dates_modal').modal('hide');
    }
    $('#addDatesForm').on('submit', function (e) {
        e.preventDefault();
        var vendor_id = $('#add_date_vendor_id').val();
        var dates = $('#modal_dates').val();

        var start_time = $('#model_start_time').val();
        var end_time = $('#model_end_time').val();
        var price = $('#model_price').val();
        var total_seats = $('#model_total_seats').val();
        var seats = $('#model_seats').val();
        var guests_booking = $('#model_guests_booking').val();
        var date_id = $('#modal_date_id').val();
        $('#overlayer').fadeIn();
        $('.content').fadeOut();
        // Swal.fire({
        //     title: 'In progress',
        //     text: 'Updating the date and sending notifications to the customers who booked the experience is in progress... ',
        //     showConfirmButton: false,
        // })
        $.ajax({
            type: "POST",
            url: "{{route('admin.vendor.add-dates')}}",
            data: {
                'vendor_id': vendor_id,
                'dates': dates,
                'price': price,
                'start_time': start_time,
                'end_time': end_time,
                'total_seats': total_seats,
                'seats': seats,
                'guests_booking': guests_booking,
                'date_id': date_id,
                '_token': '{{csrf_token()}}'
            },
            success: function (data) {
                $('#overlayer').fadeOut();
                $('.content').fadeIn();
                if (data.status) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.value) {

                            if(data.date != undefined){
                                $('#model_price').val(data.date.price);
                                $('#model_seats').val(data.date.seats);
                                $('#model_start_time').val(data.date.time_start);
                                $('#model_end_time').val(data.date.time_end);
                                $('#model_total_seats').val(data.date.total_seats);
                                $('#model_guests_booking').val(data.date.guests_booking);
                                loadDatesModal(vendor_id,$('#loadDatesUrl').val());
                            }
                            
                            $('#add-date-modal').modal('hide');

                        }
                    });
                }else{
                     Swal.fire(
                        '',
                        data.message,
                        'warning'
                    ).then((result) => {
                        
                    })
                }
            },
        });
    });
    $('#vendor_dates_form').on('submit', function (e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var formData = new FormData(this);
        var ids = formData.getAll('date_ids[]');
        if (ids.length == 0) {
            Swal.fire({
                title: 'Error!',
                text: 'Please select at least one date.',
                icon: 'error',
                toast: true,
                showConfirmButton: false,
                position: 'top-end',
                timer: 3000,
            });
            return false;
        }
        let delete_request_data = {
            'vendor_id': $('#modal_vendor_id').val(),
            'date_ids': ids,
            '_token': '{{csrf_token()}}'
        }
        // console.log(delete_request_data);
        Swal.fire({
            // title: 'Are you sure?',
            // text: "You won't be able to revert this!",
            // icon: 'warning',
            showCancelButton: true,
            // confirmButtonColor: '#3085d6',
            // cancelButtonColor: '#d33',
            // confirmButtonText: 'Yes, delete it!',
            // customClass: {
            //     container: 'on-top-sw'
            // }
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            confirmButtonText: 'Yes, delete it!',

        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: "post",
                    data: delete_request_data,
                    success: function (data) {
                        $('#doggy_dates_modal').modal('hide');
                        console.log(data.status);
                        if (data.status == 1) {
                            Swal.fire(
                                'Deleted!',
                                data.message,
                                'success'
                            ).then((result) => {
                                if (result.value) {
                                    location.reload();
                                }
                            })
                        }
                        if (data.status == 2) {
                            var inputOptions = new Promise(function(resolve) {
                                resolve({
                                    '': 'splidu',
                                    '': 'Chef'
                                });
                            });
                            Swal.fire({
                                title: 'Pending Orders Found!',
                                html: data.message,
                                input: 'radio',
                                inputOptions: inputOptions,
                                inputAttributes: {
                                    style: 'z-index:1000001',
                                },
                                showCancelButton: true,
                                confirmButtonText: 'Delete & Refund',
                                showLoaderOnConfirm: true,
                                customClass: {
                                    container: 'on-top-sw'
                                },
                                preConfirm: (result) => {
                                    if (!result) {
                                        Swal.showValidationMessage(`Please select one option`);
                                    }
                                    $('#overlayer').fadeIn();
                                    $('.content').fadeOut();
                                    delete_request_data.cost_to = result;
                                    delete_request_data.date_ids = data.date_ids;
                                    $.ajax({
                                            url: "{{route('admin.vendor.delete-date-request')}}",
                                            type: "POST",
                                            data: delete_request_data,
                                            success: function (data) {
                                                $('#overlayer').fadeOut();
                                                $('.content').fadeIn();
                                                if (data.status) {
                                                    Swal.fire(
                                                        'Deleted!',
                                                        data.message,
                                                        'success'
                                                    ).then((result) => {
                                                        if (result.value) {
                                                            location.reload();
                                                        }
                                                    })
                                                }
                                            },
                                            error: function (data) {
                                                $('#overlayer').fadeOut();
                                                $('.content').fadeIn();
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: 'Something went wrong.',
                                                    icon: 'error',
                                                    toast: true,
                                                    showConfirmButton: false,
                                                    position: 'top-end',
                                                    timer: 3000,
                                                });
                                            }
                                        }
                                    );
                                }
                            });
                        }

                    }
                });
            }
        });
    });

   
</script>
<script>
$('#example2').DataTable({
      "paging": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
    </script>
    <script>
        App.initFormView();
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
                            window.location.href = App.siteUrl('/admin/vendors');
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
@stop