# Instalação — tema Luís Seabra Vinhos (LocalWP)

Tema clássico em PHP. Design e animações portados do site estático original.

## Requisitos
- **LocalWP** (app grátis) com um site WordPress (PHP 8+, WP 6.4+).
- Plugins (grátis): **Advanced Custom Fields** e **Polylang**.

## Passos

1. **Copiar o tema**
   Copia a pasta `luis-seabra-vinhos/` para `wp-content/themes/` do teu site LocalWP.
   (No LocalWP: botão direito no site → *Reveal in Finder* → `app/public/wp-content/themes/`.)

2. **Ativar plugins e tema**
   - Instala e ativa **ACF** e **Polylang**.
   - Em *Aparência → Temas*, ativa **Luís Seabra Vinhos**.
   - Na ativação, o tema cria sozinho: os 3 termos de região e a página privada *Definições do Site*.

3. **Definir a homepage**
   *Definições → Leitura → A tua página inicial mostra → Uma página estática*.
   Cria/escolhe uma página vazia como *Página inicial* (o tema usa `front-page.php`, o conteúdo dela é ignorado).

4. **Polylang (só PT nesta v1)**
   *Idiomas* → adiciona **Português** (default). Podes já adicionar Inglês, mas o conteúdo EN cria-se depois.

5. **Importar os 9 vinhos + regiões** (uma vez)
   No LocalWP: botão direito no site → *Open site shell*, e corre:
   ```
   wp eval-file wp-content/themes/luis-seabra-vinhos/inc/seed-vinhos.php
   ```
   Isto cria os 9 vinhos, importa as garrafas para a Media Library e cria os 3 posts de *Vinhas* (texto das regiões).
   **Depois de correr, apaga o ficheiro `inc/seed-vinhos.php`.**

6. **Textos globais** (opcional, têm fallback)
   Edita a página *Definições do Site* (está em *Páginas*, como privada) para mudar os textos do Sobre, Contactos e as 3 legendas do hero.

7. **Notícias / Imprensa**
   Adiciona itens no menu *Imprensa* do admin (fonte, pontuação, data, link). A secção Notícias só aparece quando houver pelo menos um.

## Testar
- **Hero**: faz scroll na homepage — o vídeo deve seguir o scroll de forma fluida (inclui largar o trackpad).
- **Gama**: 3 carrosséis (Douro/Dão/Vinho Verde); arrasta as garrafas.
- **Formulário**: submete uma visita. Em LocalWP o email não sai para o mundo — vê-o na aba **Mailpit** do site. Para produção é preciso *WP Mail SMTP*.
- **Regiões / Notícias**: renderizam do conteúdo que criaste.

## Notas
- Os campos ACF estão registados em código (editas os *valores* por post; a estrutura vive nos ficheiros).
- Destinatário dos emails = *Definições → Geral → Endereço de email*. Para usar outro, adiciona em `functions.php`:
  `add_filter( 'lsv_form_recipient', fn() => 'reservas@dominio.pt' );`
