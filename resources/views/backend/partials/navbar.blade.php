<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">
                <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('backend/assets/images/logo-1.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-1.png') }}" alt="" height="24">
                    </span>
                </a>
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('backend/assets/images/logo-1.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('backend/assets/images/logo-1.png') }}" alt="" height="24">
                    </span>
                </a>
            </div>

            <ul id="side-menu">

                @php
                    $u = auth()->user();
                    $dashOpen = request()->routeIs('dashboard') || request()->routeIs('dashboard.*');
                @endphp

                @auth
                    <li>
                        <a href="#sidebarDashboards" data-bs-toggle="collapse"
                            aria-expanded="{{ $dashOpen ? 'true' : 'false' }}">
                            <i data-feather="home"></i>
                            <span> Dashboard </span>
                            <span class="menu-arrow"></span>
                        </a>

                        <div class="collapse {{ $dashOpen ? 'show' : '' }}" id="sidebarDashboards">
                            <ul class="nav-second-level">

                                {{-- Admin Dashboard: super_admin + admin --}}
                                @if ($u->hasAnyRole(['super_admin', 'admin']) && Route::has('dashboard.admin'))
                                    <li>
                                        <a href="{{ route('dashboard.admin') }}"
                                            class="tp-link {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}">
                                            Admin Dashboard
                                        </a>
                                    </li>
                                @endif

                                {{-- Manager Dashboard: super_admin + admin --}}
                                @if ($u->hasAnyRole(['super_admin', 'admin']) && Route::has('dashboard.manager'))
                                    <li>
                                        <a href="{{ route('dashboard.manager') }}"
                                            class="tp-link {{ request()->routeIs('dashboard.manager') ? 'active' : '' }}">
                                            Manager Dashboard
                                        </a>
                                    </li>
                                @endif

                                {{-- Employee Dashboard: employee + admin + super_admin --}}
                                @if ($u->hasAnyRole(['super_admin', 'employee']) && Route::has('dashboard.employee'))
                                    <li>
                                        <a href="{{ route('dashboard.employee') }}"
                                            class="tp-link {{ request()->routeIs('dashboard.employee') ? 'active' : '' }}">
                                            Employee Dashboard
                                        </a>
                                    </li>
                                @endif

                                {{-- Adv-User Dashboard: adv_user + admin + super_admin --}}
                                @if ($u->hasAnyRole(['super_admin', 'adv_user']) && Route::has('dashboard.adv-user'))
                                    <li>
                                        <a href="{{ route('dashboard.adv-user') }}"
                                            class="tp-link {{ request()->routeIs('dashboard.adv-user') ? 'active' : '' }}">
                                            Adv-User Dashboard
                                        </a>
                                    </li>
                                @endif

                                {{-- Examinee Dashboard: examinee only (adjust if needed) --}}
                                @if ($u->hasAnyRole(['super_admin', 'admin', 'employee', 'adv_user', 'examinee']) && Route::has('dashboard.examinee'))
                                    <li>
                                        <a href="{{ route('dashboard.examinee') }}"
                                            class="tp-link {{ request()->routeIs('dashboard.examinee') ? 'active' : '' }}">
                                            Examinee Dashboard
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endauth

                {{-- Examinee has no permission --}}
                @if (!$u->hasAnyRole(['examinee']))
                    <li class="menu-title">Pages</li>
                    <li>
                        <a href="#sidebarcategories" data-bs-toggle="collapse">
                            <i class="mdi mdi-arrow-decision-outline"></i>
                            <span> Categories </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarcategories">
                            <ul class="nav-second-level">
                                <li>
                                    <a href="{{ route('categories.index') }}" class="tp-link">Category</a>
                                </li>
                                <li>
                                    <a href="{{ route('subcategories.index') }}" class="tp-link">Sub Category</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="{{ route('packages.index') }}" class="tp-link">
                            <i data-feather="columns"></i>
                            <span> Packages </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('question_years.index') }}" class="tp-link">
                            <i class="mdi mdi-yeast"></i>
                            <span> Question Years </span>
                        </a>
                    </li>
                    <li>
                        <a href="#sidebarMaps" data-bs-toggle="collapse">
                            <i data-feather="map"></i>
                            <span> Question's </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarMaps">
                            <ul class="nav-second-level">
                                <li>
                                    <a href="{{ route('questions.index') }}" class="tp-link">Question</a>
                                </li>
                                <li>
                                    <a href="{{ route('questions.table.all') }}" class="tp-link">Question List</a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="{{ route('countdown.edit') }}" class="tp-link">
                            <i class="mdi mdi-timer-check-outline"></i>
                            <span> Timer </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}" class="tp-link">
                            <i class="mdi mdi-account-star"></i>
                            <span> User Management </span>
                        </a>
                    </li>
                @endif

                <li class="menu-title">FrontPages</li>
                <li>
                    <a href="{{ route('index') }}" class="tp-link">
                        <i class="mdi mdi-home-import-outline"></i>
                        <span> Home Page </span>
                    </a>
                </li>

            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>
