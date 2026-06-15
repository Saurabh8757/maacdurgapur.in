/**
 * MAAC Durgapur - Landing Page JavaScript
 * ----------------------------------------
 * Handles: GSAP ScrollTrigger animations, AJAX enquiry form,
 * parallax hero, card glow effects, and success states.
 *
 * Dependencies (loaded via app.blade.php with defer):
 *   - GSAP 3.12.2
 *   - GSAP ScrollTrigger plugin
 *   - Three.js r128
 *   - Swiper 10.3.1
 */
(function () {
  'use strict';

  /* ===================================================================
   *  Boot – wait for GSAP & ScrollTrigger to be available
   * =================================================================== */
  function init() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
      setTimeout(init, 100);
      return;
    }

    gsap.registerPlugin(ScrollTrigger);

    injectSuccessStyles();
    initAnimations();
    initForm();
    initParallax();
    initCardGlow();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  /* ===================================================================
   *  1. GSAP ScrollTrigger Animations
   * =================================================================== */
  function initAnimations() {
    animateHero();
    animateCTA();
    animateWhyChoose();
    animateCourses();
    animateLocations();
  }

  /* ----- a) Hero Section (timeline, delay for loader) ----- */
  function animateHero() {
    var hero = document.querySelector('.maac-hero');
    if (!hero) return;

    var tl = gsap.timeline({ delay: 0.5 });

    // Hero heading lines – stagger fade-in from left
    var headingLines = hero.querySelectorAll('.hero-heading-line, .maac-hero-heading span, .maac-hero-heading .line');
    if (headingLines.length) {
      tl.from(headingLines, {
        x: -40,
        opacity: 0,
        duration: 0.7,
        ease: 'power3.out',
        stagger: 0.15
      });
    } else {
      // Fallback: treat the whole heading as one element
      var heading = hero.querySelector('.maac-hero-heading, .hero-heading');
      if (heading) {
        tl.from(heading, { x: -40, opacity: 0, duration: 0.7, ease: 'power3.out' });
      }
    }

    // Hero subtitle – fade up
    var subtitle = hero.querySelector('.maac-hero-subtitle, .hero-subtitle');
    if (subtitle) {
      tl.from(subtitle, { y: 30, opacity: 0, duration: 0.6, ease: 'power2.out' }, '-=0.3');
    }

    // Hero tagline – fade up
    var tagline = hero.querySelector('.maac-hero-tagline, .hero-tagline');
    if (tagline) {
      tl.from(tagline, { y: 30, opacity: 0, duration: 0.6, ease: 'power2.out' }, '-=0.25');
    }

    // Hero offer card – fade up with slight scale
    var offerCard = hero.querySelector('.maac-hero-offer, .hero-offer-card');
    if (offerCard) {
      tl.from(offerCard, { y: 40, opacity: 0, scale: 0.95, duration: 0.6, ease: 'power2.out' }, '-=0.2');
    }

    // Enquiry form – fade in from right
    var enquiryForm = hero.querySelector('.maac-enquiry-form, .enquiry-form');
    if (enquiryForm) {
      tl.from(enquiryForm, { x: 40, opacity: 0, duration: 0.7, ease: 'power3.out' }, '-=0.4');
    }
  }

  /* ----- b) CTA Section ----- */
  function animateCTA() {
    var section = document.querySelector('.maac-cta');
    if (!section) return;

    var tl = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start: 'top 80%',
        toggleActions: 'play none none none'
      }
    });

    var heading = section.querySelector('.maac-cta-heading, .cta-heading, h2');
    if (heading) {
      tl.from(heading, { y: 40, opacity: 0, duration: 0.7, ease: 'power2.out' });
    }

    var btn = section.querySelector('.maac-cta-btn, .cta-btn, .cta-button');
    if (btn) {
      tl.from(btn, { y: 30, opacity: 0, duration: 0.6, ease: 'power2.out' }, '-=0.3');
    }

    var featureItems = section.querySelectorAll('.maac-cta-feature, .cta-feature-item');
    if (featureItems.length) {
      tl.from(featureItems, {
        x: 40,
        opacity: 0,
        duration: 0.6,
        ease: 'power2.out',
        stagger: 0.12
      }, '-=0.2');
    }
  }

  /* ----- c) Why Choose Section ----- */
  function animateWhyChoose() {
    var section = document.querySelector('.maac-why-choose, .maac-why');
    if (!section) return;

    var tl = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start: 'top 80%',
        toggleActions: 'play none none none'
      }
    });

    var header = section.querySelector('.section-header, .maac-section-header, h2');
    if (header) {
      tl.from(header, { y: 40, opacity: 0, duration: 0.7, ease: 'power2.out' });
    }

    var cards = gsap.utils.toArray('.maac-why-card');
    if (cards.length) {
      tl.from(cards, {
        y: 50,
        opacity: 0,
        duration: 0.6,
        ease: 'power2.out',
        stagger: 0.15
      }, '-=0.3');
    }
  }

  /* ----- d) Courses Section ----- */
  function animateCourses() {
    var section = document.querySelector('.maac-courses');
    if (!section) return;

    var tl = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start: 'top 80%',
        toggleActions: 'play none none none'
      }
    });

    var header = section.querySelector('.section-header, .maac-section-header, h2');
    if (header) {
      tl.from(header, { y: 40, opacity: 0, duration: 0.7, ease: 'power2.out' });
    }

    var cards = gsap.utils.toArray('.maac-course-card');
    if (cards.length) {
      tl.from(cards, {
        y: 50,
        opacity: 0,
        duration: 0.6,
        ease: 'power2.out',
        stagger: 0.1
      }, '-=0.3');
    }
  }

  /* ----- e) Locations Section ----- */
  function animateLocations() {
    var section = document.querySelector('.maac-locations');
    if (!section) return;

    var tl = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start: 'top 80%',
        toggleActions: 'play none none none'
      }
    });

    var header = section.querySelector('.section-header, .maac-section-header, h2');
    if (header) {
      tl.from(header, { y: 40, opacity: 0, duration: 0.7, ease: 'power2.out' });
    }

    var cards = gsap.utils.toArray('.maac-location-card');
    if (cards.length) {
      tl.from(cards, {
        y: 50,
        opacity: 0,
        duration: 0.6,
        ease: 'power2.out',
        stagger: 0.15
      }, '-=0.3');
    }
  }

  /* ===================================================================
   *  2. Enquiry Form – AJAX Submission
   * =================================================================== */
  function initForm() {
    var form = document.getElementById('maacEnquiryForm');
    if (!form) return;

    var submitBtn = form.querySelector('.enquiry-submit');
    if (!submitBtn) return;

    var btnText  = submitBtn.querySelector('.btn-text');
    var btnArrow = submitBtn.querySelector('.submit-arrow');

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      // CSRF token
      var tokenMeta = document.querySelector('meta[name="csrf-token"]');
      if (!tokenMeta) {
        console.error('CSRF token meta tag not found.');
        return;
      }
      var token = tokenMeta.content;

      // Disable button, show loading text
      submitBtn.disabled = true;
      if (btnText)  btnText.textContent = 'Submitting...';
      if (btnArrow) btnArrow.style.display = 'none';

      // Clear previous validation errors
      form.querySelectorAll('.field-error').forEach(function (el) {
        el.textContent = '';
      });

      var formData = new FormData(form);

      fetch(form.action, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      })
        .then(function (res) {
          return res.json();
        })
        .then(function (data) {
          if (data.status === 0 && data.error) {
            // Validation errors
            Object.keys(data.error).forEach(function (key) {
              var errorEl = form.querySelector('.' + key + '_error');
              if (errorEl) {
                errorEl.textContent = data.error[key][0];

                // Shake animation on the parent field wrapper
                var field = errorEl.closest('.enquiry-field');
                if (field) {
                  gsap.fromTo(field,
                    { x: -8 },
                    { x: 0, duration: 0.5, ease: 'elastic.out(1, 0.3)' }
                  );
                }
              }
            });
          } else if (data.status === 1) {
            showSuccess(form);
          }
        })
        .catch(function (err) {
          console.error('Form submission error:', err);
          alert('Something went wrong. Please try again.');
        })
        .finally(function () {
          submitBtn.disabled = false;
          if (btnText)  btnText.textContent = 'ENROLL NOW';
          if (btnArrow) btnArrow.style.display = '';
        });
    });
  }

  /* ===================================================================
   *  3. Success State
   * =================================================================== */
  function showSuccess(form) {
    var formCard = form.closest('.maac-enquiry-form');
    if (!formCard) formCard = form.parentElement; // fallback

    // Build success overlay
    var successDiv       = document.createElement('div');
    successDiv.className = 'enquiry-success-state';
    successDiv.innerHTML =
      '<div class="success-checkmark">' +
        '<svg viewBox="0 0 100 100" width="80" height="80">' +
          '<circle class="success-circle" cx="50" cy="50" r="45" fill="none" stroke="url(#maacGoldGrad)" stroke-width="3"/>' +
          '<path class="success-check" d="M30 52 L44 66 L70 36" fill="none" stroke="url(#maacGoldGrad)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>' +
          '<defs>' +
            '<linearGradient id="maacGoldGrad" x1="0%" y1="0%" x2="100%" y2="100%">' +
              '<stop offset="0%" style="stop-color:#ffd700"/>' +
              '<stop offset="100%" style="stop-color:#ff6a00"/>' +
            '</linearGradient>' +
          '</defs>' +
        '</svg>' +
      '</div>' +
      '<h3>Thank You! 🎉</h3>' +
      '<p>Our counsellor will contact you shortly.</p>';

    // Animate form out, then success in
    gsap.to(form, {
      opacity: 0,
      y: -20,
      duration: 0.4,
      onComplete: function () {
        form.style.display = 'none';
        formCard.appendChild(successDiv);

        // Scale-in the success state
        gsap.fromTo(successDiv,
          { opacity: 0, scale: 0.8 },
          { opacity: 1, scale: 1, duration: 0.5, ease: 'back.out(1.7)' }
        );

        // Animate SVG circle draw
        var circle = successDiv.querySelector('.success-circle');
        if (circle) {
          var circleLen = circle.getTotalLength();
          gsap.set(circle, { strokeDasharray: circleLen, strokeDashoffset: circleLen });
          gsap.to(circle, { strokeDashoffset: 0, duration: 0.8, delay: 0.3, ease: 'power2.out' });
        }

        // Animate SVG check draw
        var check = successDiv.querySelector('.success-check');
        if (check) {
          var checkLen = check.getTotalLength();
          gsap.set(check, { strokeDasharray: checkLen, strokeDashoffset: checkLen });
          gsap.to(check, { strokeDashoffset: 0, duration: 0.5, delay: 0.9, ease: 'power2.out' });
        }

        // Reset after 4 seconds
        setTimeout(function () {
          gsap.to(successDiv, {
            opacity: 0,
            duration: 0.3,
            onComplete: function () {
              successDiv.remove();
              form.reset();
              form.style.display = '';
              gsap.fromTo(form,
                { opacity: 0, y: 20 },
                { opacity: 1, y: 0, duration: 0.4 }
              );
            }
          });
        }, 4000);
      }
    });
  }

  /* ===================================================================
   *  4. Parallax on Hero Background
   * =================================================================== */
  function initParallax() {
    var heroBg = document.querySelector('.maac-hero-bg');
    if (!heroBg) return;

    gsap.to(heroBg, {
      y: '20%',
      ease: 'none',
      scrollTrigger: {
        trigger: '.maac-hero',
        start: 'top top',
        end: 'bottom top',
        scrub: true
      }
    });
  }

  /* ===================================================================
   *  5. Card Hover Glow Effect (mouse-follow)
   * =================================================================== */
  function initCardGlow() {
    var cards = document.querySelectorAll('.maac-why-card, .maac-course-card, .maac-location-card');
    if (!cards.length) return;

    cards.forEach(function (card) {
      card.addEventListener('mousemove', function (e) {
        var rect = card.getBoundingClientRect();
        var x = e.clientX - rect.left;
        var y = e.clientY - rect.top;
        card.style.setProperty('--glow-x', x + 'px');
        card.style.setProperty('--glow-y', y + 'px');
      });
    });
  }

  /* ===================================================================
   *  6. Dynamic Styles for Success State
   * =================================================================== */
  function injectSuccessStyles() {
    if (document.getElementById('maac-success-styles')) return;

    var css =
      '.enquiry-success-state {' +
        'text-align: center;' +
        'padding: 40px 20px;' +
        'color: #ffffff;' +
        'display: flex;' +
        'flex-direction: column;' +
        'align-items: center;' +
        'justify-content: center;' +
        'min-height: 200px;' +
      '}' +
      '.enquiry-success-state .success-checkmark {' +
        'margin-bottom: 8px;' +
      '}' +
      '.enquiry-success-state h3 {' +
        'font-family: "Oswald", sans-serif;' +
        'font-size: 1.5rem;' +
        'margin-top: 20px;' +
        'color: #ffd700;' +
        'font-weight: 600;' +
        'letter-spacing: 0.5px;' +
      '}' +
      '.enquiry-success-state p {' +
        'font-family: "Nunito", sans-serif;' +
        'color: #9999bb;' +
        'margin-top: 8px;' +
        'font-size: 1rem;' +
        'line-height: 1.5;' +
      '}';

    var style   = document.createElement('style');
    style.id    = 'maac-success-styles';
    style.type  = 'text/css';
    style.textContent = css;
    document.head.appendChild(style);
  }

})();
