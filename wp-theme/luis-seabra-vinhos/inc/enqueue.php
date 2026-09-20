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
}

/**
 * Preconnect às fontes (pequena optimização, como no original).
 */
add_action( 'wp_head', function () {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
	echo '<meta name="theme-color" content="#000000">' . "\n";
}, 1 );
