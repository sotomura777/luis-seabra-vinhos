<?php
/**
 * Importa as notas de vindima (conteudo.json → vindimas) para o CPT `vindima`.
 * wp eval-file wp-content/themes/luis-seabra-vinhos/inc/import-vindimas.php
 * Idempotente (dedup por título).
 */
if ( ! function_exists( 'update_field' ) ) { echo "ACF inativo\n"; return; }

$data = json_decode( file_get_contents( get_template_directory() . '/assets/data/conteudo.json' ), true );
$vindimas = $data['vindimas'] ?? array();
$region_slug = array( 'Douro' => 'douro', 'Dão' => 'dao', 'Vinho Verde' => 'vinho-verde' );

$n = 0;
foreach ( $vindimas as $regiao => $notas ) {
	$slug  = $region_slug[ $regiao ] ?? '';
	$rterm = $slug ? get_term_by( 'slug', $slug, 'regiao' ) : null;
	foreach ( $notas as $nota ) {
		$title = $regiao . ' ' . $nota['a'];
		$dup   = get_posts( array( 'post_type' => 'vindima', 'title' => $title, 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) );
		if ( $dup ) { continue; }

		$id = wp_insert_post( array(
			'post_type'    => 'vindima',
			'post_title'   => $title,
			'post_content' => $nota['x'],
			'post_status'  => 'publish',
			'menu_order'   => (int) $nota['a'],
		) );
		if ( is_wp_error( $id ) || ! $id ) { continue; }
		if ( $rterm ) { wp_set_object_terms( $id, array( (int) $rterm->term_id ), 'regiao' ); }
		update_field( 'vindima_ano', $nota['a'], $id );
		update_field( 'vindima_periodo', $nota['p'], $id );
		if ( function_exists( 'pll_set_post_language' ) ) { pll_set_post_language( $id, 'pt' ); }
		$n++;
	}
}
echo "Vindimas importadas: $n\n";
