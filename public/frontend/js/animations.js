/* ============================================================
   ANIMATIONS.JS — GSAP + ScrollTrigger animations [OPTIMIZED]
   ============================================================ */

(function() {
  'use strict';

  // Register GSAP plugins
  if (typeof ScrollTrigger !== 'undefined') gsap.registerPlugin(ScrollTrigger);
  if (typeof ScrollToPlugin !== 'undefined') gsap.registerPlugin(ScrollToPlugin);

  /* ─── Navbar entrance ──────────────────────────────────── */
  gsap.from('#navbar', {
    y: -80,
    opacity: 0,
    duration: 1,
    ease: 'power3.out',
    delay: 0.2,
  });

  // Navbar shrink on scroll — use passive scroll listener
  let ticking = false;
  const navbar = document.getElementById('navbar');
  if (navbar) {
    window.addEventListener('scroll', function () {
      if (!ticking) {
        requestAnimationFrame(function () {
          if (window.scrollY > 60) {
            navbar.classList.add('scrolled');
          } else if (window.scrollY <= 10) {
            navbar.classList.remove('scrolled');
          }
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });
  }

  /* ─── Hero section ──────────────────────────────────────── */
  if (document.querySelector('.hero-section')) {
    const heroTl = gsap.timeline({ delay: 0.5 });
    heroTl
      .from('.hero-badge', { x: 80, opacity: 0, duration: 0.7, ease: 'power2.out' })
      .from('.hero-heading .line1', { y: 40, opacity: 0, duration: 0.7, ease: 'power3.out' }, '-=0.3')
      .from('.hero-heading .line2', { y: 40, opacity: 0, duration: 0.7, ease: 'power3.out' }, '-=0.5')
      .from('.hero-heading .line3', { y: 40, opacity: 0, duration: 0.8, ease: 'power3.out' }, '-=0.5')
      .from('.hero-subtext', { y: 20, opacity: 0, duration: 0.6, ease: 'power2.out' }, '-=0.3')
      .from('.hero-sub2', { y: 20, opacity: 0, duration: 0.6, ease: 'power2.out' }, '-=0.4')
      .from('.hero-buttons', { y: 20, opacity: 0, duration: 0.6, ease: 'power2.out' }, '-=0.4');
  }

  /* ─── Parallax backgrounds — passive ScrollTrigger ────── */
  gsap.utils.toArray('.parallax-bg').forEach(function (img) {
    var speed = parseFloat(img.dataset.speed) || 0.2;
    gsap.to(img, {
      yPercent: speed * 30,
      ease: 'none',
      scrollTrigger: {
        trigger: img.closest('section') || img.parentElement,
        start: 'top bottom',
        scrub: 0.5, // Reduced scrub to fix lag
      },
    });
  });

  /* ─── Mouse parallax for hero (throttled) ──────────────── */
  var heroSection = document.querySelector('.hero-section');
  if (heroSection) {
    var heroBgImg = heroSection.querySelector('.hero-bg-img');
    var heroContent = heroSection.querySelector('.hero-content');
    var mouseRAF = false;

    heroSection.addEventListener('mousemove', function (e) {
      if (!mouseRAF) {
        requestAnimationFrame(function () {
          var mx = (e.clientX / window.innerWidth - 0.5) * 20;
          var my = (e.clientY / window.innerHeight - 0.5) * 10;
          if (heroBgImg) {
            gsap.to(heroBgImg, { x: mx * 0.5, y: my * 0.3, duration: 1.2, ease: 'power1.out' });
          }
          if (heroContent) {
            gsap.to(heroContent, { x: -mx * 0.15, y: -my * 0.1, duration: 1.2, ease: 'power1.out' });
          }
          mouseRAF = false;
        });
        mouseRAF = true;
      }
    }, { passive: true });
  }

  /* ─── ScrollTrigger-based reveals (using GSAP's built-in) ── */
  gsap.utils.toArray('.institute-card').forEach(function (card) {
    var fromRight = card.classList.contains('maac-card') || card.classList.contains('spacefic-card');
    gsap.from(card, {
      x: fromRight ? 80 : -80,
      opacity: 0,
      duration: 1,
      ease: 'power3.out',
      scrollTrigger: { trigger: card, start: 'top 85%', toggleActions: 'play none none none' },
    });
  });

  gsap.utils.toArray('.section-title').forEach(function (title) {
    gsap.from(title, {
      y: 30, opacity: 0, duration: 0.8, ease: 'power2.out',
      scrollTrigger: { trigger: title, start: 'top 88%', toggleActions: 'play none none none' },
    });
  });

  // Stats count-up
  var statNums = document.querySelectorAll('.stat-num');
  statNums.forEach(function (el) {
    var target = parseInt(el.dataset.target);
    ScrollTrigger.create({
      trigger: el.closest('.stats-section'),
      start: 'top 80%',
      once: true,
      onEnter: function () {
        gsap.fromTo(el, { innerText: 0 }, {
          innerText: target, duration: 2.2, ease: 'power2.out', snap: { innerText: 1 },
          onUpdate: function () { el.textContent = Math.floor(parseFloat(el.innerText)).toLocaleString(); },
        });
      },
    });
  });

  gsap.utils.toArray('.stat-item').forEach(function (item, i) {
    gsap.from(item, {
      y: 30, opacity: 0, duration: 0.6, delay: i * 0.12, ease: 'power2.out',
      scrollTrigger: { trigger: '.stats-section', start: 'top 80%', toggleActions: 'play none none none' },
    });
  });

  gsap.utils.toArray('.course-card').forEach(function (card, i) {
    gsap.from(card, {
      y: 50, opacity: 0, duration: 0.7, delay: i * 0.1, ease: 'power3.out',
      scrollTrigger: { trigger: '.courses-swiper', start: 'top 85%', toggleActions: 'play none none none' },
    });
  });

  gsap.utils.toArray('.ai-feature').forEach(function (f, i) {
    gsap.from(f, {
      y: 40, opacity: 0, duration: 0.6, delay: i * 0.15, ease: 'power2.out',
      scrollTrigger: { trigger: '.ai-features', start: 'top 85%', toggleActions: 'play none none none' },
    });
  });

  /* ─── Journey cards — cinematic staggered reveal ───────── */
  if (document.querySelector('.journey-swiper')) {
    gsap.from('.journey-card', {
      y: 60,
      opacity: 0,
      scale: 0.95,
      duration: 0.9,
      stagger: 0.15,
      ease: 'power3.out',
      scrollTrigger: {
        trigger: '.journey-swiper',
        start: 'top 85%',
        toggleActions: 'play none none none',
      },
    });
  }

  gsap.from('.footer-top > *', {
    y: 30, opacity: 0, duration: 0.7, stagger: 0.12, ease: 'power2.out',
    scrollTrigger: { trigger: '.footer-top', start: 'top 90%', toggleActions: 'play none none none' },
  });

  if (document.querySelector('.logo-ticker-wrapper')) {
    gsap.from('.logo-ticker-wrapper', {
      opacity: 0, y: 20, duration: 0.8, ease: 'power2.out',
      scrollTrigger: { trigger: '.logo-ticker-wrapper', start: 'top 90%', toggleActions: 'play none none none' },
    });
  }

  /* ─── Placement cards staggered reveal REMOVED ────────────────── */
  if (document.querySelector('.placement-swiper')) {
    // Animation removed per user request to fix layout bugs with Swiper clones
  }

  if (document.querySelector('.placement-section .section-header')) {
    gsap.from('.placement-accent', {
      y: 15, opacity: 0, duration: 0.6, ease: 'power2.out',
      scrollTrigger: {
        trigger: '.placement-section .section-header',
        start: 'top 88%',
        toggleActions: 'play none none none',
      },
    });
  }

  gsap.utils.toArray('.institute-section').forEach(function (section) {
    gsap.from(section, {
      opacity: 0, duration: 1, ease: 'power2.out',
      scrollTrigger: { trigger: section, start: 'top 90%', toggleActions: 'play none none none' },
    });
  });

  /* ─── Smooth scroll (only if ScrollToPlugin is available) ── */
  if (typeof ScrollToPlugin !== 'undefined') {
    document.querySelectorAll('a[href^="#"]').forEach(function (link) {
      link.addEventListener('click', function (e) {
        var href = link.getAttribute('href');
        if (!href || href === '#') return;
        
        try {
          var target = document.querySelector(href);
          if (target) {
            e.preventDefault();
            gsap.to(window, { scrollTo: { y: target, offsetY: 72 }, duration: 1.2, ease: 'power3.inOut' });
          }
        } catch (err) {
          // Ignore invalid selectors like href="#some@invalid"
        }
      });
    });
  }

  /* ─── Active nav highlight (throttled) ──────────────────── */
  var sections = document.querySelectorAll('section[id]');
  var navLinks = document.querySelectorAll('.nav-links a');
  if (sections.length && navLinks.length) {
    ScrollTrigger.create({
      trigger: document.body,
      start: 'top top',
      end: 'bottom bottom',
      onUpdate: function () {
        sections.forEach(function (section) {
          var rect = section.getBoundingClientRect();
          if (rect.top <= 100 && rect.bottom > 100) {
            navLinks.forEach(function (a) { a.classList.remove('active'); });
            var id = section.getAttribute('id');
            var activeLink = document.querySelector('.nav-links a[href="#' + id + '"]');
            if (activeLink) activeLink.classList.add('active');
          }
        });
      },
    });
  }

  /* ============================================================
     PREMIUM MOBILE SCROLL EFFECTS (Added dynamically)
     ============================================================ */
  
  // 1. Sleek Scroll Progress Bar at the top
  const progressBar = document.createElement('div');
  progressBar.style.position = 'fixed';
  progressBar.style.top = '0';
  progressBar.style.left = '0';
  progressBar.style.height = '3px';
  progressBar.style.width = '0%';
  progressBar.style.background = 'linear-gradient(to right, #ff416c, #ff4b2b)';
  progressBar.style.zIndex = '9999';
  progressBar.style.pointerEvents = 'none';
  progressBar.style.transition = 'width 0.1s ease-out';
  document.body.appendChild(progressBar);

  window.addEventListener('scroll', function() {
    const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    const scrollPercent = (scrollTop / scrollHeight) * 100;
    progressBar.style.width = scrollPercent + '%';
  }, { passive: true });

  // 2. Hero Content Cinematic Fade out on scroll
  if (document.querySelector('.hero-content')) {
    gsap.to('.hero-content', {
      y: -100,
      opacity: 0,
      ease: 'none',
      scrollTrigger: {
        trigger: '.hero-section',
        start: 'top top',
        end: 'bottom top',
        scrub: true
      }
    });
  }

  // 3. Subtle scale-down reveal for all images
  gsap.utils.toArray('img').forEach(function(img) {
    if(!img.classList.contains('hero-bg-img') && !img.closest('.logo-ticker-wrapper')) {
      gsap.from(img, {
        scale: 1.1,
        duration: 1.5,
        ease: 'power2.out',
        scrollTrigger: {
          trigger: img,
          start: 'top 95%',
          toggleActions: 'play none none none'
        }
      });
    }
  });

})();
