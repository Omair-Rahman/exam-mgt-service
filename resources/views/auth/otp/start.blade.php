<!doctype html>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>OTP Sender</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&family=Jost:wght@300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet"> 
    <!--============================== All CSS File ============================== -->
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{asset('fontend/assets/css/bootstrap.min.css')}}">
    <!-- Fontawesome Icon -->
    <link rel="stylesheet" href="{{asset('fontend/assets/css/fontawesome.min.css')}}">
    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="{{asset('fontend/assets/css/style.css')}}">
    <link rel="shortcut icon" href="{{asset('fontend/assets/img/favicon.jpg')}}" type="image/x-icon">
   
</head>

<body>
    <!--============================== Breadcumb ============================== -->
    <div class="breadcumb-wrapper " data-bg-src="{{asset('fontend/assets/img/bra.jpg')}}" data-overlay="title" data-opacity="8">
        <div class="breadcumb-shape" data-bg-src="{{asset('fontend/assets/img/bg/breadcumb_shape_1_1.png')}}">
        </div>
        <div class="shape-mockup breadcumb-shape2 jump d-lg-block d-none" data-right="30px" data-bottom="30px">
            <img src="{{asset('fontend/assets/img/bg/breadcumb_shape_1_2.png')}}" alt="shape">
        </div>
        <div class="shape-mockup breadcumb-shape3 jump-reverse d-lg-block d-none" data-left="50px" data-bottom="80px">
            <img src="{{asset('fontend/assets/img/bg/breadcumb_shape_1_3.png')}}" alt="shape">
        </div>
        <div class="container">
            <div class="breadcumb-content text-center">
                <h1 class="breadcumb-title">OTP Page</h1>
            </div>
        </div>
    </div>
    <!--============================= OTP Area  ==============================-->
    <div class="space" id="contact-sec">
        <div class="container">
            <div class="row">
                <div class="col-4" style="margin: 0px auto !important;">
                    <div class="contact-form-wrap" data-bg-src="{{asset('fontend/assets/img/bg/contact_bg_1.png')}}">
                        <span class="sub-title">Contact With Us!</span>
                        <h2 class="border-title">Get in Touch</h2>
                        <p class="mt-n1 mb-30 sec-text">Lorem ipsum dolor sit amet adipiscing elit, sed do eiusmod tempor eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        <form method="POST" action="{{ route('otp.send') }}" class="contact-form">@csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input name="identifier" placeholder="Email or phone" value="{{ old('identifier') }}" class="form-control style-white">
                                        <i class="fa-solid fa-square-phone"></i>
                                        <div class="form-btn col-12 mt-10">
                                            <button class="th-btn">Send OTP<i class="fas fa-long-arrow-right ms-2"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Jquery -->
    <script src="{{asset('fontend/assets/js/vendor/jquery-3.6.0.min.js')}}"></script>
    <!-- Slick Slider -->
    <script src="{{asset('fontend/assets/js/slick.min.js')}}"></script>
    <!-- Bootstrap -->
    <script src="{{asset('fontend/assets/js/bootstrap.min.js')}}"></script>
    <!-- Magnific Popup -->
    <script src="{{asset('fontend/assets/js/jquery.magnific-popup.min.js')}}"></script>
    <!-- Counter Up -->
    <script src="{{asset('fontend/assets/js/jquery.counterup.min.js')}}"></script>
    <!-- Range Slider -->
    <script src="{{asset('fontend/assets/js/jquery-ui.min.js')}}"></script>
    <!-- Isotope Filter -->
    <script src="{{asset('fontend/assets/js/imagesloaded.pkgd.min.js')}}"></script>

    <!-- Main Js File -->
    <script src="{{asset('fontend/assets/js/main.js')}}"></script>
</body>
</html>



