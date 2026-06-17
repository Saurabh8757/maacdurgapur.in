/* ============================================================
   SAKURA.JS — Three.js Sakura Particle Systems [OPTIMIZED]
   ============================================================ */

(function() {
  'use strict';

  var isMobile = window.innerWidth < 768;
  var heroCanvas = document.getElementById('hero-sakura-canvas');

  // Skip Hero sakura on mobile (too heavy)
  if (heroCanvas && !isMobile) {
    initHeroSakura(heroCanvas);
  }

  // Initialize section sakura everywhere
  document.querySelectorAll('.sakura-canvas').forEach(function (canvas) {
    initSectionSakura(canvas);
  });

  /* ─────────────────────────────────────────────────────────
     HERO SAKURA (Three.js) — reduced particles
  ───────────────────────────────────────────────────────── */
  function initHeroSakura(canvas) {
    var renderer = new THREE.WebGLRenderer({ canvas: canvas, alpha: true, antialias: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setClearColor(0x000000, 0);

    var scene = new THREE.Scene();
    var camera = new THREE.PerspectiveCamera(60, 1, 0.1, 200);
    camera.position.z = 30;

    var W = 0, H = 0;
    function resize() {
      var rect = canvas.parentElement.getBoundingClientRect();
      W = rect.width; H = rect.height;
      renderer.setSize(W, H);
      camera.aspect = W / H;
      camera.updateProjectionMatrix();
      canvas.style.width = W + 'px';
      canvas.style.height = H + 'px';
    }
    resize();
    window.addEventListener('resize', resize, { passive: true });

    // Petal geometry
    function makePetalGeo() {
      var shape = new THREE.Shape();
      shape.moveTo(0, 0);
      shape.bezierCurveTo(0.3, 0.5, 0.6, 0.8, 0, 1.2);
      shape.bezierCurveTo(-0.6, 0.8, -0.3, 0.5, 0, 0);
      return new THREE.ShapeGeometry(shape);
    }

    var petalGeo = makePetalGeo();
    var petalColors = [0xffb7c5, 0xffc0cb, 0xffaabb, 0xffe4e8, 0xff8fa3];
    var MAX_PETALS = isMobile ? 20 : 60; // Reduced from 80
    var petals = [];

    function createPetal(fromMouse, mx, my) {
      var mat = new THREE.MeshBasicMaterial({
        color: petalColors[Math.floor(Math.random() * petalColors.length)],
        side: THREE.DoubleSide,
        transparent: true,
        opacity: 0,
      });
      var mesh = new THREE.Mesh(petalGeo, mat);
      var startX = fromMouse
        ? (mx / W * 2 - 1) * (W / 60) + (Math.random() - 0.5) * 3
        : (Math.random() - 0.5) * (W / 28);
      var startY = fromMouse
        ? -(my / H * 2 - 1) * (H / 60) + (Math.random() - 0.5) * 3
        : H / 80 + Math.random() * 3;
      mesh.position.set(startX, startY, (Math.random() - 0.5) * 10);
      var s = 0.15 + Math.random() * 0.35;
      mesh.scale.set(s, s, s);
      mesh.rotation.z = Math.random() * Math.PI * 2;
      scene.add(mesh);
      return {
        mesh: mesh,
        vx: (Math.random() - 0.5) * 0.06,
        vy: -(0.04 + Math.random() * 0.08),
        vz: (Math.random() - 0.5) * 0.02,
        rotX: (Math.random() - 0.5) * 0.04,
        rotY: (Math.random() - 0.5) * 0.04,
        rotZ: (Math.random() - 0.5) * 0.03,
        wind: (Math.random() - 0.5) * 0.003,
        life: 0,
        maxLife: 180 + Math.random() * 120,
        fadeDuration: 30,
        alive: true,
        fromMouse: fromMouse,
      };
    }

    // Initial ambient petals
    for (var i = 0; i < (isMobile ? 8 : 25); i++) {
      var p = createPetal();
      p.life = Math.random() * p.maxLife;
      petals.push(p);
    }

    // Mouse — only on desktop, passive
    var mouseX3D = 0, mouseY3D = 0;
    var mouseOver = false;
    var spawnTimer = 0;
    var parent = canvas.parentElement;

    if (!isMobile) {
      parent.addEventListener('mousemove', function (e) {
        var rect = canvas.getBoundingClientRect();
        mouseX3D = e.clientX - rect.left;
        mouseY3D = e.clientY - rect.top;
        mouseOver = true;
      }, { passive: true });
      parent.addEventListener('mouseleave', function () { mouseOver = false; }, { passive: true });
    }

    var clock = new THREE.Clock();
    var active = true;
    var animationId = null;
    
    var observer = new IntersectionObserver(function(entries) {
      active = entries[0].isIntersecting;
      if (active && !animationId) {
        clock.getElapsedTime(); // Prevent huge jump
        animate();
      }
    }, { threshold: 0.0 });
    observer.observe(canvas.parentElement);

    function animate() {
      if (!active) {
        animationId = null;
        return;
      }
      animationId = requestAnimationFrame(animate);
      
      var time = clock.getElapsedTime();
      spawnTimer++;

      // Ambient spawn (slower on mobile)
      var spawnRate = isMobile ? 15 : 8;
      if (spawnTimer % spawnRate === 0 && petals.length < MAX_PETALS) {
        petals.push(createPetal());
      }

      // Mouse-triggered (only desktop)
      if (!isMobile && mouseOver && spawnTimer % 4 === 0 && petals.length < MAX_PETALS) {
        petals.push(createPetal(true, mouseX3D, mouseY3D));
      }

      for (var j = petals.length - 1; j >= 0; j--) {
        var p = petals[j];
        p.life++;
        p.vx += p.wind * Math.sin(time * 0.5 + j);
        p.vx *= 0.995;
        p.mesh.position.x += p.vx;
        p.mesh.position.y += p.vy;
        p.mesh.position.z += p.vz;
        p.mesh.rotation.x += p.rotX;
        p.mesh.rotation.y += p.rotY;
        p.mesh.rotation.z += p.rotZ;
        p.vy -= 0.001;
        var fadeIn = Math.min(p.life / p.fadeDuration, 1);
        var fadeOut = Math.max(0, 1 - (p.life - (p.maxLife - p.fadeDuration)) / p.fadeDuration);
        p.mesh.material.opacity = Math.min(fadeIn, fadeOut) * (0.6 + Math.random() * 0.1);
        if (p.life > p.maxLife) {
          scene.remove(p.mesh);
          p.mesh.material.dispose();
          petals.splice(j, 1);
        }
      }
      renderer.render(scene, camera);
    }
    animate();
  }

  /* ─────────────────────────────────────────────────────────
     SECTION SAKURA (Canvas 2D — lightweight)
  ───────────────────────────────────────────────────────── */
  function initSectionSakura(canvas) {
    var ctx = canvas.getContext('2d');
    var W, H;
    var petals = [];
    var active = false;
    var frame = 0;

    function resize() {
      var rect = canvas.parentElement.getBoundingClientRect();
      W = rect.width || window.innerWidth;
      H = rect.height || 600;
      canvas.width = W;
      canvas.height = H;
      canvas.style.width = W + 'px';
      canvas.style.height = H + 'px';
    }
    resize();
    window.addEventListener('resize', resize, { passive: true });

    var animationId = null;
    var observer = new IntersectionObserver(function (entries) {
      active = entries[0].isIntersecting;
      if (active && !animationId) {
        animatePetals();
      }
    }, { threshold: 0.1 });
    observer.observe(canvas.parentElement);

    // Mouse — reduced spawn rate, passive
    canvas.parentElement.addEventListener('mousemove', function (e) {
      if (Math.random() > 0.7) spawnPetal(e.offsetX, e.offsetY, true);
    }, { passive: true });

    function spawnPetal(x, y, fromMouse) {
      petals.push({
        x: x !== undefined ? x : Math.random() * W,
        y: y !== undefined ? y : -10,
        vx: (Math.random() - 0.5) * 1.5,
        vy: fromMouse ? (-1 - Math.random() * 2) : (0.6 + Math.random() * 1.2),
        rotation: Math.random() * Math.PI * 2,
        rotSpeed: (Math.random() - 0.5) * 0.08,
        life: 1.0,
        decay: fromMouse ? 0.008 : 0.004,
        w: 6 + Math.random() * 8,
        h: 4 + Math.random() * 5,
        color: 'hsl(' + (340 + Math.random() * 25) + ', 80%, ' + (70 + Math.random() * 15) + '%)',
        swing: Math.random() * Math.PI * 2,
        swingSpeed: 0.02 + Math.random() * 0.03,
      });
    }

    function drawPetal(p) {
      ctx.save();
      ctx.translate(p.x, p.y);
      ctx.rotate(p.rotation);
      ctx.globalAlpha = p.life * 0.7;
      ctx.fillStyle = p.color;
      ctx.beginPath();
      ctx.ellipse(0, 0, p.w * 0.5, p.h * 0.5, 0, 0, Math.PI * 2);
      ctx.fill();
      ctx.restore();
    }

    function animatePetals() {
      if (!active) {
        animationId = null;
        return;
      }
      animationId = requestAnimationFrame(animatePetals);
      frame++;
      ctx.clearRect(0, 0, W, H);

      var isTablet = window.innerWidth < 1024 && window.innerWidth >= 768;
      var isMobileCheck = window.innerWidth < 768;
      var MAX_PETALS = isMobileCheck ? 8 : (isTablet ? 15 : 25);
      var SPAWN_RATE = isMobileCheck ? 40 : (isTablet ? 30 : 20);

      if (frame % SPAWN_RATE === 0 && petals.length < MAX_PETALS) {
        spawnPetal();
      }

      for (var i = petals.length - 1; i >= 0; i--) {
        var p = petals[i];
        p.swing += p.swingSpeed;
        p.vx += Math.sin(p.swing) * 0.02;
        p.x += p.vx;
        p.y += p.vy;
        p.rotation += p.rotSpeed;
        p.life -= p.decay;
        if (p.y > H + 20 || p.life <= 0) { petals.splice(i, 1); continue; }
        drawPetal(p);
      }
    }

    animatePetals();
  }

  /* ─────────────────────────────────────────────────────────
     INTERACTIVE SAKURA (Hover/Touch trigger, auto-decay)
  ───────────────────────────────────────────────────────── */
  function initInteractiveSakura(canvas, section) {
    var ctx = canvas.getContext('2d');
    var W, H;
    var petals = [];
    var frame = 0;
    var isHovering = false;
    var animationId = null;

    function resize() {
      var rect = section.getBoundingClientRect();
      W = rect.width || window.innerWidth;
      H = rect.height || 600;
      canvas.width = W;
      canvas.height = H;
    }
    resize();
    window.addEventListener('resize', resize, { passive: true });

    function spawnPetal(x, y, fromMouse) {
      petals.push({
        x: x !== undefined ? x : Math.random() * W,
        y: y !== undefined ? y : -20 + Math.random() * 20,
        vx: (Math.random() - 0.5) * 1.5,
        vy: fromMouse ? (-1 - Math.random() * 2) : (1 + Math.random() * 1.5),
        rotation: Math.random() * Math.PI * 2,
        rotSpeed: (Math.random() - 0.5) * 0.08,
        life: 1.0,
        decay: fromMouse ? 0.008 : (0.002 + Math.random() * 0.002), // 6-15s lifespan
        w: 6 + Math.random() * 8,
        h: 4 + Math.random() * 5,
        color: 'hsl(' + (340 + Math.random() * 25) + ', 80%, ' + (70 + Math.random() * 15) + '%)',
        swing: Math.random() * Math.PI * 2,
        swingSpeed: 0.02 + Math.random() * 0.03,
      });
    }

    function drawPetal(p) {
      ctx.save();
      ctx.translate(p.x, p.y);
      ctx.rotate(p.rotation);
      ctx.globalAlpha = p.life * 0.7;
      ctx.fillStyle = p.color;
      ctx.beginPath();
      ctx.ellipse(0, 0, p.w * 0.5, p.h * 0.5, 0, 0, Math.PI * 2);
      ctx.fill();
      ctx.restore();
    }

    function animatePetals() {
      frame++;
      ctx.clearRect(0, 0, W, H);

      // Ambient spawn only while hovering
      if (isHovering && frame % 15 === 0 && petals.length < 50) {
        spawnPetal();
      }

      for (var i = petals.length - 1; i >= 0; i--) {
        var p = petals[i];
        p.swing += p.swingSpeed;
        p.vx += Math.sin(p.swing) * 0.02;
        p.x += p.vx;
        p.y += p.vy;
        p.rotation += p.rotSpeed;
        p.life -= p.decay;
        
        // Remove dead/fallen petals
        if (p.y > H + 20 || p.life <= 0) { petals.splice(i, 1); continue; }
        drawPetal(p);
      }

      // Stop engine if completely idle
      if (petals.length > 0 || isHovering) {
        animationId = requestAnimationFrame(animatePetals);
      } else {
        animationId = null;
      }
    }

    function triggerBurst() {
      // Spawn 15-20 petals instantly
      var count = 15 + Math.floor(Math.random() * 5);
      for(var i=0; i<count; i++) {
        spawnPetal(Math.random() * W, Math.random() * (H * 0.3));
      }
      if (!animationId) animatePetals();
    }

    // --- Desktop Interactions ---
    section.addEventListener('mouseenter', function() {
      isHovering = true;
      triggerBurst();
    }, { passive: true });

    section.addEventListener('mousemove', function(e) {
      if (!isHovering) return;
      if (Math.random() > 0.85 && petals.length < 60) {
        var rect = section.getBoundingClientRect();
        spawnPetal(e.clientX - rect.left, e.clientY - rect.top, true);
      }
    }, { passive: true });

    section.addEventListener('mouseleave', function() {
      isHovering = false;
    }, { passive: true });

    // --- Mobile Interactions ---
    section.addEventListener('touchstart', function() {
      if (!isHovering) {
        isHovering = true;
        triggerBurst();
        // Auto-fade since mobile lacks clean 'mouseleave'
        setTimeout(function() { isHovering = false; }, 1500);
      }
    }, { passive: true });
  }

  // Initialize interactive canvases safely
  document.querySelectorAll('.interactive-leaf-canvas').forEach(function (canvas) {
    var section = canvas.closest('section');
    if (section) {
      // Only init if not on low-end mobile to save battery
      initInteractiveSakura(canvas, section);
    }
  });

})();
