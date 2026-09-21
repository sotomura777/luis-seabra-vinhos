<?php
/**
 * Overlay da ficha técnica de um vinho (#sheet).
 * Preenchido por JS (vinhos.js no catálogo, landing-carousel.js na landing).
 *
 * @package luisseabra
 */
?>
<div id="sheet">
	<div class="bg"></div><div class="photo"></div>
	<div class="top">
		<span class="cap" style="color:#fff; opacity:.7">Luís Seabra Vinhos</span>
		<div style="display:flex; gap:10px">
			<button type="button" data-step="-1" aria-label="<?php echo esc_attr( pll__( 'Anterior' ) ); ?>">&lsaquo;</button>
			<button type="button" data-step="1" aria-label="<?php echo esc_attr( pll__( 'Seguinte' ) ); ?>">&rsaquo;</button>
			<button type="button" data-close><?php pll_e( 'Fechar' ); ?></button>
		</div>
	</div>
	<div class="info">
		<div style="display:flex; flex-direction:column; gap:14px">
			<span class="cap ey" style="color:#fff; opacity:.6"></span>
			<h2></h2>
			<span class="sub" style="font-size:clamp(14px,1.4vw,18px); letter-spacing:.24em; text-transform:uppercase; opacity:.8"></span>
			<p class="tx muted" style="margin:18px 0 0; max-width:48ch; color:#C9C6C1"></p>
		</div>
		<div style="display:flex; flex-direction:column; gap:14px">
			<span class="cap" style="color:#fff; opacity:.6"><?php pll_e( 'Ficha técnica' ); ?></span>
			<div class="ficha"></div>
		</div>
		<div class="acts">
			<a class="p pdf" href="#"><?php pll_e( 'Descarregar ficha técnica (PDF)' ); ?></a>
			<a class="buy" href="<?php echo esc_url( lsv_nav_url( array( 'kind' => 'page', 'slug' => 'onde-comprar' ) ) ); ?>"><?php pll_e( 'Onde comprar este vinho' ); ?></a>
		</div>
	</div>
</div>
