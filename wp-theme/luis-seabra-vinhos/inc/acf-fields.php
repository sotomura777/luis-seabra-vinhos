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
				'label'        => 'Descrição',
				'name'         => 'vinho_descricao',
				'type'         => 'textarea',
				'rows'         => 3,
				'instructions' => 'Texto simples que aparece no carrossel. A versão inglesa escreve-se na tradução EN do vinho (Polylang).',
			),
			array(
				'key'          => 'field_vinho_castas',
				'label'        => 'Castas',
				'name'         => 'vinho_castas',
				'type'         => 'textarea',
				'rows'         => 3,
				'instructions' => 'Uma casta por linha. Ex.: Rabigato / Códega / Gouveio.',
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
			array(
				'key'           => 'field_vinho_ficha',
				'label'         => 'Ficha técnica (PDF)',
				'name'          => 'vinho_ficha',
				'type'          => 'file',
				'return_format' => 'array',
				'library'       => 'all',
				'mime_types'    => 'pdf',
			),
			array( 'key' => 'field_vinho_sub', 'label' => 'Subtítulo', 'name' => 'vinho_sub', 'type' => 'text', 'instructions' => 'Ex.: "Alfrocheiro · Tinto", "Branco".' ),
			array( 'key' => 'field_vinho_ambiente', 'label' => 'Foto de ambiente', 'name' => 'vinho_ambiente', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium' ),
			array( 'key' => 'field_vinho_tom', 'label' => 'Cor de acento', 'name' => 'vinho_tom', 'type' => 'color_picker', 'instructions' => 'Cor da etiqueta usada como acento na página do vinho.' ),
			array( 'key' => 'field_vinho_destaque', 'label' => 'Destaque na landing', 'name' => 'vinho_destaque', 'type' => 'true_false', 'ui' => 1, 'instructions' => 'Aparece no carrossel da página inicial.' ),
			// --- Ficha técnica ---
			array( 'key' => 'field_vinho_tab_ficha', 'label' => 'Ficha técnica', 'type' => 'tab' ),
			array( 'key' => 'field_vinho_vinha', 'label' => 'Vinha', 'name' => 'vinho_vinha', 'type' => 'text' ),
			array( 'key' => 'field_vinho_solo', 'label' => 'Solo', 'name' => 'vinho_solo', 'type' => 'text' ),
			array( 'key' => 'field_vinho_idade', 'label' => 'Idade das vinhas', 'name' => 'vinho_idade', 'type' => 'text' ),
			array( 'key' => 'field_vinho_plantas', 'label' => 'Plantas/ha', 'name' => 'vinho_plantas', 'type' => 'text' ),
			array( 'key' => 'field_vinho_altitude', 'label' => 'Altitude', 'name' => 'vinho_altitude', 'type' => 'text' ),
			array( 'key' => 'field_vinho_fermentacao', 'label' => 'Fermentação', 'name' => 'vinho_fermentacao', 'type' => 'text' ),
			array( 'key' => 'field_vinho_estagio', 'label' => 'Estágio', 'name' => 'vinho_estagio', 'type' => 'text' ),
			array( 'key' => 'field_vinho_acidez', 'label' => 'Acidez', 'name' => 'vinho_acidez', 'type' => 'text' ),
			array( 'key' => 'field_vinho_ph', 'label' => 'pH', 'name' => 'vinho_ph', 'type' => 'text' ),
			array( 'key' => 'field_vinho_capacidade', 'label' => 'Capacidade', 'name' => 'vinho_capacidade', 'type' => 'text', 'default_value' => '750 ml' ),
			array( 'key' => 'field_vinho_alc', 'label' => 'Álcool', 'name' => 'vinho_alc', 'type' => 'text' ),
		),
	) );

	// ===== RESERVA (marcações de visita) =====
	acf_add_local_field_group( array(
		'key'      => 'group_reserva',
		'title'    => 'Detalhes da marcação',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'reserva' ) ) ),
		'fields'   => array(
			array( 'key' => 'field_reserva_estado', 'label' => 'Estado', 'name' => 'reserva_estado', 'type' => 'select', 'return_format' => 'value', 'default_value' => 'pendente',
				'choices' => array( 'pendente' => 'Pendente', 'confirmada' => 'Confirmada', 'recusada' => 'Recusada' ),
				'instructions' => 'Mudar para "Confirmada" ou "Recusada" envia automaticamente um email ao visitante.' ),
			array( 'key' => 'field_reserva_data', 'label' => 'Data', 'name' => 'reserva_data', 'type' => 'date_picker', 'display_format' => 'd/m/Y', 'return_format' => 'Y-m-d' ),
			array( 'key' => 'field_reserva_hora', 'label' => 'Hora', 'name' => 'reserva_hora', 'type' => 'time_picker', 'display_format' => 'H:i', 'return_format' => 'H:i' ),
			array( 'key' => 'field_reserva_pessoas', 'label' => 'Nº de pessoas', 'name' => 'reserva_pessoas', 'type' => 'number', 'min' => 1 ),
			array( 'key' => 'field_reserva_idioma', 'label' => 'Idioma', 'name' => 'reserva_idioma', 'type' => 'select', 'choices' => array( 'pt' => 'Português', 'en' => 'English' ), 'return_format' => 'value' ),
			array( 'key' => 'field_reserva_nome', 'label' => 'Nome', 'name' => 'reserva_nome', 'type' => 'text' ),
			array( 'key' => 'field_reserva_email', 'label' => 'Email', 'name' => 'reserva_email', 'type' => 'email' ),
			array( 'key' => 'field_reserva_tel', 'label' => 'Telefone', 'name' => 'reserva_tel', 'type' => 'text' ),
			array( 'key' => 'field_reserva_obs', 'label' => 'Observações', 'name' => 'reserva_obs', 'type' => 'textarea', 'rows' => 4 ),
		),
	) );

	// ===== PEDIDO "ONDE COMPRAR" =====
	acf_add_local_field_group( array(
		'key'      => 'group_pedido_compra',
		'title'    => 'Pedido "Onde comprar"',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'pedido_compra' ) ) ),
		'fields'   => array(
			array( 'key' => 'field_pc_estado', 'label' => 'Estado', 'name' => 'pc_estado', 'type' => 'select', 'return_format' => 'value', 'default_value' => 'novo',
				'choices' => array( 'novo' => 'Novo', 'respondido' => 'Respondido' ) ),
			array( 'key' => 'field_pc_tipo', 'label' => 'Tipo', 'name' => 'pc_tipo', 'type' => 'select', 'return_format' => 'value',
				'choices' => array( 'particular' => 'Particular', 'restaurante' => 'Restaurante', 'importador' => 'Importador' ) ),
			array( 'key' => 'field_pc_nome', 'label' => 'Nome', 'name' => 'pc_nome', 'type' => 'text' ),
			array( 'key' => 'field_pc_email', 'label' => 'Email', 'name' => 'pc_email', 'type' => 'email' ),
			array( 'key' => 'field_pc_tel', 'label' => 'Telefone', 'name' => 'pc_tel', 'type' => 'text' ),
			array( 'key' => 'field_pc_local', 'label' => 'Cidade / País', 'name' => 'pc_local', 'type' => 'text' ),
			array( 'key' => 'field_pc_idioma', 'label' => 'Idioma', 'name' => 'pc_idioma', 'type' => 'select', 'choices' => array( 'pt' => 'Português', 'en' => 'English' ), 'return_format' => 'value' ),
			array( 'key' => 'field_pc_mensagem', 'label' => 'Mensagem', 'name' => 'pc_mensagem', 'type' => 'textarea', 'rows' => 4 ),
		),
	) );

	// ===== SUBSCRITOR (newsletter) =====
	acf_add_local_field_group( array(
		'key'      => 'group_subscritor',
		'title'    => 'Subscritor',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'subscritor' ) ) ),
		'fields'   => array(
			array( 'key' => 'field_sub_idioma', 'label' => 'Idioma', 'name' => 'sub_idioma', 'type' => 'select', 'choices' => array( 'pt' => 'Português', 'en' => 'English' ), 'return_format' => 'value' ),
		),
	) );

	// ===== VINHA (secção Regiões) =====
	acf_add_local_field_group( array(
		'key'      => 'group_vinha',
		'title'    => 'Detalhes da vinha / região',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'vinha' ) ) ),
		'fields'   => array(
			array(
				'key'          => 'field_vinha_tipo',
				'label'        => 'Tipo',
				'name'         => 'vinha_tipo',
				'type'         => 'select',
				'choices'      => array( 'regiao' => 'Bloco de região (landing)', 'parcela' => 'Parcela (página Vinhas + tabela Regiões)' ),
				'default_value' => 'parcela',
				'instructions' => '"Região" = os 3 blocos com foto na página inicial. "Parcela" = as linhas da página Vinhas e da tabela das Regiões.',
			),
			array(
				'key'          => 'field_vinha_subtitulo',
				'label'        => 'Subtítulo (blocos de região)',
				'name'         => 'vinha_subtitulo',
				'type'         => 'text',
				'instructions' => 'Ex.: "Xisto micáceo, 400 a 700 metros". O corpo vai no editor; a imagem na imagem destacada.',
			),
			array(
				'key'          => 'field_vinha_solo',
				'label'        => 'Solo / altitude (parcelas)',
				'name'         => 'vinha_solo',
				'type'         => 'text',
				'instructions' => 'Ex.: "Xisto micáceo, 650–700 m".',
			),
			array(
				'key'          => 'field_vinha_detalhe',
				'label'        => 'Detalhe (parcelas)',
				'name'         => 'vinha_detalhe',
				'type'         => 'textarea',
				'rows'         => 2,
				'instructions' => 'Ex.: "Vinha única plantada entre 1920 e 1933. Rabigato, Códega… → Xisto Cru Branco". O nome do lugar é o título do post.',
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
			array( 'key' => 'field_vindima_periodo', 'label' => 'Período', 'name' => 'vindima_periodo', 'type' => 'text', 'instructions' => 'Ex.: "23 Set - 02 Out".' ),
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
				// Contactos — dados gerais (cabeçalho da página).
				array( 'key' => 'field_contacto_tel', 'label' => 'Contactos — telefone principal', 'name' => 'contacto_tel', 'type' => 'text', 'placeholder' => '+351 254 090 044' ),
				array( 'key' => 'field_contacto_tel2', 'label' => 'Contactos — telefone secundário', 'name' => 'contacto_tel2', 'type' => 'text', 'placeholder' => '+351 913 190 201' ),
				array( 'key' => 'field_contacto_email', 'label' => 'Contactos — email', 'name' => 'contacto_email', 'type' => 'text', 'placeholder' => 'geral@luisseabravinhos.com' ),
				// Contactos — 3 locais no mapa.
				array( 'key' => 'field_loc1_nome', 'label' => 'Local 1 — cidade', 'name' => 'loc1_nome', 'type' => 'text', 'placeholder' => 'S. João da Pesqueira' ),
				array( 'key' => 'field_loc1_morada', 'label' => 'Local 1 — morada', 'name' => 'loc1_morada', 'type' => 'textarea', 'rows' => 3 ),
				array( 'key' => 'field_loc1_tel', 'label' => 'Local 1 — telefones', 'name' => 'loc1_tel', 'type' => 'text', 'instructions' => 'Separe dois números por " · ".' ),
				array( 'key' => 'field_loc2_nome', 'label' => 'Local 2 — cidade', 'name' => 'loc2_nome', 'type' => 'text', 'placeholder' => 'Lamego' ),
				array( 'key' => 'field_loc2_morada', 'label' => 'Local 2 — morada', 'name' => 'loc2_morada', 'type' => 'textarea', 'rows' => 3 ),
				array( 'key' => 'field_loc2_tel', 'label' => 'Local 2 — telefones', 'name' => 'loc2_tel', 'type' => 'text' ),
				array( 'key' => 'field_loc3_nome', 'label' => 'Local 3 — cidade', 'name' => 'loc3_nome', 'type' => 'text', 'placeholder' => 'Escritório' ),
				array( 'key' => 'field_loc3_morada', 'label' => 'Local 3 — morada', 'name' => 'loc3_morada', 'type' => 'textarea', 'rows' => 3 ),
				array( 'key' => 'field_loc3_tel', 'label' => 'Local 3 — telefones', 'name' => 'loc3_tel', 'type' => 'text' ),
			),
		) );
	}
}
