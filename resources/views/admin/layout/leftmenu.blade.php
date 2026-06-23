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

                <!-- Lead Management Menu -->
                <li class="nav-item @if (in_array($segment4, ['lead-management'])) menu-open @endif">
                    <a href="#" class="nav-link @if (in_array($segment4, ['lead-management'])) active @endif">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>
                            Lead Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin::leads.index') }}" class="nav-link @if ($segment4 == 'lead-management' && request('status') == null) active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Leads</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin::leads.index', ['status' => 'new']) }}" class="nav-link @if ($segment4 == 'lead-management' && request('status') == 'new') active @endif">
                                <i class="far fa-circle nav-icon text-info"></i>
                                <p>New Leads</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin::leads.index', ['status' => 'follow_up']) }}" class="nav-link @if ($segment4 == 'lead-management' && request('status') == 'follow_up') active @endif">
                                <i class="far fa-circle nav-icon text-warning"></i>
                                <p>Follow Up</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin::leads.index', ['status' => 'converted']) }}" class="nav-link @if ($segment4 == 'lead-management' && request('status') == 'converted') active @endif">
                                <i class="far fa-circle nav-icon text-success"></i>
                                <p>Converted</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin::leads.index', ['status' => 'closed']) }}" class="nav-link @if ($segment4 == 'lead-management' && request('status') == 'closed') active @endif">
                                <i class="far fa-circle nav-icon text-danger"></i>
                                <p>Closed</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item">
                    <a href="{{ route('admin::course') }}"
                       class="nav-link
                            @if (in_array($segment4, ['course','add-course','edit-course'])) active @endif">
                        <i class="fas fa-graduation-cap nav-icon"></i>
                        <p>Courses</p>
                    </a>
                </li>







                @if ($canViewCmsMenu)
                    <li class="nav-item has-treeview @if (request()->routeIs('admin::content.*') || request()->routeIs('admin::blog*')) menu-open @endif">
                        <a href="#" class="nav-link @if (request()->routeIs('admin::content.*') || request()->routeIs('admin::blog*')) active @endif">
                            <i class="fas fa-layer-group nav-icon"></i>
                            <p>
                                Content Management
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            {{-- FAQ Group --}}
                            @if ($cmsMenuPermissions['faqs'])
                            <li class="nav-item has-treeview @if (request()->routeIs('admin::content.faqs.*') || request()->routeIs('admin::content.faq-categories.*')) menu-open @endif">
                                <a href="#" class="nav-link @if (request()->routeIs('admin::content.faqs.*') || request()->routeIs('admin::content.faq-categories.*')) active @endif" style="padding-left: 2rem !important;">
                                    <i class="fas fa-question-circle nav-icon" style="font-size: 0.9rem;"></i>
                                    <p>
                                        FAQ
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin::content.faq-categories.index') }}"
                                           class="nav-link @if (request()->routeIs('admin::content.faq-categories.*')) active @endif" style="padding-left: 3rem !important;">
                                            <i class="fas fa-folder-open nav-icon" style="font-size: 0.8rem;"></i>
                                            <p style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">FAQ Categories</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin::content.faqs.index') }}"
                                           class="nav-link @if (request()->routeIs('admin::content.faqs.*')) active @endif" style="padding-left: 3rem !important;">
                                            <i class="far fa-circle nav-icon" style="font-size: 0.8rem;"></i>
                                            <p style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">FAQs</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            {{-- MAAC Group --}}
                            @if ($cmsMenuPermissions['courses'] || $cmsMenuPermissions['features'])
                            <li class="nav-item has-treeview @if (request()->routeIs('admin::content.courses.*') || request()->routeIs('admin::content.features.*')) menu-open @endif">
                                <a href="#" class="nav-link @if (request()->routeIs('admin::content.courses.*') || request()->routeIs('admin::content.features.*')) active @endif" style="padding-left: 2rem !important;">
                                    <i class="fas fa-graduation-cap nav-icon" style="font-size: 0.9rem;"></i>
                                    <p>
                                        MAAC
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @if ($cmsMenuPermissions['courses'])
                                    <li class="nav-item">
                                        <a href="{{ route('admin::content.courses.index') }}"
                                           class="nav-link @if (request()->routeIs('admin::content.courses.*')) active @endif" style="padding-left: 3rem !important;">
                                            <i class="far fa-circle nav-icon" style="font-size: 0.8rem;"></i>
                                            <p style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Courses</p>
                                        </a>
                                    </li>
                                    @endif
                                    @if ($cmsMenuPermissions['features'])
                                    <li class="nav-item">
                                        <a href="{{ route('admin::content.features.index') }}"
                                           class="nav-link @if (request()->routeIs('admin::content.features.*')) active @endif" style="padding-left: 3rem !important;">
                                            <i class="fas fa-star nav-icon" style="font-size: 0.8rem;"></i>
                                            <p style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Features</p>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            {{-- Student Work Group --}}
                            @if ($cmsMenuPermissions['showcase'])
                            <li class="nav-item has-treeview @if (request()->routeIs('admin::content.showcase.*') || request()->routeIs('admin::content.showcase-categories.*')) menu-open @endif">
                                <a href="#" class="nav-link @if (request()->routeIs('admin::content.showcase.*') || request()->routeIs('admin::content.showcase-categories.*')) active @endif" style="padding-left: 2rem !important;">
                                    <i class="fas fa-images nav-icon" style="font-size: 0.9rem;"></i>
                                    <p>
                                        Student Work
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin::content.showcase-categories.index') }}"
                                           class="nav-link @if (request()->routeIs('admin::content.showcase-categories.*')) active @endif" style="padding-left: 3rem !important;">
                                            <i class="fas fa-folder-open nav-icon" style="font-size: 0.8rem;"></i>
                                            <p style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Showcase Categories</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin::content.showcase.index') }}"
                                           class="nav-link @if (request()->routeIs('admin::content.showcase.*')) active @endif" style="padding-left: 3rem !important;">
                                            <i class="far fa-circle nav-icon" style="font-size: 0.8rem;"></i>
                                            <p style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Showcase</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            {{-- Blogs Group --}}
                            <li class="nav-item has-treeview @if (request()->routeIs('admin::blog*')) menu-open @endif">
                                <a href="#" class="nav-link @if (request()->routeIs('admin::blog*')) active @endif" style="padding-left: 2rem !important;">
                                    <i class="fas fa-blog nav-icon" style="font-size: 0.9rem;"></i>
                                    <p>
                                        Blogs
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin::blog-categories.index') }}"
                                           class="nav-link @if (request()->routeIs('admin::blog-categories.*')) active @endif" style="padding-left: 3rem !important;">
                                            <i class="fas fa-tags nav-icon" style="font-size: 0.8rem;"></i>
                                            <p style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Blog Categories</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin::blogs.index') }}"
                                           class="nav-link @if (request()->routeIs('admin::blogs.*')) active @endif" style="padding-left: 3rem !important;">
                                            <i class="far fa-circle nav-icon" style="font-size: 0.8rem;"></i>
                                            <p style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Blogs</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            {{-- Placement Showcase --}}
                            <li class="nav-item">
                                <a href="{{ route('admin::placement-showcases.index') }}"
                                   class="nav-link @if (request()->routeIs('admin::placement-showcases.*')) active @endif" style="padding-left: 2rem !important;">
                                    <i class="fas fa-user-graduate nav-icon" style="font-size: 0.9rem;"></i>
                                    <p>Placement Showcase</p>
                                </a>
                            </li>

                            {{-- Recruiters --}}
                            <li class="nav-item">
                                <a href="{{ route('admin::recruiters.index') }}"
                                   class="nav-link @if (request()->routeIs('admin::recruiters.*')) active @endif" style="padding-left: 2rem !important;">
                                    <i class="fas fa-building nav-icon" style="font-size: 0.9rem;"></i>
                                    <p>Recruiters</p>
                                </a>
                            </li>

                            {{-- Lead Forms --}}
                            <li class="nav-item">
                                <a href="{{ route('admin::lead_forms.index') }}"
                                   class="nav-link @if (request()->routeIs('admin::lead_forms.*')) active @endif" style="padding-left: 2rem !important;">
                                    <i class="fas fa-list-alt nav-icon" style="font-size: 0.9rem;"></i>
                                    <p>Lead Forms</p>
                                </a>
                            </li>
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
