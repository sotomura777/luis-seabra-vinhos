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

define( 'LSV_VERSION', '1.0.10' );
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
require LSV_DIR . '/inc/pages.php';
require LSV_DIR . '/inc/enqueue.php';
require LSV_DIR . '/inc/polylang.php';
require LSV_DIR . '/inc/i18n.php';
require LSV_DIR . '/inc/reservas.php';
require LSV_DIR . '/inc/security.php';
require LSV_DIR . '/inc/seo.php';
require LSV_DIR . '/inc/redirects.php';
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
 * Ficha técnica de um vinho: pares [label, valor] por ordem, omitindo
 * vazios / "—" / "a confirmar". Reutilizada na página do vinho e no overlay.
 *
 * @param int $post_id
 * @return array[] [ [label, valor], ... ]
 */
function lsv_wine_ficha( $post_id ) {
	// tipo (label) e origem (termo) são compostos; os restantes são campos diretos.
	$obj      = function_exists( 'get_field_object' ) ? get_field_object( 'vinho_tipo', $post_id ) : false;
	$tipo_key = get_field( 'vinho_tipo', $post_id );
	$tipo     = ( $obj && isset( $obj['choices'][ $tipo_key ] ) ) ? pll__( $obj['choices'][ $tipo_key ] ) : '';
	$terms    = get_the_terms( $post_id, 'regiao' );
	$origem   = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';

	$fields = array(
		array( pll__( 'Vinho' ), $tipo ),
		array( pll__( 'Ano' ), get_field( 'vinho_ano', $post_id ) ),
		array( pll__( 'Castas' ), get_field( 'vinho_castas', $post_id ) ),
		array( pll__( 'Origem' ), $origem ),
		array( pll__( 'Solo' ), get_field( 'vinho_solo', $post_id ) ),
		array( pll__( 'Idade das vinhas' ), get_field( 'vinho_idade', $post_id ) ),
		array( pll__( 'Plantas por ha' ), get_field( 'vinho_plantas', $post_id ) ),
		array( pll__( 'Altitude' ), get_field( 'vinho_altitude', $post_id ) ),
		array( pll__( 'Fermentação' ), get_field( 'vinho_fermentacao', $post_id ) ),
		array( pll__( 'Estágio' ), get_field( 'vinho_estagio', $post_id ) ),
		array( pll__( 'Acidez total' ), get_field( 'vinho_acidez', $post_id ) ),
		array( pll__( 'pH' ), get_field( 'vinho_ph', $post_id ) ),
		array( pll__( 'Capacidade' ), get_field( 'vinho_capacidade', $post_id ) ),
		array( pll__( 'Álcool' ), get_field( 'vinho_alc', $post_id ) ),
	);

	$out = array();
	foreach ( $fields as $f ) {
		$v = trim( (string) $f[1] );
		if ( '' === $v || '—' === $v || preg_match( '/a confirmar/i', $v ) ) {
			continue;
		}
		$out[] = array( $f[0], $v );
	}
	return $out;
}

/**
 * Dados de todos os vinhos para o carrossel/overlay da landing (reutilizável).
 * Inclui garrafa recortada (cut), foto de ambiente (img), região (nome+slug) e ficha.
 *
 * @return array[]
 */
function lsv_wines_data() {
	$q = new WP_Query( array(
		'post_type'      => 'vinho',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	) );
	$wines = array();
	while ( $q->have_posts() ) {
		$q->the_post();
		$pid    = get_the_ID();
		$terms  = get_the_terms( $pid, 'regiao' );
		$region = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
		$cut    = wp_get_attachment_image_url( get_field( 'vinho_garrafa', $pid ), 'large' );
		$amb    = wp_get_attachment_image_url( get_field( 'vinho_ambiente', $pid ), 'large' );
		$fpdf   = get_field( 'vinho_ficha', $pid );
		$wines[] = array(
			'nome'   => get_the_title(),
			'sub'    => get_field( 'vinho_sub', $pid ),
			'regiao' => $region ? $region->name : '',
			'region' => $region ? $region->slug : '',
			'ano'    => get_field( 'vinho_ano', $pid ),
			'texto'  => get_field( 'vinho_descricao', $pid ),
			'castas' => get_field( 'vinho_castas', $pid ),
			'cut'    => $cut ? $cut : '',
			'img'    => $amb ? $amb : $cut,
			'pdf'    => ( is_array( $fpdf ) && ! empty( $fpdf['url'] ) ) ? $fpdf['url'] : '',
			'url'    => get_permalink(),
			'tom'    => get_field( 'vinho_tom', $pid ),
			'ficha'  => lsv_wine_ficha( $pid ),
		);
	}
	wp_reset_postdata();
	return $wines;
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
