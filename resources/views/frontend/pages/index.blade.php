<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>MAAC Durgapur – West Bengal's #1 Animation, VFX & AI Creative Institute</title>
<meta name="description" content="MAAC Durgapur is West Bengal's leading Animation, VFX, Gaming, Graphic Design & AI Creative Institute. Industry-focused training, expert mentorship, modern studios and 100% placement support near City Centre, Durgapur.">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ url()->current() }}">
<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:title" content="MAAC Durgapur – West Bengal's #1 Animation, VFX & AI Creative Institute">
<meta property="og:description" content="Learn Animation, VFX, Gaming, Graphic Design & AI at MAAC Durgapur. Industry-focused training with 100% placement support.">
<meta property="og:image" content="{{ asset('frontend/images/pg-01.webp') }}">
<meta property="og:url" content="{{ url()->current() }}">
<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="MAAC Durgapur – West Bengal's #1 Animation, VFX & AI Creative Institute">
<meta name="twitter:description" content="MAAC Durgapur is West Bengal's leading Animation, VFX, Gaming, Graphic Design & AI Creative Institute. Industry-focused training, expert mentorship, modern studios and 100% placement support near City Centre, Durgapur.">
<meta name="twitter:image" content="{{ asset('frontend/images/pg-01.webp') }}">
<link rel="icon" type="image/png" href="{{ asset('frontend/images/favicon.png') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}?v={{ time() }}">
<link rel="stylesheet" href="{{ asset('frontend/css/chatbot.css') }}">
<style>
    /* URGENT CACHE BYPASS FIX FOR MOBILE & TAB HERO */
    @media (max-width: 1024px) {
        .hero-bg-img.parallax-bg {
            top: 0 !important;
            height: 100% !important;
            transform: none !important;
        }
        
        /* Ekdum soft radial mask text ke upar jo background me dissolve ho jayega */
        .hero-section::before {
            content: '' !important;
            display: block !important;
            position: absolute !important;
            inset: 0 !important;
            /* Center moved slightly up and right (70% 60%) to cover main heading, radius increased to 90% */
            background: radial-gradient(circle at 70% 60%, rgba(20, 20, 25, 0.55) 0%, rgba(20, 20, 25, 0.25) 60%, transparent 90%) !important;
            z-index: 1 !important;
            pointer-events: none !important;
        }

        /* Remove any other gradients */
        .hero-section::after {
            display: none !important;
        }
        .hero-content {
            position: relative !important;
            z-index: 5 !important;
            background: none !important;
        }
        .hero-content::before {
            display: none !important;
        }
    }
</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Rajdhani:wght@400;500;600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/10.3.1/swiper-bundle.min.css">
<!-- Counselling Modal CSS -->
<link rel="stylesheet" href="{{ asset('frontend/css/counselling-modal.css') }}?v={{ time() }}">
<!-- Preload hero image for LCP -->
<link rel="preload" as="image" href="{{ asset('frontend/images/pg-01.webp') }}" fetchpriority="high">
<style>
    @font-face {
        font-family: 'Barber Chop';
        src: url('{{ asset('frontend/fonts/barber_chop/BarberChop.otf') }}') format('opentype');
        font-weight: normal;
        font-style: normal;
    }

    .hero-heading,
    .hero-heading .line1,
    .hero-heading .line2,
    .hero-heading .line3 {
        font-family: 'Barber Chop', sans-serif !important;
        font-weight: 100 !important;
        letter-spacing: 2px;
    }
</style>
<style>
    /* FINAL BLEND & BUTTON FIX */
    
    /* 1. Purana z-index hataya taaki blend wapas aa jaye */
    #spacefic { z-index: 2 !important; }
    
    /* 2. Courses section ke transparent hisso ko clicks rokne se mana kiya */
    #courses,
    #courses .section-bg,
    #courses .sakura-canvas,
    #courses .interactive-leaf-canvas,
    #courses::before,
    #courses::after {
        pointer-events: none !important;
    }
    
    /* 3. Sirf Courses ke asli content ko clickable rakha */
    #courses .section-header,
    #courses .courses-swiper {
        pointer-events: auto !important;
    }

    /* 4. Space-E-Fic button ko click catch karne ke liye ready kiya */
    #spacefic .brand-section-content {
        pointer-events: auto !important;
    }

    /* 5. Desktop Mode on Phone FIX (Prevent extreme background cropping) */
    @media (min-width: 769px) and (orientation: portrait) {
        .hero-section, .institute-section {
            min-height: 600px !important;
            max-height: 800px !important;
        }
        .section-bg-img.parallax-bg {
            height: 110% !important; 
            top: -5% !important;
        }
    }
</style>
</head>
<body class="loading">

