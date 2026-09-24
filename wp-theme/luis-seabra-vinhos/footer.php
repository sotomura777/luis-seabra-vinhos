<?php
/**
 * Footer (novo design).
 *
 * @package luisseabra
 */
?>
<footer>
	<span>Luís Seabra Vinhos, Lda · PT510774156</span>
	<span>Dão · Douro · Vinho Verde</span>
	<span><?php pll_e( 'Beba com moderação' ); ?> · &copy; <?php echo esc_html( date( 'Y' ) ); ?></span>
	<span class="legal">
		<?php if ( lsv_privacy_url() ) : ?><a href="<?php echo esc_url( lsv_privacy_url() ); ?>"><?php pll_e( 'Política de Privacidade' ); ?></a><?php endif; ?>
		<?php if ( lsv_ga_id() ) : ?><button type="button" data-lsv-cookies><?php pll_e( 'Preferências de cookies' ); ?></button><?php endif; ?>
	</span>
</footer>

<?php wp_footer(); ?>
</body>
</html>
