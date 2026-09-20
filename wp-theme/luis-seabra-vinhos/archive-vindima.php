<?php
get_header();
get_template_part( 'template-parts/page-hero', null, array( 'roman' => 'VI', 'eyebrow' => pll__( 'Vindimas' ), 'title' => pll__( 'Notas de colheita' ), 'bg' => LSV_URI . '/assets/img/fotos/uvas-navalha.webp' ) );
echo "<main><section class=\"sec\"><div class=\"wrap\"><p class=\"muted\">Em breve.</p></div></section></main>";
get_footer();