<!-- ===================== SITE LOADER ===================== -->
<style>
.custom-home-loader { position: fixed; inset: 0; z-index: 99999; background: rgba(5, 10, 20, 1); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); display: flex; flex-direction: column; align-items: center; justify-content: center; }
.custom-loader-brand { position: relative; width: 80px; height: 80px; }
.custom-loader-ring-outer { position: absolute; inset: 0; border-radius: 50%; border: 2.5px solid transparent; border-top-color: #ff7a00; border-right-color: rgba(251, 191, 36, 0.4); animation: customLoaderSpin 1.2s linear infinite; }
.custom-loader-ring-inner { position: absolute; inset: 10px; border-radius: 50%; border: 2px solid transparent; border-top-color: rgba(255, 122, 0, 0.5); border-right-color: #ff7a00; animation: customLoaderSpin 1.8s linear infinite reverse; }
.custom-loader-glow { position: absolute; inset: -20px; border-radius: 50%; background: radial-gradient(circle, rgba(255, 122, 0, 0.18) 0%, transparent 70%); animation: customLoaderGlow 2s ease-in-out infinite alternate; }
.custom-loader-text-brand { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 0.72rem; font-weight: 800; color: #ff7a00; letter-spacing: 1px; z-index: 1; font-family: 'Oswald', sans-serif; }
@keyframes customLoaderSpin { to { transform: rotate(360deg); } }
@keyframes customLoaderGlow { 0% { transform: scale(0.85); opacity: 0.4; } 100% { transform: scale(1.15); opacity: 1; } }
</style>
<div id="siteLoader" class="custom-home-loader">
  <div class="custom-loader-brand loader-content">
      <div class="custom-loader-glow"></div>
      <div class="custom-loader-ring-outer"></div>
      <div class="custom-loader-ring-inner"></div>
      <div class="custom-loader-text-brand">MAAC</div>
  </div>
</div>



<!-- Floating Action Buttons -->
<a href="https://wa.me/919333504000" class="fab fab-whatsapp" target="_blank">
  <img src="{{ asset('frontend/images/whatsapp__1_.png') }}" alt="WhatsApp" loading="lazy">
</a>


<!-- ===================== NAVBAR ===================== -->
<nav id="navbar">
  <!-- Mascot Overlap Decor -->
  <a href="{{ url('/') }}" class="nav-brand">
    <img src="{{ asset('frontend/images/maac/icons/transparent-logo.png') }}" alt="MAAC Durgapur Official Brand Mascot" class="nav-brand-icon" loading="lazy">
  </a>

  <!-- Desktop Links -->
  <ul class="nav-links">
    <li><a href="#home" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
    <li class="has-dropdown">
      <a href="#courses" class="{{ request()->routeIs('maac', 'aksha', 'space_e_fic') ? 'active' : '' }}">Courses <span class="arrow">▼</span></a>
      <ul class="dropdown">
        <li><a href="#courses"><span class="dot-icon"></span>Animation &amp; VFX</a></li>
        <li><a href="#courses"><span class="dot-icon"></span>Gaming &amp; Unreal Engine</a></li>
        <li><a href="#courses"><span class="dot-icon"></span>Motion Graphics &amp; Video Editing</a></li>
        <li><a href="#courses"><span class="dot-icon"></span>Graphic Design &amp; Multimedia</a></li>
        <li><a href="#courses"><span class="dot-icon"></span>AI Creative Tools &amp; Digital Content Creation</a></li>
        <li><a href="#courses"><span class="dot-icon"></span>Filmmaking &amp; Visual Storytelling</a></li>
      </ul>
    </li>
    <li><a href="{{ route('showcase') }}" class="{{ request()->routeIs('showcase') ? 'active' : '' }}">Students Work</a></li>
    <li><a href="{{ route('blogs.index') }}" class="{{ request()->routeIs('blogs.*') ? 'active' : '' }}">Blog</a></li>
    <li><a href="{{ route('faq') }}" class="{{ request()->routeIs('faq*') ? 'active' : '' }}">FAQ</a></li>
    <li><a href="#contact">Contact Us</a></li>
    
  </ul>

  <!-- Desktop CTA -->
  <a href="#" class="btn-counselling open-modal">Book Free Counselling</a>

  <!-- Hamburger (mobile) -->
  <button class="hamburger" id="hamburger" aria-label="Open menu" aria-expanded="false">
    <span class="ham-line"></span>
    <span class="ham-line"></span>
    <span class="ham-line"></span>
  </button>
</nav>

<!-- Mobile Overlay Menu -->
<div class="mobile-menu" id="mobileMenu" role="dialog" aria-modal="true" aria-label="Navigation">
  <div class="mobile-nav-inner">
    <a href="#home" class="mobile-link {{ request()->routeIs('home') ? 'active' : '' }}" data-close>Home</a>

    <a href="#courses" class="mobile-link {{ request()->routeIs('maac', 'aksha', 'space_e_fic') ? 'active' : '' }}" id="coursesToggle">
      Courses
      <span class="arrow" style="font-size:0.7rem">▼</span>
    </a>
    <ul class="mobile-sub" id="coursesSub">
      <li><a href="#courses" data-close><span class="dot-icon"></span>Animation &amp; VFX</a></li>
      <li><a href="#courses" data-close><span class="dot-icon"></span>Gaming &amp; Unreal Engine</a></li>
      <li><a href="#courses" data-close><span class="dot-icon"></span>Motion Graphics &amp; Video Editing</a></li>
      <li><a href="#courses" data-close><span class="dot-icon"></span>Graphic Design &amp; Multimedia</a></li>
      <li><a href="#courses" data-close><span class="dot-icon"></span>AI Creative Tools &amp; Digital Content Creation</a></li>
      <li><a href="#courses" data-close><span class="dot-icon"></span>Filmmaking &amp; Visual Storytelling</a></li>
    </ul>

    <a href="{{ route('showcase') }}" class="mobile-link {{ request()->routeIs('showcase') ? 'active' : '' }}" data-close>Students Work</a>
    <a href="{{ route('blogs.index') }}" class="mobile-link {{ request()->routeIs('blogs.*') ? 'active' : '' }}" data-close>Blog</a>
    <a href="{{ route('faq') }}" class="mobile-link {{ request()->routeIs('faq*') ? 'active' : '' }}" data-close>FAQ</a>
    <a href="#contact" class="mobile-link" data-close>Contact Us</a>
    

    <div class="mobile-cta-wrap">
      <p class="mobile-divider">Ready to start?</p>
      <a href="#counselling" class="mobile-cta" data-close>Book Free Counselling</a>
    </div>
  </div>
</div>

<!-- ===================== HERO SECTION ===================== -->
<section id="home" class="hero-section">
  <div class="hero-bg">
    <img src="{{ asset('frontend/images/pg-01.webp') }}" alt="Hero Background" class="hero-bg-img parallax-bg" data-speed="0.3" fetchpriority="high">
  </div>
  <canvas id="hero-sakura-canvas"></canvas>
  <canvas class="interactive-leaf-canvas"></canvas>
  
  <div class="hero-badge">End-to-End</div>
  
  <div class="hero-content">
    <h1 class="hero-heading">
      <span class="line1">WEST BENGAL'S</span>
      <span class="line2">#1 ANIMATION,<br class="hero-br">VFX & AI</span>
      <span class="line3 orange-gradient">CREATIVE<br class="hero-br">INSTITUTE</span>
    </h1>
    <p class="hero-subtext">
      Learn from the future of creatives at MAAC Durgapur the leading destination for Animation, VFX, Gaming, Graphic Design, Multimedia, and Digital Filmmaking. Located in the heart of Durgapur, we offer industry-focused training, expert mentorship, modern studios, and 100% placement support.
    </p>
    <p class="hero-sub2">From Kolkata to Burdwan, Bolpur to Bankura, and Purulia, to diverse students choose MAAC Durgapur for advanced, funded career training to fulfill animation and digitally-enabled creativity and educational platforms and effective career opportunities without relocating to metro cities.</p>
    <div class="hero-buttons">
      <a href="#" class="btn-hero btn-primary open-modal">Get Facilities Available <span class="btn-arrow">→</span></a>
      <a href="#" class="btn-hero btn-secondary">Watch Showreel ▶</a>
    </div>
  </div>
</section>

<!-- Bypassing CDN/Browser cache for urgent overlap fix -->
<style>
  .institute-section { padding-bottom: 180px !important; }
  .dark-bg::after { pointer-events: none !important; }
  .brand-section-content { position: relative; z-index: 9999 !important; pointer-events: auto !important; }
  .courses-section { pointer-events: none !important; }
</style>

<!-- ===================== MAAC SECTION ===================== -->
<section id="maac" class="institute-section maac-section">
  <div class="section-bg">
    <img src="{{ asset('frontend/images/pg-02.webp') }}" alt="MAAC Background" class="section-bg-img parallax-bg" data-speed="0.2" loading="lazy">
  </div>
  <canvas class="sakura-canvas" data-section="maac"></canvas>
  <canvas class="interactive-leaf-canvas"></canvas>
  
  <div class="brand-section-content align-right">
    <div class="brand-logo-box maac-logo-box">
      <a href="{{ route('maac') }}" class="premium-brand-link">
        <img src="{{ asset('frontend/images/maac_new_logo.png') }}" alt="MAAC Premium Brand Logo" class="premium-brand-logo" loading="lazy">
      </a>
    </div>
    <div class="card-content">
      <p>MAAC (Maya Academy of Advanced Creativity) Durgapur is the region's most trusted animation and multimedia training institute, strategically located near City Center, Durgapur. Students from Kolkata, Burdwan, Bolpur, Bankura, Asansol, Raniganj and Purulia choose us for career-ready skills in animation, VFX, gaming, graphic design, and the latest AI creative tools.</p>
    </div>
    <a href="{{ route('maac') }}" class="btn-register btn-gradient-orange scroll-to-hero-form" style="position: relative; z-index: 999;" onclick="window.location.href='{{ route('maac') }}'; return false;">
      Explore Now →
    </a>
  </div>
</section>

<!-- ===================== AKSHA SECTION ===================== -->
<section id="aksha" class="institute-section aksha-section">
  <div class="section-bg">
    <img src="{{ asset('frontend/images/pg-03.webp') }}" alt="AKSHA Background" class="section-bg-img parallax-bg" data-speed="0.2" loading="lazy">
  </div>
  <canvas class="sakura-canvas" data-section="aksha"></canvas>
  <canvas class="interactive-leaf-canvas"></canvas>
  
  <div class="brand-section-content align-left">
    <div class="brand-logo-box aksha-logo-box">
      <a href="{{ route('aksha') }}" class="premium-brand-link">
        <img src="{{ asset('frontend/images/aksha_new_logo.png') }}" alt="AKSHA Premium Brand Logo" class="premium-brand-logo" loading="lazy">
      </a>
    </div>
    <div class="card-content">
      <p>AKSHA is a modern creative design institute focused on visual storytelling, UI/UX, graphic design, branding, digital communication, and AI creative tool. With hands-on learning, expert mentorship & industry-focused training, AKSHA helps students build professional skills, creative portfolios, and successful careers in the digital design project. West Bengal's no.1 UI/UX, branding and Digital marketing training institute, strategically located near City Center, Durgapur.</p>
    </div>
    <a href="{{ route('aksha') }}" class="btn-register btn-gradient-blue scroll-to-hero-form" style="position: relative; z-index: 999;" onclick="window.location.href='{{ route('aksha') }}'; return false;">
      Explore Now →
    </a>
  </div>
</section>

<!-- ===================== SPACE-E-FIC SECTION ===================== -->
<section id="spacefic" class="institute-section spacefic-section">
  <div class="section-bg">
    <img src="{{ asset('frontend/images/pg-04.webp') }}" alt="Space-E-Fic Background" class="section-bg-img parallax-bg" data-speed="0.2" loading="lazy">
  </div>
  <canvas class="sakura-canvas" data-section="spacefic"></canvas>
  <canvas class="interactive-leaf-canvas"></canvas>
  
  <div class="brand-section-content align-right">
    <div class="brand-logo-box spacefic-logo-box">
      <a href="{{ route('space_e_fic') }}" class="premium-brand-link">
        <img src="{{ asset('frontend/images/space-e-fic_new_logo.png') }}" alt="Space-E-Fic Premium Brand Logo" class="premium-brand-logo" loading="lazy">
      </a>
    </div>
    <div class="card-content">
      <p>Space-E-Fic is a future focused creative technology institute specializing in AI, Robotics, Game Development, Coding, AR/VR, and immersive digital innovation. Designed for the next generation of creators and tech innovators, Space-E-Fic creativity with futuristic technology through hands-on learning and real world training best no.1 institute, strategically located near City Center, Durgapur.</p>
    </div>
    <a href="{{ route('space_e_fic') }}" class="btn-register btn-gradient-cyan scroll-to-hero-form" style="position: relative; z-index: 999;" onclick="window.location.href='{{ route('space_e_fic') }}'; return false;">
      Explore Now →
    </a>
  </div>
</section>

<!-- ===================== COURSES SECTION ===================== -->
<section id="courses" class="courses-section">
  <div class="section-bg dark-bg">
    <img src="{{ asset('frontend/images/pg-05.webp') }}" alt="Courses Background" class="section-bg-img parallax-bg" data-speed="0.15" loading="lazy">
  </div>
  <canvas class="sakura-canvas" data-section="courses"></canvas>
  <canvas class="interactive-leaf-canvas"></canvas>
  
  <div class="section-header">
    <h2 class="section-title gold">EXPLORE CAREER-FOCUSED COURSES IN</h2>
    <p class="section-subtitle">Become Industry Ready with AI + Creativity + Technology</p>
  </div>
  
  <!-- Swiper Carousel -->
  <div class="swiper courses-swiper">
    <div class="swiper-wrapper">
      @if(isset($courses) && !$courses->isEmpty())
        @foreach($courses as $course)
          <div class="swiper-slide">
            <div class="course-card">
              <div class="course-img">
                <img loading="lazy"
                     src="{{ asset($course->image) }}"
                     alt="{{ $course->name }}"
                     onerror="this.onerror=null;this.src='{{ asset('upload/images/course/default.png') }}';">
                <div class="course-overlay"></div>
              </div>
              <div class="course-info">
                <h3>{{ $course->name }}</h3>
                <p class="course-desc">{{ Str::limit($course->desc, 120) }}</p>
                @php
                  $link = '#';
                  if (!empty($course->page_link) && $course->page_link !== 'default') {
                      $link = route($course->page_link);
                  }
                @endphp
                <a href="{{ $link }}" class="course-link">Learn More →</a>
              </div>
            </div>
          </div>
        @endforeach
      @endif
    </div>
    <div class="swiper-button-prev courses-prev"></div>
    <div class="swiper-button-next courses-next"></div>
  </div>
</section>

<!-- ===================== STATS SECTION ===================== -->
<section class="stats-section">
  <canvas class="sakura-canvas" data-section="stats"></canvas>
  <canvas class="interactive-leaf-canvas"></canvas>
  <div class="stats-grid">
    <div class="stat-item" data-count="5000" data-suffix="+">
      <span class="stat-num" data-target="5000">0</span><span class="stat-suffix">+</span>
      <p>Students Trained</p>
    </div>
    <div class="stat-item" data-count="100" data-suffix="%">
      <span class="stat-num" data-target="100">0</span><span class="stat-suffix">%</span>
      <p>Placement Assistance</p>
    </div>
    <div class="stat-item" data-count="20" data-suffix="+">
      <span class="stat-num" data-target="20">0</span><span class="stat-suffix">+</span>
      <p>Years of Excellence</p>
    </div>
    <div class="stat-item" data-count="50" data-suffix="+">
      <span class="stat-num" data-target="50">0</span><span class="stat-suffix">+</span>
      <p>Industry Partners</p>
    </div>
  </div>
</section>

<!-- ===================== GROUPED: RECRUITERS + PLACEMENT ===================== -->
<style>
    /* Global fixes for sticky positioning */
    html, body {
        overflow-x: visible !important;
        overflow-x: clip !important; 
    }
    .placement-group {
        background-image: none !important; /* Remove static image */
        overflow: visible !important;
    }
    
    /* The Absolute Container that spans the full placement-group height without taking space */
    .placement-video-absolute-wrapper {
        position: absolute !important;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 0 !important;
        pointer-events: none;
    }
    
    /* The Sticky Element that stays in view */
    .placement-video-sticky-box {
        position: -webkit-sticky !important;
        position: sticky !important;
        top: 0;
        width: 100%;
        height: 100vh;
        height: 100svh;
        overflow: hidden;
        /* Instant Poster while video loads */
        background: url('{{ asset("frontend/images/pg-06.webp") }}') center/cover no-repeat;
    }

    /* Inner relative wrapper to fix Safari sticky offset parent bug */
    .placement-video-inner {
        position: relative !important;
        width: 100%;
        height: 100%;
        transform: translateZ(0); /* Force hardware acceleration */
    }
    
    .placement-bg-video {
        position: absolute !important;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: translateZ(0); /* Force hardware acceleration to prevent blur/pixelation */
        backface-visibility: hidden;
        transition: opacity 1.5s ease-in-out; /* Smooth crossfade for seamless loop */
    }
    
    .placement-bg-overlay {
        position: absolute !important;
        inset: 0;
        background: rgba(5, 5, 12, 0.15);
        z-index: 2 !important;
    }

    /* All content sections scroll normally over the sticky video */
    .recruiters-section,
    .placement-section,
    .journey-section,
    .ai-section {
        position: relative !important; /* Force relative so they scroll */
        z-index: 10 !important;
        background: transparent !important;
        height: auto !important; /* Remove any 100vh overrides */
        min-height: auto !important;
    }
</style>
<div class="placement-group">

    <!-- Seamless Crossfade Sticky Video Background -->
    <div class="placement-video-absolute-wrapper">
        <div class="placement-video-sticky-box">
            <!-- Inner wrapper resolves occasional size distortion/stretch bugs on Safari -->
            <div class="placement-video-inner">
                <!-- Dual Videos for Seamless Looping -->
                <video id="bg-video-1" class="placement-bg-video" muted playsinline preload="auto" oncontextmenu="return false;" controlsList="nodownload nofullscreen noremoteplayback" disablePictureInPicture style="z-index: 1; opacity: 1;"></video>
                <video id="bg-video-2" class="placement-bg-video" muted playsinline preload="auto" oncontextmenu="return false;" controlsList="nodownload nofullscreen noremoteplayback" disablePictureInPicture style="z-index: 0; opacity: 0;"></video>
                <div class="placement-bg-overlay"></div>
            </div>
        </div>
    </div>

    <!-- Performance & Seamless Loop Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const video1 = document.getElementById('bg-video-1');
            const video2 = document.getElementById('bg-video-2');
            if(!video1 || !video2) return;

            let activeVideo = 1;
            const crossfadeMargin = 1.0; // Starts fading 1 second before video ends
            
            // 1. Performance Optimization: Only load the correct video for the device!
            let currentMode = window.innerWidth <= 1024 ? 'mobile' : 'desktop';
            
            function loadVideos() {
                const videoSrc = (currentMode === 'mobile' ? "{{ asset('frontend/vedio/waterfall.mp4') }}" : "{{ asset('frontend/vedio/waterfall_desktop.mp4') }}") + "?v=" + new Date().getTime();
                video1.src = videoSrc;
                video2.src = videoSrc;
                video1.load();
                video2.load();
                
                // Play the active video after swapping sources
                if (activeVideo === 1) video1.play().catch(e=>console.log(e));
                else video2.play().catch(e=>console.log(e));
            }
            
            // Initial load
            loadVideos();
            
            // Listen for window resize to swap videos if testing responsive design (fixes bad quality/cropping)
            window.addEventListener('resize', function() {
                const newMode = window.innerWidth <= 1024 ? 'mobile' : 'desktop';
                if (currentMode !== newMode) {
                    currentMode = newMode;
                    loadVideos(); // Hot-swap the video files!
                }
            });

            // 2. Seamless Loop Crossfade Logic
            function checkVideoTime() {
                const currentVid = activeVideo === 1 ? video1 : video2;
                const nextVid = activeVideo === 1 ? video2 : video1;
                
                // When we are near the end of the current video...
                if (currentVid.duration && currentVid.currentTime >= (currentVid.duration - crossfadeMargin)) {
                    // Prepare and play the next video from the start
                    nextVid.currentTime = 0;
                    nextVid.play().catch(e => console.log(e));
                    
                    // Trigger the CSS crossfade
                    nextVid.style.opacity = '1';
                    nextVid.style.zIndex = '1';
                    currentVid.style.opacity = '0';
                    currentVid.style.zIndex = '0';
                    
                    activeVideo = activeVideo === 1 ? 2 : 1;
                    
                    // Wait until crossfade finishes before checking again
                    setTimeout(checkVideoTime, crossfadeMargin * 1000 + 100);
                    return;
                }
                
                // Efficient loop using requestAnimationFrame
                requestAnimationFrame(checkVideoTime);
            }
            
            // Start monitoring
            requestAnimationFrame(checkVideoTime);
        });
    </script>

<!-- ===================== RECRUITERS SECTION ===================== -->
@if(isset($recruiters) && $recruiters->count() > 0)
<section class="recruiters-section">
  <!-- <canvas class="sakura-canvas" data-section="recruiters"></canvas> -->
  <!-- <canvas class="interactive-leaf-canvas"></canvas> -->
  
  <div class="section-header">
    <h2 class="section-title gold">OUR TOP RECRUITERS</h2>
    <p class="recruiter-desc">MAAC Durgapur students consistently secure placements across leading animation studios, gaming companies, and digital agencies across India and internationally.</p>
  </div>
  
  <div class="logo-ticker-wrapper">
    <div class="logo-ticker" id="logo-ticker">
      @foreach($recruiters as $recruiter)
        <div class="ticker-item">
          @if($recruiter->logo)
            <div class="recruiter-logo" style="padding: 10px;">
                <img src="{{ asset('storage/' . $recruiter->logo->storage_key) }}" alt="{{ $recruiter->company_name }}" class="recruiter-logo-img" style="max-height: 45px; max-width: 130px; object-fit: contain;">
            </div>
          @else
            <div class="recruiter-logo {{ $recruiter->css_class }}">{!! $recruiter->custom_html ?? $recruiter->company_name !!}</div>
          @endif
        </div>
      @endforeach
      <!-- Clones for infinite loop -->
      @foreach($recruiters->take(4) as $recruiter)
        <div class="ticker-item">
          @if($recruiter->logo)
            <div class="recruiter-logo" style="padding: 10px;">
                <img src="{{ asset('storage/' . $recruiter->logo->storage_key) }}" alt="{{ $recruiter->company_name }}" class="recruiter-logo-img" style="max-height: 45px; max-width: 130px; object-fit: contain;">
            </div>
          @else
            <div class="recruiter-logo {{ $recruiter->css_class }}">{!! $recruiter->custom_html ?? $recruiter->company_name !!}</div>
          @endif
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- ===================== PLACEMENT SECTION ===================== -->
@if(isset($placements) && $placements->count() > 0)
<section class="placement-section">
  <!-- <canvas class="sakura-canvas" data-section="placement"></canvas> -->
  <div class="section-header">
    <span class="placement-accent">✦ Premium Placements</span>
    <h2 class="section-title gold">OUR PLACEMENT PROMISE</h2>
    <p class="section-subtitle">When students from Durgapur, Kolkata, Asansol, Burdwan, Raniganj and Purulia search for the best animation studios, agencies, and production houses across India and internationally, we help with up to</p>
  </div>
  
  <div class="swiper placement-swiper">
    <div class="swiper-wrapper">
      @foreach($placements as $placement)
      <div class="swiper-slide">
        <div class="placement-card">
          <div class="placement-card-img-wrap">
            <div class="placement-card-ribbon"></div>
            @if($placement->studentImage)
              @if(Str::startsWith($placement->studentImage->storage_key, 'upload/'))
                <img src="{{ asset($placement->studentImage->storage_key) }}" alt="{{ $placement->student_name }}" class="placement-card-img" loading="lazy">
              @else
                <img src="{{ asset('storage/' . $placement->studentImage->storage_key) }}" alt="{{ $placement->student_name }}" class="placement-card-img" loading="lazy">
              @endif
            @else
              <img src="{{ asset('frontend/images/default-avatar.jpg') }}" alt="{{ $placement->student_name }}" class="placement-card-img" loading="lazy">
            @endif
          </div>
          <div class="placement-card-body">
            <h4 class="student-name">{{ $placement->student_name }}</h4>
            <p class="company-name">{{ $placement->company ? $placement->company->name : $placement->company_name }}</p>
            <p class="job-role">{{ $placement->designation }}</p>
            <div class="salary-wrap">
              <span class="salary-amount">₹{{ number_format($placement->annual_package) }}</span>
              <span class="salary-label">PER ANNUM</span>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- ===================== JOURNEY SECTION ===================== -->
<section class="journey-section">
  <!-- <canvas class="sakura-canvas" data-section="journey"></canvas> -->
  <!-- <canvas class="interactive-leaf-canvas"></canvas> -->
  <div class="section-header">
    <h2 class="section-title gold">YOUR JOURNEY TO EXCELLENCE BEGINS HERE</h2>
    <p class="section-subtitle">Join thousands of students from Durgapur, Kolkata, Asansol, Burdwan, Raniganj, Bolpur and Purulia who have built their careers in animation and technology at MAAC Durgapur.</p>
  </div>
  
  <div class="swiper journey-swiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide">
        <div class="journey-card">
          <div class="journey-card-bg">
            <img src="{{ asset('frontend/images/animation.webp') }}" alt="Spider-Man: Across the Spider-Verse" class="journey-card-img" loading="lazy">
          </div>
          <div class="journey-card-overlay"></div>
          <div class="journey-card-content">
            <span class="journey-card-badge">Animation</span>
            <h3 class="journey-card-title">Spider-Man: Across the Spider-Verse</h3>
            <div class="journey-card-meta">
              <span class="journey-card-student">Priya Sharma</span>
              <span class="journey-card-sep">•</span>
              <span class="journey-card-company">MAAC Durgapur</span>
            </div>
            <p class="journey-card-desc">A dimensional leap into the Spider-Verse using advanced animation techniques, CGI compositing, and visual storytelling mastered at MAAC Durgapur.</p>
          </div>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="journey-card">
          <div class="journey-card-bg">
            <img src="{{ asset('upload/SERVICE/graphic_small_banner-3.jpg') }}" alt="AKSHA Design Portfolio" class="journey-card-img" loading="lazy">
          </div>
          <div class="journey-card-overlay"></div>
          <div class="journey-card-content">
            <span class="journey-card-badge">Graphic Design</span>
            <h3 class="journey-card-title">AKSHA Brand Identity Suite</h3>
            <div class="journey-card-meta">
              <span class="journey-card-student">Rahul Verma</span>
              <span class="journey-card-sep">•</span>
              <span class="journey-card-company">AKSHA Institute</span>
            </div>
            <p class="journey-card-desc">A comprehensive branding and UI/UX portfolio showcasing modern design thinking, visual identity systems, and digital creative tools.</p>
          </div>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="journey-card">
          <div class="journey-card-bg">
            <img src="{{ asset('frontend/images/esport-video-game-r-wearing-headset-playing-online-video-game-space-shooter-championship.webp') }}" alt="Star Wars: Cinematic Tribute" class="journey-card-img" loading="lazy">
          </div>
          <div class="journey-card-overlay"></div>
          <div class="journey-card-content">
            <span class="journey-card-badge">VFX & Cinema</span>
            <h3 class="journey-card-title">Star Wars: Cinematic Tribute</h3>
            <div class="journey-card-meta">
              <span class="journey-card-student">Arjun Mukherjee</span>
              <span class="journey-card-sep">•</span>
              <span class="journey-card-company">MAAC Durgapur</span>
            </div>
            <p class="journey-card-desc">Visual effects and compositing masterpiece featuring spacecraft rendering, particle simulations, and post-production workflows.</p>
          </div>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="journey-card">
          <div class="journey-card-bg">
            <img src="{{ asset('upload/SERVICE/3d animation.jpg') }}" alt="The Last Samurai: VFX Breakdown" class="journey-card-img" loading="lazy">
          </div>
          <div class="journey-card-overlay"></div>
          <div class="journey-card-content">
            <span class="journey-card-badge">VFX</span>
            <h3 class="journey-card-title">The Last Samurai: VFX Breakdown</h3>
            <div class="journey-card-meta">
              <span class="journey-card-student">Sneha Banerjee</span>
              <span class="journey-card-sep">•</span>
              <span class="journey-card-company">Prime Focus</span>
            </div>
            <p class="journey-card-desc">Historical battle scene recreated with particle effects, matte painting, and compositing using Nuke and Maya at MAAC Durgapur.</p>
          </div>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="journey-card">
          <div class="journey-card-bg">
            <img src="{{ asset('upload/SERVICE/3d-only.jpg') }}" alt="Cyberpunk 2077: Fan Trailer" class="journey-card-img" loading="lazy">
          </div>
          <div class="journey-card-overlay"></div>
          <div class="journey-card-content">
            <span class="journey-card-badge">Gaming</span>
            <h3 class="journey-card-title">Cyberpunk 2077: Fan Trailer</h3>
            <div class="journey-card-meta">
              <span class="journey-card-student">Vikram Das</span>
              <span class="journey-card-sep">•</span>
              <span class="journey-card-company">Rockstar Games</span>
            </div>
            <p class="journey-card-desc">Neon-drenched cinematic trailer with real-time VFX, 3D environment modeling, and motion tracking techniques.</p>
          </div>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="journey-card">
          <div class="journey-card-bg">
            <img src="{{ asset('upload/SERVICE/animation.jpeg') }}" alt="Brand Identity for TechStart" class="journey-card-img" loading="lazy">
          </div>
          <div class="journey-card-overlay"></div>
          <div class="journey-card-content">
            <span class="journey-card-badge">Graphic Design</span>
            <h3 class="journey-card-title">Brand Identity for TechStart</h3>
            <div class="journey-card-meta">
              <span class="journey-card-student">Ananya Gupta</span>
              <span class="journey-card-sep">•</span>
              <span class="journey-card-company">AKSHA Institute</span>
            </div>
            <p class="journey-card-desc">Complete brand identity system including logo, typography, color palette, and marketing collateral for a tech startup.</p>
          </div>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="journey-card">
          <div class="journey-card-bg">
            <img src="{{ asset('upload/SERVICE/animation2.jpg') }}" alt="The Ocean's Tale" class="journey-card-img" loading="lazy">
          </div>
          <div class="journey-card-overlay"></div>
          <div class="journey-card-content">
            <span class="journey-card-badge">Animation</span>
            <h3 class="journey-card-title">The Ocean's Tale</h3>
            <div class="journey-card-meta">
              <span class="journey-card-student">Maya Chatterjee</span>
              <span class="journey-card-sep">•</span>
              <span class="journey-card-company">MAAC Durgapur</span>
            </div>
            <p class="journey-card-desc">A heartfelt 3D animated short film about marine conservation, featuring character rigging, fluid simulations, and environmental lighting.</p>
          </div>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="journey-card">
          <div class="journey-card-bg">
            <img src="{{ asset('frontend/images/ui_ux_redesign.png') }}" alt="UI/UX Redesign — EduApp" class="journey-card-img" loading="lazy">
          </div>
          <div class="journey-card-overlay"></div>
          <div class="journey-card-content">
            <span class="journey-card-badge">UI/UX Design</span>
            <h3 class="journey-card-title">UI/UX Redesign — EduApp</h3>
            <div class="journey-card-meta">
              <span class="journey-card-student">Rohan Sen</span>
              <span class="journey-card-sep">•</span>
              <span class="journey-card-company">Tata Elxsi</span>
            </div>
            <p class="journey-card-desc">User research-driven redesign of an educational platform with wireframes, prototypes, and usability testing in Figma.</p>
          </div>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="journey-card">
          <div class="journey-card-bg">
            <img src="{{ asset('frontend/images/animation.webp') }}" alt="Motion Graphics Reel" class="journey-card-img" loading="lazy">
          </div>
          <div class="journey-card-overlay"></div>
          <div class="journey-card-content">
            <span class="journey-card-badge">Motion Design</span>
            <h3 class="journey-card-title">Motion Graphics Reel 2026</h3>
            <div class="journey-card-meta">
              <span class="journey-card-student">Kriti Singh</span>
              <span class="journey-card-sep">•</span>
              <span class="journey-card-company">DNEG</span>
            </div>
            <p class="journey-card-desc">A dynamic showreel combining kinetic typography, logo animations, and broadcast graphics created in After Effects and Cinema 4D.</p>
          </div>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="journey-card">
          <div class="journey-card-bg">
            <img src="{{ asset('upload/SERVICE/graphic_small_banner-3.jpg') }}" alt="Architectural Visualization" class="journey-card-img" loading="lazy">
          </div>
          <div class="journey-card-overlay"></div>
          <div class="journey-card-content">
            <span class="journey-card-badge">3D Modeling</span>
            <h3 class="journey-card-title">Architectural Visualization</h3>
            <div class="journey-card-meta">
              <span class="journey-card-student">Debashish Roy</span>
              <span class="journey-card-sep">•</span>
              <span class="journey-card-company">Space-E-Fic</span>
            </div>
            <p class="journey-card-desc">Photorealistic 3D architectural renders with lighting, texturing, and walkthrough animation using 3ds Max and V-Ray.</p>
          </div>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="journey-card">
          <div class="journey-card-bg">
            <img src="{{ asset('upload/SERVICE/animation.jpeg') }}" alt="Short Film: The Awakening" class="journey-card-img" loading="lazy">
          </div>
          <div class="journey-card-overlay"></div>
          <div class="journey-card-content">
            <span class="journey-card-badge">Filmmaking</span>
            <h3 class="journey-card-title">Short Film: The Awakening</h3>
            <div class="journey-card-meta">
              <span class="journey-card-student">Ishita Nandi</span>
              <span class="journey-card-sep">•</span>
              <span class="journey-card-company">MAAC Durgapur</span>
            </div>
            <p class="journey-card-desc">A narrative short film exploring themes of self-discovery with professional color grading, sound design, and post-production.</p>
          </div>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="journey-card">
          <div class="journey-card-bg">
            <img src="{{ asset('frontend/images/esport-video-game-r-wearing-headset-playing-online-video-game-space-shooter-championship.webp') }}" alt="Game Environment Design" class="journey-card-img" loading="lazy">
          </div>
          <div class="journey-card-overlay"></div>
          <div class="journey-card-content">
            <span class="journey-card-badge">Game Dev</span>
            <h3 class="journey-card-title">Game Environment Design</h3>
            <div class="journey-card-meta">
              <span class="journey-card-student">Tanay Pal</span>
              <span class="journey-card-sep">•</span>
              <span class="journey-card-company">Ubisoft</span>
            </div>
            <p class="journey-card-desc">Open-world game environment with procedural generation, PBR texturing, and real-time lighting in Unreal Engine 5.</p>
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="swiper-button-prev journey-prev"></div>
    <div class="swiper-button-next journey-next"></div>
    <div class="journey-pagination"></div> -->
  </div>
</section>

<!-- ===================== AI SECTION ===================== -->
<section class="ai-section">
  <!-- <canvas class="sakura-canvas" data-section="ai"></canvas> -->
  <!-- <canvas class="interactive-leaf-canvas"></canvas> -->
  <div class="section-header">
    <h2 class="section-title gold">THE FUTURE OF AI IN ANIMATION & DESIGN</h2>
    <p class="section-subtitle">MAAC Durgapur is included in the Schoolnet Advanced Technology framework to include AI creative courses. We are the premier for the future of design and we have a roster of the best trainers from Burdwan–</p>
  </div>
  
  <div class="ai-content-grid">
    <div class="ai-text-block">
      <p>MAAC Durgapur's approach to AI has opened up tremendous educational opportunities for learners in Durgapur and surrounding areas of West Bengal. Through state-of-the-art AI integrated curricula, students gain hands-on experience with industry tools and technologies.</p>
    </div>
    <div class="ai-features">
      <div class="ai-feature">
        <div class="ai-feature-icon">🤖</div>
        <h4>AI-Powered Tools</h4>
        <p>Learn Midjourney, DALL-E, Stable Diffusion</p>
      </div>
      <div class="ai-feature">
        <div class="ai-feature-icon">🎨</div>
        <h4>Creative AI</h4>
        <p>AI-driven animation and design tools</p>
      </div>
      <div class="ai-feature">
        <div class="ai-feature-icon">⚡</div>
        <h4>Future-Ready Skills</h4>
        <p>Industry-aligned AI curriculum</p>
      </div>
    </div>
  </div>
</section>

</div><!-- /placement-group -->

<!-- ===================== FOOTER ===================== -->
<footer id="contact" class="footer">
  <div class="footer-tree-left"></div>
  <div class="footer-tree-right"></div>
  <canvas class="sakura-canvas" data-section="footer"></canvas>
  
  <div class="footer-top">
    <div class="footer-about">
      <p>Join MAAC and AKSHA Durgapur to unlock your creative future. With state-of-the-art facilities, expert faculty, and strong industry connections, we prepare you for success in animation, design, gaming, and technology.</p>
    </div>
    
    <div class="footer-links-col">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="{{ route('blogs.index') }}">Blog</a></li>
        <li><a href="#">Courses</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Disclaimer</a></li>
        <li><a href="#">Student Enquiry</a></li>
        <li><a href="#">Placement</a></li>
        <li><a href="#">Contact us</a></li>
      </ul>
    </div>
    
    <div class="footer-social-col">
      <h4>Follow Us On</h4>
      <div class="social-icons">
        <a href="#" class="social-icon fb">f</a>
        <a href="#" class="social-icon yt">▶</a>
        <a href="#" class="social-icon ig">📸</a>
      </div>
    </div>
    
    <div class="footer-contact-col">
      <h4>Contact Us</h4>
      <p>📞 Phone: +91 - 9333111777</p>
      <p>📞 Phone: +91 - 9333504000</p>
      <p>✉ Email: vindhyasini2009@gmail.com</p>
      <p>✉ Email: durgapur@maacmail.com</p>
      <p class="footer-address">Address: Ambedkar Square, A/C 14, Ambedkar Union Bank, City Centre, Durgapur West Bengal 713216</p>
    </div>
  </div>
  
  <div class="footer-bottom">
    <p>Powered by</p>
    <div class="footer-logos">
      <a href="{{ route('aksha') }}"><img src="{{ asset('frontend/images/aksha_new_logo.png') }}" alt="AKSHA" class="footer-logo" loading="lazy"></a>
      <a href="{{ route('maac') }}"><img src="{{ asset('frontend/images/maac_new_logo.png') }}" alt="MAAC" class="footer-logo" loading="lazy"></a>
      <a href="{{ route('space_e_fic') }}"><img src="{{ asset('frontend/images/space-e-fic_new_logo.png') }}" alt="Space-E-Fic" class="footer-logo" loading="lazy"></a>
    </div>
    <p class="copyright">© 2026 MAAC Durgapur. All Rights Reserved.</p>
  </div>
</footer>

<!-- ===================== COUNSELLING MODAL ===================== -->
<div class="counselling-overlay" id="counsellingOverlay">
  <div class="counselling-modal" id="counsellingModal" role="dialog" aria-modal="true" aria-label="Free Career Counselling">
    
    <!-- Close Button -->
    <button class="counselling-close" id="counsellingClose" aria-label="Close modal">✕</button>

    <!-- Form Body -->
    <div id="counsellingFormBody">
      <div class="counselling-header">
        <div class="counselling-icon-wrap">
          <span class="counselling-icon">🎓</span>
        </div>
        <h3 class="counselling-title">Free Career Counselling</h3>
        <p class="counselling-subtitle">Get expert guidance from MAAC Durgapur professionals</p>
      </div>

      <form action="{{ route('career_counselling') }}" method="POST" id="comment_form" class="counselling-form">
        @csrf

        @if(count($globalModalFormFields) > 0)
          <input type="hidden" name="brand_id" value="{{ $brand->id }}">
          <input type="hidden" name="form_type" value="global_modal">
          @foreach($globalModalFormFields as $field)
            <div class="form-group {{ $field->type === 'checkbox' ? 'form-check-group' : '' }}">
              @php
                $icon = '👤';
                if($field->field_name == 'phone') $icon = '📞';
                elseif($field->field_name == 'email') $icon = '✉️';
                elseif($field->field_name == 'course_id') $icon = '🎓';
                elseif($field->field_name == 'location') $icon = '📍';
                elseif($field->field_name == 'message') $icon = '💬';
              @endphp
              <span class="field-icon">{{ $icon }}</span>
              
              @if($field->type === 'select')
                <select name="{{ $field->field_name }}" id="modal-{{ $field->field_name }}" class="field-select" {{ $field->is_required ? 'required' : '' }}>
                  <option value="" disabled selected hidden></option>
                  @if(!empty($field->options))
                    @foreach(json_decode($field->options, true) as $opt)
                      <option value="{{ trim($opt) }}">{{ trim($opt) }}</option>
                    @endforeach
                  @elseif($field->field_name === 'course_id' && !empty($courses))
                    @foreach($courses as $course)
                      <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                  @endif
                </select>
                <label class="field-label" for="modal-{{ $field->field_name }}">{{ $field->label }}</label>
              @elseif($field->type === 'textarea')
                <textarea name="{{ $field->field_name }}" id="modal-{{ $field->field_name }}" class="field-input" placeholder=" " {{ $field->is_required ? 'required' : '' }} rows="3"></textarea>
                <label class="field-label" for="modal-{{ $field->field_name }}">{{ $field->label }}</label>
              @elseif($field->type === 'checkbox')
                <div style="padding-left: 30px; padding-top: 10px;">
                  <input type="checkbox" name="{{ $field->field_name }}" id="modal-{{ $field->field_name }}" value="1" {{ $field->is_required ? 'required' : '' }}>
                  <label for="modal-{{ $field->field_name }}" style="color: #fff;">{{ $field->label }}</label>
                </div>
              @else
                <input type="{{ $field->type }}" name="{{ $field->field_name }}" id="modal-{{ $field->field_name }}" class="field-input" placeholder=" " autocomplete="{{ $field->field_name }}" {{ $field->is_required ? 'required' : '' }}>
                <label class="field-label" for="modal-{{ $field->field_name }}">{{ $field->label }}</label>
              @endif
              <span class="error-text {{ $field->field_name }}_error"></span>
            </div>
          @endforeach
        @else
          <div class="form-group">
            <span class="field-icon">👤</span>
            <input type="text" name="name" id="modal-name" class="field-input" placeholder=" " autocomplete="name" required>
            <label class="field-label" for="modal-name">Your Full Name</label>
            <span class="error-text name_error"></span>
          </div>

          <div class="form-group">
            <span class="field-icon">📞</span>
            <input type="tel" name="phone" id="modal-phone" class="field-input" placeholder=" " autocomplete="tel" required>
            <label class="field-label" for="modal-phone">Phone Number</label>
            <span class="error-text phone_error"></span>
          </div>

          <div class="form-group">
            <span class="field-icon">✉️</span>
            <input type="email" name="email" id="modal-email" class="field-input" placeholder=" " autocomplete="email" required>
            <label class="field-label" for="modal-email">Email Address</label>
            <span class="error-text email_error"></span>
          </div>

          <div class="form-group">
            <span class="field-icon">🎓</span>
            <select name="course_id" id="modal-course" class="field-select" required>
              <option value="" disabled selected hidden></option>
              @if(!empty($courses))
                @foreach ($courses as $course)
                  <option value="{{ $course->id }}">{{ $course->name }}</option>
                @endforeach
              @endif
            </select>
            <label class="field-label" for="modal-course">Select Course</label>
            <span class="error-text course_id_error"></span>
          </div>
        @endif

        <button type="submit" class="counselling-submit" id="counsellingSubmit">
          <span class="spinner"></span>
          <span class="btn-text">Book Free Counselling</span>
          <span class="btn-arrow">→</span>
          <div class="btn-shine"></div>
        </button>
      </form>
    </div>

    <!-- Success State -->
    <div class="counselling-success" id="counsellingSuccess">
      <div class="success-icon-wrap">
        <svg class="success-checkmark-svg" viewBox="0 0 100 100" width="100" height="100">
          <circle class="success-circle" cx="50" cy="50" r="45" fill="none" stroke="url(#goldGradSuccess)" stroke-width="3"/>
          <path class="success-check" d="M30 52 L44 66 L70 36" fill="none" stroke="url(#goldGradSuccess)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
          <defs>
            <linearGradient id="goldGradSuccess" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" style="stop-color:#ffd700"/>
              <stop offset="100%" style="stop-color:#ff6a00"/>
            </linearGradient>
          </defs>
        </svg>
      </div>
      <h3 class="success-title">✅ Thank You</h3>
      <p class="success-message">Our counsellor will contact you shortly.</p>
      <button class="success-close-btn" type="button">Return to Website</button>
    </div>

  </div>
</div>



<!-- chatbot -->
<button id="aksha-launcher" aria-label="Open chat" onclick="toggleChat()">
  <svg class="chat-icon" viewBox="0 0 24 24" width="28" height="28"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z" fill="white"/></svg>
  <svg class="close-icon" viewBox="0 0 24 24" width="28" height="28"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" fill="white"/></svg>
</button>
<div id="aksha-window">

  <!-- Header -->
  <div class="chat-header">
    <div class="header-avatar">🤖</div>
    <div class="header-info">
      <div class="header-name">AKSHA AI Assistant</div>
      <div class="header-subtitle">Powered by MAAC Durgapur</div>
    </div>
    <button class="chat-close-btn" onclick="closeChat()" aria-label="Close chat">✕</button>
  </div>

  <!-- Messages -->
  <div id="chat-messages"></div>

  <!-- Input -->
  <div class="chat-input-bar">
    <textarea id="chat-input" placeholder="Ask about courses, fees, careers…" rows="1"
      onkeydown="handleKey(event)" oninput="autoResize(this)"></textarea>
    <button id="send-btn" onclick="sendMessage()" title="Send">
      <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
    </button>
  </div>

  <div class="chat-footer">Powered by <span>AKSHA AI</span> · MAAC Durgapur</div>
</div>
<!-- chatbot end -->

<!-- Scripts -->
<!-- ===================== SITE LOADER JS ===================== -->
<script>
(function() {
  'use strict';
  document.documentElement.style.overflow = 'hidden';
  document.body.style.overflow = 'hidden';
  document.body.classList.add('loading');

  var loader = document.getElementById('siteLoader');
  if (!loader) return;

  window.addEventListener('load', function() {
    setTimeout(function() {
      if (typeof gsap !== 'undefined') {
        var tl = gsap.timeline({
          onComplete: function() {
            if (loader.parentNode) loader.parentNode.removeChild(loader);
            document.body.classList.remove('loading');
            document.documentElement.style.overflow = '';
            document.body.style.overflow = '';
          }
        });
        tl.to(loader, { opacity: 0, duration: 0.6, ease: 'power2.inOut' });
      } else {
        loader.style.display = 'none';
        document.body.classList.remove('loading');
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
      }
    }, 200);
  });
})();
</script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/10.3.1/swiper-bundle.min.js"></script>
<script defer src="{{ asset('frontend/js/sakura.js') }}"></script>
<script defer src="{{ asset('frontend/js/animations.js') }}"></script>
<script defer src="{{ asset('frontend/js/main.js') }}"></script>
<script defer src="{{ asset('frontend/js/counselling-modal.js') }}"></script>
<script defer src="{{ asset('frontend/js/chatbot.js') }}"></script>
</body>
</html>
