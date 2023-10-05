@extends("admin.template.layout")

@section('header')
@stop


@section('content')
    <div class="card mb-5">
      
        <div class="card-body">
            <form method="post" action="{{ url('/admin/questions') }}" id="admin-form" enctype="multipart/form-data" data-parsley-validate="true">
                @csrf
                <input type="hidden" name="id" value="{{$datamain->id??''}}">
                <div class="row  d-flex justify-content-between align-items-center">
                    <div class="col-md-6 form-group">
                        <label>Question<b class="text-danger">*</b></label>
                        <input type="text" name="question" class="form-control jqv-input" value="{{$datamain->question??''}}" data-jqv-required="true" required
                            data-parsley-required-message="Question required">
                    </div>


                    <div class="col-md-6 form-group">
                        <label>Question For</label>
                        <select name="question_for" class="form-control" required>
                            <option value="">Select</option>
                            <option value="1" @if(!empty($datamain)) {{$datamain->question_for==1 ? "selected" : null}} @endif>Interior Designing</option>
                            <option value="2" @if(!empty($datamain)) {{$datamain->question_for==2 ? "selected" : null}} @endif>Contracting</option>
                            <option value="3" @if(!empty($datamain)) {{$datamain->question_for==3 ? "selected" : null}} @endif>Investing</option>
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Status</label>
                        <select name="active" class="form-control">
                            <option value="1" @if(!empty($datamain)) {{$datamain->active==1 ? "selected" : null}} @endif>Active</option>
                            <option value="0" @if(!empty($datamain)) {{$datamain->active==0 ? "selected" : null}} @endif>Inactive</option>
                        </select>
                    </div>

                     

                    <div class="col-md-6 form-group">
                        <label>Answer Type</label>
                        <select name="answer_type" class="form-control" required id="answertype">
                            <option value="">Select</option>
                            <option value="1" @if(!empty($datamain)) {{$datamain->answer_type==1 ? "selected" : null}} @endif>Text field</option>
                            <option value="2" @if(!empty($datamain)) {{$datamain->answer_type==2 ? "selected" : null}} @endif>Textarea</option>
                            <option value="3" @if(!empty($datamain)) {{$datamain->answer_type==3 ? "selected" : null}} @endif>Radio</option>
                            <option value="4" @if(!empty($datamain)) {{$datamain->answer_type==4 ? "selected" : null}} @endif>Check Box</option>
                        </select>
                    </div>

                    

                    </div>
                   <div class="row" id="datarowsadd" style="display:none;">
                    @if(count($options) > 0)
                    @foreach($options as $key=>$value)
                    <div class="row col-md-12"><div class="col-md-6 form-group">
                        <label>Options<b class="text-danger">*</b></label>
                         <input type="text" name="option[]" value="{{$value->options}}" class="form-control jqv-input" data-jqv-required="true" required
                            data-parsley-required-message="Option required">
                    </div>
                    <div class="col-md-6 form-group">
                        <br>
                       <button type="button" class="btn-custom @if($key == 0) addmore @else remove @endif"> @if($key == 0) Add More @else Remove @endif</button>
                        
                    </div></div>
                    @endforeach
                    @else
                    <div class="col-md-6 form-group">
                        <label>Options<b class="text-danger">*</b></label>
                         <input type="text" name="option[]" class="form-control jqv-input" data-jqv-required="true" required
                            data-parsley-required-message="Option required">
                    </div>
                    <div class="col-md-6 form-group">
                        <br>
                       <button type="button" class="btn-custom addmore"> Add More</button>
                        
                    </div>
                    @endif

                    </div>
                    <div class="row">
                 
                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop


@section('script')
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
                            window.location.href = App.siteUrl('/admin/questions');
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

$('body').off('change', '#answertype');
$('body').on('change', '#answertype', function(e) {
  $('#datarowsadd').hide();
  $('#datarowsadd').find('[required]').prop('required', false);
  var ans = $(this).val();
  if(ans == 3 || ans == 4)
  {
   $('#datarowsadd').show();  
   $('#datarowsadd').find('[required]').prop('required', true);
  }
});

$('body').off('click', '.addmore');
$('body').on('click', '.addmore', function(e) {
  $('#datarowsadd').append('<div class="row col-md-12"><div class="col-md-6 form-group"><input type="text" name="option[]" class="form-control jqv-input" data-jqv-required="true" required data-parsley-required-message="Option required"></div><div class="col-md-6 form-group"><button type="button" class="btn-custom remove"> Remove</button></div></div');
});
$('body').off('click', '.remove');
$('body').on('click', '.remove', function(e) {
  $(this).parent().parent().remove();

});
$(function(){
$("#answertype").trigger("change");
});
    </script>
@stop
