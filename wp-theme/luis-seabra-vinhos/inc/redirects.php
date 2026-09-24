<?php
/**
 * Redirects 301 dos URLs do site antigo (luisseabravinhos.com, versão React)
 * para os novos — o Google passa a reputação de cada página antiga para a nova.
 *
 * URLs antigos (tirados do app.js do site antigo, 2026-09-24):
 *   /pt|en/                         → início
 *   /pt|en/sobre, vinhas, visitas, contactos
 *   /pt|en/noticias                 → imprensa
 *   /pt|en/regioes/{id}             → regiões
 *   /pt|en/vindimas/{id}            → vindimas
 *   /pt|en/vinho/{id}               → vinhos
 *   /pt|en/detalhes_vinho/{regiao}/{slug}/{ano} → página do vinho
 *
 * @package luisseabra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Slug do vinho antigo → slug PT do vinho novo (regiões antigas: 2=Douro, 3=Dão, 5=Vinho Verde). */
function lsv_old_wine_map() {
	return array(
		'2/indie-xisto'       => 'indie-xisto',
		'2/mono-c'            => 'mono-c',
		'2/mono-m'            => 'mono-m',
		'2/xisto-cru'         => 'xisto-cru',             // branco
		'2/xisto-cru-1'       => 'xisto-cru-tinto',       // tinto
		'2/xisto-ilimitado'   => 'xisto-ilimitado',       // tinto
		'2/xisto-ilimitado-1' => 'xisto-ilimitado-2',     // branco
		'3/granito-cru-1'     => 'granito-cru',           // Dão
		'3/mono-a'            => 'mono-a',
		'5/granito-cru'       => 'granito-cru-alvarinho', // Vinho Verde
	);
}

/** URL de uma página/vinho (pelo slug PT) na língua pedida. */
function lsv_redirect_post_url( $slug, $type, $lang ) {
	$post = get_page_by_path( $slug, OBJECT, $type );
	if ( ! $post ) {
		return '';
	}
	$id = function_exists( 'pll_get_post' ) ? ( pll_get_post( $post->ID, $lang ) ?: $post->ID ) : $post->ID;
	return get_permalink( $id );
}

/** Destino novo para um caminho antigo (sem o prefixo de língua), ou '' se não for antigo. */
function lsv_old_url_target( $path, $lang ) {
	$prefix  = 'en' === $lang ? '/en' : '';
	$archive = function ( $p ) use ( $prefix ) {
		return home_url( $prefix . '/' . $p . '/' );
	};
	$parts    = explode( '/', $path );
	$parts[0] = preg_replace( '#-en$#', '', $parts[0] ); // slugs EN da demo (/en/sobre-en/)

	switch ( $parts[0] ) {
		case '':
			return function_exists( 'pll_home_url' ) ? pll_home_url( $lang ) : home_url( '/' );
		case 'sobre':
		case 'vinhas':
		case 'visitas':
		case 'contactos':
		case 'onde-comprar':
			return lsv_redirect_post_url( $parts[0], 'page', $lang );
		case 'regioes':
			return lsv_redirect_post_url( 'regioes', 'page', $lang );
		case 'noticias':
			return $archive( 'imprensa' );
		case 'vindimas':
			return $archive( 'vindimas' );
		case 'vinho':
		case 'vinhos':
			return $archive( 'vinhos' );
		case 'detalhes_vinho':
			$key = ( $parts[1] ?? '' ) . '/' . ( $parts[2] ?? '' );
			$map = lsv_old_wine_map();
			$url = ! empty( $map[ $key ] ) ? lsv_redirect_post_url( $map[ $key ], 'vinho', $lang ) : '';
			return $url ? $url : $archive( 'vinhos' );
	}
	return '';
}

// Só os formatos exatos do site antigo — não pode apanhar URLs novos (ex.: /en/vindimas/ é o arquivo novo,
// o antigo tinha sempre /vindimas/{id}). /en/ sozinho é a home EN nova, fica de fora.
// Prioridade 1: antes do redirect_canonical do WordPress, que tentaria "adivinhar" outra página
// (ex.: /en/sobre iria parar à página PT "sobre").
add_action( 'template_redirect', function () {
	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
	$old  = '#^(?:(pt)|(en))(?:/(|(?:sobre|vinhas|visitas|contactos|regioes|onde-comprar)(?:-en)?|noticias|regioes/\d+|vindimas/\d+|vinho/\d+|detalhes_vinho/\d+/[a-z0-9-]+(?:/\d+)?))?$#';
	if ( 'en' === $path || ! preg_match( $old, $path, $m ) ) {
		return;
	}
	$target = lsv_old_url_target( $m[3] ?? '', '' !== $m[1] ? 'pt' : 'en' );
	if ( $target ) {
		wp_safe_redirect( $target, 301, 'Luis Seabra - URL antigo' );
		exit;
	}
}, 1 );
