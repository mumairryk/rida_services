@extends('vendor.template.layout')
@php use Illuminate\Support\Facades\DB; @endphp
@section('header')
    <link href="{{ asset('') }}admin-assets/assets/css/support-chat.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}admin-assets/plugins/maps/vector/jvector/jquery-jvectormap-2.0.3.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}admin-assets/plugins/charts/chartist/chartist.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('') }}admin-assets/assets/css/default-dashboard/style.css" rel="stylesheet" type="text/css" />
@stop

<?php 
$role = Auth::user()->role;

if($role == 4) //store manager
{
    $privileges = \App\Models\UserPrivileges::privilege();
    $privileges = json_decode($privileges, true);
} ?>



@section('content')

<div class="row mb-2">
        <div class="col-lg-12 mb-4">
            <!--<ul class="nav nav-pills mb-3 custom-pills justify-content-around" id="pills-tab" role="tablist">-->
                
            <!--    <li class="nav-item">-->
            <!--        <a class="nav-link active" id="pills-stores-tab" href="{{url('vendor/orders')}}" role="tab" aria-controls="pills-stores" aria-selected="false">-->
            <!--            <i class='bx bx-cart-alt' ></i> <span>Orders</span>-->
            <!--        </a>-->
            <!--    </li>-->

            <!--    <li class="nav-item">-->
            <!--        <a class="nav-link" id="pills-stores-tab" href="{{url('vendor/videos')}}" role="tab" aria-controls="pills-stores" aria-selected="false">-->
            <!--            <i class='bx bx-video' ></i> <span>Videos</span>-->
            <!--        </a>-->
            <!--    </li>-->

            <!--    <li class="nav-item">-->
            <!--        <a class="nav-link" id="pills-stores-tab" href="{{url('vendor/pictures')}}" role="tab" aria-controls="pills-stores" aria-selected="false">-->
            <!--            <i class='bx bx-image-alt' ></i> <span>Pictures</span>-->
            <!--        </a>-->
            <!--    </li>-->
               
                
            <!--</ul>-->
            
            <div class="row">
   
   
   <div class="col-xl-3 col-lg-3 col-sm-6 mb-30">
      <a href="{{ url('vendor/products') }}" class="icon-card height-100 text-center align-items-center" style="height:100%; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 10px;">
         <div class="icon orange m-0">
               <i class="bx bx-box"></i>
         </div>
         <div class="content m-0">
               <h6 class="mb-0">Inventory</h6>
         </div>
      </a>
      <!-- End Icon Cart -->
   </div>
   <div class="col-xl-3 col-lg-3 col-sm-6 mb-30">
      <a href="{{ url('vendor/orders') }}" class="icon-card height-100 text-center align-items-center" style="height:100%; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 10px;">
         <div class="icon purple m-0">
               <i class="bx bx-cart"></i>
         </div>
         <div class="content m-0">
               <h6 class="mb-0">Orders</h6>
         </div>
      </a>
      <!-- End Icon Cart -->
   </div>
