@extends('admin.template.layout')
@section('header')
<link href="{{ asset('') }}admin-assets/assets/css/support-chat.css" rel="stylesheet" type="text/css" />
<link href="{{ asset('') }}admin-assets/plugins/maps/vector/jvector/jquery-jvectormap-2.0.3.css" rel="stylesheet"
   type="text/css" />
<link href="{{ asset('') }}admin-assets/plugins/charts/chartist/chartist.css" rel="stylesheet" type="text/css">
<link href="{{ asset('') }}admin-assets/assets/css/default-dashboard/style.css" rel="stylesheet" type="text/css" />

@stop
@section('content')
<style>
   .home-section footer {
   bottom: auto !important;
   }

   @media(min-width:992px){
      .custom-pr{
         padding-right: 5px;
      }
      .custom-pl{
         padding-left: 5px;
      }
   }
</style>
<div class="row">
   <div class="col-xl-3 col-lg-3 col-sm-6 mb-30">
      <a href="{{ url('admin/customers') }}" class="icon-card height-100 text-center align-items-center" style="height:100%; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 10px;">
         <div class="icon purple m-0">
               <i class="bx bx-user"></i>
         </div>
         <div class="content m-0">
               <h6 class="mb-0">Customers</h6>
         </div>
      </a>
      <!-- End Icon Cart -->
   </div>
   <div class="col-xl-3 col-lg-3 col-sm-6 mb-30">
      <a href="{{ url('admin/vendors') }}" class="icon-card height-100 text-center align-items-center" style="height:100%; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 10px;">
         <div class="icon success m-0">
         <svg width="23" height="25" viewBox="0 0 23 25" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M5.79772 2.19191C2.72946 3.23216 0.167012 4.12448 0.104979 4.16743L0 4.24855V6.53423C0 7.78921 0.0143154 8.85332 0.0286307 8.89627C0.0954357 9.06805 0.300622 9.111 1.04979 9.111H1.76556V15.2618V21.4174L0.973444 21.4317C0.209959 21.4461 0.181328 21.4508 0.0906639 21.5606C0.00477178 21.6656 0 21.761 0 22.973C0 24.1851 0.00477178 24.2805 0.0906639 24.3855L0.181328 24.5H11.5H22.8187L22.9093 24.3855C22.9952 24.2805 23 24.1851 23 22.973C23 21.761 22.9952 21.6656 22.9093 21.5606C22.8187 21.4508 22.79 21.4461 22.0266 21.4317L21.2344 21.4174V15.2618V9.111H21.9502C22.6994 9.111 22.9046 9.06805 22.9714 8.89627C22.9857 8.85332 23 7.78921 23 6.53901C23 4.38693 22.9952 4.25332 22.9141 4.17697C22.8139 4.09108 11.6098 0.278425 11.4714 0.287968C11.4189 0.29274 8.87075 1.14689 5.79772 2.19191ZM16.8539 2.94108L22.1888 4.74481V6.51992V8.29979H11.5H0.811203V6.51992V4.74481L6.1222 2.94585C9.03776 1.95809 11.4523 1.14689 11.4761 1.14212C11.5048 1.14212 13.9241 1.95332 16.8539 2.94108ZM20.4232 15.2666V21.4222H11.5H2.57676V15.2666V9.111H11.5H20.4232V15.2666ZM22.1888 22.973V23.7127H11.5H0.811203V22.973V22.2334H11.5H22.1888V22.973Z" fill="#219653"/>
