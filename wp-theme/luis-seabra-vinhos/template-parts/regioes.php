<?php
/**
 * Regiões — render a partir do CPT "vinha" (um post por região).
 * O texto/imagem vivem em posts traduzíveis (não em term-meta), por causa
 * das limitações do Polylang Free com metadados de termo.
 *
 * @package luisseabra
 */

// Recolhe uma vinha por região, na ordem fixa.
$vinhas = array();
foreach ( lsv_region_order() as $slug ) {
	$regiao = lsv_current_region( $slug );
	if ( ! $regiao ) {
		continue;
	}
	$q = new WP_Query( array(
		'post_type'      => 'vinha',
		'posts_per_page' => 1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
		'tax_query'      => array( array(
			'taxonomy' => 'regiao',
			'field'    => 'term_id',
			'terms'    => $regiao->term_id,
		) ),
		// Só os blocos de região (as parcelas ficam para a página Vinhas).
		'meta_query'     => array(
			'relation' => 'OR',
			array( 'key' => 'vinha_tipo', 'value' => 'regiao', 'compare' => '=' ),
			array( 'key' => 'vinha_tipo', 'compare' => 'NOT EXISTS' ),
		),
	) );
	if ( $q->have_posts() ) {
		$q->the_post();
		$vinhas[] = array( 'regiao' => $regiao, 'id' => get_the_ID(), 'slug' => $slug );
		wp_reset_postdata();
	}
}

if ( empty( $vinhas ) ) {
	return;
}

// Foto por defeito de cada região (fallback quando o post `vinha` não tem imagem de destaque).
$region_photo = array(
	'douro'       => LSV_URI . '/assets/img/fotos/vinha-douro.webp',
	'dao'         => LSV_URI . '/assets/img/fotos/vindima-homem.webp',
	'vinho-verde' => LSV_URI . '/assets/img/fotos/uvas-navalha.webp',
);
?>
<section id="regioes" data-screen-label="Regiões" style="background:#FFFFFF; padding:clamp(74px,13vh,160px) clamp(22px,6vw,96px); border-top:1px solid #E4E2DF">
	<div style="max-width:1400px; margin:0 auto; display:flex; flex-direction:column; gap:clamp(50px,9vh,120px)">
		<div id="vinhas">
			<span style="font-size:10px; letter-spacing:.3em; text-transform:uppercase; color:#6E6E6E"><?php pll_e( 'Regiões' ); ?></span>
			<h2 style="margin:14px 0 0; font-size:clamp(30px,4.2vw,52px); line-height:1.05; font-weight:200; letter-spacing:.01em"><?php pll_e( 'Xisto e granito' ); ?></h2>
		</div>

		<?php foreach ( $vinhas as $i => $v ) :
			$pid       = $v['id'];
			$regiao    = $v['regiao'];
			$subtitulo = get_field( 'vinha_subtitulo', $pid );
			$image_first = ( 0 === $i % 2 );
			?>
			<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:clamp(24px,4vw,64px); align-items:center">
				<div style="position:relative; aspect-ratio:4/3; background:#F5F4F2; overflow:hidden;<?php echo $image_first ? '' : ' order:2'; ?>">
					<?php
					if ( has_post_thumbnail( $pid ) ) {
						echo get_the_post_thumbnail( $pid, 'large', array( 'style' => 'width:100%;height:100%;object-fit:cover;display:block' ) );
					} elseif ( ! empty( $region_photo[ $v['slug'] ] ) ) {
						printf(
							'<img src="%s" alt="%s" loading="lazy" style="width:100%%;height:100%%;object-fit:cover;display:block">',
							esc_url( $region_photo[ $v['slug'] ] ),
							esc_attr( $regiao->name )
						);
					}
					?>
				</div>
				<div style="display:flex; flex-direction:column; gap:16px;<?php echo $image_first ? '' : ' order:1'; ?>">
					<span style="font-size:10px; letter-spacing:.28em; text-transform:uppercase; color:#6E6E6E"><?php echo esc_html( sprintf( '%02d — %s', $i + 1, $regiao->name ) ); ?></span>
					<?php if ( $subtitulo ) : ?>
						<h3 style="margin:0; font-size:clamp(24px,3.2vw,40px); line-height:1.16; font-weight:200; letter-spacing:.005em"><?php echo esc_html( $subtitulo ); ?></h3>
					<?php endif; ?>
					<div style="font-size:15.5px; line-height:1.8; color:#4A4A4A">
						<?php
						// Contexto global do post para o filtro the_content (shortcodes, etc.).
						global $post;
						$post = get_post( $pid );
						setup_postdata( $post );
						the_content();
						wp_reset_postdata();
						?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
