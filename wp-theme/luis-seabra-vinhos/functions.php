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
require LSV_DIR . '/inc/security.php';
require LSV_DIR . '/inc/seo.php';
require LSV_DIR . '/inc/backoffice.php';

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
			$parts[] = pll__( $obj['choices'][ $tipo_key ] ); // "Tinto"/"Branco" traduzíveis
		}
		$ano = get_field( 'vinho_ano', $post_id );
		if ( $ano ) {
			$parts[] = $ano;
		}
	}

	return implode( ' · ', array_filter( $parts ) );
}

/**
 * Navegação — fonte única para o header, a cortina de menu e o índice da landing.
 * Cada item: label (traduzível), tipo/slug para resolver o URL, numeral romano, foto de hover.
 *
 * @return array[]
 */
function lsv_nav() {
	$img = LSV_URI . '/assets/img/fotos/';
	return array(
		array( 'label' => 'Início', 'roman' => 'I', 'kind' => 'home', 'slug' => '', 'img' => $img . 'vinha-douro.webp' ),
		array( 'label' => 'Sobre', 'roman' => 'II', 'kind' => 'page', 'slug' => 'sobre', 'img' => $img . 'tonelaria.webp' ),
		array( 'label' => 'Vinhos', 'roman' => 'III', 'kind' => 'cpt', 'slug' => 'vinho', 'path' => 'vinhos', 'img' => $img . 'gama-completa.webp' ),
		array( 'label' => 'Regiões', 'roman' => 'IV', 'kind' => 'page', 'slug' => 'regioes', 'img' => $img . 'vindima-homem.webp' ),
		array( 'label' => 'Vinhas', 'roman' => 'V', 'kind' => 'page', 'slug' => 'vinhas', 'img' => $img . 'vinha-douro.webp' ),
		array( 'label' => 'Vindimas', 'roman' => 'VI', 'kind' => 'cpt', 'slug' => 'vindima', 'path' => 'vindimas', 'img' => $img . 'uvas-navalha.webp' ),
		array( 'label' => 'Imprensa', 'roman' => 'VII', 'kind' => 'cpt', 'slug' => 'imprensa', 'path' => 'imprensa', 'img' => $img . 'vindimadores.webp' ),
		array( 'label' => 'Visitas', 'roman' => 'VIII', 'kind' => 'page', 'slug' => 'visitas', 'img' => $img . 'tonelaria.webp' ),
		array( 'label' => 'Onde comprar', 'roman' => 'IX', 'kind' => 'page', 'slug' => 'onde-comprar', 'img' => $img . 'gama-completa.webp' ),
		array( 'label' => 'Contactos', 'roman' => 'X', 'kind' => 'page', 'slug' => 'contactos', 'img' => $img . 'padaria.webp' ),
	);
}

/**
 * Resolve o URL de um item de navegação (consciente de Polylang; com fallback
 * para /slug/ enquanto as páginas/arquivos ainda não existem).
 */
function lsv_nav_url( $item ) {
	if ( 'home' === $item['kind'] ) {
		return function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );
	}
	if ( 'cpt' === $item['kind'] ) {
		$url = get_post_type_archive_link( $item['slug'] );
		return $url ? $url : home_url( '/' . $item['path'] . '/' );
	}
	// page
	$page = get_page_by_path( $item['slug'] );
	if ( $page ) {
		$id = function_exists( 'pll_get_post' ) ? ( pll_get_post( $page->ID ) ?: $page->ID ) : $page->ID;
		return get_permalink( $id );
	}
	return home_url( '/' . $item['slug'] . '/' );
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

/**
 * Devolve o termo de região correspondente ao slug base, TRADUZIDO para a
 * língua atual (Polylang). O termo PT tem o slug canónico ('douro'); os termos
 * EN têm outro slug, por isso resolvemos via pll_get_term em vez do slug.
 *
 * @param string $slug Slug canónico (PT): douro | dao | vinho-verde.
 * @return WP_Term|null
 */
function lsv_current_region( $slug ) {
	// Buscar o termo canónico (PT, slug 'douro') SEM o filtro de idioma do
	// Polylang — senão em EN o termo com slug 'douro' fica escondido.
	$terms = get_terms( array(
		'taxonomy'   => 'regiao',
		'slug'       => $slug,
		'hide_empty' => false,
		'number'     => 1,
		'lang'       => '',
	) );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return null;
	}
	$base = $terms[0];
	if ( function_exists( 'pll_get_term' ) ) {
		$tid = pll_get_term( $base->term_id ); // traduz para a língua atual
		if ( $tid ) {
			$t = get_term( $tid, 'regiao' );
			if ( $t && ! is_wp_error( $t ) ) {
				return $t;
			}
		}
	}
	return $base;
}