<path d="M10.9415 3.02227C10.5025 3.16542 10.1016 3.52808 9.89167 3.9814C9.77714 4.22953 9.75806 4.32974 9.75806 4.69717C9.75806 5.05982 9.77714 5.16957 9.88689 5.39862C10.0539 5.76127 10.3593 6.08098 10.7124 6.26231C10.9796 6.40546 11.0321 6.41501 11.4998 6.41501C11.9578 6.41501 12.0247 6.40546 12.2537 6.2814C13.1413 5.81376 13.4896 4.84509 13.0935 3.95277C12.9456 3.61874 12.5591 3.237 12.2298 3.08908C11.9435 2.96024 11.2421 2.92206 10.9415 3.02227ZM11.9292 3.8621C12.3062 4.05298 12.5257 4.53015 12.4112 4.92144C12.1583 5.77559 11.0321 5.88534 10.6599 5.08845C10.4929 4.72103 10.5215 4.44426 10.7697 4.11024C11.0178 3.78098 11.5427 3.66646 11.9292 3.8621Z" fill="#219653"/>
<path d="M15.9803 9.86964C15.8945 9.94122 15.7942 10.1989 15.5557 10.9815L15.2407 11.9979L10.4785 12.0217L5.71624 12.0456L5.62558 12.1601C5.57786 12.2221 5.53491 12.3367 5.53491 12.4225C5.53491 12.5037 5.81645 13.4819 6.16002 14.5985C6.66105 16.24 6.80421 16.6456 6.89964 16.7315L7.01894 16.8412H10.7409H14.4629L14.5679 16.7315C14.6442 16.6551 14.9258 15.801 15.5986 13.6059L16.5291 10.5902H17.3976C18.1992 10.5902 18.2708 10.5854 18.3901 10.49C18.4903 10.4136 18.5142 10.3516 18.5142 10.1846C18.5142 10.0176 18.4903 9.95553 18.3901 9.87918C18.2708 9.78375 18.204 9.77898 17.1781 9.77898C16.1808 9.77898 16.0806 9.78852 15.9803 9.86964ZM8.57931 14.4028C8.84653 15.2665 9.06603 15.9823 9.06603 16.0014C9.06603 16.0157 8.70338 16.03 8.26437 16.03H7.45794L6.99031 14.5126C6.73263 13.6823 6.51313 12.9618 6.49881 12.914C6.47973 12.8377 6.54176 12.8329 7.28616 12.8329H8.09736L8.57931 14.4028ZM12.4301 13.0572C12.392 13.186 12.1725 13.897 11.9386 14.6462L11.5235 16.0062L10.7314 16.0205L9.93927 16.03L9.47163 14.5174C9.21396 13.6823 8.99446 12.9618 8.98014 12.914C8.96105 12.8377 9.06603 12.8329 10.7266 12.8329H12.4969L12.4301 13.0572ZM14.9592 12.8902C14.9496 12.9236 14.7301 13.6298 14.4772 14.4553C14.2243 15.2808 14.0048 15.9728 13.9857 15.9966C13.9667 16.0157 13.5945 16.0252 13.165 16.0205L12.3777 16.0062L12.8691 14.4315C13.1364 13.563 13.3559 12.852 13.3559 12.8425C13.3606 12.8377 13.7233 12.8329 14.1718 12.8329C14.7969 12.8329 14.9735 12.8472 14.9592 12.8902Z" fill="#219653"/>
<path d="M8.26987 17.6763C7.49684 18.0676 7.30597 19.1079 7.88813 19.7378C8.25555 20.1338 8.74228 20.2674 9.26717 20.1147C10.1977 19.8475 10.4935 18.6212 9.80161 17.9245C9.54394 17.6668 9.33398 17.5857 8.89974 17.5666C8.56095 17.5523 8.48937 17.5666 8.26987 17.6763ZM9.21468 18.5066C9.3817 18.6737 9.41033 18.8884 9.30057 19.1127C9.23854 19.232 9.01427 19.3703 8.87589 19.3703C8.71365 19.3703 8.49891 19.2367 8.41779 19.0793C8.16966 18.5925 8.82817 18.1201 9.21468 18.5066Z" fill="#219653"/>
<path d="M11.9866 17.6812C11.2183 18.0629 11.0275 19.1032 11.6096 19.7426C11.8577 20.0098 12.1154 20.1386 12.4542 20.1673C13.4229 20.2579 14.1196 19.3752 13.8333 18.416C13.7474 18.1297 13.3179 17.6955 13.0459 17.6144C12.7024 17.5142 12.2681 17.5428 11.9866 17.6812ZM12.9362 18.5067C13.0459 18.6212 13.0746 18.688 13.0746 18.8598C13.0746 19.0459 13.0507 19.0936 12.9075 19.2225C12.8121 19.3036 12.6833 19.3704 12.6165 19.3704C12.4351 19.3704 12.2252 19.2416 12.1393 19.0793C11.8911 18.5926 12.5497 18.1202 12.9362 18.5067Z" fill="#219653"/>
</svg>
            </div>
         <div class="content m-0">
               <h6 class="mb-0">Vendors</h6>
         </div>
      </a>
   </div>
   <div class="col-xl-3 col-lg-3 col-sm-6 mb-30">
      <a href="{{ url('admin/products') }}" class="icon-card height-100 text-center align-items-center" style="height:100%; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 10px;">
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
      <a href="{{ url('admin/orders') }}" class="icon-card height-100 text-center align-items-center" style="height:100%; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 10px;">
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
<div class="row" style="display:none;">
   <div class="col-xl-5 col-lg-6 mb-4 custom-pr">
      <div class="card h-100">
         <div class="card-body p-2">
            <canvas id="piechart" width="600" height="400"></canvas>
         </div>
      </div>
   </div>
   <div class="col-xl-7 col-lg-6 mb-4 custom-pl">
      <div class="card h-100">
         <div class="card-body p-2">
            <canvas id="chart" width="600" height="400"></canvas>
         </div>
      </div>
   </div>
