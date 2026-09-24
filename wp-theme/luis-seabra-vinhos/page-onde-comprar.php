<?php
/**
 * Template Name: Onde comprar
 * Importadores / garrafeiras / restauração por mercado + formulário de pedido.
 *
 * @package luisseabra
 */

get_header();
$in  = 'background:none; border:none; border-bottom:1px solid rgba(21,21,23,.35); color:#151517; font-size:16px; padding:12px 2px; outline:none; width:100%';
$lab = 'display:flex; flex-direction:column; gap:6px; font-size:11px; letter-spacing:.24em; text-transform:uppercase; color:#5A5650';

// Mercados (conteúdo real; placeholders "" ficam à espera de texto do cliente).
$mercados = array(
	array( 'Portugal', 'Garrafeiras e restauração', '' ),
	array( 'Estados Unidos', 'Importador', 'Olé & Obrigado · Chambers & Chambers (CA, HI)' ),
	array( 'Alemanha · Áustria · Suíça', 'Importador', '' ),
	array( 'Reino Unido', 'Importador', '' ),
	array( 'Brasil', 'Importador', '' ),
	array( 'Outros mercados', 'Contacte-nos', 'geral@luisseabravinhos.com' ),
);
?>

<main>
	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'roman'   => 'IX',
		'eyebrow' => pll__( 'Onde comprar' ),
		'title'   => pll__( 'Importadores' ),
		'title_i' => pll__( 'e garrafeiras.' ),
		'lead'    => pll__( 'Onde encontrar os vinhos, por mercado. Não encontra perto de si? Escreva-nos.' ),
		'bg'      => LSV_URI . '/assets/img/fotos/gama-completa.webp',
	) );
	?>

	<section class="sec lt"><div class="wrap">
		<div class="grid3">
			<?php foreach ( $mercados as $m ) : ?>
				<div class="cell">
					<span class="cap on"><?php echo esc_html( pll__( $m[1] ) ); ?></span>
					<h3><?php echo esc_html( $m[0] ); ?></h3>
					<p class="muted"><?php echo $m[2] ? esc_html( $m[2] ) : '<span style="opacity:.5">' . esc_html( pll__( 'Em breve.' ) ) . '</span>'; // phpcs:ignore ?></p>
					<span class="foot"><a href="#pedido"><?php pll_e( 'Contactar' ); ?> &rarr;</a></span>
				</div>
			<?php endforeach; ?>
		</div>

		<div id="pedido" class="split" style="margin-top:clamp(50px,8vh,100px); border-top:1px solid rgba(138,113,71,.28); padding-top:clamp(50px,8vh,100px)">
			<div>
				<span class="cap on"><?php pll_e( 'Onde comprar' ); ?></span>
				<h2 class="h2"><?php pll_e( 'Procura os nossos vinhos?' ); ?></h2>
				<p class="muted" style="margin-top:14px; max-width:44ch"><?php pll_e( 'Diga-nos quem é e onde está. Encaminhamos para o ponto de venda ou distribuidor mais próximo.' ); ?></p>
			</div>
			<div>
				<?php echo wp_kses_post( lsv_form_status_message( 'compra' ) ); ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:flex; flex-direction:column; gap:18px">
					<?php wp_nonce_field( 'lsv_ondecomprar', 'lsv_oc_nonce' ); ?>
					<input type="hidden" name="action" value="lsv_ondecomprar">
					<input type="hidden" name="redirect" value="<?php echo esc_url( get_permalink() ); ?>">
					<div style="display:flex; flex-wrap:wrap; gap:18px">
						<label style="flex:1 1 220px; <?php echo esc_attr( $lab ); ?>"><?php pll_e( 'Nome' ); ?><input type="text" name="nome" required style="<?php echo esc_attr( $in ); ?>"></label>
						<label style="flex:1 1 220px; <?php echo esc_attr( $lab ); ?>"><?php pll_e( 'Email' ); ?><input type="email" name="email" required style="<?php echo esc_attr( $in ); ?>"></label>
					</div>
					<div style="display:flex; flex-wrap:wrap; gap:18px">
						<label style="flex:1 1 150px; <?php echo esc_attr( $lab ); ?>"><?php pll_e( 'Telefone' ); ?><input type="tel" name="tel" style="<?php echo esc_attr( $in ); ?>"></label>
						<label style="flex:1 1 150px; <?php echo esc_attr( $lab ); ?>"><?php pll_e( 'Cidade / País' ); ?><input type="text" name="local" style="<?php echo esc_attr( $in ); ?>"></label>
						<label style="flex:1 1 150px; <?php echo esc_attr( $lab ); ?>"><?php pll_e( 'Perfil' ); ?>
							<select name="tipo" style="<?php echo esc_attr( $in ); ?>">
								<option value="particular"><?php pll_e( 'Particular' ); ?></option>
								<option value="restaurante"><?php pll_e( 'Restaurante' ); ?></option>
								<option value="importador"><?php pll_e( 'Importador' ); ?></option>
							</select></label>
					</div>
					<label style="<?php echo esc_attr( $lab ); ?>"><?php pll_e( 'Mensagem' ); ?><textarea name="mensagem" rows="3" style="<?php echo esc_attr( $in ); ?>; resize:vertical"></textarea></label>
					<div aria-hidden="true" style="position:absolute; left:-9999px"><label><?php pll_e( 'Não preencher' ); ?><input type="text" name="lsv_hp" tabindex="-1" autocomplete="off"></label></div>
					<button type="submit" class="lsv-btn-ghost" style="align-self:flex-start; margin-top:6px; background:none; border:1px solid #151517; color:#151517; font:inherit; font-size:11px; letter-spacing:.24em; text-transform:uppercase; padding:14px 24px; cursor:pointer"><?php pll_e( 'Enviar pedido' ); ?></button>
					<?php lsv_form_privacy_note(); ?>
				</form>
			</div>
		</div>
	</div></section>
</main>

<?php
get_footer();
