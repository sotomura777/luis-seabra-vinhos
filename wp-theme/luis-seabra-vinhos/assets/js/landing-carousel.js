/* Gama da landing: carrossel coverflow de todos os vinhos, com separadores por
 * região. A garrafa central em foco; clicar nela (ou "Ver ficha") abre a ficha
 * no overlay #sheet. Setas ‹ › mudam de garrafa. */
(function () {
  'use strict';

  var section = document.getElementById('gama');
  var deck = document.getElementById('lc-deck');
  var dataEl = document.getElementById('lsv-wines');
  var sheet = document.getElementById('sheet');
  if (!section || !deck || !dataEl) return;

  var ALL = [];
  try { ALL = JSON.parse(dataEl.textContent) || []; } catch (e) { return; }

  var bottles = Array.prototype.slice.call(deck.querySelectorAll('.lc-bottle'));
  var stage = section.querySelector('.lc-stage');
  var cap = section.querySelector('.lc-cap');
  var elName = cap.querySelector('.lc-name');
  var elMeta = cap.querySelector('.lc-meta');
  var elDesc = cap.querySelector('.lc-desc');

  var filter = 'all';
  var order = [];  // índices (em ALL) visíveis no filtro atual
  var active = 0;  // posição dentro de order

  function computeOrder() {
    order = [];
    for (var i = 0; i < ALL.length; i++) {
      if (filter === 'all' || (bottles[i] && bottles[i].dataset.region === filter)) order.push(i);
    }
  }

  function layout() {
    var n = order.length;
    bottles.forEach(function (b) { b.style.opacity = '0'; b.style.pointerEvents = 'none'; b.style.zIndex = '0'; });
    order.forEach(function (gi, pos) {
      var b = bottles[gi];
      var off = pos - active;
      if (n > 1) { if (off > n / 2) off -= n; if (off < -n / 2) off += n; }
      var ao = Math.abs(off), vis = ao <= 2;
      b.style.transform = 'translate(-50%,-50%) translateX(' + (off * 62) + '%) scale(' + (1 - ao * 0.16) + ')';
      b.style.opacity = vis ? String(1 - ao * 0.38) : '0';
      b.style.zIndex = String(10 - ao);
      b.style.pointerEvents = vis ? 'auto' : 'none';
    });
    updateCap();
  }

  function updateCap() {
    var w = ALL[order[active]];
    if (!w) return;
    elName.textContent = w.nome || '';
    elMeta.textContent = [w.regiao, w.sub, w.ano].filter(Boolean).join(' · ');
    elDesc.textContent = w.castas || w.texto || '';
  }

  function pick(pos) { if (!order.length) return; active = ((pos % order.length) + order.length) % order.length; layout(); }
  function setFilter(f) { filter = f; computeOrder(); active = 0; layout(); }

  bottles.forEach(function (b) {
    b.addEventListener('click', function () {
      var gi = parseInt(b.dataset.idx, 10);
      var pos = order.indexOf(gi);
      if (pos < 0) return;
      if (pos === active) openFicha(gi); else pick(pos);
    });
  });

  stage.querySelector('.lc-prev').addEventListener('click', function () { pick(active - 1); });
  stage.querySelector('.lc-next').addEventListener('click', function () { pick(active + 1); });

  section.querySelectorAll('.lc-tabs button').forEach(function (t) {
    t.addEventListener('click', function () {
      section.querySelectorAll('.lc-tabs button').forEach(function (x) { x.setAttribute('aria-pressed', x === t ? 'true' : 'false'); });
      setFilter(t.dataset.region);
    });
  });

  var openBtn = cap.querySelector('.lc-open');
  if (openBtn) openBtn.addEventListener('click', function () { openFicha(order[active]); });

  /* ---- Overlay da ficha (#sheet) ---- */
  var q = sheet ? function (s) { return sheet.querySelector(s); } : function () { return null; };
  var sheetPos = 0;

  function openFicha(gi) {
    if (!sheet || typeof gi !== 'number') return;
    var w = ALL[gi];
    if (!w) return;
    sheetPos = order.indexOf(gi);
    if (w.img) {
      q('.bg').style.backgroundImage = "url('" + w.img + "')";
      q('.photo').style.backgroundImage = "url('" + w.img + "')";
    }
    q('.ey').textContent = [w.regiao, w.ano].filter(Boolean).join(' · ');
    q('h2').textContent = w.nome || '';
    q('.sub').textContent = w.sub || '';
    q('.tx').textContent = w.texto || '';
    q('.ficha').innerHTML = (w.ficha || []).map(function (f) {
      return '<div><span>' + f[0] + '</span><span>' + f[1] + '</span></div>';
    }).join('');
    var pdf = q('.pdf');
    if (w.pdf) { pdf.href = w.pdf; pdf.style.display = ''; } else { pdf.style.display = 'none'; }
    if (w.tom) { sheet.style.setProperty('--tom', w.tom); }
    sheet.classList.add('on');
    document.body.classList.add('lsv-sheet-open');
    document.body.style.overflow = 'hidden';
  }
  function closeFicha() { if (!sheet) return; sheet.classList.remove('on'); document.body.classList.remove('lsv-sheet-open'); document.body.style.overflow = ''; }
  function stepFicha(d) {
    if (!order.length) return;
    sheetPos = (sheetPos + d + order.length) % order.length;
    active = sheetPos;
    openFicha(order[sheetPos]);
    layout();
  }

  if (sheet) {
    sheet.querySelectorAll('[data-step]').forEach(function (b) {
      b.addEventListener('click', function () { stepFicha(parseInt(b.dataset.step, 10)); });
    });
    var cl = sheet.querySelector('[data-close]');
    if (cl) cl.addEventListener('click', closeFicha);
    document.addEventListener('keydown', function (e) {
      if (!sheet.classList.contains('on')) return;
      if (e.key === 'Escape') closeFicha();
      if (e.key === 'ArrowRight') stepFicha(1);
      if (e.key === 'ArrowLeft') stepFicha(-1);
    });
  }

  setFilter('all');
})();
