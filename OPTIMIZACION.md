# OPTIMIZACIÓN — Ronaldou24.github.io

> **Spec de ejecución para Claude Code.**
> Repo: `Ronaldou24.github.io` (GitHub Pages, sitio estático, sin build step).
> Autor: Ronaldo Gael Solano Gutiérrez (Roni) · Puerto Vallarta, Jalisco → Guadalajara.
> Fecha del spec: 15 de agosto de 2026.
>
> **Cómo usar este archivo:** abre Claude Code en la raíz del repo y dile
> `lee OPTIMIZACION.md y ejecuta la FASE 0`. Cada fase se ejecuta y se verifica
> **completa** antes de pasar a la siguiente. No brinques fases.

---

## 0. POR QUÉ IMPORTA ESTO

Este no es un sitio de práctica. Es **la carta de presentación de Roni como
desarrollador y freelance**: es el link que va a mandar a clientes, a reclutadores,
a compañeros de CUCEI y a su Pinterest. Un portafolio que tarda 8 segundos en cargar
en un celular con datos móviles **pierde al cliente antes de que vea un solo proyecto**.

La estética retro no se negocia y no se "moderniza". El sitio es un escritorio de
consola de 64 bits y así se queda. Lo que cambia es **lo que hay debajo**: que sea
rápido, que funcione en cualquier pantalla, y que la información esté completa.

**Regla de oro:** si un cambio hace el sitio más rápido pero menos retro, no se hace.
Si lo hace más retro pero más lento, se busca la forma de tener las dos.

---

## 1. ESTADO ACTUAL — AUDITORÍA MEDIDA

Todo lo de abajo está **medido sobre el repo real**, no estimado.

### 1.1 Peso y estructura

| Recurso | Peso | Nota |
|---|---:|---|
| `index.html` | **133,034 B** (3,348 líneas) | Monolito: HTML + 52,824 chars de CSS inline + 33,816 chars de JS inline en 3 bloques |
| `three.js r128` (cdnjs) | **~580 KB** | Se descarga **siempre**, aunque el visor 3D viva dentro de una ventana que casi nadie abre |
| Google Fonts | ~150 KB | 4 familias: `Press Start 2P`, `Michroma`, `Barlow` (4 pesos), `JetBrains Mono` (3 pesos) |
| `ki.png` (hero) | **1,074,101 B** | PNG de 1 MB, carga *eager*, sin `width`/`height` |
| `cat.png` | 197,138 B | Decorativa, *eager* |
| `gods.png` | 100,131 B | Decorativa, *eager* |
| `styles/tokens.css` | 2,097 B | Único CSS externo enlazado |
| **TOTAL primera visita** | **≈ 2.2 MB** | En 4G real: 6–10 s hasta que se ve algo útil |

### 1.2 Lastre del repositorio (no se carga, pero pesa)

Estos archivos están en el repo y GitHub Pages los sirve, pero **ninguno se usa en la página**:

- `curriculum.rar` — **30,805,695 B (30.8 MB)** ← esto solo hace que clonar el repo sea insoportable
- `image.png` — 3,623,117 B (3.6 MB)
- `ki.png` sí se usa, pero `pesi.png` (471,703 B), `gatito.png` (402,797 B) y `assets/hero-bg.png` (520,054 B) **no**
- `styles.css` (11,579 B) y `script.js` (392 B) — **huérfanos**: `index.html` no los enlaza. Código muerto.

### 1.3 Costo de CPU / GPU

- **32** declaraciones `animation:`, **51** `box-shadow`, **20** `filter:` en el CSS.
  Varias animaciones son `infinite` y corren aunque el elemento esté fuera del viewport.
- **Cero** `will-change`. Cero `content-visibility`.
- El loop `animate()` de three.js (línea ~3254) **nunca se detiene**. Una vez que se abre
  `papusBlindajeWindow`, sigue renderizando a 60 fps aunque la ventana esté cerrada
  (`display:none`) o la pestaña en segundo plano. Devorador de batería.
- `new THREE.WebGLRenderer({antialias:true, preserveDrawingBuffer:true})` —
  `preserveDrawingBuffer` desactiva optimizaciones del navegador y es caro.
- La **cámara glitch** hace `getImageData()` + doble `for` por píxel **en cada frame**,
  en el hilo principal. Eso es JS puro tocando ~250,000 píxeles a 60 fps. Jank garantizado
  en cualquier celular.

### 1.4 SEO y metadatos — **hoy están vacíos**

En `<head>` no hay: `meta description`, Open Graph, Twitter Card, favicon declarado,
`theme-color`, `canonical`, ni JSON-LD. **Consecuencia directa:** cuando Roni manda el link
por WhatsApp, Telegram o lo pinea en Pinterest, **no sale preview**: sale un link pelón.
Para un portafolio freelance eso es perder la primera impresión antes de que abran nada.

Tampoco hay `robots.txt` ni `sitemap.xml`.

---

## 2. BUGS DE CELULAR — CAUSA RAÍZ CONFIRMADA

Roni reporta que "hay unas pestañas que en celular no funcionan". Son **estas**, y esta es
la razón técnica de cada una:

### BUG-1 — Las ventanas se posicionan con medidas inventadas 🔴 CRÍTICO
`openWindow()` llama a `estimateWindowSize()`, que **adivina** el tamaño (380×300 px)
*antes* de que la ventana se muestre. Pero en móvil la ventana real mide
`calc(100vw - 20px)` de ancho y hasta `82vh` de alto. El `clampPos()` calcula con datos
falsos → la ventana queda posicionada fuera del área útil y **su parte de abajo,
donde están los links y botones, es inalcanzable**.
**Fix:** mostrar primero con `visibility:hidden`, medir con `getBoundingClientRect()`,
posicionar, y hasta entonces hacer visible.

### BUG-2 — `position: fixed` + `innerHeight` mienten en móvil 🔴 CRÍTICO
`clampPos()` usa `window.innerHeight`. En iOS Safari y Chrome Android la barra de URL
se colapsa y expande: `innerHeight` reporta la altura *grande*, así que la ventana queda
parcialmente debajo de la barra del navegador.
**Fix:** usar `window.visualViewport.height` cuando exista, y `100dvh` en CSS en lugar de `100vh`.

