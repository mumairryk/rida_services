@extends('admin.template.layout')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-user">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped" id="example2">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Order No</th>
                                <th>Customer</th>
                                <th>Title</th>
                                <th>Comment</th>
                                <th>Rating</th>
                                <th>Created</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datamain as $i=>$datarow)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ config('global.sale_order_prefix').date(date('Ymd', strtotime($datarow->order->created_at))).$datarow->order_id }}</td>
                                    <td>{{ @$datarow->order->customer->name }}</td>
                                    <td>{{ $datarow->title }}</td>
                                    <td>{{ $datarow->comment }}</td>
                                    <td>{{ $datarow->rating }}</td>
                                    <td>{{ $datarow->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endsection
