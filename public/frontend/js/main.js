/* ============================================================
   MAIN.JS — Swiper init, misc interactions
   ============================================================ */

(function() {

  /* ─── Courses Swiper ──────────────────────────────────── */
  const coursesSwiper = new Swiper('.courses-swiper', {
    slidesPerView: 1.2,
    spaceBetween: 20,
    centeredSlides: false,
    loop: true,
    grabCursor: true,
    navigation: {
      nextEl: '.courses-next',
      prevEl: '.courses-prev',
    },
    breakpoints: {
      480: { slidesPerView: 1.5, spaceBetween: 20 },
      640: { slidesPerView: 2, spaceBetween: 20 },
      900: { slidesPerView: 3, spaceBetween: 24 },
      1200: { slidesPerView: 4, spaceBetween: 24 },
    },
  });

  /* ─── Placement Swiper — Premium Redesign ─────────────── */
  const placementSwiper = new Swiper('.placement-swiper', {
    slidesPerView: 1.2,
    spaceBetween: 16,
    loop: true,
    grabCursor: true,
    speed: 600,
    navigation: {
      nextEl: '.placement-next',
      prevEl: '.placement-prev',
    },
    breakpoints: {
      320: { slidesPerView: 1.2, spaceBetween: 12 },
      480: { slidesPerView: 2, spaceBetween: 14 },
      768: { slidesPerView: 3, spaceBetween: 18 },
      1024: { slidesPerView: 4, spaceBetween: 22 },
      1200: { slidesPerView: 6, spaceBetween: 24 },
    },
  });

  /* ─── Journey Swiper — Premium Cinematic Coverflow ────────── */
  var journeySwiperEl = document.querySelector('.journey-swiper');
  if (journeySwiperEl && typeof Swiper !== 'undefined') {
    var journeySwiper = new Swiper('.journey-swiper', {
      slidesPerView: 1,
      spaceBetween: -40,
      centeredSlides: true,
      loop: true,
      grabCursor: true,
      speed: 700,
      effect: 'coverflow',
      coverflowEffect: {
        rotate: 0,
        stretch: 0,
        depth: 120,
        modifier: 1,
        scale: 0.92,
        slideShadows: false,
      },
      autoplay: {
        delay: 4000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },
      pagination: {
        el: '.journey-pagination',
        clickable: true,
        dynamicBullets: false,
      },
      navigation: {
        nextEl: '.journey-next',
        prevEl: '.journey-prev',
      },
      breakpoints: {
        320: { slidesPerView: 1, spaceBetween: 0, coverflowEffect: { depth: 60, modifier: 0.8, scale: 0.88 } },
        480: { slidesPerView: 1, spaceBetween: 0, coverflowEffect: { depth: 50, modifier: 0.8, scale: 0.88 } },
        768: { slidesPerView: 2, spaceBetween: -30, coverflowEffect: { depth: 80, modifier: 0.9, scale: 0.9 } },
        1024: { slidesPerView: 2.5, spaceBetween: -35, coverflowEffect: { depth: 100, modifier: 0.95, scale: 0.92 } },
        1280: { slidesPerView: 3, spaceBetween: -40, coverflowEffect: { depth: 120, modifier: 1, scale: 0.92 } },
      },
    });
  }

  /* ─── Logo ticker — pause on hover ─────────────────────── */
  const ticker = document.getElementById('logo-ticker');
  if (ticker) {
    ticker.addEventListener('mouseenter', () => {
      ticker.style.animationPlayState = 'paused';
    });
    ticker.addEventListener('mouseleave', () => {
      ticker.style.animationPlayState = 'running';
    });
  }

  /* ─── Navbar mobile toggle (placeholder) ────────────────── */
  // Mobile nav can be added later with hamburger button
   const hamburger = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobileMenu');
  const coursesToggle = document.getElementById('coursesToggle');
  const coursesSub = document.getElementById('coursesSub');
  const navbar = document.getElementById('navbar');
  let menuOpen = false;

  function openMenu() {
    menuOpen = true;
    hamburger.classList.add('open');
    hamburger.setAttribute('aria-expanded', 'true');
    mobileMenu.classList.add('visible');
    document.body.style.overflow = 'hidden';
  }

  function closeMenu() {
    menuOpen = false;
    hamburger.classList.remove('open');
    hamburger.setAttribute('aria-expanded', 'false');
    mobileMenu.classList.remove('visible');
    document.body.style.overflow = '';
  }

  hamburger.addEventListener('click', () => menuOpen ? closeMenu() : openMenu());

  coursesToggle.addEventListener('click', (e) => {
    e.preventDefault();
    const open = coursesSub.classList.toggle('open');
    coursesToggle.querySelector('.arrow').style.transform = open ? 'rotate(180deg)' : '';
  });

  document.querySelectorAll('[data-close]').forEach(el => {
    el.addEventListener('click', closeMenu);
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && menuOpen) closeMenu();
  });



  /* ─── Magnetic button effect ────────────────────────────── */
  document.querySelectorAll('.btn-primary, .btn-secondary, .btn-register, .btn-counselling').forEach(btn => {
    btn.addEventListener('mousemove', (e) => {
      const rect = btn.getBoundingClientRect();
      const dx = e.clientX - (rect.left + rect.width / 2);
      const dy = e.clientY - (rect.top + rect.height / 2);
      btn.style.transform = `translate(${dx * 0.18}px, ${dy * 0.18}px)`;
    });
    btn.addEventListener('mouseleave', () => {
      btn.style.transform = '';
    });
  });





  /* ─── Image blur-up loading (journey + placement cards) ── */
  document.querySelectorAll('.journey-card-img, .placement-card-img').forEach(function (img) {
    if (img.complete && img.naturalWidth > 0) {
      img.classList.add('loaded');
    } else {
      img.addEventListener('load', function () {
        img.classList.add('loaded');
      });
      img.addEventListener('error', function () {
        // fallback: show image even if load fails
        img.classList.add('loaded');
      });
    }
  });

  /* ─── Recruiter logo glow on hover ─────────────────────── */
  document.querySelectorAll('.recruiter-logo').forEach(logo => {
    logo.addEventListener('mouseenter', () => {
      logo.style.transform = 'scale(1.08)';
      logo.style.transition = 'transform 0.3s, box-shadow 0.3s';
    });
    logo.addEventListener('mouseleave', () => {
      logo.style.transform = '';
    });
  });






})();