### BUG-3 — "Abrir escritorio" apila 9 ventanas encima de la misma 🔴 CRÍTICO
El botón `#openAllBtn` abre 9 ventanas de golpe. En desktop se ven escalonadas; en móvil
**todas miden pantalla completa y quedan una encima de otra**. El usuario ve una sola y
cree que las demás "no abren". No hay forma de navegar entre ellas.
**Fix:** en móvil, modo *sheet* + una **taskbar con las ventanas abiertas** para cambiar entre ellas.
Y en móvil, `#openAllBtn` no abre 9: abre un índice.

### BUG-4 — Los tooltips no existen en celular 🟠 ALTO
Los `[data-tooltip]` se activan con `mouseenter`/`mouseleave`. En una pantalla táctil
**nunca se disparan**. Toda la info que vive ahí es invisible en móvil. Esto es,
literalmente, "info que no funciona en el celular".
**Fix:** activar también con `click`/`focus` y cerrar con toque fuera.

### BUG-5 — El scroll dentro de la ventana arrastra la página de atrás 🟠 ALTO
`.window-content` tiene `overflow-y: auto` pero no `overscroll-behavior: contain`.
En iOS, al llegar al final del contenido el gesto sigue y mueve el fondo.
**Fix:** `overscroll-behavior: contain` + `-webkit-overflow-scrolling: touch`.

### BUG-6 — `touch-action: none` en la barra de título bloquea el scroll 🟡 MEDIO
`.title-bar` tiene `touch-action: none` (correcto para arrastrar en desktop), pero en móvil
arrastrar ventanas no sirve de nada y ese gesto **secuestra el scroll** si el dedo cae ahí.
**Fix:** desactivar el drag por completo bajo 900px.

### BUG-7 — Objetivos táctiles demasiado chicos 🟡 MEDIO
`.btn-close` mide 28×28 px en móvil. El mínimo recomendado (WCAG 2.5.5 / Apple HIG) es
**44×44 px**. Varios íconos y chips están igual.

### BUG-8 — Funciones exclusivas de teclado, sin equivalente táctil 🟡 MEDIO
El código Konami y las flechas ← → para cambiar de personaje 3D **no tienen forma de
activarse en celular**. El easter egg y el cambio de crítter quedan muertos ahí.
**Fix:** secuencia táctil alterna (5 toques en el logo) y los botones ◀ ▶ ya existentes bien visibles.

### BUG-9 — Sin `Escape`, sin `role="dialog"`, sin foco atrapado 🟡 MEDIO
Las ventanas no son diálogos accesibles. Con teclado no hay forma de cerrarlas.

---

## 3. PRESUPUESTO DE RENDIMIENTO (metas duras)

Ninguna fase se da por terminada si rompe estos números.

| Métrica | Hoy (estimado) | Meta |
|---|---:|---:|
| Peso total, primera visita | ≈ 2.2 MB | **< 500 KB** |
| `index.html` | 133 KB | **< 30 KB** |
| CSS total (sin comprimir) | 55 KB inline | **< 45 KB**, crítico inline **< 10 KB** |
| JS ejecutado al cargar | ~34 KB + 580 KB three.js | **< 20 KB** (three.js solo bajo demanda) |
| Imágenes above-the-fold | ~1.37 MB | **< 150 KB** |
| LCP (móvil, 4G simulado) | ~6–8 s | **< 2.0 s** |
| CLS | sin medir (imgs sin dimensiones) | **< 0.05** |
| TBT | alto | **< 200 ms** |
| Lighthouse Performance (móvil) | — | **≥ 90** |
| Lighthouse Accesibilidad | — | **≥ 95** |
| Lighthouse Best Practices | — | **≥ 95** |
| Lighthouse SEO | — | **≥ 95** |

---

## 4. ARQUITECTURA OBJETIVO

Se parte el monolito. Sigue siendo **GitHub Pages puro, sin build step, sin npm en producción**.

```
Ronaldou24.github.io/
├── index.html                  # < 30 KB — solo markup + CSS crítico inline
├── css/
│   ├── tokens.css              # variables: paleta PICO-8, tipografías, espaciados
│   ├── base.css                # reset, tipografía, utilidades, :focus-visible
│   ├── components.css          # ventanas, taskbar, escritorio, hero, tarjetas, chips
│   ├── fx.css                  # animaciones, glitch, scanlines, decorativos
│   └── responsive.css          # media queries; móvil primero en lo nuevo
├── js/
│   ├── main.js                 # entry point (type="module")
│   ├── window-manager.js       # abrir/cerrar/z-index/posición — modo desktop y modo sheet
│   ├── taskbar.js              # reloj + switcher de ventanas abiertas
│   ├── tooltip.js              # tooltips con soporte táctil
│   ├── perf.js                 # pausar animaciones fuera de viewport, prefers-reduced-motion
│   ├── easter-eggs.js          # konami, bsod, ícono fantasma, logro
│   ├── render.js               # pinta servicios/skills/lab/proyectos desde data/*.json
│   └── critters/
│       ├── viewer.js           # carga three.js con import() dinámico
│       ├── gatito.js  momo.js  tvbot.js
│       └── glitch-cam.js       # cámara glitch reescrita sin getImageData
├── data/
│   ├── proyectos.json
│   ├── servicios.json          # ← Roni edita precios/paquetes aquí, sin tocar código
│   ├── skills.json
│   └── lab.json
├── assets/
│   ├── img/                    # todo en .webp con fallback .png donde haga falta
│   └── og-cover.png            # 1200×630 para previews de redes
├── robots.txt
├── sitemap.xml
└── README.md
```

**Por qué así:** cada archivo se cachea por separado (cambias un color y el visitante
no vuelve a bajar 133 KB), el JS pesado solo baja cuando se necesita, y cualquiera
que abra el repo — un reclutador, un cliente técnico — entiende la estructura en 10 segundos.
Eso también es portafolio.

---

## 5. FASES DE EJECUCIÓN

### FASE 0 — Red de seguridad (obligatoria, primero)

- [ ] `git status` limpio. Si hay cambios sin commitear, commitearlos antes de empezar.
- [ ] Crear rama: `git checkout -b optimizacion-2026`
- [ ] Etiquetar el estado actual: `git tag pre-optimizacion`
- [ ] Levantar servidor local: `python -m http.server 8000`
- [ ] Capturar **baseline** de Lighthouse antes de tocar nada:
  ```bash
  npx lighthouse http://localhost:8000 --preset=desktop --output=json --output-path=./.perf/baseline-desktop.json --quiet
  npx lighthouse http://localhost:8000 --form-factor=mobile --output=json --output-path=./.perf/baseline-mobile.json --quiet
  ```
