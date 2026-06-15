<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>MAAC Durgapur – West Bengal's #1 Animation, VFX & AI Creative Institute</title>
<meta name="description" content="MAAC Durgapur is West Bengal's leading Animation, VFX, Gaming, Graphic Design & AI Creative Institute. Industry-focused training, expert mentorship, modern studios and 100% placement support near City Centre, Durgapur.">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ url('/') }}">
<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:title" content="MAAC Durgapur – West Bengal's #1 Animation, VFX & AI Creative Institute">
<meta property="og:description" content="Learn Animation, VFX, Gaming, Graphic Design & AI at MAAC Durgapur. Industry-focused training with 100% placement support.">
<meta property="og:image" content="{{ asset('frontend/images/pg-01.webp') }}">
<meta property="og:url" content="{{ url('/') }}">
<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="MAAC Durgapur – West Bengal's #1 Animation, VFX & AI Institute">
<meta name="twitter:description" content="Learn Animation, VFX, Gaming, Graphic Design & AI at MAAC Durgapur. Industry-focused training with 100% placement support.">
<meta name="twitter:image" content="{{ asset('frontend/images/pg-01.webp') }}">
<!-- Favicon -->
<link rel="icon" type="image/png" href="{{ asset('frontend/images/favicon.png') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/chatbot.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Rajdhani:wght@400;500;600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/10.3.1/swiper-bundle.min.css">
<!-- Counselling Modal CSS -->
<link rel="stylesheet" href="{{ asset('frontend/css/counselling-modal.css') }}">
<!-- Preload hero image for LCP -->
<link rel="preload" as="image" href="{{ asset('frontend/images/pg-01.webp') }}" fetchpriority="high">
</head>
<body class="loading">

<!-- ===================== SITE LOADER ===================== -->
<div id="siteLoader">
  <div class="loader-bg"></div>
  <div class="loader-particles" id="loaderParticles"></div>
  <div class="loader-content">
    <div class="loader-ring-wrap">
      <div class="loader-ring"></div>
      <div class="loader-ring loader-ring-inner"></div>
      <div class="loader-glow"></div>

      <div class="loader-logo">
        <div class="loader-robot">
          <div class="loader-robot-head"></div>
          <div class="loader-robot-body"></div>
          <div class="loader-robot-arm left"></div>
          <div class="loader-robot-arm right"></div>
        </div>
      </div>
    </div>
    <h2 class="loader-text">Loading Creative Universe<span class="loader-dots"></span></h2>
    <div class="loader-progress-wrap">
      <div class="loader-progress-bar">
        <div class="loader-progress-fill" id="loaderProgressFill"></div>
        <div class="loader-progress-glow"></div>
      </div>
      <span class="loader-percent" id="loaderPercent">0%</span>
    </div>
  </div>
</div>



<!-- Floating Action Buttons -->
<a href="https://wa.me/919333504000" class="fab fab-whatsapp" target="_blank">
  <img src="{{ asset('frontend/images/whatsapp__1_.png') }}" alt="WhatsApp" loading="lazy">
</a>


