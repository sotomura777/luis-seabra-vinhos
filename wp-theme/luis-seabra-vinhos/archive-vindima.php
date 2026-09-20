<?php
/**
 * Arquivo de vindimas — notas de colheita com separadores por região.
 *
 * @package luisseabra
 */

get_header();
$regioes = array( 'douro' => 'Douro', 'dao' => 'Dão', 'vinho-verde' => 'Vinho Verde' );
?>

<main>
	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'roman'   => 'VI',
		'eyebrow' => pll__( 'Vindimas' ),
		'title'   => pll__( 'Notas de' ),
		'title_i' => pll__( 'colheita.' ),
		'lead'    => pll__( 'Ano a ano, região a região: as condições e as datas de cada vindima.' ),
		'bg'      => LSV_URI . '/assets/img/fotos/uvas-navalha.webp',
	) );
	?>

	<section class="sec lt"><div class="wrap">
		<div class="filters" style="margin-bottom:clamp(30px,5vh,50px)">
			<?php $first = true; foreach ( $regioes as $slug => $name ) : ?>
				<button class="vtab" data-r="<?php echo esc_attr( $slug ); ?>" aria-pressed="<?php echo $first ? 'true' : 'false'; ?>"><?php echo esc_html( $name ); ?></button>
			<?php $first = false; endforeach; ?>
		</div>

		<?php
		$first = true;
		foreach ( $regioes as $slug => $name ) :
			$term = lsv_current_region( $slug );
			$q    = $term ? new WP_Query( array(
				'post_type'      => 'vindima',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order',
				'order'          => 'DESC',
				'no_found_rows'  => true,
				'tax_query'      => array( array( 'taxonomy' => 'regiao', 'field' => 'term_id', 'terms' => $term->term_id ) ),
			) ) : null;
			?>
			<div class="vlist" data-r="<?php echo esc_attr( $slug ); ?>" style="<?php echo $first ? '' : 'display:none'; ?>">
				<div class="ledger">
					<?php if ( $q && $q->have_posts() ) : while ( $q->have_posts() ) : $q->the_post(); ?>
						<div class="row">
							<span class="v" style="font-family:var(--serif)"><?php echo esc_html( get_field( 'vindima_ano', get_the_ID() ) ); ?><small style="font-family:var(--sans); letter-spacing:.14em; text-transform:uppercase; color:#5A5650"><?php echo esc_html( get_field( 'vindima_periodo', get_the_ID() ) ); ?></small></span>
							<p style="margin:0; font-family:var(--sans); font-size:clamp(16px,1.25vw,18px); line-height:1.85; color:#2E2B27"><?php echo esc_html( get_the_content() ); ?></p>
						</div>
					<?php endwhile; wp_reset_postdata(); else : ?>
						<p class="muted"><?php pll_e( 'Em breve.' ); ?></p>
					<?php endif; ?>
				</div>
			</div>
			<?php $first = false; endforeach; ?>
	</div></section>
</main>

<script>
(function(){
  var tabs=document.querySelectorAll('.vtab'),lists=document.querySelectorAll('.vlist');
  tabs.forEach(function(t){t.addEventListener('click',function(){
    tabs.forEach(function(x){x.setAttribute('aria-pressed',x===t?'true':'false');});
    lists.forEach(function(l){l.style.display=(l.dataset.r===t.dataset.r)?'':'none';});
  });});
})();
</script>

<?php
get_footer();