</div>
{{-- <div class="row d-none">
   <div class="col-lg-12 mb-4">
      
      <div class="tab-content mt-5" id="pills-tabContent">
         <div class="tab-pane fade" id="pills-vendors" role="tabpanel" aria-labelledby="pills-vendors-tab">
            <div class="card custom-card">
               <div class="row align-items-center">
                  <div class="col-lg-6">
                     <div class="">
                        <div class="mb-2">
                           <h6 class="text-xl">Lorem Ipsum</h6>
                           <p class="text-xxs mb-0">Lorem Ipsum is simply dummy text of the printing and
                              typesetting industry.
                           </p>
                        </div>
                        <div class="mb-0">
                           <h6 class="text-xl">Lorem Ipsum</h6>
                           <p class="text-xxs mb-0">Lorem Ipsum is simply dummy text of the printing and
                              typesetting industry.
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="progress-circle">
                        <div role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                           style="--value: 75"></div>
                        <div role="progressbar" aria-valuenow="71" aria-valuemin="0" aria-valuemax="100"
                           style="--value: 71"></div>
                        <div role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"
                           style="--value: 30"></div>
                        <div role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"
                           style="--value: 45"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="tab-pane fade" id="pills-product" role="tabpanel" aria-labelledby="pills-product-tab">
            <div class="card custom-card">
               <div class="row align-items-center">
                  <div class="col-lg-6">
                     <div class="">
                        <div class="mb-2">
                           <h6 class="text-xl">Lorem Ipsum</h6>
                           <p class="text-xxs mb-0">Lorem Ipsum is simply dummy text of the printing and
                              typesetting industry.
                           </p>
                        </div>
                        <div class="mb-0">
                           <h6 class="text-xl">Lorem Ipsum</h6>
                           <p class="text-xxs mb-0">Lorem Ipsum is simply dummy text of the printing and
                              typesetting industry.
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="progress-circle">
                        <div role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                           style="--value: 75"></div>
                        <div role="progressbar" aria-valuenow="71" aria-valuemin="0" aria-valuemax="100"
                           style="--value: 71"></div>
                        <div role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"
                           style="--value: 30"></div>
                        <div role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"
                           style="--value: 45"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="tab-pane fade" id="pills-stores" role="tabpanel" aria-labelledby="pills-stores-tab">
            <div class="card custom-card">
               <div class="row align-items-center">
                  <div class="col-lg-6">
                     <div class="">
                        <div class="mb-2">
                           <h6 class="text-xl">Lorem Ipsum</h6>
                           <p class="text-xxs mb-0">Lorem Ipsum is simply dummy text of the printing and
                              typesetting industry.
                           </p>
                        </div>
                        <div class="mb-0">
                           <h6 class="text-xl">Lorem Ipsum</h6>
                           <p class="text-xxs mb-0">Lorem Ipsum is simply dummy text of the printing and
                              typesetting industry.
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="progress-circle">
                        <div role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                           style="--value: 75"></div>
                        <div role="progressbar" aria-valuenow="71" aria-valuemin="0" aria-valuemax="100"
                           style="--value: 71"></div>
                        <div role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"
                           style="--value: 30"></div>
                        <div role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"
                           style="--value: 45"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div> --}}
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
                  <td><a href="{{ url('admin/order_details/' . $item->order_id) }}"><span
                     class="badge badge-success"> Details</span></a></td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
   {{--<div class="col-6">
      <div class="card custom-card">
         <div class="card-header "> <b style="color: black;">Items Are Ready For Delivery</b></div>
         <table class="table table-responsive recent-orders-table mb-0"
            style="max-height: 330px; overflow-y: scroll;">
            <thead>
               <tr>
                  <th scope="col" width="10%">Order ID</th>
                  <th scope="col" width="40%">Product</th>
                  <th scope="col" width="25%">Order Date</th>
                  <th scope="col" width="15%">Total</th>
                  <th scope="col" width="10%">View</th>
               </tr>
            </thead>
            <tbody>
               @if($ready_for_delivery->isEmpty())
               <tr>
                  <td colspan="6"> No Data</td>
               </tr>
               @endif
               @foreach ($ready_for_delivery as $item)
               <tr>
                  <td><?php echo config('global.sale_order_prefix') . date(date('Ymd', strtotime($item->created_at))) . $item->order_id; ?></td>
                  <td>{{ $item->product_name }}</td>
                  <td>{{ web_date_in_timezone($item->booking_date, 'M d h:i A') }}</td>
                  <td>GBP {{ $item->total }}</td>
                  <td><a href="{{ url('admin/order_details/' . $item->order_id) }}"><span
                     class="badge badge-success"> Details</span></a></td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
</div> --}}
{{-- <div class="row mb-4">
   <div class="col-lg-12">
      <div class="row">
         <div class="col-12">
            <div class="row">
               <div class="col-lg-6">
                  <div class="card custom-card">
                     <b style="color: black;">Order Product Statistics</b>
                     <div class="chart" style="position: relative; height: 35vh; overflow: hidden;">
                           @if(count($st_count) > 0)
                              <canvas id="orderschart"></canvas>
                           @else    
                              <div id="no-data">Nothing to display</div>
                           @endif
                     </div>
                  </div>
               </div>
              
            </div>
            <div class="row mt-4 d-none">
               <div class="col-lg-12">
                  <div class="card custom-card">
                     <div class="col-lg-12">
                        <b style="color: black;">Vendor Registration</b>
                        <ul class="nav justify-content-sm-end justify-content-center monthly-chart-tab nav-pills"
                           id="monthly-chart" role="tablist">
                           <li class="nav-item">
                              <a class="nav-link active" id="monthly-chart-weekly-tab" data-toggle="pill"
                                 href="#monthly-chart-weekly" role="tab"
                                 aria-controls="monthly-chart-weekly" aria-selected="true">Last 7 Days</a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link" id="monthly-chart-monthly-tab" data-toggle="pill"
                                 href="#monthly-chart-monthly" role="tab"
                                 aria-controls="monthly-chart-monthly" aria-selected="true">Monthly</a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link" id="monthly-chart-yearly-tab" data-toggle="pill"
                                 href="#monthly-chart-yearly" role="tab"
                                 aria-controls="monthly-chart-yearly" aria-selected="false">Yearly</a>
                           </li>
                        </ul>
                     </div>
                     <div class="tab-content" id="monthly-chartContent">
                        <div class="tab-pane fade show active" id="monthly-chart-weekly" role="tabpanel"
                           aria-labelledby="monthly-chart-weekly-tab">
                           <div class="v-pv-weekly" style="height: 300px; width: 100%; margin-top: 30px;">
                           </div>
                        </div>
                        <div class="tab-pane fade" id="monthly-chart-monthly" role="tabpanel"
                           aria-labelledby="monthly-chart-monthly-tab">
                           <div class="v-pv-monthly" style="height: 300px; width: 100%; margin-top: 30px;">
                           </div>
                        
                        </div>
                        <div class="tab-pane fade" id="monthly-chart-yearly" role="tabpanel"
                           aria-labelledby="monthly-chart-yearly-tab">
                           <div class="v-pv-yearly" style="height: 300px; width: 100%; margin-top: 30px;">
                           </div>
                          
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            
         </div>
      </div>
   </div>
</div> --}}
</div>
@stop
@section('footer')
<script src="{{asset('')}}admin-assets/plugins/charts/chartist/chartist.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"
   integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA=="
   crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0-rc"></script>
