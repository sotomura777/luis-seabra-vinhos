<?php
/**
 * Traduções PT → EN das strings de UI.
 *
 * - lsv_i18n_map(): mapa único (msgid PT => tradução EN).
 * - Regista todas as chaves como strings Polylang (ecrã Languages › Strings).
 * - Semeia as traduções EN no PLL_MO da língua 'en' (idempotente: só preenche
 *   o que ainda não está traduzido, por isso edições no backoffice ganham).
 *
 * Ao acrescentar/alterar strings, sobe a versão em LSV_I18N_SEED para re-semear.
 *
 * @package luisseabra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'LSV_I18N_SEED', '2026-09-21-1' );

/**
 * @return array<string,string> msgid PT => tradução EN.
 */
function lsv_i18n_map() {
	return array(
		// Navegação / capítulos.
		'Início'            => 'Home',
		'Sobre'             => 'About',
		'Sobre nós'         => 'About us',
		'Quem somos'        => 'Who we are',
		'Vinhos'            => 'Wines',
		'Vinho'             => 'Wine',
		'Regiões'           => 'Regions',
		'Região'            => 'Region',
		'Vinhas'            => 'Vineyards',
		'Vinha'             => 'Vineyard',
		'Vindimas'          => 'Harvests',
		'Imprensa'          => 'Press',
		'Visitas'           => 'Visits',
		'Onde comprar'      => 'Where to buy',
		'Contactos'         => 'Contact',
		'Menu'              => 'Menu',
		'Fechar'            => 'Close',
		'Capítulos'         => 'Chapters',
		'Loja'              => 'Shop',
		'Mais'              => 'More',
		'Notícias'          => 'News',
		'Todos'             => 'All',
		'Todos os vinhos'   => 'All wines',
		'Ver ficha'         => 'View sheet',
		'Anterior'          => 'Previous',
		'Seguinte'          => 'Next',

		// Hero / landing.
		'Douro · Dão · Vinho Verde — desde 2013' => 'Douro · Dão · Vinho Verde — since 2013',
		'Desça'             => 'Scroll',
		'A gama'            => 'The range',
		'Xisto e granito'   => 'Schist and granite',
		'Xisto e granito.'  => 'Schist and granite.',
		'Prémios e imprensa' => 'Awards and press',
		'Prémios'           => 'Awards',
		'Mailing list'      => 'Mailing list',
		'Avisamos quando abre cada colheita. Duas ou três vezes por ano, nada mais.' => 'We let you know when each vintage opens. Two or three times a year, no more.',
		'Envio para Portugal continental em 48 h · Europa em 5 dias úteis' => 'Shipping to mainland Portugal in 48 h · Europe in 5 working days',
		'Primeira colheita' => 'First harvest',
		'Vinha trabalhada'  => 'Vines farmed',
		'Provas e visitas, por marcação' => 'Tastings and visits, by appointment',

		// Tipos de vinho (subtítulo dos slides).
		'Tinto'             => 'Red',
		'Branco'            => 'White',

		// Botões / formulários.
		'Adicionar'         => 'Add',
		'Subscrever'        => 'Subscribe',
		'Marcar visita'     => 'Book a visit',
		'Importadores'      => 'Importers',
		'Garrafa anterior'  => 'Previous bottle',
		'Garrafa seguinte'  => 'Next bottle',
		'Nome'              => 'Name',
		'Email'             => 'Email',
		'Telefone'          => 'Phone',
		'Data pretendida'   => 'Preferred date',
		'Hora'              => 'Time',
		'Nº de pessoas'     => 'Number of people',
		'Mensagem'          => 'Message',
		'Não preencher'     => 'Do not fill in',
		'O seu email'       => 'Your email',
		'Cidade / País'     => 'City / Country',
		'Perfil'            => 'Profile',
		'Particular'        => 'Private',
		'Restaurante'       => 'Restaurant',
		'Importador'        => 'Importer',
		'Enviar pedido'     => 'Send request',
		'Enviar'            => 'Send',
		'Assunto'           => 'Subject',
		'Contactar'         => 'Contact',
		'Ler mais'          => 'Read more',
		'Em breve.'         => 'Coming soon.',
		'Nada encontrado'   => 'Nothing found',
		'Beba com moderação' => 'Please drink responsibly',

		// Onde comprar.
		'Procura os nossos vinhos?' => 'Looking for our wines?',
		'Diga-nos quem é e onde está. Encaminhamos para o ponto de venda ou distribuidor mais próximo.' => 'Tell us who you are and where you are. We\'ll point you to the nearest point of sale or distributor.',
		'Onde encontrar os vinhos, por mercado. Não encontra perto de si? Escreva-nos.' => 'Where to find the wines, by market. Can\'t find one near you? Write to us.',
		'e garrafeiras.'    => 'and wine shops.',

		// Contactos.
		'Fale'              => 'Talk',
		'connosco.'         => 'to us.',
		'Para provas, visitas, encomendas ou imprensa. Respondemos em dois dias úteis — a vindima é a única exceção.' => 'For tastings, visits, orders or press. We reply within two working days — harvest is the only exception.',
		'Resposta em 48 horas' => 'Reply within 48 hours',
		'Por marcação'      => 'By appointment',
		'Segunda a sexta das 9h00 às 16h00' => 'Monday to Friday, 9am to 4pm',
		'Na adega'          => 'At the cellar',
		'Adega'             => 'Cellar',
		'Sede · Adega'      => 'Head office · Cellar',
		'Armazém'           => 'Warehouse',
		'Escritório'        => 'Office',
		'Escreva-nos'       => 'Write to us',
		'Uma pergunta, uma prova,' => 'A question, a tasting,',
		'uma encomenda.'    => 'an order.',
		'Se for uma visita, indique o dia e o número de pessoas. Para restauração ou importação, o país e o volume aproximado.' => 'If it\'s a visit, tell us the day and number of people. For restaurants or importers, the country and approximate volume.',
		'Novas colheitas, lançamentos limitados.' => 'New vintages, limited releases.',
		'Receba em primeira mão notícias sobre novas colheitas, lançamentos limitados e experiências exclusivas. As mensagens serão pontuais e sempre com propósito — apenas quando houver algo verdadeiramente especial para partilhar.' => 'Be the first to hear about new vintages, limited releases and exclusive experiences. Messages are occasional and always with purpose — only when there is something truly special to share.',
		'Aceito que os meus dados sejam usados apenas para responder a este pedido.' => 'I agree that my data will be used only to reply to this request.',
		'Visita e prova'    => 'Visit and tasting',
		'Encomenda'         => 'Order',
		'Importação / distribuição' => 'Import / distribution',
		'Outro'             => 'Other',
		'Adega Luís Seabra Vinhos' => 'Luís Seabra Vinhos cellar',

		// Visitas.
		'A visita'          => 'The visit',
		'Disponibilidade'   => 'Availability',
		'Prova'             => 'Tasting',
		'Idiomas'           => 'Languages',
		'Local'             => 'Location',
		'Pedir visita'      => 'Request a visit',
		'Provas e visitas,' => 'Tastings and visits,',
		'por marcação.'     => 'by appointment.',
		'Descubra vinhos de terroir, feitos com mínima intervenção e máxima expressão do lugar. As visitas são pensadas para quem procura autenticidade, detalhe e tempo.' => 'Discover terroir wines, made with minimal intervention and maximum expression of place. Visits are designed for those seeking authenticity, detail and time.',
		'Uma prova guiada de vinhos diretamente da barrica e da garrafa.' => 'A guided tasting of wines straight from barrel and bottle.',
		'Português, Inglês, Espanhol (outros consoante disponibilidade)' => 'Portuguese, English, Spanish (others subject to availability)',
		'Estrada Nacional 222, Lugar do Seixinhal, 5130-557 Vilarouco, S. João da Pesqueira, Viseu' => 'Estrada Nacional 222, Lugar do Seixinhal, 5130-557 Vilarouco, S. João da Pesqueira, Viseu',

		// Sobre.
		'Nas palavras do Luís' => 'In Luís\'s words',
		'Filosofia'         => 'Philosophy',
		'Um percurso em seis momentos' => 'A journey in six moments',
		'Após muitos anos a trabalhar para outros, decidi seguir o meu próprio caminho. Em 2013 foi criada a Luís Seabra Vinhos.' => 'After many years working for others, I decided to follow my own path. In 2013 Luís Seabra Vinhos was founded.',
		'Vinhos que expressam' => 'Wines that express',
		'o sítio de onde vêm.' => 'the place they come from.',
		'Intervenção'       => 'Intervention',
		'mínima.'           => 'minimal.',
		'Fermentação espontânea, cachos inteiros, extração muito suave ao longo de até 30 dias.' => 'Spontaneous fermentation, whole bunches, very gentle extraction over up to 30 days.',
		'Leveduras indígenas' => 'Indigenous yeasts',
		'Madeira usada'     => 'Used wood',
		'Estágio em barricas e tonéis usados, sem bâtonnage. Aço inox só quando é indispensável.' => 'Ageing in used barrels and casks, without bâtonnage. Stainless steel only when unavoidable.',
		'Vinhas velhas'     => 'Old vines',
		'Pequenas, remotas, escolhidas pelo solo. Cepas que nunca souberam o que é rega.' => 'Small, remote, chosen for their soil. Vines that never knew irrigation.',

		// Vinhos (catálogo + ficha).
		'Nove vinhos,'      => 'Nine wines,',
		'três regiões.'     => 'three regions.',
		'Dão, Douro e Vinho Verde, com ficha técnica completa. Carregue num vinho para abrir a ficha.' => 'Dão, Douro and Vinho Verde, with full technical sheets. Click a wine to open its sheet.',
		'Ficha técnica'     => 'Technical sheet',
		'Descarregar ficha técnica (PDF)' => 'Download technical sheet (PDF)',
		'Onde comprar este vinho' => 'Where to buy this wine',
		'Ano'               => 'Year',
		'Castas'            => 'Grapes',
		'Origem'            => 'Origin',
		'Solo'              => 'Soil',
		'Idade das vinhas'  => 'Vine age',
		'Plantas por ha'    => 'Vines per ha',
		'Altitude'          => 'Altitude',
		'Fermentação'       => 'Fermentation',
		'Estágio'           => 'Ageing',
		'Acidez total'      => 'Total acidity',
		'pH'                => 'pH',
		'Capacidade'        => 'Capacity',
		'Álcool'            => 'Alcohol',

		// Vindimas.
		'Notas de'          => 'Notes from',
		'colheita.'         => 'the harvest.',
		'Ano a ano, região a região: as condições e as datas de cada vindima.' => 'Year by year, region by region: the conditions and dates of each harvest.',

		// Imprensa.
		'e imprensa.'       => 'and press.',
		'Da vinha à imprensa internacional' => 'From the vineyard to the international press',
		'O que se escreve e ouve sobre os vinhos.' => 'What is written and heard about the wines.',

		// Regiões / Vinhas.
		'As parcelas'       => 'The parcels',
		'Parcelas'          => 'Parcels',
		'Lugar'             => 'Place',
		'Xisto micáceo, 650–700 m' => 'Micaceous schist, 650–700 m',
		'Mapa de Portugal continental com as regiões do Douro, Dão e Vinho Verde.' => 'Map of mainland Portugal with the Douro, Dão and Vinho Verde regions.',
		'Solos, altitudes'  => 'Soils, altitudes',
		'e idades.'         => 'and ages.',
		'Seis parcelas em três regiões. Vinhas velhas, escolhidas pelo solo e pela altitude.' => 'Six parcels across three regions. Old vines, chosen for soil and altitude.',

		// Mensagens de estado dos formulários.
		'Pedido enviado. Respondemos dentro de um dia útil.' => 'Request sent. We\'ll reply within one working day.',
		'Não foi possível enviar. Verifique os campos e tente de novo.' => 'Could not send. Please check the fields and try again.',
		'Pedido enviado. Entramos em contacto em breve.' => 'Request sent. We\'ll be in touch soon.',
		'Mensagem enviada. Respondemos dentro de dois dias úteis.' => 'Message sent. We\'ll reply within two working days.',
		'Subscrição registada. Obrigado.' => 'Subscription registered. Thank you.',
		'Email inválido. Tente de novo.' => 'Invalid email. Please try again.',

		// SEO.
		'Vinhos de xisto e granito do Douro, Dão e Vinho Verde, por Luís Seabra. Vinhas velhas, intervenção mínima, desde 2013.' => 'Schist and granite wines from Douro, Dão and Vinho Verde, by Luís Seabra. Old vines, minimal intervention, since 2013.',
		'Vinhos de xisto e granito do Douro, Dão e Vinho Verde, por Luís Seabra.' => 'Schist and granite wines from Douro, Dão and Vinho Verde, by Luís Seabra.',
	);
}

