<?php
/**
 * Template Name: Vinhas
 * Ledger das parcelas (solos, altitudes, idades).
 *
 * @package luisseabra
 */

get_header();

// Parcelas (conteúdo real do protótipo; migrar para o CPT `vinha` numa fase seguinte).
$parcelas = array(
	array( 'Meda · Douro', 'Xisto micáceo, 650–700 m', 'Vinha única plantada entre 1920 e 1933. Rabigato, Códega, Gouveio, Viosinho. → Xisto Cru Branco' ),
	array( 'Cima Corgo · Douro', 'Xisto micáceo, 500–600 m', 'Vinhas de 30 a 45 anos. → Xisto Ilimitado' ),
	array( 'Alijó · Douro', 'Vinha única, xisto', 'Tinta Roriz, Touriga Franca, Tinta Amarela, Rufete, Tinta Barroca. → Indie Xisto' ),
	array( 'Baixo Corgo · Douro', 'Xisto amarelo, 450 m', 'Plantada em 1993. 100% Castelão. → Mono C' ),
	array( 'Vila Nova de Tazém · Dão', 'Franco arenoso de origem granítica, 490 m', 'Mais de 35 anos, mais de 4500 plantas por hectare. → Granito Cru, Mono A' ),
	array( 'Monção e Melgaço · Vinho Verde', 'Granito', 'Alvarinho. → Granito Cru Alvarinho' ),
);
?>

<main>
	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'roman'   => 'V',
		'eyebrow' => pll__( 'Vinhas' ),
		'title'   => pll__( 'Solos, altitudes' ),
		'title_i' => pll__( 'e idades.' ),
		'lead'    => pll__( 'Seis parcelas em três regiões. Vinhas velhas, escolhidas pelo solo e pela altitude.' ),
		'bg'      => LSV_URI . '/assets/img/fotos/vinha-douro.webp',
	) );
	?>

	<section class="sec lt"><div class="wrap">
		<span class="cap on"><?php pll_e( 'Parcelas' ); ?></span>
		<div class="ledger" style="margin-top:clamp(24px,4vh,44px)">
			<?php foreach ( $parcelas as $p ) : ?>
				<div class="row">
					<span class="cap"><?php echo esc_html( $p[0] ); ?></span>
					<span class="v"><?php echo esc_html( $p[1] ); ?><small><?php echo esc_html( $p[2] ); ?></small></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div></section>
</main>

<?php
get_footer();
