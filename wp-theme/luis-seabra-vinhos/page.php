<?php
/**
 * Página de texto simples (ex.: Política de Privacidade) — fundo claro, texto legível.
 * As páginas com desenho próprio têm o seu template (page-sobre.php, etc.).
 *
 * @package luisseabra
 */

get_header();
?>

<main>
	<section class="sec lt" style="padding-top:clamp(130px,20vh,200px)"><div class="wrap legal-page">
		<?php while ( have_posts() ) : the_post(); ?>
			<h1 class="h2"><?php the_title(); ?></h1>
			<div class="legal-body"><?php the_content(); ?></div>
		<?php endwhile; ?>
	</div></section>
</main>

<?php
get_footer();
