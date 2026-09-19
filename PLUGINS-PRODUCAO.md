# Plugins de produção — o que instalar e como configurar

Todos **grátis, oficiais, 0 €/ano**. Instalados e configurados no WordPress local; repetir em produção. (Os plugins não fazem parte do tema/Git — vivem na instalação WordPress.)

## Instalar (via wp-cli ou Plugins → Adicionar novo)
```
wp plugin install seo-by-rank-math limit-login-attempts-reloaded wp-2fa updraftplus wp-mail-smtp --activate
```

## Rank Math (SEO) — ✅ configurado
- Módulos ativos só os essenciais: `link-counter, sitemap, rich-snippet, acf, role-manager, seo-analysis` (desligados: analytics, content-ai, ai-visibility, web-stories, woocommerce, buddypress, bbpress, instant-indexing).
- Knowledge Graph: tipo **Company**, nome **Luís Seabra Vinhos**, local business **Winery**. Usage tracking **off**.
- **Fazer sempre um `wp rewrite flush --hard`** após ativar, senão `/sitemap_index.xml` dá 404 (a regra de reescrita não regista).
- Coexiste com o Polylang (hreflang vem do Polylang; sem duplicados). O SEO do tema (`inc/seo.php`) recua sozinho quando o Rank Math está ativo.
- **Por fazer em produção:** ligar a Search Console; usar o **Redirects** para os 301 dos URLs antigos (precisa da lista de URLs do site atual).

## Limit Login Attempts — ✅ ativo
- Funciona out-of-the-box (limita brute-force no login). Nota GDPR: regista IPs — mencionar na política de privacidade.

## WP 2FA — ✅ ativo
- **Por fazer (interativo, por utilizador):** cada admin inscreve o 2FA (ler QR com Google Authenticator/Authy). Recomendado exigir 2FA para administradores.

## UpdraftPlus (backups) — ✅ agendado
- Base de dados **diária** (reter 7), ficheiros **semanal** (reter 4).
- **Por fazer:** ligar destino remoto (Google Drive/Dropbox) — precisa de OAuth na conta do cliente. Backup off-site é inegociável.

## WP Mail SMTP — ✅ instalado, ⏳ por configurar
- **Bloqueado à espera da info do email** (onde estão as caixas @luisseabravinhos.com). Depois: WP Mail SMTP → provider **Brevo** (grátis 300/dia), autenticar DKIM no domínio (só ADICIONAR registos DKIM, **nunca tocar no MX**). From: `no-reply@luisseabravinhos.com`.
- Sem isto, os emails dos formulários/reservas não saem.

## Segurança extra (fora de plugin, em produção)
- `wp-config.php`: `define('DISALLOW_FILE_EDIT', true);`
- HTTPS forçado + HSTS; Cloudflare grátis à frente (também resolve o Range dos vídeos do hero).
- Utilizador **Editor** para o cliente; **Administrator** só para o dev.