<!-- ===================== NAVBAR ===================== -->
<nav id="navbar">
  <!-- Robot Logo -->
  <div class="nav-robot" aria-label="Logo">
    <div class="robot-figure">
      <div class="robot-head"></div>
      <div class="robot-body"></div>
      <div class="robot-arm left"></div>
      <div class="robot-arm right"></div>
    </div>
  </div>

  <!-- Desktop Links -->
  <ul class="nav-links">
    <li><a href="#home" class="active">Home</a></li>
    <li class="has-dropdown">
      <a href="#courses">Courses <span class="arrow">▼</span></a>
      <ul class="dropdown">
        <li><a href="#"><span class="dot-icon"></span>Animation &amp; VFX</a></li>
        <li><a href="#"><span class="dot-icon"></span>UI/UX Design</a></li>
        <li><a href="#"><span class="dot-icon"></span>Game Development</a></li>
        <li><a href="#"><span class="dot-icon"></span>AI &amp; Technology</a></li>
      </ul>
    </li>
    <li><a href="{{ route('showcase') }}">Students Work</a></li>
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
    <a href="#home" class="mobile-link active" data-close>Home</a>

    <a href="#" class="mobile-link" id="coursesToggle">
      Courses
      <span class="arrow" style="font-size:0.7rem">▼</span>
    </a>
    <ul class="mobile-sub" id="coursesSub">
      <li><a href="#" data-close><span class="dot-icon"></span>Animation &amp; VFX</a></li>
      <li><a href="#" data-close><span class="dot-icon"></span>UI/UX Design</a></li>
      <li><a href="#" data-close><span class="dot-icon"></span>Game Development</a></li>
      <li><a href="#" data-close><span class="dot-icon"></span>AI &amp; Technology</a></li>
    </ul>

    <a href="{{ route('showcase') }}" class="mobile-link" data-close>Students Work</a>
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

<!-- ===================== MAAC SECTION ===================== -->
<section id="maac" class="institute-section maac-section">
  <div class="section-bg">
    <img src="{{ asset('frontend/images/pg-02.webp') }}" alt="MAAC Background" class="section-bg-img parallax-bg" data-speed="0.2" loading="lazy">
  </div>
  <canvas class="sakura-canvas" data-section="maac"></canvas>
  
  <div class="institute-card maac-card">
    <div class="card-logo">
      <a href="{{ route('maac') }}">
        <img src="{{ asset('frontend/images/maac_logo.png') }}" alt="MAAC Logo" loading="lazy">
      </a>
    </div>
    <div class="card-content">
      <p>MAAC (Maya Academy of Advanced Creativity) Durgapur is the region's most trusted animation and multimedia training institute, strategically located near City Center Durgapur. Students from Kolkata, Burdwan, Bolpur, Bankura, Asansol, Raniganj and Purulia choose MAAC for career-ready skills in animation, VFX, gaming, graphic design, and the latest AI creative tools.</p>
    </div>
    <button class="btn-register orange open-modal">
    Register Now →
</button>
  </div>
</section>

<!-- ===================== AKSHA SECTION ===================== -->
<section id="aksha" class="institute-section aksha-section">
  <div class="section-bg">
    <img src="{{ asset('frontend/images/pg-03.webp') }}" alt="AKSHA Background" class="section-bg-img parallax-bg" data-speed="0.2" loading="lazy">
  </div>
  <canvas class="sakura-canvas" data-section="aksha"></canvas>
  
  <div class="institute-card aksha-card">
    <div class="card-logo">
      <a href="{{ route('aksha') }}">
        <img src="{{ asset('frontend/images/Aksha_logo.png') }}" alt="AKSHA Logo" loading="lazy">
      </a>
    </div>
    <div class="card-content">
      <p>AKSHA is a modern creative design institute focused on visual storytelling, UI/UX, graphic design, branding, digital communication, and AI-creative tools. With hands-on training, expert mentorship & industry-focused training, AKSHA helps students build professional skills, creative portfolios, and successful careers in the digital design project. West Bengal's No.1 UI/UX, Graphic Design and Digitally-focused learning institute, strategically located near City Centre, Durgapur.</p>
    </div>
    <button class="btn-register orange open-modal">
    Register Now →
</button>
  </div>
</section>

