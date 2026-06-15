/* ============================================================
   COUNSELLING MODAL — Premium GSAP Animations & Form Logic
   ============================================================ */

(function () {
  'use strict';

  /* ─── DOM refs ─────────────────────────────────────────── */
  const overlay = document.getElementById('counsellingOverlay');
  const modal  = document.getElementById('counsellingModal');
  const closeBtn = document.getElementById('counsellingClose');
  const form   = document.getElementById('comment_form');
  const submitBtn = document.getElementById('counsellingSubmit');
  const successEl = document.getElementById('counsellingSuccess');
  const formBody  = document.getElementById('counsellingFormBody');

  if (!overlay || !modal) return;

  /* ─── State ────────────────────────────────────────────── */
  let isOpen = false;
  let animating = false;
  let modalParticles = [];

  /* ─── Spawn ambient particles inside modal ─────────────── */
  function spawnModalParticles() {
    clearModalParticles();
    var colors = ['#ff6a00', '#ffd700', '#ff4500', '#ff8c38', '#ffe55c'];
    for (var i = 0; i < 18; i++) {
      var p = document.createElement('div');
      p.className = 'modal-particle';
      var size = 2 + Math.random() * 4;
      p.style.cssText =
        'position:absolute;width:' + size + 'px;height:' + size + 'px;' +
        'border-radius:50%;pointer-events:none;z-index:0;' +
        'background:' + colors[Math.floor(Math.random() * colors.length)] + ';' +
        'left:' + (Math.random() * 100) + '%;' +
        'top:' + (Math.random() * 100) + '%;opacity:0;';
      modal.appendChild(p);
      modalParticles.push(p);

      gsap.to(p, {
        y: (Math.random() - 0.5) * 200,
        x: (Math.random() - 0.5) * 100,
        opacity: 0.15 + Math.random() * 0.35,
        duration: 3 + Math.random() * 4,
        repeat: -1,
        yoyo: true,
        ease: 'sine.inOut',
        delay: Math.random() * 2,
      });
    }
  }

  function clearModalParticles() {
    modalParticles.forEach(function (p) {
      gsap.killTweensOf(p);
      if (p.parentNode) p.parentNode.removeChild(p);
    });
    modalParticles = [];
  }

  /* ─── Open modal ───────────────────────────────────────── */
  function openModal(e) {
    if (e) {
      e.preventDefault();
      e.stopPropagation();
    }
    if (isOpen || animating) return;
    animating = true;

    // Reset form state
    if (form) {
      form.reset();
      form.querySelectorAll('.error-text').forEach(function (el) { el.textContent = ''; });
      // Reset floating label states
      form.querySelectorAll('.field-input, .field-select').forEach(function (input) {
        input.classList.remove('filled', 'focused');
      });
    }
    if (formBody) formBody.style.display = '';
    if (successEl) {
      successEl.classList.remove('show');
      successEl.style.display = 'none';
    }
    if (submitBtn) {
      submitBtn.classList.remove('loading');
      submitBtn.disabled = false;
    }

    overlay.classList.add('active');
    isOpen = true;

    // Prevent body scroll
    document.body.style.overflow = 'hidden';
    document.body.style.paddingRight = window.innerWidth - document.documentElement.clientWidth + 'px';

    // Spawn ambient particles
    spawnModalParticles();

    // GSAP entrance animation
    gsap.set(modal, { scale: 0.8, opacity: 0, y: 30 });

    var tl = gsap.timeline({
      onComplete: function () {
        animating = false;
        if (form) {
          var firstInput = form.querySelector('input, select');
          if (firstInput) firstInput.focus();
        }
      }
    });

    // Overlay fade in
    tl.to(overlay, { opacity: 1, duration: 0.35, ease: 'power2.out' });

    // Modal entrance: scale + opacity + y
    tl.to(modal, {
      scale: 1,
      opacity: 1,
      y: 0,
      duration: 0.55,
      ease: 'back.out(1.4)',
    }, '-=0.2');

    // Stagger form elements with premium feel
    var formElements = modal.querySelectorAll(
      '.counselling-icon-wrap, .counselling-title, .counselling-subtitle, ' +
      '.counselling-form .form-group, .counselling-submit'
    );
    if (formElements.length) {
      tl.fromTo(formElements,
        { y: 25, opacity: 0 },
        { y: 0, opacity: 1, duration: 0.4, stagger: 0.04, ease: 'power3.out' },
        '-=0.25'
      );
    }
  }

  /* ─── Close modal ──────────────────────────────────────── */
  function closeModal() {
    if (!isOpen || animating) return;
    animating = true;

    // Kill particle tweens
    clearModalParticles();

    var tl = gsap.timeline({
      onComplete: function () {
        overlay.classList.remove('active');
        isOpen = false;
        animating = false;
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
      }
    });

    tl.to(modal, {
      scale: 0.85,
      opacity: 0,
      y: 20,
      duration: 0.35,
      ease: 'power2.in',
    });

    tl.to(overlay, {
      opacity: 0,
      duration: 0.3,
      ease: 'power2.in',
    }, '-=0.2');
  }

  /* ─── Success animation ────────────────────────────────── */
  function showSuccess() {
    formBody.style.display = 'none';
    successEl.style.display = 'block';
    successEl.classList.add('show');

    // Clear any lingering particles
    clearModalParticles();

    var successTl = gsap.timeline();

    successTl.fromTo(successEl,
      { scale: 0.8, opacity: 0 },
      { scale: 1, opacity: 1, duration: 0.55, ease: 'back.out(1.7)' }
    );

    // Animate the SVG circle
    var circle = successEl.querySelector('.success-circle');
    if (circle) {
      var circumference = 2 * Math.PI * 45;
      circle.style.strokeDasharray = circumference;
      circle.style.strokeDashoffset = circumference;
      successTl.to(circle, {
        strokeDashoffset: 0,
        duration: 0.8,
        ease: 'power2.out',
      }, '-=0.3');
    }

    // Animate the checkmark path
    var check = successEl.querySelector('.success-check');
    if (check) {
      var checkLength = check.getTotalLength ? check.getTotalLength() : 80;
      check.style.strokeDasharray = checkLength;
      check.style.strokeDashoffset = checkLength;
      successTl.to(check, {
        strokeDashoffset: 0,
        duration: 0.5,
        ease: 'power2.out',
      }, '-=0.4');
    }

    // Animate success text
    var textEls = successEl.querySelectorAll('.success-title, .success-message, .success-submessage, .success-close-btn');
    successTl.fromTo(textEls,
      { y: 15, opacity: 0 },
      { y: 0, opacity: 1, duration: 0.4, stagger: 0.08, ease: 'power2.out' },
      '-=0.2'
    );

    // Celebration particles
    createParticles();
  }

  /* ─── Particle celebration ─────────────────────────────── */
  function createParticles() {
    var colors = ['#ff6a00', '#ffd700', '#ff4500', '#ff8c38', '#ffe55c', '#fff'];

    for (var i = 0; i < 40; i++) {
      var particle = document.createElement('div');
      var size = 4 + Math.random() * 8;
      var isSquare = Math.random() > 0.6;
      particle.style.cssText =
        'position:fixed;width:' + size + 'px;' +
        'height:' + size + 'px;' +
        'border-radius:' + (isSquare ? '2px' : '50%') + ';' +
        'background:' + colors[Math.floor(Math.random() * colors.length)] + ';' +
        'z-index:10001;' +
        'pointer-events:none;' +
        'left:' + (35 + Math.random() * 30) + '%;' +
        'top:' + (35 + Math.random() * 20) + '%;' +
        'transform:rotate(' + (Math.random() * 360) + 'deg);';

      overlay.appendChild(particle);

      var angle = Math.random() * Math.PI * 2;
      var distance = 150 + Math.random() * 250;

      gsap.to(particle, {
        x: Math.cos(angle) * distance,
        y: Math.sin(angle) * distance - 80,
        rotation: Math.random() * 720 - 360,
        scale: 0,
        opacity: 0,
        duration: 1 + Math.random() * 1.2,
        ease: 'power3.out',
        delay: 0.05 + Math.random() * 0.2,
        onComplete: function () {
          if (particle.parentNode) particle.parentNode.removeChild(particle);
        },
      });
    }
  }

  /* ─── Event listeners ──────────────────────────────────── */

  // Direct event attachment for ALL modal trigger buttons to prevent mobile/iOS Safari delegation issues
  const attachModalListeners = () => {
    document.querySelectorAll('.btn-counselling, .btn-register, .btn-primary, .open-modal, .mobile-cta').forEach(btn => {
      btn.addEventListener('click', function(e) {
        openModal(e);
      });
    });
  };
  
  // Call once initially
  attachModalListeners();

  // Close button
  if (closeBtn) {
    closeBtn.addEventListener('click', closeModal);
  }

  // Click outside modal
  overlay.addEventListener('click', function (e) {
    if (e.target === overlay) closeModal();
  });

  // ESC key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && isOpen) closeModal();
  });

  /* ─── AJAX form submission ─────────────────────────────── */
  function submitForm(e) {
    e.preventDefault();

    var name = form.querySelector('[name="name"]');
    var phone = form.querySelector('[name="phone"]');
    var email = form.querySelector('[name="email"]');
    var course = form.querySelector('[name="course_id"]');
    var hasError = false;

    // Clear previous errors
    form.querySelectorAll('.error-text').forEach(function (el) { el.textContent = ''; });

    if (!name.value.trim()) {
      var nameErr = form.querySelector('.name_error');
      if (nameErr) nameErr.textContent = 'Please enter your Full Name';
      hasError = true;
    }

    if (!phone.value.trim()) {
      var phoneErr = form.querySelector('.phone_error');
      if (phoneErr) phoneErr.textContent = 'Please enter phone number';
      hasError = true;
    } else if (!/^\d+$/.test(phone.value.trim())) {
      var phoneErr = form.querySelector('.phone_error');
      if (phoneErr) phoneErr.textContent = 'Phone number must be numeric';
      hasError = true;
    }

    if (!email.value.trim()) {
      var emailErr = form.querySelector('.email_error');
      if (emailErr) emailErr.textContent = 'Please enter your Email';
      hasError = true;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
      var emailErr = form.querySelector('.email_error');
      if (emailErr) emailErr.textContent = 'Email should be a valid format';
      hasError = true;
    }

    if (!course.value || course.value === '') {
      var courseErr = form.querySelector('.course_id_error');
      if (courseErr) courseErr.textContent = 'Please Select Course';
      hasError = true;
    }

    if (hasError) {
      gsap.fromTo(form, { x: 0 }, {
        x: [-8, 8, -6, 6, -3, 3, 0],
        duration: 0.5,
        ease: 'power2.out',
      });
      return;
    }

    // Show loading state
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;

    var action = form.getAttribute('action');
    var method = form.getAttribute('method') || 'POST';
    var formData = new FormData(form);

    fetch(action, {
      method: method.toUpperCase(),
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      },
    })
    .then(function (response) {
      return response.json();
    })
    .then(function (data) {
      submitBtn.classList.remove('loading');
      submitBtn.disabled = false;

      if (data.status === 0) {
        if (data.error) {
          for (var key in data.error) {
            if (data.error.hasOwnProperty(key)) {
              var errorEl = form.querySelector('.' + key + '_error');
              if (errorEl) errorEl.textContent = data.error[key][0];
            }
          }
        }
        gsap.fromTo(form, { x: 0 }, {
          x: [-8, 8, -6, 6, -3, 3, 0],
          duration: 0.5,
          ease: 'power2.out',
        });
      } else if (data.status === 1) {
        showSuccess();
      }
    })
    .catch(function () {
      submitBtn.classList.remove('loading');
      submitBtn.disabled = false;
      form.submit();
    });
  }

  if (form) {
    form.addEventListener('submit', submitForm);
  }

  /* ─── Success close button ─────────────────────────────── */
  var successClose = document.querySelector('.success-close-btn');
  if (successClose) {
    successClose.addEventListener('click', closeModal);
  }

  /* ─── Magnetic button effect ───────────────────────────── */
  if (submitBtn) {
    submitBtn.addEventListener('mousemove', function (e) {
      if (submitBtn.disabled) return;
      var rect = submitBtn.getBoundingClientRect();
      var dx = e.clientX - (rect.left + rect.width / 2);
      var dy = e.clientY - (rect.top + rect.height / 2);
      submitBtn.style.transform = 'translate(' + (dx * 0.15) + 'px, ' + (dy * 0.15) + 'px)';
    });

    submitBtn.addEventListener('mouseleave', function () {
      gsap.to(submitBtn, { x: 0, y: 0, duration: 0.4, ease: 'elastic.out(1, 0.5)' });
    });
  }

  /* ─── Floating label behavior ──────────────────────────── */
  function setupFloatingLabels() {
    form.querySelectorAll('.form-group.labeled').forEach(function (group) {
      var input = group.querySelector('.field-input, .field-select');
      var label = group.querySelector('.field-label');
      if (!input || !label) return;

      function checkValue() {
        if (input.value && input.value !== '') {
          input.classList.add('filled');
        } else {
          input.classList.remove('filled');
        }
      }

      input.addEventListener('focus', function () {
        input.classList.add('focused');
      });

      input.addEventListener('blur', function () {
        input.classList.remove('focused');
        checkValue();
      });

      input.addEventListener('input', checkValue);
      input.addEventListener('change', checkValue);

      // Check initial state
      checkValue();
    });
  }

  // Setup labels after DOM is ready
  if (form) {
    setupFloatingLabels();
    initCustomSelect();
  }

  /* ─── Custom Select Enhancement ────────────────────────── */
  function initCustomSelect() {
    const nativeSelect = document.getElementById('modal-course');
    if (!nativeSelect) return;

    const group = nativeSelect.closest('.form-group');
    const container = document.createElement('div');
    container.className = 'custom-select-container';
    
    // Insert container before native select
    nativeSelect.parentNode.insertBefore(container, nativeSelect);
    
    const trigger = document.createElement('div');
    trigger.className = 'custom-select-trigger';
    trigger.textContent = ''; // Empty initially to let label show in placeholder pos
    container.appendChild(trigger);

    const optionsList = document.createElement('div');
    optionsList.className = 'custom-options';
    container.appendChild(optionsList);

    // Flag as enhanced to hide native select via CSS
    nativeSelect.classList.add('enhanced');

    // Populate options
    Array.from(nativeSelect.options).forEach((opt) => {
      if (opt.value === "" || opt.hidden) return;
      const customOpt = document.createElement('span');
      customOpt.className = 'custom-option';
      customOpt.textContent = opt.textContent;
      customOpt.dataset.value = opt.value;
      optionsList.appendChild(customOpt);

      customOpt.addEventListener('click', (e) => {
        e.stopPropagation();
        nativeSelect.value = customOpt.dataset.value;
        trigger.textContent = customOpt.textContent;
        container.classList.remove('open');
        
        // Update states for CSS animations
        nativeSelect.classList.add('filled');
        nativeSelect.dispatchEvent(new Event('change'));
        nativeSelect.dispatchEvent(new Event('input'));
        
        optionsList.querySelectorAll('.custom-option').forEach(el => el.classList.remove('selected'));
        customOpt.classList.add('selected');
      });
    });

    // Toggle open
    trigger.addEventListener('click', (e) => {
      e.stopPropagation();
      const wasOpen = container.classList.contains('open');
      
      // Close others
      document.querySelectorAll('.custom-select-container').forEach(c => c.classList.remove('open'));
      
      if (!wasOpen) {
        container.classList.add('open');
        nativeSelect.classList.add('focused');
      } else {
        if (!nativeSelect.value) nativeSelect.classList.remove('focused');
      }
    });

    // Handle clicks outside
    document.addEventListener('click', () => {
      container.classList.remove('open');
      if (!nativeSelect.value) nativeSelect.classList.remove('focused');
    });

    // Reset handler
    form.addEventListener('reset', () => {
      trigger.textContent = '';
      nativeSelect.classList.remove('filled', 'focused');
      optionsList.querySelectorAll('.custom-option').forEach(el => el.classList.remove('selected'));
    });
  }


})();
