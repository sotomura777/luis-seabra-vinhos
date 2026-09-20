<?php
/**
 * Template Name: Contactos
 * Hero de contacto + mapa Leaflet (3 locais) + formulário (lsv_contacto) + newsletter (lsv_mailing).
 * O Leaflet e o contactos-map.js são enfileirados condicionalmente em inc/enqueue.php.
 *
 * @package luisseabra
 */

get_header();

// Dados de contacto — editáveis na página "Definições" (fallback = valores atuais).
$c_tel   = lsv_setting( 'contacto_tel', '+351 254 090 044' );
$c_tel2  = lsv_setting( 'contacto_tel2', '+351 913 190 201' );
$c_email = lsv_setting( 'contacto_email', 'geral@luisseabravinhos.com' );

$locais = array(
	array(
		'cap'    => pll__( 'Sede · Adega' ),
		'll'     => '41.1236,-7.4210',
		'nome'   => lsv_setting( 'loc1_nome', 'S. João da Pesqueira' ),
		'morada' => lsv_setting( 'loc1_morada', "Estrada Nacional 222, Lugar do Seixinhal\n5130-557 Vilarouco\nS. João da Pesqueira, Viseu" ),
		'tel'    => lsv_setting( 'loc1_tel', '+351 254 090 044 · +351 913 190 201' ),
	),
	array(
		'cap'    => pll__( 'Armazém' ),
		'll'     => '41.1210,-7.8060',
		'nome'   => lsv_setting( 'loc2_nome', 'Lamego' ),
		'morada' => lsv_setting( 'loc2_morada', "Zona Industrial Britiande, Quinta do Godim, Lote 1\n5100-454 Cepões, Lamego\nPortugal" ),
		'tel'    => lsv_setting( 'loc2_tel', '+351 254 096 518 · +351 913 190 201' ),
	),
	array(
		'cap'    => pll__( 'Escritório' ),
		'll'     => '41.1330,-8.6060',
		'nome'   => lsv_setting( 'loc3_nome', pll__( 'Escritório' ) ),
		'morada' => lsv_setting( 'loc3_morada', "Avenida da República 333\n2º Andar sala 16\n4430-999" ),
		'tel'    => lsv_setting( 'loc3_tel', '+351 223 166 509' ),
	),
);
?>

