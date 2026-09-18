/* Carrossel de garrafas em coverflow 3D — arrastar/swipe, sem dependências.
 * Cada .lsv-carousel na página é independente (um por região).
 * Enriquece os slides estáticos de .lsv-deck: posiciona em perspetiva, trata do
 * arrasto (rato + toque) com inércia e actualiza a legenda.
 * Isolado do runtime do hero: só mexe no DOM do carrossel. */
(function () {
  'use strict';

  function initOne(root) {
    if (root.dataset.ready) return;
    root.dataset.ready = '1';

    var deck = root.querySelector('.lsv-deck');
    var caption = root.querySelector('.lsv-caption');
    if (!deck) return;

    var view = Array.prototype.slice.call(deck.querySelectorAll('.lsv-slide'));
    var single = view.length <= 1;
    var pos = 0, target = 0;
    var slideW = 200, slideH = 480, spacing = 150;
    var raf = null;

    function measure() {
      var w = root.clientWidth || window.innerWidth;
      slideW = Math.max(150, Math.min(w * 0.44, 230));
      slideH = Math.max(320, Math.min(window.innerHeight * 0.6, 560));
      spacing = slideW * 0.88;                 // mais afastamento entre garrafas
      deck.style.height = slideH + 'px';
      view.forEach(function (s) { s.style.width = slideW + 'px'; s.style.height = slideH + 'px'; s.style.marginLeft = (-slideW / 2) + 'px'; });
    }

    function render() {
      for (var i = 0; i < view.length; i++) {
        var s = view[i];
        var o = i - pos;
        var ao = Math.abs(o);
        if (ao > 3.2) { s.style.opacity = '0'; s.style.pointerEvents = 'none'; s.style.visibility = 'hidden'; continue; }
        s.style.visibility = 'visible';
        var x = o * spacing;
        var z = -ao * 140;
        var rot = Math.max(-46, Math.min(46, -o * 30));
        // centro bem maior que as laterais: a garrafa ao centro "cresce"
        var scale = Math.max(0.5, 1 - ao * 0.27);
        var op = Math.max(0, 1 - ao * 0.42);
        s.style.transform = 'translateX(' + x.toFixed(1) + 'px) translateZ(' + z.toFixed(1) + 'px) rotateY(' + rot.toFixed(1) + 'deg) scale(' + scale.toFixed(3) + ')';
        s.style.opacity = op.toFixed(2);
        s.style.zIndex = String(1000 - Math.round(ao * 10));
        s.style.pointerEvents = 'auto';
        s.classList.toggle('is-active', Math.round(pos) === i);
      }
      updateCaption();
    }

    function updateCaption() {
      if (!caption || !view.length) return;
      var idx = Math.max(0, Math.min(view.length - 1, Math.round(pos)));
      var d = view[idx].dataset;
      var set = function (sel, val) { var el = caption.querySelector(sel); if (el) el.textContent = val || ''; };
      set('.lsv-c-name', d.name);
      set('.lsv-c-sub', d.sub);
      set('.lsv-c-desc', d.desc);
      set('.lsv-c-price', d.price);
    }

    function animate() {
      if (raf) return;
      var step = function () {
        pos += (target - pos) * 0.18;
        if (Math.abs(target - pos) < 0.0008) { pos = target; render(); raf = null; return; }
        render();
        raf = requestAnimationFrame(step);
      };
      raf = requestAnimationFrame(step);
    }
    function stopAnim() { if (raf) { cancelAnimationFrame(raf); raf = null; } }
    function goTo(i) { target = Math.max(0, Math.min(view.length - 1, i)); animate(); }

    // arrastar / swipe
    var dragging = false, startX = 0, startPos = 0, lastX = 0, lastT = 0, vel = 0, moved = false;
    if (!single) {
      deck.addEventListener('pointerdown', function (e) {
        dragging = true; moved = false; stopAnim();
        startX = lastX = e.clientX; startPos = pos; lastT = e.timeStamp || Date.now(); vel = 0;
        try { deck.setPointerCapture(e.pointerId); } catch (x) {}
        deck.classList.add('is-dragging');
      });
      deck.addEventListener('pointermove', function (e) {
        if (!dragging) return;
        var dx = e.clientX - startX;
        if (Math.abs(dx) > 3) moved = true;
        pos = Math.max(-0.4, Math.min(view.length - 0.6, startPos - dx / spacing));
        var now = e.timeStamp || Date.now(), dt = now - lastT;
        if (dt > 0) vel = (e.clientX - lastX) / dt;
        lastX = e.clientX; lastT = now;
        render();
      });
      var release = function () {
        if (!dragging) return;
        dragging = false; deck.classList.remove('is-dragging');
        var projected = pos - (vel * 140) / spacing;
        target = Math.max(0, Math.min(view.length - 1, Math.round(projected)));
        animate();
      };
      deck.addEventListener('pointerup', release);
      deck.addEventListener('pointercancel', release);
      deck.addEventListener('dragstart', function (e) { e.preventDefault(); });

      deck.addEventListener('wheel', function (e) {
        if (Math.abs(e.deltaX) < Math.abs(e.deltaY)) return;
        e.preventDefault();
        goTo(Math.round(target) + (e.deltaX > 0 ? 1 : -1));
      }, { passive: false });

      document.addEventListener('keydown', function (e) {
        if (e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') return;
        var r = root.getBoundingClientRect();
        if (r.bottom < 80 || r.top > window.innerHeight - 80) return;
        goTo(Math.round(target) + (e.key === 'ArrowRight' ? 1 : -1));
      });
    }

    // clicar num slide lateral centra-o
    view.forEach(function (s, i) {
      s.addEventListener('click', function () { if (!moved) goTo(i); });
    });

    // botões prev / next
    var prev = root.querySelector('.lsv-prev'), next = root.querySelector('.lsv-next');
    if (single) { if (prev) prev.style.display = 'none'; if (next) next.style.display = 'none'; }
    if (prev) prev.addEventListener('click', function () { goTo(Math.round(target) - 1); });
    if (next) next.addEventListener('click', function () { goTo(Math.round(target) + 1); });

    // "Adicionar" (só feedback, não há carrinho real)
    var addBtn = caption && caption.querySelector('.lsv-c-add');
    if (addBtn) addBtn.addEventListener('click', function () {
      var en = document.documentElement.lang === 'en';
      var was = addBtn.textContent;
      addBtn.textContent = en ? 'Added' : 'No cesto';
      setTimeout(function () { addBtn.textContent = was; }, 1500);
    });

    window.addEventListener('resize', function () { measure(); render(); });
    measure();
    render();
  }

  function initAll() {
    injectStyles();
    Array.prototype.forEach.call(document.querySelectorAll('.lsv-carousel'), initOne);
  }

  function injectStyles() {
    if (document.getElementById('lsv-carousel-css')) return;
    var css =
      '.lsv-stage{position:relative;display:flex;align-items:center;justify-content:center;perspective:1700px;padding:10px 0 6px;overflow:hidden}' +
      '.lsv-deck{position:relative;width:100%;transform-style:preserve-3d;touch-action:pan-y;cursor:grab;user-select:none}' +
      '.lsv-deck.is-dragging{cursor:grabbing}' +
      '.lsv-carousel[data-single] .lsv-deck{cursor:default}' +
      '.lsv-slide{position:absolute;top:0;left:50%;display:flex;align-items:flex-end;justify-content:center;will-change:transform,opacity;backface-visibility:hidden}' +
      '.lsv-slide img{width:100%;height:100%;object-fit:contain;object-position:bottom center;pointer-events:none;filter:drop-shadow(0 26px 34px rgba(0,0,0,.18))}' +
      '.lsv-nav{position:absolute;top:50%;transform:translateY(-50%);z-index:1200;width:46px;height:46px;border:1px solid #E4E2DF;background:rgba(255,255,255,.7);backdrop-filter:blur(4px);border-radius:50%;font-size:22px;line-height:1;color:#000;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s,border-color .2s}' +
      '.lsv-nav:hover{background:#000;color:#fff;border-color:#000}' +
      '.lsv-prev{left:clamp(6px,3vw,32px)}.lsv-next{right:clamp(6px,3vw,32px)}' +
      '.lsv-caption{max-width:520px;margin:clamp(18px,3vh,34px) auto 0;text-align:center;display:flex;flex-direction:column;gap:10px;align-items:center;min-height:140px}' +
      '.lsv-c-name{margin:0;font-size:clamp(21px,2.6vw,28px);font-weight:400;letter-spacing:.06em;text-transform:uppercase}' +
      '.lsv-c-sub{font-size:10px;letter-spacing:.28em;text-transform:uppercase;color:#6E6E6E}' +
      '.lsv-c-desc{margin:2px 0 0;font-size:14.5px;line-height:1.75;color:#4A4A4A;max-width:44ch}' +
      '.lsv-c-foot{display:flex;align-items:center;gap:22px;margin-top:8px}' +
      '.lsv-c-price{font-size:16px}' +
      '.lsv-c-add{background:none;border:1px solid #D9D6D2;color:#000;font:inherit;font-size:10.5px;letter-spacing:.22em;text-transform:uppercase;padding:12px 20px;cursor:pointer;transition:background .2s,color .2s,border-color .2s}' +
      '.lsv-c-add:hover{background:#000;color:#fff;border-color:#000}' +
      '@media (max-width:640px){.lsv-nav{width:40px;height:40px;font-size:19px}}';
    var st = document.createElement('style');
    st.id = 'lsv-carousel-css';
    st.textContent = css;
    document.head.appendChild(st);
  }

  // O conteúdo é injetado pelo motor DC depois do load, e pode ser re-renderizado
  // (ex.: troca de idioma). Observamos o DOM e (re)inicializamos os carrosséis que
  // apareçam — o guard data-ready evita inicializar duas vezes o mesmo nó.
  var scheduled = false, mo = null;
  function boot() {
    scheduled = false;
    if (document.querySelector('.lsv-carousel')) {
      initAll();
      if (mo) { mo.disconnect(); mo = null; }   // nós estáveis: não precisamos de observar mais
    }
  }
  function schedule() { if (!scheduled) { scheduled = true; requestAnimationFrame(boot); } }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', schedule);
  schedule();
  mo = new MutationObserver(schedule);
  mo.observe(document.documentElement, { childList: true, subtree: true });
})();