<!-- ===================== SPACE-E-FIC SECTION ===================== -->
<section id="spacefic" class="institute-section spacefic-section">
  <div class="section-bg">
    <img src="{{ asset('frontend/images/pg-04.webp') }}" alt="Space-E-Fic Background" class="section-bg-img parallax-bg" data-speed="0.2" loading="lazy">
  </div>
  <canvas class="sakura-canvas" data-section="spacefic"></canvas>
  
  <div class="institute-card spacefic-card">
    <div class="card-logo spacefic-logo-wrap">
      <a href="{{ route('space_e_fic') }}">
        <img src="{{ asset('frontend/images/spacific_logo.png') }}" alt="Space-E-Fic Logo" class="spacefic-logo" loading="lazy">
      </a>
    </div>
    <div class="card-content">
      <p>Space-E-Fic is a future-focused coding technology institute specializing in AI, Robotics, Game Development, Coding, AR/VR, and immersive digital innovation. Designed for the next generation of creators and tech innovators, Space-E-Fic creativity with futuristic technology through hands-on learning and real-world training. Best No.1 institute, strategically located near City Centre, Durgapur.</p>
    </div>
    <button class="btn-register orange open-modal">
    Register Now →
</button>
  </div>
</section>

<!-- ===================== COURSES SECTION ===================== -->
<section id="courses" class="courses-section">
  <div class="section-bg dark-bg">
    <img src="{{ asset('frontend/images/pg-05.webp') }}" alt="Courses Background" class="section-bg-img parallax-bg" data-speed="0.15" loading="lazy">
  </div>
  <canvas class="sakura-canvas" data-section="courses"></canvas>
  
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
                <img src="{{ asset($course->image) }}" alt="{{ $course->name }}" loading="lazy">
                <div class="course-overlay"></div>
              </div>
              <div class="course-info">
                <h3>{{ $course->name }}</h3>
                <p class="course-desc">{{ Str::limit($course->desc, 120) }}</p>
                <a href="#" class="course-link">Learn More →</a>
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
<div class="placement-group" style="background-image: url('{{ asset('frontend/images/pg-06.webp') }}')">

<!-- ===================== RECRUITERS SECTION ===================== -->
<section class="recruiters-section">
  <canvas class="sakura-canvas" data-section="recruiters"></canvas>
  <canvas class="interactive-leaf-canvas"></canvas>
  
  <div class="section-header">
    <h2 class="section-title gold">OUR TOP RECRUITERS</h2>
    <p class="recruiter-desc">MAAC Durgapur students consistently secure placements across leading animation studios, gaming companies, and digital agencies across India and internationally.</p>
  </div>
  
  <div class="logo-ticker-wrapper">
    <div class="logo-ticker" id="logo-ticker">
      <div class="ticker-item"><div class="recruiter-logo netflix">NETFLIX</div></div>
      <div class="ticker-item"><div class="recruiter-logo rockstar">R★<br><small>GAMES</small></div></div>
      <div class="ticker-item"><div class="recruiter-logo tata-elxsi">TATA<br>ELXSI</div></div>
      <div class="ticker-item"><div class="recruiter-logo pogo">pogo</div></div>
      <div class="ticker-item"><div class="recruiter-logo dneg">DNEG</div></div>
      <div class="ticker-item"><div class="recruiter-logo prime">prime<br>video</div></div>
      <div class="ticker-item"><div class="recruiter-logo ea">EA</div></div>
      <div class="ticker-item"><div class="recruiter-logo ubisoft">UBISOFT</div></div>
      <!-- Clones for infinite loop -->
      <div class="ticker-item"><div class="recruiter-logo netflix">NETFLIX</div></div>
      <div class="ticker-item"><div class="recruiter-logo rockstar">R★<br><small>GAMES</small></div></div>
      <div class="ticker-item"><div class="recruiter-logo tata-elxsi">TATA<br>ELXSI</div></div>
      <div class="ticker-item"><div class="recruiter-logo pogo">pogo</div></div>
    </div>
  </div>
  <!-- <div class="swiper-button-prev recruiter-prev"></div>
  <div class="swiper-button-next recruiter-next"></div>-->
</section>

