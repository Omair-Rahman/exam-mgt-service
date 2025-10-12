<!doctype html>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'BCS Exam')</title>
    <meta name="author" content="themeholy">
    <meta name="description" content="BCS Exam Preparation">
    <meta name="keywords" content="BCS, Exam, Preparation, Online, Education, University">
    <meta name="robots" content="INDEX,FOLLOW">
    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/img/favicons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link rel="shortcut icon" href="{{asset('fontend/assets/img/favicon.jpg')}}" type="image/x-icon">
    @include('fontend.partials.css')
    @stack('css')
    

</head>

<body>
    <!--==============================Mobile Menu============================== -->
    @include('fontend.partials.mobile')

    <!--============================== Header Area ==============================-->
    @include('fontend.partials.topbar')

    <!--============================== Hero Area ==============================-->
    @include('fontend.partials.hero')
    <!--======== / Hero Section ========-->

    <!--============================== About Area  ==============================-->
    @include('fontend.partials.about')

    <!--============================== Category Area  ==============================-->
    @include('fontend.partials.category')

    <!--============================== Counter Area  ==============================-->
    @include('fontend.partials.counter')

    <!--============================== Course Area  ==============================-->
    @include('fontend.partials.course')

    <!--============================== why Area  ==============================-->
    @include('fontend.partials.why')

    <!--============================== Popular Area  ==============================-->
    @include('fontend.partials.popular')

    <!--============================== Why Choose Us ==============================-->
    @include('fontend.partials.choose')

    <!--============================== Team Area ==============================-->
    @include('fontend.partials.examinee')

    <!--============================== Testimonial Area ==============================-->
    @include('fontend.partials.testimonial')

    <!--============================== Contact Area ==============================-->
    <!--@include('fontend.partials.contact') -->

    <!--============================== Footer Area ==============================-->
    @include('fontend.partials.footer')

    <!--********************************
			Code End  Here 
	******************************** -->

    <!-- Scroll To Top -->
    <div class="scroll-top">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;"></path>
        </svg>
    </div>

    <!--==============================All Js File ============================== -->
    @include('fontend.partials.js')

</body>

</html>