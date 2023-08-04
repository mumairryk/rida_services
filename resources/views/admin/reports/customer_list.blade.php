@extends("admin.template.layout")

@section("header")
<style>
.dataTables_filter {
    display: flex;
    justify-content: center;
    align-items: end;
    gap: 20px;
    flex-wrap: wrap;
}

.dataTables_filter label,
.dataTables_filter button,
.dataTables_filter input.btn,
.dataTables_filter a.btn {
    flex: 1 1 auto;
    margin: 0;
}
</style>
@stop


@section("content")
<div class="card">

    <div class="card-body">
        <div class="row">
            <form method="get" action='' class="col-sm-12 col-md-12">
                <div id="column-filter_filter" class="dataTables_filter">
                    <label>From Date:
                        <input type="date" name="from_date" class="form-control form-control-sm" placeholder=""
                            aria-controls="column-filter" value="{{$from_date}}">
                    </label>
                    <label>To Date:
                        <input type="date" name="to_date" class="form-control form-control-sm" placeholder=""
                            aria-controls="column-filter" value="{{$to_date}}">
                    </label>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <input type="submit" name="excel" value="Export" class="btn btn-primary">
                    <a href="{{url('admin/report/customers')}}" class="btn btn-primary">Clear</a>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-condensed table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile No</th>
                        <th>Address</th>
                        <th>Country/State/City</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=0; ?>
                    @foreach($list as $datarow)
                    <?php $i++ ?>
                    <tr>
                        <td>{{$i}}</td>

                        <td>{{$datarow->first_name}} {{$datarow->last_name}} </td>
                        <td>{{$datarow->email}}</td>
                        <td>+{{$datarow->dial_code}} {{$datarow->phone}}</td>
                        <td>
                            @if(!empty($datarow->vendordata))
                            {{$datarow->vendordata->address1}}<br>
                            {{$datarow->vendordata->address2}}<br>
                            {{$datarow->vendordata->street}}
                            @endif
                        </td>
                        <td>
                            @if(!empty($datarow->country))
                            {{$datarow->country->name}},{{$datarow->state->name}},{{$datarow->city->name}}
                            @endif
                        </td>
                        <td>{{$datarow->created_at}}</td>


                    </tr>
                    @endforeach
                </tbody>
            </table>
            <span>Total {{ $list->total() }} entries</span>

            <div class="col-sm-12 col-md-12 pull-right">
                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                    {{-- {!! $list->links('admin.template.pagination') !!} --}}
                    {!! $list->appends(request()->input())->links('admin.template.pagination') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section("script")

@stop