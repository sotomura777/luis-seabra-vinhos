<?php
/**
 * Endurecimento de segurança ao nível do tema (sem plugins).
 * O baseline pesado (2FA, limitar logins) fica para plugins gratuitos;
 * isto trata do que se faz em código.
 *
 * @package luisseabra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* --- Esconder a versão do WordPress (não facilitar a vida a scanners) --- */
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );
add_filter( 'style_loader_src', 'lsv_strip_version_query', 15 );
add_filter( 'script_loader_src', 'lsv_strip_version_query', 15 );
function lsv_strip_version_query( $src ) {
	if ( $src && strpos( $src, 'ver=' ) && false === strpos( $src, LSV_VERSION ) ) {
		// mantém a versão dos nossos assets (cache-busting), tira a do WP core.
		if ( strpos( $src, site_url() ) !== false && strpos( $src, '/wp-includes/' ) !== false ) {
			$src = remove_query_arg( 'ver', $src );
		}
	}
	return $src;
}

/* --- Limpar links inúteis do <head> (RSD/WLW, ligados ao XML-RPC) --- */
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );

/* --- Desligar XML-RPC e pingbacks (vetor comum de brute-force/DDoS) --- */
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'wp_headers', function ( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
} );

/* --- Bloquear enumeração de utilizadores (?author=1 → revela o login) --- */
add_action( 'template_redirect', function () {
	if ( ! is_admin() && isset( $_GET['author'] ) && preg_match( '/^\d+$/', (string) $_GET['author'] ) ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
} );

/* --- Esconder o endpoint REST de utilizadores para quem não está autenticado --- */
add_filter( 'rest_endpoints', function ( $endpoints ) {
	if ( ! is_user_logged_in() ) {
		unset( $endpoints['/wp/v2/users'] );
		unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	}
	return $endpoints;
} );

/* --- Cabeçalhos de segurança em todas as respostas do front-end --- */
add_action( 'send_headers', function () {
	if ( is_admin() ) {
		return;
	}
	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'Permissions-Policy: geolocation=(), microphone=(), camera=()' );
} );

/* --- Mensagem de erro de login genérica (não dizer se o user existe) --- */
add_filter( 'login_errors', function () {
	return __( 'Credenciais inválidas.', 'luisseabra' );
} );
