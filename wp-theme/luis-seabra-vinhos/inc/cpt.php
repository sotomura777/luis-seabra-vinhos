<?php
/**
 * Custom Post Types + taxonomia regiao.
 *
 * @package luisseabra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'lsv_register_content_types' );

function lsv_register_content_types() {

	// --- Taxonomia partilhada: Região ---
	register_taxonomy( 'regiao', array( 'vinho', 'vinha', 'vindima' ), array(
		'labels'             => array(
			'name'          => __( 'Regiões', 'luisseabra' ),
			'singular_name' => __( 'Região', 'luisseabra' ),
			'menu_name'     => __( 'Regiões', 'luisseabra' ),
			'add_new_item'  => __( 'Adicionar região', 'luisseabra' ),
			'edit_item'     => __( 'Editar região', 'luisseabra' ),
		),
		'public'            => true,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'regiao' ),
	) );

	// --- CPT: Vinho ---
	register_post_type( 'vinho', array(
		'labels'       => lsv_cpt_labels( 'Vinho', 'Vinhos' ),
		'public'       => true,
		'has_archive'  => 'vinhos',
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-carrot',
		'supports'     => array( 'title', 'thumbnail', 'page-attributes' ),
		'rewrite'      => array( 'slug' => 'vinhos' ),
	) );

	// --- CPT: Vinha (alimenta a secção Regiões) ---
	register_post_type( 'vinha', array(
		'labels'       => lsv_cpt_labels( 'Vinha', 'Vinhas' ),
		'public'       => true,
		'has_archive'  => false,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-location',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		'rewrite'      => array( 'slug' => 'vinhas' ),
	) );

	// --- CPT: Imprensa (secção Notícias) ---
	register_post_type( 'imprensa', array(
		'labels'       => lsv_cpt_labels( 'Imprensa', 'Imprensa' ),
		'public'       => true,
		'has_archive'  => 'imprensa',
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-awards',
		'supports'     => array( 'title', 'editor', 'page-attributes' ),
		'rewrite'      => array( 'slug' => 'imprensa' ),
	) );

	// --- CPT: Vindima (registado; secção front numa fase seguinte) ---
	register_post_type( 'vindima', array(
		'labels'       => lsv_cpt_labels( 'Vindima', 'Vindimas' ),
		'public'       => true,
		'has_archive'  => 'vindimas',
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-calendar-alt',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		'rewrite'      => array( 'slug' => 'vindimas' ),
	) );

	// --- CPTs PRIVADOS (dados de formulários; nunca visíveis no site) ---
	// Marcações de visita.
	register_post_type( 'reserva', array(
		'labels'              => lsv_cpt_labels( 'Reserva', 'Reservas' ),
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_icon'           => 'dashicons-calendar-alt',
		'menu_position'       => 26,
		'supports'            => array( 'title' ),
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'post',
		'map_meta_cap'        => true,
	) );

	// Pedidos "Onde comprar".
	register_post_type( 'pedido_compra', array(
		'labels'              => lsv_cpt_labels( 'Pedido', 'Onde comprar' ),
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_icon'           => 'dashicons-cart',
		'menu_position'       => 27,
		'supports'            => array( 'title' ),
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
	) );

	// Subscritores da newsletter.
	register_post_type( 'subscritor', array(
		'labels'              => lsv_cpt_labels( 'Subscritor', 'Subscritores' ),
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_icon'           => 'dashicons-email',
		'menu_position'       => 28,
		'supports'            => array( 'title' ),
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
	) );
}

/**
 * Gera o array de labels de um CPT.
 */
function lsv_cpt_labels( $singular, $plural ) {
	return array(
		'name'               => $plural,
		'singular_name'      => $singular,
		'menu_name'          => $plural,
		'add_new'            => __( 'Adicionar', 'luisseabra' ),
		'add_new_item'       => sprintf( __( 'Adicionar %s', 'luisseabra' ), $singular ),
		'edit_item'          => sprintf( __( 'Editar %s', 'luisseabra' ), $singular ),
		'new_item'           => sprintf( __( 'Novo %s', 'luisseabra' ), $singular ),
		'view_item'          => sprintf( __( 'Ver %s', 'luisseabra' ), $singular ),
		'search_items'       => sprintf( __( 'Procurar %s', 'luisseabra' ), $plural ),
		'not_found'          => __( 'Nada encontrado', 'luisseabra' ),
		'all_items'          => $plural,
	);
}

/**
 * Garante que os 3 termos de região existem (idempotente).
 * Corre no seeder, mas também aqui como segurança na ativação do tema.
 */
add_action( 'after_switch_theme', 'lsv_seed_regions' );

function lsv_seed_regions() {
	$regions = array(
		'Douro'       => 'douro',
		'Dão'         => 'dao',
		'Vinho Verde' => 'vinho-verde',
	);
	foreach ( $regions as $name => $slug ) {
		if ( ! term_exists( $slug, 'regiao' ) ) {
			wp_insert_term( $name, 'regiao', array( 'slug' => $slug ) );
		}
	}
	flush_rewrite_rules();
}
