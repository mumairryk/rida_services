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

    .parsley-errors-list>.parsley-pattern {
        margin-top: 10px;
    }

    .form-group.d-flex.align-items-center>div {
        flex: 1;
    }
    </style>
    <form method="post" id="admin-form" action="{{ url('admin/wholeSellers') }}" enctype="multipart/form-data"
        data-parsley-validate="true">
        <input type="hidden" name="id" value="{{ $id }}">
        @csrf()
        <div class="">
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">Fill Your Basic Info</h5>
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Store Name <span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control" data-jqv-maxlength="100" name="name"
                                    value="{{empty($datamain->name) ? '': $datamain->name}}" required
                                    data-parsley-required-message="Enter Full Name">
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Email <span style="color:red;">*<span></span></span></label>
                                <input type="email" class="form-control" name="email" data-jqv-maxlength="50"
                                    value="{{empty($datamain->email) ? '': $datamain->email}}" required
                                    data-parsley-required-message="Enter Email" autocomplete="off"
                                    autocomplete="new-password">

                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Mobile<b class="text-danger">*</b></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <select class="form-control" name="dial_code" required
                                            data-parsley-required-message="">
                                            <option value="">code</option>
                                            @foreach ($countries as $cnt)
                                            <option @if($id) @if($datamain->dial_code==$cnt->dial_code) selected @endif
                                                @endif value="{{ $cnt->dial_code }}">+{{ $cnt->dial_code }}</option>
                                            @endforeach;
                                        </select>
                                    </div>
                                    <input type="number" class="form-control" name="phone"
                                        value="{{empty($datamain->phone) ? '': $datamain->phone}}"
                                        data-jqv-required="true" required
                                        data-parsley-required-message="Enter Phone number" data-parsley-type="digits"
                                        data-parsley-minlength="5" data-parsley-maxlength="12"
                                        data-parsley-trigger="keyup">
                                </div>
                                <span id="mob_err"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Password </label>
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" id="password" name="password"
                                        data-jqv-maxlength="50" value="" data-parsley-minlength="8" autocomplete="off"
                                        data-parsley-errors-container="#p1_err" autocomplete="new-password">
                                    <div class="input-group-append" style="cursor: pointer">
                                        <span class="input-group-text" onclick="password_show_hide();">
                                            <i class="fas fa-eye" id="show_eye"></i>
                                            <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                                        </span>
                                    </div>
                                </div>
                                <span id="p1_err"></span>
                            </div>
                        </div>

                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Confirm Password </label>
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" name="confirm_password"
                                        data-jqv-maxlength="50" value="" data-parsley-minlength="8"
                                        data-parsley-equalto="#password" autocomplete="off"
                                        data-parsley-required-message="Please re-enter your new password."
                                        data-parsley-required-if="#password" id="password2"
                                        data-parsley-errors-container="#p2_err" autocomplete="new-password">
                                    <div class="input-group-append" style="cursor: pointer">
                                        <span class="input-group-text" onclick="password_show_hide2();">
                                            <i class="fas fa-eye" id="show_eye2"></i>
                                            <i class="fas fa-eye-slash d-none" id="hide_eye2"></i>
                                        </span>
                                    </div>
                                </div>
                                <span id="p2_err"></span>
                            </div>
                        </div>

                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Admin Commission %</label>
                                <div class="input-group mb-3">
                                    <input type="text" data-parsley-type="number" class="form-control" id="admin_commission_perc" name="admin_commission_perc" required data-parsley-required-message="Enter Commission" min="0" max="100" value="{{empty($datamain->admin_commission_perc) ? 0: $datamain->admin_commission_perc}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">Bank Account Details</h5>
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Account No <span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control" data-jqv-maxlength="100" name="account_no"
                                    value="{{empty($datamain->bankdetails->account_no) ? '': $datamain->bankdetails->account_no}}"
                                    required data-parsley-required-message="Enter Account No">
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Bank Name <span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control" data-jqv-maxlength="100" name="bank_name"
                                    value="{{empty($datamain->bankdetails->bank_name) ? '': $datamain->bankdetails->bank_name}}"
                                    required data-parsley-required-message="Enter Bank Name">
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>IBAN Code <span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control" data-jqv-maxlength="100" name="iban_code"
                                    value="{{empty($datamain->bankdetails->iban_code) ? '': $datamain->bankdetails->iban_code}}"
                                    required data-parsley-required-message="Enter IBAN Code" data-parsley-type="digits" data-parsley-type-message="Only numbers allowed">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-2">
                <h5 class="card-body card-title">Commercial Registration Details</h5>
                <div class="row m-3">
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group d-flex align-items-center">
                            <div>
                                <label>Upload Commercial License (gif,jpg,png,jpeg) <span
                                        style="color:red;">*<span></span></span></label>
                                <input type="file" class="form-control jqv-input" name="commercial_license"
                                    data-role="file-image" data-preview="commercial_license-preview" value=""
                                    @if(empty($id)) required data-parsley-required-message="image is required" @endif
                                    data-parsley-trigger="change">
                            </div>
                            <img id="commercial_license-preview" class="img-thumbnail w-50"
                                style="margin-left: 5px; height:75px; width:75px !important;"
                                src="{{empty($datamain->commercial_license) ? asset('admin-assets/assets/img/placeholder.jpg'): asset($datamain->commercial_license) }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Commercial Registration No <span style="color:red;">*<span></span></span></label>
                            <input type="text" class="form-control" data-jqv-maxlength="100" name="commercial_reg_no"
                                value="{{empty($datamain->bankdetails->account_no) ? '': $datamain->bankdetails->account_no}}"
                                required data-parsley-required-message="Commercial Registration No">
                        </div>
                        <span id="mob_err"></span>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group d-flex align-items-center">
                            <div>
                                <label>Upload Associated License (gif,jpg,png,jpeg) <span
                                        style="color:red;">*<span></span></span></label>
                                <input type="file" class="form-control jqv-input" name="associated_license"
                                    data-role="file-image" data-preview="associated_license-preview" value=""
                                    @if(empty($id)) required data-parsley-required-message="image is required" @endif
                                    data-parsley-trigger="change">
                                <p class="text-muted mt-2">Allowed Dim 200x200(px)</p>
                            </div>
                            <img id="associated_license-preview" class="img-thumbnail w-50"
                                style="margin-left: 5px; height:75px; width:75px !important;"
                                src="{{empty($datamain->associated_license) ? asset('admin-assets/assets/img/placeholder.jpg'): asset($datamain->associated_license)}}">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12 mb-0">
                        <label>Address <span style="color:red;">*<span></span></span></label>
                        <input type="text" class="form-control" name="address"
                            value="{{empty($datamain->address) ? '': $datamain->address}}" data-jqv-maxlength="100"
                            required data-parsley-required-message="Enter Address">
                    </div>

                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>Activity Type<b class="text-danger">*</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend w-100">
                                    <select class="form-control " name="activity_type_id" required
                                        data-parsley-required-message="">
                                        <option value="">Activity Type</option>
                                        @foreach ($activityTypes as $activityType)
                                        <option @if($id) @if($datamain->activity_type_id==$activityType->id) selected
                                            @endif @endif value="{{ $activityType->id }}"> {{ $activityType->name }}
                                        </option>
                                        @endforeach;
                                    </select>
                                </div>
                            </div>
                            <span id="mob_err"></span>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="control-label">Enter the location or Drag the marker<b
                                class="text-danger">*</b></label>
                        <input type="text" name="txt_location" id="txt_location" class="form-control autocomplete"
                            placeholder="Location" required data-parsley-required-message="Enter Location" @if(isset($location->location_name))
                            value="{{$location->location_name}}" @endif>
                        <input type="hidden" id="location" name="location">
                    </div>
                    <div class="form-group col-md-12">
                        <div id="map_canvas" style="height: 200px;width:100%;"></div>
                    </div>

                </div>

                <div class="row ">
                    <div class="col-sm-4 col-xs-12 other_docs m-3" id="certificate_product_registration_div">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</form>
