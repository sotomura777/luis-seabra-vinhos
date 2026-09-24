# O que pedir à empresa, à Site.pt e à Cosmicode (antes de migrar)

Lista pronta a adaptar para um email. O objetivo é ter **tudo em mãos antes de mexer em qualquer coisa** — em especial no DNS, para não interromper o email da empresa.

---

## Quem tem o quê (verificado em 2026-09-24 pelos registos públicos do domínio)
| Peça | Onde está | Custo atual |
|---|---|---|
| Domínio `luisseabravinhos.com` (expira a **2 jul 2027**) | **Site.pt** | 128,95 €/ano (junto com o email) |
| DNS (`ns7/ns8.sitedns.pt`) | **Site.pt** | incluído |
| Email (`mail.luisseabravinhos.com`, servidor próprio) | **Site.pt** | incluído |
| Site atual (servidor na DigitalOcean) | **Cosmicode** (agência) | 260 €/ano (alojamento + manutenção) |

**Consequência:** para mudar o site só precisamos da **conta da Site.pt** (onde está o DNS). A Cosmicode não é precisa para nada técnico. O email não é afetado, porque tem endereço próprio (`mail.`), separado do site.

## 1. Site.pt — domínio, DNS e email (pedir à empresa)
- [ ] **Quem tem o login da conta Site.pt** da empresa? Precisamos de acesso (ou de alguém que faça a alteração connosco no dia).
- [ ] Confirmar que o domínio está **em nome da empresa** (e não de uma pessoa ou agência).
- [ ] **Quantas caixas de email** têm (para avaliar se os 128,95 €/ano são bom preço).
- [x] ~~Export dos registos DNS~~ — já levantados publicamente: MX → `mail.luisseabravinhos.com` (167.235.169.140); SPF `v=spf1 +a +mx +ip4:167.235.169.140 +ip4:94.126.174.29 ~all`; DKIM e DMARC (`p=none`) configurados.
  > No dia da mudança: alterar **só** o registo `A` (e `www`) para o novo alojamento, e acrescentar ao SPF o serviço de envio de emails do site novo. **Nunca mexer no MX nem em `mail.`.**

## 2. Cosmicode — site atual (pedir à agência, via empresa)
- [ ] Data de fim do contrato / aviso prévio para **cancelar os 260 €/ano** — cancelar só **depois** de o site novo estar no ar e testado.
- [ ] (Opcional) Imagens originais em alta resolução e PDFs das fichas técnicas, se não os tiverem do lado da empresa.

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

## Email pronto a enviar à empresa — POR ENVIAR

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
> 13. Quem tem o login da conta da Site.pt (onde está o domínio e o email)? No dia da mudança só precisamos de alterar lá um registo — os emails não são afetados.
> 14. Quantas caixas de email @luisseabravinhos.com têm atualmente?
> 15. Qual é o prazo de aviso para cancelar o contrato com a Cosmicode? (Só cancelamos depois de o site novo estar no ar.)
>
> Obrigado!
