<?php
/**
 * Gama — um carrossel 3D por região, alimentado pelo CPT "vinho".
 * Emite o markup exato que o carousel.js espera (data-name/sub/desc/price).
 *
 * @package luisseabra
 */
?>
<section id="gama" data-screen-label="Gama / Loja" style="background:#FFFFFF; padding:clamp(20px,4vh,40px) clamp(22px,6vw,96px) clamp(74px,13vh,160px)">
	<div style="max-width:1400px; margin:0 auto">
		<div style="display:flex; flex-wrap:wrap; align-items:flex-end; justify-content:space-between; gap:22px; margin-bottom:clamp(30px,5vh,56px); border-top:1px solid #E4E2DF; padding-top:clamp(24px,4vh,40px)">
			<div>
				<span style="font-size:10px; letter-spacing:.3em; text-transform:uppercase; color:#6E6E6E"><?php pll_e( 'Loja' ); ?></span>
				<h2 style="margin:14px 0 0; font-size:clamp(30px,4.2vw,52px); line-height:1.05; font-weight:200; letter-spacing:.01em"><?php pll_e( 'A gama' ); ?></h2>
			</div>
		</div>

		<?php
		$n = 0;
		foreach ( lsv_region_order() as $slug ) :
			$regiao = get_term_by( 'slug', $slug, 'regiao' );
			if ( ! $regiao || is_wp_error( $regiao ) ) {
				continue;
			}

			$q = new WP_Query( array(
				'post_type'      => 'vinho',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'no_found_rows'  => true,
				'tax_query'      => array( array(
					'taxonomy' => 'regiao',
					'field'    => 'term_id',
					'terms'    => $regiao->term_id,
				) ),
			) );

			if ( ! $q->have_posts() ) {
				continue;
			}
			$n++;
			$single = ( 1 === $q->post_count ) ? ' data-single' : '';
			?>
			<div class="lsv-region" style="border-top:1px solid #E4E2DF; padding-top:clamp(30px,5vh,56px); margin-top:clamp(44px,7vh,96px)">
				<div style="text-align:center; margin-bottom:clamp(4px,1.4vh,14px)">
					<span style="font-size:10px; letter-spacing:.3em; text-transform:uppercase; color:#6E6E6E"><?php echo esc_html( sprintf( '%02d — %s', $n, $regiao->name ) ); ?></span>
				</div>
				<div class="lsv-carousel"<?php echo $single; ?>>
					<div class="lsv-stage">
						<button type="button" class="lsv-nav lsv-prev" aria-label="<?php echo esc_attr( pll__( 'Garrafa anterior' ) ); ?>">&lsaquo;</button>
						<div class="lsv-deck">
							<?php
							while ( $q->have_posts() ) :
								$q->the_post();
								$pid = get_the_ID();
								$sub = lsv_wine_sub( $pid, $regiao );
								?>
							<figure class="lsv-slide"
								data-name="<?php echo esc_attr( get_the_title() ); ?>"
								data-sub="<?php echo esc_attr( $sub ); ?>"
								data-desc="<?php echo esc_attr( get_field( 'vinho_descricao' ) ); ?>"
								data-price="<?php echo esc_attr( get_field( 'vinho_preco' ) ); ?>">
								<?php
								$img_id = get_field( 'vinho_garrafa' );
								if ( $img_id ) {
									echo wp_get_attachment_image( $img_id, 'full', false, array(
										'alt'       => esc_attr( get_the_title() . ' — ' . $sub ),
										'draggable' => 'false',
									) );
								}
								?>
							</figure>
							<?php endwhile; ?>
						</div>
						<button type="button" class="lsv-nav lsv-next" aria-label="<?php echo esc_attr( pll__( 'Garrafa seguinte' ) ); ?>">&rsaquo;</button>
					</div>
					<div class="lsv-caption">
						<h3 class="lsv-c-name"></h3>
						<span class="lsv-c-sub"></span>
						<p class="lsv-c-desc"></p>
						<div class="lsv-c-foot">
							<span class="lsv-c-price"></span>
							<button type="button" class="lsv-c-add"><?php pll_e( 'Adicionar' ); ?></button>
						</div>
					</div>
				</div>
			</div>
			<?php
			wp_reset_postdata();
		endforeach;
		?>

		<p style="margin:clamp(26px,4vh,44px) 0 0; font-size:11px; letter-spacing:.14em; text-transform:uppercase; color:#6E6E6E"><?php pll_e( 'Envio para Portugal continental em 48 h · Europa em 5 dias úteis' ); ?></p>
	</div>
</section>
