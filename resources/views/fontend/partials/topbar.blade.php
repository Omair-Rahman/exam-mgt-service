<header class="th-header header-layout11 header-layout13 onepage-nav">
    <div class="header-layout2">
        <div class="header-top">
            <div class="container">
                <div class="row justify-content-center justify-content-lg-between align-items-center gy-2">
                    <div class="col-auto d-none d-lg-block">
                        <div class="header-links">
                            <ul>
                                <li><i class="fas fa-envelope"></i><b>Email Us: </b> <a
                                        href="mailto:codioustechnology@gmail.com">codioustechnology@gmail.com</a></li>
                                <li><i class="fas fa-phone"></i><b>Hotline: </b> <a
                                        href="tel:+8801307-959622">+8801307-959622</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="header-links">
                            <ul>
                                <li>
                                    <div class="header-social">
                                        <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                                        <a href="https://www.twitter.com/"><i class="fab fa-twitter"></i></a>
                                        <a href="https://www.linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                                        <a href="https://www.youtube.com/"><i class="fab fa-youtube"></i></a>
                                    </div>
                                </li>
                                <li>
                                    @if (Route::has('login'))
                                        <nav class="flex items-center justify-end gap-4">
                                            @auth
                                                Welcome {{ auth()->user()->name }}
                                            @else
                                                <a href="{{ route('login') }}">
                                                    Log in
                                                </a>
                                                @if (Route::has('register'))
                                                    <a href="{{ route('register') }}">
                                                        Register
                                                    </a>
                                                @endif
                                            @endauth
                                        </nav>
                                    @endif
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sticky-wrapper">
        <div class="sticky-active">
            <!-- Main Menu Area -->
            <div class="menu-area">
                <div class="container">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto">
                            <div class="header-logo">
                                <a href="{{ route('index') }}" class="navbar-brand"><img width="150" height="24"
                                        src="{{ asset('fontend/assets/img/logo-1.png') }}" alt="codiousTechnology"></a>
                            </div>
                        </div>

                        <div class="col-auto">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <nav class="main-menu d-none d-lg-inline-block">
                                        <ul>
                                            <li><a href="#hero">Home</a></li>
                                            <li><a href="#about-sec">About Us</a></li>
                                            <li><a href="#course-sec">Course</a></li>
                                            <li><a href="#team-sec">Instructor</a></li>
                                            <!--<li><a href="#contact-sec">Contact Us</a></li>-->
                                        </ul>
                                    </nav>
                                    <button type="button" class="th-menu-toggle d-inline-block d-lg-none"><i
                                            class="far fa-bars"></i></button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
