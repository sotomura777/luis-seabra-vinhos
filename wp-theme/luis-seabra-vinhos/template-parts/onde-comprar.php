<?php
/**
 * "Onde comprar" — pedido de particulares / restaurantes / importadores.
 *
 * @package luisseabra
 */

$in  = 'background:none; border:none; border-bottom:1px solid #D9D6D2; color:#000; font-size:15px; padding:12px 2px; outline:none';
$lab = 'display:flex; flex-direction:column; gap:6px; font-size:10px; letter-spacing:.24em; text-transform:uppercase; color:#6E6E6E';
?>
<section id="onde-comprar" data-screen-label="Onde comprar" style="background:#F5F4F2; padding:clamp(74px,13vh,160px) clamp(22px,6vw,96px); border-top:1px solid #E4E2DF">
	<div style="max-width:760px; margin:0 auto">
		<span style="font-size:10px; letter-spacing:.3em; text-transform:uppercase; color:#6E6E6E"><?php pll_e( 'Onde comprar' ); ?></span>
		<h2 style="margin:14px 0 8px; font-size:clamp(28px,3.8vw,46px); line-height:1.1; font-weight:200; letter-spacing:.01em"><?php pll_e( 'Procura os nossos vinhos?' ); ?></h2>
		<p style="margin:0 0 28px; font-size:16px; line-height:1.8; color:#4A4A4A; max-width:52ch"><?php pll_e( 'Diga-nos quem é e onde está. Encaminhamos para o ponto de venda ou distribuidor mais próximo.' ); ?></p>

		<?php echo wp_kses_post( lsv_form_status_message( 'compra' ) ); ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:flex; flex-direction:column; gap:18px">
			<?php wp_nonce_field( 'lsv_ondecomprar', 'lsv_oc_nonce' ); ?>
			<input type="hidden" name="action" value="lsv_ondecomprar">
			<input type="hidden" name="redirect" value="<?php echo esc_url( get_permalink() ); ?>">

			<div style="display:flex; flex-wrap:wrap; gap:18px">
				<label style="flex:1 1 220px; <?php echo esc_attr( $lab ); ?>"><?php pll_e( 'Nome' ); ?>
					<input type="text" name="nome" required style="<?php echo esc_attr( $in ); ?>"></label>
				<label style="flex:1 1 220px; <?php echo esc_attr( $lab ); ?>"><?php pll_e( 'Email' ); ?>
					<input type="email" name="email" required placeholder="o.seu@email.pt" style="<?php echo esc_attr( $in ); ?>"></label>
			</div>

			<div style="display:flex; flex-wrap:wrap; gap:18px">
				<label style="flex:1 1 160px; <?php echo esc_attr( $lab ); ?>"><?php pll_e( 'Telefone' ); ?>
					<input type="tel" name="tel" style="<?php echo esc_attr( $in ); ?>"></label>
				<label style="flex:1 1 160px; <?php echo esc_attr( $lab ); ?>"><?php pll_e( 'Cidade / País' ); ?>
					<input type="text" name="local" style="<?php echo esc_attr( $in ); ?>"></label>
				<label style="flex:1 1 160px; <?php echo esc_attr( $lab ); ?>"><?php pll_e( 'Perfil' ); ?>
					<select name="tipo" style="<?php echo esc_attr( $in ); ?>; color-scheme:light">
						<option value="particular"><?php pll_e( 'Particular' ); ?></option>
						<option value="restaurante"><?php pll_e( 'Restaurante' ); ?></option>
						<option value="importador"><?php pll_e( 'Importador' ); ?></option>
					</select></label>
			</div>

			<label style="<?php echo esc_attr( $lab ); ?>"><?php pll_e( 'Mensagem' ); ?>
				<textarea name="mensagem" rows="3" style="<?php echo esc_attr( $in ); ?>; resize:vertical"></textarea></label>

			<div aria-hidden="true" style="position:absolute; left:-9999px; width:1px; height:1px; overflow:hidden">
				<label><?php pll_e( 'Não preencher' ); ?><input type="text" name="lsv_hp" tabindex="-1" autocomplete="off"></label>
			</div>

			<button type="submit" class="lsv-btn-ghost" style="align-self:flex-start; margin-top:4px; background:none; border:1px solid #000; color:#000; font:inherit; font-size:11px; letter-spacing:.24em; text-transform:uppercase; padding:14px 22px; cursor:pointer"><?php pll_e( 'Enviar pedido' ); ?></button>
			<?php lsv_form_privacy_note(); ?>
		</form>
	</div>
</section>
