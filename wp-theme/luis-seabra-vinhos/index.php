<?php
/**
 * Fallback genérico. A landing vive em front-page.php; este template só
 * evita erros em rotas que não sejam a homepage.
 *
 * @package luisseabra
 */

get_header();
?>

<main style="max-width:800px; margin:0 auto; padding:clamp(120px,20vh,220px) clamp(22px,6vw,96px)">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>
			<article style="margin-bottom:64px">
				<h1 style="font-size:clamp(28px,4vw,44px); font-weight:200; letter-spacing:.01em"><?php the_title(); ?></h1>
				<div style="font-size:16px; line-height:1.8; color:#4A4A4A; margin-top:24px"><?php the_content(); ?></div>
			</article>
			<?php
		endwhile;
	else :
		?>
		<p style="font-size:16px; color:#4A4A4A"><?php pll_e( 'Nada encontrado' ); ?></p>
		<?php
	endif;
	?>
</main>

<?php
get_footer();
