# Luís Seabra Vinhos — landing page

Landing page com hero em scroll animado sobre dois vídeos (a garrafa a aparecer e o copo a encher).
Site estático: HTML, CSS inline e JavaScript, sem build.

## Ficheiros

```
index.html          a página
support.js          runtime necessário à página
image-slot.js       molduras de imagem (arrastar e largar)
uploads/            os dois vídeos do hero
assets/             imagem do Xisto Cru Tinto
```

## Ver localmente

Os vídeos não carregam com `file://`. Servir por HTTP:

```bash
python3 -m http.server 8000
# abrir http://localhost:8000
```

## Publicar no GitHub Pages

```bash
git init
git add .
git commit -m "Landing page Luís Seabra Vinhos"
git branch -M main
git remote add origin https://github.com/<utilizador>/<repositorio>.git
git push -u origin main
```

No GitHub: **Settings → Pages → Source: Deploy from a branch → main / (root)**.
Fica em `https://<utilizador>.github.io/<repositorio>/` em um ou dois minutos.

## Por rever antes de mostrar

- Os preços são provisórios (45 €, 42 €, 30 €, 32 €, 24 €, 22 €, 28 €, 185 €).
- As molduras de imagem da gama e das regiões estão vazias, à espera de fotografias.
- Prazos de envio e textos de contacto são um ponto de partida.
