<?php
/**
 * Header — <head>, header fixo e menu overlay.
 *
 * @package luisseabra
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="lsv-head" style="position:fixed; top:0; left:0; right:0; z-index:50; display:flex; align-items:center; justify-content:space-between; gap:20px; padding:20px clamp(18px,4vw,56px); color:#FFFFFF; transition:color .35s ease, background-color .35s ease">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>#top" style="display:flex; align-items:center; gap:11px; color:inherit">
		<span style="font-size:clamp(12px,1.4vw,14px); letter-spacing:.3em; text-transform:uppercase; font-weight:400">Luís Seabra</span>
		<span style="font-size:clamp(12px,1.4vw,14px); letter-spacing:.3em; text-transform:uppercase; font-weight:200; opacity:.6"><?php pll_e( 'Vinhos' ); ?></span>
	</a>
	<nav style="display:flex; align-items:center; gap:clamp(14px,2vw,26px); font-size:11px; letter-spacing:.2em; text-transform:uppercase; color:inherit">
		<?php
		$switcher = function_exists( 'lsv_language_switcher' ) ? lsv_language_switcher() : '';
		if ( $switcher ) :
			?>
			<span style="display:flex; align-items:center; gap:7px"><?php echo wp_kses_post( $switcher ); ?></span>
		<?php endif; ?>
		<a href="#gama" style="color:inherit; border-bottom:1px solid currentColor; padding-bottom:2px"><?php pll_e( 'Loja' ); ?></a>
		<button type="button" id="lsv-menu-open" style="background:none; border:none; color:inherit; font:inherit; letter-spacing:.2em; text-transform:uppercase; padding:0; cursor:pointer"><?php pll_e( 'Menu' ); ?></button>
	</nav>
</header>

<div id="lsv-menu" style="display:none; position:fixed; inset:0; z-index:60; background:#000000; color:#FFFFFF; padding:clamp(22px,5vw,56px); overflow-y:auto; flex-direction:column; gap:clamp(28px,5vh,64px)">
	<div style="display:flex; align-items:center; justify-content:space-between">
		<span style="font-size:13px; letter-spacing:.3em; text-transform:uppercase">Luís Seabra Vinhos</span>
		<button type="button" id="lsv-menu-close" style="background:none; border:none; color:inherit; font:inherit; font-size:11px; letter-spacing:.24em; text-transform:uppercase; cursor:pointer; opacity:.6"><?php pll_e( 'Fechar' ); ?></button>
	</div>
	<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:clamp(24px,3vw,44px); font-size:15px">
		<div style="display:flex; flex-direction:column; gap:12px">
			<span style="font-size:10px; letter-spacing:.28em; text-transform:uppercase; opacity:.45"><?php pll_e( 'Vinho' ); ?></span>
			<a href="#gama">Douro</a><a href="#gama">Dão</a><a href="#gama">Vinho Verde</a>
		</div>
		<div style="display:flex; flex-direction:column; gap:12px">
			<span style="font-size:10px; letter-spacing:.28em; text-transform:uppercase; opacity:.45"><?php pll_e( 'Regiões' ); ?></span>
			<a href="#regioes">Douro</a><a href="#regioes">Dão</a><a href="#regioes">Vinho Verde</a>
		</div>
		<div style="display:flex; flex-direction:column; gap:12px">
			<span style="font-size:10px; letter-spacing:.28em; text-transform:uppercase; opacity:.45"><?php pll_e( 'Mais' ); ?></span>
			<a href="#sobre"><?php pll_e( 'Sobre Nós' ); ?></a>
			<a href="#noticias"><?php pll_e( 'Notícias' ); ?></a>
			<a href="#contactos"><?php pll_e( 'Contactos / Mailing List' ); ?></a>
		</div>
	</div>
	<div style="margin-top:auto; display:flex; gap:22px; font-size:10.5px; letter-spacing:.2em; text-transform:uppercase; opacity:.6">
		<a href="https://www.instagram.com/lseabrawine" target="_blank" rel="noopener">Instagram</a>
		<a href="https://www.facebook.com/luis.seabra.vinhos" target="_blank" rel="noopener">Facebook</a>
		<a href="https://x.com/lseabrawine" target="_blank" rel="noopener">X</a>
	</div>
</div>