<style>
#chero{padding-top:0!important}
.chero{min-height:100svh;display:grid;grid-template-columns:minmax(0,1.15fr) minmax(0,.85fr);position:relative}
.chero .l{padding:clamp(140px,20vh,220px) clamp(24px,5vw,90px) clamp(56px,9vh,110px);display:flex;flex-direction:column;justify-content:space-between;gap:clamp(40px,7vh,80px)}
.chero .fig{position:relative;overflow:hidden;background:#000}
.chero .fig img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:50% 30%;filter:contrast(1.05) saturate(.9)}
.chero .fig:before{content:"";position:absolute;inset:0;z-index:1;background:linear-gradient(90deg,var(--bg) 0%,rgba(13,13,14,0) 26%),linear-gradient(0deg,var(--bg) 0%,rgba(13,13,14,0) 30%)}
.chero .fig .tag{position:absolute;z-index:2;left:clamp(20px,3vw,40px);bottom:clamp(24px,4vh,44px);display:flex;flex-direction:column;gap:8px}
.chero .fig .tag b{font-family:var(--serif);font-weight:400;font-size:clamp(22px,2vw,30px);letter-spacing:.04em;text-transform:uppercase}
.chero .l .num{display:inline-block;margin-bottom:26px}
.chero h1{margin:0}
.chero .l p{margin:clamp(30px,4vh,44px) 0 0;max-width:40ch;line-height:1.9}
.chero .r{display:flex;flex-direction:column;gap:0}
.crow{display:grid;grid-template-columns:110px minmax(0,1fr);gap:20px;align-items:baseline;padding:clamp(20px,3vh,30px) 0;border-top:1px solid var(--line,rgba(233,230,224,.12))}
.crow:last-child{border-bottom:1px solid var(--line,rgba(233,230,224,.12))}
.crow .v{font-family:var(--serif);font-weight:400;text-transform:uppercase;letter-spacing:.06em;font-size:clamp(18px,1.7vw,24px);line-height:1.2}
.crow .v a{position:relative}
.crow .v a:after{content:"";position:absolute;left:0;right:100%;bottom:-3px;height:1px;background:var(--gold);transition:right .5s cubic-bezier(.7,0,.2,1)}
.crow .v a:hover:after{right:0}
.crow .v small{display:block;font-family:var(--sans);font-size:11px;letter-spacing:.2em;text-transform:uppercase;color:var(--ink-3,#9C978E);margin-top:8px}

.places{position:relative;background:var(--panel,#141416);border-top:1px solid var(--line,rgba(233,230,224,.12));min-height:max(92svh,760px);display:flex;align-items:flex-end}
#lmap{position:absolute;inset:0;z-index:0}
.leaflet-container{background:#101012;font-family:var(--sans)}
.leaflet-tile-pane{filter:invert(1) hue-rotate(180deg) grayscale(.9) brightness(.62) contrast(1.15)}
.leaflet-control-attribution{background:rgba(13,13,14,.7);color:#6B6964;font-size:9px;letter-spacing:.05em}
.leaflet-control-attribution a{color:#8F8C86}
.places:before{content:"";position:absolute;inset:0;z-index:1;pointer-events:none;background:linear-gradient(0deg,var(--panel,#141416) 0%,rgba(20,20,22,.55) 40%,rgba(20,20,22,.15) 70%,var(--panel,#141416) 100%)}
.places .wrap{position:relative;z-index:2;width:100%;max-width:1440px;margin:0 auto;padding:0 clamp(24px,6vw,104px);display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1px;background:var(--line,rgba(233,230,224,.12));border:1px solid var(--line,rgba(233,230,224,.12));margin-bottom:clamp(40px,7vh,80px)}
.pl{padding:clamp(28px,3.4vw,44px);display:grid;grid-template-rows:auto auto 1fr auto;gap:20px;background:rgba(20,20,22,.82);backdrop-filter:blur(14px);cursor:pointer;transition:background .4s}
.pl:hover,.pl.on{background:rgba(30,30,33,.9)}
.pin-dot{width:12px;height:12px;border-radius:50%;background:var(--gold-2,#D9C39E);box-shadow:0 0 0 6px rgba(216,205,188,.15),0 0 0 1px rgba(216,205,188,.6)}
.pin-lab{background:none;border:none;box-shadow:none;padding:0;font-size:10px;letter-spacing:.3em;text-transform:uppercase;color:#F4F1EB;font-weight:400;text-shadow:0 1px 8px #000}
.pin-lab:before{display:none}
.pl h3{margin:0}
.pl address{font-style:normal;line-height:1.85}
.pl .hours{display:flex;justify-content:space-between;gap:12px;font-size:10px;letter-spacing:.28em;text-transform:uppercase;color:var(--ink-3,#9C978E);border-top:1px solid var(--line,rgba(233,230,224,.12));padding-top:16px}

.write{padding:clamp(110px,16vh,200px) 0;background:linear-gradient(180deg,#E6E4DF 0%,#D8D5CF 100%)}
.write .f{border-bottom-color:rgba(21,21,23,.28)}
.write .f:after{background:#151517}
.write .f .chev{border-color:#4F4D49}
.write .consent input{border-color:rgba(21,21,23,.28)}
.write .consent input:checked{background:#151517;border-color:#151517}
.write .send .ln{background:#4F4D49}
.write .send:hover .ln{background:#8A7147}
.write .wrap{max-width:1440px;margin:0 auto;padding:0 clamp(24px,6vw,104px);display:grid;grid-template-columns:minmax(0,.8fr) minmax(0,1.2fr);gap:clamp(48px,8vw,140px);align-items:start}
.write h2{margin:26px 0 0}
.write .lead{margin:30px 0 0;max-width:36ch;line-height:1.9}
.soc{display:flex;gap:28px;margin-top:clamp(40px,6vh,64px);font-size:10px;letter-spacing:.3em;text-transform:uppercase}
.soc a{position:relative;padding-bottom:4px}
.soc a:after{content:"";position:absolute;left:0;right:100%;bottom:0;height:1px;background:currentColor;transition:right .5s cubic-bezier(.7,0,.2,1)}
.soc a:hover:after{right:0}
.write form{display:grid;grid-template-columns:1fr 1fr;gap:0 clamp(24px,3vw,44px)}
.f{position:relative;padding:30px 0 14px;border-bottom:1px solid var(--hair-2,rgba(233,230,224,.22))}
.f.full{grid-column:1/-1}
.f label{position:absolute;left:0;top:32px;font-size:15px;color:var(--ink-3,#9C978E);letter-spacing:.02em;transition:transform .35s cubic-bezier(.7,0,.2,1),color .35s,font-size .35s;pointer-events:none;transform-origin:left top}
.f input,.f textarea,.f select{width:100%;background:none;border:none;color:var(--ink,#F4F1EB);font:inherit;font-size:17px;padding:0;outline:none;border-radius:0;line-height:1.5}
.f textarea{min-height:96px;resize:none}
.f select{appearance:none;cursor:pointer}
.f select option{background:#141416;color:#F4F1EB}
.f:after{content:"";position:absolute;left:0;right:100%;bottom:-1px;height:1px;background:var(--gold);transition:right .6s cubic-bezier(.7,0,.2,1)}
.f:focus-within:after{right:0}
.f:focus-within label,.f.has label{transform:translateY(-24px);font-size:10px;letter-spacing:.3em;text-transform:uppercase;color:var(--ink-3,#9C978E)}
.f .chev{position:absolute;right:0;bottom:18px;width:8px;height:8px;border-right:1px solid var(--ink-3,#9C978E);border-bottom:1px solid var(--ink-3,#9C978E);transform:rotate(45deg);pointer-events:none}
.consent{grid-column:1/-1;display:flex;gap:14px;align-items:flex-start;margin-top:30px;font-size:13px;line-height:1.7;color:var(--ink-3,#9C978E)}
.consent input{appearance:none;width:14px;height:14px;border:1px solid var(--hair-2,rgba(233,230,224,.22));margin:5px 0 0;flex:0 0 auto;cursor:pointer;position:relative}
.consent input:checked{background:var(--gold);border-color:var(--gold)}
.send{grid-column:1/-1;justify-self:start;margin-top:40px;display:inline-flex;align-items:center;gap:26px;background:none;border:none;color:var(--ink,#F4F1EB);font:inherit;font-size:11px;letter-spacing:.34em;text-transform:uppercase;padding:0;cursor:pointer}
.send .ln{width:64px;height:1px;background:var(--ink-2,#CFCAC1);position:relative;overflow:hidden;transition:width .5s cubic-bezier(.7,0,.2,1)}
.send:hover .ln{width:104px;background:var(--gold)}

.news{position:relative;background:#1B1C1E;border-top:1px solid var(--line,rgba(233,230,224,.12));overflow:hidden}
.news .wrap{position:relative;max-width:1440px;margin:0 auto;padding:clamp(70px,10vh,120px) clamp(24px,6vw,104px);display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:clamp(40px,6vw,120px);align-items:end}
.news h2{margin:22px 0 0}
.news form{display:grid;grid-template-columns:minmax(0,1fr) auto;align-items:end;gap:0 30px}
.news .f{padding-top:0}
.news .f label{top:2px}
.news .f:focus-within label,.news .f.has label{transform:translateY(-22px)}
.news .send{margin:0 0 14px}

.in{opacity:0;transform:translateY(18px);animation:in 1.1s cubic-bezier(.2,.7,.2,1) forwards}
.d1{animation-delay:.1s}.d2{animation-delay:.25s}.d3{animation-delay:.4s}.d4{animation-delay:.55s}
@keyframes in{to{opacity:1;transform:none}}

@media (max-width:820px){
  .chero{grid-template-columns:1fr}
  .chero .fig{min-height:56svh;order:-1}
  .write .wrap,.news .wrap{grid-template-columns:1fr}
  .write form{grid-template-columns:1fr}.f.full,.consent,.send{grid-column:auto}
  .crow{grid-template-columns:80px 1fr}
}
</style>

<main>
	<section class="chero hero" id="chero">
		<div class="l">
			<div>
				<span class="num in d1">X &nbsp;—&nbsp; <?php pll_e( 'Contactos' ); ?></span>
				<h1 class="in d2"><?php pll_e( 'Fale' ); ?><i><?php pll_e( 'connosco.' ); ?></i></h1>
				<p class="in d3"><?php pll_e( 'Para provas, visitas, encomendas ou imprensa. Respondemos em dois dias úteis — a vindima é a única exceção.' ); ?></p>
			</div>
			<div class="r in d4">
				<div class="crow"><span class="cap"><?php pll_e( 'Telefone' ); ?></span><span class="v"><a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $c_tel ) ); ?>"><?php echo esc_html( $c_tel ); ?></a><small><?php echo esc_html( $c_tel2 ); ?></small></span></div>
				<div class="crow"><span class="cap"><?php pll_e( 'Email' ); ?></span><span class="v"><a href="mailto:<?php echo esc_attr( $c_email ); ?>"><?php echo esc_html( $c_email ); ?></a><small><?php pll_e( 'Resposta em 48 horas' ); ?></small></span></div>
				<div class="crow"><span class="cap"><?php pll_e( 'Visitas' ); ?></span><span class="v"><a href="<?php echo esc_url( home_url( '/visitas/' ) ); ?>"><?php pll_e( 'Por marcação' ); ?></a><small><?php pll_e( 'Segunda a sexta das 9h00 às 16h00' ); ?></small></span></div>
			</div>
		</div>
		<figure class="fig in d3" style="margin:0">
			<img src="<?php echo esc_url( LSV_URI . '/assets/img/fotos/tonelaria.webp' ); ?>" alt="<?php echo esc_attr( pll__( 'Adega Luís Seabra Vinhos' ) ); ?>">
			<figcaption class="tag"><span class="cap on"><?php pll_e( 'Na adega' ); ?></span><b>Xisto Cru · Douro</b></figcaption>
		</figure>
	</section>

	<section class="places">
		<div id="lmap" aria-hidden="true"></div>
		<div class="wrap">
			<?php foreach ( $locais as $l ) :
				$tels = array_values( array_filter( array_map( 'trim', explode( '·', (string) $l['tel'] ) ) ) );
				?>
				<div class="pl" data-ll="<?php echo esc_attr( $l['ll'] ); ?>">
					<span class="cap on"><?php echo esc_html( $l['cap'] ); ?></span>
					<h3 class="h2"><?php echo esc_html( $l['nome'] ); ?></h3>
					<address class="muted"><?php echo nl2br( esc_html( $l['morada'] ) ); ?></address>
					<span class="hours"><span><?php echo esc_html( $tels[0] ?? '' ); ?></span><span><?php echo esc_html( $tels[1] ?? '' ); ?></span></span>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="write lt" id="contactos">
		<div class="wrap">
			<div>
				<span class="cap on"><?php pll_e( 'Escreva-nos' ); ?></span>
				<h2 class="h2"><?php pll_e( 'Uma pergunta, uma prova,' ); ?> <i><?php pll_e( 'uma encomenda.' ); ?></i></h2>
				<p class="lead muted"><?php pll_e( 'Se for uma visita, indique o dia e o número de pessoas. Para restauração ou importação, o país e o volume aproximado.' ); ?></p>
				<div class="soc"><a href="https://www.instagram.com/lseabrawine" target="_blank" rel="noopener">Instagram</a><a href="https://www.facebook.com/luis.seabra.vinhos" target="_blank" rel="noopener">Facebook</a><a href="https://x.com/lseabrawine" target="_blank" rel="noopener">X</a></div>
			</div>
			<div>
				<?php echo wp_kses_post( lsv_form_status_message( 'contacto' ) ); ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php wp_nonce_field( 'lsv_contacto', 'lsv_contacto_nonce' ); ?>
					<input type="hidden" name="action" value="lsv_contacto">
					<input type="hidden" name="redirect" value="<?php echo esc_url( get_permalink() ); ?>">
					<div class="f"><input id="n" name="nome" type="text" autocomplete="name" required><label for="n"><?php pll_e( 'Nome' ); ?></label></div>
					<div class="f"><input id="e" name="email" type="email" autocomplete="email" required><label for="e"><?php pll_e( 'Email' ); ?></label></div>
					<div class="f has"><select id="t" name="assunto"><option><?php pll_e( 'Visita e prova' ); ?></option><option><?php pll_e( 'Encomenda' ); ?></option><option><?php pll_e( 'Importação / distribuição' ); ?></option><option><?php pll_e( 'Imprensa' ); ?></option><option><?php pll_e( 'Outro' ); ?></option></select><span class="chev"></span><label for="t"><?php pll_e( 'Assunto' ); ?></label></div>
					<div class="f"><input id="p" name="tel" type="tel" autocomplete="tel"><label for="p"><?php pll_e( 'Telefone' ); ?></label></div>
					<div class="f full"><textarea id="m" name="mensagem" rows="3"></textarea><label for="m"><?php pll_e( 'Mensagem' ); ?></label></div>
					<div aria-hidden="true" style="position:absolute;left:-9999px"><label><?php pll_e( 'Não preencher' ); ?><input type="text" name="lsv_hp" tabindex="-1" autocomplete="off"></label></div>
					<label class="consent"><input type="checkbox" required> <?php pll_e( 'Aceito que os meus dados sejam usados apenas para responder a este pedido.' ); ?></label>
					<button class="send" type="submit"><span><?php pll_e( 'Enviar' ); ?></span><span class="ln"></span></button>
				</form>
			</div>
		</div>
	</section>

	<section class="news">
		<div class="wrap">
			<div><span class="cap on"><?php pll_e( 'Mailing list' ); ?></span><h2 class="h2"><?php pll_e( 'Novas colheitas, lançamentos limitados.' ); ?></h2><p class="muted" style="margin:22px 0 0;max-width:52ch;line-height:1.8"><?php pll_e( 'Receba em primeira mão notícias sobre novas colheitas, lançamentos limitados e experiências exclusivas. As mensagens serão pontuais e sempre com propósito — apenas quando houver algo verdadeiramente especial para partilhar.' ); ?></p></div>
			<div>
				<?php echo wp_kses_post( lsv_form_status_message( 'mailing' ) ); ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php wp_nonce_field( 'lsv_mailing', 'lsv_mailing_nonce' ); ?>
					<input type="hidden" name="action" value="lsv_mailing">
					<input type="hidden" name="redirect" value="<?php echo esc_url( get_permalink() ); ?>">
					<div class="f"><input id="ml" name="email" type="email" required><label for="ml"><?php pll_e( 'O seu email' ); ?></label></div>
					<div aria-hidden="true" style="position:absolute;left:-9999px"><label><?php pll_e( 'Não preencher' ); ?><input type="text" name="lsv_hp" tabindex="-1" autocomplete="off"></label></div>
					<button class="send" type="submit"><span><?php pll_e( 'Subscrever' ); ?></span><span class="ln"></span></button>
				</form>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
