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

define( 'LSV_I18N_SEED', '2026-09-24-2' );

/**
 * @return array<string,string> msgid PT => tradução EN.
 */
function lsv_i18n_map() {
	return array_merge( array(
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
	), lsv_i18n_sobre(), lsv_i18n_regioes(), lsv_i18n_legal() );
}

/**
 * Página Sobre: cronologia e testemunho (page-sobre.php).
 *
 * @return array<string,string>
 */
function lsv_i18n_sobre() {
	return array(
		'Formação'       => 'Education',
		'Carreira'       => 'Career',
		'Primeiros anos' => 'Early years',
		'Reconhecimento' => 'Recognition',
		'Hoje'           => 'Today',
		'Estudos e solos' => 'Studies and soils',
		'Enólogo na Niepoort' => 'Winemaker at Niepoort',
		'Nasce a Luis Seabra Vinhos' => 'Luis Seabra Vinhos is born',
		'Uma estreia em Lisboa' => 'A debut in Lisbon',
		'N.º 4 — Top 100 Portugal 2025' => 'No. 4 — Top 100 Portugal 2025',
		'Três regiões, uma ideia' => 'Three regions, one idea',
		'Estuda viticultura na universidade e trabalha em investigação de solos antes de entrar na produção de vinho.' => 'Studies viticulture at university and works in soil research before moving into winemaking.',
		'Faz nome como enólogo da Niepoort, onde trabalha durante uma década antes de seguir o seu próprio caminho.' => 'Makes his name as winemaker at Niepoort, where he works for a decade before going his own way.',
		'Primeiras colheitas no Douro e no Vinho Verde, com a missão de criar vinhos que expressem o sítio de onde vêm.' => 'First harvests in the Douro and Vinho Verde, with the mission of making wines that express the place they come from.',
		'A primeira vindima chega à feira Vinhos e Sabores. Um crítico prova num pequeno stand e sai a perguntar quem fez aquilo.' => 'The first vintage reaches the Vinhos e Sabores fair. A critic tastes it at a small stand and walks away asking who made it.',
		'O Xisto Cru Branco 2023 entra no Top 100 de James Suckling com 97 pontos. O Xisto Cru 2022 tinto figura na posição 73.' => 'Xisto Cru Branco 2023 enters James Suckling\'s Top 100 with 97 points. The red Xisto Cru 2022 is ranked No. 73.',
		'Dão, Douro e Vinho Verde. Vinhas velhas, intervenção mínima e uma equipa que inclui o enólogo Frederico Ferreira.' => 'Dão, Douro and Vinho Verde. Old vines, minimal intervention and a team that includes winemaker Frederico Ferreira.',
		'Após muitos anos a trabalhar para outros, decidi seguir o meu próprio caminho, em 2013 foi criada a Luis Seabra Vinhos, com a missão de criar vinhos que expressem o sítio de onde vêem, feitos com uma filosofia de intervenção mínima respeitando a sua natureza e o seu carácter. Queremos que os vinhos mostrem os diferentes solos onde estão plantadas as vinhas, as diferentes altitudes e exposições, as suas diferenças e semelhanças. Queremos acima de tudo criar vinhos que sejam únicos. Com a consciência que estes são apenas os primeiros passos de um longo caminho, a aventura é ainda descobrir vinhas velhas desconhecidas que vão resistindo ao longo dos anos. Ao longo dos anos que fui trabalhando na região, criei uma relação especial com estas vinhas velhas.' => 'After many years working for others, I decided to follow my own path, and in 2013 Luis Seabra Vinhos was founded, with the mission of making wines that express the place they come from, made with a minimal-intervention philosophy that respects their nature and character. We want the wines to show the different soils where the vines are planted, the different altitudes and exposures, their differences and similarities. Above all, we want to make wines that are unique. Knowing that these are only the first steps on a long road, the adventure is still to discover unknown old vineyards that have held out over the years. Over the years I have worked in the region, I have built a special relationship with these old vines.',
	);
}

/**
 * Mapa das Regiões (assets/js/regioes-map.js) e tabela de parcelas (page-regioes.php).
 * As chaves também seguem para o JS (window.LSV_MAP.i18n), já traduzidas.
 *
 * @return array<string,string>
 */
