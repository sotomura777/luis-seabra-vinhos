<?php
/**
 * Reservas/visitas + mailing list — handlers admin-post.php.
 * Sem plugin de formulários. wp_mail() para o admin do site.
 *
 * @package luisseabra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Destinatário dos emails (filtrável).
 */
function lsv_form_recipient() {
	return apply_filters( 'lsv_form_recipient', get_option( 'admin_email' ) );
}

/**
 * Redirect PRG de volta à página, com estado, ancorado em #contactos.
 */
function lsv_form_redirect( $redirect, $form, $status ) {
	$base = $redirect ? $redirect : home_url( '/' );
	$url  = add_query_arg(
		array( 'lsv' => $status, 'form' => $form ),
		$base
	) . '#contactos';
	wp_safe_redirect( $url );
	exit;
}

/**
 * Cabeçalhos: From no domínio do site, Reply-To no visitante.
 */
function lsv_mail_headers( $reply_email ) {
	$host = wp_parse_url( home_url(), PHP_URL_HOST );
	$from = 'no-reply@' . preg_replace( '/^www\./', '', (string) $host );
	return array(
		'Content-Type: text/plain; charset=UTF-8',
		'From: Luís Seabra Vinhos <' . $from . '>',
		'Reply-To: ' . $reply_email,
	);
}

/* ===== 1) VISITA / RESERVA ===== */
add_action( 'admin_post_lsv_visita', 'lsv_handle_visita' );
add_action( 'admin_post_nopriv_lsv_visita', 'lsv_handle_visita' );

function lsv_handle_visita() {
	$redirect = isset( $_POST['redirect'] ) ? esc_url_raw( wp_unslash( $_POST['redirect'] ) ) : home_url( '/' );

	check_admin_referer( 'lsv_visita', 'lsv_visita_nonce' );

	// Honeypot: sai a fingir sucesso.
	if ( ! empty( $_POST['lsv_hp'] ) ) {
		lsv_form_redirect( $redirect, 'visita', 'ok' );
	}

	$nome     = isset( $_POST['nome'] ) ? sanitize_text_field( wp_unslash( $_POST['nome'] ) ) : '';
	$email    = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$data     = isset( $_POST['data'] ) ? sanitize_text_field( wp_unslash( $_POST['data'] ) ) : '';
	$pessoas  = isset( $_POST['pessoas'] ) ? absint( $_POST['pessoas'] ) : 0;
	$mensagem = isset( $_POST['mensagem'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mensagem'] ) ) : '';

	if ( '' === $nome || ! is_email( $email ) ) {
		lsv_form_redirect( $redirect, 'visita', 'err' );
	}

	$data_fmt = '';
	if ( $data && preg_match( '/^\d{4}-\d{2}-\d{2}$/', $data ) ) {
		$ts = strtotime( $data );
		if ( $ts ) {
			$data_fmt = date_i18n( 'd/m/Y', $ts );
		}
	}

	$lang  = function_exists( 'pll_current_language' ) ? pll_current_language() : '';
	$body  = "Novo pedido de visita — Luís Seabra Vinhos\n";
	$body .= "-------------------------------------------\n";
	$body .= 'Nome:     ' . $nome . "\n";
	$body .= 'Email:    ' . $email . "\n";
	$body .= 'Data:     ' . ( $data_fmt ? $data_fmt : '(não indicada)' ) . "\n";
	$body .= 'Pessoas:  ' . ( $pessoas ? $pessoas : '(não indicado)' ) . "\n";
	$body .= 'Idioma:   ' . ( $lang ? strtoupper( $lang ) : 'PT' ) . "\n";
	$body .= "-------------------------------------------\n";
	$body .= "Mensagem:\n" . ( $mensagem ? $mensagem : '(sem mensagem)' ) . "\n";

	$sent = wp_mail( lsv_form_recipient(), sprintf( 'Pedido de visita: %s', $nome ), $body, lsv_mail_headers( $email ) );

	lsv_form_redirect( $redirect, 'visita', $sent ? 'ok' : 'err' );
}

/* ===== 2) MAILING LIST ===== */
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

	$lang  = function_exists( 'pll_current_language' ) ? pll_current_language() : '';
	$body  = "Nova subscrição da mailing list\n-------------------------------\n";
	$body .= 'Email:  ' . $email . "\n";
	$body .= 'Idioma: ' . ( $lang ? strtoupper( $lang ) : 'PT' ) . "\n";

	$sent = wp_mail( lsv_form_recipient(), 'Nova subscrição — mailing list', $body, lsv_mail_headers( $email ) );

	lsv_form_redirect( $redirect, 'mailing', $sent ? 'ok' : 'err' );
}

/**
 * Mensagem de estado de um formulário (usada nos template-parts).
 */
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
		'ok_mailing'  => 'Subscrição registada. Obrigado.',
		'err_mailing' => 'Email inválido. Tente de novo.',
	);
	return sprintf(
		'<p class="lsv-form-msg %s" role="status">%s</p>',
		$ok ? 'ok' : 'err',
		esc_html( pll__( $msg[ $key ] ) )
	);
}
