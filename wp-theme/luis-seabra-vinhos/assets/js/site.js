/* Luís Seabra Vinhos — interações de front-end (portadas do runtime DC).
 * (1) Menu overlay abrir/fechar. (2) Scrubber do hero em vídeo.
 * O carrossel 3D vive em carousel.js (auto-inicializa). */
(function () {
  'use strict';

  // ---------------------------------------------------------------------------
  // Menu overlay
  // ---------------------------------------------------------------------------
  // Cortina de menu: foto de fundo ao passar o rato (data-img) + Escape fecha.
  // Abrir/fechar é feito por onclick inline no header (add/remove classe 'on').
  function initMenu() {
    var menu = document.getElementById('lsv-menu');
    if (!menu) return;
    var bg = menu.querySelector('.bgimg');
    menu.querySelectorAll('.list a').forEach(function (a) {
      a.addEventListener('mouseenter', function () {
        if (bg && a.dataset.img) { bg.style.backgroundImage = 'url(' + a.dataset.img + ')'; bg.style.opacity = '.5'; }
      });
      a.addEventListener('mouseleave', function () { if (bg) bg.style.opacity = '0'; });
    });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') menu.classList.remove('on'); });
  }

  // Fade-in ao entrar no viewport (o CSS anima via classe .in).
  function initReveal() {
    var els = document.querySelectorAll('.tl-row, .reveal');
    if (!els.length || !('IntersectionObserver' in window)) {
      els.forEach(function (el) { el.classList.add('in'); });
      return;
    }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
    }, { threshold: 0.2 });
    els.forEach(function (el) { io.observe(el); });
  }

  // Índice de capítulos da landing (#lsv-index): destaca a secção à vista.
  function initChapterIndex() {
    var index = document.getElementById('lsv-index');
    if (!index || !('IntersectionObserver' in window)) return;
    var links = index.querySelectorAll('a[data-ix]');
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (!e.isIntersecting) return;
        links.forEach(function (l) { l.style.opacity = (l.getAttribute('data-ix') === e.target.id) ? '1' : '.45'; });
      });
    }, { threshold: 0.5 });
    links.forEach(function (l) {
      var sec = document.getElementById(l.getAttribute('data-ix'));
      if (sec) io.observe(sec);
    });
  }

  // ---------------------------------------------------------------------------
  // Hero scrubber — dois vídeos all-intra, scrubbing pela posição do scroll.
  // Idêntico ao site estático: seek por evento de scroll + rAF, sem estados
  // presos, com o "unlock" de autoplay mudo necessário ao seek.
  // ---------------------------------------------------------------------------
  // Scrub por SEQUÊNCIA DE IMAGENS desenhadas num <canvas> (~0ms/frame),
  // em vez de saltar num <video> (seek caro → encrava, ainda pior via túnel).
  function initHero() {
    var pin = document.getElementById('top');
    var stage = pin ? pin.firstElementChild : null;
    var canvas = document.getElementById('lsv-canvas');
    var head = document.getElementById('lsv-head');
    var name = document.getElementById('lsv-name');
    var prog = document.getElementById('lsv-prog');
    var caps = [1, 2, 3].map(function (i) { return document.getElementById('lsv-cap-' + i); });
    if (!pin || !canvas) return;
    var ctx = canvas.getContext('2d');
    if (!ctx) return;

    var BASE = window.LSV_HERO_BASE || 'assets/hero/';
    var clamp = function (x, lo, hi) { return Math.max(lo, Math.min(hi, x)); };
    var band = function (p, s, e, f) { return clamp(Math.min((p - s) / f, (e - p) / f), 0, 1); };
    var smooth = function (t) { return t * t * (3 - 2 * t); };
    var pad3 = function (n) { return ('00' + n).slice(-3); };

    // A = a garrafa aparece, B = o copo enche
    var SRC_W = 640, SRC_H = 854, HAND = 0.46, CF = 0.03, NA = 60, NB = 80;
    var A = new Array(NA), B = new Array(NB);
    var urlA = function (i) { return BASE + 'a-' + pad3(i + 1) + '.jpg'; };
    var urlB = function (i) { return BASE + 'b-' + pad3(i + 1) + '.jpg'; };

    var loadedAny = false, headLight = null, pinBottom = 0;
    var posv = 0, target = 0, lastT = 0, running = false;
    var stepN = 1, buildW = SRC_W, buildH = SRC_H, builtW = 0, fails = 0, jobs = [], ji = 0, rzT;

    var computeBuild = function () {
      var cw = stage ? stage.clientWidth : window.innerWidth;
      var ch = stage ? stage.clientHeight : window.innerHeight;
      stepN = cw < 700 ? 2 : 1;
      buildW = Math.round(clamp(Math.min(cw, ch * SRC_W / SRC_H), 340, SRC_W));
      buildH = Math.round(buildW * SRC_H / SRC_W);
    };
    var resizeCanvas = function () {
      var dpr = Math.min(window.devicePixelRatio || 1, 2);
      var cw = stage ? stage.clientWidth : window.innerWidth;
      var ch = stage ? stage.clientHeight : window.innerHeight;
      canvas.width = Math.max(1, Math.round(cw * dpr));
      canvas.height = Math.max(1, Math.round(ch * dpr));
    };
    computeBuild(); resizeCanvas();

    var drawFit = function (bmp, alpha) {
      if (!bmp) return;
      var s = Math.min(canvas.width / bmp.width, canvas.height / bmp.height);
      var w = bmp.width * s, h = bmp.height * s;
      ctx.globalAlpha = alpha;
      ctx.drawImage(bmp, (canvas.width - w) / 2, (canvas.height - h) / 2, w, h);
      ctx.globalAlpha = 1;
    };
    var pick = function (arr, idx) {
      idx = clamp(idx, 0, arr.length - 1);
      if (arr[idx]) return arr[idx];
      for (var d = 1; d < arr.length; d++) {
        if (arr[idx - d]) return arr[idx - d];
        if (arr[idx + d]) return arr[idx + d];
      }
      return null;
    };
    var drawFrame = function (p) {
      if (!loadedAny) return;
      ctx.fillStyle = '#000000';
      ctx.fillRect(0, 0, canvas.width, canvas.height);
      var ai = Math.round(clamp(p / HAND, 0, 1) * (NA - 1));
      var bi = Math.round(clamp((p - HAND) / (1 - HAND), 0, 1) * (NB - 1));
      var bAlpha = smooth(clamp((p - (HAND - CF)) / (2 * CF), 0, 1));
      if (bAlpha < 1) drawFit(pick(A, ai), 1);
      if (bAlpha > 0) drawFit(pick(B, bi), bAlpha);
    };
    var overlays = function (p) {
      if (prog) prog.style.width = (p * 100).toFixed(2) + '%';
      if (name) {
        name.style.opacity = String(clamp(1 - p / 0.09, 0, 1));
        name.style.transform = 'translateY(' + (-p * 90).toFixed(1) + 'px)';
      }
      if (caps[0]) caps[0].style.opacity = String(band(p, 0.16, 0.40, 0.06));
      if (caps[1]) caps[1].style.opacity = String(band(p, 0.46, 0.68, 0.06));
      if (caps[2]) caps[2].style.opacity = String(band(p, 0.76, 1.1, 0.06));
      if (head) {
        var light = window.scrollY > pinBottom;
        if (light !== headLight) {
          headLight = light;
          head.style.color = light ? '#000000' : '#FFFFFF';
          head.style.backgroundColor = light ? 'rgba(255,255,255,.92)' : 'transparent';
          head.style.backdropFilter = light ? 'blur(6px)' : 'none';
        }
      }
    };
    var apply = function (p) { drawFrame(p); overlays(p); };

    // decodifica com downscale garantido (Safari ignora resize do createImageBitmap)
    var canBitmap = typeof createImageBitmap === 'function';
    var decode = function (blob) {
      if (buildW >= SRC_W) return createImageBitmap(blob);
      return createImageBitmap(blob).then(function (big) {
        var c = document.createElement('canvas');
        c.width = buildW; c.height = buildH;
        c.getContext('2d').drawImage(big, 0, 0, buildW, buildH);
        if (big.close) big.close();
        return createImageBitmap(c);
      });
    };
    var loadInto = function (arr, idx, url) {
      return fetch(url).then(function (r) { return r.blob(); }).then(decode).then(function (bmp) {
        var old = arr[idx]; arr[idx] = bmp; if (old && old.close) old.close();
        loadedAny = true;
        if (!running) apply(posv);
      }).catch(function () { fails++; });
    };
    var pump = function () { if (ji >= jobs.length || fails > 12) return; var j = jobs[ji++]; loadInto(j[0], j[1], j[2]).then(pump); };
    var startLoading = function () {
      builtW = buildW;
      jobs = [[A, 0, urlA(0)], [A, NA - 1, urlA(NA - 1)], [B, 0, urlB(0)], [B, NB - 1, urlB(NB - 1)]];
      for (var i = 0; i < NA; i += stepN) jobs.push([A, i, urlA(i)]);
      for (var k = 0; k < NB; k += stepN) jobs.push([B, k, urlB(k)]);
      ji = 0; fails = 0;
      for (var m = 0; m < 6; m++) pump();
    };
    if (canBitmap) startLoading();

    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var tick = function (nowT) {
      var dt = lastT ? Math.min(0.05, (nowT - lastT) / 1000) : 0.016;
      lastT = nowT;
      if (reduce) { posv = target; apply(posv); running = false; return; }
      posv += (target - posv) * (1 - Math.exp(-dt * 11));
      if (Math.abs(target - posv) > 0.0006) { apply(posv); requestAnimationFrame(tick); }
      else { posv = target; apply(posv); running = false; }
    };
    var kick = function () { if (!running && !reduce) { running = true; lastT = 0; requestAnimationFrame(tick); } };

    var readTarget = function () {
      var r = pin.getBoundingClientRect();
      var span = r.height - window.innerHeight;
      target = clamp(-r.top / (span > 0 ? span : 1), 0, 1);
      pinBottom = window.scrollY + r.top + r.height - 110;
    };
    window.addEventListener('scroll', function () { readTarget(); if (reduce) { posv = target; apply(posv); } else kick(); }, { passive: true });
    window.addEventListener('resize', function () {
      resizeCanvas(); apply(posv);
      clearTimeout(rzT);
      rzT = setTimeout(function () { computeBuild(); resizeCanvas(); if (canBitmap && buildW > builtW + 8) startLoading(); apply(posv); }, 200);
    }, { passive: true });
    readTarget(); apply(target);
  }

  // Páginas interiores: o header transparente esconde-se ao descer (para não
  // ficar por cima do texto) e volta ao subir. A landing tem lógica própria no hero.
  function initHeadHide() {
    var head = document.getElementById('lsv-head');
    if (!head || document.body.classList.contains('home')) return;
    var lastY = window.scrollY, ticking = false;
    var update = function () {
      var y = window.scrollY, dy = y - lastY;
      if (y < 80 || dy < -6) head.classList.remove('lsv-hide');
      else if (dy > 6) head.classList.add('lsv-hide');
      if (Math.abs(dy) > 6 || y < 80) lastY = y;
      ticking = false;
    };
    window.addEventListener('scroll', function () {
      if (!ticking) { ticking = true; requestAnimationFrame(update); }
    }, { passive: true });
  }

  // Imprensa: o leitor do Spotify (que põe cookies) só é carregado quando o visitante carrega no botão.
  function initSpotify() {
    document.addEventListener('click', function (e) {
      var b = e.target.closest('.lsv-spotify button');
      if (!b) return;
      var box = b.parentNode, f = document.createElement('iframe');
      f.src = box.getAttribute('data-src');
      f.title = box.getAttribute('data-title') || 'Spotify';
      f.width = '100%'; f.height = '152';
      f.setAttribute('allow', 'autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture');
      f.style.cssText = 'border:0; border-radius:12px; display:block';
      box.replaceChildren(f);
    });
  }

  function boot() { initMenu(); initReveal(); initChapterIndex(); initHero(); initHeadHide(); initSpotify(); }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
})();
