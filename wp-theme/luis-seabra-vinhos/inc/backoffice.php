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

/* --- Lista de vinhos: colunas com miniatura, região, tipo, ano --- */
add_filter( 'manage_vinho_posts_columns', function ( $cols ) {
	$new = array( 'cb' => $cols['cb'], 'lsv_thumb' => '' );
	$new['title']     = 'Vinho';
	$new['lsv_reg']   = 'Região';
	$new['lsv_tipo']  = 'Tipo';
	$new['lsv_ano']   = 'Ano';
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

/* ==========================================================================
   ADMIN SIMPLIFICADO — quem não é administrador (perfil "cliente"/editor)
   vê só o essencial. O administrador continua a ver tudo.
   ========================================================================== */

/* Esconde os menus técnicos e dá um atalho directo aos "Textos do site". */
add_action( 'admin_menu', function () {
	if ( current_user_can( 'manage_options' ) ) {
		return; // administrador vê tudo
	}
	remove_menu_page( 'edit.php' );                // Publicações (blog — não é usado)
	remove_menu_page( 'edit-comments.php' );       // Comentários
	remove_menu_page( 'tools.php' );               // Ferramentas
	remove_menu_page( 'edit.php?post_type=page' ); // Páginas (o conteúdo é gerido em Textos do site + nos menus próprios)
	remove_menu_page( 'rank-math' );               // Rank Math SEO (técnico; o SEO de cada página edita-se dentro do post)

	// Atalho directo para a página de textos editáveis (Definições).
	$settings_id = (int) get_option( 'lsv_settings_page_id' );
	if ( $settings_id ) {
		add_menu_page(
			'Textos do site',
			'Textos do site',
			'edit_pages',
			'post.php?post=' . $settings_id . '&action=edit',
			'',
			'dashicons-edit',
			58
		);
	}
}, 999 );

/* Limpa o painel inicial dos widgets do WordPress que não interessam à adega. */
add_action( 'wp_dashboard_setup', function () {
	if ( current_user_can( 'manage_options' ) ) {
		return;
	}
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );     // Notícias do WordPress
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );  // Rascunho rápido
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );   // Atividade
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );  // De relance
	remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' ); // Saúde do site
}, 99 );

/* Painel de boas-vindas com atalhos (aparece no topo do painel). */
add_action( 'wp_dashboard_setup', function () {
	wp_add_dashboard_widget( 'lsv_welcome', 'Bem-vindo ao backoffice', 'lsv_welcome_widget' );
	// Empurra o widget para o topo.
	global $wp_meta_boxes;
	$normal = &$wp_meta_boxes['dashboard']['normal']['core'];
	if ( isset( $normal['lsv_welcome'] ) ) {
		$w = array( 'lsv_welcome' => $normal['lsv_welcome'] );
		unset( $normal['lsv_welcome'] );
		$normal = $w + $normal;
	}
}, 1 );

function lsv_welcome_widget() {
	$settings_id = (int) get_option( 'lsv_settings_page_id' );
	$atalhos = array(
		array( 'Adicionar vinho', admin_url( 'post-new.php?post_type=vinho' ) ),
		array( 'Gerir vinhos', admin_url( 'edit.php?post_type=vinho' ) ),
		array( 'Adicionar notícia / prémio', admin_url( 'post-new.php?post_type=imprensa' ) ),
		array( 'Nota de vindima', admin_url( 'post-new.php?post_type=vindima' ) ),
		array( 'Marcações de visita', admin_url( 'edit.php?post_type=reserva' ) ),
		array( 'Subscritores da newsletter', admin_url( 'edit.php?post_type=subscritor' ) ),
	);
	if ( $settings_id ) {
		$atalhos[] = array( 'Textos do site', admin_url( 'post.php?post=' . $settings_id . '&action=edit' ) );
	}
	echo '<p style="margin-top:0">Bem-vindo. Use os atalhos abaixo para gerir o site. Se tiver dúvidas, cada ecrã tem indicações nos campos.</p>';
	echo '<div style="display:flex;flex-wrap:wrap;gap:8px">';
	foreach ( $atalhos as $a ) {
		printf( '<a class="button button-primary" href="%s" style="margin:0">%s</a>', esc_url( $a[1] ), esc_html( $a[0] ) );
	}
	echo '</div>';
}

