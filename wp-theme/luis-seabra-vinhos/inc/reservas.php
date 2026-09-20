<?php
/**
 * Formulários: marcações de visita, "Onde comprar" e newsletter.
 * Guardam os dados em CPTs privados e enviam email (admin-post.php + wp_mail).
 * Sem plugin de formulários.
 *
 * @package luisseabra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ==========================================================================
   Helpers partilhados
   ========================================================================== */

/** Destinatário dos emails da empresa (filtrável). */
function lsv_form_recipient() {
	return apply_filters( 'lsv_form_recipient', get_option( 'admin_email' ) );
}

/** Redirect PRG de volta à página, com estado, ancorado em #contactos. */
function lsv_form_redirect( $redirect, $form, $status ) {
	$base = $redirect ? $redirect : home_url( '/' );
	$url  = add_query_arg( array( 'lsv' => $status, 'form' => $form ), $base ) . '#contactos';
	wp_safe_redirect( $url );
	exit;
}

/** Cabeçalhos: From no domínio do site, Reply-To configurável. */
function lsv_mail_headers( $reply_email ) {
	// Defesa extra contra header injection (o is_email já valida, mas garantimos).
	$reply_email = str_replace( array( "\r", "\n", "\0" ), '', (string) $reply_email );
	$host = wp_parse_url( home_url(), PHP_URL_HOST );
	$from = 'no-reply@' . preg_replace( '/^www\./', '', (string) $host );
	return array(
		'Content-Type: text/plain; charset=UTF-8',
		'From: Luís Seabra Vinhos <' . $from . '>',
		'Reply-To: ' . $reply_email,
	);
}

/* ==========================================================================
   1) VISITA / RESERVA — guarda + email à empresa + email ao visitante
   ========================================================================== */
add_action( 'admin_post_lsv_visita', 'lsv_handle_visita' );
add_action( 'admin_post_nopriv_lsv_visita', 'lsv_handle_visita' );