- [ ] Agregar `.perf/` al `.gitignore`.
- [ ] Anotar en `.perf/NOTAS.md` los números de partida (LCP, CLS, TBT, peso total).

**Criterio de aceptación:** existe la rama, existe el tag, y hay dos JSON de baseline.
Sin baseline no se puede demostrar la mejora, y demostrar la mejora es parte del entregable.

---

### FASE 1 — Limpieza de peso muerto (la victoria más barata)

- [ ] **Sacar `curriculum.rar` (30.8 MB) del repo.** Agregarlo a `.gitignore`.
      El CV ya está publicado como PDF (`Ronaldo Gael Solano Gutierrez-1 (1).pdf`), el `.rar` no aporta nada.
- [ ] Borrar o mover fuera del repo lo que no se usa: `image.png` (3.6 MB), `pesi.png`,
      `gatito.png`, `assets/hero-bg.png`, `d.txt`, `papus.json` — **verificar con `grep` antes de borrar cada uno**:
      ```bash
      grep -rn "nombre-del-archivo" --include="*.html" --include="*.css" --include="*.js" .
      ```
- [ ] Borrar `styles.css` y `script.js` (huérfanos confirmados: `index.html` no los enlaza).
      Si algo de `styles.css` sirve, migrarlo a `css/components.css` **antes** de borrarlo.
- [ ] Renombrar el PDF del CV a `cv-ronaldo-solano.pdf` (sin espacios ni paréntesis: los espacios
      en URLs se escapan feo y rompen links al compartir). Actualizar el `href` en `index.html`.
- [ ] Igual con las rutas que hoy llevan `%20`: `htdocs/Estetica_Canina - Monuu/`,
      `htdocs/web2/uploads/2025124304_Captura de pantalla...`. Renombrar a `kebab-case` sin espacios.

**Criterio de aceptación:** `du -sh .` baja de forma evidente; ningún link roto
(verificar con el checador de la Fase 8).

---

### FASE 2 — Imágenes (el 60% del problema de velocidad)

- [ ] Convertir a WebP con calidad 82 (calidad visual idéntica, ~75% menos peso):
  ```bash
  # instalar: sudo apt install webp   |   brew install webp
  cwebp -q 82 ki.png -o assets/img/ki.webp
  cwebp -q 82 cat.png -o assets/img/cat.webp
  cwebp -q 82 gods.png -o assets/img/gods.webp
  for f in assets/proyectos/*.jpg; do cwebp -q 82 "$f" -o "assets/img/$(basename "${f%.jpg}").webp"; done
  ```
- [ ] `ki.png` (1 MB) además hay que **redimensionarla**: no necesita más de 900 px de ancho
      para el marco del hero. `cwebp -resize 900 0 -q 82 ki.png -o assets/img/ki.webp`.
      **Meta: < 120 KB.**
- [ ] Servir con `<picture>` para navegadores viejos:
  ```html
  <picture>
    <source srcset="assets/img/ki.webp" type="image/webp">
    <img src="assets/img/ki.png" alt="Portada estilo caja de videojuego N64, arte 'Digital Future'"
         width="900" height="1200" fetchpriority="high" decoding="async">
  </picture>
  ```
- [ ] **`width` y `height` explícitos en TODAS las imágenes.** Es lo que mata el CLS
      (el brinco de la página mientras carga). Sin excepción.
- [ ] `loading="lazy"` + `decoding="async"` en todo lo que no sea el hero.
      El hero lleva `fetchpriority="high"` y **no** lleva lazy.
- [ ] Las decorativas (`cat.png`, `gods.png` en el escritorio) → `alt=""` + `aria-hidden="true"`.
      Hoy tienen `alt="Imagen decorativa del escritorio"`, que le hace perder el tiempo a un lector de pantalla.
- [ ] Las 2 imágenes de `github-readme-stats.vercel.app`: agregarles `loading="lazy"`,
      `width`/`height`, y un `onerror` que muestre un fallback de texto. Ese servicio tiene
      arranques en frío de varios segundos y a veces falla; hoy dejaría un hueco en la ventana.
- [ ] Crear `assets/og-cover.png` de **1200×630 px** para los previews de redes
      (reusar el arte de `ki.png` con el nombre encima, en la misma estética).

**Criterio de aceptación:** imágenes above-the-fold suman **< 150 KB**; CLS **< 0.05**.

---

### FASE 3 — Partir el monolito

- [ ] Extraer el bloque `<style>` (líneas 11–1581) a los 5 archivos de `css/` según la
      estructura de la §4. Respetar el orden de cascada: `tokens → base → components → fx → responsive`.
- [ ] **CSS crítico inline:** dejar dentro de `<head>` solo lo que se ve sin hacer scroll
      (tokens, reset, `.taskbar`, `.hero`, `.desktop`). Meta: **< 10 KB**.
      El resto va en `<link>`.
- [ ] Cargar `fx.css` sin bloquear el render:
  ```html
  <link rel="stylesheet" href="css/fx.css" media="print" onload="this.media='all'">
  <noscript><link rel="stylesheet" href="css/fx.css"></noscript>
  ```
- [ ] Extraer los 3 bloques `<script>` (líneas 2387–2713, 2716–3311, 3313–3345) a los
      módulos de `js/`. Entry point único:
  ```html
  <script type="module" src="js/main.js"></script>
  ```
- [ ] **Purgar CSS muerto.** Con 3,348 líneas hay reglas que ya no aplican a nada:
  ```bash
  npx purgecss --css css/*.css --content index.html js/*.js --output ./.perf/purged/
  ```
  Revisar el resultado **a mano** antes de aplicar — PurgeCSS no ve las clases que el JS
  agrega dinámicamente (`.burst`, `.shake`, `.konami-flash`, `.visible`, `.active`, `.hidden`,
  `.window`, `.popup`). **Agregarlas al safelist.**
- [ ] Adelgazar las fuentes: `Michroma` y `Barlow` con 4 pesos es exceso.
      Quedarse con `Press Start 2P` (400), `Barlow` (400, 700) y `JetBrains Mono` (400).
      Evaluar tirar `Michroma` y usar `Press Start 2P` en su lugar.
      Agregar `&display=swap` (ya está) y `font-display: swap`.

**Criterio de aceptación:** `index.html` **< 30 KB**; el sitio se ve **pixel por pixel igual**
que antes en desktop (comparar capturas antes/después).

---