@stop
@section('script')
<script>
   var orderschartctx = document.getElementById("orderschart");
   var myChart = new Chart(orderschartctx, {
       type: 'doughnut',
       data: {
           labels: ['Pending', 'Accepted', 'Ready For Delivery', 'Dispatched', 'Delivered', 'Cancelled',
               'Return Pending', 'Returned', 'Return Rejected'
           ],
           datasets: [{
               label: '',
               data: [{{ $st_count['pending_count'] }}, {{ $st_count['accepted_count'] }},
                   {{ $st_count['ready_for_delivery_count'] }},
                   {{ $st_count['dispatched_count'] }}, {{ $st_count['delivered_count'] }},
                   {{ $st_count['cancelled_count'] }}, {{ $st_count['return_pending_count'] }},
                   {{ $st_count['returned_count'] }}, {{ $st_count['return_rejected_count'] }}
               ],
               backgroundColor: [
                   'rgba(202, 153, 67, 0.8)',
                   'rgba(0, 0, 0, 0.8)',
                   'rgba(235, 192, 94, 0.8)',
                   'rgba(73, 0, 0, 0.8)',
                   'rgba(156, 210, 57, 0.8)',
                   'rgba(99, 68, 94, 0.8)',
                   'rgba(189, 100, 24, 0.8)',
                   'rgba(45, 28, 85, 0.8)',
                   'rgba(55, 222, 1, 0.8)',
               ],
               borderColor: [
                   'rgba(202, 153, 67, 0.8)',
                   'rgba(0, 0, 0, 0.8)',
                   'rgba(235, 192, 94, 0.8)',
                   'rgba(73, 0, 0, 0.8)',
                   'rgba(156, 210, 57, 0.8)',
                   'rgba(99, 68, 94, 0.8)',
                   'rgba(189, 100, 24, 0.8)',
                   'rgba(45, 28, 85, 0.8)',
                   'rgba(55, 222, 1, 0.8)',
               ],
               borderWidth: 2
           }]
       },
       options: {
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

   var serviceCanvas = document.getElementById("piechart");

   var serviceData = {
      labels: [
         "Customers",
         "Vendors",
         "Inventory",
         "Orders",
      ],
      datasets: [
         {
               data: [133.3, 86.2, 52.2, 51.2],
               backgroundColor: [
                  "#9b51e0",
                  "#219653",
                  "#f2994a",
                  "#4a6cf7"
               ]
         }],
         options: {
            legend: {
                  position: 'top',
                  display: true
              },
              scale: {
                    display: true,
                    ticks: {
                          beginAtZero: true,
                            }
                     },
              responsive:true,
              maintainAspectRatio: true,
         }
   };

   var pieChart = new Chart(serviceCanvas, {
   type: 'pie',
   data: serviceData
   });

   var ctx = document.getElementById("chart").getContext('2d');
var barChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    datasets: [{
      label: 'Customers',
      data: [12, 19, 3, 17, 28, 24, 7, 15, 9, 6, 12, 7],
      backgroundColor: "rgba(242, 153, 74, 0.1)",
      borderColor: "#f2994a",
      hoverBackgroundColor: "rgba(242, 153, 74, 0.4)",
      hoverBorderColor: "rgba(242, 153, 74, 1)",
      borderWidth: 2,
      fill: true,
      borderRadius: 2,
      datalabels: {
                    display: false
                }
    }, {
      label: 'Inventory',
      data: [30, 29, 5, 5, 20, 3, 10, 15, 9, 6, 12, 7],
      backgroundColor: "rgba(33, 150, 83, 0.1)",
      borderColor: "#219653",
      hoverBackgroundColor: "rgba(33, 150, 83, 0.4)",
      hoverBorderColor: "rgba(33, 150, 83, 1)",
      borderWidth: 2,
      fill: true,
      borderRadius: 2,
      datalabels: {
                    display: false
                }
    }, {
      label: 'Orders',
      data: [30, 29, 5, 5, 20, 3, 10, 15, 9, 6, 12, 7],
      backgroundColor: "rgba(155, 81, 224, 0.1)",
      borderColor: "#9b51e0",
      hoverBackgroundColor: "rgba(155, 81, 224, 0.4)",
      hoverBorderColor: "rgba(155, 81, 224, 1)",
      borderWidth: 2,
      fill: true,
      borderRadius: 2,
      datalabels: {
                    display: false
                }
    }]
  },
  options: {
      scales: {
         y: {
            grid: {
               display: false
            }
         },
         x: {
            grid: {
               display: false
            }
         }
      }
	}
});

   
   
