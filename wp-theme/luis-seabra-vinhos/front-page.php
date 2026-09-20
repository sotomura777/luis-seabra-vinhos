<?php
/**
 * Front page — a landing completa, montada por secções.
 * Índice de capítulos I–VI (scroll-spy no site.js) + secções em template-parts.
 *
 * @package luisseabra
 */

get_header();

// Índice de capítulos da landing (rail fixo à esquerda; data-ix = id da secção).
$chapters = array(
	'top'       => 'I',
	'sobre'     => 'II',
	'gama'      => 'III',
	'regioes'   => 'IV',
	'noticias'  => 'V',
	'contactos' => 'VI',
);
?>

<nav id="lsv-index" aria-label="<?php echo esc_attr( pll__( 'Capítulos' ) ); ?>" style="position:fixed; left:clamp(14px,2vw,28px); top:50%; transform:translateY(-50%); z-index:45; display:flex; flex-direction:column; gap:14px; mix-blend-mode:difference">
	<?php foreach ( $chapters as $id => $roman ) : ?>
		<a href="#<?php echo esc_attr( $id ); ?>" data-ix="<?php echo esc_attr( $id ); ?>" style="display:flex; align-items:center; gap:10px; color:#fff; font-size:9.5px; letter-spacing:.3em; text-transform:uppercase; opacity:.45; transition:opacity .4s"><span style="width:18px; height:1px; background:currentColor; display:block"></span><?php echo esc_html( $roman ); ?></a>
	<?php endforeach; ?>
</nav>

<div style="background:#FFFFFF">
	<?php
	get_template_part( 'template-parts/hero' );
	get_template_part( 'template-parts/sobre' );
	get_template_part( 'template-parts/gama' );
	get_template_part( 'template-parts/regioes' );
	get_template_part( 'template-parts/noticias' );
	get_template_part( 'template-parts/contactos' );
	?>
</div>

<?php
get_footer();