### FASE 4 — Arreglar los bugs de celular (§2)

Esta es la fase que Roni pidió explícitamente. Cada bug de la §2 tiene que quedar cerrado.

#### 4.1 Window manager con dos modos

El cambio conceptual: **en desktop son ventanas flotantes arrastrables; en móvil son
hojas (sheets) a pantalla completa con historial**. Mismo HTML, comportamiento distinto.

```js
// js/window-manager.js
const MOBILE_Q = window.matchMedia('(max-width: 900px)');
const isMobile = () => MOBILE_Q.matches;

// Altura real del viewport, a prueba de la barra de URL de iOS/Android
function viewportSize() {
  const vv = window.visualViewport;
  return { w: vv ? vv.width : innerWidth, h: vv ? vv.height : innerHeight };
}

export function openWindow(id) {
  const win = document.getElementById(id);
  if (!win) return;

  if (isMobile()) {
    win.classList.add('as-sheet');
    win.classList.remove('hidden');
    win.style.left = win.style.top = '';   // el CSS manda en modo sheet
    history.pushState({ win: id }, '', '#' + id);   // el botón atrás cierra
    taskbar.add(id);
    trapFocus(win);
    return;
  }

  // desktop: MEDIR primero, posicionar después  ← arregla BUG-1
  win.style.visibility = 'hidden';
  win.classList.remove('hidden');
  const r = win.getBoundingClientRect();      // medida REAL, no estimada
  const [x, y] = clampPos(...defaultPos(win), r.width, r.height);
  win.style.left = x + 'px';
  win.style.top  = y + 'px';
  win.style.visibility = '';
  bringToFront(win);
  taskbar.add(id);
}

function clampPos(x, y, w, h) {
  const { w: vw, h: vh } = viewportSize();   // ← arregla BUG-2
  return [
    Math.min(Math.max(8, x), Math.max(8, vw - w - 8)),
    Math.min(Math.max(56, y), Math.max(56, vh - h - 8))
  ];
}
```

- [ ] Implementar los dos modos.
- [ ] El drag (`makeDraggable`) **solo se registra si `!isMobile()`** → cierra BUG-6.
- [ ] Escuchar `MOBILE_Q.addEventListener('change', …)` y `visualViewport.resize` para
      re-acomodar las ventanas abiertas al rotar el teléfono.
- [ ] `popstate` cierra la sheet activa (botón atrás de Android). Esto es lo que hace que
      se sienta app nativa.

#### 4.2 CSS del modo sheet

```css
/* css/responsive.css */
@media (max-width: 900px) {
  .window.as-sheet {
    position: fixed;
    inset: auto 0 0 0;
    width: 100%;
    max-width: 100%;
    height: 88dvh;              /* dvh, NO vh ← BUG-2 */
    max-height: 88dvh;
    border-width: 3px 0 0 0;
    animation: sheetUp .22s steps(6) both;   /* steps() = movimiento 8-bit, no suave */
  }
  .window.as-sheet .window-content {
    overscroll-behavior: contain;            /* ← BUG-5 */
    -webkit-overflow-scrolling: touch;
    padding-bottom: max(1.5rem, env(safe-area-inset-bottom));
  }
  .window.as-sheet .btn-close { width: 44px; height: 44px; }  /* ← BUG-7 */
}
@keyframes sheetUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
@media (prefers-reduced-motion: reduce) { .window.as-sheet { animation: none; } }
```

- [ ] **Todos** los objetivos táctiles a mínimo 44×44 px en móvil: `.btn-close`,
      `.desktop-icon`, `.chip` clicable, `.critter-prev/next`, `.tb-copy`, `.tb-clock`.
      Verificar uno por uno con el inspector, no de memoria.

#### 4.3 Taskbar con switcher — cierra BUG-3

- [ ] La barra de arriba lista las ventanas abiertas como botones (como la barra de tareas
      de Windows 95, que es exactamente la estética que ya tiene el sitio). Tocar uno
      lo trae al frente; en móvil, cambia de sheet.
- [ ] En móvil, `#openAllBtn` **no abre 9 ventanas**. Cambia a "Ver índice" y abre una sola
      ventana con la lista de secciones. En desktop se queda como está.
- [ ] Scroll horizontal en la taskbar si hay muchas ventanas (`overflow-x: auto`,
      `scrollbar-width: none`).

#### 4.4 Tooltips táctiles — cierra BUG-4

```js
// js/tooltip.js — hover en desktop, tap en táctil
const canHover = matchMedia('(hover: hover)').matches;
el.addEventListener(canHover ? 'mouseenter' : 'click', show);
el.addEventListener('focus', show);        // teclado
el.addEventListener('blur', hide);
if (!canHover) document.addEventListener('click', e => {
  if (!e.target.closest('[data-tooltip]')) hide();
});
```

- [ ] Auditar **cada** `[data-tooltip]` del sitio: si el texto contiene información que no
      está en ningún otro lado, **moverla al contenido visible**. Un tooltip nunca debe ser
      el único lugar donde vive un dato.

#### 4.5 Accesibilidad de ventanas — cierra BUG-9

- [ ] `role="dialog"` + `aria-modal="false"` + `aria-labelledby` apuntando al `.title`.
- [ ] `Escape` cierra la ventana con foco.
- [ ] Al abrir, el foco va al botón de cerrar; al cerrar, regresa al ícono que la abrió.
- [ ] Estilos `:focus-visible` visibles y retro (borde `--gold` de 3px, sin `outline: none` a secas).
- [ ] Skip link al inicio del `<body>`: `<a class="skip" href="#escritorio">Saltar al escritorio</a>`.

#### 4.6 Equivalentes táctiles — cierra BUG-8

- [ ] Konami: además del teclado, 5 toques seguidos en `#logoBtn` en menos de 3 s.
- [ ] Cambio de crítter 3D: los botones ◀ ▶ ya existen — asegurar 44×44 px y buen contraste,
      y agregar swipe horizontal sobre el canvas.

**Criterio de aceptación de la FASE 4:** el checklist de QA de la §8 pasa **completo**
en iPhone SE (375px), Pixel 7 (412px), iPad (768px) y desktop (1440px).

---

### FASE 5 — Rendimiento en tiempo de ejecución

#### 5.1 three.js bajo demanda (ahorro inmediato: ~580 KB)

- [ ] Quitar el `<script src="…three.min.js">` del HTML.
- [ ] Cargarlo solo cuando se abre la ventana del visor 3D:

