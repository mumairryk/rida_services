@extends('front_end.template.layout')
@section('header')
   
@stop

@section('content')
<div class="hero-area-l1  position-relative background-property" style=" background-image: url({{ asset('') }}front_end/image/body-bg.jpg) !important;">
   <div class="container">
     <div class="row justify-content-center align-items-center">
       <div class="col-lg-6 col-md-10 order-lg-1 order-1" data-aos="fade-right" data-aos-delay="500" data-aos-duration="1000">
         <div class="content">
           <h2>Features of Moda App</h2>
           <p>There are many features of Moda App. In this App one can go live, start a live show, do auctions, initiate zoom meetings, can add stories and many more.</p>
           <div class="l1-create-acc-btn mt-4">
             <!-- <a href="#" class="btn btn-style-02">
               <span class="fab fa-apple icon-size-sm mr-3"></span>
               <div class="download-text text-left">
                 <small>Download from</small>
                 <h5 class="mb-0">App Store</h5>
               </div>
                 
             </a> -->
               <a href="#" class="app-store" style="margin-right: 10px;"><img src="{{ asset('') }}front_end/image/app-store.png" class="img-fluid" alt=""></a>
               <a href="#" class="app-store"><img src="{{ asset('') }}front_end/image/google-play.png" class="img-fluid" alt=""></a>
             <!-- <a href="#" class="btn btn-style-02">
               <span class="fab fa-google-play icon-size-sm mr-3"></span>
               <div class="download-text text-left">
                 <small>Download from</small>
                 <h5 class="mb-0">Google Play</h5>
               </div>
             </a> -->
           </div>
           <!-- <span>No Credit Card Necessary</span> -->
         </div>
       </div>
       <div class="offset-lg-1 col-lg-5 col-md-8 order-lg-1 order-0" data-aos="fade-left" data-aos-delay="700" data-aos-duration="1000">
         <div class="hero-image-group-l1">
           <div class="image-1">
             <img class="w-100" src="{{ asset('') }}front_end/image/banner-right-img.png" alt="image">
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>


 <section class="feature-area-l1" style="padding-top: 5rem; background-image: linear-gradient(#000000c9, #000000c9), url({{ asset('') }}front_end/image/body-bg.jpg) !important;background-repeat: no-repeat; background-position: center; background-size: cover;">
   <div class="container" data-aos="fade-up" data-aos-delay="600" data-aos-duration="1000">
     <div class="row feature-l1-items justify-content-center">
       <div class="col-lg-4 col-md-6 col-sm-11 col-xs-12 aos-init aos-animate" data-aos="fade-up" data-aos-duration="800" data-aos-delay="500">
         <div class="content h-100 text-center">
           <img src="{{ asset('') }}front_end/image/features/5.jpg" alt="image">
           <h5>Fastest Delivery</h5>
           <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
         </div>
       </div>
       <div class="col-lg-4 col-md-6 col-sm-11 col-xs-12 aos-init aos-animate" data-aos="fade-up" data-aos-duration="800" data-aos-delay="700">
         <div class="content h-100 text-center">
           <img src="{{ asset('') }}front_end/image/features/6.jpg" alt="image">
           <h5>Mobile App</h5>
           <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
         </div>
       </div>
       <div class="col-lg-4 col-md-12 col-sm-11 col-xs-12 aos-init aos-animate" data-aos="fade-up" data-aos-duration="800" data-aos-delay="800">
         <div class="content h-100 text-center">
           <img src="{{ asset('') }}front_end/image/features/7.jpg" alt="image">
           <h5>24/7 Support</h5>
           <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
         </div>
       </div>
     </div>
   </div>
 </section>

 

 
 <!-- Content Area 1-->
 <div id="features">
   <div class="content-area-l6-2 pb-5" style="padding-top: 5rem; background-image: linear-gradient(#000000c9, #000000c9), url({{ asset('') }}front_end/image/body-bg.jpg) !important;background-repeat: no-repeat; background-position: center; background-size: cover;">
     <div class="container">
       <div class="row align-items-center justify-content-center justify-content-lg-start">
         <div class="offset-xl-1 col-xl-5 offset-lg-1 col-lg-5 col-md-9" data-aos="fade-right" data-aos-delay="500" data-aos-duration="1000">
           <div class="content-image-group-l6-2 position-relative text-center">
             <img src="{{ asset('') }}front_end/image/features/mobile-1.png" class="img-fluid" alt="">
           </div>
         </div>
         <div class="offset-xxl-1 col-xxl-4 offset-xl-1 col-xl-5 col-lg-5 col-md-8" data-aos="fade-left" data-aos-delay="700" data-aos-duration="1000">
           <div class="content-box-l6-2 section__heading-3 text-lg-start text-center">
             <h2>Explore Moda</h2>
             <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ac enim sollicitudin, viverra est vel, volutpat orci. Maecenas at velit sodales, Explore Moda
               ligula eu, rutrum metus. Donec lacus ligula, mollis sit amet enim non</p>
           </div>
         </div>
       </div>
     </div>
     <div class="border-bottom"></div>
   </div>
   <div class="content-area-l6-3 pb-5 pt-5" style="background-image: linear-gradient(#000000c9, #000000c9), url({{ asset('') }}front_end/image/body-bg.jpg) !important;background-repeat: no-repeat; background-position: center; background-size: cover;">
     <div class="container">
       <div class="row align-items-center justify-content-center justify-content-lg-start">
         <div class="offset-xxl-1 col-xxl-4 col-xl-5 col-lg-5 col-md-8 order-2 order-lg-1" data-aos="fade-right" data-aos-delay="500" data-aos-duration="1000">
           <div class="content-box-l6-3 section__heading-3 text-lg-start text-center">
             <h2>Shop on Moda</h2>
             <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ac enim sollicitudin, viverra est vel, volutpat orci. Maecenas at velit sodales, laoreet ligula eu, rutrum metus. Donec lacus ligula, mollis sit amet enim non</p>
           </div>
         </div>
         <div class="offset-xl-1 col-xl-5 offset-lg-1 col-lg-5 col-md-9 order-1 order-lg-2" data-aos="fade-left" data-aos-delay="700" data-aos-duration="1000">
           <div class="content-image-group-l6-3 position-relative text-center">
             <img src="{{ asset('') }}front_end/image/features/mobile-2.png" class="img-fluid" alt="">
           </div>
         </div>
       </div>
     </div>
     <div class="border-bottom"></div>
   </div>
   <div class="content-area-l6-3 pb-0 pt-5" style="background-image: linear-gradient(#000000c9, #000000c9), url({{ asset('') }}front_end/image/body-bg.jpg) !important;background-repeat: no-repeat; background-position: center; background-size: cover;">
     <div class="container">
       <div class="row align-items-center justify-content-center justify-content-lg-start">
         
         <div class="offset-xl-1 col-xl-5 offset-lg-1 col-lg-5 col-md-9" data-aos="fade-left" data-aos-delay="700" data-aos-duration="1000">
           <div class="content-image-group-l6-3 position-relative text-center">
             <img src="{{ asset('') }}front_end/image/features/mobile-3.png" class="img-fluid" alt="">
           </div>
         </div>
         <div class="offset-xxl-1 col-xxl-4 col-xl-5 col-lg-5 col-md-8" data-aos="fade-right" data-aos-delay="500" data-aos-duration="1000">
           <div class="content-box-l6-3 section__heading-3 text-lg-start text-center">
             <h2>Add Stories</h2>
             <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ac enim sollicitudin, viverra est vel, volutpat orci. Maecenas at velit sodales, laoreet ligula eu, rutrum metus. Donec lacus ligula, mollis sit amet enim non</p>
           </div>
         </div>
       </div>
     </div>
     <div class="border-bottom"></div>
   </div>
 </div>  
 <!-- Testimonial Area -->
 <div class="testimonial-area-l1 position-relative bg-skeptic d-none">
   
   <div class="container">
     <div class="row justify-content-center">
       <div class="col-xxl-5 col-xl-6 col-lg-7 col-md-8" data-aos="fade-in" data-aos-delay="500" data-aos-duration="1000">
         <div class="section__heading text-center">
           <h2>Customers are loving it</h2>
           <p>With lots of unique blocks, you can easily build a page without coding. Build your next website
             fast.</p>
         </div>
       </div>
     </div>
     <div class="row testimonial-area-l1-items  justify-content-center">
       <div class="col-lg-4 col-md-6 col-sm-11 col-xs-12" data-aos="fade-right" data-aos-delay="500" data-aos-duration="1000">
         <div class="content h-100 text-center">
           <img src="{{ asset('') }}front_end/image/team/team-image.png" alt="image">
           <p>“<span>You made it so simple.</span> My new site is so much faster and easier to work with than
             my old site.”</p>
           <h5>Alan Farmer</h5>
           <small>User</small>
         </div>
       </div>
       <div class="col-lg-4 col-md-6 col-sm-11 col-xs-12" data-aos="fade-up" data-aos-delay="800" data-aos-duration="1000">
         <div class="content h-100 text-center">
           <img src="{{ asset('') }}front_end/image/team/team-image-2.png" alt="image">
           <p>“Simply the best. <span>Better than all the rest.</span> I’d recommend this product to beginners
             and advanced users.”</p>
           <h5>Alan Farmer</h5>
           <small>User</small>
         </div>
       </div>
       <div class="col-lg-4 col-md-6 col-sm-11 col-xs-12" data-aos="fade-left" data-aos-delay="1000" data-aos-duration="1000">
         <div class="content h-100 text-center">
           <img src="{{ asset('') }}front_end/image/team/team-image-3.png" alt="image">
           <p>“<span>Must have service</span> for all, who want to be successful Product Designer or
             Interaction Designer.”</p>
           <h5>Alan Farmer</h5>
           <small>User</small>
         </div>
       </div>
     </div>
   </div>
 </div>
 

 



 <!-- Features Area -->
 <div class="feature-area-l1" style="padding-top: 5rem; background-image: linear-gradient(#000000c9, #000000c9), url({{ asset('') }}front_end/image/body-bg.jpg) !important;background-repeat: no-repeat; background-position: center; background-size: cover;" id="howitworks">
   <div class="container">
     <div class="row feature-l1-items justify-content-center">
       <div class="col-lg-3 col-md-6 col-sm-11 col-xs-12" data-aos="fade-up" data-aos-duration="800" data-aos-once="true">
         <div class="content h-100 text-center">
           <img src="{{ asset('') }}front_end/image/features/1.jpg" alt="image">
           <h5>1. Place your order</h5>
           <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
         </div>
       </div>
       <div class="col-lg-3 col-md-6 col-sm-11 col-xs-12" data-aos="fade-up" data-aos-duration="800" data-aos-once="true">
         <div class="content h-100 text-center">
           <img src="{{ asset('') }}front_end/image/features/2.jpg" alt="image">
           <h5>2. Pay your order</h5>
           <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
         </div>
       </div>
       <div class="col-lg-3 col-md-6 col-sm-11 col-xs-12" data-aos="fade-up" data-aos-duration="800" data-aos-once="true">
         <div class="content h-100 text-center">
           <img src="{{ asset('') }}front_end/image/features/3.jpg" alt="image">
           <h5>3. Order sent</h5>
           <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
         </div>
       </div>
       <div class="col-lg-3 col-md-6 col-sm-11 col-xs-12" data-aos="fade-up" data-aos-duration="800" data-aos-once="true">
         <div class="content h-100 text-center">
           <img src="{{ asset('') }}front_end/image/features/4.jpg" alt="image">
           <h5>4. Enjoy your order</h5>
           <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
         </div>
       </div>
     </div>
     <div class="row">
       <div class="col-lg-12">
         <div class="feature-border-l1"></div>
       </div>
     </div>
     <div class="row customer-area-l1 justify-content-center align-items-center ">
       <div class="col-xl-5 col-lg-5 col-md-10">
         <div class="section__heading">
           <h2 data-aos="fade-up" data-aos-delay="500" data-aos-duration="1000">Trusted by 3,000+ happy customers.</h2>
           <p data-aos="fade-up" data-aos-delay="800" data-aos-duration="1000">We strive to offer competitive fixed price repairs <br
                         class="d-none d-sm-block d-md-none d-xl-block">
                     for a wide range of appliances and boilers all across the UK.<br
                         class="d-none d-sm-block d-md-none d-xl-block"></p>
         </div>
       </div>
       <div class="col-xl-6 col-lg-7 col-md-10 offset-xl-1">
         <div class="row user-rating-box-area">
           <div class="col-sm-6 col-xs-8" data-aos="fade-left" data-aos-delay="500" data-aos-duration="1000">
             <div class="h-100 rate-box bg-primary">
               <h3>
                 3k
               </h3>
               <p>Active users visiting <br> us every month!</p>
             </div>
           </div>
           <div class="col-sm-6 col-xs-8" data-aos="fade-left" data-aos-delay="800" data-aos-duration="1000">
             <div class="h-100 rate-box rate-box-2 bg-cloudburst">
               <h3>
                 4.9
               </h3>
               <ul class="list-inline d-flex">
                 <li class=""><i class="fas fa-star"></i></li>
                 <li class=""><i class="fas fa-star"></i></li>
                 <li class=""><i class="fas fa-star"></i></li>
                 <li class=""><i class="fas fa-star"></i></li>
                 <li class=""><i class="fas fa-star"></i></li>
               </ul>
               <p>200+ Rating</p>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>

 <section class="section-bg feature-area-l1 text-center pt-4" id="screenshot" style="background-image: linear-gradient(#000000c9, #000000c9), url({{ asset('') }}front_end/image/body-bg.jpg) !important;background-repeat: no-repeat; background-position: center; background-size: cover;">
   <div class="container" data-aos="fade-up" data-aos-delay="800" data-aos-duration="1000">

     <div class="section__heading text-center">
       <h2>Apps <span>Screenshots</span></h2>
       <p>Proactively impact value-added channels via backend leadership skills. Efficiently revolutionize worldwide networks whereas strategic catalysts for change.</p>
     </div>
     <!--start app screen carousel-->
     <div class="screenshot-wrap">
       <div class="screenshot-frame"></div>
       <!-- Slider main container -->
       <div class="swiper screen-carousel">
         <!-- Additional required wrapper -->
         <div class="swiper-wrapper">
           <!-- Slides -->
           <div class="swiper-slide"><img src="{{ asset('') }}front_end/image/screenshots/1.png" class="img-fluid" alt="screenshots"/></div>
           <div class="swiper-slide"><img src="{{ asset('') }}front_end/image/screenshots/2.png" class="img-fluid" alt="screenshots"/></div>
           <div class="swiper-slide"><img src="{{ asset('') }}front_end/image/screenshots/3.png" class="img-fluid" alt="screenshots"/></div>
           <div class="swiper-slide"><img src="{{ asset('') }}front_end/image/screenshots/4.png" class="img-fluid" alt="screenshots"/></div>
           <div class="swiper-slide"><img src="{{ asset('') }}front_end/image/screenshots/5.png" class="img-fluid" alt="screenshots"/></div>
           <div class="swiper-slide"><img src="{{ asset('') }}front_end/image/screenshots/6.png" class="img-fluid" alt="screenshots"/></div>
           <div class="swiper-slide"><img src="{{ asset('') }}front_end/image/screenshots/7.png" class="img-fluid" alt="screenshots"/></div>
           <div class="swiper-slide"><img src="{{ asset('') }}front_end/image/screenshots/8.png" class="img-fluid" alt="screenshots"/></div>
           <div class="swiper-slide"><img src="{{ asset('') }}front_end/image/screenshots/9.png" class="img-fluid" alt="screenshots"/></div>
           <div class="swiper-slide"><img src="{{ asset('') }}front_end/image/screenshots/10.png" class="img-fluid" alt="screenshots"/></div>
         </div>
         
       </div>
       <div class="swiper-pagination"></div>
     </div>
     <!--end app screen carousel-->
   </div>
 </section>
 
@stop

@section('script')
@stop