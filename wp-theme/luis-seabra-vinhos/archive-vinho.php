<?php
get_header();
get_template_part( 'template-parts/page-hero', null, array( 'roman' => 'III', 'eyebrow' => pll__( 'Vinhos' ), 'title' => pll__( 'Nove vinhos, três regiões' ), 'bg' => LSV_URI . '/assets/img/fotos/gama-completa.webp' ) );
echo "<main><section class=\"sec\"><div class=\"wrap\"><p class=\"muted\">Em breve.</p></div></section></main>";
get_footer();