function lsv_handle_visita() {
	$redirect = isset( $_POST['redirect'] ) ? esc_url_raw( wp_unslash( $_POST['redirect'] ) ) : home_url( '/' );
	check_admin_referer( 'lsv_visita', 'lsv_visita_nonce' );

	if ( ! empty( $_POST['lsv_hp'] ) ) {
		lsv_form_redirect( $redirect, 'visita', 'ok' );
	}

	$nome    = isset( $_POST['nome'] ) ? sanitize_text_field( wp_unslash( $_POST['nome'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$tel     = isset( $_POST['tel'] ) ? sanitize_text_field( wp_unslash( $_POST['tel'] ) ) : '';
	$data    = isset( $_POST['data'] ) ? sanitize_text_field( wp_unslash( $_POST['data'] ) ) : '';
	$hora    = isset( $_POST['hora'] ) ? sanitize_text_field( wp_unslash( $_POST['hora'] ) ) : '';
	$pessoas = isset( $_POST['pessoas'] ) ? absint( $_POST['pessoas'] ) : 0;
	$obs     = isset( $_POST['mensagem'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mensagem'] ) ) : '';
	$lang    = function_exists( 'pll_current_language' ) ? pll_current_language() : 'pt';
	$lang    = in_array( $lang, array( 'pt', 'en' ), true ) ? $lang : 'pt';

	// Data válida E não no passado (a validação do browser é contornável).
	$data_ok = $data && preg_match( '/^\d{4}-\d{2}-\d{2}$/', $data ) && strtotime( $data ) >= strtotime( date( 'Y-m-d' ) );
	if ( '' === $nome || ! is_email( $email ) || ! $data_ok ) {
		lsv_form_redirect( $redirect, 'visita', 'err' );
	}
	if ( $hora && ! preg_match( '/^\d{2}:\d{2}$/', $hora ) ) {
		$hora = '';
	}

	$ref     = sprintf( '%s — %s', $nome, date_i18n( 'd/m/Y', strtotime( $data ) ) );
	$post_id = wp_insert_post( array(
		'post_type'   => 'reserva',
		'post_status' => 'publish',
		'post_title'  => wp_strip_all_tags( $ref ),
	), true );

	if ( is_wp_error( $post_id ) ) {
		lsv_form_redirect( $redirect, 'visita', 'err' );
	}

	update_field( 'reserva_estado', 'pendente', $post_id );
	update_field( 'reserva_nome', $nome, $post_id );
	update_field( 'reserva_email', $email, $post_id );
	update_field( 'reserva_tel', $tel, $post_id );
	update_field( 'reserva_data', $data, $post_id );
	update_field( 'reserva_hora', $hora, $post_id );
	update_field( 'reserva_pessoas', $pessoas, $post_id );
	update_field( 'reserva_idioma', $lang, $post_id );
	update_field( 'reserva_obs', $obs, $post_id );
	// "Sombra" para o hook de transição não disparar no primeiro save.
	update_post_meta( $post_id, '_lsv_estado_anterior', 'pendente' );

	$data_fmt    = date_i18n( 'd/m/Y', strtotime( $data ) );
	$admin_body  = "Novo pedido de visita — Luís Seabra Vinhos\n";
	$admin_body .= "-------------------------------------------\n";
	$admin_body .= 'Nome:     ' . $nome . "\n";
	$admin_body .= 'Email:    ' . $email . "\n";
	$admin_body .= 'Telefone: ' . ( $tel ? $tel : '—' ) . "\n";
	$admin_body .= 'Data:     ' . $data_fmt . ( $hora ? '  às ' . $hora : '' ) . "\n";
	$admin_body .= 'Pessoas:  ' . ( $pessoas ? $pessoas : '—' ) . "\n";
	$admin_body .= 'Idioma:   ' . strtoupper( $lang ) . "\n";
	$admin_body .= "-------------------------------------------\n";
	$admin_body .= "Observações:\n" . ( $obs ? $obs : '(sem observações)' ) . "\n\n";
	$admin_body .= 'Gerir: ' . admin_url( 'post.php?post=' . $post_id . '&action=edit' ) . "\n";
	wp_mail( lsv_form_recipient(), 'Pedido de visita: ' . $nome, $admin_body, lsv_mail_headers( $email ) );

	lsv_send_visitor_email( 'recebido', $post_id );

	lsv_form_redirect( $redirect, 'visita', 'ok' );
}

/** Emails ao visitante (receção / confirmação / recusa), na língua da reserva. */
function lsv_send_visitor_email( $tipo, $post_id ) {
	$email = get_field( 'reserva_email', $post_id );
	if ( ! is_email( $email ) ) {
		return false;
	}
	$nome     = get_field( 'reserva_nome', $post_id );
	$data     = get_field( 'reserva_data', $post_id );
	$hora     = get_field( 'reserva_hora', $post_id );
	$lang     = ( 'en' === get_field( 'reserva_idioma', $post_id ) ) ? 'en' : 'pt';
	$data_fmt = $data ? date_i18n( 'd/m/Y', strtotime( $data ) ) : '';
	$quando   = $data_fmt . ( $hora ? ( 'en' === $lang ? ' at ' . $hora : ' às ' . $hora ) : '' );

	$t = array(
		'recebido'   => array(
			'pt' => array( 'Recebemos o seu pedido de visita', "Olá $nome,\n\nRecebemos o seu pedido de visita para $quando. Vamos confirmar a disponibilidade e respondemos dentro de um dia útil.\n\nObrigado,\nLuís Seabra Vinhos" ),
			'en' => array( 'We received your visit request', "Hello $nome,\n\nWe received your visit request for $quando. We'll check availability and reply within one business day.\n\nThank you,\nLuís Seabra Vinhos" ),
		),
		'confirmada' => array(
			'pt' => array( 'Visita confirmada — Luís Seabra Vinhos', "Olá $nome,\n\nA sua visita está CONFIRMADA para $quando. Ficamos à sua espera.\n\nAté breve,\nLuís Seabra Vinhos" ),
			'en' => array( 'Visit confirmed — Luís Seabra Vinhos', "Hello $nome,\n\nYour visit is CONFIRMED for $quando. We look forward to welcoming you.\n\nSee you soon,\nLuís Seabra Vinhos" ),
		),
		'recusada'   => array(
			'pt' => array( 'Sobre o seu pedido de visita', "Olá $nome,\n\nInfelizmente não temos disponibilidade para $quando. Responda a este email para procurarmos uma data alternativa.\n\nCom os melhores cumprimentos,\nLuís Seabra Vinhos" ),
			'en' => array( 'About your visit request', "Hello $nome,\n\nUnfortunately we have no availability for $quando. Reply to this email and we'll find an alternative date.\n\nBest regards,\nLuís Seabra Vinhos" ),
		),
	);
	if ( empty( $t[ $tipo ][ $lang ] ) ) {
		return false;
	}
	list( $assunto, $corpo ) = $t[ $tipo ][ $lang ];
	return wp_mail( $email, $assunto, $corpo, lsv_mail_headers( lsv_form_recipient() ) );
}

/** Ao mudar o estado no admin, envia email ao visitante (só na transição real). */
add_action( 'acf/save_post', 'lsv_reserva_estado_change', 20 );

function lsv_reserva_estado_change( $post_id ) {
	if ( 'reserva' !== get_post_type( $post_id ) ) {
		return;
	}
	// Ler o valor JÁ GRAVADO (o hook corre com prioridade 20, depois do ACF gravar)
	// — mais robusto que confiar na forma do $_POST.
	$novo     = get_field( 'reserva_estado', $post_id );
	$novo     = in_array( $novo, array( 'pendente', 'confirmada', 'recusada' ), true ) ? $novo : '';
	$anterior = get_post_meta( $post_id, '_lsv_estado_anterior', true );

	if ( $novo && $novo !== $anterior && in_array( $novo, array( 'confirmada', 'recusada' ), true ) ) {
		lsv_send_visitor_email( $novo, $post_id );
	}
	if ( $novo ) {
		update_post_meta( $post_id, '_lsv_estado_anterior', $novo );
	}
}

/* ==========================================================================
   2) "ONDE COMPRAR" — guarda + email à empresa
   ========================================================================== */
add_action( 'admin_post_lsv_ondecomprar', 'lsv_handle_ondecomprar' );
add_action( 'admin_post_nopriv_lsv_ondecomprar', 'lsv_handle_ondecomprar' );

function lsv_handle_ondecomprar() {
	$redirect = isset( $_POST['redirect'] ) ? esc_url_raw( wp_unslash( $_POST['redirect'] ) ) : home_url( '/' );
	check_admin_referer( 'lsv_ondecomprar', 'lsv_oc_nonce' );

	if ( ! empty( $_POST['lsv_hp'] ) ) {
		lsv_form_redirect( $redirect, 'compra', 'ok' );
	}

	$nome  = isset( $_POST['nome'] ) ? sanitize_text_field( wp_unslash( $_POST['nome'] ) ) : '';
	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$tel   = isset( $_POST['tel'] ) ? sanitize_text_field( wp_unslash( $_POST['tel'] ) ) : '';
	$local = isset( $_POST['local'] ) ? sanitize_text_field( wp_unslash( $_POST['local'] ) ) : '';
	$tipo  = isset( $_POST['tipo'] ) ? sanitize_key( wp_unslash( $_POST['tipo'] ) ) : '';
	$msg   = isset( $_POST['mensagem'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mensagem'] ) ) : '';
	$lang  = function_exists( 'pll_current_language' ) ? pll_current_language() : 'pt';
	$lang  = in_array( $lang, array( 'pt', 'en' ), true ) ? $lang : 'pt';
	$tipo  = in_array( $tipo, array( 'particular', 'restaurante', 'importador' ), true ) ? $tipo : 'particular';

	if ( '' === $nome || ! is_email( $email ) ) {
		lsv_form_redirect( $redirect, 'compra', 'err' );
	}

	$post_id = wp_insert_post( array(
		'post_type'   => 'pedido_compra',
		'post_status' => 'publish',
		'post_title'  => wp_strip_all_tags( $nome . ' — ' . ucfirst( $tipo ) ),
	), true );
	if ( is_wp_error( $post_id ) ) {
		lsv_form_redirect( $redirect, 'compra', 'err' );
	}

	update_field( 'pc_estado', 'novo', $post_id );
	update_field( 'pc_tipo', $tipo, $post_id );
	update_field( 'pc_nome', $nome, $post_id );
	update_field( 'pc_email', $email, $post_id );
	update_field( 'pc_tel', $tel, $post_id );
	update_field( 'pc_local', $local, $post_id );
	update_field( 'pc_idioma', $lang, $post_id );
	update_field( 'pc_mensagem', $msg, $post_id );

	$body  = "Novo pedido \"Onde comprar\" — Luís Seabra Vinhos\n";
	$body .= "-------------------------------------------\n";
	$body .= 'Nome:     ' . $nome . "\n";
	$body .= 'Email:    ' . $email . "\n";
	$body .= 'Telefone: ' . ( $tel ? $tel : '—' ) . "\n";
	$body .= 'Local:    ' . ( $local ? $local : '—' ) . "\n";
	$body .= 'Tipo:     ' . ucfirst( $tipo ) . "\n";
	$body .= 'Idioma:   ' . strtoupper( $lang ) . "\n";
	$body .= "-------------------------------------------\n";
	$body .= "Mensagem:\n" . ( $msg ? $msg : '(sem mensagem)' ) . "\n\n";
	$body .= 'Gerir: ' . admin_url( 'post.php?post=' . $post_id . '&action=edit' ) . "\n";
	wp_mail( lsv_form_recipient(), 'Onde comprar: ' . $nome, $body, lsv_mail_headers( $email ) );

	lsv_form_redirect( $redirect, 'compra', 'ok' );
}

/* ==========================================================================
   2b) CONTACTO — formulário geral (guarda em pedido_compra + email à empresa)
   ========================================================================== */
add_action( 'admin_post_lsv_contacto', 'lsv_handle_contacto' );
add_action( 'admin_post_nopriv_lsv_contacto', 'lsv_handle_contacto' );

function lsv_handle_contacto() {
	$redirect = isset( $_POST['redirect'] ) ? esc_url_raw( wp_unslash( $_POST['redirect'] ) ) : home_url( '/' );
	check_admin_referer( 'lsv_contacto', 'lsv_contacto_nonce' );

	if ( ! empty( $_POST['lsv_hp'] ) ) {
		lsv_form_redirect( $redirect, 'contacto', 'ok' );
	}

	$nome    = isset( $_POST['nome'] ) ? sanitize_text_field( wp_unslash( $_POST['nome'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$tel     = isset( $_POST['tel'] ) ? sanitize_text_field( wp_unslash( $_POST['tel'] ) ) : '';
	$assunto = isset( $_POST['assunto'] ) ? sanitize_text_field( wp_unslash( $_POST['assunto'] ) ) : '';
	$msg     = isset( $_POST['mensagem'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mensagem'] ) ) : '';
	$lang    = function_exists( 'pll_current_language' ) ? pll_current_language() : 'pt';
	$lang    = in_array( $lang, array( 'pt', 'en' ), true ) ? $lang : 'pt';

	if ( '' === $nome || ! is_email( $email ) ) {
		lsv_form_redirect( $redirect, 'contacto', 'err' );
	}

	$post_id = wp_insert_post( array(
		'post_type'   => 'pedido_compra',
		'post_status' => 'publish',
		'post_title'  => wp_strip_all_tags( $nome . ( $assunto ? ' — ' . $assunto : '' ) ),
	), true );
	if ( is_wp_error( $post_id ) ) {
		lsv_form_redirect( $redirect, 'contacto', 'err' );
	}

	update_field( 'pc_estado', 'novo', $post_id );
	update_field( 'pc_tipo', 'contacto', $post_id );
	update_field( 'pc_nome', $nome, $post_id );
	update_field( 'pc_email', $email, $post_id );
	update_field( 'pc_tel', $tel, $post_id );
	update_field( 'pc_idioma', $lang, $post_id );
	update_field( 'pc_mensagem', ( $assunto ? '[' . $assunto . '] ' : '' ) . $msg, $post_id );

	$body  = "Novo contacto — Luís Seabra Vinhos\n";
	$body .= "-------------------------------------------\n";
	$body .= 'Nome:     ' . $nome . "\n";
	$body .= 'Email:    ' . $email . "\n";
	$body .= 'Telefone: ' . ( $tel ? $tel : '—' ) . "\n";
	$body .= 'Assunto:  ' . ( $assunto ? $assunto : '—' ) . "\n";
	$body .= 'Idioma:   ' . strtoupper( $lang ) . "\n";
	$body .= "-------------------------------------------\n";
	$body .= "Mensagem:\n" . ( $msg ? $msg : '(sem mensagem)' ) . "\n\n";
	$body .= 'Gerir: ' . admin_url( 'post.php?post=' . $post_id . '&action=edit' ) . "\n";
	wp_mail( lsv_form_recipient(), 'Contacto: ' . $nome, $body, lsv_mail_headers( $email ) );

	lsv_form_redirect( $redirect, 'contacto', 'ok' );
}

/* ==========================================================================
   3) NEWSLETTER — guarda subscritor (anti-duplicado) + email de aviso
   ========================================================================== */
add_action( 'admin_post_lsv_mailing', 'lsv_handle_mailing' );
add_action( 'admin_post_nopriv_lsv_mailing', 'lsv_handle_mailing' );

function lsv_handle_mailing() {
	$redirect = isset( $_POST['redirect'] ) ? esc_url_raw( wp_unslash( $_POST['redirect'] ) ) : home_url( '/' );
	check_admin_referer( 'lsv_mailing', 'lsv_mailing_nonce' );

	if ( ! empty( $_POST['lsv_hp'] ) ) {
		lsv_form_redirect( $redirect, 'mailing', 'ok' );
	}

	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	if ( ! is_email( $email ) ) {
		lsv_form_redirect( $redirect, 'mailing', 'err' );
	}
	$lang = function_exists( 'pll_current_language' ) ? pll_current_language() : 'pt';
	$lang = in_array( $lang, array( 'pt', 'en' ), true ) ? $lang : 'pt';

	// Anti-duplicado: já existe um subscritor com este email?
	$dup = get_posts( array(
		'post_type'   => 'subscritor',
		'title'       => $email,
		'post_status' => 'any',
		'numberposts' => 1,
		'fields'      => 'ids',
	) );
	if ( empty( $dup ) ) {
		$post_id = wp_insert_post( array(
			'post_type'   => 'subscritor',
			'post_status' => 'publish',
			'post_title'  => wp_strip_all_tags( $email ),
		), true );
		if ( ! is_wp_error( $post_id ) ) {
			update_field( 'sub_idioma', $lang, $post_id );
			$body = "Nova subscrição da mailing list\n-------------------------------\n";
			$body .= 'Email:  ' . $email . "\nIdioma: " . strtoupper( $lang ) . "\n";
			wp_mail( lsv_form_recipient(), 'Nova subscrição — mailing list', $body, lsv_mail_headers( $email ) );
		}
	}

	lsv_form_redirect( $redirect, 'mailing', 'ok' );
}

/* ==========================================================================
   Estado dos formulários (mensagens sob cada formulário)
   ========================================================================== */
function lsv_form_status_message( $form ) {
	$lsv  = isset( $_GET['lsv'] ) ? sanitize_key( wp_unslash( $_GET['lsv'] ) ) : '';
	$fkey = isset( $_GET['form'] ) ? sanitize_key( wp_unslash( $_GET['form'] ) ) : '';
	if ( '' === $lsv || $fkey !== $form ) {
		return '';
	}
	$ok  = ( 'ok' === $lsv );
	$key = ( $ok ? 'ok_' : 'err_' ) . $form;
	$msg = array(
		'ok_visita'   => 'Pedido enviado. Respondemos dentro de um dia útil.',
		'err_visita'  => 'Não foi possível enviar. Verifique os campos e tente de novo.',
		'ok_compra'   => 'Pedido enviado. Entramos em contacto em breve.',
		'err_compra'  => 'Não foi possível enviar. Verifique os campos e tente de novo.',
		'ok_contacto'  => 'Mensagem enviada. Respondemos dentro de dois dias úteis.',
		'err_contacto' => 'Não foi possível enviar. Verifique os campos e tente de novo.',
		'ok_mailing'  => 'Subscrição registada. Obrigado.',
		'err_mailing' => 'Email inválido. Tente de novo.',
	);
	if ( ! isset( $msg[ $key ] ) ) {
		return '';
	}
	return sprintf(
		'<p class="lsv-form-msg %s" role="status">%s</p>',
		$ok ? 'ok' : 'err',
		esc_html( pll__( $msg[ $key ] ) )
	);
}

/* ==========================================================================
   Backoffice — colunas e filtros
   ========================================================================== */

// Reservas: colunas.
add_filter( 'manage_reserva_posts_columns', function ( $cols ) {
	return array(
		'cb'        => $cols['cb'],
		'title'     => 'Referência',
		'r_estado'  => 'Estado',
		'r_data'    => 'Data',
		'r_hora'    => 'Hora',
		'r_pessoas' => 'Pessoas',
		'date'      => 'Recebido',
	);
} );
add_action( 'manage_reserva_posts_custom_column', function ( $col, $post_id ) {
	$labels = array( 'pendente' => 'Pendente', 'confirmada' => 'Confirmada', 'recusada' => 'Recusada' );
	switch ( $col ) {
		case 'r_estado':
			$e = get_field( 'reserva_estado', $post_id ) ?: 'pendente';
			printf( '<span class="lsv-badge lsv-%s">%s</span>', esc_attr( $e ), esc_html( $labels[ $e ] ?? $e ) );
			break;
		case 'r_data':
			$d = get_field( 'reserva_data', $post_id );
			echo esc_html( $d ? date_i18n( 'd/m/Y', strtotime( $d ) ) : '' );
			break;
		case 'r_hora':
			echo esc_html( get_field( 'reserva_hora', $post_id ) );
			break;
		case 'r_pessoas':
			echo esc_html( get_field( 'reserva_pessoas', $post_id ) );
			break;
	}
}, 10, 2 );
add_filter( 'manage_edit-reserva_sortable_columns', function ( $c ) {
	$c['r_data'] = 'r_data';
	return $c;
} );

// Reservas: filtro por estado + ordenação por data.
add_action( 'restrict_manage_posts', function ( $post_type ) {
	if ( 'reserva' !== $post_type ) {
		return;
	}
	$cur = isset( $_GET['r_estado'] ) ? sanitize_key( wp_unslash( $_GET['r_estado'] ) ) : '';
	echo '<select name="r_estado"><option value="">' . esc_html__( 'Todos os estados', 'luisseabra' ) . '</option>';
	foreach ( array( 'pendente' => 'Pendentes', 'confirmada' => 'Confirmadas', 'recusada' => 'Recusadas' ) as $k => $v ) {
		printf( '<option value="%s"%s>%s</option>', esc_attr( $k ), selected( $cur, $k, false ), esc_html( $v ) );
	}
	echo '</select>';
} );
add_action( 'pre_get_posts', function ( $q ) {
	if ( ! is_admin() || ! $q->is_main_query() ) {
		return;
	}
	if ( 'reserva' === $q->get( 'post_type' ) ) {
		if ( 'r_data' === $q->get( 'orderby' ) ) {
			$q->set( 'meta_key', 'reserva_data' );
			$q->set( 'orderby', 'meta_value' );
		}
		if ( ! empty( $_GET['r_estado'] ) ) {
			$q->set( 'meta_query', array( array( 'key' => 'reserva_estado', 'value' => sanitize_key( wp_unslash( $_GET['r_estado'] ) ) ) ) );
		}
	}
} );

// "Onde comprar": colunas.
add_filter( 'manage_pedido_compra_posts_columns', function ( $cols ) {
	return array(
		'cb'       => $cols['cb'],
		'title'    => 'Referência',
		'pc_tipo'  => 'Tipo',
		'pc_estado' => 'Estado',
		'date'     => 'Recebido',
	);
} );
add_action( 'manage_pedido_compra_posts_custom_column', function ( $col, $post_id ) {
	if ( 'pc_tipo' === $col ) {
		echo esc_html( ucfirst( (string) get_field( 'pc_tipo', $post_id ) ) );
	} elseif ( 'pc_estado' === $col ) {
		echo esc_html( ucfirst( (string) get_field( 'pc_estado', $post_id ) ) );
	}
}, 10, 2 );

// Badges de estado (cores) no admin.
add_action( 'admin_head', function () {
	echo '<style>.lsv-badge{display:inline-block;padding:2px 9px;border-radius:10px;font-size:11px;font-weight:600}
	.lsv-pendente{background:#FFF3CD;color:#8A6D00}.lsv-confirmada{background:#D7F0DB;color:#1E6B2A}.lsv-recusada{background:#FBD9D9;color:#9B1C1C}</style>';
} );

/* ==========================================================================
   Exportar subscritores em CSV
   ========================================================================== */
add_action( 'restrict_manage_posts', function ( $post_type ) {
	if ( 'subscritor' !== $post_type || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$url = wp_nonce_url( admin_url( 'admin-post.php?action=lsv_export_subs' ), 'lsv_export_subs' );
	printf( '<a href="%s" class="button">%s</a>', esc_url( $url ), esc_html__( 'Exportar CSV', 'luisseabra' ) );
} );
add_action( 'admin_post_lsv_export_subs', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Sem permissão.' );
	}
	check_admin_referer( 'lsv_export_subs' );

	nocache_headers();
	header( 'Content-Type: text/csv; charset=UTF-8' );
	header( 'X-Content-Type-Options: nosniff' );
	header( 'Content-Disposition: attachment; filename=subscritores.csv' );
	$out = fopen( 'php://output', 'w' );
	fputcsv( $out, array( 'email', 'idioma', 'data' ) );
	$subs = get_posts( array( 'post_type' => 'subscritor', 'numberposts' => -1, 'post_status' => 'any' ) );
	foreach ( $subs as $s ) {
		fputcsv( $out, array(
			$s->post_title,
			get_field( 'sub_idioma', $s->ID ),
			get_the_date( 'Y-m-d', $s ),
		) );
	}
	fclose( $out );
	exit;
} );