```js
// js/critters/viewer.js
let threePromise = null;
export function loadThree() {
  if (!threePromise) {
    threePromise = import('https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js');
  }
  return threePromise;
}

export async function initCritterViewer(canvas, container, name) {
  container.classList.add('loading');   // spinner pixel-art mientras baja
  const THREE = await loadThree();
  container.classList.remove('loading');
  /* …resto del setup… */
}
```

> **Ojo:** r128 es de 2021 y usa la API global `THREE.*`. Al pasar a módulos ES hay
> cambios: `outputEncoding` → `outputColorSpace`, `sRGBEncoding` → `SRGBColorSpace`,
> y los materiales `MeshStandardMaterial` responden distinto a las luces.
> **Migrar y probar el visor visualmente antes de dar por buena esta fase.**
> Si la migración se complica, la alternativa aceptable es seguir con r128 pero
> cargándolo con `import()` dinámico de un script clásico.

#### 5.2 Que el loop 3D se detenga

- [ ] `animate()` debe parar cuando no se está viendo:

```js
let rafId = null, running = false;
function start() { if (!running) { running = true; rafId = requestAnimationFrame(animate); } }
function stop()  { running = false; if (rafId) cancelAnimationFrame(rafId); rafId = null; }

new IntersectionObserver(([e]) => e.isIntersecting ? start() : stop()).observe(canvas);
document.addEventListener('visibilitychange', () => document.hidden ? stop() : start());
// y stop() también cuando se cierra la ventana que lo contiene
```

- [ ] `antialias: !isMobile()` — en pantallas de alta densidad no se nota y cuesta caro.
- [ ] `preserveDrawingBuffer: true` **solo** si la cámara glitch está activa. Por defecto `false`.
- [ ] `renderer.setPixelRatio(Math.min(devicePixelRatio, isMobile() ? 1.5 : 2))`.
- [ ] Al cerrar la ventana: `renderer.dispose()`, `geometry.dispose()`, `material.dispose()`.
      Hoy no se libera nada.

#### 5.3 Cámara glitch sin tocar píxeles a mano

El `getImageData` + doble `for` por frame se reemplaza por composición de canales con
`globalCompositeOperation`, que corre en GPU:

```js
// js/critters/glitch-cam.js — 6 operaciones de canvas por frame en vez de ~1M de ops de JS
function channelPass(ctx, src, color) {
  ctx.globalCompositeOperation = 'source-over';
  ctx.drawImage(src, 0, 0, ctx.canvas.width, ctx.canvas.height);
  ctx.globalCompositeOperation = 'multiply';   // aísla un canal
  ctx.fillStyle = color;
  ctx.fillRect(0, 0, ctx.canvas.width, ctx.canvas.height);
}

function render() {
  // rastro
  out.globalAlpha = DECAY;
  out.drawImage(feedback, 0, 0);
  out.globalAlpha = 1;

  channelPass(rCtx, src, '#f00');
  channelPass(gCtx, src, '#0f0');
  channelPass(bCtx, src, '#00f');

  out.globalCompositeOperation = 'lighter';    // recompone con desfase RGB
  out.drawImage(rCanvas,  SHIFT_R, 0);
  out.drawImage(gCanvas,        0, 0);
  out.drawImage(bCanvas,  SHIFT_B, 0);
  out.globalCompositeOperation = 'source-over';
}
```

- [ ] Limitar la cámara glitch a **30 fps** (no 60) y capar la resolución del canvas a 480 px de ancho.
- [ ] Desactivarla por completo bajo 640 px de ancho de pantalla.

#### 5.4 Animaciones CSS

- [ ] Envolver las secciones pesadas con `content-visibility: auto` +
      `contain-intrinsic-size` (evita que el navegador pinte lo que no se ve).
- [ ] Pausar animaciones fuera del viewport:
  ```js
  // js/perf.js
  const io = new IntersectionObserver(es => es.forEach(e =>
    e.target.classList.toggle('anim-paused', !e.isIntersecting)), { rootMargin: '100px' });
  document.querySelectorAll('[data-animated]').forEach(el => io.observe(el));
  ```
  ```css
  .anim-paused, .anim-paused * { animation-play-state: paused !important; }
  ```
- [ ] `will-change: transform` **solo** en lo que se mueve de verdad, y quitarlo al terminar.
      Ponerlo en todo es contraproducente: reserva memoria de GPU.
- [ ] Auditar los 51 `box-shadow`: los que tienen `blur` grande sobre elementos animados
      son de los repintados más caros que existen. En estética 8-bit las sombras deberían ser
      **duras** (`box-shadow: 4px 4px 0 #000`) — se ve **más** retro y cuesta casi nada.
- [ ] **Toggle "MODO RENDIMIENTO"** en la taskbar: apaga scanlines, glitch, bloom y el 3D.
      Guardar la preferencia en `localStorage`. Es una función de accesibilidad real y,
      en un portafolio, se lee como que el dev entiende de rendimiento.
- [ ] Respetar `prefers-reduced-motion` en **todo** lo nuevo (ya se respeta en varias partes).

**Criterio de aceptación:** JS ejecutado al cargar **< 20 KB**; TBT **< 200 ms**;
el visor 3D no consume CPU con la ventana cerrada (verificar en el panel Performance de DevTools).

---

### FASE 6 — SEO, metadatos y compartir (crítico para freelance)

- [ ] `<head>` completo:

```html
<meta name="description" content="Ronaldo Solano — desarrollador full-stack (PHP · JavaScript · MySQL) y estudiante de Ingeniería en Electrónica y Sistemas Inteligentes en CUCEI, UDG. Portafolio interactivo estilo consola retro de 64 bits.">
<link rel="canonical" href="https://ronaldou24.github.io/">
<meta name="theme-color" content="#1d2b53">

<meta property="og:type" content="website">
<meta property="og:url" content="https://ronaldou24.github.io/">
<meta property="og:title" content="Ronaldo Solano — Full-Stack Dev Cartridge">
<meta property="og:description" content="Portafolio interactivo estilo escritorio retro de 64 bits. Proyectos web, electrónica y modding de consolas.">
<meta property="og:image" content="https://ronaldou24.github.io/assets/og-cover.png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="es_MX">

<meta name="twitter:card" content="summary_large_image">

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">
```

