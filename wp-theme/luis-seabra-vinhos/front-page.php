<?php
/**
 * Front page — a landing completa, montada por secções.
 *
 * @package luisseabra
 */

get_header();
?>

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
