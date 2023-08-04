@extends('admin.template.layout')

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css"
    href="{{ asset('') }}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop

@section('content')
<div class="card mb-5">
    <div class="card-header">
        @if(check_permission('industry','Create'))
        <a href="{{ url('admin/industry_type/create') }}" class="btn-custom btn mr-2 mt-2 mb-2"><i
                class="fa-solid fa-plus"></i> Create Industry
            Type</a>
        @endif
        <a href="{{ url('admin/industry_type/sort') }}" class="btn btn-warning mb-2 mt-2 btn-rounded d-nones">Sort</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-condensed table-striped" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Is Active</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @foreach ($industry_types as $industry_type)
                    <?php $i++; ?>
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $industry_type->name }}</td>
                        <td>
                            <label class="switch s-icons s-outline  s-outline-warning  mb-4 mr-2">
                                <input type="checkbox" class="change_status" data-id="{{ $industry_type->id }}"
                                    data-url="{{ (url('admin/industry_type/change_status')) }}" @if ($industry_type ->
                                active) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>{{web_date_in_timezone($industry_type->created_at,'d-M-Y h:i A')}}</td>
                        <td class="text-center">
                            <div class="dropdown custom-dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="flaticon-dot-three"></i>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink7">
                                    @if(check_permission('industry','Edit'))
                                    <a class="dropdown-item"
                                        href="{{ url('admin/industry_type/edit/' . $industry_type->id) }}"><i
                                            class="flaticon-pencil-1"></i> Edit</a>
                                    @endif

                                    @if(check_permission('industry','Delete'))
                                    <a class="dropdown-item" data-role="unlink"
                                        data-message="Do you want to remove this industry_type?"
                                        href="{{ url('admin/industry_type/delete/' . $industry_type->id) }}"><i
                                            class="flaticon-delete-1"></i> Delete</a>
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
@stop

@section('script')
<script src="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.js"></script>
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