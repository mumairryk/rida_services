<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ config('global.site_name') }} | {{ $page_heading }} </title>
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
  <meta name="theme-color" content="#ffffff">
  <!-- Bootstrap , fonts & icons  -->
  <link rel="stylesheet" href="{{ asset('') }}front_end/css/bootstrap.css">
  <link rel="stylesheet" href="{{ asset('') }}front_end/fonts/icon-font/css/style.css">
  <link rel="stylesheet" href="{{ asset('') }}front_end/fonts/typography-font/typo.css">
  <link rel="stylesheet" href="{{ asset('') }}front_end/fonts/fontawesome-5/css/all.css">
  <link href="https://fonts.googleapis.com/css2?family=Karla:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Gothic+A1:wght@400;500;700;900&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css" integrity="sha512-MQXduO8IQnJVq1qmySpN87QQkiR1bZHtorbJBD0tzy7/0U9+YIC93QWHeGTEoojMVHWWNkoCp8V6OzVSYrX0oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Plugin'stylesheets  -->
  <link rel="stylesheet" href="{{ asset('') }}front_end/plugins/aos/aos.min.css">
  <link rel="stylesheet" href="{{ asset('') }}front_end/plugins/fancybox/jquery.fancybox.min.css">
  <link rel="stylesheet" href="{{ asset('') }}front_end/plugins/nice-select/nice-select.min.css">
  <link rel="stylesheet" href="{{ asset('') }}front_end/plugins/slick/slick.min.css">
  <link rel="stylesheet" href="{{ asset('') }}front_end/css/swiper-bundle.min.css">
  <!-- Vendor stylesheets  -->
  <link rel="stylesheet" href="{{ asset('') }}front_end/css/main.css">
  <link rel="stylesheet" href="{{ asset('') }}front_end/css/custom.css">
  <link href="{{ asset('') }}admin-assets/assets/css/parsley.css" rel="stylesheet" type="text/css" />
  <!-- Custom stylesheet -->
  @yield('header')
</head>

