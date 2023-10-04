<?php
// $_current_user = \Request::get('_current_user');
// $userid = Auth::user()->id;
// if ($userid > 1) {
//     $privileges = \App\Models\UserPrivileges::privilege();
//     $privileges = json_decode($privileges, true);
// }
$_current_user = \Request::get('_current_user');
    $CurrentUrl = url()->current();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>{{ config('global.site_name') }} | Admin</title>
  <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('') }}admin-assets/assets/img/favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('') }}admin-assets/assets/img/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('') }}admin-assets/assets/img/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('') }}admin-assets/assets/img/favicon/favicon-16x16.png">
<link rel="manifest" href="{{ asset('') }}admin-assets/assets/img/favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="{{ asset('') }}admin-assets/assets/img/favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <!-- <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'> -->
    <link href="{{ asset('') }}admin-assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{ asset('') }}admin-assets/assets/css/boxicons.min.css" rel='stylesheet'>
    <link href="{{ asset('') }}admin-assets/assets/css/sidebar.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
        integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/solid.min.css"
        integrity="sha512-EvFBzDO3WBynQTyPJI+wLCiV0DFXzOazn6aoy/bGjuIhGCZFh8ObhV/nVgDgL0HZYC34D89REh6DOfeJEWMwgg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css"
        integrity="sha512-MQXduO8IQnJVq1qmySpN87QQkiR1bZHtorbJBD0tzy7/0U9+YIC93QWHeGTEoojMVHWWNkoCp8V6OzVSYrX0oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}admin-assets/assets/css/custom.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}admin-assets/assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}admin-assets/assets/css/parsley.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}admin-assets/assets/css/sidebar.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}admin-assets/assets/css/newsidebar.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}admin-assets/assets/css/datatable-custom.css" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    @yield('header')
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

</head>

<!-- <body class="default-sidebar"> -->

