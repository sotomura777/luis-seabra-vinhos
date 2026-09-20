<?php
/**
 * Importa as 6 parcelas para o CPT `vinha` (tipo=parcela), PT + EN, e marca
 * os 3 blocos de região existentes como tipo=regiao.
 * wp eval-file wp-content/themes/luis-seabra-vinhos/inc/import-vinhas.php
 * Idempotente (dedup por título dentro de tipo=parcela).
 */
if ( ! function_exists( 'update_field' ) ) { echo "ACF inativo\n"; return; }

// 1) Marca os blocos de região (os que já existem) como tipo=regiao.
$regs = get_posts( array( 'post_type' => 'vinha', 'numberposts' => -1, 'post_status' => 'any', 'lang' => '' ) );
$marked = 0;
foreach ( $regs as $p ) {
	if ( 'parcela' === get_field( 'vinha_tipo', $p->ID ) ) { continue; }
	if ( get_field( 'vinha_solo', $p->ID ) ) { continue; } // já é parcela criada por este script
	update_field( 'vinha_tipo', 'regiao', $p->ID );
	$marked++;
}

// 2) As 6 parcelas (lugar = título; solo; detalhe; região).
$parcelas = array(
	array(
		'slug' => 'douro',
		'pt'   => array( 'Meda · Douro', 'Xisto micáceo, 650–700 m', 'Vinha única plantada entre 1920 e 1933. Rabigato, Códega, Gouveio, Viosinho. → Xisto Cru Branco' ),
		'en'   => array( 'Meda · Douro', 'Micaceous schist, 650–700 m', 'Single vineyard planted between 1920 and 1933. Rabigato, Códega, Gouveio, Viosinho. → Xisto Cru Branco' ),
	),
	array(
		'slug' => 'douro',
		'pt'   => array( 'Cima Corgo · Douro', 'Xisto micáceo, 500–600 m', 'Vinhas de 30 a 45 anos. → Xisto Ilimitado' ),
		'en'   => array( 'Cima Corgo · Douro', 'Micaceous schist, 500–600 m', 'Vines 30 to 45 years old. → Xisto Ilimitado' ),
	),
	array(
		'slug' => 'douro',
		'pt'   => array( 'Alijó · Douro', 'Vinha única, xisto', 'Tinta Roriz, Touriga Franca, Tinta Amarela, Rufete, Tinta Barroca. → Indie Xisto' ),
		'en'   => array( 'Alijó · Douro', 'Single vineyard, schist', 'Tinta Roriz, Touriga Franca, Tinta Amarela, Rufete, Tinta Barroca. → Indie Xisto' ),
	),
	array(
		'slug' => 'douro',
		'pt'   => array( 'Baixo Corgo · Douro', 'Xisto amarelo, 450 m', 'Plantada em 1993. 100% Castelão. → Mono C' ),
		'en'   => array( 'Baixo Corgo · Douro', 'Yellow schist, 450 m', 'Planted in 1993. 100% Castelão. → Mono C' ),
	),
	array(
		'slug' => 'dao',
		'pt'   => array( 'Vila Nova de Tazém · Dão', 'Franco arenoso de origem granítica, 490 m', 'Mais de 35 anos, mais de 4500 plantas por hectare. → Granito Cru, Mono A' ),
		'en'   => array( 'Vila Nova de Tazém · Dão', 'Sandy loam of granitic origin, 490 m', 'Over 35 years old, more than 4,500 vines per hectare. → Granito Cru, Mono A' ),
	),
	array(
		'slug' => 'vinho-verde',
		'pt'   => array( 'Monção e Melgaço · Vinho Verde', 'Granito', 'Alvarinho. → Granito Cru Alvarinho' ),
		'en'   => array( 'Monção e Melgaço · Vinho Verde', 'Granite', 'Alvarinho. → Granito Cru Alvarinho' ),
	),
);

$has_pll = function_exists( 'pll_set_post_language' );
$order   = 0;
$created = 0;

foreach ( $parcelas as $p ) {
	$order += 10;

	$dup = get_posts( array( 'post_type' => 'vinha', 'title' => $p['pt'][0], 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids', 'lang' => '' ) );
	// Só considera duplicado se já existir uma PARCELA com este título.
	$exists = false;
	foreach ( $dup as $did ) {
		if ( 'parcela' === get_field( 'vinha_tipo', $did ) ) { $exists = true; break; }
	}
	if ( $exists ) { continue; }

	$pt_term = get_term_by( 'slug', $p['slug'], 'regiao' );

	// PT.
	$pt_id = wp_insert_post( array( 'post_type' => 'vinha', 'post_status' => 'publish', 'post_title' => $p['pt'][0], 'menu_order' => $order ) );
	if ( is_wp_error( $pt_id ) || ! $pt_id ) { continue; }
	update_field( 'vinha_tipo', 'parcela', $pt_id );
	update_field( 'vinha_solo', $p['pt'][1], $pt_id );
	update_field( 'vinha_detalhe', $p['pt'][2], $pt_id );
	if ( $pt_term ) { wp_set_object_terms( $pt_id, array( (int) $pt_term->term_id ), 'regiao' ); }
	if ( $has_pll ) { pll_set_post_language( $pt_id, 'pt' ); }

	// EN.
	$en_id = wp_insert_post( array( 'post_type' => 'vinha', 'post_status' => 'publish', 'post_title' => $p['en'][0], 'menu_order' => $order ) );
	if ( ! is_wp_error( $en_id ) && $en_id ) {
		update_field( 'vinha_tipo', 'parcela', $en_id );
		update_field( 'vinha_solo', $p['en'][1], $en_id );
		update_field( 'vinha_detalhe', $p['en'][2], $en_id );
		if ( $pt_term && function_exists( 'pll_get_term' ) ) {
			$en_term_id = pll_get_term( (int) $pt_term->term_id, 'en' );
			if ( $en_term_id ) { wp_set_object_terms( $en_id, array( (int) $en_term_id ), 'regiao' ); }
		}
		if ( $has_pll ) {
			pll_set_post_language( $en_id, 'en' );
			pll_save_post_translations( array( 'pt' => $pt_id, 'en' => $en_id ) );
		}
	}
	$created++;
}

echo "Blocos de região marcados: $marked · Parcelas criadas (PT+EN): $created\n";
