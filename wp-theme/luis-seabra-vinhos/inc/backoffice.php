<?php
/**
 * Melhorias de backoffice para o cliente gerir sem programar.
 *
 * @package luisseabra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* --- Widget no painel: marcações pendentes --- */
add_action( 'wp_dashboard_setup', function () {
	wp_add_dashboard_widget( 'lsv_reservas_widget', 'Marcações pendentes', 'lsv_reservas_dashboard_widget' );
} );

function lsv_reservas_dashboard_widget() {
	$pend = get_posts( array(
		'post_type'      => 'reserva',
		'posts_per_page' => 8,
		'post_status'    => 'publish',
		'meta_query'     => array( array( 'key' => 'reserva_estado', 'value' => 'pendente' ) ),
		'orderby'        => 'date',
		'order'          => 'DESC',
	) );

	$total = count( get_posts( array(
		'post_type'      => 'reserva',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'meta_query'     => array( array( 'key' => 'reserva_estado', 'value' => 'pendente' ) ),
	) ) );

	if ( ! $pend ) {
		echo '<p>Sem marcações pendentes.</p>';
	} else {
		printf( '<p><strong>%d</strong> marcaç%s pendente%s.</p>', (int) $total, 1 === $total ? 'ão' : 'ões', 1 === $total ? '' : 's' );
		echo '<ul style="margin:0">';
		foreach ( $pend as $r ) {
			$data = get_field( 'reserva_data', $r->ID );
			$data = $data ? date_i18n( 'd/m/Y', strtotime( $data ) ) : '';
			printf(
				'<li style="border-top:1px solid #eee;padding:6px 0"><a href="%s"><strong>%s</strong></a> — %s · %s pessoa(s)</li>',
				esc_url( get_edit_post_link( $r->ID ) ),
				esc_html( get_field( 'reserva_nome', $r->ID ) ),
				esc_html( $data ),
				esc_html( (string) get_field( 'reserva_pessoas', $r->ID ) )
			);
		}
		echo '</ul>';
	}
	printf(
		'<p style="margin-top:10px"><a class="button" href="%s">Ver todas as marcações</a></p>',
		esc_url( admin_url( 'edit.php?post_type=reserva' ) )
	);
}

/* --- Contador de pendentes no menu (bolha vermelha, como os comentários) --- */
add_filter( 'add_menu_classes', function ( $menu ) {
	$pend = count( get_posts( array(
		'post_type'      => 'reserva',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'meta_query'     => array( array( 'key' => 'reserva_estado', 'value' => 'pendente' ) ),
	) ) );
	if ( ! $pend ) {
		return $menu;
	}
	foreach ( $menu as $k => $item ) {
		if ( isset( $item[2] ) && 'edit.php?post_type=reserva' === $item[2] ) {
			$menu[ $k ][0] .= sprintf( ' <span class="awaiting-mod"><span class="pending-count">%d</span></span>', (int) $pend );
		}
	}
	return $menu;
} );

/* --- Lista de vinhos: colunas com miniatura, região, tipo, ano, preço --- */
add_filter( 'manage_vinho_posts_columns', function ( $cols ) {
	$new = array( 'cb' => $cols['cb'], 'lsv_thumb' => '' );
	$new['title']     = 'Vinho';
	$new['lsv_reg']   = 'Região';
	$new['lsv_tipo']  = 'Tipo';
	$new['lsv_ano']   = 'Ano';
	$new['lsv_preco'] = 'Preço';
	$new['menu_order'] = 'Ordem';
	return $new;
} );
add_action( 'manage_vinho_posts_custom_column', function ( $col, $post_id ) {
	switch ( $col ) {
		case 'lsv_thumb':
			$img = get_field( 'vinho_garrafa', $post_id );
			if ( $img ) {
				echo wp_get_attachment_image( $img, array( 40, 60 ), false, array( 'style' => 'height:52px;width:auto;object-fit:contain' ) );
			}
			break;
		case 'lsv_reg':
			$t = get_the_terms( $post_id, 'regiao' );
			echo esc_html( ( $t && ! is_wp_error( $t ) ) ? $t[0]->name : '—' );
			break;
		case 'lsv_tipo':
			$tipo = get_field( 'vinho_tipo', $post_id );
			echo esc_html( $tipo ? ucfirst( $tipo ) : '—' );
			break;
		case 'lsv_ano':
			echo esc_html( (string) get_field( 'vinho_ano', $post_id ) );
			break;
		case 'lsv_preco':
			echo esc_html( (string) get_field( 'vinho_preco', $post_id ) );
			break;
		case 'menu_order':
			echo (int) get_post_field( 'menu_order', $post_id );
			break;
	}
}, 10, 2 );

/* --- Ordenar a lista de vinhos por "Ordem" por defeito --- */
add_action( 'pre_get_posts', function ( $q ) {
	if ( is_admin() && $q->is_main_query() && 'vinho' === $q->get( 'post_type' ) && ! $q->get( 'orderby' ) ) {
		$q->set( 'orderby', 'menu_order' );
		$q->set( 'order', 'ASC' );
	}
} );

/* --- Rodapé do admin discreto (marca) --- */
add_filter( 'admin_footer_text', function () {
	return 'Luís Seabra Vinhos — backoffice';
} );