function lsv_i18n_regioes() {
	return array(
		'Xisto micáceo, dos 400 aos 700 metros' => 'Mica schist, from 400 to 700 metres',
		'Granito à sombra da Serra da Estrela' => 'Granite in the shadow of Serra da Estrela',
		'Um só vinho, em Monção e Melgaço' => 'A single wine, in Monção e Melgaço',
		'O Douro é uma das mais antigas regiões vinícolas demarcadas do mundo, com uma história de viticultura que remonta ao século XVIII. Os vinhos aqui nascem de encostas dramáticas, talhadas em xisto, onde a vinha desafia a gravidade e o clima se exprime com intensidade. É uma região de contrastes – entre altitudes, exposições solares e microclimas – que oferece um terroir complexo e vibrante. Na Luis Seabra Vinhos, acreditamos que o Douro não é apenas monumental – é subtil. Procuramos vinhos que traduzam a origem, respeitando a singularidade de cada parcela, sem maquilhagem. É aqui que as castas autóctones encontram a sua expressão mais autêntica – em brancos de notável frescura e profundidade, e tintos com estrutura, precisão e grande capacidade de envelhecimento.' => 'The Douro is one of the oldest demarcated wine regions in the world, with a history of viticulture dating back to the 18th century. Its wines are born on dramatic slopes carved into schist, where the vines defy gravity and the climate expresses itself with intensity. It is a region of contrasts – between altitudes, sun exposures and microclimates – offering a complex, vibrant terroir. At Luis Seabra Vinhos, we believe the Douro is not only monumental – it is subtle. We look for wines that convey their origin, respecting the singularity of each plot, without make-up. This is where native grape varieties find their most authentic expression – in whites of remarkable freshness and depth, and reds with structure, precision and great ageing potential.',
		'O Dão é uma das regiões mais antigas e distintas de Portugal, encravada entre serras – Estrela, Caramulo e Buçaco – esta região beneficia de um clima continental moderado, com noites frescas e solos graníticos que promovem vinhos equilibrados, estruturados e longevos. Trabalhar no Dão é explorar a harmonia entre natureza e tradição. As vinhas antigas, muitas vezes em campo misto, dão origem a vinhos que revelam um lado mais contido, fresco e subtil. É essa expressão serena mas profunda que procuramos preservar, vinificando com respeito e parcimónia, deixando que o lugar fale mais alto do que a mão do enólogo.' => 'The Dão is one of Portugal\'s oldest and most distinctive regions. Set between mountain ranges – Estrela, Caramulo and Buçaco – it enjoys a moderate continental climate, with cool nights and granite soils that produce balanced, structured, long-lived wines. Working in the Dão means exploring the harmony between nature and tradition. The old vineyards, often field blends, give wines that reveal a more restrained, fresh and subtle side. It is this serene yet profound expression that we seek to preserve, vinifying with respect and restraint, letting the place speak louder than the winemaker\'s hand.',
		'A região dos Vinhos Verdes estende-se pelo noroeste de Portugal, onde o verde da paisagem encontra a influência constante do Atlântico. É uma terra de contrastes, marcada por chuvas generosas, encostas soalheiras e solos graníticos que conferem identidade e frescura aos vinhos. Aqui a tradição convive com a diversidade, permitindo múltiplas expressões da mesma origem. Entre as várias sub-regiões, Monção e Melgaço distinguem-se. O seu microclima, protegido pelas serras e beneficiado por noites frescas, oferece condições únicas para vinhos de grande intensidade, precisão e longevidade. Nos Vinhos Verdes trabalhamos para revelar esta pluralidade: vinhos leves e vibrantes, vinhos mais densos e profundos – todos com a frescura como fio condutor. Procuramos respeitar o caráter próprio de cada lugar, deixando que seja a região, em toda a sua diversidade, a falar através de cada garrafa.' => 'The Vinho Verde region stretches across north-western Portugal, where the green of the landscape meets the constant influence of the Atlantic. It is a land of contrasts, marked by generous rainfall, sunny slopes and granite soils that give the wines identity and freshness. Here tradition lives alongside diversity, allowing many expressions of the same origin. Among its sub-regions, Monção e Melgaço stands out. Its microclimate, sheltered by the mountains and blessed with cool nights, offers unique conditions for wines of great intensity, precision and longevity. In Vinho Verde we work to reveal this plurality: light, vibrant wines and denser, deeper ones – all with freshness as the common thread. We seek to respect the character of each place, letting the region, in all its diversity, speak through every bottle.',
		'Solo'           => 'Soil',
		'Altitude'       => 'Altitude',
		'Vinhas'         => 'Vineyards',
		'Condução'       => 'Winemaking',
		'Lugar'          => 'Place',
		'Castas'         => 'Grapes',
		'Sub-região'     => 'Sub-region',
		'Casta'          => 'Grape',
		'Estágio'        => 'Ageing',
		'Xisto micáceo e de transição' => 'Mica and transition schist',
		'Plantadas entre 1920 e 1933' => 'Planted between 1920 and 1933',
		'Cachos inteiros, pouca extração' => 'Whole bunches, light extraction',
		'Granito'        => 'Granite',
		'Xisto'          => 'Schist',
		'Mais de 35 anos' => 'Over 35 years',
		'Xisto Cru Tinto' => 'Xisto Cru Red',
		'Xisto Cru Branco' => 'Xisto Cru White',
		'Granito Cru Branco' => 'Granito Cru White',
		'21 concelhos'   => '21 municipalities',
		'17 concelhos'   => '17 municipalities',
		'Três regiões'   => 'Three regions',
		'Xisto e granito' => 'Schist and granite',
		'Três regiões · oito hectares' => 'Three regions · eight hectares',
		'As vinhas não foram escolhidas por região, mas por solo. Xisto micáceo no Douro, granito no Dão e no vale do Minho — as mesmas castas dariam vinhos diferentes em cada um deles.' => 'The vineyards were not chosen by region but by soil. Mica schist in the Douro, granite in the Dão and the Minho valley — the same grapes would give different wines in each.',
		'Carregue numa região do mapa. As manchas mais fortes são os concelhos onde estão as vinhas.' => 'Click a region on the map. The darker patches are the municipalities where the vineyards are.',
		'Ver tudo'       => 'View all',
	);
}

/**
 * Privacidade: aviso de cookies, rodapé, nota nos formulários, Spotify.
 *
 * @return array<string,string>
 */
function lsv_i18n_legal() {
	return array(
		'Política de Privacidade' => 'Privacy Policy',
		'Preferências de cookies' => 'Cookie preferences',
		'Usamos cookies de estatística (Google Analytics) para perceber como o site é visitado. Só são ativados se aceitar.' => 'We use statistics cookies (Google Analytics) to understand how the site is visited. They are only activated if you accept.',
		'Aceitar'        => 'Accept',
		'Recusar'        => 'Decline',
		'Ao enviar, os seus dados são tratados de acordo com a nossa' => 'By sending, your data is handled in accordance with our',
		'Ouvir episódio' => 'Listen to episode',
		'Ao carregar, o leitor do Spotify é aberto e pode guardar cookies.' => 'Clicking opens the Spotify player, which may set cookies.',
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
