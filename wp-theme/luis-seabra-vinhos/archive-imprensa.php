<?php
/**
 * Arquivo de imprensa — artigos + episódios Spotify.
 *
 * @package luisseabra
 */

get_header();

$q = new WP_Query( array(
	'post_type'      => 'imprensa',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
?>

<main>
	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'roman'   => 'VII',
		'eyebrow' => pll__( 'Imprensa' ),
		'title'   => pll__( 'Prémios' ),
		'title_i' => pll__( 'e imprensa.' ),
		'lead'    => pll__( 'O que se escreve e ouve sobre os vinhos.' ),
		'bg'      => LSV_URI . '/assets/img/fotos/vindimadores.webp',
	) );
	?>

	<section class="sec lt"><div class="wrap">
		<div class="ledger">
			<?php
			while ( $q->have_posts() ) :
				$q->the_post();
				$data    = get_field( 'imprensa_data', get_the_ID() );
				$link    = get_field( 'imprensa_link', get_the_ID() );
				$spotify = ( $link && preg_match( '#open\.spotify\.com/episode/([A-Za-z0-9]+)#', $link, $m ) ) ? $m[1] : '';
				?>
				<div class="row" style="grid-template-columns:150px minmax(0,1fr); align-items:start; gap:clamp(20px,4vw,60px)">
					<span class="cap" style="padding-top:6px"><?php echo esc_html( $data ); ?></span>
					<div>
						<h3 style="margin:0 0 10px; font-family:var(--sans); font-weight:400; font-size:clamp(17px,1.5vw,22px); letter-spacing:.01em; line-height:1.3"><?php the_title(); ?></h3>
						<?php if ( get_the_content() ) : ?>
							<p style="margin:0; font-family:var(--sans); font-size:clamp(15px,1.15vw,17px); line-height:1.8; color:#2E2B27; max-width:70ch"><?php echo esc_html( wp_strip_all_tags( get_the_content() ) ); ?></p>
						<?php endif; ?>
						<?php if ( $spotify ) : ?>
							<?php // O leitor do Spotify põe cookies: só é carregado depois do clique (RGPD). ?>
							<div class="lsv-spotify" data-src="https://open.spotify.com/embed/episode/<?php echo esc_attr( $spotify ); ?>?theme=0" data-title="<?php echo esc_attr( get_the_title() ); ?>">
								<button type="button"><?php pll_e( 'Ouvir episódio' ); ?> &rarr;</button>
								<small><?php pll_e( 'Ao carregar, o leitor do Spotify é aberto e pode guardar cookies.' ); ?></small>
							</div>
						<?php elseif ( $link ) : ?>
							<a href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener" style="display:inline-block; margin-top:14px; font-size:10.5px; letter-spacing:.24em; text-transform:uppercase; border-bottom:1px solid currentColor; padding-bottom:2px"><?php pll_e( 'Ler mais' ); ?> &rarr;</a>
						<?php endif; ?>
					</div>
				</div>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</div></section>
</main>

<?php
get_footer();
