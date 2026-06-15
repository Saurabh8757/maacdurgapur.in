/* ============================================================
   CURSOR.JS — Custom Cursor [OPTIMIZED]
   ============================================================ */

(function() {
  'use strict';

  var outer = document.getElementById('cursor-outer');
  var inner = document.getElementById('cursor-inner');
  var canvas = document.getElementById('cursor-canvas');
  var ctx = canvas ? canvas.getContext('2d') : null;

  var mouseX = -200, mouseY = -200;
  var outerX = -200, outerY = -200;
  var particles = [];
  var frame = 0;
  var isMobile = window.innerWidth < 768;

  // Disable custom cursor on touch devices
  if (isMobile && outer) { outer.style.display = 'none'; }
  if (isMobile && inner) { inner.style.display = 'none'; }
  if (isMobile && canvas) { canvas.style.display = 'none'; }
  if (isMobile) return;

  function resizeCanvas() {
    if (!canvas) return;
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
  }
  resizeCanvas();
  window.addEventListener('resize', resizeCanvas, { passive: true });

  // Track mouse — passive
  document.addEventListener('mousemove', function (e) {
    mouseX = e.clientX;
    mouseY = e.clientY;

    // Reduce trail particle spawn rate
    if (frame % 4 === 0) {
      particles.push({
        x: mouseX, y: mouseY,
        vx: (Math.random() - 0.5) * 1.5,
        vy: (Math.random() - 0.5) * 1.5,
        life: 1.0,
        decay: 0.04 + Math.random() * 0.04,
        size: 2 + Math.random() * 3,
        hue: Math.random() > 0.5 ? 25 : 200,
      });
    }
  }, { passive: true });

  // Hover state changes — use event delegation for performance
  document.addEventListener('mouseover', function (e) {
    var tag = e.target.tagName.toLowerCase();
    var isInteractive = ['a', 'button', 'input', 'select', 'textarea'].indexOf(tag) !== -1
      || e.target.closest('a, button, .course-card, .placement-card, .institute-card');

    if (isInteractive) {
      if (outer) { outer.style.width = '52px'; outer.style.height = '52px'; outer.style.borderColor = 'rgba(255,200,0,0.8)'; outer.style.background = 'rgba(255,106,0,0.08)'; }
      if (inner) { inner.style.width = '12px'; inner.style.height = '12px'; }
    } else {
      if (outer) { outer.style.width = '36px'; outer.style.height = '36px'; outer.style.borderColor = 'rgba(255,106,0,0.7)'; outer.style.background = 'transparent'; }
      if (inner) { inner.style.width = '8px'; inner.style.height = '8px'; }
    }
  }, { passive: true });

  function lerp(a, b, t) { return a + (b - a) * t; }

  function animate() {
    frame++;
    if (!ctx) { requestAnimationFrame(animate); return; }

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    outerX = lerp(outerX, mouseX, 0.12);
    outerY = lerp(outerY, mouseY, 0.12);

    if (outer) { outer.style.left = outerX + 'px'; outer.style.top = outerY + 'px'; }
    if (inner) { inner.style.left = mouseX + 'px'; inner.style.top = mouseY + 'px'; }

    // Draw trail particles
    for (var i = particles.length - 1; i >= 0; i--) {
      var p = particles[i];
      p.x += p.vx;
      p.y += p.vy;
      p.vy += 0.05;
      p.life -= p.decay;
      if (p.life <= 0) { particles.splice(i, 1); continue; }
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.size * p.life, 0, Math.PI * 2);
      ctx.fillStyle = 'hsla(' + p.hue + ', 100%, 65%, ' + (p.life * 0.5) + ')';
      ctx.fill();
    }

    // Cursor glow
    if (mouseX > 0) {
      var grad = ctx.createRadialGradient(mouseX, mouseY, 0, mouseX, mouseY, 28);
      grad.addColorStop(0, 'rgba(255,106,0,0.07)');
      grad.addColorStop(1, 'rgba(255,106,0,0)');
      ctx.beginPath();
      ctx.arc(mouseX, mouseY, 28, 0, Math.PI * 2);
      ctx.fillStyle = grad;
      ctx.fill();
    }

    requestAnimationFrame(animate);
  }

  animate();
})();