</div>
@stop

@section('script')

<script type="text/javascript"
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8QkOt74HuPCD8N6m1OfwSzyb0NWnjorg&v=weekly&libraries=places">
</script>
<script>
    var currentLat = <?php echo isset($location->lattitude) ? $location->lattitude : 25.204819 ?>;
    var currentLong = <?php echo isset($location->longitude) ? $location->longitude : 55.270931 ?>;
    $("#location").val(currentLat + "," + currentLong);
    currentlocation = {
    "lat": currentLat,
    "lng": currentLong,
};
initMap();
initAutocomplete();

function initMap() {
    map2 = new google.maps.Map(document.getElementById('map_canvas'), {
        center: {
            lat: currentlocation.lat,
            lng: currentlocation.lng
        },
        zoom: 14,
        gestureHandling: 'greedy',
        mapTypeControl: false,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
        },
        streetViewControlOptions: {
            position: google.maps.ControlPosition.LEFT_BOTTOM
        },
    });

    geocoder = new google.maps.Geocoder();

    // geocoder2 = new google.maps.Geocoder;
    usermarker = new google.maps.Marker({
        position: {
            lat: currentlocation.lat,
            lng: currentlocation.lng
        },
        map: map2,
        draggable: true,

        animation: google.maps.Animation.BOUNCE
    });


    //map click
    google.maps.event.addListener(map2, 'click', function(event) {
        updatepostition(event.latLng, "movemarker");
        //drag end event
        usermarker.addListener('dragend', function(event) {
            // alert();
            updatepostition(event.latLng, "movemarker");

        });
    });

    //drag end event
    usermarker.addListener('dragend', function(event) {
        // alert();
        updatepostition(event.latLng);

    });
}
updatepostition = function(position, movemarker) {
    geocodePosition(position);
    usermarker.setPosition(position);
    map2.panTo(position);
    map2.setZoom(15);
    let createLatLong = position.lat() + "," + position.lng();
    console.log("Address Lat/long=" + createLatLong);
    $("#location").val(createLatLong);
}