- [ ] Favicon en pixel art (16×16 y 32×32, `image-rendering: pixelated`) — reusar `cartridge.svg`.
- [ ] JSON-LD de tipo `Person` con `name`, `jobTitle`, `alumniOf` (CUCEI · Universidad de Guadalajara),
      `knowsAbout`, `email`, `sameAs` (GitHub, Pinterest, Instagram, TikTok, Telegram).
      Esto es lo que hace que Google entienda quién es y lo muestre como persona, no como página suelta.
- [ ] `robots.txt` y `sitemap.xml`.
- [ ] Jerarquía de encabezados correcta: un solo `<h1>`, `<h2>` por ventana, sin brincos de nivel.
- [ ] `<title>` con la carrera: `Ronaldo Solano — Dev Full-Stack & Electrónica | Portafolio`.

**Verificar el preview real** pegando el link en el validador de Facebook/Open Graph y en
Telegram antes de dar por cerrada la fase.

---

### FASE 7 — Contenido nuevo (lo que Roni pidió: "más diseños e info práctica")

Todo el contenido nuevo se lee de `data/*.json` y se pinta con `js/render.js`.
Motivo: **Roni actualiza un JSON, no toca HTML.** Un portafolio que cuesta trabajo actualizar
se queda desactualizado.

#### 7.1 `servicios.exe` — la ventana que convierte visitas en clientes

Icono nuevo en el escritorio. `data/servicios.json`:

```json
{
  "intro": "Hago sitios y sistemas web a la medida, y reparo/modeo consolas retro.",
  "paquetes": [
    {
      "id": "basico",
      "nombre": "CARTUCHO BÁSICO",
      "para": "Negocios que necesitan presencia y que los encuentren",
      "incluye": ["Landing de una página", "Diseño responsive", "Formulario de contacto", "Publicación en GitHub Pages o hosting"],
      "entrega": "3–5 días",
      "precio_desde": "PENDIENTE_RONI"
    },
    {
      "id": "estandar",
      "nombre": "CARTUCHO ESTÁNDAR",
      "para": "Negocios con catálogo, servicios o galería",
      "incluye": ["Hasta 5 secciones", "Galería de imágenes", "Formulario funcional", "SEO básico + preview al compartir"],
      "entrega": "1–2 semanas",
      "precio_desde": "PENDIENTE_RONI"
    },
    {
      "id": "pro",
      "nombre": "CARTUCHO PRO",
      "para": "Sistemas con usuarios, roles y base de datos",
      "incluye": ["CRUD completo (PHP + MySQL)", "Login y roles", "Panel de administración", "Consultas preparadas contra inyección SQL", "Capacitación de uso"],
      "entrega": "3–4 semanas",
      "precio_desde": "PENDIENTE_RONI",
      "referencia": "projFlujoWindow"
    },
    {
      "id": "hardware",
      "nombre": "SERVICIO TÉCNICO RETRO",
      "para": "Consolas que ya no prenden o no leen juegos",
      "incluye": ["Limpieza de contactos", "Instalación de flash carts", "Modchips", "Diagnóstico y reporte"],
      "entrega": "Según diagnóstico",
      "precio_desde": "PENDIENTE_RONI"
    }
  ],
  "proceso": ["Brief (qué necesitas)", "Mockup y cotización", "Desarrollo con avances visibles", "Deploy", "15 días de soporte incluido"]
}
```

- [ ] **`PENDIENTE_RONI` es un marcador a propósito.** No inventar precios. Si al momento
      de ejecutar siguen ahí, renderizar "Cotización sin costo" y dejar un `console.warn`
      en desarrollo. Los precios los pone Roni.
- [ ] Cada paquete que tenga un proyecto real que lo demuestre enlaza a esa ventana
      (`referencia`). Un cliente que ve el trabajo hecho pregunta menos y confía más.
- [ ] CTA doble: `mailto:` con asunto prellenado
      (`?subject=Cotización%20—%20Cartucho%20Estándar`) **y** botón de Telegram.
- [ ] Diseño: cada paquete es una **caja de cartucho** (marco tipo N64, etiqueta arriba,
      "código de barras" en pixel art abajo). Que se vea como una repisa de juegos.

#### 7.2 `skills.exe` — barras RPG **con evidencia**

`data/skills.json` — cada skill declara en qué proyecto se usó. Sin evidencia, una barra
de nivel es decorativa; con evidencia, es un argumento de venta.

```json
{
  "categorias": [
    {
      "nombre": "BACKEND",
      "skills": [
        { "nombre": "PHP",    "nivel": 4, "desde": 2023, "proyectos": ["projDogfatherWindow", "projFlujoWindow", "projEsteticaWindow"] },
        { "nombre": "MySQL",  "nivel": 4, "desde": 2023, "proyectos": ["projFlujoWindow"] },
        { "nombre": "Python", "nivel": 3, "desde": 2023, "proyectos": [] }
      ]
    },
    {
      "nombre": "FRONTEND",
      "skills": [
        { "nombre": "JavaScript", "nivel": 4, "desde": 2023, "proyectos": ["projHollowheadsWindow"] },
        { "nombre": "HTML/CSS",   "nivel": 5, "desde": 2022, "proyectos": ["projHollowheadsWindow", "projEsteticaWindow"] }
      ]
    },
    {
      "nombre": "ELECTRÓNICA",
      "skills": [
        { "nombre": "Arduino / ESP32",        "nivel": 3, "desde": 2025, "proyectos": [] },
        { "nombre": "Soldadura y diagnóstico", "nivel": 3, "desde": 2024, "proyectos": [] },
        { "nombre": "Modding de consolas",     "nivel": 4, "desde": 2023, "proyectos": [] }
      ]
    }
  ]
}
```

- [ ] Barras de nivel en **pixel art**: 5 bloques cuadrados, sin degradados, sin bordes
      redondeados, rellenos con `--gold`. Nada de barras de progreso genéricas de Bootstrap.
- [ ] Animar el llenado con `steps(5)` cuando la ventana entra en viewport — el escalonado
      es lo que lo hace leerse como barra de videojuego y no como barra de carga moderna.
- [ ] Los proyectos enlazados abren la ventana correspondiente.
- [ ] **Sin `nivel: 5` inflados.** Un nivel honesto con evidencia vale más que uno inventado;
      cualquier cliente técnico lo nota en la primera pregunta.

#### 7.3 `lab.exe` — bitácora de electrónica

La ventana que conecta INES con el portafolio y lo separa de cualquier otro portafolio
de dev junior. `data/lab.json`:

