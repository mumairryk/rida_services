@extends('admin.template.layout')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-user">
                <div class="card-body">
                    <div class="author">
                        <a href="#">
                            <img class="avatar border-gray" src="{{empty($vendor->user_image) ? asset('uploads/place_holder.jpg'): asset($vendor->user_image) }}" style="width:60px;height:60px;" alt="...">
                            <h5 class="title">{{ $vendor->name }}</h5>
                        </a>
                        <p class="description">
                            {{ $vendor->email }}
                        </p>
                        <p class="description">
                            {{ $vendor->mobile_no }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
