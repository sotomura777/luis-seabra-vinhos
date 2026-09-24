<?php
/**
 * Template Name: Regiões
 * Mapa 3D interativo (D3) + painel de região + tabela de parcelas.
 * O D3 e o regioes-map.js são enfileirados condicionalmente em inc/enqueue.php.
 *
 * @package luisseabra
 */

get_header();
?>

<style>
.stage{display:grid;grid-template-columns:repeat(auto-fit,minmax(340px,1fr));align-items:stretch;min-height:100svh}
.mapcol{position:sticky;top:0;height:100svh;display:flex;flex-direction:column;gap:clamp(8px,1.4vh,16px);padding:clamp(88px,12vh,110px) clamp(16px,3vw,44px) clamp(18px,3vh,30px)}
svg#map{display:block;width:100%;flex:1 1 auto;min-height:0;overflow:visible}
.mapcap{order:-1;flex:0 0 auto;display:flex;align-items:baseline;gap:12px;pointer-events:none}
.mapcap .nm{font-family:var(--serif);font-size:clamp(30px,3.4vw,46px);line-height:1;letter-spacing:.02em}
.mapcap .sub{font-size:9.5px;letter-spacing:.3em;text-transform:uppercase;color:var(--gold)}
.mapfoot{flex:0 0 auto;display:flex;flex-wrap:wrap;gap:8px 18px;align-items:center}
.key{display:inline-flex;align-items:center;gap:8px;font-size:9.5px;letter-spacing:.24em;text-transform:uppercase;color:var(--muted);cursor:pointer}
.key i{width:11px;height:11px;border-radius:2px;display:block}
.key[aria-current="true"]{color:var(--ink)}
.region{cursor:pointer;transition:opacity .4s ease}
.parcel{cursor:pointer}
.plabel{letter-spacing:.2em;text-transform:uppercase;fill:#E9E6E0;pointer-events:none;paint-order:stroke;stroke:#0D0D0E;stroke-linejoin:round;stroke-opacity:.92}
.tick{stroke:#8F8779}
.rgroup,.pgroup,.pin{transition:opacity .4s ease}
.panel{position:relative;background:var(--panel,#141416);border-left:1px solid var(--line,rgba(233,230,224,.12));box-shadow:-24px 0 60px rgba(0,0,0,.35);padding:clamp(92px,13vh,150px) clamp(24px,4vw,64px) clamp(40px,7vh,80px);display:flex;flex-direction:column;justify-content:center}
.panel .eyebrow{display:flex;align-items:center;gap:10px;font-size:10px;letter-spacing:.3em;text-transform:uppercase}
.panel .eyebrow i{width:10px;height:10px;border-radius:50%;display:block}
.panel h1{margin:18px 0 0;line-height:.98}
.panel h2{margin:16px 0 0;line-height:1.32}
.panel p{margin:20px 0 0;max-width:46ch;line-height:1.85}
.facts{margin:30px 0 0;display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:20px 26px;border-top:1px solid var(--line,rgba(233,230,224,.12));padding-top:24px}
.facts dt{letter-spacing:.26em;text-transform:uppercase}
.facts dd{margin:9px 0 0;line-height:1.5}
.wines{margin:26px 0 0;display:flex;flex-wrap:wrap;gap:8px}
.wines a{border:1px solid var(--line,rgba(233,230,224,.12));padding:10px 14px;font-size:10px;letter-spacing:.2em;text-transform:uppercase}
.switch{display:flex;flex-wrap:wrap;gap:8px;margin:34px 0 0;border-top:1px solid var(--line,rgba(233,230,224,.12));padding-top:24px}
.switch button{background:none;border:1px solid var(--line,rgba(233,230,224,.12));font:inherit;letter-spacing:.22em;text-transform:uppercase;padding:11px 15px;cursor:pointer;display:inline-flex;align-items:center;gap:9px}
.switch button i{width:8px;height:8px;border-radius:50%;display:block}
.fade{animation:fin .5s ease both}
@keyframes fin{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
.below{padding:clamp(60px,10vh,120px) clamp(22px,6vw,90px);max-width:1400px;margin:0 auto}
.prow{display:grid;grid-template-columns:minmax(0,1.1fr) minmax(0,.9fr) minmax(0,1fr) minmax(0,.9fr);gap:18px;align-items:baseline;padding:clamp(15px,2.2vh,22px) 0;border-top:1px solid var(--line,rgba(233,230,224,.12));font-size:15px;cursor:pointer}
.prow:last-child{border-bottom:1px solid var(--line,rgba(233,230,224,.12))}
.prow:hover{background:rgba(255,255,255,.025)}
.prow .nm{display:flex;align-items:center;gap:11px;font-size:17px}
.prow .nm i{width:8px;height:8px;border-radius:50%;display:block}
:root{--douro:#B99B6B;--dao:#8C6A4A;--vv:#A8A296}
</style>

<main>
	<div class="stage">
		<div class="mapcol">
			<svg id="map" viewBox="0 0 600 880" role="img" aria-label="<?php echo esc_attr( pll__( 'Mapa de Portugal continental com as regiões do Douro, Dão e Vinho Verde.' ) ); ?>"></svg>
			<div class="mapcap" id="mapcap"></div>
			<div class="mapfoot">
				<span class="key" data-go="douro"><i style="background:var(--douro)"></i>Douro</span>
				<span class="key" data-go="dao"><i style="background:var(--dao)"></i>Dão</span>
				<span class="key" data-go="vv"><i style="background:var(--vv)"></i>Vinho Verde</span>
			</div>
		</div>
		<aside class="panel" id="panel"></aside>
	</div>

	<div class="below">
		<span class="lbl"><?php pll_e( 'As parcelas' ); ?></span>
		<div style="margin-top:clamp(22px,4vh,38px)">
			<div class="prow" style="border-top:none;cursor:default"><span class="lbl"><?php pll_e( 'Lugar' ); ?></span><span class="lbl"><?php pll_e( 'Região' ); ?></span><span class="lbl"><?php pll_e( 'Solo' ); ?></span><span class="lbl"><?php pll_e( 'Vinho' ); ?></span></div>
			<div class="prow" data-go="douro"><span class="nm"><i style="background:var(--douro)"></i>Meda</span><span class="mt muted">Douro Superior</span><span class="mt muted"><?php echo esc_html( pll__( 'Xisto micáceo, 650–700 m' ) ); ?></span><span class="mt muted"><?php echo esc_html( pll__( 'Xisto Cru Branco' ) ); ?></span></div>
			<div class="prow" data-go="douro"><span class="nm"><i style="background:var(--douro)"></i>Alvites</span><span class="mt muted">Mirandela</span><span class="mt muted"><?php echo esc_html( pll__( 'Xisto' ) ); ?></span><span class="mt muted">Xisto Cru</span></div>
			<div class="prow" data-go="douro"><span class="nm"><i style="background:var(--douro)"></i>Alijó</span><span class="mt muted">Douro</span><span class="mt muted"><?php echo esc_html( pll__( 'Xisto' ) ); ?></span><span class="mt muted">Indie Xisto</span></div>
			<div class="prow" data-go="dao"><span class="nm"><i style="background:var(--dao)"></i>Vila Nova de Tazém</span><span class="mt muted">Gouveia, Dão</span><span class="mt muted"><?php echo esc_html( pll__( 'Granito' ) ); ?></span><span class="mt muted"><?php echo esc_html( pll__( 'Granito Cru Branco' ) ); ?></span></div>
			<div class="prow" data-go="vv"><span class="nm"><i style="background:var(--vv)"></i>Melgaço</span><span class="mt muted">Monção e Melgaço</span><span class="mt muted"><?php echo esc_html( pll__( 'Granito' ) ); ?></span><span class="mt muted">Granito Cru Alvarinho</span></div>
		</div>
	</div>
</main>

<?php
get_footer();
