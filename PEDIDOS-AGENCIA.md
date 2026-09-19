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
- [ ] **PDFs das fichas técnicas** de cada vinho.
- [ ] **Textos** de todas as páginas (PT e EN, se existirem) — sobre, regiões, vinhos, imprensa, etc.
- [ ] **Lista de prémios / imprensa** (fonte, pontuação, data, link).

## 4. SEO — para não perder o Google na migração
- [ ] Acesso (ou verificação de propriedade) à **Google Search Console** do domínio atual.
- [ ] **URL do sitemap antigo** (`/sitemap.xml`), se existir.
- [ ] **Lista de URLs antigos** indexados (exportável da Search Console) — para fazer os redirects 301.
- [ ] **Estrutura de URLs atual** (ex.: `/pt/vinhos/nome-do-vinho`) para desenhar as regras.
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

---

### Nota de segurança para a migração (interna)
Regra de ouro no cutover: **muda-se só o registo `A` (e `www`) para o novo alojamento; nunca se toca no `MX`.** O site e o email são independentes — o site usa `A`, o email usa `MX`. Baixar o TTL 24-48 h antes, mudar o `A`, confirmar visualmente que os `MX`/SPF/DKIM ficaram iguais, emitir o SSL, e só depois testar envio+receção de um email real para uma caixa @luisseabravinhos.com antes de dar por concluído.
