<?php
/**
 * Template Name: Visitas
 * Info de visitas + formulário de marcação (reutiliza o handler lsv_visita).
 *
 * @package luisseabra
 */

get_header();
$in  = 'background:none; border:none; border-bottom:1px solid rgba(21,21,23,.35); color:#151517; font-size:16px; padding:12px 2px; outline:none; width:100%';
$lab = 'display:flex; flex-direction:column; gap:6px; font-size:11px; letter-spacing:.24em; text-transform:uppercase; color:#5A5650';
?>

<main>
	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'roman'   => 'VIII',
		'eyebrow' => pll__( 'Visitas' ),
		'title'   => pll__( 'Provas e visitas,' ),
		'title_i' => pll__( 'por marcação.' ),
		'lead'    => pll__( 'Descubra vinhos de terroir, feitos com mínima intervenção e máxima expressão do lugar. As visitas são pensadas para quem procura autenticidade, detalhe e tempo.' ),
		'bg'      => LSV_URI . '/assets/img/fotos/tonelaria.webp',
	) );
	?>

	<section class="sec lt"><div class="wrap split">
		<div>
			<span class="cap on"><?php pll_e( 'A visita' ); ?></span>
			<div class="ledger" style="margin-top:clamp(20px,3vh,34px)">
				<div class="row"><span class="cap"><?php pll_e( 'Disponibilidade' ); ?></span><span class="v" style="font-family:var(--sans); font-size:18px"><?php echo esc_html( pll__( 'Segunda a sexta das 9h00 às 16h00' ) ); ?></span></div>
				<div class="row"><span class="cap"><?php pll_e( 'Prova' ); ?></span><span class="v" style="font-family:var(--sans); font-size:18px"><?php echo esc_html( pll__( 'Uma prova guiada de vinhos diretamente da barrica e da garrafa.' ) ); ?></span></div>
				<div class="row"><span class="cap"><?php pll_e( 'Idiomas' ); ?></span><span class="v" style="font-family:var(--sans); font-size:18px"><?php echo esc_html( pll__( 'Português, Inglês, Espanhol (outros consoante disponibilidade)' ) ); ?></span></div>
				<div class="row"><span class="cap"><?php pll_e( 'Local' ); ?></span><span class="v" style="font-family:var(--sans); font-size:18px"><?php echo esc_html( pll__( 'Estrada Nacional 222, Lugar do Seixinhal, 5130-557 Vilarouco, S. João da Pesqueira, Viseu' ) ); ?></span></div>
			</div>
		</div>

		<div>
			<span class="cap on"><?php pll_e( 'Pedir visita' ); ?></span>
			<?php echo wp_kses_post( lsv_form_status_message( 'visita' ) ); ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:flex; flex-direction:column; gap:18px; margin-top:20px">
				<?php wp_nonce_field( 'lsv_visita', 'lsv_visita_nonce' ); ?>
				<input type="hidden" name="action" value="lsv_visita">
				<input type="hidden" name="redirect" value="<?php echo esc_url( get_permalink() ); ?>">

				<div style="display:flex; flex-wrap:wrap; gap:18px">
					<label style="flex:1 1 220px; <?php echo esc_attr( $lab ); ?>"><?php pll_e( 'Nome' ); ?><input type="text" name="nome" required style="<?php echo esc_attr( $in ); ?>"></label>
					<label style="flex:1 1 220px; <?php echo esc_attr( $lab ); ?>"><?php pll_e( 'Email' ); ?><input type="email" name="email" required style="<?php echo esc_attr( $in ); ?>"></label>
				</div>
				<div style="display:flex; flex-wrap:wrap; gap:18px">
					<label style="flex:1 1 150px; <?php echo esc_attr( $lab ); ?>"><?php pll_e( 'Telefone' ); ?><input type="tel" name="tel" style="<?php echo esc_attr( $in ); ?>"></label>
					<label style="flex:1 1 140px; <?php echo esc_attr( $lab ); ?>"><?php pll_e( 'Data pretendida' ); ?><input type="date" name="data" required min="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>" style="<?php echo esc_attr( $in ); ?>"></label>
					<label style="flex:1 1 90px; <?php echo esc_attr( $lab ); ?>"><?php pll_e( 'Hora' ); ?><input type="time" name="hora" style="<?php echo esc_attr( $in ); ?>"></label>
					<label style="flex:1 1 90px; <?php echo esc_attr( $lab ); ?>"><?php pll_e( 'Nº de pessoas' ); ?><input type="number" name="pessoas" min="1" max="40" style="<?php echo esc_attr( $in ); ?>"></label>
				</div>
				<label style="<?php echo esc_attr( $lab ); ?>"><?php pll_e( 'Mensagem' ); ?><textarea name="mensagem" rows="3" style="<?php echo esc_attr( $in ); ?>; resize:vertical"></textarea></label>

				<div aria-hidden="true" style="position:absolute; left:-9999px"><label><?php pll_e( 'Não preencher' ); ?><input type="text" name="lsv_hp" tabindex="-1" autocomplete="off"></label></div>

				<button type="submit" class="lsv-btn-ghost" style="align-self:flex-start; margin-top:6px; background:none; border:1px solid #151517; color:#151517; font:inherit; font-size:11px; letter-spacing:.24em; text-transform:uppercase; padding:14px 24px; cursor:pointer"><?php pll_e( 'Marcar visita' ); ?></button>
			</form>
		</div>
	</div></section>
</main>

<?php
get_footer();