<body class="">

    <!-- New Sidebar Starts Here -->
    <aside class="sidebar-nav-wrapper">
            <div class="navbar-logo">
                <a href="#!">
                    <img src="{{ asset('') }}admin-assets/assets/img/logo.svg" width="160" class="img-fluid" alt="logo" />
                </a>
            </div>
            <nav class="sidebar-nav">
                <ul>
               
                    <li class="nav-item {{preg_match('/admin\/dashboard/', $CurrentUrl) ? 'active' : null}}">
                        <a href="{{ url('admin/dashboard') }}">
                            <span class="icon">
                                <i class="bx bx-grid-alt"></i>
                            </span>
                            <span class="text">Dashboard</span>
                        </a>
                    </li>
                    <!-- <span class="divider"><hr /></span> -->

                   <!--  @if(check_permission('adminusers','View') || check_permission('admin_user_desig','View'))
                    <li class="nav-item nav-item-has-children {{preg_match('/admin\/admin_users/', $CurrentUrl) || preg_match('/admin\/admin_user_designation/', $CurrentUrl) ? 'active' : null}}">
                        <a href="#0" class="{{preg_match('/admin\/admin_users/', $CurrentUrl) || preg_match('/admin\/admin_user_designation/', $CurrentUrl) ? '' : 'collapsed'}}" data-toggle="collapse" data-auto-close="outside" data-target="#ddmenu_1" aria-controls="ddmenu_1" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="icon">
                                <i class="bx bx-user"></i>
                            </span>
                            <span class="text"> Admin Users </span>
                        </a>
                        <ul id="ddmenu_1" class="collapse dropdown-nav {{preg_match('/admin\/admin_users/', $CurrentUrl) || preg_match('/admin\/admin_user_designation/', $CurrentUrl) ? 'show' : null}}">
                            @if(check_permission('adminusers','View'))
                                <li>
                                    <a  class="{{preg_match('/admin\/admin_users/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/admin_users') }}"> <span class="text"> Admin User</span></a>
                                </li>
                            @endif
                            @if(check_permission('admin_user_desig','View'))
                                <li>
                                    <a  class="{{preg_match('/admin\/admin_user_designation/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/admin_user_designation') }}"><span class="text"> Admin User Designation</span></a>
                                </li>
                            @endif     
                        </ul>
                    </li>
                    @endif -->

                    @if(check_permission('customers','View') || check_permission('vendor','View') || check_permission('stores','View'))
                    <li class="nav-item nav-item-has-children {{preg_match('/admin\/customers/', $CurrentUrl) || preg_match('/admin\/vendors/', $CurrentUrl) ? 'active' : null}}">
                        <a href="#0" class="{{preg_match('/admin\/customers/', $CurrentUrl) || preg_match('/admin\/vendors/', $CurrentUrl) ? '' : 'collapsed'}}" data-toggle="collapse" data-auto-close="outside" data-target="#ddmenu_2" aria-controls="ddmenu_2" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="icon">
                                <i class='bx bx-user-circle'></i>
                            </span>
                            <span class="text"> Users </span>
                        </a>
                        <ul id="ddmenu_2" class="collapse dropdown-nav {{preg_match('/admin\/customers/', $CurrentUrl) || preg_match('/admin\/vendors/', $CurrentUrl) ? 'show' : null}}">
                            <!-- @if(check_permission('customer','View'))
                            <li>
                                <a class="{{preg_match('/admin\/customers/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/customers') }}"><span class="text">Customers</span></a>
                            </li>
                            @endif -->

                            @if(check_permission('vendor','View'))
                            <li>
                                <a class="{{preg_match('/admin\/vendors/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/vendors') }}"> <span class="text">Vendors</span></a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if(check_permission('food_products','View') || check_permission('products','View') || check_permission('products','ImportExport') || check_permission('coupon','View'))
                    <li class="nav-item nav-item-has-children {{preg_match('/admin\/products/', $CurrentUrl) || preg_match('/admin\/coupons/', $CurrentUrl) || preg_match('/admin\/product/', $CurrentUrl) ? 'active' : null}}">
                        <a href="#0" class="{{preg_match('/admin\/products/', $CurrentUrl) || preg_match('/admin\/coupons/', $CurrentUrl) || preg_match('/admin\/product/', $CurrentUrl) ? '' : 'collapsed'}}" data-toggle="collapse" data-auto-close="outside" data-target="#ddmenu_3" aria-controls="ddmenu_3" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="icon">
                                <i class='bx bx-box'></i>
                            </span>
                            <span class="text"> Inventory </span>
                        </a>
                        <ul id="ddmenu_3" class="collapse dropdown-nav {{preg_match('/admin\/products/', $CurrentUrl) || preg_match('/admin\/coupons/', $CurrentUrl) || preg_match('/admin\/product/', $CurrentUrl) ? 'show' : null}}">

                            @if(check_permission('products','View'))
                            <li>
                                <a class="{{preg_match('/admin\/products/', $CurrentUrl) || preg_match('/admin\/product/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/products') }}"> <span class="text">Products</span></a>
                            </li>
                            @endif
                            @if(check_permission('products','ImportExport'))
                            {{-- <li>
                                <a href="{{ url('admin/import_export') }}"> <span class="text">Product Import/Export</span></a>
                            </li> --}}
                            @endif
                            @if(check_permission('coupon','View'))
                            <li>
                                <a class="{{preg_match('/admin\/coupons/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/coupons') }}"> <span class="text">Coupons</span></a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif


                    @if(check_permission('food_category','View') || check_permission('category','View') || check_permission('activity_type','View') || check_permission('account_type','View') || check_permission('brand','View') || check_permission('attribute','View') || check_permission('industry','View') || check_permission('store_type','View') || check_permission('country','View') || check_permission('states','View') || check_permission('cities','View') || check_permission('bank','View') || check_permission('moda_categories','View') || check_permission('skin_colors','View') || check_permission('hair_colors','View') || check_permission('public_business_info','View') || 
                    check_permission('project_purpose','View') || check_permission('room','View') || check_permission('room','View') || check_permission('project_purpose','View') || check_permission('square_footage','View') || check_permission('hash_tags','View'))
                    <li class="nav-item nav-item-has-children {{preg_match('/admin\/services/', $CurrentUrl) || preg_match('/admin\/foods/', $CurrentUrl)  || preg_match('/admin\/doctors/', $CurrentUrl)  || preg_match('/admin\/category/', $CurrentUrl)  || preg_match('/admin\/species/', $CurrentUrl)  || preg_match('/admin\/breed/', $CurrentUrl)  || preg_match('/admin\/product_attribute/', $CurrentUrl)  || preg_match('/admin\/industry_type/', $CurrentUrl) || preg_match('/admin\/brand/', $CurrentUrl) || preg_match('/admin\/appointment_types/', $CurrentUrl) || preg_match('/admin\/appointment_times/', $CurrentUrl) || preg_match('/admin\/room_types/', $CurrentUrl) || preg_match('/admin\/feeding_schedules/', $CurrentUrl) || preg_match('/admin\/grooming_types/', $CurrentUrl) || preg_match('/admin\/country/', $CurrentUrl) || preg_match('/admin\/states/', $CurrentUrl) || preg_match('/admin\/cities/', $CurrentUrl) ? 'active' : null}}">
                        <a href="#0" class="{{preg_match('/admin\/services/', $CurrentUrl) || preg_match('/admin\/foods/', $CurrentUrl)  || preg_match('/admin\/doctors/', $CurrentUrl)  || preg_match('/admin\/category/', $CurrentUrl)  || preg_match('/admin\/species/', $CurrentUrl)  || preg_match('/admin\/breed/', $CurrentUrl)  || preg_match('/admin\/product_attribute/', $CurrentUrl)  || preg_match('/admin\/industry_type/', $CurrentUrl) || preg_match('/admin\/brand/', $CurrentUrl) || preg_match('/admin\/appointment_types/', $CurrentUrl) || preg_match('/admin\/appointment_times/', $CurrentUrl) || preg_match('/admin\/room_types/', $CurrentUrl) || preg_match('/admin\/feeding_schedules/', $CurrentUrl) || preg_match('/admin\/grooming_types/', $CurrentUrl) || preg_match('/admin\/country/', $CurrentUrl) || preg_match('/admin\/states/', $CurrentUrl) || preg_match('/admin\/square_footage/', $CurrentUrl) || preg_match('/admin\/project_purpose/', $CurrentUrl) || preg_match('/admin\/room/', $CurrentUrl) || preg_match('/admin\/cities/', $CurrentUrl) ? '' : 'collapsed'}}" data-toggle="collapse" data-auto-close="outside" data-target="#ddmenu_4" aria-controls="ddmenu_4" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="icon">
                                <i class='bx bx-cube'></i>
                            </span>
                            <span class="text"> Masters </span>
                        </a>
                        <ul id="ddmenu_4" class="collapse dropdown-nav {{preg_match('/admin\/services/', $CurrentUrl) || preg_match('/admin\/foods/', $CurrentUrl)  || preg_match('/admin\/doctors/', $CurrentUrl)  || preg_match('/admin\/category/', $CurrentUrl)  || preg_match('/admin\/species/', $CurrentUrl)  || preg_match('/admin\/breed/', $CurrentUrl)  || preg_match('/admin\/product_attribute/', $CurrentUrl)  || preg_match('/admin\/industry_type/', $CurrentUrl) || preg_match('/admin\/brand/', $CurrentUrl) || preg_match('/admin\/appointment_types/', $CurrentUrl) || preg_match('/admin\/appointment_times/', $CurrentUrl) || preg_match('/admin\/room_types/', $CurrentUrl) || preg_match('/admin\/feeding_schedules/', $CurrentUrl) || preg_match('/admin\/grooming_types/', $CurrentUrl) || preg_match('/admin\/country/', $CurrentUrl) || preg_match('/admin\/states/', $CurrentUrl) || preg_match('/admin\/project_purpose/', $CurrentUrl) || preg_match('/admin\/room/', $CurrentUrl) || preg_match('/admin\/square_footage/', $CurrentUrl) || preg_match('/admin\/cities/', $CurrentUrl) ? 'show' : ''}}">

                           
                            @if(check_permission('category','View'))
                            <li>
                                <a class="{{preg_match('/admin\/category/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/category') }}"><span class="text">Category</span></a>
                            </li>
                            @endif
                          
                                             
                            @if(check_permission('attribute','View'))
                            <li>
                                <a class="{{preg_match('/admin\/product_attribute/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/product_attribute') }}"><span class="text">Product Attribute</span></a>
                            </li>
                            @endif 

                             @if(check_permission('brand','View'))
                            <li>
                                <a class="{{preg_match('/admin\/brand/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/brand') }}"><span class="text">Brands</span> </a>
                            </li>
                            @endif                        
                            
                             @if(check_permission('project_purpose','View'))
                            <li>
                                <a class="{{preg_match('/admin\/project_purpose/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/project_purpose') }}"><span class="text">Project Purpose</span></a>
                            </li>
                            @endif  

                             @if(check_permission('room','View'))
                            <li>
                                <a class="{{preg_match('/admin\/room/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/room') }}"><span class="text">Room Type</span></a>
                            </li>
                            @endif

                             @if(check_permission('square_footage','View'))
                            <li>
                                <a class="{{preg_match('/admin\/square_footage/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/square_footage') }}"><span class="text">Square footage</span></a>
                            </li>
                            @endif  

                           

                        
                            
                            
                            @if(check_permission('country','View'))
                            <li>
                                <a class="{{preg_match('/admin\/country/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/country') }}"> <span class="text">Country</span></a>
                            </li>
                            @endif
                            @if(check_permission('states','View'))
                            <li>
                                <a class="{{preg_match('/admin\/states/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/states') }}"> <span class="text">States</span></a>
                            </li>
                            @endif
                            @if(check_permission('cities','View'))
                            <li>
                                <a class="{{preg_match('/admin\/cities/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/cities') }}"> <span class="text">Cities</span></a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    {{-- <li class="nav-item">
                        <a href="{{ url('admin/service_quotes') }}">
                            <span class="icon">
                                <i class='bx bx-cart-alt'></i>
                            </span>
                            <span class="text"> Service Quotes </span>
                        </a>
                    </li> --}}


                    
                    @if(check_permission('cms','View') || check_permission('faq','View') || check_permission('help','View') || check_permission('contact_settings','Edit') || check_permission('settings','Edit'))
                    <li class="nav-item {{preg_match('/admin\/orders/', $CurrentUrl) ? 'active' : null}}">
                        <a href="{{ url('admin/orders') }}">
                            <span class="icon">
                                <i class='bx bx-cart-alt'></i>
                            </span>
                            <span class="text"> Orders </span>
                        </a>
                    </li>
                    @endif



                    @if(check_permission('cms','View') || check_permission('faq','View') || check_permission('help','View') || check_permission('contact_settings','Edit') || check_permission('settings','Edit'))
                    <li class="nav-item nav-item-has-children {{preg_match('/admin\/cms_pages/', $CurrentUrl) || preg_match('/admin\/faq/', $CurrentUrl) || preg_match('/admin\/help/', $CurrentUrl) || preg_match('/admin\/contact_details/', $CurrentUrl) || preg_match('/admin\/settings/', $CurrentUrl) || preg_match('/admin\/page/', $CurrentUrl) ? 'active' : null}}">
                        <a href="#0" class="{{preg_match('/admin\/cms_pages/', $CurrentUrl) || preg_match('/admin\/faq/', $CurrentUrl) || preg_match('/admin\/help/', $CurrentUrl) || preg_match('/admin\/contact_details/', $CurrentUrl) || preg_match('/admin\/settings/', $CurrentUrl)  || preg_match('/admin\/page/', $CurrentUrl) ? '' : 'collapsed'}}" data-toggle="collapse" data-auto-close="outside" data-target="#ddmenu_5" aria-controls="ddmenu_5" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="icon">
                                <i class='bx bx-file'></i>
                            </span>
                            <span class="text"> Pages </span>
                        </a>
                        <ul id="ddmenu_5" class="collapse dropdown-nav {{preg_match('/admin\/cms_pages/', $CurrentUrl) || preg_match('/admin\/faq/', $CurrentUrl) || preg_match('/admin\/help/', $CurrentUrl) || preg_match('/admin\/contact_details/', $CurrentUrl) || preg_match('/admin\/settings/', $CurrentUrl)  || preg_match('/admin\/page/', $CurrentUrl) ? 'show' : null}}">

                            @if(check_permission('cms','View'))
                            <li>
                                <a class="{{preg_match('/admin\/cms_pages/', $CurrentUrl)  || preg_match('/admin\/page/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/cms_pages') }}"> <span class="text">CMS Pages</span></a>
                            </li>
                            @endif
                            @if(check_permission('faq','View'))
                            <li>
                                <a class="{{preg_match('/admin\/faq/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/faq') }}"> <span class="text">FAQ</span></a>
                            </li>
                            @endif

                            @if(check_permission('help','View'))
                            <li>
                                <a class="{{preg_match('/admin\/help/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/help') }}"> <span class="text">Help</span></a>
                            </li>
                            @endif
                            @if(check_permission('contact_settings','Edit'))
                            <li>
                                <a class="{{preg_match('/admin\/contact_details/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/contact_details') }}"> <span class="text">Contact Details Settings</span></a>
                            </li>
                            @endif
                            @if(check_permission('settings','Edit'))
                            <li>
                                <a class="{{preg_match('/admin\/settings/', $CurrentUrl) ? 'active' : null}}" href="{{ url('admin/settings') }}"> <span class="text">Settings</span></a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    <li class="nav-item {{preg_match('/admin\/banners/', $CurrentUrl) || preg_match('/admin\/banner/', $CurrentUrl) ? 'active' : null}}">
                        <a href="{{ url('admin/banners') }}">
                            <span class="icon">
                                <i class='bx bx-images'></i>
                            </span>
                            <span class="text"> App Banners </span>
                        </a>
                    </li>

                    @if(check_permission('notification','View'))
                    <li class="nav-item {{preg_match('/admin\/notifications/', $CurrentUrl) ? 'active' : null}}">
                        <a href="{{ url('admin/notifications') }}">
                            <span class="icon">
                                <i class='bx bx-bell'></i>
                            </span>
                            <span class="text"> Notifications </span>
                        </a>
                    </li>

                    @endif


                
                </ul>
            </nav>
        </aside>
        <div class="overlay"></div>
        <!-- ======== sidebar-nav end =========== -->

        <!-- New Sidebar Ends Here -->

