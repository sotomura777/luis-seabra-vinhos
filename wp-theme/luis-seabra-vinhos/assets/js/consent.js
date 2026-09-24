/* Aviso de cookies: o Google Analytics só é descarregado depois de "Aceitar".
 * A escolha fica 6 meses no cookie lsv_consent ("granted|<data>" ou "denied|<data>"). */
(function () {
  'use strict';
  var CFG = window.LSV_CONSENT || {};
  var box = document.getElementById('lsv-consent');
  if (!CFG.ga || !box) return;

  var NAME = 'lsv_consent', MAX_DAYS = 183;

  function read() {
    var m = document.cookie.match(/(?:^|; )lsv_consent=([^;]*)/);
    if (!m) return '';
    var p = decodeURIComponent(m[1]).split('|');
    var age = (Date.now() - Number(p[1] || 0)) / 864e5;
    return age < MAX_DAYS ? p[0] : ''; // expirado → volta a perguntar
  }
  function save(v) {
    document.cookie = NAME + '=' + encodeURIComponent(v + '|' + Date.now()) +
      '; max-age=' + MAX_DAYS * 86400 + '; path=/; SameSite=Lax' + (location.protocol === 'https:' ? '; Secure' : '');
  }
  function loadGA() {
    if (window.__lsvGA) return;
    window.__lsvGA = true;
    window.dataLayer = window.dataLayer || [];
    window.gtag = function () { window.dataLayer.push(arguments); };
    window.gtag('consent', 'default', { analytics_storage: 'granted', ad_storage: 'denied', ad_user_data: 'denied', ad_personalization: 'denied' });
    window.gtag('js', new Date());
    window.gtag('config', CFG.ga);
    var s = document.createElement('script');
    s.async = true;
    s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(CFG.ga);
    document.head.appendChild(s);
  }
  function dropGACookies() {
    document.cookie.split('; ').forEach(function (c) {
      var n = c.split('=')[0];
      if (/^_ga/.test(n)) {
        var host = location.hostname.split('.').slice(-2).join('.');
        ['', '; domain=' + location.hostname, '; domain=.' + host].forEach(function (d) {
          document.cookie = n + '=; max-age=0; path=/' + d;
        });
      }
    });
  }
  function show() { box.hidden = false; }
  function hide() { box.hidden = true; }

  box.addEventListener('click', function (e) {
    var b = e.target.closest('[data-consent]');
    if (!b) return;
    var v = b.getAttribute('data-consent');
    var before = read();
    save(v);
    hide();
    if (v === 'granted') loadGA();
    else if (before === 'granted') { dropGACookies(); location.reload(); } // descarrega o GA já carregado
  });

  // Link "Preferências de cookies" no rodapé.
  document.addEventListener('click', function (e) {
    if (e.target.closest('[data-lsv-cookies]')) { e.preventDefault(); show(); box.querySelector('button').focus(); }
  });

  var state = read();
  if (state === 'granted') loadGA();
  else {
    dropGACookies(); // limpa restos (o GA ainda ativo pode reescrevê-los ao sair da página)
    if (!state) show();
  }
})();
