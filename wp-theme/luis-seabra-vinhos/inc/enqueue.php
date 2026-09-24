<?php
/**
 * Estilos e scripts.
 *
 * @package luisseabra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', 'lsv_enqueue_assets' );

function lsv_enqueue_assets() {

	// Fontes: Bodoni Moda (títulos) + Jost (corpo).
	wp_enqueue_style(
		'lsv-fonts',
		'https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400;0,6..96,500;1,6..96,400&family=Jost:wght@200;300;400;500&display=swap',
		array(),
		null
	);

	// CSS do tema (header do WP em style.css; sistema visual em theme.css).
	wp_enqueue_style( 'lsv-style', get_stylesheet_uri(), array( 'lsv-fonts' ), LSV_VERSION );
	wp_enqueue_style( 'lsv-theme', LSV_URI . '/assets/css/theme.css', array( 'lsv-style' ), LSV_VERSION );

	// Carrossel 3D (inalterado do site estático; injeta o próprio CSS).
	wp_enqueue_script( 'lsv-carousel', LSV_URI . '/assets/js/carousel.js', array(), LSV_VERSION, true );

	// Scrubber do hero + menu (portado do runtime DC).
	wp_enqueue_script( 'lsv-site', LSV_URI . '/assets/js/site.js', array(), LSV_VERSION, true );

	// Página Regiões: mapa D3 (só aqui, CDN + SRI).
	if ( is_page_template( 'page-regioes.php' ) ) {
		wp_enqueue_script( 'd3', 'https://unpkg.com/d3@7.9.0/dist/d3.min.js', array(), '7.9.0', true );
		wp_enqueue_script( 'lsv-regioes-map', LSV_URI . '/assets/js/regioes-map.js', array( 'd3' ), LSV_VERSION, true );
		wp_localize_script( 'lsv-regioes-map', 'LSV_MAP', array(
			'mapUrl'    => LSV_URI . '/assets/data/mapa-pt.json',
			'vinhosUrl' => get_post_type_archive_link( 'vinho' ),
			'i18n'      => array_combine( array_keys( lsv_i18n_regioes() ), array_map( 'pll__', array_keys( lsv_i18n_regioes() ) ) ),
		) );
	}

	// Página Contactos: mapa Leaflet (só aqui, CDN + SRI). Tiles OSM = pedido externo (nota RGPD na política).
	if ( is_page_template( 'page-contactos.php' ) ) {
		wp_enqueue_style( 'leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', array(), '1.9.4' );
		wp_enqueue_script( 'leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true );
		wp_enqueue_script( 'lsv-contactos-map', LSV_URI . '/assets/js/contactos-map.js', array( 'leaflet' ), LSV_VERSION, true );
	}
}

/**
 * Subresource Integrity nos scripts/estilos de CDN (defesa contra CDN comprometido).
 */
add_filter( 'style_loader_tag', 'lsv_sri_tag', 10, 2 );
add_filter( 'script_loader_tag', 'lsv_sri_tag', 10, 2 );
function lsv_sri_tag( $tag, $handle ) {
	$sri = array(
		'd3'      => 'sha384-CjloA8y00+1SDAUkjs099PVfnY2KmDC2BZnws9kh8D/lX1s46w6EPhpXdqMfjK6i',
		'leaflet' => ( false !== strpos( $tag, '.css' ) )
			? 'sha384-sHL9NAb7lN7rfvG5lfHpm643Xkcjzp4jFvuavGOndn6pjVqS6ny56CAt3nsEVT4H'
			: 'sha384-cxOPjt7s7Iz04uaHJceBmS+qpjv2JkIHNVcuOrM+YHwZOmJGBXI00mdUXEq65HTH',
	);
	if ( isset( $sri[ $handle ] ) ) {
		$attr = ' integrity="' . esc_attr( $sri[ $handle ] ) . '" crossorigin="anonymous"';
		$tag  = str_replace( ' src=', $attr . ' src=', $tag );
		$tag  = str_replace( " href='", $attr . " href='", $tag );
		$tag  = str_replace( ' href="', $attr . ' href="', $tag );
	}
	return $tag;
}

/**
 * Preconnect às fontes (pequena optimização, como no original).
 */
add_action( 'wp_head', function () {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
	echo '<meta name="theme-color" content="#000000">' . "\n";
}, 1 );
