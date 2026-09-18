<?php
/**
 * Footer.
 *
 * @package luisseabra
 */
?>
<footer style="background:#000000; color:#FFFFFF; padding:clamp(36px,6vh,60px) clamp(22px,6vw,96px); border-top:1px solid rgba(255,255,255,.18); display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:22px">
	<span style="font-size:12px; letter-spacing:.3em; text-transform:uppercase">Luís Seabra Vinhos</span>
	<div style="display:flex; flex-wrap:wrap; gap:22px; font-size:10.5px; letter-spacing:.2em; text-transform:uppercase; color:#C9C6C1">
		<a href="https://www.instagram.com/lseabrawine" target="_blank" rel="noopener">Instagram</a>
		<a href="https://www.facebook.com/luis.seabra.vinhos" target="_blank" rel="noopener">Facebook</a>
		<a href="https://x.com/lseabrawine" target="_blank" rel="noopener">X</a>
		<span><?php pll_e( 'Beba com moderação' ); ?></span>
		<span>&copy; <?php echo esc_html( date( 'Y' ) ); ?></span>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
