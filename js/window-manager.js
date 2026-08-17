// Extraido del monolito original (Fase 3) y ampliado en la Fase 4 con los fixes de
// movil de OPTIMIZACION.md §2. Window manager + taskbar se mantienen juntos a
// proposito -- comparten estado interno (zTop, openedWindows, la funcion
// openWindow/spawnPopup/makeDraggable). La camara glitch (js/critters/glitch-cam.js),
// los easter eggs (js/easter-eggs.js) y el tooltip global (js/tooltip.js) se
// separaron en la Fase 5.4 y se piden con import() diferido -- ver startPapusViewer()
// y loadDeferred() mas abajo -- para que ese codigo no cuente en el JS ejecutado al
// arrancar la pagina.
import { initCritterViewer } from './critters/viewer.js';
import { isPerfMode, onPerfModeChange } from './perf.js';

export function initWindowManager() {
  'use strict';

  // Punto de quiebre unico entre modo ventana-flotante y modo sheet. Debe coincidir
  // con el "@media (max-width: 900px)" de css/responsive.css.
  var MOBILE_Q = window.matchMedia('(max-width: 900px)');
  function isMobile() { return MOBILE_Q.matches; }

  function updateClock() {
    var d = new Date();
    var h = String(d.getHours()).padStart(2, '0');
    var m = String(d.getMinutes()).padStart(2, '0');
    document.getElementById('clockText').textContent = h + ':' + m;
  }
  updateClock();
  setInterval(updateClock, 30000);

  var zTop = 100;

  // BUG-2: window.innerWidth/innerHeight mienten en movil cuando la barra de URL
  // se colapsa/expande. window.visualViewport reporta el tamano real visible.
  function viewportSize() {
    var vv = window.visualViewport;
    return { w: vv ? vv.width : window.innerWidth, h: vv ? vv.height : window.innerHeight };
  }

  function clampPos(x, y, w, h) {
    var vp = viewportSize();
    var maxX = vp.w - w - 8;
    var maxY = vp.h - h - 8;
    return [
      Math.min(Math.max(8, x), Math.max(8, maxX)),
      Math.min(Math.max(56, y), Math.max(56, maxY))
    ];
  }

  function bringToFront(win) {
    zTop++;
    win.style.zIndex = zTop;
  }

  // Fase 5: dispose() de cada ventana lazy, para llamar al cerrarla (ver closeWindow).
  var windowCloseHandlers = {};

  // Fase 5.1/5.2: el visor 3D de papusBlindajeWindow se crea la primera vez que se
  // abre esa ventana y se DESTRUYE (dispose) cada vez que se cierra -- asi que a
  // diferencia del resto de lazyWindowInit (que corre una sola vez en toda la vida
  // de la pagina), este necesita poder re-crearse cada vez que la ventana se vuelve
  // a abrir. Para eso: los listeners de los controles (botones ◀▶, teclado, swipe)
  // se registran UNA sola vez aqui abajo, por fuera del ciclo de vida del visor, y
  // llaman siempre a traves de papusState.api (que se reasigna en cada apertura).
  var papusState = { api: null, idx: 0 };
  var papusNames = ['gatito', 'momo', 'tvbot'];

  function papusShow(i) {
    if (!papusState.api) return;
    papusState.idx = (i + papusNames.length) % papusNames.length;
    papusState.api.setCharacter(papusNames[papusState.idx]);
    var nameEl = document.getElementById('papusCritterName');
    if (nameEl) nameEl.textContent = papusNames[papusState.idx];
  }

  // Fase 5.4: con "MODO RENDIMIENTO" activo, el visor 3D ni siquiera pide three.js --
  // se pinta un aviso estatico con un boton para forzarlo si el usuario de verdad lo
  // quiere ver. startPapusViewer() queda aparte para poder llamarla tanto desde el
  // flujo normal como desde ese boton de "activar de todos modos".
  //
  // glitchCam guarda la referencia al modulo js/critters/glitch-cam.js una vez que
  // se pide con import() diferido (ver mas abajo) -- null si el visor nunca llego a
  // arrancar, asi que stopGlitchCamIfLoaded() no revienta si se cierra la ventana
  // antes de que ese import termine.
  var glitchCam = null;
  function stopGlitchCamIfLoaded() {
    if (glitchCam) glitchCam.stopGlitchCam();
  }

  function startPapusViewer(c, cont) {
    // Fase 5.1: three.js (~580 KB) se pide bajo demanda desde initCritterViewer,
    // recien ahora que se abrio esta ventana. Mientras carga, se ve el spinner
    // pixel-art de .cat-viewer-box.loading (ver css/components.css).
    initCritterViewer(c, cont, papusNames[papusState.idx]).then(function (api) {
      if (!api) return;
      papusState.api = api;
      windowCloseHandlers.papusBlindajeWindow = function () {
        stopGlitchCamIfLoaded();
        api.dispose();
        papusState.api = null;
      };
      // Fase 5.4: la camara glitch (js/critters/glitch-cam.js) tambien se pide con
      // import() diferido -- se necesita en cuanto el visor 3D arranca (no solo
      // cuando se le da clic al boton de camara), pero eso ya es bastante mas tarde
      // que el arranque de la pagina.
      import('./critters/glitch-cam.js').then(function (m) {
        glitchCam = m;
        m.initGlitchCam(c);
      });
    });
  }

  function showPerfFallback(c, cont) {
    var switchRow = document.querySelector('#papusBlindajeWindow .critter-switch');
    if (switchRow) switchRow.style.display = 'none';
    var msg = document.createElement('div');
    msg.className = 'perf-fallback';
    msg.innerHTML = '<p>Visor 3D desactivado en <strong>MODO RENDIMIENTO</strong>.</p>' +
      '<button type="button" class="btn" id="papusForce3D">Activar de todos modos</button>';
    cont.appendChild(msg);
    msg.querySelector('#papusForce3D').addEventListener('click', function () {
      msg.remove();
      if (switchRow) switchRow.style.display = '';
      startPapusViewer(c, cont);
    });
  }

  var lazyWindowInit = {
    // se llama en CADA apertura de la ventana (ver openWindow) -- por eso es
    // idempotente: si el visor ya esta vivo (no se cerro desde la ultima vez), no
    // hace nada.
    papusBlindajeWindow: function () {
      if (papusState.api) return;
      var c = document.getElementById('papusCatCanvas');
      var cont = document.getElementById('papusCatContainer');
      if (!c || !cont) return;
      if (isPerfMode()) { showPerfFallback(c, cont); return; }
      startPapusViewer(c, cont);
    }
  };

  // si el usuario prende "MODO RENDIMIENTO" con el visor ya corriendo, se apaga en
  // el acto -- no hace falta cerrar y reabrir la ventana para que surta efecto.
  onPerfModeChange(function (active) {
    if (!active || !papusState.api) return;
    var handler = windowCloseHandlers.papusBlindajeWindow;
    if (handler) { handler(); windowCloseHandlers.papusBlindajeWindow = null; }
    var win = document.getElementById('papusBlindajeWindow');
    var cont = document.getElementById('papusCatContainer');
    var c = document.getElementById('papusCatCanvas');
    if (win && cont && c && !win.classList.contains('hidden')) showPerfFallback(c, cont);
  });

  var papusPrevBtn = document.getElementById('papusCritterPrev');
  var papusNextBtn = document.getElementById('papusCritterNext');
  if (papusPrevBtn) papusPrevBtn.addEventListener('click', function () { papusShow(papusState.idx - 1); });
  if (papusNextBtn) papusNextBtn.addEventListener('click', function () { papusShow(papusState.idx + 1); });

  // flechas del teclado tambien cambian de personaje, mientras la ventana este abierta
  document.addEventListener('keydown', function (e) {
    var win = document.getElementById('papusBlindajeWindow');
    if (!win || win.classList.contains('hidden')) return;
    if (e.key === 'ArrowLeft') { e.preventDefault(); papusShow(papusState.idx - 1); }
    else if (e.key === 'ArrowRight') { e.preventDefault(); papusShow(papusState.idx + 1); }
  });

  // BUG-8: equivalente tactil de las flechas -- swipe horizontal sobre el canvas
  var papusCanvasEl = document.getElementById('papusCatCanvas');
  if (papusCanvasEl) {
    var papusTouchStartX = null;
    papusCanvasEl.addEventListener('touchstart', function (e) {
      if (e.touches.length === 1) papusTouchStartX = e.touches[0].clientX;
    }, { passive: true });
    papusCanvasEl.addEventListener('touchend', function (e) {
      if (papusTouchStartX === null) return;
      var endX = (e.changedTouches && e.changedTouches[0]) ? e.changedTouches[0].clientX : papusTouchStartX;
      var dx = endX - papusTouchStartX;
      papusTouchStartX = null;
      if (Math.abs(dx) < 40) return; // swipe corto: probablemente fue un drag de rotacion
      if (dx < 0) papusShow(papusState.idx + 1); else papusShow(papusState.idx - 1);
    }, { passive: true });
  }

  var openedWindows = {};
  var achievementUnlocked = false;
  var ACHIEVEMENT_THRESHOLD = 12;

  // ---------- BUG-9: accesibilidad de dialogos ----------
  var lastFocused = null;
  document.querySelectorAll('.window').forEach(function (win, i) {
    win.setAttribute('role', 'dialog');
    win.setAttribute('aria-modal', 'false');
    var titleEl = win.querySelector('.title-bar .title');
    if (titleEl) {
      if (!titleEl.id) titleEl.id = 'winTitle' + i;
      win.setAttribute('aria-labelledby', titleEl.id);
    }
  });

  function closeWindow(win) {
    win.classList.add('hidden');
    win.classList.remove('as-sheet');
    removeFromTaskbar(win.id);
    // Fase 5.2: si esta ventana tenia un visor 3D corriendo, se libera todo
    // (loop, listeners, geometrias, materiales, renderer) al cerrarla.
    if (windowCloseHandlers[win.id]) windowCloseHandlers[win.id]();
    if (lastFocused && document.contains(lastFocused)) lastFocused.focus();
  }

  // ---------- taskbar: switcher de ventanas abiertas (BUG-3) ----------
  var tbWindows = document.getElementById('tbWindows');
  var activeSheetId = null;

  function windowTitle(win) {
    var t = win.querySelector('.title-bar .title');
    return t ? t.textContent.trim() : win.id;
  }

  function addToTaskbar(win) {
    if (!tbWindows || document.getElementById('tbw_' + win.id)) return;
    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'tb-win-btn';
    btn.id = 'tbw_' + win.id;
    btn.textContent = windowTitle(win);
    btn.addEventListener('click', function () { focusWindow(win.id); });
    tbWindows.appendChild(btn);
    markActive(win.id);
  }

  function removeFromTaskbar(id) {
    var btn = document.getElementById('tbw_' + id);
    if (btn) btn.remove();
  }

  function markActive(id) {
    if (!tbWindows) return;
    tbWindows.querySelectorAll('.tb-win-btn').forEach(function (b) {
      b.classList.toggle('active', b.id === 'tbw_' + id);
    });
  }

  function focusWindow(id) {
    var win = document.getElementById(id);
    if (!win) return;
    if (isMobile()) {
      // en modo sheet solo una ventana esta "activa" a la vez
      document.querySelectorAll('.window.as-sheet').forEach(function (w) {
        w.classList.toggle('hidden', w.id !== id);
      });
      activeSheetId = id;
      markActive(id);
    } else {
      win.classList.remove('hidden');
      bringToFront(win);
    }
    var closeBtn = win.querySelector('[data-close]');
    if (closeBtn) closeBtn.focus();
  }

  function openWindow(id) {
    var win = document.getElementById(id);
    if (!win) return;
    lastFocused = document.activeElement;

    if (win.classList.contains('hidden')) {
      if (isMobile()) {
        // BUG-1/2/3: modo sheet -- el CSS define la posicion (fixed, bottom), no
        // hace falta adivinar tamano/posicion como hacia estimateWindowSize.
        win.classList.add('as-sheet');
        win.style.left = win.style.top = '';
        document.querySelectorAll('.window.as-sheet').forEach(function (w) {
          if (w !== win) w.classList.add('hidden');
        });
        try { history.pushState({ win: id }, '', '#' + id); } catch (_) { /* noop */ }
        activeSheetId = id;
      } else {
        // BUG-1: medir el tamano REAL con getBoundingClientRect en vez de adivinar
        var pos = (win.dataset.defaultPos || '60,110').split(',').map(Number);
        win.style.visibility = 'hidden';
        win.classList.remove('hidden');
        var r = win.getBoundingClientRect();
        var clamped = clampPos(pos[0], pos[1], r.width, r.height);
        win.style.left = clamped[0] + 'px';
        win.style.top = clamped[1] + 'px';
        win.style.visibility = '';
      }

      // OJO: se llama en CADA transicion oculta->visible, no solo la primera vez --
      // papusBlindajeWindow necesita poder re-crear su visor 3D tras un dispose() al
      // cerrar (Fase 5.2). Las funciones registradas aqui deben ser idempotentes.
      if (lazyWindowInit[id]) lazyWindowInit[id]();

      openedWindows[id] = true;
      if (!achievementUnlocked && Object.keys(openedWindows).length >= ACHIEVEMENT_THRESHOLD) {
        achievementUnlocked = true;
        setTimeout(function () {
          spawnPopup('🏆 ¡Explorador de cartucho completo! Ya viste casi todo el escritorio — gracias por curiosear tanto.', 40, Math.max(80, viewportSize().h - 200));
        }, 500);
      }
    }
    win.classList.remove('hidden');
    if (!isMobile()) bringToFront(win);
    addToTaskbar(win);
    markActive(id);

    var closeBtn = win.querySelector('[data-close]');
    if (closeBtn) closeBtn.focus();
  }

  // el boton atras de Android/el navegador cierra la sheet activa
  window.addEventListener('popstate', function () {
    if (isMobile() && activeSheetId) {
      var win = document.getElementById(activeSheetId);
      if (win) closeWindow(win);
      activeSheetId = null;
    }
  });

  // Escape cierra la ventana con foco (BUG-9)
  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    var openWin = document.activeElement && document.activeElement.closest('.window:not(.hidden)');
    if (!openWin) {
      // sin foco especifico: cierra la sheet activa en movil, o la de mayor z-index en desktop
      if (isMobile() && activeSheetId) openWin = document.getElementById(activeSheetId);
      else {
        var open = Array.prototype.slice.call(document.querySelectorAll('.window:not(.hidden)'));
        openWin = open.sort(function (a, b) { return (b.style.zIndex || 0) - (a.style.zIndex || 0); })[0];
      }
    }
    if (openWin) closeWindow(openWin);
  });

  // re-acomodar ventanas abiertas al cambiar de modo (rotar el telefono, redimensionar)
  MOBILE_Q.addEventListener('change', function () {
    document.querySelectorAll('.window:not(.hidden)').forEach(function (win) {
      if (isMobile()) {
        win.classList.add('as-sheet');
        win.style.left = win.style.top = '';
      } else {
        win.classList.remove('as-sheet');
        bringToFront(win);
      }
    });
  });

  function makeDraggable(win) {
    var handle = win.querySelector('.drag-handle');
    if (!handle) return;
    var startX = 0, startY = 0, activeId = null;
    handle.addEventListener('pointerdown', function (e) {
      if (isMobile()) return; // BUG-6: en movil no se arrastra, el titulo no secuestra el scroll
      if (e.target.closest('[data-close]')) return;
      activeId = e.pointerId;
      startX = e.clientX;
      startY = e.clientY;
      handle.setPointerCapture(e.pointerId);
      bringToFront(win);
    });
    handle.addEventListener('pointermove', function (e) {
      if (e.pointerId !== activeId) return;
      var dx = e.clientX - startX, dy = e.clientY - startY;
      startX = e.clientX;
      startY = e.clientY;
      var clamped = clampPos(win.offsetLeft + dx, win.offsetTop + dy, win.offsetWidth, win.offsetHeight);
      win.style.left = clamped[0] + 'px';
      win.style.top = clamped[1] + 'px';
    });
    function end(e) {
      if (e.pointerId !== activeId) return;
      if (handle.hasPointerCapture(e.pointerId)) handle.releasePointerCapture(e.pointerId);
      activeId = null;
    }
    handle.addEventListener('pointerup', end);
    handle.addEventListener('pointercancel', end);
  }

  document.querySelectorAll('.window').forEach(function (win) {
    win.addEventListener('pointerdown', function () { if (!isMobile()) bringToFront(win); });
    var closeBtn = win.querySelector('[data-close]');
    if (closeBtn) {
      closeBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        closeWindow(win);
      });
    }
    makeDraggable(win);
  });

  document.querySelectorAll('.desktop-icon[data-window]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      openWindow(btn.getAttribute('data-window'));
    });
  });

  document.querySelectorAll('.fighter-card[data-window]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      openWindow(btn.getAttribute('data-window'));
    });
  });

  document.getElementById('copyBtn').addEventListener('click', function () {
    openWindow('copyrightWindow');
  });

  var OPEN_ALL_IDS = ['sobreMiWindow', 'statusWindow', 'inventarioWindow', 'misionesWindow', 'contactoWindow', 'moodboardWindow', 'refsWindow', 'retroWindow', 'coleccionWindow'];
  var OPEN_ALL_LABELS = {
    sobreMiWindow: 'sobre-mi.txt', statusWindow: 'status.exe', inventarioWindow: 'inventario.zip',
    misionesWindow: 'misiones.lnk', contactoWindow: 'contacto.txt', moodboardWindow: 'moodboard',
    refsWindow: 'refs.friq', retroWindow: 'retro-cartr', coleccionWindow: 'colección'
  };

  function openIndexWindow() {
    // BUG-3: en movil #openAllBtn no apila 9 sheets encima -- abre un indice
    var id = 'indexPopup';
    var existing = document.getElementById(id);
    if (existing) { openWindow(id); return; }

    var p = document.createElement('div');
    p.className = 'window cute hidden';
    p.id = id;
    var listHtml = OPEN_ALL_IDS.map(function (winId) {
      return '<button type="button" class="tb-win-btn index-item" data-window="' + winId + '">' +
        (OPEN_ALL_LABELS[winId] || winId) + '</button>';
    }).join('');
    p.innerHTML =
      '<header class="title-bar drag-handle"><h2 class="title">ÍNDICE.LNK</h2>' +
      '<button class="btn-close" data-close type="button">X</button></header>' +
      '<div class="window-content"><p style="margin-top:0">Ver todo de un tirón satura la pantalla chica — elige a dónde ir:</p>' +
      '<div class="index-list">' + listHtml + '</div></div>';
    document.body.appendChild(p);
    p.setAttribute('role', 'dialog');
    p.setAttribute('aria-modal', 'false');
    var titleEl = p.querySelector('.title-bar .title');
    titleEl.id = 'winTitle_' + id;
    p.setAttribute('aria-labelledby', titleEl.id);
    p.querySelectorAll('[data-window]').forEach(function (btn) {
      btn.addEventListener('click', function () { openWindow(btn.getAttribute('data-window')); });
    });
    p.querySelector('[data-close]').addEventListener('click', function () { closeWindow(p); });
    makeDraggable(p);
    p.addEventListener('pointerdown', function () { if (!isMobile()) bringToFront(p); });
    openWindow(id);
  }

  document.getElementById('openAllBtn').addEventListener('click', function () {
    if (isMobile()) openIndexWindow();
    else OPEN_ALL_IDS.forEach(openWindow);
  });

  document.getElementById('logoBtn').addEventListener('click', function () {
    var cube = document.querySelector('.cube');
    if (!cube) return;
    cube.style.animationDuration = '0.8s';
    setTimeout(function () { cube.style.animationDuration = '14s'; }, 1600);
  });

  // Fase 5.4: BSOD, icono fantasma, Konami code (js/easter-eggs.js) y el tooltip
  // global (js/tooltip.js) se piden con import() diferido -- no hace falta que
  // cuenten en el JS ejecutado al arrancar. openWindow/spawnPopup se le pasan al
  // modulo de easter eggs porque viven en este closure y no se exportan.
  //
  // requestIdleCallback pide el hueco libre mas cercano en el hilo principal -- no
  // bloquea el arranque ni cuenta para TBT. Se probo primero disparar esto en la
  // primera interaccion (pointerdown/click/keydown/touchstart), pero eso SIEMPRE
  // perdia el primer intento si esa interaccion ES uno de los propios easter eggs (el
  // listener del modulo llega demasiado tarde para el evento que lo disparo). Con
  // requestIdleCallback la ventana de riesgo baja a ~1-2s tras el load -- en teoria
  // alguien podria hacer clic en el icono fantasma (opacity 0.14, "casi invisible")
  // en ese margen y que su primer clic no haga nada, pero es un escenario remoto y de
  // bajo costo (el segundo clic ya funciona) frente a cargar este codigo siempre.
  function loadDeferred() {
    import('./easter-eggs.js').then(function (m) { m.initEasterEggs(openWindow, spawnPopup); });
    import('./tooltip.js').then(function (m) { m.initTooltip(); });
  }
  if (window.requestIdleCallback) requestIdleCallback(loadDeferred, { timeout: 3000 });
  else setTimeout(loadDeferred, 1500);

  function spawnPopup(text, x, y) {
    var p = document.createElement('div');
    p.className = 'window popup';
    var vp = viewportSize();
    var popupW = Math.min(300, vp.w - 32);
    var clamped = clampPos(x, y, popupW, 140);
    p.style.left = clamped[0] + 'px';
    p.style.top = clamped[1] + 'px';
    p.style.zIndex = ++zTop;
    p.setAttribute('role', 'dialog');
    p.setAttribute('aria-modal', 'false');
    p.setAttribute('aria-label', 'Aviso');
    var msg = document.createElement('p');
    msg.textContent = text; // textContent, no innerHTML -- ver Fase 8 (revision de seguridad)
    var header = document.createElement('header');
    header.className = 'title-bar drag-handle';
    header.innerHTML = '<h2 class="title">AVISO</h2><button class="btn-close" data-close type="button">X</button>';
    var content = document.createElement('div');
    content.className = 'window-content';
    content.appendChild(msg);
    p.appendChild(header);
    p.appendChild(content);
    document.body.appendChild(p);
    makeDraggable(p);
    p.querySelector('[data-close]').addEventListener('click', function () { p.remove(); });
    p.addEventListener('pointerdown', function () { if (!isMobile()) bringToFront(p); });
  }

  window.addEventListener('load', function () {
    setTimeout(function () {
      spawnPopup('Este sitio corre a 64 bits, con café, cartuchos y mucha vibra friqui. Explora el escritorio ↓', 40, 420);
    }, 1400);
  });
}
