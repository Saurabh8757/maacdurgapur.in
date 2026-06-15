@php
    $segment4 = Request::segment(4);
    $segment5 = Request::segment(5);
    $info = \App\Helper\admin\siteInformation::siteInfo();
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin::dashboard') }}" class="brand-link">
        <img src="{{url('/')}}/frontend/images/logo.webp" alt="MAAC Logo"
            class="brand-image img-circle elevation-3" style="width: 34px; height: 34px;">
        <span class="brand-text font-weight-light">MAAC</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('upload/images/profile/' . Auth::user()['profile_picture']) }}"
                    class="img-circle elevation-2" alt="User_Image" style="width: 36px; height: 36px;">
            </div>
            <div class="info">
                <a href="{{ route('admin::profile', ['name' => Auth::user()['slug_name']]) }}"
                    class="d-block">{{ Auth::user()['name'] }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item @if (url()->current() == route('admin::dashboard')) menu-open @endif">
                    <a href="{{ route('admin::dashboard') }}" class="nav-link @if (url()->current() == route('admin::dashboard')) active @endif">
                        <i class="fas fa-th-large nav-icon"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin::banner') }}"
                       class="nav-link
                            @if (in_array($segment4, ['banner','add-banner','edit-banner'])) active @endif">
                        <i class="fas fa-images nav-icon"></i>
                        <p>Banner</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin::course') }}"
                       class="nav-link
                            @if (in_array($segment4, ['course','add-course','edit-course'])) active @endif">
                        <i class="fas fa-graduation-cap nav-icon"></i>
                        <p>Courses</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin::about') }}"
                       class="nav-link
                            @if (in_array($segment4, ['about','edit-about'])) active @endif">
                        <i class="fas fa-info-circle nav-icon"></i>
                        <p>About</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin::user_detail') }}"
                       class="nav-link @if (in_array($segment4, ['users-details'])) active @endif">
                        <i class="fas fa-users nav-icon"></i>
                        <p>Users Details</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin::testimonials') }}"
                       class="nav-link
                            @if (in_array($segment4, ['testimonials','add-testimonial','edit-testimonial'])  ||
                                $segment5 == 'testimonial')
                                active @endif">
                        <i class="fas fa-quote-right nav-icon"></i>
                        <p>Testimonials</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin::contact') }}"
                       class="nav-link
                            @if (in_array($segment4, ['contact','add-contact','edit-contact'])) active @endif">
                        <i class="fas fa-address-card nav-icon"></i>
                        <p>Contact Info</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
