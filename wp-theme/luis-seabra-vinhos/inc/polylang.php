<?php
/**
 * Integração Polylang (Free).
 * - Torna os CPTs e a taxonomia traduzíveis.
 * - Regista as strings de UI (chave = string PT literal).
 *
 * @package luisseabra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CPTs traduzíveis (via código, mais fiável que a checkbox das definições).
 */
add_filter( 'pll_get_post_types', function ( $types, $is_settings ) {
	if ( ! $is_settings ) {
		foreach ( array( 'vinho', 'vinha', 'vindima', 'imprensa' ) as $t ) {
			$types[ $t ] = $t;
		}
	}
	return $types;
}, 10, 2 );

/**
 * Taxonomia regiao traduzível.
 */
add_filter( 'pll_get_taxonomies', function ( $tax, $is_settings ) {
	if ( ! $is_settings ) {
		$tax['regiao'] = 'regiao';
	}
	return $tax;
}, 10, 2 );

// O registo e as traduções das strings de UI vivem em inc/i18n.php (mapa PT→EN único).

/**
 * Switcher PT/EN para o header. Devolve markup ou vazio se o Polylang
 * não estiver ativo (v1 é só PT — fica pronto para quando o EN existir).
 */
function lsv_language_switcher() {
	if ( ! function_exists( 'pll_the_languages' ) ) {
		return '';
	}
	$langs = pll_the_languages( array(
		'raw'        => 1,
		'hide_if_no_translation' => 0,
	) );
	if ( empty( $langs ) || count( $langs ) < 2 ) {
		return '';
	}
	$out = array();
	foreach ( $langs as $l ) {
		$slug   = strtoupper( esc_html( $l['slug'] ) );
		$active = $l['current_lang'] ? ' style="opacity:1"' : ' style="opacity:.45"';
		$out[]  = sprintf( '<a href="%s"%s>%s</a>', esc_url( $l['url'] ), $active, $slug );
	}
	return implode( '<span style="opacity:.35">/</span>', $out );
}
