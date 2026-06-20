@php
    $segment4 = Request::segment(4);
    $segment5 = Request::segment(5);
    $info = \App\Helper\admin\siteInformation::siteInfo();
    $settingsNavigation = app(\App\Services\Settings\SettingsNavigationService::class);
    $canViewBrandSettings = $settingsNavigation->canViewBrand(Auth::user());
    $canViewGlobalSettings = $settingsNavigation->canViewGlobal(Auth::user());
    $cmsAuthorization = app(\App\Services\Cms\CmsAuthorizationService::class);
    $adminBrandContext = app(\App\Services\Brands\BrandContextManager::class)->adminContext();
    $cmsMenuPermissions = [
        'faqs' => $adminBrandContext
            ? $cmsAuthorization->allows(Auth::user(), 'faqs', 'view', $adminBrandContext->brand())
            : false,
        'courses' => $adminBrandContext
            ? $cmsAuthorization->allows(Auth::user(), 'courses', 'view', $adminBrandContext->brand())
            : false,
        'features' => $adminBrandContext
            ? $cmsAuthorization->allows(Auth::user(), 'features', 'view', $adminBrandContext->brand())
            : false,
        'showcase' => $adminBrandContext
            ? $cmsAuthorization->allows(Auth::user(), 'showcase', 'view', $adminBrandContext->brand())
            : false,
    ];
    $canViewCmsMenu = in_array(true, $cmsMenuPermissions, true);
    $profilePicture = basename((string) Auth::user()->profile_picture);
    $profilePicturePath = $profilePicture !== ''
        ? public_path('upload/images/profile/'.$profilePicture)
        : null;
    $hasProfilePicture = $profilePicturePath && is_file($profilePicturePath);
    $defaultProfilePicture = 'upload/images/profile/user_image.jpg';
    $hasDefaultProfilePicture = is_file(public_path($defaultProfilePicture));
    $profileInitials = collect(preg_split('/\s+/', trim((string) Auth::user()->name)))
        ->filter()
        ->take(2)
        ->map(static fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('');
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
                @if ($hasProfilePicture)
                    <img src="{{ asset('upload/images/profile/'.$profilePicture) }}"
                        class="img-circle elevation-2" alt="{{ Auth::user()->name }}" style="width: 36px; height: 36px; object-fit: cover;">
                @elseif ($hasDefaultProfilePicture)
                    <img src="{{ asset($defaultProfilePicture) }}"
                        class="img-circle elevation-2" alt="{{ Auth::user()->name }}" style="width: 36px; height: 36px; object-fit: cover;">
                @else
                    <span class="img-circle elevation-2 d-flex align-items-center justify-content-center"
                        aria-label="{{ Auth::user()->name }}"
                        style="width: 36px; height: 36px; background: linear-gradient(135deg, #ff8a00, #ff4d00); color: #fff; font-size: 0.72rem; font-weight: 700;">
                        {{ $profileInitials ?: 'U' }}
                    </span>
                @endif
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

                @if ($canViewCmsMenu)
                    <li class="nav-item has-treeview @if (request()->routeIs('admin::content.*')) menu-open @endif">
                        <a href="#" class="nav-link @if (request()->routeIs('admin::content.*')) active @endif">
                            <i class="fas fa-layer-group nav-icon"></i>
                            <p>
                                Content Management
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if ($cmsMenuPermissions['faqs'])
                                <li class="nav-item">
                                    <a href="{{ route('admin::content.faq-categories.index') }}"
                                       class="nav-link @if (request()->routeIs('admin::content.faq-categories.*')) active @endif">
                                        <i class="fas fa-folder-open nav-icon"></i>
                                        <p>FAQ Categories</p>
                                    </a>
                                </li>
                            @endif
                            @foreach ([
                                'faqs' => ['label' => 'FAQs', 'icon' => 'fa-question-circle'],
                                'courses' => ['label' => 'Courses', 'icon' => 'fa-graduation-cap'],
                                'features' => ['label' => 'Features', 'icon' => 'fa-star'],
                                'showcase' => ['label' => 'Showcase', 'icon' => 'fa-images'],
                            ] as $module => $menu)
                                @if ($cmsMenuPermissions[$module])
                                    <li class="nav-item">
                                        <a href="{{ route("admin::content.{$module}.index") }}"
                                           class="nav-link @if (request()->routeIs("admin::content.{$module}.*")) active @endif">
                                            <i class="fas {{ $menu['icon'] }} nav-icon"></i>
                                            <p>{{ $menu['label'] }}</p>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif

                @if ($canViewBrandSettings || $canViewGlobalSettings)
                    <li class="nav-item has-treeview @if ($segment4 === 'settings') menu-open @endif">
                        <a href="#" class="nav-link @if ($segment4 === 'settings') active @endif">
                            <i class="fas fa-sliders-h nav-icon"></i>
                            <p>
                                Settings
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if ($canViewBrandSettings)
                                <li class="nav-item">
                                    <a href="{{ route('admin::settings.brand.index') }}"
                                       class="nav-link @if (request()->routeIs('admin::settings.brand.index')) active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Brand Settings</p>
                                    </a>
                                </li>
                            @endif
                            @if ($canViewGlobalSettings)
                                <li class="nav-item">
                                    <a href="{{ route('admin::settings.global.index') }}"
                                       class="nav-link @if (request()->routeIs('admin::settings.global.index')) active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Global Settings</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
