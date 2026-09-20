<?php
/**
 * Cria as páginas institucionais e atribui-lhes o template certo.
 * IDs guardados na option `lsv_page_ids` (slug => ID). Idempotente.
 *
 * @package luisseabra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_switch_theme', 'lsv_ensure_pages' );

/**
 * @return array slug => page ID (PT).
 */
function lsv_ensure_pages() {
	$defs = array(
		'sobre'        => array( 'pt' => 'Sobre', 'en' => 'About', 'tpl' => 'page-sobre.php' ),
		'regioes'      => array( 'pt' => 'Regiões', 'en' => 'Regions', 'tpl' => 'page-regioes.php' ),
		'vinhas'       => array( 'pt' => 'Vinhas', 'en' => 'Vineyards', 'tpl' => 'page-vinhas.php' ),
		'visitas'      => array( 'pt' => 'Visitas', 'en' => 'Visits', 'tpl' => 'page-visitas.php' ),
		'onde-comprar' => array( 'pt' => 'Onde comprar', 'en' => 'Where to buy', 'tpl' => 'page-onde-comprar.php' ),
		'contactos'    => array( 'pt' => 'Contactos', 'en' => 'Contact', 'tpl' => 'page-contactos.php' ),
	);

	$ids = (array) get_option( 'lsv_page_ids', array() );

	foreach ( $defs as $slug => $d ) {
		// PT
		$pt = isset( $ids[ $slug ] ) ? get_post( $ids[ $slug ] ) : get_page_by_path( $slug );
		if ( ! $pt ) {
			$pid = wp_insert_post( array(
				'post_type'    => 'page',
				'post_title'   => $d['pt'],
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_content' => '',
			) );
		} else {
			$pid = $pt->ID;
		}
		if ( $pid && ! is_wp_error( $pid ) ) {
			update_post_meta( $pid, '_wp_page_template', $d['tpl'] );
			$ids[ $slug ] = (int) $pid;
			if ( function_exists( 'pll_set_post_language' ) && ! pll_get_post_language( $pid ) ) {
				pll_set_post_language( $pid, 'pt' );
			}

			// EN twin
			if ( function_exists( 'pll_get_post' ) && ! pll_get_post( $pid, 'en' ) ) {
				$en = wp_insert_post( array(
					'post_type'   => 'page',
					'post_title'  => $d['en'],
					'post_name'   => $slug . '-en',
					'post_status' => 'publish',
				) );
				if ( $en && ! is_wp_error( $en ) ) {
					update_post_meta( $en, '_wp_page_template', $d['tpl'] );
					pll_set_post_language( $en, 'en' );
					pll_save_post_translations( array( 'pt' => (int) $pid, 'en' => (int) $en ) );
				}
			}
		}
	}

	update_option( 'lsv_page_ids', $ids );
	flush_rewrite_rules();
	return $ids;
}

/** ID da página (na língua atual) a partir do slug canónico. */
function lsv_page_id( $slug ) {
	$ids = (array) get_option( 'lsv_page_ids', array() );
	$id  = isset( $ids[ $slug ] ) ? (int) $ids[ $slug ] : 0;
	if ( $id && function_exists( 'pll_get_post' ) ) {
		$tr = pll_get_post( $id );
		if ( $tr ) {
			return (int) $tr;
		}
	}
	return $id;
}
