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
  function initHero() {
    var a = document.getElementById('lsv-vid-a');
    var b = document.getElementById('lsv-vid-b');
    var pin = document.getElementById('top');
    var head = document.getElementById('lsv-head');
    var name = document.getElementById('lsv-name');
    var prog = document.getElementById('lsv-prog');
    var caps = [1, 2, 3].map(function (i) { return document.getElementById('lsv-cap-' + i); });
    if (!a || !b || !pin) return;

    // desbloqueio do seek: alguns browsers só deixam mexer no currentTime
    // depois de um play() (mesmo mudo). Tocar e pausar de imediato.
    [a, b].forEach(function (v) {
      v.muted = true; v.pause();
      var p = v.play();
      if (p && p.then) p.then(function () { v.pause(); v.currentTime = 0; }).catch(function () {});
    });

    var dur = function (v) { return (isFinite(v.duration) && v.duration > 0 ? v.duration : 6); };
    var clamp = function (x, lo, hi) { return Math.max(lo, Math.min(hi, x)); };
    var band = function (p, s, e, f) { return clamp(Math.min((p - s) / f, (e - p) / f), 0, 1); };

    var seekTo = function (v, T) {
      if (v.readyState < 1 || v.seeking) return;
      var t = clamp(T, 0, dur(v));
      if (Math.abs(t - v.currentTime) > 0.01) v.currentTime = t;
    };

    var HAND = 0.48;
    var apply = function (p) {
      var onB = p >= HAND;
      var pa = clamp(p / HAND, 0, 1);
      var pb = clamp((p - HAND) / (1 - HAND), 0, 1);
      if (!a.paused) a.pause();
      if (!b.paused) b.pause();
      if (onB) {
        seekTo(b, pb * (dur(b) - 0.02));
        seekTo(a, dur(a) - 0.02);
      } else {
        seekTo(a, pa * (dur(a) - 0.02));
      }
      a.style.opacity = onB ? '0' : '1';
      b.style.opacity = onB ? '1' : '0';
      if (prog) prog.style.width = (p * 100).toFixed(2) + '%';
      if (name) {
        var o = clamp(1 - p / 0.09, 0, 1);
        name.style.opacity = String(o);
        name.style.transform = 'translateY(' + (-p * 90).toFixed(1) + 'px)';
      }
      if (caps[0]) caps[0].style.opacity = String(band(p, 0.16, 0.40, 0.06));
      if (caps[1]) caps[1].style.opacity = String(band(p, 0.46, 0.68, 0.06));
      if (caps[2]) caps[2].style.opacity = String(band(p, 0.76, 1.1, 0.06));
      if (head) {
        var light = window.scrollY > pin.offsetTop + pin.offsetHeight - 110;
        head.style.color = light ? '#000000' : '#FFFFFF';
        head.style.backgroundColor = light ? 'rgba(255,255,255,.92)' : 'transparent';
        head.style.backdropFilter = light ? 'blur(6px)' : 'none';
      }
    };

    var pos = 0, target = 0, lastT = 0;
    var step = function (nowT) {
      var dt = lastT ? Math.min(0.05, (nowT - lastT) / 1000) : 0.016;
      lastT = nowT;
      pos += (target - pos) * (1 - Math.exp(-dt * 12));
      if (Math.abs(target - pos) < 0.0004) pos = target;
      apply(pos);
    };
    var readTarget = function () {
      var r = pin.getBoundingClientRect();
      var span = pin.offsetHeight - window.innerHeight;
      target = clamp(-r.top / (span > 0 ? span : 1), 0, 1);
    };

    window.addEventListener('scroll', function () { readTarget(); step(performance.now()); }, { passive: true });
    readTarget(); pos = target; apply(pos);

    var tick = function () { step(performance.now()); requestAnimationFrame(tick); };
    requestAnimationFrame(tick);
  }

  function boot() { initMenu(); initReveal(); initChapterIndex(); initHero(); }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
})();
