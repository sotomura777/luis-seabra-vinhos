<?php
/**
 * Liga a foto de ambiente (fundo temático) a cada vinho (campo vinho_ambiente).
 * Deriva o nome: vinho_garrafa = real-{slug}.webp  →  ambiente = {slug}.webp.
 * Idempotente: salta vinhos que já têm ambiente.
 */
$dir = get_template_directory() . '/assets/img/vinhos/ambiente/';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

$cache = array();
$done  = 0;
$wines = get_posts( array( 'post_type' => 'vinho', 'numberposts' => -1, 'lang' => '', 'post_status' => 'any' ) );
foreach ( $wines as $w ) {
	if ( get_field( 'vinho_ambiente', $w->ID ) ) {
		continue;
	}
	$gar = get_field( 'vinho_garrafa', $w->ID );
	if ( ! $gar ) {
		continue;
	}
	$garfile = basename( get_attached_file( is_array( $gar ) ? $gar['ID'] : $gar ) );
	$ambfile = preg_replace( '/^real-/', '', $garfile );
	$src     = $dir . $ambfile;
	if ( ! file_exists( $src ) ) {
		echo "falta: $ambfile\n";
		continue;
	}
	if ( ! isset( $cache[ $ambfile ] ) ) {
		$up = wp_upload_bits( $ambfile, null, file_get_contents( $src ) );
		if ( ! empty( $up['error'] ) ) {
			echo "erro upload: $ambfile\n";
			continue;
		}
		$type = wp_check_filetype( $up['file'] );
		$aid  = wp_insert_attachment( array(
			'post_mime_type' => $type['type'],
			'post_title'     => sanitize_file_name( pathinfo( $ambfile, PATHINFO_FILENAME ) ),
			'post_status'    => 'inherit',
		), $up['file'] );
		wp_update_attachment_metadata( $aid, wp_generate_attachment_metadata( $aid, $up['file'] ) );
		$cache[ $ambfile ] = $aid;
	}
	update_field( 'vinho_ambiente', $cache[ $ambfile ], $w->ID );
	$done++;
}
echo "vinho_ambiente definido em $done posts (PT+EN); imagens únicas: " . count( $cache ) . "\n";
