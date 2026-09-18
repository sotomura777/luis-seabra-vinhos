<?php
/**
 * Seeder de conteúdo (correr UMA vez, depois apagar este ficheiro).
 * Uso, no shell do site LocalWP:
 *   wp eval-file wp-content/themes/luis-seabra-vinhos/inc/seed-vinhos.php
 *
 * Cria os 9 vinhos (PT), as 3 regiões e 3 posts "vinha" com o texto das regiões,
 * importando as garrafas de assets/img/vinhos/ para a Media Library.
 * Idempotente: não duplica (dedup por título+tipo+ano nos vinhos, por região nas vinhas).
 *
 * @package luisseabra
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return; // Só via WP-CLI.
}

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

/**
 * Importa um ficheiro local do tema para a Media Library e devolve o attachment ID.
 */
function lsv_seed_sideload( $filename ) {
	$path = get_template_directory() . '/assets/img/vinhos/' . $filename;
	if ( ! file_exists( $path ) ) {
		WP_CLI::warning( "Imagem não encontrada: $filename" );
		return 0;
	}
	// Evita reimportar se já existe um attachment com este slug.
	$slug     = sanitize_title( pathinfo( $filename, PATHINFO_FILENAME ) );
	$existing = get_page_by_path( $slug, OBJECT, 'attachment' );
	if ( $existing ) {
		return (int) $existing->ID;
	}

	$upload = wp_upload_bits( $filename, null, file_get_contents( $path ) );
	if ( ! empty( $upload['error'] ) ) {
		WP_CLI::warning( 'Upload falhou: ' . $upload['error'] );
		return 0;
	}
	$attach_id = wp_insert_attachment( array(
		'post_mime_type' => wp_check_filetype( $upload['file'] )['type'],
		'post_title'     => $slug,
		'post_name'      => $slug,
		'post_status'    => 'inherit',
	), $upload['file'] );
	wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $upload['file'] ) );
	return (int) $attach_id;
}

/* ---- Garantir regiões ---- */
$regions = array( 'Douro' => 'douro', 'Dão' => 'dao', 'Vinho Verde' => 'vinho-verde' );
foreach ( $regions as $name => $slug ) {
	if ( ! term_exists( $slug, 'regiao' ) ) {
		wp_insert_term( $name, 'regiao', array( 'slug' => $slug ) );
	}
}

/* ---- Vinhos ---- */
$vinhos = array(
	array( 'Xisto Cru', 'douro', 'branco', 2023, '42 €', 'Vinha única a 650–700 m, plantada entre 1920 e 1933. Rabigato com Códega, Gouveio e Viosinho.', 'bottle-xisto-cru.webp' ),
	array( 'Indie Xisto', 'douro', 'tinto', 2023, '28 €', 'Vinha única em Alijó. Tinta Roriz à cabeça, com Touriga Franca, Tinta Amarela, Rufete e Tinta Barroca.', 'bottle-indie-xisto.webp' ),
	array( 'Mono M', 'douro', 'tinto', 2023, '34 €', 'Monocasta do Douro, de um só lugar.', 'bottle-mono-m.webp' ),
	array( 'Mono C', 'douro', 'tinto', 2023, '34 €', 'Castelão em monocasta.', 'bottle-mono-c.webp' ),
	array( 'Xisto Ilimitado', 'douro', 'tinto', 2024, '24 €', 'O vinho de aldeia da casa: vários lugares do Douro num só blend, cachos inteiros e pouca extração.', 'bottle-xisto-ilimitado-tinto.webp' ),
	array( 'Xisto Ilimitado', 'douro', 'branco', 2024, '22 €', 'Xisto, mica e feldspato a 400–500 m. Field blend de vinhas com 30 a 60 anos, sem maloláctica nem bâtonnage.', 'bottle-xisto-ilimitado-branco.webp' ),
	array( 'Granito Cru', 'dao', 'branco', 2023, '32 €', 'Encruzado com Bical e Cercial, em granito no sopé da Serra da Estrela. Vinhas com mais de 35 anos.', 'bottle-granito-cru-dao.webp' ),
	array( 'Mono A', 'dao', 'tinto', 2022, '34 €', 'Alfrocheiro em monocasta, do Dão.', 'bottle-mono-a.webp' ),
	array( 'Granito Cru Alvarinho', 'vinho-verde', 'branco', 2023, '30 €', 'Alvarinho de Melgaço criado em foudre. Granito, tensão redutiva e um travo salgado no fim.', 'bottle-granito-cru-alvarinho.webp' ),
);

