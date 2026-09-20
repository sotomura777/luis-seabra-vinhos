<?php
/**
 * Importa a imprensa (conteudo.json → noticias) para o CPT `imprensa`.
 * wp eval-file wp-content/themes/luis-seabra-vinhos/inc/import-imprensa.php
 * Idempotente (dedup por título). Podcast = URL Spotify no link.
 */
if ( ! function_exists( 'update_field' ) ) { echo "ACF inativo\n"; return; }

$data = json_decode( file_get_contents( get_template_directory() . '/assets/data/conteudo.json' ), true );
$noticias = $data['noticias'] ?? array();

$n = 0; $order = 0;
foreach ( $noticias as $item ) {
	$order += 10;
	$title = $item['t'];
	$dup   = get_posts( array( 'post_type' => 'imprensa', 'title' => $title, 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) );
	if ( $dup ) { continue; }

	$id = wp_insert_post( array(
		'post_type'    => 'imprensa',
		'post_title'   => $title,
		'post_content' => $item['x'] ?? '',
		'post_status'  => 'publish',
		'menu_order'   => $order,
	) );
	if ( is_wp_error( $id ) || ! $id ) { continue; }
	if ( ! empty( $item['d'] ) ) { update_field( 'imprensa_data', $item['d'], $id ); }
	if ( ! empty( $item['u'] ) ) { update_field( 'imprensa_link', $item['u'], $id ); }
	if ( function_exists( 'pll_set_post_language' ) ) { pll_set_post_language( $id, 'pt' ); }
	$n++;
}
echo "Imprensa importada: $n\n";
