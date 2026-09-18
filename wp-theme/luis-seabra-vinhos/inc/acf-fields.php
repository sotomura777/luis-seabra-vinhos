<?php
/**
 * Campos ACF (Free) registados em código.
 * Requer o plugin Advanced Custom Fields (grátis) ativo.
 *
 * @package luisseabra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'acf/init', 'lsv_register_acf_fields' );

function lsv_register_acf_fields() {

	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return; // ACF não está ativo.
	}

	// ===== VINHO =====
	acf_add_local_field_group( array(
		'key'      => 'group_vinho',
		'title'    => 'Detalhes do vinho',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'vinho' ) ) ),
		'fields'   => array(
			array(
				'key'           => 'field_vinho_tipo',
				'label'         => 'Tipo',
				'name'          => 'vinho_tipo',
				'type'          => 'select',
				'choices'       => array( 'tinto' => 'Tinto', 'branco' => 'Branco' ),
				'return_format' => 'value',
			),
			array(
				'key'   => 'field_vinho_ano',
				'label' => 'Ano',
				'name'  => 'vinho_ano',
				'type'  => 'number',
				'min'   => 1990,
				'max'   => 2100,
			),
			array(
				'key'          => 'field_vinho_descricao',
				'label'        => 'Descrição / castas',
				'name'         => 'vinho_descricao',
				'type'         => 'textarea',
				'rows'         => 3,
				'instructions' => 'Texto simples (vai para o carrossel). Ex.: "Rabigato com Códega, Gouveio e Viosinho."',
			),
			array(
				'key'   => 'field_vinho_preco',
				'label' => 'Preço',
				'name'  => 'vinho_preco',
				'type'  => 'text',
				'instructions' => 'Como aparece no site, ex.: "42 €".',
			),
			array(
				'key'           => 'field_vinho_garrafa',
				'label'         => 'Garrafa (imagem)',
				'name'          => 'vinho_garrafa',
				'type'          => 'image',
				'return_format' => 'id',
				'preview_size'  => 'medium',
				'library'       => 'all',
				'instructions'  => 'PNG/WebP com fundo transparente.',
			),
		),
	) );

	// ===== VINHA (secção Regiões) =====
	acf_add_local_field_group( array(
		'key'      => 'group_vinha',
		'title'    => 'Detalhes da vinha / região',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'vinha' ) ) ),
		'fields'   => array(
			array(
				'key'          => 'field_vinha_subtitulo',
				'label'        => 'Subtítulo',
				'name'         => 'vinha_subtitulo',
				'type'         => 'text',
				'instructions' => 'Ex.: "Xisto micáceo, 400 a 700 metros". O corpo vai no editor; a imagem na imagem destacada.',
			),
		),
	) );

	// ===== IMPRENSA (secção Notícias) =====
	acf_add_local_field_group( array(
		'key'      => 'group_imprensa',
		'title'    => 'Detalhes da menção',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'imprensa' ) ) ),
		'fields'   => array(
			array( 'key' => 'field_imprensa_fonte', 'label' => 'Fonte', 'name' => 'imprensa_fonte', 'type' => 'text' ),
			array( 'key' => 'field_imprensa_pontuacao', 'label' => 'Pontuação', 'name' => 'imprensa_pontuacao', 'type' => 'text', 'instructions' => 'Ex.: "97 · 94".' ),
			array( 'key' => 'field_imprensa_data', 'label' => 'Data', 'name' => 'imprensa_data', 'type' => 'text', 'instructions' => 'Ex.: "Porto · Fevereiro 2026".' ),
			array( 'key' => 'field_imprensa_link', 'label' => 'Link', 'name' => 'imprensa_link', 'type' => 'url' ),
		),
	) );

	// ===== VINDIMA =====
	acf_add_local_field_group( array(
		'key'      => 'group_vindima',
		'title'    => 'Detalhes da vindima',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'vindima' ) ) ),
		'fields'   => array(
			array( 'key' => 'field_vindima_ano', 'label' => 'Ano', 'name' => 'vindima_ano', 'type' => 'number' ),
		),
	) );

	// ===== PÁGINA "DEFINIÇÕES" (texto global editável) =====
	$settings_id = (int) get_option( 'lsv_settings_page_id' );
	if ( $settings_id ) {
		acf_add_local_field_group( array(
			'key'      => 'group_definicoes',
			'title'    => 'Textos do site',
			'location' => array( array( array( 'param' => 'page', 'operator' => '==', 'value' => $settings_id ) ) ),
			'fields'   => array(
				array( 'key' => 'field_hero_cap1', 'label' => 'Hero — legenda 1', 'name' => 'hero_cap1', 'type' => 'textarea', 'rows' => 2 ),
				array( 'key' => 'field_hero_cap2', 'label' => 'Hero — legenda 2', 'name' => 'hero_cap2', 'type' => 'textarea', 'rows' => 2 ),
				array( 'key' => 'field_hero_cap3', 'label' => 'Hero — legenda 3', 'name' => 'hero_cap3', 'type' => 'textarea', 'rows' => 2 ),
				array( 'key' => 'field_sobre_lead', 'label' => 'Sobre — destaque', 'name' => 'sobre_lead', 'type' => 'textarea', 'rows' => 3 ),
				array( 'key' => 'field_sobre_body', 'label' => 'Sobre — corpo', 'name' => 'sobre_body', 'type' => 'wysiwyg', 'media_upload' => 0, 'tabs' => 'visual' ),
				array( 'key' => 'field_contacto_body', 'label' => 'Contactos — corpo', 'name' => 'contacto_body', 'type' => 'textarea', 'rows' => 3 ),
			),
		) );
	}
}