<!-- ===================== PLACEMENT SECTION ===================== -->
<section class="placement-section">
  <canvas class="sakura-canvas" data-section="placement"></canvas>
  <div class="section-header">
    <span class="placement-accent">✦ Premium Placements</span>
    <h2 class="section-title gold">OUR PLACEMENT PROMISE</h2>
    <p class="section-subtitle">When students from Durgapur, Kolkata, Asansol, Burdwan, Raniganj and Purulia search for the best animation studios, agencies, and production houses across India and internationally, we help with up to</p>
  </div>
  
  <div class="swiper placement-swiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide">
        <div class="placement-card">
          <div class="placement-card-img-wrap">
            <div class="placement-card-ribbon"></div>
            <img src="{{ asset('upload/SERVICE/photo-1512295767273-ac109ac3acfa.jpeg') }}" alt="Priya S." class="placement-card-img" loading="lazy">
          </div>
          <div class="placement-card-body">
            <h4 class="student-name">Priya S.</h4>
            <p class="company-name">Netflix Studios</p>
            <p class="job-role">VFX Artist</p>
            <div class="salary-wrap">
              <span class="salary-amount">₹3,79,000</span>
              <span class="salary-label">PER ANNUM</span>
            </div>
          </div>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="placement-card">
          <div class="placement-card-img-wrap">
            <div class="placement-card-ribbon"></div>
            <img src="{{ asset('upload/SERVICE/photo-1540242908484-50aa09aea5a7.jpeg') }}" alt="Rahul M." class="placement-card-img" loading="lazy">
          </div>
          <div class="placement-card-body">
            <h4 class="student-name">Rahul M.</h4>
            <p class="company-name">Prime Focus</p>
            <p class="job-role">Motion Designer</p>
            <div class="salary-wrap">
              <span class="salary-amount">₹1,70,000</span>
              <span class="salary-label">PER ANNUM</span>
            </div>
          </div>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="placement-card">
          <div class="placement-card-img-wrap">
            <div class="placement-card-ribbon"></div>
            <img src="{{ asset('upload/SERVICE/photo-1572044162444-ad60f128bdea.jpeg') }}" alt="Anjali K." class="placement-card-img" loading="lazy">
          </div>
          <div class="placement-card-body">
            <h4 class="student-name">Anjali K.</h4>
            <p class="company-name">Adobe</p>
            <p class="job-role">UI/UX Designer</p>
            <div class="salary-wrap">
              <span class="salary-amount">₹3,60,000</span>
              <span class="salary-label">PER ANNUM</span>
            </div>
          </div>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="placement-card">
          <div class="placement-card-img-wrap">
            <div class="placement-card-ribbon"></div>
            <img src="{{ asset('upload/SERVICE/photo-1611241893603-3c359704e0ee.jpeg') }}" alt="Arjun D." class="placement-card-img" loading="lazy">
          </div>
          <div class="placement-card-body">
            <h4 class="student-name">Arjun D.</h4>
            <p class="company-name">DNEG</p>
            <p class="job-role">3D Animator</p>
            <div class="salary-wrap">
              <span class="salary-amount">₹4,40,000</span>
              <span class="salary-label">PER ANNUM</span>
            </div>
          </div>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="placement-card">
          <div class="placement-card-img-wrap">
            <div class="placement-card-ribbon"></div>
            <img src="{{ asset('upload/SERVICE/photo-1613909207039-6b173b755cc1.jpeg') }}" alt="Sneha R." class="placement-card-img" loading="lazy">
          </div>
          <div class="placement-card-body">
            <h4 class="student-name">Sneha R.</h4>
            <p class="company-name">Tata Elxsi</p>
            <p class="job-role">Graphic Designer</p>
            <div class="salary-wrap">
              <span class="salary-amount">₹2,40,000</span>
              <span class="salary-label">PER ANNUM</span>
            </div>
          </div>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="placement-card">
          <div class="placement-card-img-wrap">
            <div class="placement-card-ribbon"></div>
            <img src="{{ asset('upload/SERVICE/animation2.jpg') }}" alt="Vikram P." class="placement-card-img" loading="lazy">
          </div>
          <div class="placement-card-body">
            <h4 class="student-name">Vikram P.</h4>
            <p class="company-name">Ubisoft</p>
            <p class="job-role">Game Developer</p>
            <div class="salary-wrap">
              <span class="salary-amount">₹5,20,000</span>
              <span class="salary-label">PER ANNUM</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="swiper-button-prev placement-prev"></div>
    <div class="swiper-button-next placement-next"></div>
  </div>
