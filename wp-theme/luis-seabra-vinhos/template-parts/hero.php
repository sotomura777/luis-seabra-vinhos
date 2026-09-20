<?php
/**
 * Hero — vídeo com scroll (scrubbing). IDs preservados para o site.js.
 *
 * @package luisseabra
 */

$cap1 = lsv_setting( 'hero_cap1', 'Uma vinha de 80 anos, plantada em xisto micáceo a 600 metros.' );
$cap2 = lsv_setting( 'hero_cap2', 'Fermentação espontânea, cachos inteiros, extração mínima.' );
$cap3 = lsv_setting( 'hero_cap3', 'Framboesa, cereja ácida, pedra molhada. 12% de álcool.' );
?>
<section id="top" data-screen-label="Hero vídeo" style="position:relative; height:560vh; background:#000000">
	<div class="lsv-hero-sticky" style="position:sticky; top:0; height:100svh; overflow:hidden; background:#000000">
		<video id="lsv-vid-a" src="<?php echo esc_url( LSV_URI . '/assets/videos/video-6.mp4' ); ?>" muted playsinline preload="auto" style="position:absolute; inset:0; width:100%; height:100%; object-fit:contain; opacity:1"></video>
		<video id="lsv-vid-b" src="<?php echo esc_url( LSV_URI . '/assets/videos/video-5.mp4' ); ?>" muted playsinline preload="auto" style="position:absolute; inset:0; width:100%; height:100%; object-fit:contain; opacity:0"></video>

		<div id="lsv-name" style="position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:clamp(14px,2.6vh,26px); text-align:center; color:#FFFFFF; padding:0 clamp(20px,5vw,60px); pointer-events:none">
			<span style="font-size:clamp(26px,6.4vw,74px); letter-spacing:.24em; text-transform:uppercase; font-weight:300; line-height:1.15; padding-left:.24em">Luís Seabra</span>
			<span style="font-size:clamp(12px,2.1vw,24px); letter-spacing:.66em; text-transform:uppercase; font-weight:200; opacity:.75; padding-left:.66em"><?php pll_e( 'Vinhos' ); ?></span>
			<span style="margin-top:clamp(10px,2vh,22px); font-size:clamp(10px,1.15vw,12px); letter-spacing:.34em; text-transform:uppercase; opacity:.9"><?php pll_e( 'Douro · Dão · Vinho Verde — desde 2013' ); ?></span>
			<span style="display:flex; flex-direction:column; align-items:center; gap:8px; margin-top:clamp(16px,4vh,44px); animation:lsvCue 2.4s ease-in-out infinite">
				<span style="font-size:9.5px; letter-spacing:.32em; text-transform:uppercase"><?php pll_e( 'Desça' ); ?></span>
				<span style="width:1px; height:40px; background:linear-gradient(180deg,rgba(255,255,255,.8),rgba(255,255,255,0))"></span>
			</span>
		</div>

		<div style="position:absolute; inset:0; pointer-events:none; background:linear-gradient(0deg, rgba(0,0,0,.72) 0%, rgba(0,0,0,0) 38%)"></div>
		<div style="position:absolute; inset:auto 0 0 0; display:flex; padding:0 clamp(20px,6vw,90px) clamp(46px,9vh,96px)">
			<div style="position:relative; width:100%; min-height:clamp(140px,24vh,220px)">
				<div id="lsv-cap-1" style="position:absolute; inset:auto auto 0 0; max-width:min(520px,88vw); color:#FFFFFF; opacity:0">
					<span style="font-size:10px; letter-spacing:.3em; text-transform:uppercase; opacity:.55">Xisto Cru 2023 · Douro</span>
					<p style="margin:14px 0 0; font-size:clamp(22px,3.1vw,38px); line-height:1.24; font-weight:200; letter-spacing:.005em"><?php echo esc_html( $cap1 ); ?></p>
				</div>
				<div id="lsv-cap-2" style="position:absolute; inset:auto auto 0 0; max-width:min(520px,88vw); color:#FFFFFF; opacity:0">
					<span style="font-size:10px; letter-spacing:.3em; text-transform:uppercase; opacity:.55">Adega</span>
					<p style="margin:14px 0 0; font-size:clamp(22px,3.1vw,38px); line-height:1.24; font-weight:200; letter-spacing:.005em"><?php echo esc_html( $cap2 ); ?></p>
				</div>
				<div id="lsv-cap-3" style="position:absolute; inset:auto auto 0 0; max-width:min(520px,88vw); color:#FFFFFF; opacity:0">
					<span style="font-size:10px; letter-spacing:.3em; text-transform:uppercase; opacity:.55">No copo</span>
					<p style="margin:14px 0 18px; font-size:clamp(22px,3.1vw,38px); line-height:1.24; font-weight:200; letter-spacing:.005em"><?php echo esc_html( $cap3 ); ?></p>
					<a href="#gama" class="lsv-cta" style="display:inline-flex; align-items:center; gap:14px; border:1px solid rgba(255,255,255,.5); padding:13px 22px; font-size:11px; letter-spacing:.24em; text-transform:uppercase; color:#FFFFFF">
						<span>Comprar Xisto Cru</span>
					</a>
				</div>
			</div>
		</div>
		<div style="position:absolute; bottom:0; left:0; right:0; height:1px; background:rgba(255,255,255,.18)">
			<div id="lsv-prog" style="height:100%; width:0%; background:#D9C39E"></div>
		</div>
	</div>
</section>
