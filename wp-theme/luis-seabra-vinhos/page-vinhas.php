<?php
/**
 * Template Name: Vinhas
 * Ledger das parcelas (solos, altitudes, idades).
 *
 * @package luisseabra
 */

get_header();

// Parcelas a partir do CPT `vinha` (tipo=parcela) — editáveis no backoffice.
$q = new WP_Query( array(
	'post_type'      => 'vinha',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
	'meta_query'     => array( array( 'key' => 'vinha_tipo', 'value' => 'parcela', 'compare' => '=' ) ),
) );
?>

<main>
	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'roman'   => 'V',
		'eyebrow' => pll__( 'Vinhas' ),
		'title'   => pll__( 'Solos, altitudes' ),
		'title_i' => pll__( 'e idades.' ),
		'lead'    => pll__( 'Seis parcelas em três regiões. Vinhas velhas, escolhidas pelo solo e pela altitude.' ),
		'bg'      => LSV_URI . '/assets/img/fotos/vinha-douro.webp',
	) );
	?>

	<section class="sec lt"><div class="wrap">
		<span class="cap on"><?php pll_e( 'Parcelas' ); ?></span>
		<div class="ledger" style="margin-top:clamp(24px,4vh,44px)">
			<?php if ( $q->have_posts() ) : while ( $q->have_posts() ) : $q->the_post(); ?>
				<div class="row">
					<span class="cap"><?php the_title(); ?></span>
					<span class="v"><?php echo esc_html( get_field( 'vinha_solo', get_the_ID() ) ); ?><small><?php echo esc_html( get_field( 'vinha_detalhe', get_the_ID() ) ); ?></small></span>
				</div>
			<?php endwhile; wp_reset_postdata(); else : ?>
				<p class="muted"><?php pll_e( 'Em breve.' ); ?></p>
			<?php endif; ?>
		</div>
	</div></section>
</main>

<?php
get_footer();
