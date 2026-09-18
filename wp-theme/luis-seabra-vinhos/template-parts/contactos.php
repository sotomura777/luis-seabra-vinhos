<?php
/**
 * Contactos — formulário de visita/reserva + mailing list.
 *
 * @package luisseabra
 */

$contacto_body = lsv_setting( 'contacto_body', 'Recebemos na adega para provar a gama completa, incluindo garrafas que não saem daqui. Escreva-nos com a data e o número de pessoas.' );
$input_css     = 'background:none; border:none; border-bottom:1px solid rgba(255,255,255,.4); color:#FFFFFF; font-size:15px; padding:12px 2px; outline:none';
$label_css     = 'display:flex; flex-direction:column; gap:6px; font-size:10px; letter-spacing:.24em; text-transform:uppercase; color:#C9C6C1';
?>
<section id="contactos" data-screen-label="Contactos" style="background:#000000; color:#FFFFFF; padding:clamp(74px,13vh,160px) clamp(22px,6vw,96px)">
	<div style="max-width:1400px; margin:0 auto; display:grid; grid-template-columns:repeat(auto-fit,minmax(290px,1fr)); gap:clamp(36px,5vw,90px)">

		<!-- Visita / reserva -->
		<div style="display:flex; flex-direction:column; gap:20px">
			<span style="font-size:10px; letter-spacing:.3em; text-transform:uppercase; opacity:.55"><?php pll_e( 'Contactos' ); ?></span>
			<h2 style="margin:0; font-size:clamp(28px,3.8vw,46px); line-height:1.14; font-weight:200; letter-spacing:.005em"><?php pll_e( 'Provas e visitas, por marcação' ); ?></h2>
			<p style="margin:0; max-width:46ch; font-size:16px; line-height:1.8; color:#C9C6C1"><?php echo esc_html( $contacto_body ); ?></p>

			<?php echo wp_kses_post( lsv_form_status_message( 'visita' ) ); ?>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:flex; flex-direction:column; gap:16px; margin-top:6px; max-width:440px">
				<?php wp_nonce_field( 'lsv_visita', 'lsv_visita_nonce' ); ?>
				<input type="hidden" name="action" value="lsv_visita">
				<input type="hidden" name="redirect" value="<?php echo esc_url( get_permalink() ); ?>">

				<label style="<?php echo esc_attr( $label_css ); ?>"><?php pll_e( 'Nome' ); ?>
					<input type="text" name="nome" required style="<?php echo esc_attr( $input_css ); ?>"></label>

				<label style="<?php echo esc_attr( $label_css ); ?>"><?php pll_e( 'Email' ); ?>
					<input type="email" name="email" required placeholder="o.seu@email.pt" style="<?php echo esc_attr( $input_css ); ?>"></label>

				<div style="display:flex; flex-wrap:wrap; gap:16px">
					<label style="flex:1 1 160px; <?php echo esc_attr( $label_css ); ?>"><?php pll_e( 'Data pretendida' ); ?>
						<input type="date" name="data" min="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>" style="<?php echo esc_attr( $input_css ); ?>; color-scheme:dark"></label>
					<label style="flex:1 1 120px; <?php echo esc_attr( $label_css ); ?>"><?php pll_e( 'Nº de pessoas' ); ?>
						<input type="number" name="pessoas" min="1" max="40" step="1" style="<?php echo esc_attr( $input_css ); ?>"></label>
				</div>

				<label style="<?php echo esc_attr( $label_css ); ?>"><?php pll_e( 'Mensagem' ); ?>
					<textarea name="mensagem" rows="3" style="<?php echo esc_attr( $input_css ); ?>; resize:vertical"></textarea></label>

				<div aria-hidden="true" style="position:absolute; left:-9999px; width:1px; height:1px; overflow:hidden">
					<label><?php pll_e( 'Não preencher' ); ?><input type="text" name="lsv_hp" tabindex="-1" autocomplete="off"></label>
				</div>

				<button type="submit" class="lsv-cta lsv-cta-invert" style="align-self:flex-start; margin-top:4px; border:1px solid #FFFFFF; background:#FFFFFF; color:#000000; padding:14px 22px; font:inherit; font-size:11px; letter-spacing:.24em; text-transform:uppercase; cursor:pointer"><?php pll_e( 'Marcar visita' ); ?></button>
			</form>
		</div>

		<!-- Mailing list -->
		<div style="display:flex; flex-direction:column; gap:16px">
			<span style="font-size:10px; letter-spacing:.3em; text-transform:uppercase; opacity:.55"><?php pll_e( 'Mailing list' ); ?></span>
			<p style="margin:0; font-size:clamp(19px,2.2vw,26px); line-height:1.35; font-weight:200"><?php pll_e( 'Avisamos quando abre cada colheita. Duas ou três vezes por ano, nada mais.' ); ?></p>

			<?php echo wp_kses_post( lsv_form_status_message( 'mailing' ) ); ?>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:flex; flex-wrap:wrap; gap:10px; margin-top:6px">
				<?php wp_nonce_field( 'lsv_mailing', 'lsv_mailing_nonce' ); ?>
				<input type="hidden" name="action" value="lsv_mailing">
				<input type="hidden" name="redirect" value="<?php echo esc_url( get_permalink() ); ?>">

				<label for="lsv-mail" style="position:absolute; left:-9999px"><?php pll_e( 'O seu email' ); ?></label>
				<input id="lsv-mail" type="email" name="email" required placeholder="o.seu@email.pt" style="flex:1 1 200px; min-width:0; <?php echo esc_attr( $input_css ); ?>">

				<div aria-hidden="true" style="position:absolute; left:-9999px; width:1px; height:1px; overflow:hidden">
					<label><?php pll_e( 'Não preencher' ); ?><input type="text" name="lsv_hp" tabindex="-1" autocomplete="off"></label>
				</div>

				<button type="submit" class="lsv-sub-btn" style="background:none; border:1px solid rgba(255,255,255,.4); color:#FFFFFF; font:inherit; font-size:11px; letter-spacing:.24em; text-transform:uppercase; padding:13px 20px; cursor:pointer"><?php pll_e( 'Subscrever' ); ?></button>
			</form>

			<a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>?subject=Importadores" style="margin-top:10px; font-size:11px; letter-spacing:.24em; text-transform:uppercase; color:#C9C6C1; border-bottom:1px solid rgba(255,255,255,.3); align-self:flex-start; padding-bottom:2px"><?php pll_e( 'Importadores' ); ?></a>
		</div>

	</div>
</section>
