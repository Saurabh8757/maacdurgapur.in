(function () {
  'use strict';

  var page = document.querySelector('.aksha-page');
  if (!page) return;

  page.classList.add('aksha-js');

  var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var revealItems = page.querySelectorAll('.aksha-reveal');

  function initHeroAnimation() {
    var hero = page.querySelector('.aksha-hero');
    if (!hero) return;

    function runGsap() {
      if (typeof window.gsap === 'undefined') {
        window.setTimeout(runGsap, 60);
        return;
      }

      var timeline = window.gsap.timeline({ defaults: { ease: 'power3.out' } });
      timeline
        .from(hero.querySelector('.aksha-hero-brand'), { y: 24, opacity: 0, duration: .65 })
        .from(hero.querySelector('.aksha-hero-label'), { y: 20, opacity: 0, duration: .5 }, '-=.35')
        .from(hero.querySelector('.aksha-hero h1'), { y: 42, opacity: 0, duration: .8 }, '-=.28')
        .from(hero.querySelector('.aksha-hero-text'), { y: 26, opacity: 0, duration: .6 }, '-=.42')
        .from(hero.querySelectorAll('.aksha-hero .aksha-btn'), { y: 20, opacity: 0, duration: .5, stagger: .12 }, '-=.32')
        .from(hero.querySelector('.aksha-student-circle'), { x: 55, opacity: 0, scale: .94, duration: .9 }, '-=.82')
        .from(hero.querySelectorAll('.aksha-achievement-card'), { y: 24, opacity: 0, scale: .94, duration: .55, stagger: .13 }, '-=.48');

      window.gsap.to('.aksha-card-one', { y: -10, duration: 2.8, repeat: -1, yoyo: true, ease: 'sine.inOut' });
      window.gsap.to('.aksha-card-two', { y: 12, duration: 3.2, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: .25 });
      window.gsap.to('.aksha-card-three', { y: -8, duration: 3, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: .5 });
    }

    if (!reducedMotion) runGsap();

    if (!reducedMotion && window.matchMedia('(pointer: fine)').matches) {
      var visual = hero.querySelector('.aksha-hero-visual');
      var student = hero.querySelector('.aksha-student-circle');
      var cards = hero.querySelectorAll('.aksha-achievement-card');

      hero.addEventListener('pointermove', function (event) {
        var bounds = hero.getBoundingClientRect();
        var x = (event.clientX - bounds.left) / bounds.width - 0.5;
        var y = (event.clientY - bounds.top) / bounds.height - 0.5;
        visual.style.transform = 'translate3d(' + (x * 8) + 'px,' + (y * 8) + 'px,0)';
        student.style.transform = 'translate3d(' + (x * -5) + 'px,' + (y * -5) + 'px,0)';
        cards.forEach(function (card, index) {
          card.style.marginLeft = (x * (6 + index * 3)) + 'px';
        });
      });

      hero.addEventListener('pointerleave', function () {
        visual.style.transform = '';
        student.style.transform = '';
        cards.forEach(function (card) { card.style.marginLeft = ''; });
      });
    }
  }

  initHeroAnimation();

  if (reducedMotion || !('IntersectionObserver' in window)) {
    revealItems.forEach(function (item) { item.classList.add('is-visible'); });
  } else {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px' });

    revealItems.forEach(function (item, index) {
      item.style.transitionDelay = Math.min(index % 4, 3) * 70 + 'ms';
      observer.observe(item);
    });
  }

})();
