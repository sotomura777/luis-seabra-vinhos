<?php
/**
 * Catálogo de vinhos (grelha filtrável) + overlay da ficha técnica.
 *
 * @package luisseabra
 */

get_header();

$q = new WP_Query( array(
	'post_type'      => 'vinho',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );

// Dados para o overlay (ficha sem recarregar).
$wines = array();
?>

<main>
	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'roman'   => 'III',
		'eyebrow' => pll__( 'Vinhos' ),
		'title'   => pll__( 'Nove vinhos,' ),
		'title_i' => pll__( 'três regiões.' ),
		'lead'    => pll__( 'Dão, Douro e Vinho Verde, com ficha técnica completa. Carregue num vinho para abrir a ficha.' ),
		'bg'      => LSV_URI . '/assets/img/fotos/gama-completa.webp',
	) );
	?>

	<section class="sec lt"><div class="wrap">
		<div style="display:flex; flex-wrap:wrap; align-items:flex-end; justify-content:space-between; gap:22px; margin-bottom:clamp(30px,5vh,56px)">
			<div><span class="cap on"><?php pll_e( 'A gama' ); ?></span><h2 class="h2"><?php pll_e( 'Xisto e granito.' ); ?></h2></div>
			<div class="filters">
				<button data-f="all" aria-pressed="true"><?php pll_e( 'Todos' ); ?></button>
				<?php foreach ( lsv_region_order() as $slug ) : $r = lsv_current_region( $slug ); if ( ! $r ) { continue; } ?>
					<button data-f="<?php echo esc_attr( $r->slug ); ?>"><?php echo esc_html( $r->name ); ?></button>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="wgrid" id="wgrid">
			<?php
			$i = 0;
			while ( $q->have_posts() ) :
				$q->the_post();
				$pid    = get_the_ID();
				$terms  = get_the_terms( $pid, 'regiao' );
				$region = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
				$sub    = get_field( 'vinho_sub', $pid );
				$ano    = get_field( 'vinho_ano', $pid );
				$cut    = wp_get_attachment_image_url( get_field( 'vinho_garrafa', $pid ), 'large' );
				$amb    = wp_get_attachment_image_url( get_field( 'vinho_ambiente', $pid ), 'large' );
				$meta   = implode( ' · ', array_filter( array( $region ? $region->name : '', $sub, $ano ) ) );
				?>
				<a class="wcard" href="<?php the_permalink(); ?>" data-idx="<?php echo (int) $i; ?>" data-region="<?php echo esc_attr( $region ? $region->slug : '' ); ?>">
					<div class="ph" style="background-image:url('<?php echo esc_url( $cut ); ?>'); background-size:contain; background-color:#E9E7E2"></div>
					<h3><?php the_title(); ?></h3>
					<span class="m"><?php echo esc_html( $meta ); ?></span>
					<p class="muted" style="margin:0; font-size:14px; line-height:1.7"><?php echo esc_html( get_field( 'vinho_castas', $pid ) ); ?></p>
				</a>
				<?php
				$fpdf   = get_field( 'vinho_ficha', $pid );
				$wines[] = array(
					'nome'   => get_the_title(),
					'sub'    => $sub,
					'regiao' => $region ? $region->name : '',
					'ano'    => $ano,
					'texto'  => get_field( 'vinho_descricao', $pid ),
					'img'    => $amb ? $amb : $cut,
					'pdf'    => ( is_array( $fpdf ) && ! empty( $fpdf['url'] ) ) ? $fpdf['url'] : '',
					'url'    => get_permalink(),
					'tom'    => get_field( 'vinho_tom', $pid ),
					'ficha'  => lsv_wine_ficha( $pid ),
				);
				$i++;
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div></section>

	<!-- Overlay da ficha -->
	<div id="sheet">
		<div class="bg"></div><div class="photo"></div>
		<div class="top">
			<span class="cap" style="color:#fff; opacity:.7">Luís Seabra Vinhos</span>
			<div style="display:flex; gap:10px">
				<button type="button" data-step="-1" aria-label="Anterior">&lsaquo;</button>
				<button type="button" data-step="1" aria-label="Seguinte">&rsaquo;</button>
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

	<script type="application/json" id="lsv-wines"><?php echo wp_json_encode( $wines ); ?></script>
	<?php wp_enqueue_script( 'lsv-vinhos', LSV_URI . '/assets/js/vinhos.js', array(), LSV_VERSION, true ); ?>
</main>

<?php
get_footer();