<!-- ======== main-wrapper start =========== -->
    <main class="main-wrapper">

        <div class="container-fluid d-none">
            <div class="page-header">
                <div class="page-title">
                    <h3>{{ $page_heading ?? '' }}</h3>
                    <div class="crumbs">
                        <ul id="breadcrumbs" class="breadcrumb">
                            <li><a href="{{ url('admin/dashboard') }}"><i class="flaticon-home-fill"></i></a>
                            </li>
                            <li><a onclick="window.history.back()" href="#">{{ $page_heading ?? '' }}</a>
                            </li>
                            <?php if(isset($mode)) { ?>
                            <li class="active"><a href="#">{{ $mode ?? '' }}</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


        <!-- ======== Header start =========== -->
        <header class="header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-6">
                            <div class="header-left d-flex align-items-center">
                                <div class="menu-toggle-btn mr-20">
                                    <button id="menu-toggle" class="main-btn primary-btn btn-hover"><i class="bx bx-chevron-left"></i> Menu</button>
                                </div>

                                @if(!preg_match('/admin\/dashboard/', $CurrentUrl))
                                <div class="menu-toggle-btn ml-20 mr-20">
                                    <a href="javascript:history.back()" class="main-btn ml-2 primary-btn btn-hover"><i class="bx bx-chevron-left"></i> Back</a>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-7 col-6">
                            <div class="header-right">
                                <!-- profile start -->
                                <div class="profile-box ml-15">
                                    <button class="dropdown-toggle bg-transparent border-0" type="button" id="profile" data-toggle="dropdown" aria-expanded="false">
                                        <div class="profile-info">
                                            <div class="info">
                                                <h6 class="text-white mb-0">Hi, Admin</h6>
                                                <div class="image">
                                                    <img src="{{ asset('') }}admin-assets/assets/img/profile-icon.svg" alt="" />
                                                    <span class="status"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <i class="bx bx-chevron-down"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profile">
                                        <li>
                                            <a href="{{ url('admin/dashboard') }}"> <i class="bx bx-grid-alt"></i> Dashboard </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('admin/change_password') }}"> <i class="bx bxs-key"></i> Change Password </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('admin/logout') }}"> <i class="bx bx-log-out-circle"></i> Sign Out </a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- profile end -->
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- ========== Header end ========== -->


        <section class="section">
            <div class="container-fluid">

            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30 pb-10">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="title mb-30">
                                                <h2>{{ $page_heading ?? '' }}</h2>
                                            </div>
                                        </div>
                                        <!-- end col -->
                                        <div class="col-md-6">
                                            <div class="breadcrumb-wrapper mb-30">
                                                <nav aria-label="breadcrumb">
                                                    <ol class="breadcrumb">
                                                        <li class="breadcrumb-item">
                                                            <a href="{{ url('admin/dashboard') }}">
                                                                <i class='bx bx-home-alt'></i>
                                                            </a>
                                                        </li>
                                                        <li class="breadcrumb-item">
                                                            <a onclick="window.history.back()" href="#">{{ $page_heading ?? '' }}</a>
                                                        </li>
                                                        <?php if(isset($mode)) { ?>
                                                            <li class="breadcrumb-item active">
                                                                <a href="#">{{ $mode ?? '' }}</a>
                                                            </li>
                                                        <?php } ?>
                                                    </ol>
                                                </nav>
                                            </div>
                                        </div>
                                        <!-- end col -->
                                    </div>
                                    <!-- end row -->
                                </div>


                @yield('content')
            </div>
        </section>




        <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 order-last order-md-first">
                            <div class="copyright text-center text-md-start">
                                <p class="text-sm mb-0">
                                    &#xA9; {{ date('Y') }}
                                    <a href="#!" class="" rel="nofollow" target="_blank">
                                    {{ config('global.site_name') }}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
                <!-- end container -->
            </footer>

                            <!-- </section> -->

        @yield('footer')
    </main>


    <div class="modal_loader">
        <!-- Place at bottom of page -->
    </div>
    <!-- Modal -->
    <div class="modal fade" id="changepassword" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change password</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
                <div class="modal-body">
                    <form method="post" id="admin-form" action="{{ url('admin/change_user_password') }}"
                        enctype="multipart/form-data" data-parsley-validate="true">
                        @csrf()
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="hidden" name="id" value="" id="userid">
                                    <input type="password" name="password" id="password"
                                        class="form-control jqv-input" data-jqv-required="true" required
                                        data-parsley-required-message="Enter Password" data-parsley-minlength="8"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Re-enter Password</label>
                                    <input type="password" name="new_pswd" class="form-control jqv-input"
                                        data-parsley-minlength="8" data-parsley-equalto="#password"
                                        autocomplete="off" required
                                        data-parsley-required-message="Please re-enter password."
                                         data-parsley-trigger="keyup" data-parsley-equalto-message="Both passwords should be the same">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Change</button>
                                </div>
                            </div>
                        </div>





                    </form>
                </div>

            </div>

        </div>
    </div>
    <style>
        .modal_loader {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(255, 255, 255, .8) url('https://i.stack.imgur.com/FhHRx.gif') 50% 50% no-repeat;
        }

        /* When the body has the loading class, we turn
    the scrollbar off with overflow:hidden */
        body.my-loading .modal_loader {
            overflow: hidden;
        }

        /* Anytime the body has the loading class, our
    modal element will be visible */
        body.my-loading .modal_loader {
            display: block;
        }

        .custom-file-label {
            overflow: hidden;
            white-space: nowrap;
            padding-right: 6em;
            text-overflow: ellipsis;
        }

        #image_crop_section {
            max-width: 100% !important;
        }
    </style>
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('') }}admin-assets/assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="{{ asset('') }}admin-assets/bootstrap/js/popper.min.js"></script>
    <script src="{{ asset('') }}admin-assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="{{ asset('') }}admin-assets/plugins/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- <script src="{{asset('custom_js/')}}/jquery-validation/jquery.validate.min.js"></script> --}}
    <script src="{{ asset('admin-assets/assets/js/app.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/js/boxicons.min.js') }}"></script>


    <!-- <script src="{{asset('')}}js/utilities/axios.min.js"></script> -->
    <!-- <script src="{{asset('')}}js/utilities/noty.js"></script> -->
    <!-- <script src="{{asset('')}}js/utilities/mo.min.js"></script> -->
    <script src="{{ asset('') }}admin-assets/plugins/sweetalerts/sweetalert2.min.js"></script>
    <!-- <script src="{{asset('')}}js/utilities/validator.min.js"></script> -->


    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/js/all.min.js"
        integrity="sha512-8pHNiqTlsrRjVD4A/3va++W1sMbUHwWxxRPWNyVlql3T+Hgfd81Qc6FC5WMXDC+tSauxxzp1tgiAvSKFu1qIlA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/js/solid.min.js"
        integrity="sha512-LKdDHe5ZhpmiH6Kd6crBCESKkS6ryNpGRoBjGeh5mM/BW3NRN4WH8pyd7lHgQTTHQm5nhu0M+UQclYQalQzJnw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"
        integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"
        integrity="sha512-K/oyQtMXpxI4+K0W7H25UopjM8pzq0yrVdFdG21Fh5dBe91I40pDd9A4lzNlHPHBIP2cwZuoxaUSX0GJSObvGA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script type="text/javascript">
        today = "<?php echo date('Y-m-d'); ?>";
        jQuery(function() {
            App2.init();
            App.init({
                site_url: '{{ url('/') }}',
                base_url: '{{ url('/') }}',
                site_name: '{{ config('global.site_name') }}',
            });
            App.toast([]);

            App.initTreeView();
        });
        $('.date').datepicker({
            orientation: "bottom auto",
            todayHighlight: true,
            format: "yyyy-mm-dd",
            autoclose: true,
        });

        window.Parsley.addValidator('fileextension', {
            validateString: function(value, requirement) {
                var fileExtension = value.split('.').pop();
                extns = requirement.split(',');
                if (extns.indexOf(fileExtension.toLowerCase()) == -1) {
                    return fileExtension === requirement;
                }
            },
        });
        window.Parsley.addValidator('maxFileSize', {
            validateString: function(_value, maxSize, parsleyInstance) {
                var files = parsleyInstance.$element[0].files;
                return files.length != 1 || files[0].size <= maxSize * 1024;
            },
            requirementType: 'integer',
        });

        window.Parsley.addValidator('lt', {
            validateString: function(value, requirement) {
                return parseFloat(value) < parseRequirement(requirement);
            },
            priority: 32
        });
        var parseRequirement = function(requirement) {
            if (isNaN(+requirement))
                return parseFloat(jQuery(requirement).val());
            else
                return +requirement;
        };

        window.Parsley.addValidator('lte', {
            validateString: function(value, requirement) {
                return parseFloat(value) <= parseRequirement(requirement);
            },
            priority: 32
        });
        window.Parsley.addValidator('imagedimensions', {
            requirementType: 'string',
            validateString: function(value, requirement, parsleyInstance) {
                let file = parsleyInstance.$element[0].files[0];
                let [width, height] = requirement.split('x');
                let image = new Image();
                let deferred = $.Deferred();

                image.src = window.URL.createObjectURL(file);
                image.onload = function() {
                    if (image.width == width && image.height == height) {
                        deferred.resolve();
                    } else {
                        deferred.reject();
                    }
                };

                return deferred.promise();
            },
            messages: {
                en: 'Image dimensions should be  %spx'
            }
        });

        window.Parsley.addValidator('dategttoday', {
            validateString: function (value) {
                if (value !== '') {
                    return Date.parse(value) >= Date.parse(today);
                }
                return true;
            },
            messages: {
                en: 'Date should be equal or greater than today'
            }
        });


        // Handle record delete
        $('body').off('click', '[data-role="unlink"]');
        $('body').on('click', '[data-role="unlink"]', function(e) {
            e.preventDefault();
            var msg = $(this).data('message') || 'Are you sure that you want to delete this record?';
            var href = $(this).attr('href');

            App.confirm('Confirm Delete', msg, function() {
                var ajxReq = $.ajax({
                    url: href,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        if (res['status'] == 1) {
                            App.alert(res['message'] || 'Deleted successfully', 'Success!');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);

                        } else {
                            App.alert(res['message'] || 'Unable to delete the record.',
                                'Failed!');
                        }
                    },
                    error: function(jqXhr, textStatus, errorMessage) {

                    }
                });
            });

        });

        $('body').off('click', '[data-role="approve"]');
        $('body').on('click', '[data-role="approve"]', function(e) {
            e.preventDefault();
            var msg = $(this).data('message') || 'Are you sure that you want to approve this record?';
            var href = $(this).attr('href');
            var title = $(this).data('title') || 'Confirm Approve';

            App.confirm(title, msg, function() {
                var ajxReq = $.ajax({
                    url: href,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        if (res['status'] == 1) {
                            App.alert(res['message'] || 'Approved successfully', 'Success!');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);

                        } else {
                            App.alert(res['message'] || 'Unable to approve the record.',
                                'Failed!');
                        }
                    },
                    error: function(jqXhr, textStatus, errorMessage) {

                    }
                });
            });

        });
        $('body').off('click', '[data-role="reject"]');
        $('body').on('click', '[data-role="reject"]', function(e) {
            e.preventDefault();
            var msg = $(this).data('message') || 'Are you sure that you want to reject this record?';
            var href = $(this).attr('href');
            var title = $(this).data('title') || 'Confirm Reject';

            App.confirm(title, msg, function() {
                var ajxReq = $.ajax({
                    url: href,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        if (res['status'] == 1) {
                            App.alert(res['message'] || 'Rejected successfully', 'Success!');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);

                        } else {
                            App.alert(res['message'] || 'Unable to reject the record.',
                                'Failed!');
                        }
                    },
                    error: function(jqXhr, textStatus, errorMessage) {

                    }
                });
            });

        });

        $(document).on('change', '.custom-file-input', function() {
            var file = $(this)[0].files[0].name;
            $(this).next('.custom-file-label').html(file);
        });

        $('body').off('click', '[data-role="verify"]');
        $('body').on('click', '[data-role="verify"]', function(e) {
            e.preventDefault();
            var href = $(this).attr('url');

            $.ajax({
                url: href,
                type: 'POST',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    if (res['status'] == 1) {
                        App.alert(res['message'] || 'Verified successfully', 'Success!');
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);

                    } else {
                        App.alert(res['message'] || 'Unable to verify the record.', 'Failed!');
                    }
                },
                error: function(jqXhr, textStatus, errorMessage) {

                }
            });

        });
        $(".change_status").change(function() {
            status = 0;
            if (this.checked) {
                status = 1;
            }

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: $(this).data('url'),
                data: {
                    "id": $(this).data('id'),
                    'status': status,
                    "_token": "{{ csrf_token() }}"
                },
                timeout: 600000,
                dataType: 'json',
                success: function(res) {
                    App.loading(false);

                    if (res['status'] == 0) {
                        var m = res['message']
                        App.alert(m, 'Oops!');
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        App.alert(res['message']);
                    }
                },
                error: function(e) {
                    App.alert(e.responseText, 'Oops!');
                }
            });
        });
        $(document).on('keyup', 'input[type="text"],textarea', function() {
            _name = $(this).attr("name");
            _type = $(this).attr("type");
            if (_name == "email" || _name == "r_email" || _name == "password" || _type == "email" || _type ==
                "password" || $(this).hasClass("no-caps")) {
                return false;
            }
            txt = $(this).val();
            //$(this).val(txt.substr(0,1).toUpperCase()+txt.substr(1));
        });
        // Load Provinces on Country Change
        $('body').off('change', '[data-role="country-change"]');
        $('body').on('change', '[data-role="country-change"]', function() {
            var $t = $(this);
            var $dialcode = $('#' + $t.data('input-dial-code'));
            var $state = $('#' + $t.data('input-state'));

            if ($dialcode.length > 0) {
                var code = $t.find('option:selected').data('phone-code');
                console.log(code)
                if (code == '') {
                    $dialcode.val('');
                } else {
                    $dialcode.val(code);
                }
            }

            if ($state.length > 0) {

                var id = $t.val();
                var html = '<option value="">Select</option>';
                $state.html(html);
                $state.trigger('change');

                if (id != '') {
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: "{{ url('admin/states/get_by_country') }}",
                        data: {
                            "id": id,
                            "_token": "{{ csrf_token() }}"
                        },
                        timeout: 600000,
                        dataType: 'json',
                        success: function(res) {
                            for (var i = 0; i < res['states'].length; i++) {
                                html += '<option value="' + res['states'][i]['id'] + '">' + res[
                                    'states'][i]['name'] + '</option>';
                                if (i == res['states'].length - 1) {
                                    $state.html(html);
                                    // $('.selectpicker').selectpicker('refresh')
                                }
                            }
                        }
                    });
                }
            }
        });
        $('body').off('change', '[data-role="state-change"]');
        $('body').on('change', '[data-role="state-change"]', function() {
            var $t = $(this);
            var $city = $('#' + $t.data('input-city'));

            if ($city.length > 0) {
                var id = $t.val();
                var html = '<option value="">Select</option>';

                $city.html(html);
                if (id != '') {
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: "{{ url('admin/cities/get_by_state') }}",
                        data: {
                            "id": id,
                            "_token": "{{ csrf_token() }}"
                        },
                        timeout: 600000,
                        dataType: 'json',
                        success: function(res) {
                            for (var i = 0; i < res['cities'].length; i++) {
                                html += '<option value="' + res['cities'][i]['id'] + '">' + res[
                                    'cities'][i]['name'] + '</option>';
                                if (i == res['cities'].length - 1) {
                                    $city.html(html);
                                    // $('.selectpicker').selectpicker('refresh')
                                }
                            }
                        }
                    });
                }

            }
        });

        $('body').off('change', '[data-role="category-change"]');
        $('body').on('change', '[data-role="category-change"]', function() {
            var $t = $(this);
            var $prd = $('#' + $t.data('input-prd'));

            if ($prd.length > 0) {
                var id = $t.val();
                var html = '<option value="">Select</option>';

                $prd.html(html);
                if (id != '') {
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: "{{ url('admin/products/get_by_category') }}",
                        data: {
                            "id": id,
                            "_token": "{{ csrf_token() }}"
                        },
                        timeout: 600000,
                        dataType: 'json',
                        success: function(res) {
                            for (var i = 0; i < res['prds'].length; i++) {
                                html += '<option value="' + res['prds'][i]['id'] + '">' + res[
                                    'prds'][i]['product_name'] + '</option>';
                                if (i == res['prds'].length - 1) {
                                    $prd.html(html);
                                }
                            }
                        }
                    });
                }

            }
        });

        $('body').off('change', '[data-role="vendor-change"]');
        $('body').on('change', '[data-role="vendor-change"]', function() {
            var $t = $(this);
            var $city = $('#' + $t.data('input-store'));

            if ($city.length > 0) {
                var id = $t.val();
                var html = '<option value="">Select</option>';

                $city.html(html);
                if (id != '') {
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: "{{ url('admin/store/get_by_vendor') }}",
                        data: {
                            "id": id,
                            "_token": "{{ csrf_token() }}"
                        },
                        timeout: 600000,
                        dataType: 'json',
                        success: function(res) {
                            for (var i = 0; i < res['stores'].length; i++) {
                                html += '<option value="' + res['stores'][i]['id'] + '">' + res[
                                    'stores'][i]['store_name'] + '</option>';
                                if (i == res['stores'].length - 1) {
                                    $city.html(html);
                                    // $('.selectpicker').selectpicker('refresh')
                                }
                            }
                        }
                    });
                }

            }
        });


        $('body').on('change', '.species-change', function() {
            var $t = $(this);
            var $city = $('#' + $t.data('input-species'));

            if ($city.length > 0) {
                var id = $t.val();
                var html = '<option value="">Select</option>';

                $city.html(html);
                if (id != '') {
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: "{{ url('admin/breed/get_by_species') }}",
                        data: {
                            "id": id,
                            "_token": "{{ csrf_token() }}"
                        },
                        timeout: 600000,
                        dataType: 'json',
                        success: function(res) {
                            for (var i = 0; i < res['breeds'].length; i++) {
                                html += '<option value="' + res['breeds'][i]['id'] + '">' + res[
                                    'breeds'][i]['name'] + '</option>';
                                if (i == res['breeds'].length - 1) {
                                    $city.html(html);
                                }
                            }
                        }
                    });
                }

            }
        });



        $('body').off('click', '[data-role="change_password"]');
        $('body').on('click', '[data-role="change_password"]', function(e) {
            var userid = $(this).attr('userid');
            $('#userid').val(userid);
            $('#changepassword').modal('show');
        });




        /* ========= sidebar toggle ======== */
    const sidebarNavWrapper = document.querySelector(".sidebar-nav-wrapper");
    const mainWrapper = document.querySelector(".main-wrapper");
    const menuToggleButton = document.querySelector("#menu-toggle");
    const menuToggleButtonIcon = document.querySelector("#menu-toggle i");
    const overlay = document.querySelector(".overlay");

    menuToggleButton.addEventListener("click", () => {
      sidebarNavWrapper.classList.toggle("active");
      overlay.classList.add("active");
      mainWrapper.classList.toggle("active");

      if (document.body.clientWidth > 1200) {
        if (menuToggleButtonIcon.classList.contains("bx-chevron-left")) {
          menuToggleButtonIcon.classList.remove("bx-chevron-left");
          menuToggleButtonIcon.classList.add("bx-menu");
        } else {
          menuToggleButtonIcon.classList.remove("bx-menu");
          menuToggleButtonIcon.classList.add("bx-chevron-left");
        }
      } else {
        if (menuToggleButtonIcon.classList.contains("bx-chevron-left")) {
          menuToggleButtonIcon.classList.remove("bx-chevron-left");
          menuToggleButtonIcon.classList.add("bx-menu");
        }
      }
    });
    overlay.addEventListener("click", () => {
      sidebarNavWrapper.classList.remove("active");
      overlay.classList.remove("active");
      mainWrapper.classList.remove("active");
    });

        $(".flatpickr-input").flatpickr({
            enableTime: false,
            dateFormat: "Y-m-d"
        });

        let arrow = document.querySelectorAll(".arrow");
        for (var i = 0; i < arrow.length; i++) {
            arrow[i].addEventListener("click", (e) => {
                let arrowParent = e.target.parentElement.parentElement; //selecting main parent of arrow
                arrowParent.classList.toggle("showMenu");
            });
        }

        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".bx-menu");
        console.log(sidebarBtn);
        sidebarBtn.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        });

        $(".progress-bar-1").css('width', '30%');
        $(".progress-bar-2").css('width', '70%');


        const body = document.querySelector("body"),
            modeSwitch = body.querySelector(".toggle-switch"),
            modeText = body.querySelector(".mode-text");
        modeSwitch.addEventListener("click", () => {
            body.classList.toggle("dark");

            if (body.classList.contains("dark")) {
                modeText.innerText = "Light mode";
            } else {
                modeText.innerText = "Dark mode";

            }
        });
    </script>

    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    @yield('script')
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
</body>

</html>
