<?php
/**
 * Header — <head>, header fixo e cortina de menu (novo design).
 *
 * @package luisseabra
 */

$lsv_nav = lsv_nav();

/** É este item a página atual? (para aria-current) */
function lsv_nav_is_current( $item ) {
	if ( 'home' === $item['kind'] ) {
		return is_front_page();
	}
	if ( 'cpt' === $item['kind'] ) {
		return is_post_type_archive( $item['slug'] ) || is_singular( $item['slug'] );
	}
	return is_page( $item['slug'] );
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="lsv-head">
	<a class="wm" href="<?php echo esc_url( function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' ) ); ?>">Luís Seabra<small>Vinhos</small></a>
	<?php $sw = function_exists( 'lsv_language_switcher' ) ? lsv_language_switcher() : ''; ?>
	<nav>
		<?php if ( $sw ) : ?><span class="lang"><?php echo wp_kses_post( $sw ); ?></span><?php endif; ?>
		<button class="menu-btn" type="button" onclick="document.getElementById('lsv-menu').classList.add('on')"><?php pll_e( 'Menu' ); ?></button>
	</nav>
</header>

<div id="lsv-menu">
	<div class="bgimg" aria-hidden="true"></div>
	<div class="top">
		<span class="wm">Luís Seabra Vinhos</span>
		<button class="x" type="button" onclick="document.getElementById('lsv-menu').classList.remove('on')"><?php pll_e( 'Fechar' ); ?></button>
	</div>
	<nav class="list">
		<?php foreach ( $lsv_nav as $item ) : ?>
			<a href="<?php echo esc_url( lsv_nav_url( $item ) ); ?>" data-img="<?php echo esc_url( $item['img'] ); ?>"><small><?php echo esc_html( $item['roman'] ); ?></small><?php echo esc_html( pll__( $item['label'] ) ); ?></a>
		<?php endforeach; ?>
	</nav>
	<div class="foot">
		<a href="https://www.instagram.com/lseabrawine" target="_blank" rel="noopener">Instagram</a>
		<a href="https://www.facebook.com/luis.seabra.vinhos" target="_blank" rel="noopener">Facebook</a>
		<a href="https://x.com/lseabrawine" target="_blank" rel="noopener">X</a>
	</div>
</div>
