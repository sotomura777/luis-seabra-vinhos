<?php
/**
 * Página "Definições do Site" — casa dos textos globais editáveis.
 * (Alternativa Free às ACF Options Pages, que são Pro. É uma página normal,
 * privada, cujos campos ACF são lidos por ID.)
 *
 * @package luisseabra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cria a página de definições na ativação do tema e guarda o ID numa option.
 */
add_action( 'after_switch_theme', 'lsv_ensure_settings_page' );

function lsv_ensure_settings_page() {
	$existing = (int) get_option( 'lsv_settings_page_id' );
	if ( $existing && get_post( $existing ) ) {
		return;
	}

	$page_id = wp_insert_post( array(
		'post_title'   => 'Definições do Site',
		'post_name'    => 'definicoes-do-site',
		'post_status'  => 'private',
		'post_type'    => 'page',
		'post_content' => '',
	) );

	if ( $page_id && ! is_wp_error( $page_id ) ) {
		update_option( 'lsv_settings_page_id', (int) $page_id );
	}
}

/**
 * Lê um campo da página de definições (na língua atual, se traduzida).
 *
 * @param string $field    Nome do campo ACF.
 * @param string $fallback Valor por defeito.
 * @return string
 */
function lsv_setting( $field, $fallback = '' ) {
	$id = (int) get_option( 'lsv_settings_page_id' );
	if ( ! $id ) {
		return $fallback;
	}

	// Se a página estiver traduzida, usa a versão da língua atual.
	if ( function_exists( 'pll_get_post' ) ) {
		$translated = pll_get_post( $id );
		if ( $translated ) {
			$id = $translated;
		}
	}

	if ( ! function_exists( 'get_field' ) ) {
		return $fallback;
	}

	$value = get_field( $field, $id );
	return ( '' !== $value && null !== $value ) ? $value : $fallback;
}
