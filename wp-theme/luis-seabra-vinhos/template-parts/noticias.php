<?php
/**
 * Notícias / Imprensa — lista de menções do CPT "imprensa".
 * Não renderiza se não houver conteúdo (evita secção vazia).
 *
 * @package luisseabra
 */

$q = new WP_Query( array(
	'post_type'      => 'imprensa',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );

if ( ! $q->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>
<section id="noticias" data-screen-label="Notícias" style="background:#FFFFFF; padding:clamp(74px,13vh,160px) clamp(22px,6vw,96px); border-top:1px solid #E4E2DF">
	<div style="max-width:1180px; margin:0 auto">
		<div style="margin-bottom:clamp(30px,5vh,56px)">
			<span style="font-size:10px; letter-spacing:.3em; text-transform:uppercase; color:#6E6E6E"><?php pll_e( 'Notícias' ); ?></span>
			<h2 style="margin:14px 0 0; font-size:clamp(30px,4.2vw,52px); line-height:1.05; font-weight:200; letter-spacing:.01em"><?php pll_e( 'Prémios e imprensa' ); ?></h2>
		</div>

		<ul style="list-style:none; margin:0; padding:0">
			<?php
			while ( $q->have_posts() ) :
				$q->the_post();
				$pid   = get_the_ID();
				$fonte = get_field( 'imprensa_fonte', $pid );
				$pont  = get_field( 'imprensa_pontuacao', $pid );
				$data  = get_field( 'imprensa_data', $pid );
				$link  = get_field( 'imprensa_link', $pid );
				?>
				<li style="display:flex; flex-wrap:wrap; align-items:baseline; justify-content:space-between; gap:16px; padding:clamp(20px,3vh,30px) 0; border-top:1px solid #E4E2DF">
					<div style="display:flex; flex-direction:column; gap:6px; max-width:70ch">
						<h3 style="margin:0; font-size:clamp(18px,2.2vw,24px); font-weight:300; letter-spacing:.01em">
							<?php
							if ( $link ) {
								printf( '<a href="%s" target="_blank" rel="noopener">%s</a>', esc_url( $link ), esc_html( get_the_title() ) );
							} else {
								echo esc_html( get_the_title() );
							}
							?>
						</h3>
						<?php if ( $fonte || $data ) : ?>
							<span style="font-size:10px; letter-spacing:.22em; text-transform:uppercase; color:#6E6E6E"><?php echo esc_html( trim( implode( ' · ', array_filter( array( $fonte, $data ) ) ) ) ); ?></span>
						<?php endif; ?>
					</div>
					<?php if ( $pont ) : ?>
						<span style="font-size:clamp(20px,2.6vw,30px); font-weight:300; letter-spacing:.04em; white-space:nowrap"><?php echo esc_html( $pont ); ?></span>
					<?php endif; ?>
				</li>
			<?php endwhile; ?>
		</ul>
	</div>
</section>
<?php
wp_reset_postdata();
