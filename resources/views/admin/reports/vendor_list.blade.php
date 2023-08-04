@extends('admin.template.layout')
@section('header')
@stop
@section('content')
<div class="card mb-5">
   <div class="card-body">
      <div class="row">
         <form method="get" action='' class="col-sm-12 col-md-12">
            <div id="column-filter_filter" class="dataTables_filter">
               <div class="row align-items-end">
                  <div class="col-lg-3">
                     <label class="w-100">From Date:
                     <input type="text" name="from_date"
                        class="form-control form-control-sm flatpickr-input" placeholder=""
                        aria-controls="column-filter" value="{{ $from_date }}">
                     </label>
                  </div>
                  <div class="col-lg-3">
                     <label class="w-100">To Date:
                     <input type="text" name="to_date"
                        class="form-control form-control-sm flatpickr-input" placeholder=""
                        aria-controls="column-filter" value="{{ $to_date }}">
                     </label>
                  </div>
                  <div class="col-lg-4">
                     <div class=" mb-1">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <input type="submit" name="excel" value="Export" class="btn btn-primary">
                        <a href="{{ url('admin/report/vendors') }}" class="btn btn-primary">Clear</a>
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
      <div class="table-responsive">
         <table class="table table-condensed table-striped">
            <thead>
               <tr>
                  <th>#</th>
                  <th>Vendor Details</th>
                  <th>Registration Type</th>
                  <th>Created</th>
               </tr>
            </thead>
            <tbody>
               <?php $i = $list->perPage() * ($list->currentPage() - 1); ?>
               @foreach ($list as $datarow)
               <?php $i++; ?>
               <tr>
                  <td>{{ $i }}</td>
                  <td>
                     <div>
                        <a href="#" class="yellow-color">
                        {{ $datarow->name }}
                        </a>
                     </div>
                     <span class="">
                     {{ $datarow->email }}
                     </span> <br>
                     <span class="">
                     +{{ $datarow->dial_code }} {{ $datarow->phone }}
                     </span>
                  </td>
                  <td>
                    <?php 
                        $type="Commercial Centers";
                        if($datarow->user_type_id==2){ $type="Reservations"; }
                        if($datarow->user_type_id==3){ $type="Individuals"; }
                        if($datarow->user_type_id==4){ $type="Service Providers"; }
                        if($datarow->user_type_id==5){ $type="WholeSellers"; }
                        if($datarow->user_type_id==6){ $type="Delivery Representative"; }
                     ?>
                     {{$type}}
                  </td>
                  
                  <td>{{web_date_in_timezone($datarow->created_at,'d-M-Y h:i A')}}</td>
               </tr>
               @endforeach
            </tbody>
         </table>
         <span>Total {{ $list->total() }} entries</span>
         <div class="col-sm-12 col-md-12 pull-right">
            <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
               {!! $list->appends(request()->input())->links('admin.template.pagination') !!}
            </div>
         </div>
      </div>
   </div>
</div>
@stop
@section('script')
@stop