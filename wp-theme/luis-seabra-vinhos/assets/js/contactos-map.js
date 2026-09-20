/**
 * Mapa de contactos (Leaflet) — portado do protótipo.
 * Lê os locais de .pl[data-ll]; pinos + fly-to no hover. Tiles OSM (pedido externo → nota RGPD).
 */
(function () {
  const el = document.getElementById('lmap');
  if (el && window.L) {
    const map = L.map(el, { zoomControl:false, scrollWheelZoom:false, dragging:false, doubleClickZoom:false, attributionControl:true });
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution:'© OpenStreetMap', maxZoom:19 }).addTo(map);
    const pts = [];
    const wrap = el.parentElement.querySelector('.wrap');
    const pad = () => ({ paddingTopLeft:[40,110], paddingBottomRight:[40, (wrap ? wrap.offsetHeight : 240) + 110] });
    document.querySelectorAll('.pl[data-ll]').forEach(c => {
      const ll = c.dataset.ll.split(',').map(Number); pts.push(ll);
      const m = L.marker(ll, { icon: L.divIcon({ className:'', html:'<div class="pin-dot"></div>', iconSize:[12,12], iconAnchor:[6,6] }) }).addTo(map);
      m.bindTooltip(c.querySelector('h3').textContent, { permanent:true, direction:'top', offset:[0,-14], className:'pin-lab' });
      c.addEventListener('mouseenter', () => map.flyTo(ll, 10, { duration:1.2 }));
      c.addEventListener('mouseleave', () => map.flyToBounds(L.latLngBounds(pts), Object.assign({ duration:1.2 }, pad())));
    });
    if (pts.length) {
      map.fitBounds(L.latLngBounds(pts), pad());
      window.addEventListener('resize', () => map.fitBounds(L.latLngBounds(pts), pad()));
    }
  }

  // Floating labels dos campos do formulário.
  document.querySelectorAll('.f input,.f textarea').forEach(el => {
    const f = el.closest('.f');
    const s = () => f.classList.toggle('has', !!el.value);
    el.addEventListener('input', s); el.addEventListener('blur', s); s();
  });
})();