//    var vendorschartctx = document.getElementById("vendorschart");
//    var myChart = new Chart(vendorschart, {
//        type: 'doughnut',
//        data: {
//            labels: ['Daily', 'Week', 'Ready For Delivery', 'Dispatched', 'Delivered', 'Cancelled',
//                'Return Pending', 'Returned', 'Return Rejected'
//            ],
//            datasets: [{
//                label: '',
//                data: [{{ $st_count['pending_count'] }}, {{ $st_count['accepted_count'] }},
//                    {{ $st_count['ready_for_delivery_count'] }},
//                    {{ $st_count['dispatched_count'] }}, {{ $st_count['delivered_count'] }},
//                    {{ $st_count['cancelled_count'] }}, {{ $st_count['return_pending_count'] }},
//                    {{ $st_count['returned_count'] }}, {{ $st_count['return_rejected_count'] }}
//                ],
//                backgroundColor: [
//                    'rgba(202, 153, 67, 0.8)',
//                    'rgba(0, 0, 0, 0.8)',
//                    'rgba(235, 192, 94, 0.8)',
//                    'rgba(73, 0, 0, 0.8)',
//                    'rgba(156, 210, 57, 0.8)',
//                    'rgba(99, 68, 94, 0.8)',
//                    'rgba(189, 100, 24, 0.8)',
//                    'rgba(45, 28, 85, 0.8)',
//                    'rgba(55, 222, 1, 0.8)',
//                ],
//                borderColor: [
//                    'rgba(202, 153, 67, 0.8)',
//                    'rgba(0, 0, 0, 0.8)',
//                    'rgba(235, 192, 94, 0.8)',
//                    'rgba(73, 0, 0, 0.8)',
//                    'rgba(156, 210, 57, 0.8)',
//                    'rgba(99, 68, 94, 0.8)',
//                    'rgba(189, 100, 24, 0.8)',
//                    'rgba(45, 28, 85, 0.8)',
//                    'rgba(55, 222, 1, 0.8)',
//                ],
//                borderWidth: 2
//            }]
//        },
//        options: {
//            cutout: 60,
//            centerPercentage: 80,
//            responsive: true,
//            maintainAspectRatio: false,
//            tooltips: {
//                enabled: true
//            },
//            interaction: {
//                intersect: false
//            },
//            plugins: {
//                legend: {
//                    display: true,
//                    position: 'bottom',
   
