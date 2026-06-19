(function () {
  'use strict';

  var page = document.querySelector('.sef-page');
  if (!page) return;

  page.classList.add('sef-js');

  var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var revealItems = page.querySelectorAll('.sef-reveal');

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
      item.style.transitionDelay = Math.min(index % 4, 3) * 65 + 'ms';
      observer.observe(item);
    });
  }

  var tabs = Array.prototype.slice.call(page.querySelectorAll('.sef-track-tab'));
  var panels = Array.prototype.slice.call(page.querySelectorAll('.sef-track-panel'));

  function selectTrack(tab) {
    var track = tab.getAttribute('data-track');

    tabs.forEach(function (item) {
      var active = item === tab;
      item.classList.toggle('is-active', active);
      item.setAttribute('aria-selected', active ? 'true' : 'false');
      item.setAttribute('tabindex', active ? '0' : '-1');
    });

    panels.forEach(function (panel) {
      var active = panel.getAttribute('data-panel') === track;
      panel.classList.toggle('is-active', active);
      panel.hidden = !active;
    });
  }

  tabs.forEach(function (tab, index) {
    tab.addEventListener('click', function () { selectTrack(tab); });
    tab.addEventListener('keydown', function (event) {
      if (event.key !== 'ArrowRight' && event.key !== 'ArrowLeft' && event.key !== 'ArrowDown' && event.key !== 'ArrowUp') return;
      event.preventDefault();
      var direction = event.key === 'ArrowRight' || event.key === 'ArrowDown' ? 1 : -1;
      var next = tabs[(index + direction + tabs.length) % tabs.length];
      selectTrack(next);
      next.focus();
    });
  });

  if (!reducedMotion) {
    var heroMedia = page.querySelector('.sef-hero-media img');
    window.addEventListener('scroll', function () {
      if (!heroMedia || window.scrollY > window.innerHeight) return;
      heroMedia.style.transform = 'translate3d(0,' + (window.scrollY * 0.08) + 'px,0) scale(1.02)';
    }, { passive: true });
  }
})();
