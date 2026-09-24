<?php
/**
 * Gama — carrossel coverflow de todos os vinhos, com separadores por região.
 * A garrafa central em foco; clicar nela (ou "Ver ficha") abre a ficha técnica
 * no overlay #sheet. As setas ‹ › mudam de garrafa.
 *
 * @package luisseabra
 */

$wines = lsv_wines_data();
if ( empty( $wines ) ) {
	return;
}
?>
<section id="gama" class="sec lt" data-screen-label="Gama" style="overflow:hidden; text-align:center">
	<div class="wrap">
		<div style="text-align:center; margin-bottom:clamp(20px,3.5vh,40px)">
			<span class="cap on"><?php pll_e( 'Loja' ); ?></span>
			<h2 class="h2" style="margin-top:12px"><?php pll_e( 'A gama' ); ?></h2>
		</div>

		<div class="filters lc-tabs" style="justify-content:center; margin-bottom:clamp(8px,1.6vh,18px)">
			<button type="button" data-region="all" aria-pressed="true"><?php pll_e( 'Todos' ); ?></button>
			<?php
			foreach ( lsv_region_order() as $slug ) :
				$r = lsv_current_region( $slug );
				if ( ! $r ) {
					continue;
				}
				?>
				<button type="button" data-region="<?php echo esc_attr( $r->slug ); ?>"><?php echo esc_html( $r->name ); ?></button>
			<?php endforeach; ?>
		</div>

		<div class="lc-stage">
			<button type="button" class="lc-nav lc-prev" aria-label="<?php echo esc_attr( pll__( 'Garrafa anterior' ) ); ?>">&lsaquo;</button>
			<div class="lc-deck" id="lc-deck">
				<?php foreach ( $wines as $i => $w ) : ?>
					<button type="button" class="lc-bottle" data-idx="<?php echo (int) $i; ?>" data-region="<?php echo esc_attr( $w['region'] ); ?>" aria-label="<?php echo esc_attr( trim( $w['nome'] . ( $w['sub'] ? ', ' . $w['sub'] : '' ) . ( $w['ano'] ? ' ' . $w['ano'] : '' ) ) ); ?>" style="background-image:url('<?php echo esc_url( $w['cut'] ); ?>')"></button>
				<?php endforeach; ?>
			</div>
			<button type="button" class="lc-nav lc-next" aria-label="<?php echo esc_attr( pll__( 'Garrafa seguinte' ) ); ?>">&rsaquo;</button>
		</div>

		<div class="lc-cap">
			<h3 class="lc-name"></h3>
			<span class="lc-meta"></span>
			<p class="lc-desc muted"></p>
			<div class="lc-acts">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'vinho' ) ); ?>"><?php pll_e( 'Todos os vinhos' ); ?></a>
				<button type="button" class="lc-open"><?php pll_e( 'Ver ficha' ); ?></button>
			</div>
		</div>
	</div>

	<?php get_template_part( 'template-parts/wine-sheet' ); ?>
	<script type="application/json" id="lsv-wines"><?php echo wp_json_encode( $wines ); ?></script>
	<?php wp_enqueue_script( 'lsv-landing-carousel', LSV_URI . '/assets/js/landing-carousel.js', array(), LSV_VERSION, true ); ?>
</section>