//                    labels: {
//                        font: {
//                            size: 10
//                        },
//                        boxWidth: 10
//                    }
//                }
//            },
//        }
//    });
</script>
<script>
   var data1 = {
       labels: <?php print_r(json_encode(array_values($last_7_days_name))) ?>,
       series: [
   
   
           <?php print_r(json_encode(array_values($weeklyVendr))) ?>,
       ]
   };
   
   var options = {
       seriesBarDistance: 10,
       axisY: {
           labelInterpolationFnc: function(value) {
               return value + '';
           },
           onlyInteger: true,
       }
   };
   
   var responsiveOptions = [
       ['screen and (max-width: 575px)', {
           seriesBarDistance: 5,
           axisX: {
               labelInterpolationFnc: function(value) {
                   return value[0];
               }
           }
       }]
   ];
   new Chartist.Bar('.v-pv-weekly', data1, options, responsiveOptions);
   $('.monthly-chart-tab li a').on('shown.bs.tab', function(event) {   
       var responsiveOptionsMonthly = [
           ['screen and (max-width: 575px)', {
               axisX: {
                   labelInterpolationFnc: function(value) {
                       return value[0];
                   }
               }
           }]
       ];
   
       new Chartist.Line('.v-pv-monthly', {
           labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
               'Dec'],
           series: [
               <?php print_r(json_encode(array_values($monthlyVendr))) ?>,
           ]
       }, {
           fullWidth: true,
           axisY: {
               onlyInteger: true,
               offset: 20,
               labelInterpolationFnc: function(value) {
                   return value + '';
               },
           }
       }, responsiveOptionsMonthly);
   
   
       var responsiveOptionsYearly = [
           ['screen and (max-width: 575px)', {
               axisX: {
                   labelInterpolationFnc: function(value) {
                       return value[2] + value[3];
                   }
               }
           }]
       ];
   
       new Chartist.Line('.v-pv-yearly', {
           labels: [2016, 2017, 2018, 2019, 2020, 2021, 2022],
           series: [
            <?php print_r(json_encode(array_values($yearlyVendr))) ?>,
           ]
       }, {
           low: 0,
           showArea: true,
           axisY: {
               onlyInteger: true,
               offset: 20,
               labelInterpolationFnc: function(value) {
                   return value + '';
               },
           }
       }, responsiveOptionsYearly);
   
   })
   
 
</script>
@stop