<body style="font-family: 'Mazzard H';">
  <div class="site-wrapper overflow-hidden position-relative">
    <!-- Site Header -->
    <!--Site Header Area -->
    <header class="site-header site-header--menu-right landing-1-menu site-header--absolute site-header--sticky">
      <div class="container">
        <nav class="navbar site-navbar">
          <!-- Brand Logo-->
          <div class="brand-logo">
            <a href="#!">
              <!-- light version logo (logo must be black)-->
              <img src="{{ asset('') }}admin-assets/assets/img/logo.svg" alt="" class="light-version-logo">
              <!-- Dark version logo (logo must be White)-->
              <img src="{{ asset('') }}admin-assets/assets/img/logo.svg" alt="" class="dark-version-logo">
            </a>
          </div>
          <div class="menu-block-wrapper">
            <div class="menu-overlay"></div>
            <nav class="menu-block" id="append-menu-header">
              <div class="mobile-menu-head">
                <div class="go-back">
                  <i class="fa fa-angle-left"></i>
                </div>
                <div class="current-menu-title"></div>
                <div class="mobile-menu-close">&times;</div>
              </div>
              <ul class="site-menu-main">
                <!--<li class="nav-item nav-item-has-children">-->
                <!--  <a href="{{url('/')}}" class="nav-link-item">Home -->
                <!--  </a>-->
                <!--</li>-->
                <!--<li class="nav-item">-->
                <!--  <a href="{{url('/')}}#features" class="nav-link-item">Features</a>-->
                <!--</li>-->
                <!--<li class="nav-item">-->
                <!--  <a href="{{url('/')}}#howitworks" class="nav-link-item">How it Works</a>-->
                <!--</li>-->
                <!--<li class="nav-item">-->
                <!--    <a href="{{url('/')}}#screenshot" class="nav-link-item">Screenshot</a>-->
                <!--</li>-->
                <!-- <li class="nav-item">
                    <a href="" class="nav-link-item">Contact Us</a>
                </li> -->
              </ul>
            </nav>
          </div>
          <div class="header-btn header-btn-l1 ms-auto d-xs-inline-flex">
            <a class="btn btn log-in-btn btn-style-02 focus-reset" href="{{url('/vendor')}}">
               Login
            </a>
            <a class="btn btn log-in-btn btn-style-02 focus-reset" href="{{url('/register')}}">
               Become a Vendor
            </a>
          </div>
          <!-- mobile menu trigger -->
          <div class="mobile-menu-trigger">
            <span></span>
          </div>
          <!--/.Mobile Menu Hamburger Ends-->
        </nav>
      </div>
    </header>
    @yield('content')

    <div class="border-bottom"></div>
    <!-- Content Area 1-->
    <!--<div class="cta-area-l-13 aos-init aos-animate" data-aos="fade-left" data-aos-duration="800" data-aos-once="true" style="padding-top: 80px; background-image: linear-gradient(#000000c9, #000000c9), url({{ asset('') }}front_end/image/body-bg.jpg) !important;background-repeat: no-repeat; background-position: center; background-size: cover;">-->
    <!--  <div class="container">-->
    <!--    <div class="row justify-content-center">-->
    <!--      <div class="col-xl-12">-->
    <!--        <div class="row justify-content-center">-->
    <!--          <div class="col-xxl-7 col-xl-8 text-center">-->
    <!--            <div class="content">-->
    <!--              <span class="tagline">Time to take action</span>-->
    <!--              <h2>Join over 30,000+ happy users &amp; order now.</h2>-->
    <!--              <div class="apps-btn">-->
    <!--                <a href="#" class="app-store"><img src="{{ asset('') }}front_end/image/app-store.png" alt=""></a>-->
    <!--                <a href="#" class="app-store"><img src="{{ asset('') }}front_end/image/google-play.png" alt=""></a>-->
    <!--              </div>-->
    <!--            </div>-->
    <!--          </div>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--    </div>-->
    <!--  </div>-->
    <!--</div>-->
    
    <!-- Footer Area -->

    <footer class="footer-l-13 text-center text-md-start" style="background-color: #fff; background-repeat: no-repeat; background-position: center; background-size: cover;">
      <div class="border-bottom"></div>
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="footer-border-l-13"></div>
          </div>
        </div>
        <div class="row footer-l-13-items">
          <div class="col-md-3">
            <div class="footer-logo-l-13">
              <a href="{{url('/')}}"><img src="{{ asset('') }}admin-assets/assets/img/logo.svg" style="width: 96px;" alt="logo"></a>
            </div>
          </div>
          <div class="col-md-6">
            <div class="footer-widget-l-13 d-none">
              <ul class="pl-0 list-unstyled d-flex flex-wrap justify-content-center align-items-end ">
                <li class="d-flex"><a href="{{url('/')}}">Home</a></li>
                <li class="d-flex"><a href="{{url('/')}}#features">Features</a></li>
                <li class="d-flex"><a href="{{url('/')}}#howitworks">How it Works</a></li>
                <li class="d-flex"><a href="{{url('/')}}#screenshot">Screenshot</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md-3">
            <div class="copyright-area-l-13 text-center text-md-right">
              <p>© Live Market 2023 All right reserved.</p>
            </div>
          </div>
        </div>
      </div>
    </footer>
    
    <!-- Scroll To Top -->
    <a id="scroll_to_top"><i class="fa fa-arrow-up"></i></a>
  </div>
  <!-- Vendor Scripts -->
  <script src="{{ asset('') }}front_end/js/vendor.min.js"></script>
  <!-- Plugin's Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js" integrity="sha512-K/oyQtMXpxI4+K0W7H25UopjM8pzq0yrVdFdG21Fh5dBe91I40pDd9A4lzNlHPHBIP2cwZuoxaUSX0GJSObvGA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="{{ asset('') }}front_end/plugins/fancybox/jquery.fancybox.min.js"></script>
  <script src="{{ asset('') }}front_end/plugins/nice-select/jquery.nice-select.min.js"></script>
  <script src="{{ asset('') }}front_end/plugins/aos/aos.min.js"></script>
  <script src="{{ asset('') }}front_end/plugins/slick/slick.min.js"></script>
  <script src="{{ asset('') }}front_end/plugins/counter-up/jquery.counterup.min.js"></script>
  <script src="{{ asset('') }}front_end/plugins/isotope/isotope.pkgd.min.js"></script>
  <script src="{{ asset('') }}front_end/plugins/isotope/packery.pkgd.min.js"></script>
  <script src="{{ asset('') }}front_end/plugins/isotope/image.loaded.js"></script>
  <script src="{{ asset('') }}front_end/js/swiper-bundle.min.js"></script>
  <script src="{{ asset('') }}front_end/plugins/menu/menu.js"></script>
  <!-- Activation Script -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"
        integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="{{ asset('') }}front_end/js/custom.js"></script>

  <script src="{{ asset('admin-assets/assets/js/app.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
  
  <script>
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
        window.Parsley.addValidator('imagedimensions', {
            requirementType: 'string',
            validateString: function (value, requirement, parsleyInstance) {
                let file = parsleyInstance.$element[0].files[0];
                let [width, height] = requirement.split('x');
                let image = new Image();
                let deferred = $.Deferred();

                image.src = window.URL.createObjectURL(file);
                image.onload = function() {
                    if (image.width == width && image.height == height) {
                        deferred.resolve();
                    }
                    else {
                        deferred.reject();
                    }
                };

                return deferred.promise();
            },
            messages: {
                en: 'Image dimensions should be  %spx'
            }
        });
        $('body').off('change', '[data-role="country-change"]');
        $('body').on('change', '[data-role="country-change"]', function() {
            var $t = $(this);
            var $dialcode = $('#'+ $t.data('input-dial-code'));
            var $state = $('#'+ $t.data('input-state'));

            if ( $dialcode.length > 0 ) {
                var code = $t.find('option:selected').data('phone-code');
                console.log(code)
                if ( code == '' ) {
                    $dialcode.val('');
                } else {
                    $dialcode.val(code);
                }
            }

            if ( $state.length > 0 ) {

                var id   = $t.val();                
                var html = '<option value="">Select</option>';
                $state.html(html);
                $state.trigger('change');

                if ( id != '' ) {
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: "{{url('common/states/get_by_country')}}",
                        data: {
                            "id": id,
                            "_token": "{{ csrf_token() }}"
                        },
                        timeout: 600000,
                        dataType: 'json',
                        success: function(res) {
                            for (var i=0; i < res['states'].length; i++) {
                                html += '<option value="'+ res['states'][i]['id'] +'">'+ res['states'][i]['name'] +'</option>';
                                if ( i == res['states'].length-1 ) {
                                    $state.html(html);
                                }
                                
                            }
                            $state.niceSelect('update');
                        }
                    });
                }
            }
        });
        $('body').off('change', '[data-role="state-change"]');
        $('body').on('change', '[data-role="state-change"]', function() {
          $(this).niceSelect('update');
            var $t = $(this);
            var $city = $('#'+ $t.data('input-city'));

            if ( $city.length > 0 ) {
                var id   = $t.val();
                var html = '<option value="">Select</option>';

                $city.html(html);
                if ( id != '' ) {
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: "{{url('common/cities/get_by_state')}}",
                        data: {
                            "id": id,
                            "_token": "{{ csrf_token() }}"
                        },
                        timeout: 600000,
                        dataType: 'json',
                        success: function(res) {
                            for (var i=0; i < res['cities'].length; i++) {
                            html += '<option value="'+ res['cities'][i]['id'] +'">'+ res['cities'][i]['name'] +'</option>';
                            if ( i == res['cities'].length-1 ) {
                                $city.html(html);
    	                       // $('.selectpicker').selectpicker('refresh')
                            }
                        }
                        $city.niceSelect('update');
                        }
                    });
                }

            }
        });  
  </script>      
  @yield('script')
</body>
</html>