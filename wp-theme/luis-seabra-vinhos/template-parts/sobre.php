<?php
/**
 * Sobre — destaque + corpo + estatísticas.
 *
 * @package luisseabra
 */

$lead = lsv_setting( 'sobre_lead', 'Depois de uma década a fazer vinho na Niepoort, Luís Seabra montou a sua própria adega em 2013 para provar que o Douro também dá vinhos frescos e elegantes.' );
$body = lsv_setting( 'sobre_body', '<p>Trabalha vinhas velhas, pequenas e remotas, escolhidas pelo solo. Intervenção mínima da terra à garrafa: leveduras indígenas, fermentação em madeira, aço inox só quando é indispensável. A série Cru nasceu em 2013 e organiza-se por vinha, não por casta.</p>' );
?>
<section id="sobre" data-screen-label="Sobre" style="background:#FFFFFF; padding:clamp(74px,13vh,160px) clamp(22px,6vw,96px)">
	<div style="max-width:1180px; margin:0 auto; display:grid; grid-template-columns:repeat(auto-fit,minmax(290px,1fr)); gap:clamp(30px,5vw,80px); align-items:start">
		<p style="margin:0; font-size:clamp(23px,2.9vw,38px); line-height:1.32; font-weight:200; letter-spacing:.005em"><?php echo esc_html( $lead ); ?></p>
		<div style="display:flex; flex-direction:column; gap:clamp(22px,3vh,34px)">
			<div style="font-size:16px; line-height:1.8; color:#4A4A4A"><?php echo wp_kses_post( $body ); ?></div>
			<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; border-top:1px solid #E4E2DF; padding-top:24px">
				<div><div style="font-size:clamp(24px,2.8vw,34px); font-weight:300; letter-spacing:.02em">2013</div><div style="font-size:10px; letter-spacing:.24em; text-transform:uppercase; color:#6E6E6E; margin-top:9px"><?php pll_e( 'Primeira colheita' ); ?></div></div>
				<div><div style="font-size:clamp(24px,2.8vw,34px); font-weight:300; letter-spacing:.02em">8 ha</div><div style="font-size:10px; letter-spacing:.24em; text-transform:uppercase; color:#6E6E6E; margin-top:9px"><?php pll_e( 'Vinha trabalhada' ); ?></div></div>
				<div><div style="font-size:clamp(24px,2.8vw,34px); font-weight:300; letter-spacing:.02em">3</div><div style="font-size:10px; letter-spacing:.24em; text-transform:uppercase; color:#6E6E6E; margin-top:9px"><?php pll_e( 'Regiões' ); ?></div></div>
			</div>
		</div>
	</div>
</section>