/**
 * Regista todas as strings do mapa (ecrã Languages › Strings translations).
 */
add_action( 'init', 'lsv_register_i18n_strings', 20 );
function lsv_register_i18n_strings() {
	if ( ! function_exists( 'pll_register_string' ) ) {
		return;
	}
	foreach ( lsv_i18n_map() as $pt => $en ) {
		pll_register_string( sanitize_title( $pt ), $pt, 'luisseabra', strlen( $pt ) > 60 );
	}
}

/**
 * Semeia as traduções EN no PLL_MO (só as que ainda não estão traduzidas).
 */
add_action( 'init', 'lsv_seed_en_translations', 30 );
function lsv_seed_en_translations() {
	if ( get_option( 'lsv_i18n_seed' ) === LSV_I18N_SEED ) {
		return;
	}
	if ( ! class_exists( 'PLL_MO' ) || ! function_exists( 'PLL' ) || ! PLL() || ! PLL()->model ) {
		return;
	}
	$lang = PLL()->model->get_language( 'en' );
	if ( ! $lang ) {
		return;
	}

	$mo = new PLL_MO();
	$mo->import_from_db( $lang );
	$added = 0;
	foreach ( lsv_i18n_map() as $pt => $en ) {
		if ( '' === $en ) {
			continue;
		}
		if ( $mo->translate( $pt ) === $pt ) { // ainda sem tradução.
			$mo->add_entry( new Translation_Entry( array( 'singular' => $pt, 'translations' => array( $en ) ) ) );
			$added++;
		}
	}
	if ( $added ) {
		$mo->export_to_db( $lang );
	}
	update_option( 'lsv_i18n_seed', LSV_I18N_SEED );
}
