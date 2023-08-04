@extends("admin.template.layout")

@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin_assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin_assets/plugins/table/datatable/custom_dt_customer.css">
@stop

@section("content")
<div class="card mb-5">
    @if(check_permission('notification','Create'))
    <div class="card-header">
        <a href="{{ route('admin.notifications.add')}}" class="btn-custom btn mr-2 mt-2 mb-2"><i class="fa-solid fa-plus"></i> Add New</a>
    </div>
    @endif
    <div class="card-body">

        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing p-0">
            @if ( session('message'))
            <div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong> {{ session('message') }} </strong>
            </div>
            @endif
            <div class="statbox widget box box-shadow">
                 <div class="table-responsive">
                           <table class="table table-condensed table-striped" id="example2">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Image</th>
                                    <th>Created Date</th>
                                     <th>Actions</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notification_list as $key => $list)
                                <tr>

                                    <td>{{ $key  + 1 }}</td>
                                    <td>{{ $list->title }}</td>
                                    <td>{{ $list->description }}</td>
                                    @if($list->image)
                                    <td><img src="{{ $list->image }}" style="width: 100px;height: 100px;" /></td>                                    
                                    @else
                                    <td></td>
                                    @endif
                                    <td>{{web_date_in_timezone($list->created_at,'d-M-Y h:i A')}}</td>
                                     <td>
                                        <ul class="table-controls">
                                            @if(check_permission('notification','Delete'))
                                            <li>
                                              <a class="dropdown-item" data-role="unlink" data-message="Do you want to remove this notification?" href="{{url('admin/notifications/delete/'.$list->id)}}"><i class="flaticon-delete-1"></i></a>
                                            </li>
                                            @endif
                                        </ul>
                                    </td> 
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


     @endsection

@section('footerJs')
   <script src="{{asset('')}}admin_assets/plugins/table/datatable/datatables.js"></script>
<script>
$('#example2').DataTable({
      "paging": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });</script>

        @endsection