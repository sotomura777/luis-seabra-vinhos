/**
 * Mapa 3D das regiões (Douro · Dão · Vinho Verde) — portado do protótipo.
 * Auto-contido: constrói o "muro" 3D, pinos das parcelas e troca o painel ao clicar.
 * JSON e URL do catálogo vêm do WordPress via window.LSV_MAP.
 */
(function () {
  var CFG = window.LSV_MAP || {};
  var VINHOS = CFG.vinhosUrl || '#';
  // Textos traduzidos pelo WordPress (Idiomas › Traduções); sem tradução fica o original em PT.
  var T = function (s) { return (CFG.i18n && CFG.i18n[s]) || s; };

  const C = { douro:'#B99B6B', dao:'#8C6A4A', vv:'#A8A296' };
  const R = {
    douro:{ key:'douro', name:'Douro', n:'01', color:C.douro,
      title:T('Xisto micáceo, dos 400 aos 700 metros'),
      text:T("O Douro é uma das mais antigas regiões vinícolas demarcadas do mundo, com uma história de viticultura que remonta ao século XVIII. Os vinhos aqui nascem de encostas dramáticas, talhadas em xisto, onde a vinha desafia a gravidade e o clima se exprime com intensidade. É uma região de contrastes – entre altitudes, exposições solares e microclimas – que oferece um terroir complexo e vibrante. Na Luis Seabra Vinhos, acreditamos que o Douro não é apenas monumental – é subtil. Procuramos vinhos que traduzam a origem, respeitando a singularidade de cada parcela, sem maquilhagem. É aqui que as castas autóctones encontram a sua expressão mais autêntica – em brancos de notável frescura e profundidade, e tintos com estrutura, precisão e grande capacidade de envelhecimento."),
      facts:[[T('Solo'),T('Xisto micáceo e de transição')],[T('Altitude'),T('400 – 700 m')],[T('Vinhas'),T('Plantadas entre 1920 e 1933')],[T('Condução'),T('Cachos inteiros, pouca extração')]],
      wines:[T('Xisto Cru Tinto'),T('Xisto Cru Branco'),T('Xisto Ilimitado'),T('Indie Xisto')] },
    dao:{ key:'dao', name:'Dão', n:'02', color:C.dao,
      title:T('Granito à sombra da Serra da Estrela'),
      text:T("O Dão é uma das regiões mais antigas e distintas de Portugal, encravada entre serras – Estrela, Caramulo e Buçaco – esta região beneficia de um clima continental moderado, com noites frescas e solos graníticos que promovem vinhos equilibrados, estruturados e longevos. Trabalhar no Dão é explorar a harmonia entre natureza e tradição. As vinhas antigas, muitas vezes em campo misto, dão origem a vinhos que revelam um lado mais contido, fresco e subtil. É essa expressão serena mas profunda que procuramos preservar, vinificando com respeito e parcimónia, deixando que o lugar fale mais alto do que a mão do enólogo."),
      facts:[[T('Solo'),T('Granito')],[T('Lugar'),T('Vila Nova de Tazém, Gouveia')],[T('Castas'),T('Encruzado, Bical, Cercial')],[T('Vinhas'),T('Mais de 35 anos')]],
      wines:[T('Granito Cru Branco')] },
    vv:{ key:'vv', name:'Vinho Verde', n:'03', color:C.vv,
      title:T('Um só vinho, em Monção e Melgaço'),
      text:T("A região dos Vinhos Verdes estende-se pelo noroeste de Portugal, onde o verde da paisagem encontra a influência constante do Atlântico. É uma terra de contrastes, marcada por chuvas generosas, encostas soalheiras e solos graníticos que conferem identidade e frescura aos vinhos. Aqui a tradição convive com a diversidade, permitindo múltiplas expressões da mesma origem. Entre as várias sub-regiões, Monção e Melgaço distinguem-se. O seu microclima, protegido pelas serras e beneficiado por noites frescas, oferece condições únicas para vinhos de grande intensidade, precisão e longevidade. Nos Vinhos Verdes trabalhamos para revelar esta pluralidade: vinhos leves e vibrantes, vinhos mais densos e profundos – todos com a frescura como fio condutor. Procuramos respeitar o caráter próprio de cada lugar, deixando que seja a região, em toda a sua diversidade, a falar através de cada garrafa."),
      facts:[[T('Solo'),T('Granito')],[T('Sub-região'),T('Monção e Melgaço')],[T('Casta'),T('Alvarinho')],[T('Estágio'),T('Foudre')]],
      wines:[T('Granito Cru Alvarinho')] }
  };
  const PINS = [
    { r:'douro', n:'Alvites', c:[-7.2494,41.4389], ox: 13, oy:-5 },
    { r:'douro', n:'Alijó',   c:[-7.4744,41.2758], ox:-13, oy: 10, a:'end' },
    { r:'douro', n:'Meda',    c:[-7.2620,40.9660], ox:-13, oy: 13, a:'end' },
    { r:'dao',   n:'Vila Nova de Tazém', c:[-7.6833,40.5333], ox: 12, oy:-9 },
    { r:'vv',    n:'Melgaço', c:[-8.2543,42.1147], ox: 13, oy:-7 },
    { r:'vv',    n:'Monção',  c:[-8.4822,42.0783], ox:-13, oy: 9, a:'end' }
  ];

  const W = 600, H = 880, LIFT = 10, RLIFT = 7, PLIFT = 4;
  const svg = d3.select('#map');
  const projection = d3.geoMercator();
  const path = d3.geoPath(projection);
  let active = null, regionSel, parcelSel, pins = [];

  const shade = (c, k) => d3.color(c).darker(k).formatHex();

  d3.json(CFG.mapUrl).then(fc => {
    const country = fc.features.find(f => f.properties.id === 'pt');
    const regions = fc.features.filter(f => ['douro','dao','vv'].includes(f.properties.id));
    const parcels = fc.features.filter(f => f.properties.kind === 'parcel');

    projection.fitExtent([[56, 56 + LIFT + RLIFT], [W - 56, H - 56 - LIFT]], country);

    const defs = svg.append('defs');
    const blur = defs.append('filter').attr('id','soft').attr('x','-35%').attr('y','-35%').attr('width','170%').attr('height','170%');
    blur.append('feGaussianBlur').attr('stdDeviation','13');
    const face = defs.append('linearGradient').attr('id','face').attr('x1','0').attr('y1','0').attr('x2','.4').attr('y2','1');
    face.append('stop').attr('offset','0').attr('stop-color','#26262A');
    face.append('stop').attr('offset','1').attr('stop-color','#171719');

    svg.append('path').datum(country).attr('d', path).attr('fill','#6B6154').attr('opacity',.26)
       .attr('transform', 'translate(7,' + (LIFT + 12) + ')').attr('filter','url(#soft)');

    const wall = svg.append('g').attr('aria-hidden','true');
    for (let i = LIFT; i >= 1; i--) {
      wall.append('path').datum(country).attr('d', path)
        .attr('fill', d3.interpolateLab('#2A2A2E','#0A0A0B')(i / LIFT))
        .attr('transform', 'translate(0,' + i + ')');
    }
    svg.append('path').datum(country).attr('d', path).attr('fill','url(#face)');

    const rGroups = svg.append('g').selectAll('g').data(regions).join('g')
      .attr('class', d => 'rgroup rg-' + d.properties.id).style('cursor','pointer')
      .on('click', (e,d) => select(d.properties.id))
      .on('mouseenter', function(e,d){ if (active !== d.properties.id) d3.select(this).attr('opacity',.88); })
      .on('mouseleave', function(){ d3.select(this).attr('opacity', 1); });

    rGroups.each(function(d){
      const g = d3.select(this), col = C[d.properties.id];
      for (let i = 0; i <= RLIFT; i++) {
        g.append('path').datum(d).attr('d', path).attr('class','rwall')
          .attr('fill', d3.interpolateLab(shade(col,1.5), shade(col,.55))(i / RLIFT))
          .attr('transform', 'translate(0,' + (-i) + ')');
      }
      g.append('path').datum(d).attr('d', path).attr('class','region')
        .attr('fill', col).attr('fill-opacity',.82)
        .attr('transform', 'translate(0,' + (-RLIFT) + ')');
    });
    regionSel = svg.selectAll('.region');

    const pGroups = svg.append('g').selectAll('g').data(parcels).join('g')
      .attr('class', d => 'pgroup pg-' + d.properties.region).style('cursor','pointer')
      .on('click', (e,d) => select(d.properties.region));
    pGroups.each(function(d){
      const g = d3.select(this), col = C[d.properties.region];
      for (let i = 0; i <= PLIFT; i++) {
        g.append('path').datum(d).attr('d', path)
          .attr('fill', shade(col, 1.2))
          .attr('transform', 'translate(0,' + (-RLIFT - i) + ')');
      }
      g.append('path').datum(d).attr('d', path).attr('class','parcel')
        .attr('fill', col).attr('stroke','#FDFBF6').attr('stroke-opacity',.85)
        .attr('transform', 'translate(0,' + (-RLIFT - PLIFT) + ')');
    });
    parcelSel = svg.selectAll('.parcel');

    const pg = svg.append('g');
    pins = PINS.map(p => {
      const [x, y] = projection(p.c);
      const g = pg.append('g').attr('class','pin p-' + p.r).style('cursor','pointer').on('click', () => select(p.r));
      const tick = g.append('line').attr('class','tick');
      const dot = g.append('circle').attr('class','pindot').attr('fill','#FDFBF6').attr('stroke','#2A2621');
      const txt = g.append('text').attr('class','plabel').attr('text-anchor', p.a || 'start').text(p.n);
      return { p, x, y: y - RLIFT - PLIFT, tick, dot, txt };
    });

    layout();
    window.addEventListener('resize', layout);
    paint();
    render(null);
  });

  function layout(){
    const r = svg.node().getBoundingClientRect();
    const s = Math.min(r.width / W, r.height / H) || 1;
    const u = v => v / s;
    pins.forEach(o => {
      const { p, x, y } = o;
      o.tick.attr('x1', x).attr('y1', y).attr('x2', x + u(p.ox > 0 ? p.ox - 4 : p.ox + 4)).attr('y2', y + u(p.oy - 4))
        .attr('stroke-width', u(1));
      o.dot.attr('cx', x).attr('cy', y).attr('r', u(3.4)).attr('stroke-width', u(1.5));
      o.txt.attr('x', x + u(p.ox)).attr('y', y + u(p.oy)).attr('font-size', u(14)).attr('stroke-width', u(3.6));
    });
  }

  function paint(){
    d3.selectAll('.rgroup').attr('opacity', function(){
      if (!active) return 1;
      return this.classList.contains('rg-' + active) ? 1 : .34;
    });
    d3.selectAll('.pgroup').attr('opacity', function(){
      if (!active) return 1;
      return this.classList.contains('pg-' + active) ? 1 : .3;
    });
    d3.selectAll('.pin').attr('opacity', function(){
      if (!active) return 1;
      return this.classList.contains('p-' + active) ? 1 : .25;
    });
    const cap = document.getElementById('mapcap');
    if (cap) cap.innerHTML = active
      ? '<span class="nm" style="color:' + C[active] + '">' + R[active].name + '</span><span class="sub">' + R[active].n + ' — ' + (active === 'douro' ? T('21 concelhos') : active === 'dao' ? T('17 concelhos') : 'Monção e Melgaço') + '</span>'
      : '<span class="nm">Portugal</span><span class="sub">' + T('Três regiões') + '</span>';
    document.querySelectorAll('.key').forEach(k => k.setAttribute('aria-current', String(k.dataset.go === active)));
  }
  function select(key){ active = (active === key) ? null : key; paint(); render(active ? R[active] : null); }

  function intro(){
    return `<h1 class="serif h2">${T('Xisto e granito')}<i>${T('Três regiões · oito hectares')}</i></h1>
      <p>${T('As vinhas não foram escolhidas por região, mas por solo. Xisto micáceo no Douro, granito no Dão e no vale do Minho — as mesmas castas dariam vinhos diferentes em cada um deles.')}</p>
      <p>${T('Carregue numa região do mapa. As manchas mais fortes são os concelhos onde estão as vinhas.')}</p>`;
  }
  function body(reg){
    return `<span class="eyebrow" style="color:${reg.color}"><i style="background:${reg.color}"></i>${reg.n} — ${reg.name}</span>
      <h1 class="serif h2">${reg.name}<i>${reg.title}</i></h1>
      <p>${reg.text}</p>
      <dl class="facts">${reg.facts.map(f=>`<div><dt>${f[0]}</dt><dd>${f[1]}</dd></div>`).join('')}</dl>
      <div class="wines">${reg.wines.map(w=>`<a href="${VINHOS}">${w}</a>`).join('')}</div>`;
  }
  function render(reg){
    const p = document.getElementById('panel');
    p.innerHTML = `<div class="fade">${reg ? body(reg) : intro()}
      <div class="switch">
        ${Object.values(R).map(r=>`<button type="button" data-go="${r.key}"><i style="background:${r.color}"></i>${r.name}</button>`).join('')}
        <button type="button" data-go="all">${T('Ver tudo')}</button>
      </div></div>`;
    p.querySelectorAll('.switch button').forEach(b => {
      b.setAttribute('aria-current', String(!!reg && b.dataset.go === reg.key));
      b.addEventListener('click', () => { if (b.dataset.go === 'all') { active = null; paint(); render(null); } else select(b.dataset.go); });
    });
  }
  document.querySelectorAll('.key').forEach(k => k.addEventListener('click', () => select(k.dataset.go)));
  document.querySelectorAll('.prow[data-go]').forEach(r => r.addEventListener('click', () => {
    if (active !== r.dataset.go) select(r.dataset.go);
    window.scrollTo({ top:0, behavior:'smooth' });
  }));
})();
