@extends("admin.template.layout")

@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin_assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin_assets/plugins/table/datatable/custom_dt_customer.css">
@stop


@section("content")
<div class="card mb-5">
    @if(check_permission('enquiry','Create'))
    @php $param ="";@endphp
    @if($question_for)
    @php $param = "?question_for=".$question_for; @endphp
    @endif
   
    @endif
    <div class="card-body">
    <div class="dataTables_wrapper container-fluid dt-bootstrap4">
    @if($list->total() > 0)

    <div class="row">
        <!-- <div class="col-sm-12 col-md-6">
            <div class="dataTables_length" id="column-filter_length">
            </div>
        </div> -->
        
        <form method="get" action='' class="col-sm-12 col-md-8">
            <div id="column-filter_filter" class="dataTables_filter">
                <div class="row align-items-end">
                    <div class="col-md-6">
                        <label class="w-100">Search:
                            <input type="search" name="search_key" class="form-control form-control-sm" placeholder="" aria-controls="column-filter" value="{{$search_key}}">
                            <input type="hidden" name="question_for" value="{{$question_for??''}}">
                        </label>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary mb-1">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
        <table class="table table-condensed table-striped">
            <thead>
                <tr>
                <th>#</th>
                <th>ID</th>
                <th>User</th>
                <th>Phone</th>
                <th>Date</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php $i = $list->perPage() * ($list->currentPage() - 1); ?>
            @foreach($list as $item)
            <?php $i++; ?>
               <tr>
                   <td>{{$i}}</td>
                   <td>#{{$item->id}}</td>
                   <td>{{$item->name}}</td>
                   <td>+{{$item->dial_code}} {{$item->phone}}</td>
                   <td>{{web_date_in_timezone($item->created_at,'d-M-Y h:i A')}}</td>
                   <td class="text-center">
                            <div class="dropdown custom-dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="flaticon-dot-three"></i>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink7">
                                    @if(check_permission('enquiry','View'))
                                    <a class="dropdown-item" href="{{url('admin/enquiry/details/'.$item->id)}}"><i class="flaticon-057-eye"></i> View</a>
                                    @endif
                                   
                                </div>
                            </div>
                        </td>
               </tr>
            @endforeach
            </tbody>
        </table>
       
            
            <div class="col-sm-12 col-md-12 pull-right">
                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                {!! $list->links('admin.template.pagination') !!}
                </div>
            </div>
        
        @else
        <br>
        <div class="alert alert-warning">
            <p>No Enquiry found</p>
        </div>
        @endif
    </div>
    </div>
</div>
@stop

@section("script")
<script src="{{asset('')}}admin_assets/plugins/table/datatable/datatables.js"></script>
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
@stop