</div>


            <!--<div class="tab-content" id="pills-tabContent">-->
            <!--    <div class="tab-pane fade  show active" id="pills-stores" role="tabpanel" aria-labelledby="pills-stores-tab">-->
            <!--        <div class="card custom-card">-->
            <!--            <div class="row align-items-center">-->
            <!--                <div class="col-lg-6">-->
            <!--                    <div class="">-->
            <!--                        <div class="mb-2">-->
            <!--                            <h6 class="text-xl">Lorem Ipsum</h6>-->
            <!--                            <p class="text-xxs mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>-->
            <!--                        </div>-->
            <!--                        <div class="mb-0">-->
            <!--                            <h6 class="text-xl">Lorem Ipsum</h6>-->
            <!--                            <p class="text-xxs mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--                <div class="col-lg-6">-->
            <!--                    <div class="progress-circle">-->
            <!--                        <div role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="--value: 75"></div>-->
            <!--                        <div role="progressbar" aria-valuenow="71" aria-valuemin="0" aria-valuemax="100" style="--value: 71"></div>-->
            <!--                        <div role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="--value: 30"></div>-->
            <!--                        <div role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="--value: 45"></div>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--    <div class="tab-pane fade" id="pills-managers" role="tabpanel" aria-labelledby="pills-managers-tab">-->
            <!--        <div class="card custom-card">-->
            <!--                <div class="row align-items-center">-->
            <!--                    <div class="col-lg-6">-->
            <!--                        <div class="">-->
            <!--                            <div class="mb-2">-->
            <!--                                <h6 class="text-xl">Lorem Ipsum</h6>-->
            <!--                                <p class="text-xxs mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>-->
            <!--                            </div>-->
            <!--                            <div class="mb-0">-->
            <!--                                <h6 class="text-xl">Lorem Ipsum</h6>-->
            <!--                                <p class="text-xxs mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>-->
            <!--                            </div>-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                    <div class="col-lg-6">-->
            <!--                        <div class="progress-circle">-->
            <!--                            <div role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="--value: 75"></div>-->
            <!--                            <div role="progressbar" aria-valuenow="71" aria-valuemin="0" aria-valuemax="100" style="--value: 71"></div>-->
            <!--                            <div role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="--value: 30"></div>-->
            <!--                            <div role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="--value: 45"></div>-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--    <div class="tab-pane fade" id="pills-designations" role="tabpanel" aria-labelledby="pills-designations-tab">-->
            <!--        <div class="card custom-card">-->
            <!--                    <div class="row align-items-center">-->
            <!--                        <div class="col-lg-6">-->
            <!--                            <div class="">-->
            <!--                                <div class="mb-2">-->
            <!--                                    <h6 class="text-xl">Lorem Ipsum</h6>-->
            <!--                                    <p class="text-xxs mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>-->
            <!--                                </div>-->
            <!--                                <div class="mb-0">-->
            <!--                                    <h6 class="text-xl">Lorem Ipsum</h6>-->
            <!--                                    <p class="text-xxs mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>-->
            <!--                                </div>-->
            <!--                            </div>-->
            <!--                        </div>-->
            <!--                        <div class="col-lg-6">-->
            <!--                            <div class="progress-circle">-->
            <!--                                <div role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="--value: 75"></div>-->
            <!--                                <div role="progressbar" aria-valuenow="71" aria-valuemin="0" aria-valuemax="100" style="--value: 71"></div>-->
            <!--                                <div role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="--value: 30"></div>-->
            <!--                                <div role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="--value: 45"></div>-->
            <!--                            </div>-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--            </div>-->
            <!--    </div>-->
            <!--    <div class="tab-pane fade" id="pills-product" role="tabpanel" aria-labelledby="pills-product-tab">-->
            <!--        <div class="card custom-card">-->
            <!--            <div class="row align-items-center">-->
            <!--                <div class="col-lg-6">-->
            <!--                    <div class="">-->
            <!--                        <div class="mb-2">-->
            <!--                            <h6 class="text-xl">Lorem Ipsum</h6>-->
            <!--                            <p class="text-xxs mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>-->
            <!--                        </div>-->
            <!--                        <div class="mb-0">-->
            <!--                            <h6 class="text-xl">Lorem Ipsum</h6>-->
            <!--                            <p class="text-xxs mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--                <div class="col-lg-6">-->
            <!--                    <div class="progress-circle">-->
            <!--                        <div role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="--value: 75"></div>-->
            <!--                        <div role="progressbar" aria-valuenow="71" aria-valuemin="0" aria-valuemax="100" style="--value: 71"></div>-->
            <!--                        <div role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="--value: 30"></div>-->
            <!--                        <div role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="--value: 45"></div>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
                
            <!--</div>-->
        </div>
        <div class="col-lg-4 mb-4" style="display:none;">
            <div class="card custom-card">
                <div class="progress-bars">
                    <span>
                        <div class="d-flex justify-content-between">
                                <h6 class="text-xsl">LOREM IPSUM</h6>
                                <p class="text-xxs mb-0">Lorem Ipsum is simply dummy text</p>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-1" role="progressbar" style="width: 0%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="30"></div>
                        </div>
                    </span>
                    <span class="mb-2">
                        <div class="d-flex justify-content-between">
                                <h6 class="text-xsl">LOREM IPSUM</h6>
                                <p class="text-xxs mb-0">Lorem Ipsum is simply dummy text</p>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-2" role="progressbar" style="width: 0%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="30"></div>
                        </div>
                    </span>
                    <div class="chart" style="position: relative; height: 22vh; overflow: hidden;">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

     
    <div class="row  mb-4">
   <div class="col-12">
      <div class="card custom-card">
         <div class="card-header "> <b style="color: black;">Latest Orders</b></div>
         <table class="table table-responsive recent-orders-table mb-0"
            style="max-height: 330px; overflow-y: scroll;">
            <thead>
               <tr>
                  <th scope="col" width="10%">Order ID</th>
                  <th scope="col" width="25%">Customer</th>
                  <th scope="col" width="25%">Order Date</th>
                  <th scope="col" width="15%">Total</th>
                  <th scope="col" width="15%">Status</th>
                  <th scope="col" width="10%">View</th>
               </tr>
            </thead>
            <tbody>
               @if($latest_orders->isEmpty())
               <tr>
                  <td colspan="6"> No Data</td>
               </tr>
               @endif
               @foreach ($latest_orders as $item)
               <tr>
                  <td><?php echo config('global.sale_order_prefix') . date(date('Ymd', strtotime($item->created_at))) . $item->order_id; ?></td>
                  <td>{{ $item->name ?? $item->customer_name }}</td>
                  <td>{{ web_date_in_timezone($item->booking_date, 'M d h:i A') }}</td>
                  <td>AED {{ $item->grand_total }}</td>
                  <td><span class="badge badge-info"> {{ $item->status_text }}</span></td>
                  <td><a href="{{ url('vendor/order_details/' . $item->order_id) }}"><span
                     class="badge badge-success"> Details</span></a></td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
  </div>

