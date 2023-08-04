@extends("vendor.template.layout")

@section('header')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop

@section('content')
    <div class="card mb-5">
        @if(check_permission('stores','Create'))
        <div class="card-header">
        </div>
        @endif
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-condensed table-striped" id="example2">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Video </th>
                            <th>Vendor</th>
                            <th>Is Active</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($list->total() > 0)


                            <?php $i = $list->perPage() * ($list->currentPage() - 1); ?>
                            @foreach ($list as $item)
                                <?php   $i++; ?>
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>
                                        <a href="{{asset($item->video)}}" target="_blank" rel="noopener noreferrer">
                                            <video width="150" height="150">
                                                <source src="{{asset($item->video)}}">
                                            </video>
                                        </a>
                                      </td>

                                    <td>{{ $item->vendor }}</td>
                                    <td>
                                        <label class="switch s-icons s-outline  s-outline-warning  mb-4 mr-2">
                                            <input type="checkbox" class="change_status" data-id="{{ $item->id }}"data-url="{{ url('vendor/videos/change_status') }}" @if ($item->active) checked @endif> <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        {{web_date_in_timezone($item->created_at,'d-M-Y h:i A')}}
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown custom-dropdown">
                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                <i class="flaticon-dot-three"></i>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink7">
                                                <a class="dropdown-item" data-role="unlink"
                                                data-message="Do you want to remove this Video?"
                                                href="{{ url('vendor/videos/delete/' . $item->id) }}"><i
                                                    class="flaticon-delete-1"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="12" align="center" class="pt-2 p-0">

                                    <div class="alert alert-warning">
                                        <p>No Videos found</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                </table>
            </div>
            <div class="col-sm-12 col-md-12 pull-right">
                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                    {!! $list->appends(request()->all())->links('admin.template.pagination') !!}
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')

@stop
