<?php
/**
 * Luís Seabra Vinhos — tema clássico.
 * Ponto de entrada: só configura o tema e carrega os módulos de inc/.
 *
 * @package luisseabra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'LSV_VERSION', '1.0.0' );
define( 'LSV_DIR', get_template_directory() );
define( 'LSV_URI', get_template_directory_uri() );

/**
 * Suportes do tema.
 */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	register_nav_menus( array(
		'principal' => __( 'Menu principal', 'luisseabra' ),
	) );
} );

/**
 * Módulos.
 */
require LSV_DIR . '/inc/cpt.php';
require LSV_DIR . '/inc/acf-fields.php';
require LSV_DIR . '/inc/settings-page.php';
require LSV_DIR . '/inc/enqueue.php';
require LSV_DIR . '/inc/polylang.php';
require LSV_DIR . '/inc/reservas.php';

// Nota: o seeder (inc/seed-vinhos.php) NÃO é carregado aqui de propósito.
// Corre-se uma única vez, à mão: wp eval-file .../inc/seed-vinhos.php

/**
 * Helpers de i18n com fallback quando o Polylang não está ativo,
 * para o tema não rebentar sem o plugin.
 */
if ( ! function_exists( 'pll__' ) ) {
	function pll__( $string ) { return $string; }
}
if ( ! function_exists( 'pll_e' ) ) {
	function pll_e( $string ) { echo esc_html( $string ); }
}
if ( ! function_exists( 'pll_register_string' ) ) {
	function pll_register_string( $name, $string, $group = '', $multiline = false ) {}
}

/**
 * Fallbacks do ACF — evitam erro fatal se o plugin estiver desativado.
 * (O plugin é um requisito; isto é só uma rede de segurança para não
 * partir o site todo se alguém esquecer de o ativar.)
 */
if ( ! function_exists( 'get_field' ) ) {
	function get_field( $selector, $post_id = false, $format = true ) { return null; }
}
if ( ! function_exists( 'get_field_object' ) ) {
	function get_field_object( $selector, $post_id = false ) { return false; }
}

/**
 * Compõe o subtítulo do vinho ("Douro · Branco · 2023") a partir de
 * região (termo) + tipo (campo) + ano (campo). Derivado, nunca armazenado.
 *
 * @param int          $post_id ID do post vinho.
 * @param WP_Term|null $regiao  Termo de região já disponível no loop.
 * @return string
 */
function lsv_wine_sub( $post_id, $regiao = null ) {
	$parts = array();

	if ( $regiao instanceof WP_Term ) {
		$parts[] = $regiao->name;
	} else {
		$terms = get_the_terms( $post_id, 'regiao' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$parts[] = $terms[0]->name;
		}
	}

	if ( function_exists( 'get_field' ) ) {
		$tipo_key = get_field( 'vinho_tipo', $post_id );
		$obj      = get_field_object( 'vinho_tipo', $post_id );
		if ( $tipo_key && isset( $obj['choices'][ $tipo_key ] ) ) {
			$parts[] = $obj['choices'][ $tipo_key ];
		}
		$ano = get_field( 'vinho_ano', $post_id );
		if ( $ano ) {
			$parts[] = $ano;
		}
	}

	return implode( ' · ', array_filter( $parts ) );
}

/**
 * Ordem fixa das regiões na Gama (get_terms não garante ordem, e evitamos
 * term-meta por causa da tradução no Polylang Free).
 *
 * @return string[] slugs por ordem.
 */
function lsv_region_order() {
	return array( 'douro', 'dao', 'vinho-verde' );
}
