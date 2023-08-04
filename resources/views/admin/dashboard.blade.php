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
   <div class="col-xl-2 col-lg-3 col-sm-6 mb-30">
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

   <div class="col-xl-2 col-lg-3 col-sm-6 mb-30">
      <a href="{{ url('admin/service_quotes?service=1') }}" class="icon-card height-100 text-center align-items-center" style="height:100%; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 10px;">
         <div class="icon success m-0">
               <!-- <i class="bx bx-user"></i> -->
               <!-- <svg width="30px" height="30px" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M222 76C210.988 106.84 171.627 128.31 147 132" stroke="#219653" stroke-opacity="0.9" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M236 44.053C123.346 20.1218 96.7679 144.026 136.104 167" stroke="#219653" stroke-opacity="0.9" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M256 54C302.745 75.4047 288.975 108.654 272.736 144" stroke="#219653" stroke-opacity="0.9" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M260.902 122C295.577 228.082 142 250.963 142 156.601" stroke="#219653" stroke-opacity="0.9" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M218.892 153C219.298 150.031 218.46 147.754 218 145" stroke="#219653" stroke-opacity="0.9" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M191 154C191 151.332 191 148.668 191 146" stroke="#219653" stroke-opacity="0.9" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M60 345.501C60 309.522 83.3747 224.325 163.582 228.248C185.925 229.341 191.24 351.835 206.062 345.501C232 334.416 223.446 254.231 243.571 224.158C340.019 219.027 341 340.572 341 359" stroke="#219653" stroke-opacity="0.9" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M296 271C288.365 253.665 267.103 230.409 247 228" stroke="#219653" stroke-opacity="0.9" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M163 232C139.27 246.396 128.966 267.837 120 292" stroke="#219653" stroke-opacity="0.9" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M93.0228 347.996C90.4525 330.039 91.6852 307.132 109.075 296.665C157.969 267.237 151.718 362.878 128.138 345.983" stroke="#219653" stroke-opacity="0.9" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M293.07 271.039C321.891 269.785 283.781 299.392 290.907 273.038" stroke="#219653" stroke-opacity="0.9" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M304 324.289C291.859 322.728 282.476 327.953 271 329" stroke="#219653" stroke-opacity="0.9" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
               </svg> -->
               <svg fill="#219653" height="30px" width="30px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve">
               <g>
                  <g>
                     <path d="M511.969,490.625l-8.727-98.663c-2.994-33.85-27.123-62.258-60.04-70.691l-99.967-25.609
                        c-2.617-0.671-5.391,0.046-7.355,1.898l-2.463,2.322l-25.451-11.366l-2.136-12.829c0.244-0.106,0.484-0.218,0.726-0.326
                        c0.498-0.22,0.995-0.439,1.488-0.669c0.465-0.217,0.924-0.446,1.385-0.672c30.348-14.814,50.411-46.79,50.399-82.881
                        l-0.005-16.642l1.264-0.001c5.869-0.002,11.387-2.29,15.535-6.441c4.149-4.152,6.433-9.67,6.431-15.54l-0.007-21.051
                        c-0.003-10.583-7.529-19.439-17.512-21.509c2.346-3.443,3.328-7.678,2.703-11.886l-4.889-32.923
                        c-4.505-30.332-29.125-52.338-58.55-52.338c-0.005,0-0.015,0-0.021,0l-97.616,0.031c-21.511,0.007-41.357,12.195-51.794,31.809
                        c-2.043,3.839-0.588,8.606,3.251,10.649c3.838,2.043,8.606,0.588,10.649-3.251c7.698-14.466,22.22-23.455,37.9-23.46l97.616-0.031
                        c0.005,0,0.009,0,0.015,0c21.554,0,39.628,16.36,42.975,38.904l1.343,9.044l-59.632,0.02
                        c-5.847-12.656-18.653-21.464-33.485-21.464c-0.003,0-0.008,0-0.012,0c-0.629,0-1.255,0.017-1.879,0.048
                        c-0.087,0.004-0.172,0.014-0.258,0.019c-0.544,0.031-1.085,0.07-1.624,0.124c-0.012,0.001-0.023,0.003-0.035,0.004
                        c-8.414,0.858-16.214,4.549-22.273,10.612c-3.141,3.143-5.638,6.756-7.44,10.676l-59.612,0.02l0.18-1.214
                        c0.636-4.301-2.336-8.304-6.638-8.94c-4.299-0.632-8.305,2.336-8.94,6.638l-3.71,25.096c-0.621,4.206,0.362,8.439,2.708,11.88
                        c-4.174,0.854-8.011,2.904-11.092,5.987c-4.149,4.152-6.433,9.67-6.431,15.54l0.007,21.052
                        c0.004,12.112,9.861,21.965,21.973,21.966c0.001,0,0.006,0,0.007,0l1.291-0.001l0.005,16.859
                        c0.012,36.404,20.496,68.668,51.34,83.276c0.015,0.007,0.029,0.015,0.044,0.022c0.759,0.359,1.527,0.701,2.299,1.039
                        c0.076,0.034,0.15,0.069,0.226,0.102l-2.106,12.65l-25.451,11.366l-2.463-2.322c-1.964-1.851-4.739-2.569-7.355-1.898
                        l-99.966,25.612c-32.917,8.433-57.046,36.842-60.04,70.691l-8.726,98.663c-0.195,2.2,0.544,4.381,2.036,6.01
                        c1.491,1.629,3.599,2.556,5.807,2.556h88.001c0.004,0,0.007,0.001,0.012,0.001c0.004,0,0.007-0.001,0.01-0.001h320.208
                        c0.003,0,0.007,0.001,0.01,0.001c0.004,0,0.007-0.001,0.012-0.001h88.001c2.209,0,4.316-0.928,5.807-2.556
                        C511.425,495.005,512.164,492.825,511.969,490.625z M150.927,158.822v7.873l-0.001-7.873c-3.433,0-6.226-2.793-6.227-6.225
                        l-0.007-21.052c-0.001-1.663,0.647-3.227,1.822-4.404c1.176-1.177,2.739-1.824,4.402-1.826l1.267-0.001l0.01,33.506
                        L150.927,158.822z M335.452,227.244l-4.394-33.006l13.022-10.827l0.002,7.735C344.086,204.117,340.988,216.441,335.452,227.244z
                        M361.076,125.245c3.433,0,6.226,2.792,6.227,6.225l0.005,21.051c0.001,1.663-0.647,3.228-1.822,4.404
                        c-1.176,1.177-2.74,1.825-4.403,1.826l-1.264,0.001l-0.01-33.506H361.076z M291.949,100.095c0.063-0.275,0.13-0.548,0.187-0.825
                        c0.068-0.338,0.124-0.678,0.182-1.018c0.065-0.377,0.128-0.755,0.182-1.136c0.043-0.307,0.082-0.614,0.118-0.922
                        c0.052-0.451,0.093-0.905,0.129-1.362c0.02-0.251,0.043-0.502,0.058-0.754c0.035-0.599,0.054-1.203,0.059-1.809l58.593-0.019
                        l1.208,8.133c0.084,0.566-0.245,0.847-0.284,0.865l-60.695,0.02C291.784,100.881,291.86,100.487,291.949,100.095z M241.057,77
                        c3.989-3.991,9.294-6.19,14.942-6.193c0.722,0,1.436,0.038,2.141,0.108c0.099,0.01,0.195,0.033,0.294,0.043
                        c0.612,0.07,1.22,0.155,1.814,0.278c0.01,0.002,0.02,0.005,0.03,0.007c8.216,1.697,14.704,8.182,16.407,16.396
                        c0.003,0.013,0.007,0.024,0.009,0.037c0.122,0.592,0.206,1.197,0.277,1.807c0.012,0.101,0.035,0.199,0.045,0.301
                        c0.071,0.704,0.109,1.418,0.109,2.14c0,0.669-0.039,1.328-0.1,1.981c-0.017,0.176-0.042,0.351-0.062,0.526
                        c-0.059,0.499-0.132,0.992-0.226,1.479c-0.031,0.163-0.063,0.325-0.098,0.487c-0.12,0.555-0.259,1.103-0.422,1.641
                        c-0.022,0.071-0.039,0.145-0.062,0.215c-0.401,1.277-0.922,2.502-1.547,3.662c-0.042,0.079-0.089,0.154-0.132,0.233
                        c-0.267,0.481-0.552,0.949-0.854,1.407c-0.08,0.121-0.162,0.24-0.245,0.359c-3.034,4.387-7.678,7.579-13.076,8.702
                        c-0.028,0.005-0.057,0.012-0.085,0.018c-0.643,0.131-1.299,0.23-1.961,0.301c-0.086,0.009-0.172,0.015-0.258,0.022
                        c-0.646,0.061-1.297,0.099-1.958,0.1c-0.014,0-0.026,0.001-0.04,0.001c-0.007,0-0.015-0.001-0.022-0.001
                        c-0.668-0.001-1.327-0.039-1.979-0.101c-0.08-0.007-0.161-0.013-0.24-0.021c-0.67-0.071-1.33-0.171-1.98-0.303
                        c-0.02-0.004-0.04-0.008-0.06-0.013c-5.408-1.118-10.062-4.315-13.1-8.71c-0.077-0.111-0.153-0.223-0.227-0.334
                        c-0.309-0.465-0.6-0.944-0.872-1.434c-0.039-0.07-0.081-0.139-0.12-0.209c-0.628-1.162-1.149-2.389-1.553-3.669
                        c-0.022-0.068-0.039-0.139-0.06-0.208c-0.164-0.54-0.304-1.09-0.425-1.647c-0.035-0.161-0.066-0.323-0.098-0.486
                        c-0.093-0.487-0.167-0.98-0.227-1.479c-0.021-0.175-0.046-0.35-0.063-0.526c-0.061-0.653-0.101-1.312-0.101-1.98
                        C234.872,86.297,237.068,80.991,241.057,77z M159.986,101.316h-0.352c-0.052-0.022-0.381-0.303-0.297-0.87l1.203-8.135
                        l58.597-0.018c0.006,0.607,0.025,1.209,0.06,1.809c0.015,0.254,0.039,0.506,0.058,0.759c0.036,0.452,0.078,0.903,0.129,1.35
                        c0.036,0.313,0.076,0.625,0.12,0.935c0.054,0.373,0.114,0.742,0.178,1.112c0.06,0.349,0.118,0.698,0.188,1.044
                        c0.049,0.24,0.109,0.478,0.163,0.716c0.094,0.425,0.18,0.853,0.29,1.273l-60.248,0.02
                        C160.044,101.312,160.015,101.316,159.986,101.316z M167.951,117.056l30.136-0.01l30.914-0.01
                        c0.302,0.324,0.618,0.635,0.931,0.948c0.006,0.006,0.013,0.013,0.019,0.019c0.355,0.354,0.718,0.698,1.086,1.036
                        c0.105,0.097,0.21,0.194,0.316,0.29c0.291,0.26,0.586,0.515,0.885,0.766c0.208,0.175,0.417,0.35,0.628,0.52
                        c0.214,0.172,0.43,0.342,0.648,0.509c0.324,0.251,0.653,0.494,0.986,0.735c0.121,0.086,0.24,0.173,0.362,0.258
                        c0.456,0.32,0.919,0.63,1.389,0.929c0.01,0.006,0.021,0.014,0.03,0.02c5.708,3.628,12.469,5.739,19.717,5.739h0.012
                        c7.253-0.003,14.015-2.118,19.723-5.751c0.009-0.006,0.019-0.013,0.029-0.019c0.473-0.301,0.939-0.613,1.397-0.935
                        c0.115-0.081,0.23-0.163,0.344-0.246c0.34-0.246,0.677-0.497,1.009-0.753c0.208-0.161,0.414-0.322,0.618-0.487
                        c0.224-0.181,0.444-0.364,0.663-0.55c0.287-0.241,0.569-0.486,0.848-0.736c0.12-0.108,0.238-0.218,0.357-0.328
                        c0.354-0.325,0.703-0.657,1.044-0.997c0.023-0.023,0.046-0.047,0.07-0.071c0.302-0.302,0.607-0.603,0.899-0.915l61.044-0.02v0.345
                        c0,0.012-0.002,0.022-0.002,0.033l0.015,45.563l-24.759,20.585c-21.2,3.444-42.516,5.206-63.394,5.206
                        c-20.791,0.003-42.026-1.745-63.151-5.162l-24.799-20.572L167.951,117.056z M176.63,227.495
                        c-5.544-10.798-8.649-23.115-8.653-36.072l-0.003-7.964l13.055,10.83L176.63,227.495z M190.058,246.02l6.092-45.982
                        c20.005,2.948,40.073,4.44,59.768,4.44c19.784,0,39.935-1.504,60.02-4.478l6.096,45.786c-5.024,5.1-10.695,9.369-16.835,12.711
                        c-0.166,0.09-0.332,0.181-0.499,0.27c-0.532,0.284-1.068,0.561-1.607,0.83c-0.318,0.159-0.638,0.313-0.958,0.467
                        c-0.452,0.218-0.905,0.438-1.363,0.647c-0.771,0.35-1.546,0.69-2.332,1.012c-0.234,0.097-0.471,0.184-0.705,0.278
                        c-0.653,0.26-1.309,0.514-1.971,0.755c-0.256,0.093-0.513,0.183-0.771,0.273c-0.611,0.214-1.226,0.417-1.845,0.615
                        c-0.277,0.089-0.553,0.183-0.831,0.268c-0.003,0.001-0.006,0.002-0.008,0.003c-0.921,0.281-1.849,0.548-2.786,0.794l-1.053,0.275
                        c-9.453,2.467-19.153,3.848-28.882,4.144c-5.559,0.169-11.128-0.016-16.665-0.554c-4.031-0.393-8.044-0.977-12.028-1.746
                        c-0.122-0.023-0.245-0.042-0.366-0.065c-0.973-0.19-1.941-0.405-2.91-0.618c-1.755-0.385-3.506-0.795-5.246-1.253
                        c-0.049-0.013-0.099-0.028-0.148-0.042c-0.272-0.072-0.548-0.157-0.822-0.234c-0.527-0.147-1.055-0.295-1.576-0.453
                        c-0.029-0.008-0.059-0.019-0.088-0.028c-0.552-0.17-1.104-0.352-1.657-0.537c-0.261-0.087-0.523-0.173-0.782-0.263
                        c-0.48-0.167-0.96-0.336-1.436-0.513c-0.286-0.106-0.567-0.218-0.85-0.329C205.718,258.885,197.23,253.297,190.058,246.02z
                        M214.314,301.17c2.411-1.077,4.122-3.291,4.556-5.896l2.402-14.424c0.236,0.057,0.473,0.105,0.71,0.161
                        c0.802,0.19,1.606,0.368,2.41,0.545c0.664,0.145,1.327,0.289,1.991,0.424c0.849,0.173,1.701,0.338,2.552,0.497
                        c0.592,0.11,1.184,0.216,1.777,0.319c0.943,0.164,1.886,0.318,2.832,0.462c0.472,0.072,0.945,0.14,1.417,0.208
                        c1.07,0.153,2.14,0.297,3.212,0.425c0.309,0.037,0.618,0.07,0.927,0.105c1.228,0.14,2.458,0.268,3.689,0.377
                        c0.099,0.008,0.196,0.016,0.295,0.024c4.157,0.359,8.328,0.555,12.503,0.555c0.001,0,0.001,0,0.001,0c0.002,0,0.004,0,0.006,0
                        c0.002,0,0.003,0,0.005,0c0,0-0.001,0,0.001,0c5.941,0,11.877-0.376,17.775-1.101c0.034-0.004,0.067-0.007,0.102-0.012
                        c1.115-0.138,2.228-0.297,3.339-0.461c0.419-0.061,0.839-0.117,1.258-0.182c0.888-0.139,1.774-0.294,2.661-0.449
                        c0.639-0.111,1.278-0.22,1.916-0.34c0.752-0.142,1.501-0.296,2.251-0.45c0.757-0.154,1.514-0.311,2.269-0.478
                        c0.688-0.152,1.373-0.315,2.059-0.477c0.489-0.115,0.98-0.219,1.468-0.34l2.432,14.61c0.435,2.606,2.145,4.82,4.556,5.897
                        l23.319,10.414L256,372.86l-65.005-61.277L214.314,301.17z M141.837,374.14c5.966,0,10.82,4.854,10.82,10.82
                        s-4.854,10.82-10.82,10.82s-10.82-4.854-10.82-10.82C131.018,378.995,135.872,374.14,141.837,374.14z M423.318,483.445
                        l-6.095-67.477c-0.392-4.33-4.219-7.516-8.55-7.133c-4.33,0.391-7.524,4.219-7.133,8.55l5.967,66.061H104.492l1.99-22.031
                        c0.392-4.331-2.803-8.159-7.133-8.55c-4.333-0.381-8.158,2.803-8.55,7.133l-2.117,23.449H16.473l7.968-90.096
                        c2.407-27.209,21.803-50.045,48.264-56.824l61.26-15.693v38.754c-10.817,3.363-18.693,13.466-18.693,25.375
                        c0,14.648,11.918,26.566,26.566,26.566s26.566-11.917,26.566-26.566c0-11.909-7.876-22.011-18.693-25.375V316.8l18.76-4.806
                        l79.655,75.088v49.654c0,4.348,3.525,7.873,7.873,7.873c4.348,0,7.873-3.525,7.873-7.873v-49.654l79.655-75.088l10.1,2.588v45.797
                        c-15.567,0.67-28.026,13.538-28.026,29.266v66.847c0,2.867,1.559,5.508,4.069,6.893l14.665,8.094
                        c3.805,2.101,8.597,0.718,10.697-3.088c2.101-3.806,0.718-8.597-3.088-10.697l-10.596-5.848v-62.199
                        c0-7.473,6.079-13.552,13.552-13.552h13.2c7.473,0,13.552,6.079,13.552,13.552v62.199l-10.596,5.848
                        c-3.806,2.101-5.19,6.891-3.088,10.697c1.436,2.601,4.126,4.07,6.9,4.07c1.287,0,2.591-0.316,3.797-0.982l14.665-8.094
                        c2.51-1.386,4.069-4.026,4.069-6.893v-66.85c0-15.729-12.459-28.597-28.026-29.266v-41.763l69.92,17.912
                        c26.461,6.779,45.857,29.615,48.264,56.824l7.968,90.096H423.318z"/>
                  </g>
               </g>
               <g>
                  <g>
                     <path d="M103.326,408.835c-4.332-0.386-8.159,2.803-8.55,7.133l-1.646,18.222c-0.392,4.33,2.802,8.159,7.133,8.55
                        c0.241,0.021,0.481,0.033,0.718,0.033c4.028,0,7.463-3.076,7.832-7.166l1.646-18.222
                        C110.85,413.054,107.656,409.226,103.326,408.835z"/>
                  </g>
               </g>
               <g>
                  <g>
                     <path d="M205.174,135.062c-4.348,0-7.873,3.525-7.873,7.873v6.309c0,4.348,3.525,7.873,7.873,7.873s7.873-3.525,7.873-7.873
                        v-6.309C213.047,138.587,209.522,135.062,205.174,135.062z"/>
                  </g>
               </g>
               <g>
                  <g>
                     <path d="M306.826,135.063c-4.348,0-7.873,3.525-7.873,7.873v6.309c0,4.348,3.525,7.873,7.873,7.873
                        c4.348,0,7.873-3.525,7.873-7.873v-6.309C314.699,138.588,311.174,135.063,306.826,135.063z"/>
                  </g>
               </g>
               </svg>
            </div>
         <div class="content m-0">
               <h6 class="mb-0">Veterinary Services</h6>
         </div>
      </a>
   </div>
   <div class="col-xl-2 col-lg-3 col-sm-6 mb-30">
      <a href="{{ url('admin/service_quotes?service=2') }}" class="icon-card height-100 text-center align-items-center" style="height:100%; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 10px;">
         <div class="icon orange m-0">
               <!-- <i class="bx bx-user"></i> -->
               <svg fill="#f2994a" height="30px" width="30px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                  viewBox="0 0 430.477 430.477" xml:space="preserve">
                  <path id="XMLID_50_" d="M210.953,198.917h-18v-48.233h18V198.917z M174.918,5.032h-18v48.233h18V5.032z M174.918,150.684h-18v48.233
                     h18V150.684z M174.918,77.124h-18v48.232h18V77.124z M210.953,77.124h-18v48.232h18V77.124z M246.988,5.032h-18v48.233h18V5.032z
                     M138.883,5.032h-18v48.233h18V5.032z M246.988,77.124h-18v48.232h18V77.124z M210.953,5.032h-18v48.233h18V5.032z M246.988,150.684
                     h-18v48.233h18V150.684z M66.813,5.032h-18v48.233h18V5.032z M157.979,374.15h-20.156v18h20.156V374.15z M66.813,150.684h-18v48.233
                     h18V150.684z M66.813,77.124h-18v48.232h18V77.124z M430.477,273.971v149.571l-79.517-40.931h-37.921l-79.516,40.931V273.971
                     l79.516,40.931h37.921L430.477,273.971z M301.858,329.391l-50.335-25.91v90.552l50.335-25.91v-10.366h-26.837v-18h26.837V329.391z
                     M344.141,332.901h-24.282v31.71h24.282V332.901z M412.477,303.48l-50.336,25.91v10.365h31.337v18h-31.337v10.366l50.336,25.91
                     V303.48z M295.8,244.645H190.965v180.8h-86.13v-180.8H0V20.747h28.845v18H18v187.897h259.8V38.747h-10.847v-18H295.8V244.645z
                     M172.965,244.645h-50.13v162.8h50.13V244.645z M138.883,77.124h-18v48.232h18V77.124z M102.847,5.032h-18v48.233h18V5.032z
                     M102.847,150.684h-18v48.233h18V150.684z M138.883,150.684h-18v48.233h18V150.684z M102.847,77.124h-18v48.232h18V77.124z"/>
               </svg>
         </div>
         <div class="content m-0">
               <h6 class="mb-0">Grooming Services</h6>
         </div>
      </a>
   </div>
   <div class="col-xl-2 col-lg-3 col-sm-6 mb-30">
      <a href="{{ url('admin/service_quotes?service=3') }}" class="icon-card height-100 text-center align-items-center" style="height:100%; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 10px;">
         <div class="icon primary m-0">
               <!-- <i class="bx bx-user"></i> -->
               <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M19.0803 15.7203C18.4903 12.1903 15.1003 9.32031 11.5203 9.32031C7.63028 9.32031 4.21028 12.4703 3.88028 16.3503C3.75028 17.8503 4.23028 19.2703 5.22028 20.3403C6.20028 21.4103 7.58028 22.0003 9.08028 22.0003H13.7603C15.4503 22.0003 16.9303 21.3403 17.9403 20.1503C18.9503 18.9603 19.3503 17.3803 19.0803 15.7203Z" fill="#4a6cf7"/>
                  <path d="M10.2796 7.86C11.8978 7.86 13.2096 6.54819 13.2096 4.93C13.2096 3.31181 11.8978 2 10.2796 2C8.66141 2 7.34961 3.31181 7.34961 4.93C7.34961 6.54819 8.66141 7.86 10.2796 7.86Z" fill="#4a6cf7"/>
                  <path d="M16.94 9.02844C18.2876 9.02844 19.38 7.93601 19.38 6.58844C19.38 5.24086 18.2876 4.14844 16.94 4.14844C15.5924 4.14844 14.5 5.24086 14.5 6.58844C14.5 7.93601 15.5924 9.02844 16.94 9.02844Z" fill="#4a6cf7"/>
                  <path d="M20.5496 12.9313C21.6266 12.9313 22.4996 12.0582 22.4996 10.9812C22.4996 9.90429 21.6266 9.03125 20.5496 9.03125C19.4727 9.03125 18.5996 9.90429 18.5996 10.9812C18.5996 12.0582 19.4727 12.9313 20.5496 12.9313Z" fill="#4a6cf7"/>
                  <path d="M3.94 10.9816C5.28757 10.9816 6.38 9.88914 6.38 8.54156C6.38 7.19399 5.28757 6.10156 3.94 6.10156C2.59243 6.10156 1.5 7.19399 1.5 8.54156C1.5 9.88914 2.59243 10.9816 3.94 10.9816Z" fill="#4a6cf7"/>
               </svg>
         </div>
         <div class="content m-0">
               <h6 class="mb-0">Boarding Services</h6>
         </div>
      </a>
   </div>
   <div class="col-xl-2 col-lg-3 col-sm-6 mb-30">
      <a href="{{ url('admin/service_quotes?service=4') }}" class="icon-card height-100 text-center align-items-center" style="height:100%; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 10px;">
         <div class="icon orange m-0">
               <!-- <i class="bx bx-user"></i> -->
               <svg width="30px" height="30px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.88 109.16" style="fill: #f2994a; enable-background:new 0 0 122.88 109.16" xml:space="preserve">
                  <g>
                     <path d="M66.41,92.93h21.91c1.76-1.75,3.69-3.57,5.65-5.42c4.11-3.89,8.4-7.95,12.81-13.03c5.04-5.81,5.58-7.82,7.11-13.51 c0.29-1.07,0.61-2.27,1.03-3.76l2.62-9.21l0.03-0.1c1.4-4.1,1.51-6.81,0.93-8.37c-0.18-0.48-0.41-0.8-0.68-0.97 c-0.21-0.14-0.49-0.19-0.78-0.16c-0.68,0.07-1.45,0.5-2.15,1.27l-7.78,18.53c-0.07,0.17-0.17,0.33-0.28,0.47 c-0.46,0.83-1.08,1.64-1.88,2.41l-13.8,15.39c-0.75,0.84-2.04,0.91-2.87,0.16c-0.84-0.75-0.91-2.04-0.16-2.87l13.81-15.39 c0.06-0.07,0.12-0.14,0.19-0.2c1.43-1.36,1.88-2.83,1.63-3.9c-0.08-0.33-0.22-0.61-0.42-0.8c-0.19-0.17-0.44-0.29-0.75-0.32v0 c-1.23-0.13-3.04,0.78-5.25,3.39l0,0c-0.06,0.07-0.13,0.14-0.2,0.21l-5.35,4.72l-0.03,0.03c-5.79,5.48-8.28,6.78-12.82,9.15 c-0.95,0.5-1.99,1.04-3.28,1.74c-0.51,0.28-1.01,0.62-1.5,0.99c-0.52,0.4-1.02,0.81-1.49,1.21c-2.4,2.02-3.66,3.66-4.38,5.47 c-0.75,1.88-1.02,4.17-1.39,7.31c-0.15,1.27-0.26,2.52-0.35,3.77C66.47,91.75,66.44,92.35,66.41,92.93L66.41,92.93z M75.02,5.09 c10.22-11.03,11.94-1.01,19.46,1.1C95.39,6.44,96.07,6.5,96,6.93c-0.66,4.32-2.07,5.14-3.64,5.18c-1.74,0.05-3.15,0.02-4.2,0.51 c-1.33,0.63-2.29,1.45-3,2.41L74.48,9.58C74.83,8.32,75.01,6.83,75.02,5.09L75.02,5.09L75.02,5.09z M84.05,17.11 c-2.27,6.06,0.76,15.08-9.42,18.4c-0.61,5.16-0.59,10.33,0.14,15.49l1.24-0.13c2.23-0.24,2.21,1.78,1.15,3.09h-6.58 C70.1,47.77,68.91,41,69.41,35.09c-3.47,0.93-5.55,0.68-9.32-0.75c-1.83-0.7-9.36-5.2-11.41-3.37c-1.15,1.04,1.92,5.17-5.67,12.51 l0.14,7.26l1.28,0c1.94,0,1.84,1.94,1.13,3.21h-3.75c-2.49,0-1.97,0.27-2-2.36l-0.06-5.65l-0.4-3.5c0.01-2.09,1.5-4.31,0.07-9.27 c-0.94-3.29-0.87-5.9-3.34-6.27c-2.29,4.63-4.49,8.86-6.42,13.71c-0.13,0.33-0.31,0.64-0.47,0.96c-2.2,5.06-3.12,1.26-1.89-1.75 l0.82-2c1.05-4.24,4.02-12.2,6.82-15.15c10.56-11.14,31.96-0.6,38.26-10.24L84.05,17.11L84.05,17.11L84.05,17.11z M73.26,12.39 l0.08-0.13C73.32,12.3,73.29,12.34,73.26,12.39L73.26,12.39z M74.12,10.67c-0.04,0.12-0.09,0.25-0.13,0.37L74.12,10.67L74.12,10.67 z M56.47,92.93H34.55c-1.76-1.75-3.69-3.57-5.65-5.42c-4.11-3.89-8.4-7.95-12.81-13.03c-5.04-5.81-5.58-7.82-7.11-13.51 C8.7,59.9,8.38,58.7,7.96,57.21L5.34,48l-0.03-0.1c-1.4-4.1-1.51-6.81-0.93-8.37c0.18-0.48,0.41-0.8,0.68-0.97 c0.21-0.14,0.49-0.19,0.78-0.16C6.53,38.47,7.3,38.9,8,39.67l7.78,18.53c0.07,0.17,0.17,0.33,0.28,0.47 c0.46,0.83,1.08,1.64,1.88,2.41l13.8,15.39c0.75,0.84,2.04,0.91,2.87,0.16c0.84-0.75,0.91-2.04,0.16-2.87L20.96,58.36 c-0.06-0.07-0.12-0.14-0.19-0.2c-1.43-1.36-1.88-2.83-1.63-3.9c0.08-0.33,0.22-0.61,0.42-0.8c0.19-0.17,0.44-0.29,0.75-0.32v0 c1.23-0.13,3.05,0.78,5.25,3.39l0,0c0.06,0.07,0.13,0.14,0.2,0.21l5.35,4.72l0.03,0.03c5.79,5.48,8.28,6.78,12.82,9.15 c0.95,0.5,1.99,1.04,3.28,1.74c0.51,0.28,1.01,0.62,1.5,0.99c0.52,0.4,1.02,0.81,1.49,1.21c2.4,2.02,3.66,3.66,4.38,5.47 c0.75,1.88,1.02,4.17,1.39,7.31c0.15,1.27,0.26,2.52,0.35,3.77C56.41,91.75,56.44,92.35,56.47,92.93L56.47,92.93z M29.45,93.63 c-0.31,0.36-0.5,0.83-0.5,1.34v12.14c0,1.13,0.92,2.04,2.04,2.04h27.58c1.13,0,2.04-0.92,2.04-2.04V94.86c0-1.3-0.08-2.7-0.17-4 c-0.09-1.33-0.21-2.65-0.36-3.96c-0.4-3.43-0.7-5.94-1.66-8.35c-0.99-2.47-2.58-4.6-5.53-7.09c-0.54-0.46-1.09-0.92-1.67-1.35 c-0.61-0.46-1.27-0.9-2.01-1.31c-1.2-0.65-2.32-1.24-3.34-1.78c-4.2-2.2-6.5-3.4-11.91-8.52c-0.04-0.04-0.09-0.08-0.13-0.11 L28.6,53.8c-3.19-3.73-6.31-4.97-8.7-4.71l-0.01,0v0c-1.17,0.12-2.17,0.58-2.97,1.28l-5.27-12.56l-0.01,0 c-0.08-0.18-0.18-0.35-0.31-0.51c-1.46-1.75-3.31-2.77-5.08-2.95c-1.21-0.12-2.38,0.14-3.4,0.8c-0.97,0.63-1.77,1.61-2.27,2.96 c-0.88,2.35-0.86,6,0.86,11.05l2.6,9.15c0.38,1.32,0.71,2.59,1.02,3.71c1.7,6.35,2.3,8.6,7.97,15.12 c4.49,5.17,8.88,9.33,13.1,13.32C27.23,91.52,28.33,92.56,29.45,93.63L29.45,93.63z M33.35,97.02c0.27,0.06,0.55,0.06,0.83,0h22.34 v8.06H33.03v-8.06H33.35L33.35,97.02z M93.43,93.63c0.31,0.36,0.5,0.83,0.5,1.34v12.14c0,1.13-0.91,2.04-2.04,2.04H64.32 c-1.13,0-2.04-0.92-2.04-2.04V94.86c0-0.07,0-0.15,0.01-0.22c0.03-1.31,0.08-2.58,0.16-3.78c0.09-1.33,0.21-2.65,0.36-3.96 c0.4-3.43,0.7-5.94,1.66-8.35c0.99-2.47,2.58-4.6,5.53-7.09c0.54-0.46,1.09-0.92,1.67-1.35c0.61-0.46,1.27-0.9,2.01-1.31 c1.2-0.65,2.32-1.24,3.34-1.78c4.2-2.2,6.5-3.4,11.91-8.52c0.04-0.04,0.09-0.08,0.13-0.11l5.22-4.61c3.19-3.73,6.31-4.97,8.7-4.71 l0.01,0v0c1.17,0.12,2.17,0.58,2.97,1.28l5.27-12.56l0.01,0c0.08-0.18,0.18-0.35,0.31-0.51c1.46-1.75,3.31-2.77,5.08-2.95 c1.21-0.12,2.38,0.14,3.4,0.8c0.97,0.63,1.77,1.61,2.27,2.96c0.88,2.35,0.86,6-0.86,11.05l-2.6,9.15 c-0.38,1.32-0.71,2.59-1.02,3.71c-1.7,6.35-2.3,8.6-7.97,15.12c-4.49,5.17-8.88,9.33-13.1,13.32 C95.65,91.52,94.55,92.56,93.43,93.63L93.43,93.63z M89.53,97.02c-0.27,0.06-0.55,0.06-0.83,0H66.36v8.06h23.49v-8.06H89.53 L89.53,97.02z"/>
                  </g>
               </svg>
         </div>
         <div class="content m-0">
               <h6 class="mb-0">Day Care Reservations</h6>
         </div>
      </a>
   </div>
   <div class="col-xl-2 col-lg-3 col-sm-6 mb-30">
      <a href="{{ url('admin/service_quotes?service=5') }}" class="icon-card height-100 text-center align-items-center" style="height:100%; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 10px;">
         <div class="icon primary m-0">
            <i class='bx bxs-dog'></i>
         </div>
         <div class="content m-0">
               <h6 class="mb-0">Doggy Playtime</h6>
         </div>
      </a>
   </div>

  
   
   <div class="col-xl-2 col-lg-3 col-sm-6 mb-30">
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
   <div class="col-xl-2 col-lg-3 col-sm-6 mb-30">
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
<div class="row">
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
{{-- <div class="row  mb-4">
   <div class="col-6">
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
                  <td>GBP {{ $item->grand_total }}</td>
                  <td><span class="badge badge-info"> {{ $item->status_text }}</span></td>
                  <td><a href="{{ url('admin/order_details/' . $item->order_id) }}"><span
                     class="badge badge-success"> Details</span></a></td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
   <div class="col-6">
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
         "Veterinary Services",
         "Grooming Services",
         "Boarding Services",
         "Day Care Reservations",
         "Doggy Playtime",
      ],
      datasets: [
         {
               data: [133.3, 86.2, 52.2, 51.2, 50.2],
               backgroundColor: [
                  "#9b51e0",
                  "#219653",
                  "#f2994a",
                  "#4a6cf7",
                  "#D72638"
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