$order = 0;
foreach ( $vinhos as $w ) {
	list( $title, $regiao_slug, $tipo, $ano, $preco, $desc, $webp ) = $w;
	$order += 10;

	// Dedup por título+tipo+ano.
	$dup = get_posts( array(
		'post_type'   => 'vinho',
		'title'       => $title,
		'post_status' => 'any',
		'numberposts' => -1,
		'fields'      => 'ids',
	) );
	$skip = false;
	foreach ( $dup as $did ) {
		if ( (int) get_field( 'vinho_ano', $did ) === (int) $ano && get_field( 'vinho_tipo', $did ) === $tipo ) {
			$skip = true;
			break;
		}
	}
	if ( $skip ) {
		WP_CLI::log( "· já existe: $title ($tipo $ano)" );
		continue;
	}

	$post_id = wp_insert_post( array(
		'post_type'   => 'vinho',
		'post_title'  => $title,
		'post_status' => 'publish',
		'menu_order'  => $order,
	) );
	if ( is_wp_error( $post_id ) || ! $post_id ) {
		WP_CLI::warning( "Falhou: $title" );
		continue;
	}

	$rterm = get_term_by( 'slug', $regiao_slug, 'regiao' );
	if ( $rterm ) {
		wp_set_object_terms( $post_id, array( (int) $rterm->term_id ), 'regiao' );
	}
	update_field( 'vinho_tipo', $tipo, $post_id );
	update_field( 'vinho_ano', $ano, $post_id );
	update_field( 'vinho_preco', $preco, $post_id );
	update_field( 'vinho_descricao', $desc, $post_id );

	$img_id = lsv_seed_sideload( $webp );
	if ( $img_id ) {
		update_field( 'vinho_garrafa', $img_id, $post_id );
	}

	if ( function_exists( 'pll_set_post_language' ) ) {
		pll_set_post_language( $post_id, 'pt' );
	}

	WP_CLI::success( "Vinho criado: $title ($tipo $ano)" );
}

/* ---- Vinhas (texto das regiões) ---- */
$vinhas = array(
	array( 'douro', 'Douro Superior', 'Xisto micáceo, 400 a 700 metros', 'As vinhas estão em Alvites e Meda, no Douro Superior: altitude, solos de xisto e cepas que nunca souberam o que é rega. É daqui que saem os Xisto Cru e os Ilimitado.' ),
	array( 'dao', 'Dão', 'Granito à sombra da Serra da Estrela', 'Uma parcela em Vila Nova de Tazém, no sopé da serra mais alta de Portugal continental. Clima fresco, granito e brancos de linha tensa.' ),
	array( 'vinho-verde', 'Vinho Verde', 'Granito e foudre, sem pressa', 'O Vinho Verde dá um só vinho, o Granito Cru Alvarinho — dos mais procurados da casa. Granito, foudre e sem pressa.' ),
);

$vorder = 0;
foreach ( $vinhas as $v ) {
	list( $regiao_slug, $title, $subtitulo, $body ) = $v;
	$vorder += 10;

	$exists = get_posts( array(
		'post_type'   => 'vinha',
		'title'       => $title,
		'post_status' => 'any',
		'numberposts' => 1,
		'fields'      => 'ids',
	) );
	if ( $exists ) {
		WP_CLI::log( "· vinha já existe: $title" );
		continue;
	}

	$vid = wp_insert_post( array(
		'post_type'    => 'vinha',
		'post_title'   => $title,
		'post_content' => $body,
		'post_status'  => 'publish',
		'menu_order'   => $vorder,
	) );
	if ( is_wp_error( $vid ) || ! $vid ) {
		continue;
	}
	$vterm = get_term_by( 'slug', $regiao_slug, 'regiao' );
	if ( $vterm ) {
		wp_set_object_terms( $vid, array( (int) $vterm->term_id ), 'regiao' );
	}
	update_field( 'vinha_subtitulo', $subtitulo, $vid );
	if ( function_exists( 'pll_set_post_language' ) ) {
		pll_set_post_language( $vid, 'pt' );
	}
	WP_CLI::success( "Vinha criada: $title" );
}

WP_CLI::success( 'Seed concluído. Apague este ficheiro (inc/seed-vinhos.php).' );
