// Fase 5.4: separado de js/window-manager.js -- ahi vivia desde la Fase 5.3 con una
// desviacion consciente ("pegado al ciclo de vida de la ventana, no del visor"). Sigue
// siendo asi (initGlitchCam se llama desde window-manager.js cada vez que el visor 3D
// arranca), pero ahora el archivo se pide con import() diferido en vez de parsearse
// siempre en el arranque de la pagina -- la mayoria de las visitas nunca abre
// papus.blindaje, y mucho menos prende la camara.
//
// Antes (pre-Fase 5.3): getImageData() + doble for por pixel en cada frame (~250,000
// operaciones de JS puro en el hilo principal, a 60 fps). Ahora: composicion de
// canales con globalCompositeOperation, que corre en GPU -- unas pocas llamadas de
// canvas por frame en vez de un millon de operaciones de JS.
import { isPerfMode } from '../perf.js';

// rafId y el listener del boton viven a nivel de modulo (no dentro de initGlitchCam)
// porque initGlitchCam se vuelve a llamar cada vez que el visor 3D se re-crea -- sin
// esto se acumularian listeners duplicados en el boton cada vez que el usuario cierra
// y reabre la ventana. Un modulo ES ya es un singleton (import() repetido reusa la
// misma instancia), asi que este estado persiste igual que antes.
var glitchRafId = null;
var glitchClickHandler = null;

export function stopGlitchCam() {
  if (glitchRafId) cancelAnimationFrame(glitchRafId);
  glitchRafId = null;
  var glitchOut = document.getElementById('papusGlitchCanvas');
  var glitchBtn = document.getElementById('papusGlitchToggle');
  if (glitchOut) glitchOut.classList.remove('active');
  if (glitchBtn) glitchBtn.setAttribute('aria-pressed', 'false');
}

export function initGlitchCam(sourceCanvas) {
  var glitchOut = document.getElementById('papusGlitchCanvas');
  var glitchBtn = document.getElementById('papusGlitchToggle');
  if (!glitchOut || !glitchBtn) return;

  if (glitchClickHandler) glitchBtn.removeEventListener('click', glitchClickHandler);
  stopGlitchCam();

  // desactivada del todo con "MODO RENDIMIENTO", prefers-reduced-motion o en
  // pantallas chicas (<640px): es puramente decorativa y cara, no vale la pena
  if (isPerfMode() ||
      window.matchMedia('(prefers-reduced-motion: reduce)').matches ||
      window.matchMedia('(max-width: 640px)').matches) {
    glitchBtn.style.display = 'none';
    return;
  }

  var MAX_W = 480;         // resolucion capada del canvas de la camara glitch
  var FPS = 30;            // no hace falta a 60fps, es un efecto de rastro
  var FRAME_MS = 1000 / FPS;
  var DECAY = 0.85, SHIFT_R = 3, SHIFT_B = -3;

  var octx = glitchOut.getContext('2d');
  var feedback = document.createElement('canvas');
  var fctx = feedback.getContext('2d');
  var rCanvas = document.createElement('canvas');
  var gCanvas = document.createElement('canvas');
  var bCanvas = document.createElement('canvas');
  var rCtx = rCanvas.getContext('2d');
  var gCtx = gCanvas.getContext('2d');
  var bCtx = bCanvas.getContext('2d');

  var lastFrameTime = 0;

  function channelPass(ctx, color) {
    var w = ctx.canvas.width, h = ctx.canvas.height;
    ctx.globalCompositeOperation = 'source-over';
    ctx.drawImage(sourceCanvas, 0, 0, w, h);
    ctx.globalCompositeOperation = 'multiply';
    ctx.fillStyle = color;
    ctx.fillRect(0, 0, w, h);
    ctx.globalCompositeOperation = 'source-over';
  }

  function glitchRender(now) {
    glitchRafId = requestAnimationFrame(glitchRender);
    if (now - lastFrameTime < FRAME_MS) return; // cap a 30fps
    lastFrameTime = now;

    var w = glitchOut.width, h = glitchOut.height;
    if (!w || !h) return;

    octx.globalAlpha = DECAY;
    octx.drawImage(feedback, 0, 0, w, h);
    octx.globalAlpha = 1;

    channelPass(rCtx, '#f00');
    channelPass(gCtx, '#0f0');
    channelPass(bCtx, '#00f');

    octx.globalCompositeOperation = 'lighter';
    octx.drawImage(rCanvas, SHIFT_R, 0);
    octx.drawImage(gCanvas, 0, 0);
    octx.drawImage(bCanvas, SHIFT_B, 0);
    octx.globalCompositeOperation = 'source-over';

    fctx.clearRect(0, 0, w, h);
    fctx.drawImage(glitchOut, 0, 0, w, h);
  }

  glitchClickHandler = function () {
    var on = glitchBtn.getAttribute('aria-pressed') === 'true';
    if (on) {
      stopGlitchCam();
    } else {
      var ratio = Math.min(1, MAX_W / (sourceCanvas.width || MAX_W));
      var w = Math.round((sourceCanvas.width || MAX_W) * ratio);
      var h = Math.round((sourceCanvas.height || MAX_W) * ratio);
      [glitchOut, feedback, rCanvas, gCanvas, bCanvas].forEach(function (cv) {
        cv.width = w; cv.height = h;
      });
      fctx.clearRect(0, 0, w, h);
      glitchOut.classList.add('active');
      glitchBtn.setAttribute('aria-pressed', 'true');
      lastFrameTime = 0;
      glitchRafId = requestAnimationFrame(glitchRender);
    }
  };
  glitchBtn.addEventListener('click', glitchClickHandler);
}
