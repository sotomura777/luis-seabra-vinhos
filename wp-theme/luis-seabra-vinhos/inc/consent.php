<?php
/**
 * Consentimento de cookies + Google Analytics 4.
 *
 * O GA só é descarregado depois de o visitante carregar em "Aceitar" (RGPD / CNPD).
 * O código de medição vive no wp-config.php (fora do Git). Para ativar, adicionar lá:
 *   define( 'LSV_GA_ID', 'G-XXXXXXXXXX' );
 * Sem ele não há cookies opcionais no site, por isso o aviso nem aparece.
 *
 * @package luisseabra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** ID do GA4 configurado (ou ''). */
function lsv_ga_id() {
	$id = defined( 'LSV_GA_ID' ) ? (string) LSV_GA_ID : '';
	return preg_match( '/^G-[A-Z0-9]{4,20}$/', $id ) ? $id : '';
}

/** URL da Política de Privacidade na língua atual (ou ''). */
function lsv_privacy_url() {
	$id = (int) get_option( 'wp_page_for_privacy_policy' );
	if ( $id && function_exists( 'pll_get_post' ) ) {
		$id = pll_get_post( $id ) ?: $id;
	}
	return ( $id && 'publish' === get_post_status( $id ) ) ? get_permalink( $id ) : '';
}

add_action( 'wp_enqueue_scripts', function () {
	if ( ! lsv_ga_id() ) {
		return;
	}
	wp_enqueue_script( 'lsv-consent', LSV_URI . '/assets/js/consent.js', array(), LSV_VERSION, true );
	wp_localize_script( 'lsv-consent', 'LSV_CONSENT', array( 'ga' => lsv_ga_id() ) );
} );

add_action( 'wp_footer', function () {
	if ( ! lsv_ga_id() ) {
		return;
	}
	$pp = lsv_privacy_url();
	?>
	<div id="lsv-consent" role="dialog" aria-modal="false" aria-labelledby="lsv-consent-t" hidden>
		<p id="lsv-consent-t"><?php pll_e( 'Usamos cookies de estatística (Google Analytics) para perceber como o site é visitado. Só são ativados se aceitar.' ); ?>
			<?php if ( $pp ) : ?><a href="<?php echo esc_url( $pp ); ?>"><?php pll_e( 'Política de Privacidade' ); ?></a><?php endif; ?></p>
		<div class="btns">
			<button type="button" data-consent="denied"><?php pll_e( 'Recusar' ); ?></button>
			<button type="button" data-consent="granted"><?php pll_e( 'Aceitar' ); ?></button>
		</div>
	</div>
	<?php
}, 5 );

/** Nota curta com link para a política, para pôr no fim dos formulários. */
function lsv_form_privacy_note() {
	$pp = lsv_privacy_url();
	if ( ! $pp ) {
		return;
	}
	printf(
		'<p class="form-note">%s <a href="%s">%s</a>.</p>',
		esc_html( pll__( 'Ao enviar, os seus dados são tratados de acordo com a nossa' ) ),
		esc_url( $pp ),
		esc_html( pll__( 'Política de Privacidade' ) )
	);
}
