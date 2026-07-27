# RONALDOU24 — Full-Stack Dev Cartridge

![GitHub Pages](https://img.shields.io/badge/GitHub%20Pages-live-brightgreen?logo=github)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?logo=javascript&logoColor=black)
![Status](https://img.shields.io/badge/status-en%20construcción-orange)

Portafolio personal de **Ronaldo Solano**, construido como un "cartucho" interactivo: un escritorio retro estilo consola de 64 bits donde cada icono abre una ventana con información sobre mí, mis proyectos y mis habilidades.

**Demo en vivo:** [ronaldou24.github.io](https://ronaldou24.github.io/)

---

## Sobre el proyecto

En lugar de un portafolio tradicional de scroll infinito, este sitio simula un escritorio de sistema operativo retro. Los visitantes exploran haciendo clic en los iconos del escritorio, cada uno abre una "ventana" arrastrable con contenido: currículum, stack técnico, proyectos, certificados y contacto.

La estética combina:

- **Pixel art / 8-bit** — tipografías `Press Start 2P` y `JetBrains Mono`, paleta inspirada en PICO-8.
- **Cajas de N64 / PS1** — cubo 3D animado en el hero, tarjetas con marco tipo cartucho.
- **Weirdcore / dreamcore** — fondos con estrellas, glitch sutil y colores saturados.

## Qué vas a encontrar en el escritorio

| Icono | Contenido |
|---|---|
| `sobre-mi.txt` | Quién soy, qué estudio y por qué me gusta la electrónica retro |
| `curriculum.pdf` | Datos de contacto, educación, experiencia y descarga del CV |
| `status.exe` | Ficha de personaje estilo RPG (clase, nivel, habilidad especial) |
| `inventario.zip` | Stack técnico agrupado por backend, frontend, lenguajes, electrónica y herramientas |
| `misiones.lnk` | Galería de proyectos web y scripts en Python |
| `certs` | Certificados (JavaScript, PHP, SQL) en PDF y PNG |
| `contacto.txt` | Email, GitHub, Pinterest y redes sociales |
| `highscore.exe` | Estadísticas de GitHub en vivo |
| `moodboard` / `refs.friq` / `retro-cartr` / `colección` | Referencias visuales, gustos y modding de hardware |
| `not-a-bug.docx` | Un easter egg |

## Stack técnico

- **Frontend:** HTML5, CSS3 (animaciones, grid/flexbox), JavaScript vanilla (sin frameworks ni build step)
- **Backend / prácticas incluidas en el repo:** PHP, MySQL, Python
- **Tipografías:** Google Fonts (`Press Start 2P`, `Michroma`, `Barlow`, `JetBrains Mono`)
- **Hosting:** GitHub Pages

## Estructura del repositorio

```
.
├── index.html              # Punto de entrada: escritorio, ventanas y lógica de UI
├── styles.css               # Estilos globales del sitio
├── script.js                 # Interactividad (ventanas, reloj, easter eggs)
├── assets/                  # Imágenes usadas en el hero y ventanas
├── certificates/             # Certificados descargables (PDF/PNG)
├── htdocs/                   # Proyectos web y scripts de práctica mostrados en "misiones.lnk"
└── *.pdf / *.rar             # Currículum y respaldo de proyectos
```

## Correr el proyecto en local

Es un sitio estático, no requiere instalación de dependencias.

```bash
git clone https://github.com/Ronaldou24/Ronaldou24.github.io.git
cd Ronaldou24.github.io
```

Después abre `index.html` en tu navegador, o levanta un servidor local rápido:

```bash
python -m http.server 8000
```

Y visita `http://localhost:8000`.

## Sobre mí

Estudiante de **Ingeniería en Comunicaciones y Electrónica** en ESIME Zacatenco (IPN). Desarrollo full-stack con PHP, JavaScript y MySQL, y en mis ratos libres reparo y modeo consolas retro (flash carts, limpieza de contactos, chips).

## Contacto

- **Email:** [ronaldosolano56@gmail.com](mailto:ronaldosolano56@gmail.com)
- **GitHub:** [@Ronaldou24](https://github.com/Ronaldou24)
- **Pinterest:** [ronaldosolano56](https://mx.pinterest.com/ronaldosolano56/)

---

Diseño y código por Ronaldo Solano · © 2026
