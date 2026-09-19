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

/**
 * Strings de UI para o ecrã Languages › Strings translations.
 */
add_action( 'init', function () {
	if ( ! function_exists( 'pll_register_string' ) ) {
		return;
	}
	$g = 'luisseabra';

	$strings = array(
		// Header / nav
		'Loja', 'Menu', 'Fechar',
		// Menu overlay
		'Vinho', 'Vindimas', 'Regiões', 'Mais', 'Vinhas', 'Sobre Nós', 'Visitas', 'Notícias', 'Contactos / Mailing List',
		// Hero
		'Douro · Dão · Vinho Verde — desde 2013', 'Desça', 'Vinhos',
		// Secções
		'A gama', 'Xisto e granito', 'Prémios e imprensa', 'Mailing list',
		'Avisamos quando abre cada colheita. Duas ou três vezes por ano, nada mais.',
		'Envio para Portugal continental em 48 h · Europa em 5 dias úteis',
		'Primeira colheita', 'Vinha trabalhada',
		// Tipos de vinho (usados no subtítulo dos slides)
		'Tinto', 'Branco',
		// Botões
		'Adicionar', 'Subscrever', 'Marcar visita', 'Importadores', 'Garrafa anterior', 'Garrafa seguinte',
		// Contactos
		'Contactos', 'Provas e visitas, por marcação',
		'Nome', 'Email', 'Telefone', 'Data pretendida', 'Hora', 'Nº de pessoas', 'Mensagem', 'Não preencher', 'O seu email',
		// Onde comprar
		'Onde comprar', 'Procura os nossos vinhos?',
		'Diga-nos quem é e onde está. Encaminhamos para o ponto de venda ou distribuidor mais próximo.',
		'Cidade / País', 'Perfil', 'Particular', 'Restaurante', 'Importador', 'Enviar pedido',
		// Rodapé
		'Beba com moderação',
	);
	foreach ( $strings as $s ) {
		pll_register_string( sanitize_title( $s ), $s, $g );
	}

	// Mensagens de estado dos formulários (podem ser multilinha).
	pll_register_string( 'ok_visita', 'Pedido enviado. Respondemos dentro de um dia útil.', $g, true );
	pll_register_string( 'err_visita', 'Não foi possível enviar. Verifique os campos e tente de novo.', $g, true );
	pll_register_string( 'ok_mailing', 'Subscrição registada. Obrigado.', $g );
	pll_register_string( 'err_mailing', 'Email inválido. Tente de novo.', $g );
} );

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
