# O que pedir à empresa / agência atual (antes de migrar)

Lista pronta a adaptar para um email. O objetivo é ter **tudo em mãos antes de mexer em qualquer coisa** — em especial no DNS, para não interromper o email da empresa.

---

## 1. Domínio e DNS (o ponto mais sensível)
- [ ] Confirmar **quem é o titular** do domínio `luisseabravinhos.com` (deve ser a empresa, não a agência).
- [ ] **Acesso ao registrar** (onde o domínio está registado) ou, no mínimo, acesso a editar os registos DNS.
- [ ] **Código de transferência (EPP/Auth)** do domínio — caso se queira transferir o domínio para a titularidade/registrar da empresa.
- [ ] **Export ou print de todos os registos DNS atuais**: `A`, `AAAA`, `CNAME`, **`MX`**, `TXT` (SPF/DKIM/DMARC), `NS`.
  > Isto é essencial: precisamos de saber para onde apontam os `MX` para **os preservarmos intactos** e nunca cortar o email.

## 2. Email @luisseabravinhos.com (não pode parar)
- [ ] **Onde estão alojadas as caixas de correio?** (cPanel/host, Google Workspace, Microsoft 365, outro?)
- [ ] Confirmar que **mudar o site NÃO obriga a mudar o email**.
- [ ] Registos de email atuais: **MX, SPF, DKIM, DMARC** (fazem parte do export do ponto 1).

## 3. Conteúdo e ficheiros do site atual
- [ ] **Código-fonte completo** do site atual.
- [ ] **Backup da base de dados**.
- [ ] **Todas as imagens** em alta resolução (vinhos, vinhas, regiões, equipa).
- [ ] **PDFs das fichas técnicas** de cada vinho (os 10). _Os dados técnicos já foram copiados do site atual; faltam só os PDFs para descarregar._
- [ ] **Textos** de todas as páginas (PT e EN, se existirem) — sobre, regiões, vinhos, imprensa, etc.
- [ ] **Lista de prémios / imprensa** (fonte, pontuação, data, link).

## 4. SEO — para não perder o Google na migração
- [ ] Acesso (ou verificação de propriedade) à **Google Search Console** do domínio atual.
- [ ] **URL do sitemap antigo** (`/sitemap.xml`), se existir.
- [ ] **Lista de URLs antigos** indexados (exportável da Search Console) — para fazer os redirects 301.
- [x] ~~**Estrutura de URLs atual**~~ — levantada a partir do código do site atual; redirects 301 já feitos (`inc/redirects.php`).
- [ ] Redirects já existentes, se os houver.

## 5. Analytics
- [ ] Acesso ao **Google Analytics** atual (ou confirmação de que existe / qual a conta).

## 6. Funcionamento atual (marcações e formulários)
- [ ] **Como funcionam hoje as marcações de visita** (se existem) e para onde vão os pedidos.
- [ ] **Para que email vão os formulários** atuais (contacto, etc.).
- [ ] Existe alguma **lista de subscritores / newsletter** a recuperar?

## 7. Acessos gerais
- [ ] Acesso ao **painel de alojamento atual** (para retirar ficheiros/BD, se preciso).
- [ ] Qualquer **conta de serviço** ligada ao site (mapas, chat, etc.).

## 8. Confirmações de conteúdo (levantadas em 2026-09-24)
- [ ] **Xisto Ilimitado Tinto — castas:** no site atual, a versão PT e a EN têm listas diferentes. Usámos a PT (Touriga Franca, Malvasia Preta, Alicante Bouschet, Tinta Roriz, Tinta Barroca, Tinta Amarela e outras). Qual é a certa?
- [ ] **Mono M — plantas por hectare:** no site atual aparece só "." — qual é o valor?
- [ ] **Cronologia (página Sobre):** a feira da estreia chama-se "Vinhos e Sabores" ou "Vinhos e Sabor"?
- [ ] **Imprensa:** texto original em inglês das citações da **Falstaff** e do **@thevineswinemakers** (as versões EN do site foram traduzidas por nós).
- [ ] **Contactos:** confirmar moradas, telefones e email das 3 moradas (Sede/Adega, Armazém Lamego, Escritório) — vieram do site atual.
- [ ] **Fotos das parcelas/regiões** (Douro, Dão, Vinho Verde) — hoje o site usa fotos genéricas.
- [ ] **Logótipo em ficheiro vetorial** (SVG/PDF), para o ícone do separador do browser (favicon).

## 9. Política de Privacidade (antes de publicar)
- [ ] Confirmar o **prazo de guarda** de marcações, pedidos e mensagens (proposta: 2 anos após o último contacto).
- [ ] **Revisão jurídica** do texto (rascunho em `/politica-de-privacidade/` e `/en/privacy-policy/`).
- [ ] _(Interno)_ Preencher na política o **fornecedor de alojamento** e o **fornecedor de envio de email**, quando estiverem escolhidos.

## 10. Contas a ligar (quando houver acesso)
- [ ] **Google Analytics:** criar uma propriedade GA4 na conta Google da empresa (ou dar-nos acesso) → código `G-…` para o `wp-config.php`. Retenção de dados: 14 meses; Google Signals desligado.
- [ ] **Mailchimp:** API key + Audience ID → `wp-config.php` (a newsletter passa a sincronizar sozinha).
- [ ] **Google Drive** para os backups automáticos (UpdraftPlus).
- [ ] **Google Search Console** do domínio (ponto 4).

---

### Nota de segurança para a migração (interna)
Regra de ouro no cutover: **muda-se só o registo `A` (e `www`) para o novo alojamento; nunca se toca no `MX`.** O site e o email são independentes — o site usa `A`, o email usa `MX`. Baixar o TTL 24-48 h antes, mudar o `A`, confirmar visualmente que os `MX`/SPF/DKIM ficaram iguais, emitir o SSL, e só depois testar envio+receção de um email real para uma caixa @luisseabravinhos.com antes de dar por concluído.

---

## Email pronto a enviar à empresa (secções 8–10) — POR ENVIAR

> Olá,
>
> O novo site está praticamente pronto. Para fecharmos, precisamos de algumas confirmações e acessos:
>
> **Conteúdo**
> 1. Xisto Ilimitado Tinto: no site atual, as castas da versão portuguesa e da inglesa não coincidem. Qual é a lista certa?
> 2. Mono M: qual é o número de plantas por hectare?
> 3. Na história do Luís, a feira onde os vinhos se estrearam chama-se "Vinhos e Sabores" ou "Vinhos e Sabor"?
> 4. Têm o texto original em inglês das citações da Falstaff e do @thevineswinemakers?
> 5. Podem confirmar as moradas e os telefones da Adega, do Armazém de Lamego e do Escritório?
> 6. Se tiverem: PDFs das fichas técnicas dos vinhos, fotos das vinhas por região e o logótipo em ficheiro vetorial (SVG ou PDF).
>
> **Privacidade**
> 7. Durante quanto tempo querem guardar os pedidos de visita e as mensagens? Propomos 2 anos.
> 8. Convém que alguém da vossa parte (jurista ou contabilista) reveja a Política de Privacidade antes de publicarmos.
>
> **Acessos** (só quando for altura de pôr o site no ar)
> 9. Google Analytics: criar uma propriedade na conta Google da empresa, ou dar-nos acesso.
> 10. Mailchimp: a chave de API e o ID da lista, para a newsletter passar a funcionar sozinha.
> 11. Uma conta Google Drive para os backups automáticos.
> 12. Acesso ao Google Search Console do domínio.
>
> Obrigado!
