<div class="th-hero-wrapper hero-13" data-bg-src="{{ asset('fontend/assets/img/hero.png') }}" id="hero">
    <div class="container z-index-common">
        <div class="hero-style13">
            <span class="hero-subtitle">More Than 25,659+ Students</span>
            <h1 class="hero-title">We’re Best Online <br> Education Partners <br> University 2022</h1>
            <div class="checklist">
                <ul>
                    <li>Experts Advisors</li>
                    <li>538+ Courses</li>
                </ul>
            </div>
            @auth
                <div class="btn-group">
                    <a href="#" class="th-btn"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                        <i class="fas fa-long-arrow-right ms-2"></i>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                </div>
            @else
                <div class="btn-group">
                    <a href="{{ route('otp.show') }}" class="th-btn">Examinee ? Sign in with OTP<i
                            class="fas fa-long-arrow-right ms-2"></i></a>
                </div>
            @endauth
        </div>
    </div>
    <div class="hero-shape shape1">
        <img src="{{ asset('fontend/assets/img/update1/hero/shape_4_1.png') }}" alt="shape">
    </div>
    <div class="hero-shape shape2">
        <img src="{{ asset('fontend/assets/img/update1/hero/shape_4_2.png') }}" alt="shape">
    </div>
</div>