</section>

<!-- ===================== JOURNEY SECTION ===================== -->
<section class="journey-section">
  <canvas class="sakura-canvas" data-section="journey"></canvas>
  <canvas class="interactive-leaf-canvas"></canvas>
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
            <img src="{{ asset('frontend/images/courses_outline.webp') }}" alt="UI/UX Redesign — EduApp" class="journey-card-img" loading="lazy">
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
    <div class="swiper-button-prev journey-prev"></div>
    <div class="swiper-button-next journey-next"></div>
    <div class="journey-pagination"></div>
  </div>
</section>

<!-- ===================== AI SECTION ===================== -->
<section class="ai-section">
  <canvas class="sakura-canvas" data-section="ai"></canvas>
  <canvas class="interactive-leaf-canvas"></canvas>
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
        <li><a href="{{ route('blog') }}">Blog</a></li>
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
      <p>📞 Phone: +91 - 9333504000</p>
      <p>📞 Phone: +91 - 9333504000</p>
      <p>✉ Email: durgapur@maacanimation.com</p>
      <p class="footer-address">Address: Ambedkar Square, A/C 14, Ambedkar Union Bank, City Centre, Durgapur West Bengal 713216</p>
    </div>
  </div>
  
  <div class="footer-bottom">
    <p>Powered by</p>
    <div class="footer-logos">
      <img src="{{ asset('frontend/images/Aksha_logo.png') }}" alt="AKSHA" class="footer-logo" loading="lazy">
      <img src="{{ asset('frontend/images/maac_logo.png') }}" alt="MAAC" class="footer-logo" loading="lazy">
      <img src="{{ asset('frontend/images/spacific_logo.png') }}" alt="Space-E-Fic" class="footer-logo" loading="lazy">
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

  /* ── Lock scroll immediately ────────────────────────── */
  document.documentElement.style.overflow = 'hidden';
  document.body.style.overflow = 'hidden';
  document.body.classList.add('loading');

  /* ── DOM refs ───────────────────────────────────────── */
  var loader = document.getElementById('siteLoader');
  var progressFill = document.getElementById('loaderProgressFill');
  var percentText = document.getElementById('loaderPercent');
  var particlesContainer = document.getElementById('loaderParticles');
  var petalTweens = [];

  if (!loader) return;

  /* ── Spawn floating sakura petals ───────────────────── */
  var petalCount = window.innerWidth < 768 ? 12 : 24;
  for (var i = 0; i < petalCount; i++) {
    var petal = document.createElement('div');
    petal.className = 'loader-petal';
    petal.style.left = (Math.random() * 100) + '%';
    petal.style.top = (-5 + Math.random() * 20) + '%';
    var size = 5 + Math.random() * 8;
    petal.style.width = size + 'px';
    petal.style.height = (size * 0.7) + 'px';
    petal.style.opacity = '0';
    particlesContainer.appendChild(petal);
  }

  /* ── Animate petals once GSAP is available ──────────── */
  function animatePetals() {
    if (typeof gsap === 'undefined') { setTimeout(animatePetals, 50); return; }
    var petals = particlesContainer.querySelectorAll('.loader-petal');
    petals.forEach(function(p) {
      var tw = gsap.to(p, {
        y: window.innerHeight + 60,
        x: (Math.random() - 0.5) * 120,
        rotation: Math.random() * 360,
        opacity: 0.5 + Math.random() * 0.4,
        duration: 3 + Math.random() * 4,
        repeat: -1,
        delay: Math.random() * 3,
        ease: 'none',
        modifiers: {
          y: function(y) { return (parseFloat(y) % (window.innerHeight + 80)) + 'px'; }
        }
      });
      petalTweens.push(tw);
    });
  }
  animatePetals();

  /* ── Progress tracking ──────────────────────────────── */
  var currentProgress = 0;
  var targetProgress = 0;
  var loadComplete = false;

  function updateProgress() {
    if (currentProgress < targetProgress) {
      currentProgress += (targetProgress - currentProgress) * 0.08;
      if (targetProgress - currentProgress < 0.5) currentProgress = targetProgress;
    }
    var rounded = Math.round(currentProgress);
    if (progressFill) progressFill.style.width = rounded + '%';
    if (percentText) percentText.textContent = rounded + '%';
    if (!loadComplete || currentProgress < 99) {
      requestAnimationFrame(updateProgress);
    }
  }
  requestAnimationFrame(updateProgress);

  /* ── Track image loading ────────────────────────────── */
  var images = document.querySelectorAll('img');
  var totalImages = images.length;
  var loadedImages = 0;

  if (totalImages === 0) {
    targetProgress = 50;
  } else {
    images.forEach(function(img) {
      if (img.complete) {
        loadedImages++;
      } else {
        img.addEventListener('load', function() {
          loadedImages++;
          targetProgress = Math.max(targetProgress, Math.min(50, (loadedImages / totalImages) * 50));
        });
        img.addEventListener('error', function() {
          loadedImages++;
          targetProgress = Math.max(targetProgress, Math.min(50, (loadedImages / totalImages) * 50));
        });
      }
    });
    targetProgress = Math.min(50, (loadedImages / totalImages) * 50);
  }

  /* ── Track font loading ─────────────────────────────── */
  if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(function() {
      targetProgress = Math.max(targetProgress, 75);
    });
  } else {
    targetProgress = Math.max(targetProgress, 75);
  }

  /* ── window.load — the real trigger ─────────────────── */
  window.addEventListener('load', function() {
    targetProgress = 100;
    loadComplete = true;

    /* Wait for progress animation to reach ~100, then dismiss */
    var dismissCheck = setInterval(function() {
      if (currentProgress >= 99.5) {
        clearInterval(dismissCheck);
        dismissLoader();
      }
    }, 50);

    /* Safety: dismiss after 4s max even if progress stalls */
    setTimeout(function() {
      if (loader && loader.parentNode) dismissLoader();
    }, 4000);
  });

  /* ── Dismiss loader ─────────────────────────────────── */
  var dismissed = false;
  function dismissLoader() {
    if (dismissed) return;
    dismissed = true;

    /* Kill petal tweens to prevent memory leak */
    petalTweens.forEach(function(tw) { tw.kill(); });
    petalTweens = [];

    if (typeof gsap === 'undefined') {
      /* Fallback if GSAP somehow not loaded */
      loader.style.display = 'none';
      document.body.classList.remove('loading');
      document.documentElement.style.overflow = '';
      document.body.style.overflow = '';
      return;
    }

    var tl = gsap.timeline({
      onComplete: function() {
        if (loader.parentNode) loader.parentNode.removeChild(loader);
        document.body.classList.remove('loading');
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
      }
    });

    /* 1. Fade out loader content */
    tl.to('.loader-content', {
      opacity: 0,
      y: -30,
      duration: 0.6,
      ease: 'power2.in'
    });

    /* 2. Scale + fade the entire loader */
    tl.to(loader, {
      opacity: 0,
      scale: 1.05,
      duration: 0.5,
      ease: 'power2.in'
    }, '-=0.3');

    /* 3. Reveal hero section with a subtle upward slide */
    tl.from('.hero-section', {
      opacity: 0,
      y: 40,
      duration: 0.8,
      ease: 'power3.out'
    }, '-=0.2');
  }

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
