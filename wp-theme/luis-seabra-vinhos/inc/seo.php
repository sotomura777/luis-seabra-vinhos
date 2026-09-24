<?php
/**
 * SEO — baseline no código.
 * As metas/OG/JSON-LD abaixo desligam-se automaticamente se instalares um
 * plugin de SEO (Rank Math / Yoast), para não haver tags duplicadas.
 * O resto (alt, permalinks, imagem OG de fallback) é sempre útil.
 *
 * @package luisseabra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Há um plugin de SEO a tratar das metas? */
function lsv_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'RANK_MATH_VERSION' );
}

/** Imagem para partilhas (og:image): garrafa do vinho → destacada → logo/default. */
function lsv_og_image() {
	if ( is_singular( 'vinho' ) ) {
		$img = get_field( 'vinho_garrafa', get_the_ID() );
		if ( $img ) {
			$src = wp_get_attachment_image_url( $img, 'large' );
			if ( $src ) {
				return $src;
			}
		}
	}
	if ( is_singular() && has_post_thumbnail() ) {
		$src = get_the_post_thumbnail_url( get_the_ID(), 'large' );
		if ( $src ) {
			return $src;
		}
	}
	// Fallback: imagem definida no tema (se existir) ou um dos vídeos/garrafas.
	return LSV_URI . '/assets/img/vinhos/bottle-xisto-cru.webp';
}

/** Descrição curta para meta description / og:description. */
function lsv_meta_description() {
	if ( is_front_page() ) {
		return pll__( 'Vinhos de xisto e granito do Douro, Dão e Vinho Verde, por Luís Seabra. Vinhas velhas, intervenção mínima, desde 2013.' );
	}
	if ( is_singular( 'vinho' ) ) {
		$d = get_field( 'vinho_descricao', get_the_ID() );
		if ( $d ) {
			return wp_trim_words( wp_strip_all_tags( $d ), 30 );
		}
	}
	if ( is_singular() ) {
		$p = get_post();
		if ( $p && $p->post_content ) {
			return wp_trim_words( wp_strip_all_tags( $p->post_content ), 30 );
		}
	}
	$tag = get_bloginfo( 'description' );
	return $tag ? $tag : pll__( 'Vinhos de xisto e granito do Douro, Dão e Vinho Verde, por Luís Seabra.' );
}

/* --- Título dos arquivos (Vinhos/Imprensa/Vindimas): nome traduzido em vez de "Imprensa Archive" --- */
function lsv_archive_title( $title ) {
	$labels = array( 'vinho' => 'Vinhos', 'imprensa' => 'Imprensa', 'vindima' => 'Vindimas' );
	foreach ( $labels as $type => $label ) {
		if ( is_post_type_archive( $type ) ) {
			return pll__( $label ) . ' - ' . get_bloginfo( 'name' );
		}
	}
	return $title;
}
add_filter( 'rank_math/frontend/title', 'lsv_archive_title' );
add_filter( 'pre_get_document_title', function ( $title ) {
	return lsv_seo_plugin_active() ? $title : lsv_archive_title( $title );
} );

/* --- Evitar canonical duplicado: usamos o nosso, tiramos o do core --- */
add_action( 'init', function () {
	if ( ! lsv_seo_plugin_active() ) {
		remove_action( 'wp_head', 'rel_canonical' );
	}
} );

/* --- Metas / Open Graph / Twitter (só se NÃO houver plugin de SEO) --- */
add_action( 'wp_head', function () {
	if ( lsv_seo_plugin_active() ) {
		return;
	}
	$title = wp_get_document_title();
	$desc  = lsv_meta_description();
	$url   = home_url( add_query_arg( array(), $GLOBALS['wp']->request ?? '' ) );
	if ( is_singular() ) {
		$url = get_permalink();
	}
	$img = lsv_og_image();

	printf( "<meta name=\"description\" content=\"%s\">\n", esc_attr( $desc ) );
	printf( "<link rel=\"canonical\" href=\"%s\">\n", esc_url( $url ) );
	printf( "<meta property=\"og:site_name\" content=\"%s\">\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( "<meta property=\"og:title\" content=\"%s\">\n", esc_attr( $title ) );
	printf( "<meta property=\"og:description\" content=\"%s\">\n", esc_attr( $desc ) );
	printf( "<meta property=\"og:type\" content=\"%s\">\n", is_singular( 'vinho' ) ? 'product' : 'website' );
	printf( "<meta property=\"og:url\" content=\"%s\">\n", esc_url( $url ) );
	printf( "<meta property=\"og:image\" content=\"%s\">\n", esc_url( $img ) );
	printf( "<meta name=\"twitter:card\" content=\"summary_large_image\">\n" );
}, 5 );

/* --- JSON-LD Organization (identidade do produtor) — só na home --- */
add_action( 'wp_head', function () {
	if ( lsv_seo_plugin_active() || ! is_front_page() ) {
		return;
	}
	$data = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Organization',
		'name'     => get_bloginfo( 'name' ),
		'url'      => home_url( '/' ),
		'logo'     => LSV_URI . '/screenshot.png',
		'sameAs'   => array(
			'https://www.instagram.com/lseabrawine',
			'https://www.facebook.com/luis.seabra.vinhos',
			'https://x.com/lseabrawine',
		),
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $data ) . "</script>\n";
} );