{{--
    <div class="row layout-spacing d-none">

        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 mb-sm-0 mb-4">
            <a href="{{ url('vendor/store') }}">
                <div class="widget-content-area  data-widgets br-4">
                    <div class="widget  t-customer-widget">

                        <div class="media">
                            <div class="icon ml-2">
                                <i class="flaticon-users"></i>
                            </div>
                            <div class="media-body text-right">
                                <p class="widget-text mb-0">Stores</p>
                                <p class="widget-numeric-value">
                                    {{ DB::table('stores')->where('deleted', 0)->where('vendor_id', auth()->user()->id)->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 mb-sm-0 mb-4">
            <a href="{{ url('vendor/store_managers') }}">
                <div class="widget-content-area  data-widgets br-4">
                    <div class="widget  t-customer-widget">

                        <div class="media">
                            <div class="icon ml-2">
                                <i class="flaticon-users"></i>
                            </div>
                            <div class="media-body text-right">
                                <p class="widget-text mb-0">Managers</p>
                                <p class="widget-numeric-value">{{ DB::table('users')->where('role', 4)->where('deleted', 0)->where('vendor', auth()->user()->id)->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>




    </div> --}}








    </div>
@stop

@section('footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js'></script> -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0-rc"></script>
@stop

@section('script')
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'line', // also try bar or other graph types

            // The data for our dataset
            data: {
                labels: ["100", "200", "300", "400", "500", "600", "700", "800", "900", "1000", "1100","1200", "1300", "1400", "1500", "1600", "1700"],
                // Information about the dataset
                datasets: [{
                    label: "",
                    fill: true,
                    backgroundColor: 'rgb(204 155 68)',
                    borderColor: 'rgb(235 192 94)',
                    data: [10, 200, 150, 50, 180, 80, 150, 60, 130, 90, 135, 85, 165, 78, 138, 48, 158],
                }]
            },

            // Configuration options
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    x: {
                        position: 'top',
                        grid: {
                            color: '#FAF0E6'
                        },
                        ticks: {
                            maxRotation: 0,
                            minRotation: 0,
                            font: {
                                size: 5
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: '#FAF0E6'
                        },
                        ticks: {
                            display: false
                        }
                        
                    },
                },
                plugins: {
                    legend: {
                        display: false
                    },
                }
            }
        });

        var orderschartctx = document.getElementById("orderschart");
var myChart = new Chart(orderschartctx, {
  type: 'doughnut',
  data: {
    labels: ['Order Pending', 'Order Dispatched', 'Order Completed', 'Order Cancelled'],
    datasets: [{
      label: '',
      data: [12, 19, 3, 5],
      backgroundColor: [
        'rgba(202, 153, 67, 0.8)',
        'rgba(0, 0, 0, 0.8)',
        'rgba(235, 192, 94, 0.8)',
        'rgba(73, 0, 0, 0.8)'
      ],
      borderColor: [
        'rgba(202, 153, 67, 0.8)',
        'rgba(0, 0, 0, 0.8)',
        'rgba(235, 192, 94, 0.8)',
        'rgba(73, 0, 0, 0.8)'
      ],
      borderWidth: 2
    }]
  },
  options:{
    cutout: 60,
    centerPercentage: 80,
    responsive: true,
    maintainAspectRatio: false,
    tooltips: {
        enabled: true
    },
    interaction: {
      intersect: false
    },
    plugins: {
      legend: {
        display: true,
        position: 'bottom',
        
        labels: {
            font: {
                size: 10
            },
            boxWidth: 10
        }
      }
    },
  }
});
    </script>
@stop