function geocodePosition(pos) {
    geocoder.geocode({
        latLng: pos
    }, function(responses) {
        if (responses && responses.length > 0) {
            usermarker.formatted_address = responses[0].formatted_address;
        } else {
            usermarker.formatted_address = 'Cannot determine address at this location.';
        }
        $('#txt_location').val(usermarker.formatted_address);
    });
}

function initAutocomplete() {
    // Create the search box and link it to the UI element.
    var input2 = document.getElementById('txt_location');
    var searchBox2 = new google.maps.places.SearchBox(input2);

    map2.addListener('bounds_changed', function() {
        searchBox2.setBounds(map2.getBounds());
    });

    searchBox2.addListener('places_changed', function() {
        var places2 = searchBox2.getPlaces();

        if (places2.length == 0) {
            return;
        }
        $('#txt_location').val(input2.value)

        var bounds2 = new google.maps.LatLngBounds();
        places2.forEach(function(place) {
            if (!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }

            updatepostition(place.geometry.location);

            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds2.union(place.geometry.viewport);
            } else {
                bounds2.extend(place.geometry.location);
            }
        });
        map2.fitBounds(bounds2);
    });
}
updatepostition = function(position, movemarker) {
    console.log(position);
    geocodePosition(position);
    usermarker.setPosition(position);
    map2.panTo(position);
    map2.setZoom(15);
    let createLatLong = position.lat() + "," + position.lng();
    // console.log("Address Lat/long="+createLatLong);
    $("#location").val(createLatLong);
}
</script>

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
                setTimeout(function() {
                    window.location.href = App.siteUrl('/admin/wholeSellers');
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

function password_show_hide() {
    var x = document.getElementById("password");
    var show_eye = document.getElementById("show_eye");
    var hide_eye = document.getElementById("hide_eye");
    hide_eye.classList.remove("d-none");
    if (x.type === "password") {
        x.type = "text";
        show_eye.style.display = "none";
        hide_eye.style.display = "block";
    } else {
        x.type = "password";
        show_eye.style.display = "block";
        hide_eye.style.display = "none";
    }
}

function password_show_hide2() {
    var x2 = document.getElementById("password2");
    var show_eye2 = document.getElementById("show_eye2");
    var hide_eye2 = document.getElementById("hide_eye2");
    hide_eye2.classList.remove("d-none");
    if (x2.type === "password") {
        x2.type = "text";
        show_eye2.style.display = "none";
        hide_eye2.style.display = "block";
    } else {
        x2.type = "password";
        show_eye2.style.display = "block";
        hide_eye2.style.display = "none";
    }
}
</script>

@stop