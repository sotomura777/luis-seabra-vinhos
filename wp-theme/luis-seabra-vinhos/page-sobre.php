<?php
/**
 * Template Name: Sobre
 * Página "Quem somos" — cronologia + testemunho + filosofia.
 *
 * @package luisseabra
 */

get_header();
$fotos    = LSV_URI . '/assets/img/fotos/';
$testemunho = lsv_setting( 'sobre_testemunho', 'Após muitos anos a trabalhar para outros, decidi seguir o meu próprio caminho, em 2013 foi criada a Luis Seabra Vinhos, com a missão de criar vinhos que expressem o sítio de onde vêem, feitos com uma filosofia de intervenção mínima respeitando a sua natureza e o seu carácter. Queremos que os vinhos mostrem os diferentes solos onde estão plantadas as vinhas, as diferentes altitudes e exposições, as suas diferenças e semelhanças. Queremos acima de tudo criar vinhos que sejam únicos. Com a consciência que estes são apenas os primeiros passos de um longo caminho, a aventura é ainda descobrir vinhas velhas desconhecidas que vão resistindo ao longo dos anos. Ao longo dos anos que fui trabalhando na região, criei uma relação especial com estas vinhas velhas.' );
?>

<main>
	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'roman'   => 'II',
		'eyebrow' => pll__( 'Sobre nós' ),
		'title'   => pll__( 'Vinhos que expressam' ),
		'title_i' => pll__( 'o sítio de onde vêm.' ),
		'lead'    => pll__( 'Após muitos anos a trabalhar para outros, decidi seguir o meu próprio caminho. Em 2013 foi criada a Luís Seabra Vinhos.' ),
		'bg'      => $fotos . 'vinha-douro.webp',
	) );
	?>

	<section class="sec lt"><div class="wrap">
		<span class="cap on"><?php pll_e( 'Quem somos' ); ?></span>
		<h2 class="h2"><?php pll_e( 'Da vinha à imprensa internacional' ); ?><i><?php pll_e( 'Um percurso em seis momentos' ); ?></i></h2>

		<div class="tl">
			<?php
			$timeline = array(
				array( 'I', 'Formação', 'l', 'Estudos e solos', 'Estuda viticultura na universidade e trabalha em investigação de solos antes de entrar na produção de vinho.', 'InsideHook' ),
				array( 'II', 'Carreira', 'r', 'Enólogo na Niepoort', 'Faz nome como enólogo da Niepoort, onde trabalha durante uma década antes de seguir o seu próprio caminho.', 'Wineanorak' ),
				array( 'III', '2013', 'l', 'Nasce a Luis Seabra Vinhos', 'Primeiras colheitas no Douro e no Vinho Verde, com a missão de criar vinhos que expressem o sítio de onde vêm.', '' ),
				array( 'IV', 'Primeiros anos', 'r', 'Uma estreia em Lisboa', 'A primeira vindima chega à feira Vinhos e Sabores. Um crítico prova num pequeno stand e sai a perguntar quem fez aquilo.', 'Vinography' ),
				array( 'V', 'Reconhecimento', 'l', 'N.º 4 — Top 100 Portugal 2025', 'O Xisto Cru Branco 2023 entra no Top 100 de James Suckling com 97 pontos. O Xisto Cru 2022 tinto figura na posição 73.', 'James Suckling' ),
				array( 'VI', 'Hoje', 'r', 'Três regiões, uma ideia', 'Dão, Douro e Vinho Verde. Vinhas velhas, intervenção mínima e uma equipa que inclui o enólogo Frederico Ferreira.', 'Wineanorak, 2026' ),
			);
			foreach ( $timeline as $t ) :
				list( $n, $chapter, $side, $h, $body, $src ) = $t;
				$ch   = '<div class="tl-ch">' . esc_html( $chapter ) . '</div>';
				$card = '<div class="tl-body"><h3>' . esc_html( $h ) . '</h3><p>' . esc_html( $body ) . '</p>' . ( $src ? '<span class="tl-src">' . esc_html( $src ) . '</span>' : '' ) . '</div>';
				?>
				<div class="tl-row <?php echo esc_attr( $side ); ?>" data-n="<?php echo esc_attr( $n ); ?>">
					<div class="tl-a"><?php echo 'l' === $side ? $ch : $card; // phpcs:ignore ?></div>
					<div class="tl-node"></div>
					<div class="tl-b"><?php echo 'l' === $side ? $card : $ch; // phpcs:ignore ?></div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="split" style="margin-top:clamp(40px,7vh,80px); border-top:1px solid rgba(122,95,60,.3); padding-top:clamp(40px,7vh,80px)">
			<div><span class="cap on"><?php pll_e( 'Nas palavras do Luís' ); ?></span></div>
			<p style="margin:0; font-size:clamp(16px,1.25vw,18px); line-height:1.9"><?php echo esc_html( $testemunho ); ?></p>
		</div>
	</div></section>

	<section class="sec" style="padding-top:0"><div class="wrap" style="display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:1px; padding-top:1px; margin-bottom:clamp(90px,14vh,170px)">
		<div class="img" style="aspect-ratio:3/2; background:center/cover url('<?php echo esc_url( $fotos . 'tonelaria.webp' ); ?>')"></div>
		<div class="img" style="aspect-ratio:3/2; background:center/cover url('<?php echo esc_url( $fotos . 'vindimadores.webp' ); ?>')"></div>
		<div class="img" style="aspect-ratio:3/2; background:center/cover url('<?php echo esc_url( $fotos . 'padaria.webp' ); ?>')"></div>
	</div><div class="wrap split">
		<div><span class="cap on"><?php pll_e( 'Filosofia' ); ?></span><h2 class="h2"><?php pll_e( 'Intervenção' ); ?><br><i><?php pll_e( 'mínima.' ); ?></i></h2></div>
		<div class="grid3">
			<div class="cell"><h3><?php pll_e( 'Vinhas velhas' ); ?></h3><p class="muted"><?php pll_e( 'Pequenas, remotas, escolhidas pelo solo. Cepas que nunca souberam o que é rega.' ); ?></p><span class="foot"><?php pll_e( 'Vinha' ); ?></span></div>
			<div class="cell"><h3><?php pll_e( 'Leveduras indígenas' ); ?></h3><p class="muted"><?php pll_e( 'Fermentação espontânea, cachos inteiros, extração muito suave ao longo de até 30 dias.' ); ?></p><span class="foot"><?php pll_e( 'Adega' ); ?></span></div>
			<div class="cell"><h3><?php pll_e( 'Madeira usada' ); ?></h3><p class="muted"><?php pll_e( 'Estágio em barricas e tonéis usados, sem bâtonnage. Aço inox só quando é indispensável.' ); ?></p><span class="foot"><?php pll_e( 'Estágio' ); ?></span></div>
		</div>
	</div></section>
</main>

<?php
get_footer();
