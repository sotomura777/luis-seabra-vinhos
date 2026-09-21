/* Catálogo de vinhos: filtros por região + overlay da ficha técnica.
 * A grelha é renderizada no servidor (SEO); os cartões ligam à página do vinho.
 * Com JS, o clique abre o overlay em vez de navegar (progressive enhancement). */
(function () {
  'use strict';

  var dataEl = document.getElementById('lsv-wines');
  var sheet = document.getElementById('sheet');
  var grid = document.getElementById('wgrid');
  if (!dataEl || !sheet || !grid) return;

  var W = [];
  try { W = JSON.parse(dataEl.textContent) || []; } catch (e) { return; }

  var cur = 0;
  var q = function (s) { return sheet.querySelector(s); };

  function open(i) {
    var w = W[i];
    if (!w) return;
    cur = i;
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
    // acento por vinho
    if (w.tom) { sheet.style.setProperty('--tom', w.tom); }
    sheet.classList.add('on');
    document.body.classList.add('lsv-sheet-open');
    document.body.style.overflow = 'hidden';
  }
  function close() { sheet.classList.remove('on'); document.body.classList.remove('lsv-sheet-open'); document.body.style.overflow = ''; }
  function step(d) { open((cur + d + W.length) % W.length); }

  // Cartões → abrir overlay (sem navegar)
  grid.querySelectorAll('.wcard').forEach(function (card) {
    card.addEventListener('click', function (e) {
      e.preventDefault();
      open(parseInt(card.dataset.idx, 10) || 0);
    });
  });

  // Filtros por região
  var fbtns = document.querySelectorAll('.filters button');
  fbtns.forEach(function (b) {
    b.addEventListener('click', function () {
      var f = b.dataset.f;
      fbtns.forEach(function (x) { x.setAttribute('aria-pressed', x === b ? 'true' : 'false'); });
      grid.querySelectorAll('.wcard').forEach(function (c) {
        c.style.display = (f === 'all' || c.dataset.region === f) ? '' : 'none';
      });
    });
  });

  // Botões do overlay
  sheet.querySelectorAll('[data-step]').forEach(function (b) {
    b.addEventListener('click', function () { step(parseInt(b.dataset.step, 10)); });
  });
  var cl = sheet.querySelector('[data-close]');
  if (cl) cl.addEventListener('click', close);

  document.addEventListener('keydown', function (e) {
    if (!sheet.classList.contains('on')) return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowRight') step(1);
    if (e.key === 'ArrowLeft') step(-1);
  });
})();
