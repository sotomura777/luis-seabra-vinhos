<?php
/**
 * Hero de página (selo romano + título + lead), reutilizável.
 * Uso: get_template_part( 'template-parts/page-hero', null, array(
 *   'roman' => 'II', 'eyebrow' => 'Sobre nós', 'title' => 'Vinhos que expressam',
 *   'title_i' => 'o sítio de onde vêm.', 'lead' => '…', 'bg' => 'url.webp', 'pos' => 'center 40%' ) );
 *
 * @package luisseabra
 */

$a       = wp_parse_args( $args ?? array(), array(
	'roman' => '', 'eyebrow' => '', 'title' => '', 'title_i' => '', 'lead' => '', 'bg' => '', 'pos' => 'center 40%',
) );
?>
<section class="hero" style="min-height:88svh">
	<?php if ( $a['bg'] ) : ?>
		<div class="img" style="position:absolute; inset:0; background-image:url('<?php echo esc_url( $a['bg'] ); ?>'); background-position:<?php echo esc_attr( $a['pos'] ); ?>; background-size:cover; opacity:.55"></div>
	<?php endif; ?>
	<div style="position:absolute; inset:0; background:linear-gradient(0deg,var(--bg) 0%,rgba(13,13,14,.55) 45%,rgba(13,13,14,.25) 100%)"></div>
	<div class="wrap" style="position:relative">
		<?php if ( $a['roman'] || $a['eyebrow'] ) : ?>
			<span class="num in d1"><?php echo esc_html( $a['roman'] ); ?> &nbsp;—&nbsp; <?php echo esc_html( $a['eyebrow'] ); ?></span>
		<?php endif; ?>
		<h1 class="in d2"><?php echo esc_html( $a['title'] ); ?><?php if ( $a['title_i'] ) : ?><br><i><?php echo esc_html( $a['title_i'] ); ?></i><?php endif; ?></h1>
		<?php if ( $a['lead'] ) : ?>
			<p class="lead in d3"><?php echo esc_html( $a['lead'] ); ?></p>
		<?php endif; ?>
	</div>
</section>