```json
{
  "entradas": [
    {
      "fecha": "2026-08",
      "titulo": "TÍTULO_PENDIENTE",
      "estado": "en-curso",
      "componentes": ["ESP32", "protoboard", "sensor DHT11"],
      "descripcion": "PENDIENTE_RONI",
      "aprendizaje": "PENDIENTE_RONI",
      "img": null
    }
  ]
}
```

- [ ] Renderizar como **fichas de save file**: fecha estilo contador de partida,
      estado como etiqueta (`EN CURSO` / `COMPLETADO` / `ARCHIVADO`), lista de componentes
      como chips, y foto si existe.
- [ ] Si `data/lab.json` está vacío o todo es `PENDIENTE_RONI`, mostrar un estado vacío
      con estilo — *"NO DATA — inserta cartucho"* — nunca una ventana en blanco.
- [ ] Sembrar 2–3 entradas con lo que Roni ya hace (modding de consolas, flash carts,
      limpieza de contactos) marcadas como `COMPLETADO`, para que la ventana no nazca vacía.

#### 7.4 Retoques a lo que ya existe

- [ ] `misiones.lnk`: cada proyecto necesita **stack usado**, **problema que resolvía** y
      **rol de Roni**. Hoy varias tarjetas solo describen la tecnología. Un cliente
      no compra "PHP con includes"; compra "sistema de rutas con roles que reemplazó una libreta".
- [ ] `contacto.txt`: agregar disponibilidad ("abierto a proyectos freelance") y tiempo
      de respuesta. Botón de copiar correo al portapapeles con confirmación visual.
- [ ] `moodboard`: enlazar al Pinterest real (`mx.pinterest.com/ronaldosolano56`)
      con las referencias que de verdad usó para el sitio.
- [ ] `curriculum.pdf`: que el botón de descarga diga el peso del archivo
      (`Descargar PDF · 3.7 MB`) — y de paso, **comprimir ese PDF**, 3.7 MB es mucho para un CV.

---

### FASE 8 — Verificación (no es opcional)

#### 8.1 Matriz de dispositivos

Probar **cada** punto en **cada** ancho. En DevTools: Toggle device toolbar (Ctrl+Shift+M).

| Ancho | Dispositivo | Qué se prueba |
|---|---|---|
| 375px | iPhone SE | Modo sheet, taskbar, todos los botones alcanzables |
| 412px | Pixel 7 | Botón atrás de Android cierra la sheet |
| 768px | iPad vertical | Punto de quiebre entre sheet y ventana flotante |
| 1024px | iPad horizontal | Ventanas flotantes, drag funcional |
| 1440px | Desktop | Experiencia completa, 3D, easter eggs |

#### 8.2 Checklist funcional — **cada ventana, cada ancho**

- [ ] Abre desde su ícono
- [ ] El contenido es **legible completo** (nada cortado por abajo)
- [ ] Hace scroll interno sin arrastrar la página de fondo
- [ ] El botón X cierra y es fácil de tocar (44×44 mínimo)
- [ ] Todos los links internos abren la ventana correcta
- [ ] Todos los links externos abren en pestaña nueva con `rel="noopener noreferrer"`
- [ ] Todas las imágenes cargan (ninguna rota)
- [ ] `Escape` cierra (desktop) / botón atrás cierra (móvil)
- [ ] Se puede navegar con Tab y el foco se ve

Ventanas a verificar, una por una: `sobreMi`, `curriculum`, `status`, `inventario`,
`misiones`, `projDogfather`, `projHollowheads`, `projFlujo`, `projEstetica`, `moodboard`,
`refs`, `retro`, `coleccion`, `papusBlindaje` (3D), `highscore`, `secret`, `cheats`,
`certificados`, `contacto`, `copyright`, **`servicios` (nueva)**, **`skills` (nueva)**,
**`lab` (nueva)**.

#### 8.3 Links rotos

```bash
npx linkinator http://localhost:8000 --recurse --skip "linkedin|instagram|tiktok"
```

#### 8.4 Lighthouse — comparar contra el baseline de la Fase 0

```bash
npx lighthouse http://localhost:8000 --form-factor=mobile --output=html --output-path=./.perf/final-mobile.html --view
```

Ninguna meta de la §3 puede quedar sin cumplir. Si alguna no se alcanza,
**documentar por qué** en `.perf/NOTAS.md` en vez de dar la fase por cerrada.

#### 8.5 Revisión de código (pasada de `engineering:code-review`)

- [ ] **Seguridad:** ningún `innerHTML` con contenido que venga de fuera. `spawnPopup()` hoy
      usa `innerHTML` con un string interpolado — si algún día ese texto viene de un JSON
      o de la URL, es un XSS. Cambiar a `textContent` para el mensaje.
- [ ] Todos los `target="_blank"` con `rel="noopener noreferrer"`.
- [ ] **Fugas de memoria:** cada `addEventListener` en elementos dinámicos necesita su
      `removeEventListener`. Los popups de `spawnPopup()` se borran del DOM pero sus
      listeners de `document`/`window` (si los tuvieran) no.
- [ ] `lazyWindowInit` registra un `keydown` en `document` que **nunca se quita** — se acumula
      si se reinicializa.
- [ ] **Correctitud:** revisar que `openWindow` sea idempotente (abrir dos veces no debe
      duplicar estado ni listeners).
- [ ] Sin `console.log` en producción.
- [ ] Nombres de funciones y comentarios en español, consistentes con el estilo del repo.

---

## 6. SISTEMA DE DISEÑO — REGLAS INNEGOCIABLES

La estética es el producto. Estas reglas aplican a **todo** lo nuevo.

### Paleta (PICO-8, ya en `tokens.css` — no inventar colores nuevos)

```
--void   #0a0a12   fondo profundo
--navy   #1d2b53   superficie de ventanas
--cream  #fff1e8   texto principal
--gold   #ffec27   acento primario / barras / foco
--flame  #ff004d   alerta / destructivo
--sky    #29adff   links
--lime   #00e436   éxito / estado activo
--grape  #7e2553   acento secundario
```

### Reglas 8-bit

1. **Sin `border-radius` suave.** Máximo 2px, o 0. Las esquinas redondeadas de 18px que
   están en `tokens.css` (`--radius: 18px`) son de otra estética — no usarlas en el escritorio.
2. **Sombras duras, no difusas:** `box-shadow: 4px 4px 0 #000`, no `0 4px 24px rgba(...)`.
   Se ve más retro **y** rinde mucho mejor.
