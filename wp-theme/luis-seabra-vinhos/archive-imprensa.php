<?php
get_header();
get_template_part( 'template-parts/page-hero', null, array( 'roman' => 'VII', 'eyebrow' => pll__( 'Imprensa' ), 'title' => pll__( 'Prémios e imprensa' ), 'bg' => LSV_URI . '/assets/img/fotos/vindimadores.webp' ) );
echo "<main><section class=\"sec\"><div class=\"wrap\"><p class=\"muted\">Em breve.</p></div></section></main>";
get_footer();
