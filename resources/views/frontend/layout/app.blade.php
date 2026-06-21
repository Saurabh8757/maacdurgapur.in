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
<meta name="twitter:title" content="MAAC Durgapur – West Bengal's #1 Animation, VFX & AI Institute">
<meta name="twitter:description" content="Learn Animation, VFX, Gaming, Graphic Design & AI at MAAC Durgapur. Industry-focused training with 100% placement support.">
<meta name="twitter:image" content="{{ asset('frontend/images/pg-01.webp') }}">
<!-- Favicon -->
<link rel="icon" type="image/png" href="{{ asset('frontend/images/favicon.png') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/chatbot.css') }}" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="{{ asset('frontend/css/chatbot.css') }}"></noscript>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Rajdhani:wght@400;500;600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/10.3.1/swiper-bundle.min.css">
<!-- Counselling Modal CSS -->
<link rel="stylesheet" href="{{ asset('frontend/css/counselling-modal.css') }}" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="{{ asset('frontend/css/counselling-modal.css') }}"></noscript>
@if(request()->routeIs('home'))
<!-- Preload hero image for LCP -->
<link rel="preload" as="image" href="{{ asset('frontend/images/pg-01.webp') }}" fetchpriority="high">
@endif
    @yield('custom_css')
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
        <img src="{{ asset('frontend/images/maac/icons/transparent-logo.png') }}" alt="MAAC Robot Mascot" style="max-width: 80px; height: auto;">
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
<nav id="navbar" class="{{ request()->routeIs('maac', 'aksha', 'space_e_fic') ? 'brand-navbar' : '' }}">
  <!-- Mascot Overlap Decor -->
  <a href="{{ url('/') }}" class="nav-brand {{ request()->routeIs('maac', 'aksha', 'space_e_fic') ? 'nav-brand-text-logo' : '' }}">
    @if(request()->routeIs('maac'))
        <img src="{{ asset('frontend/images/maac_new_logo.png') }}" alt="MAAC Logo" class="nav-brand-icon brand-text-logo" loading="lazy">
    @elseif(request()->routeIs('aksha'))
        <img src="{{ asset('frontend/images/aksha_new_logo.png') }}" alt="AKSHA Logo" class="nav-brand-icon brand-text-logo" loading="lazy">
    @elseif(request()->routeIs('space_e_fic'))
        <img src="{{ asset('frontend/images/space-e-fic_new_logo.png') }}" alt="Space-E-Fic Logo" class="nav-brand-icon brand-text-logo" loading="lazy">
    @else
        <img src="{{ asset('frontend/images/maac/icons/transparent-logo.png') }}" alt="MAAC Durgapur Official Brand Mascot" class="nav-brand-icon" loading="lazy">
    @endif
  </a>

  <!-- Desktop Links -->
  <ul class="nav-links">
    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
    <li class="has-dropdown">
      <a href="#courses" class="">Courses <span class="arrow">▼</span></a>
      <ul class="dropdown">
        <li><a href="#"><span class="dot-icon"></span>Animation &amp; VFX</a></li>
        <li><a href="#"><span class="dot-icon"></span>UI/UX Design</a></li>
        <li><a href="#"><span class="dot-icon"></span>Game Development</a></li>
        <li><a href="#"><span class="dot-icon"></span>AI &amp; Technology</a></li>
      </ul>
    </li>
    <li><a href="{{ route('showcase') }}" class="{{ request()->routeIs('showcase') ? 'active' : '' }}">Students Work</a></li>
    <li><a href="{{ route('blog') }}" class="{{ request()->routeIs('blog*') ? 'active' : '' }}">Blog</a></li>
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
    <a href="{{ route('home') }}" class="mobile-link {{ request()->routeIs('home') ? 'active' : '' }}" data-close>Home</a>

    <a href="#courses" class="mobile-link" id="coursesToggle">
      Courses
      <span class="arrow" style="font-size:0.7rem">▼</span>
    </a>
    <ul class="mobile-sub" id="coursesSub">
      <li><a href="#" data-close><span class="dot-icon"></span>Animation &amp; VFX</a></li>
      <li><a href="#" data-close><span class="dot-icon"></span>UI/UX Design</a></li>
      <li><a href="#" data-close><span class="dot-icon"></span>Game Development</a></li>
      <li><a href="#" data-close><span class="dot-icon"></span>AI &amp; Technology</a></li>
    </ul>

    <a href="{{ route('showcase') }}" class="mobile-link {{ request()->routeIs('showcase') ? 'active' : '' }}" data-close>Students Work</a>
    <a href="{{ route('blog') }}" class="mobile-link {{ request()->routeIs('blog*') ? 'active' : '' }}" data-close>Blog</a>
    <a href="{{ route('faq') }}" class="mobile-link {{ request()->routeIs('faq*') ? 'active' : '' }}" data-close>FAQ</a>
    <a href="#contact" class="mobile-link" data-close>Contact Us</a>

    <div class="mobile-cta-wrap">
      <p class="mobile-divider">Ready to start?</p>
      <a href="#counselling" class="mobile-cta" data-close>Book Free Counselling</a>
    </div>
  </div>
</div>


@yield('content')
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
        <li><a href="{{ route('faq') }}">FAQ</a></li>
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
    if (document.querySelector('.hero-section')) {
      tl.from('.hero-section', {
        opacity: 0,
        y: 40,
        duration: 0.8,
        ease: 'power3.out'
      }, '-=0.2');
    }
  }

})();
</script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollToPlugin.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/10.3.1/swiper-bundle.min.js"></script>
<script defer src="{{ asset('frontend/js/sakura.js') }}"></script>
<script defer src="{{ asset('frontend/js/animations.js') }}"></script>
<script defer src="{{ asset('frontend/js/main.js') }}"></script>
<script defer src="{{ asset('frontend/js/counselling-modal.js') }}"></script>
<script defer src="{{ asset('frontend/js/chatbot.js') }}"></script>
    @yield('custom_js')
</body>
</html>