3. **Animaciones escalonadas:** `steps(N)` en lugar de `ease`. El movimiento suave es
   estética moderna; el escalonado es lo que lee como sprite.
4. **`image-rendering: pixelated`** en cualquier imagen que se escale.
5. **Tipografía:** `Press Start 2P` solo para títulos y etiquetas cortas — es ilegible en
   párrafos largos y arruina la accesibilidad. Cuerpo en `Barlow`, código en `JetBrains Mono`.
6. **Espaciado en múltiplos de 4px.** La rejilla es parte del look.
7. **Contraste mínimo 4.5:1** en texto. `--sky #29adff` sobre `--navy #1d2b53` queda al filo:
   verificar con el checador de contraste y subir el tono si no pasa. **Retro no es excusa
   para texto ilegible** — y un cliente que no puede leer tu propuesta no te contrata.

### Consistencia

- Cada ventana nueva usa la misma estructura: `.window > .title-bar.drag-handle + .window-content`.
- Cada ícono nuevo del escritorio sigue el patrón `NOMBRE.EXT` en mayúsculas.
- Los nombres de ventana en la barra de título van en MAYÚSCULAS con extensión falsa
  (`SERVICIOS.EXE`, `SKILLS.EXE`, `LAB.EXE`).

---

## 7. INFO PERSONAL — FUENTE DE VERDAD

**Ya aplicado en `index.html` y `README.md` el 15/08/2026.** No revertir, no contradecir.
Si aparece cualquier resto de la info vieja (IPN / ESIME / 18 años / 2° semestre), corregirlo.

| Campo | Valor correcto |
|---|---|
| Nombre | Ronaldo Gael Solano Gutiérrez |
| Edad | **19 años** |
| Origen | Puerto Vallarta, Jalisco |
| Universidad | **CUCEI — Centro Universitario de Ciencias Exactas e Ingenierías, Universidad de Guadalajara (UDG)** |
| Carrera | **Ingeniería en Electrónica y Sistemas Inteligentes (INES)** |
| Semestre | **1er semestre** (calendario 2026-B) |
| Bachillerato | Técnico en Programación · CBTIS68 · Promedio 8.8 |
| Experiencia | 3 meses de apoyo administrativo en oficina (Guadalajara) · +2 años de curso en línea full-stack certificado por W3Schools |
| Correo | ronaldosolano56@gmail.com |
| GitHub | github.com/Ronaldou24 |
| Pinterest | mx.pinterest.com/ronaldosolano56 |

**Nombre oficial de la carrera** (verificado en el sitio de CUCEI):
"Ingeniería en Electrónica y Sistemas Inteligentes", siglas **INES**.
Escribirlo así, completo, la primera vez que aparezca en cada sección.

**Ángulo narrativo:** INES no es un dato de relleno, es **la historia**. Roni no es
"otro dev que hace webs": es alguien que programa full-stack **y** entiende el hardware
por debajo — modea consolas, suelda, diagnostica. Esa combinación es rara y es
exactamente lo que hay que vender. El sitio debe dejarla clarísima en el hero, en
`sobre-mi.txt`, en `skills.exe` y en `lab.exe`.

---

## 8. REGLAS ANTI-REGRESIÓN — QUÉ **NO** TOCAR

- ❌ **No** cambiar la estética retro/8-bit por algo "más limpio" o "más moderno".
- ❌ **No** meter React, Vue, Tailwind, ni ningún framework. Es HTML/CSS/JS vanilla
  sobre GitHub Pages y así se queda. Sin build step, sin `node_modules` en producción.
- ❌ **No** borrar los easter eggs (Konami, BSOD del `not-a-bug.docx`, ícono fantasma,
  logro de las 12 ventanas). Son personalidad, y la personalidad es lo que hace que
  alguien se acuerde del portafolio.
- ❌ **No** quitar el visor 3D de crítters. Se **optimiza**, no se elimina.
- ❌ **No** simplificar el hero glitch. Se hace más barato, no más aburrido.
- ❌ **No** cambiar la paleta PICO-8.
- ❌ **No** inventar datos: ni precios, ni niveles de skill, ni proyectos, ni fechas.
  Lo que no se sepa se marca `PENDIENTE_RONI`.
- ❌ **No** commitear a `main` sin que la Fase 8 pase completa.

---

## 9. ORDEN DE COMMITS SUGERIDO

Un commit por fase, en este orden, cada uno verificado antes del siguiente:

```
1.  chore: baseline de rendimiento y rama de trabajo
2.  chore: eliminar assets sin uso y archivos huérfanos (-35 MB)
3.  perf: convertir imágenes a webp y agregar dimensiones explícitas
4.  refactor: separar css y js del monolito index.html
5.  fix(mobile): window manager con modo sheet, viewport real y taskbar switcher
6.  fix(a11y): objetivos táctiles 44px, tooltips táctiles, foco y Escape
7.  perf: carga diferida de three.js, pausar loops fuera de viewport
8.  perf: cámara glitch sin getImageData, animaciones pausables
9.  feat(seo): metadatos, open graph, favicon, json-ld, sitemap
10. feat: ventana servicios.exe con paquetes freelance
11. feat: ventana skills.exe con niveles y evidencia por proyecto
12. feat: ventana lab.exe, bitácora de electrónica
13. content: actualizar proyectos con stack, problema y rol
14. docs: actualizar README con nueva estructura
```

---

## 10. AL TERMINAR — REPORTE

Generar `.perf/REPORTE.md` con:

- Tabla antes/después: peso total, LCP, CLS, TBT, puntajes de Lighthouse (móvil y desktop)
- Capturas del sitio en los 5 anchos de la matriz §8.1
- Lista de bugs de la §2, cada uno marcado como cerrado con la línea de código que lo arregla
- Lo que quedó pendiente y por qué
- Los `PENDIENTE_RONI` que faltan por llenar

Ese reporte no es burocracia: es **material de portafolio**. "Bajé mi sitio de 2.2 MB a
480 KB y de LCP 7 s a 1.8 s, con evidencia" es una de las mejores cosas que un dev junior
puede poner en una entrevista o mandarle a un cliente.

---

> **Nota sobre el alcance:** este spec no incluye construir un servidor MCP
> (`/mcp-builder`) — no hace falta para optimizar un sitio estático. Si más adelante
> Roni quiere un MCP (por ejemplo, para publicar automáticamente sus proyectos nuevos
> al portafolio desde Claude), eso va en un spec aparte.