/* Barra de topo mais limpa para o cliente (tira o logótipo do WordPress e os comentários). */
add_action( 'admin_bar_menu', function ( $bar ) {
	if ( current_user_can( 'manage_options' ) ) {
		return;
	}
	$bar->remove_node( 'wp-logo' );
	$bar->remove_node( 'comments' );
	$bar->remove_node( 'new-post' );
}, 999 );

/* ==========================================================================
   ECRÃ "TEXTOS DO SITE" (página Definições, PT e EN)
   Sem editor de blocos nem corpo de página: só os campos, logo à vista.
   ========================================================================== */

/** A página que está a ser editada é a de Definições (em qualquer língua)? */
function lsv_is_settings_page( $post_id ) {
	$main = (int) get_option( 'lsv_settings_page_id' );
	if ( ! $main || ! $post_id ) {
		return false;
	}
	$ids = function_exists( 'pll_get_post_translations' ) ? array_map( 'intval', pll_get_post_translations( $main ) ) : array();
	$ids[] = $main;
	return in_array( (int) $post_id, $ids, true );
}

add_filter( 'use_block_editor_for_post', function ( $use, $post ) {
	return lsv_is_settings_page( $post->ID ) ? false : $use;
}, 10, 2 );

add_action( 'load-post.php', function () {
	$id = isset( $_GET['post'] ) ? (int) $_GET['post'] : 0; // phpcs:ignore WordPress.Security.NonceVerification
	if ( ! lsv_is_settings_page( $id ) ) {
		return;
	}
	foreach ( array( 'editor', 'thumbnail', 'comments', 'author', 'page-attributes', 'revisions' ) as $f ) {
		remove_post_type_support( 'page', $f );
	}
	add_action( 'edit_form_after_title', 'lsv_settings_intro' );
	add_filter( 'get_sample_permalink_html', '__return_empty_string' ); // endereço de página privada: só confunde
	add_action( 'add_meta_boxes', function () {
		remove_meta_box( 'pageparentdiv', 'page', 'side' );
		remove_meta_box( 'rank_math_metabox', 'page', 'normal' );
		remove_meta_box( 'rank_math_metabox', 'page', 'advanced' );
		remove_meta_box( 'rank_math_metabox_link_suggestions', 'page', 'side' );
	}, 999 );
} );

/** Nota no topo do ecrã: o que se edita aqui e atalho para a outra língua. */
function lsv_settings_intro( $post ) {
	$lang  = function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $post->ID ) : 'pt';
	$other = 'en' === $lang ? 'pt' : 'en';
	$tr    = function_exists( 'pll_get_post' ) ? pll_get_post( $post->ID, $other ) : 0;
	echo '<div class="notice notice-info inline" style="margin:16px 0 0"><p>';
	printf(
		'Aqui edita os textos fixos do site em <strong>%s</strong>. Cada campo diz onde aparece. Se deixar um campo vazio, o site mostra o texto original. ',
		'en' === $lang ? 'inglês' : 'português'
	);
	if ( $tr ) {
		printf( '<a href="%s">Editar a versão em %s &rarr;</a>', esc_url( get_edit_post_link( $tr ) ), 'en' === $other ? 'inglês' : 'português' );
	}
	echo '</p></div>';
}

/* --- Vindimas: a ordem na lista segue sempre o ano (uma vindima nova não fica perdida no fim). --- */
add_action( 'acf/save_post', function ( $post_id ) {
	if ( 'vindima' !== get_post_type( $post_id ) ) {
		return;
	}
	$ano = (int) get_field( 'vindima_ano', $post_id );
	if ( $ano ) {
		global $wpdb; // update direto: wp_update_post voltaria a disparar o save
		$wpdb->update( $wpdb->posts, array( 'menu_order' => $ano ), array( 'ID' => (int) $post_id ) );
		clean_post_cache( $post_id );
	}
}, 20 );
