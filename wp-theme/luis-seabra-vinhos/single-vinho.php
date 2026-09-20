<?php
/**
 * Página de um vinho — ficha técnica completa.
 *
 * @package luisseabra
 */

get_header();
the_post();

$pid    = get_the_ID();
$terms  = get_the_terms( $pid, 'regiao' );
$region = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
$sub    = get_field( 'vinho_sub', $pid );
$ano    = get_field( 'vinho_ano', $pid );
$amb    = wp_get_attachment_image_url( get_field( 'vinho_ambiente', $pid ), 'full' );
$cut    = wp_get_attachment_image_url( get_field( 'vinho_garrafa', $pid ), 'full' );
$photo  = $amb ? $amb : $cut;
$tom    = get_field( 'vinho_tom', $pid );
$pdf    = get_field( 'vinho_ficha', $pid );
$ficha  = lsv_wine_ficha( $pid );

// Prev/next por menu_order.
$ids = get_posts( array( 'post_type' => 'vinho', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC', 'fields' => 'ids', 'no_found_rows' => true ) );
$pos = array_search( $pid, $ids, true );
$prev = ( false !== $pos ) ? ( $ids[ ( $pos - 1 + count( $ids ) ) % count( $ids ) ] ) : 0;
$next = ( false !== $pos ) ? ( $ids[ ( $pos + 1 ) % count( $ids ) ] ) : 0;
?>

<main class="wine-single" style="<?php echo $tom ? '--tom:' . esc_attr( $tom ) . ';' : ''; ?> background:#0B0B0C; color:#F4F1EB; min-height:100svh">
	<section style="display:grid; grid-template-columns:minmax(0,1fr) minmax(0,.9fr); gap:0; align-items:stretch; min-height:100svh">
		<div style="padding:clamp(120px,18vh,180px) clamp(22px,5vw,80px) clamp(50px,8vh,90px); display:flex; flex-direction:column; gap:clamp(22px,3.4vh,34px); justify-content:center">
			<span class="cap" style="color:var(--gold-2)"><?php echo esc_html( implode( ' · ', array_filter( array( $region ? $region->name : '', $ano ) ) ) ); ?></span>
			<h1 style="margin:0; font-family:var(--serif); font-weight:400; text-transform:uppercase; letter-spacing:.06em; font-size:clamp(30px,4.4vw,60px); line-height:1.05"><?php the_title(); ?></h1>
			<?php if ( $sub ) : ?><span style="font-size:clamp(13px,1.3vw,16px); letter-spacing:.24em; text-transform:uppercase; opacity:.75"><?php echo esc_html( $sub ); ?></span><?php endif; ?>
			<?php if ( get_field( 'vinho_descricao', $pid ) ) : ?>
				<p style="margin:6px 0 0; max-width:52ch; font-size:clamp(16px,1.25vw,18px); line-height:1.9; color:#CFCAC1"><?php echo esc_html( get_field( 'vinho_descricao', $pid ) ); ?></p>
			<?php endif; ?>

			<?php if ( $ficha ) : ?>
				<div style="display:flex; flex-direction:column; gap:14px; margin-top:8px">
					<span class="cap" style="color:var(--gold-2)"><?php pll_e( 'Ficha técnica' ); ?></span>
					<div class="ficha" style="display:grid; grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); gap:1px; background:rgba(255,255,255,.14); border:1px solid rgba(255,255,255,.14)">
						<?php foreach ( $ficha as $f ) : ?>
							<div style="background:rgba(11,11,12,.6); padding:16px 16px 18px; display:flex; flex-direction:column; gap:8px; min-height:84px">
								<span style="font-size:9px; letter-spacing:.28em; text-transform:uppercase; color:var(--gold-2); opacity:.85"><?php echo esc_html( $f[0] ); ?></span>
								<span style="font-size:16px; line-height:1.4"><?php echo esc_html( $f[1] ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="acts" style="display:flex; flex-wrap:wrap; gap:10px; margin-top:8px">
				<?php if ( is_array( $pdf ) && ! empty( $pdf['url'] ) ) : ?>
					<a class="p" href="<?php echo esc_url( $pdf['url'] ); ?>" style="padding:14px 22px; font-size:10.5px; letter-spacing:.24em; text-transform:uppercase; background:var(--gold-2); color:#0B0B0C; border:1px solid var(--gold-2)"><?php pll_e( 'Descarregar ficha técnica (PDF)' ); ?></a>
				<?php endif; ?>
				<a href="<?php echo esc_url( lsv_nav_url( array( 'kind' => 'page', 'slug' => 'onde-comprar' ) ) ); ?>" style="padding:14px 22px; font-size:10.5px; letter-spacing:.24em; text-transform:uppercase; border:1px solid rgba(255,255,255,.4); color:#fff"><?php pll_e( 'Onde comprar este vinho' ); ?></a>
			</div>

			<div style="display:flex; gap:22px; margin-top:10px; font-size:10px; letter-spacing:.24em; text-transform:uppercase; color:#9C978E">
				<?php if ( $prev ) : ?><a href="<?php echo esc_url( get_permalink( $prev ) ); ?>">&lsaquo; <?php echo esc_html( get_the_title( $prev ) ); ?></a><?php endif; ?>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'vinho' ) ); ?>"><?php pll_e( 'Todos os vinhos' ); ?></a>
				<?php if ( $next ) : ?><a href="<?php echo esc_url( get_permalink( $next ) ); ?>"><?php echo esc_html( get_the_title( $next ) ); ?> &rsaquo;</a><?php endif; ?>
			</div>
		</div>
		<div style="position:relative; background:#111 center/cover no-repeat; background-image:url('<?php echo esc_url( $photo ); ?>'); min-height:60vh"></div>
	</section>
</main>

<?php
get_footer();
