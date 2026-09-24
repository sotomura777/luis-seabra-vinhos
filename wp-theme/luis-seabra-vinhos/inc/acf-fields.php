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
			// --- Apresentação ---
			array( 'key' => 'field_vinho_tab_apresentacao', 'label' => 'Apresentação', 'type' => 'tab' ),
			array(
				'key'           => 'field_vinho_tipo',
				'label'         => 'Tipo',
				'name'          => 'vinho_tipo',
				'type'          => 'select',
				'choices'       => array( 'tinto' => 'Tinto', 'branco' => 'Branco' ),
				'return_format' => 'value',
				'instructions'  => 'Aparece na ficha técnica ("Vinho: Tinto").',
			),
			array( 'key' => 'field_vinho_sub', 'label' => 'Subtítulo', 'name' => 'vinho_sub', 'type' => 'text', 'instructions' => 'Por baixo do nome, no carrossel, na lista e na página do vinho. Ex.: "Alfrocheiro · Tinto" ou só "Branco".' ),
			array(
				'key'          => 'field_vinho_ano',
				'label'        => 'Ano (colheita)',
				'name'         => 'vinho_ano',
				'type'         => 'number',
				'min'          => 1990,
				'max'          => 2100,
			),
			array(
				'key'          => 'field_vinho_descricao',
				'label'        => 'Descrição',
				'name'         => 'vinho_descricao',
				'type'         => 'textarea',
				'rows'         => 3,
				'instructions' => 'Uma ou duas frases. Aparece no carrossel da página inicial e na página do vinho. A versão em inglês escreve-se na versão EN deste vinho (lápis ao lado da bandeira inglesa, na caixa "Languages" à direita).',
			),
			array(
				'key'          => 'field_vinho_castas',
				'label'        => 'Castas',
				'name'         => 'vinho_castas',
				'type'         => 'textarea',
				'rows'         => 2,
				'instructions' => 'Separadas por vírgulas. Ex.: "Rabigato (70%), Códega, Gouveio e Donzelinho Branco".',
			),
			// --- Imagens ---
			array( 'key' => 'field_vinho_tab_imagens', 'label' => 'Imagens', 'type' => 'tab' ),
			array(
				'key'           => 'field_vinho_garrafa',
				'label'         => 'Garrafa (recortada)',
				'name'          => 'vinho_garrafa',
				'type'          => 'image',
				'return_format' => 'id',
				'preview_size'  => 'medium',
				'library'       => 'all',
				'instructions'  => 'Só a garrafa, com fundo transparente (PNG ou WebP), na vertical — cerca de 400 × 1000 píxeis. Aparece no carrossel e na lista de vinhos.',
			),
			array( 'key' => 'field_vinho_ambiente', 'label' => 'Foto de ambiente', 'name' => 'vinho_ambiente', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'instructions' => 'Foto vertical em proporção 2:3 (ex.: 1200 × 1800 píxeis). Aparece ao lado da ficha, na página do vinho. Se ficar vazio, usa-se a garrafa.' ),
			// --- Ficha técnica ---
			array( 'key' => 'field_vinho_tab_ficha', 'label' => 'Ficha técnica', 'type' => 'tab' ),
			array( 'key' => 'field_vinho_msg_ficha', 'label' => '', 'type' => 'message', 'message' => 'Estes dados aparecem na tabela "Ficha técnica". Campos vazios não aparecem. A <strong>origem</strong> vem da caixa "Regiões", à direita.' ),
			array(
				'key'           => 'field_vinho_ficha',
				'label'         => 'Ficha técnica em PDF',
				'name'          => 'vinho_ficha',
				'type'          => 'file',
				'return_format' => 'array',
				'library'       => 'all',
				'mime_types'    => 'pdf',
				'instructions'  => 'Se carregar um PDF, aparece um botão para o descarregar.',
			),
			array( 'key' => 'field_vinho_solo', 'label' => 'Solo', 'name' => 'vinho_solo', 'type' => 'text' ),
			array( 'key' => 'field_vinho_idade', 'label' => 'Idade das vinhas', 'name' => 'vinho_idade', 'type' => 'text', 'placeholder' => 'Mais de 80 anos' ),
			array( 'key' => 'field_vinho_plantas', 'label' => 'Plantas por hectare', 'name' => 'vinho_plantas', 'type' => 'text', 'placeholder' => '6500' ),
			array( 'key' => 'field_vinho_altitude', 'label' => 'Altitude', 'name' => 'vinho_altitude', 'type' => 'text', 'placeholder' => '650 a 750 m' ),
			array( 'key' => 'field_vinho_fermentacao', 'label' => 'Fermentação', 'name' => 'vinho_fermentacao', 'type' => 'text' ),
			array( 'key' => 'field_vinho_estagio', 'label' => 'Estágio', 'name' => 'vinho_estagio', 'type' => 'text' ),
			array( 'key' => 'field_vinho_acidez', 'label' => 'Acidez total', 'name' => 'vinho_acidez', 'type' => 'text', 'placeholder' => '5,8 g/dm³' ),
			array( 'key' => 'field_vinho_ph', 'label' => 'pH', 'name' => 'vinho_ph', 'type' => 'text', 'placeholder' => '3,29' ),
			array( 'key' => 'field_vinho_capacidade', 'label' => 'Capacidade', 'name' => 'vinho_capacidade', 'type' => 'text', 'default_value' => '750 ml' ),
			array( 'key' => 'field_vinho_alc', 'label' => 'Álcool', 'name' => 'vinho_alc', 'type' => 'text', 'placeholder' => '13,5%' ),
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
			array( 'key' => 'field_imprensa_msg', 'label' => '', 'type' => 'message', 'message' => 'O <strong>título</strong> é o nome do artigo ou do prémio; no texto principal ponha a citação ou um resumo curto. As menções novas aparecem em primeiro lugar. A versão em inglês escreve-se na versão EN (caixa "Languages", à direita).' ),
			array( 'key' => 'field_imprensa_fonte', 'label' => 'Fonte', 'name' => 'imprensa_fonte', 'type' => 'text', 'instructions' => 'Quem publicou. Ex.: "Wineanorak", "Falstaff". Aparece na secção de notícias da página inicial.' ),
			array( 'key' => 'field_imprensa_pontuacao', 'label' => 'Pontuação', 'name' => 'imprensa_pontuacao', 'type' => 'text', 'instructions' => 'Opcional. Ex.: "97 · 94".' ),
			array( 'key' => 'field_imprensa_data', 'label' => 'Data', 'name' => 'imprensa_data', 'type' => 'text', 'instructions' => 'Como deve aparecer. Ex.: "29 Jun 2026".' ),
			array( 'key' => 'field_imprensa_link', 'label' => 'Link', 'name' => 'imprensa_link', 'type' => 'url', 'instructions' => 'Endereço do artigo (aparece o botão "Ler mais"). Se for um episódio do Spotify, aparece o botão para ouvir.' ),
		),
	) );

	// ===== VINDIMA =====
	acf_add_local_field_group( array(
		'key'      => 'group_vindima',
		'title'    => 'Detalhes da vindima',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'vindima' ) ) ),
		'fields'   => array(
			array( 'key' => 'field_vindima_msg', 'label' => '', 'type' => 'message', 'message' => 'Título: região e ano (ex.: "Douro 2025"). No texto principal descreva o ano. <strong>Escolha a região na caixa "Regiões", à direita — sem região, a vindima não aparece no site.</strong> A ordem na lista é automática, pelo ano.' ),
			array( 'key' => 'field_vindima_ano', 'label' => 'Ano', 'name' => 'vindima_ano', 'type' => 'number', 'min' => 2000, 'max' => 2100, 'required' => 1 ),
			array( 'key' => 'field_vindima_periodo', 'label' => 'Período da vindima', 'name' => 'vindima_periodo', 'type' => 'text', 'instructions' => 'Datas de início e fim. Ex.: "23 Set - 02 Out".' ),
		),
	) );

	// ===== PÁGINA "DEFINIÇÕES" (texto global editável) =====
	$settings_id = (int) get_option( 'lsv_settings_page_id' );
	if ( $settings_id ) {
		acf_add_local_field_group( array(
			'key'      => 'group_definicoes',
			'title'    => 'Textos do site',
			// Mostra os campos na página de Definições em todas as línguas (PT e a tradução EN).
			'location' => array_map(
				function ( $id ) {
					return array( array( 'param' => 'page', 'operator' => '==', 'value' => (int) $id ) );
				},
				array_unique( array_merge( array( $settings_id ), function_exists( 'pll_get_post_translations' ) ? array_values( pll_get_post_translations( $settings_id ) ) : array() ) )
			),
			'fields'   => array(
				// --- Página inicial ---
				array( 'key' => 'field_tab_inicio', 'label' => 'Página inicial', 'type' => 'tab' ),
				array( 'key' => 'field_hero_cap1', 'label' => 'Frase 1 da abertura', 'name' => 'hero_cap1', 'type' => 'textarea', 'rows' => 2, 'instructions' => 'A abertura da página inicial mostra três frases, uma de cada vez, no canto da imagem, à medida que a pessoa desce. Esta é a primeira. Curta: uma frase.' ),
				array( 'key' => 'field_hero_cap2', 'label' => 'Frase 2 da abertura', 'name' => 'hero_cap2', 'type' => 'textarea', 'rows' => 2, 'instructions' => 'A segunda frase da abertura.' ),
				array( 'key' => 'field_hero_cap3', 'label' => 'Frase 3 da abertura', 'name' => 'hero_cap3', 'type' => 'textarea', 'rows' => 2, 'instructions' => 'A terceira e última frase da abertura.' ),
				array( 'key' => 'field_sobre_lead', 'label' => 'Secção "Sobre" — frase de destaque', 'name' => 'sobre_lead', 'type' => 'textarea', 'rows' => 3, 'instructions' => 'A frase grande da secção sobre o Luís, na página inicial.' ),
				array( 'key' => 'field_sobre_body', 'label' => 'Secção "Sobre" — texto', 'name' => 'sobre_body', 'type' => 'wysiwyg', 'media_upload' => 0, 'tabs' => 'visual', 'toolbar' => 'basic', 'instructions' => 'O texto por baixo da frase de destaque, na página inicial.' ),
				array( 'key' => 'field_contacto_body', 'label' => 'Secção "Provas e visitas" — texto', 'name' => 'contacto_body', 'type' => 'textarea', 'rows' => 3, 'instructions' => 'O convite para visitar a adega, ao lado do formulário de marcação na página inicial.' ),
				// --- Página Sobre ---
				array( 'key' => 'field_tab_sobre', 'label' => 'Página Sobre', 'type' => 'tab' ),
				array( 'key' => 'field_sobre_testemunho', 'label' => 'Nas palavras do Luís', 'name' => 'sobre_testemunho', 'type' => 'textarea', 'rows' => 8, 'instructions' => 'O testemunho do Luís na página Sobre, a seguir à cronologia.' ),
				// --- Contactos ---
				array( 'key' => 'field_tab_contactos', 'label' => 'Contactos', 'type' => 'tab' ),
				array( 'key' => 'field_contacto_tel', 'label' => 'Telefone principal', 'name' => 'contacto_tel', 'type' => 'text', 'placeholder' => '+351 254 090 044', 'instructions' => 'No topo da página Contactos.' ),
				array( 'key' => 'field_contacto_tel2', 'label' => 'Telefone secundário', 'name' => 'contacto_tel2', 'type' => 'text', 'placeholder' => '+351 913 190 201' ),
				array( 'key' => 'field_contacto_email', 'label' => 'Email', 'name' => 'contacto_email', 'type' => 'text', 'placeholder' => 'geral@luisseabravinhos.com' ),
				array( 'key' => 'field_msg_locais', 'label' => 'Moradas no mapa', 'type' => 'message', 'message' => 'As três moradas que aparecem na página Contactos, cada uma com um pino no mapa. Para mudar a posição do pino é preciso pedir ao programador.' ),
				array( 'key' => 'field_loc1_nome', 'label' => 'Morada 1 (Sede · Adega) — localidade', 'name' => 'loc1_nome', 'type' => 'text', 'placeholder' => 'S. João da Pesqueira' ),
				array( 'key' => 'field_loc1_morada', 'label' => 'Morada 1 — morada completa', 'name' => 'loc1_morada', 'type' => 'textarea', 'rows' => 3, 'instructions' => 'Uma linha por cada parte da morada.' ),
				array( 'key' => 'field_loc1_tel', 'label' => 'Morada 1 — telefones', 'name' => 'loc1_tel', 'type' => 'text', 'instructions' => 'Para dois números, separe-os com " · ".' ),
				array( 'key' => 'field_loc2_nome', 'label' => 'Morada 2 (Armazém) — localidade', 'name' => 'loc2_nome', 'type' => 'text', 'placeholder' => 'Lamego' ),
				array( 'key' => 'field_loc2_morada', 'label' => 'Morada 2 — morada completa', 'name' => 'loc2_morada', 'type' => 'textarea', 'rows' => 3 ),
				array( 'key' => 'field_loc2_tel', 'label' => 'Morada 2 — telefones', 'name' => 'loc2_tel', 'type' => 'text' ),
				array( 'key' => 'field_loc3_nome', 'label' => 'Morada 3 (Escritório) — localidade', 'name' => 'loc3_nome', 'type' => 'text', 'placeholder' => 'Escritório' ),
				array( 'key' => 'field_loc3_morada', 'label' => 'Morada 3 — morada completa', 'name' => 'loc3_morada', 'type' => 'textarea', 'rows' => 3 ),
				array( 'key' => 'field_loc3_tel', 'label' => 'Morada 3 — telefones', 'name' => 'loc3_tel', 'type' => 'text' ),
			),
		) );
	}
}
