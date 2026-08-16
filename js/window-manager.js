// Extraido del monolito original (Fase 3) y ampliado en la Fase 4 con los fixes de
// movil de OPTIMIZACION.md §2. Window manager + taskbar + tooltip global + easter
// eggs (BSOD, icono fantasma, konami code) se mantienen juntos a proposito -- todas
// estas piezas comparten estado interno (zTop, openedWindows, la funcion
// openWindow/spawnPopup/makeDraggable). La unica dependencia externa real
// (window.initCritterViewer) es un import real de ES modules.
import { initCritterViewer } from './critters/viewer.js';

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

  var lazyWindowInit = {
    papusBlindajeWindow: function () {
      var c = document.getElementById('papusCatCanvas');
      var cont = document.getElementById('papusCatContainer');
      if (!c || !cont) return;

      var names = ['gatito', 'momo', 'tvbot'];
      var idx = 0;
      var api = initCritterViewer(c, cont, names[idx]);
      if (!api) return;

      var nameEl = document.getElementById('papusCritterName');
      var prevBtn = document.getElementById('papusCritterPrev');
      var nextBtn = document.getElementById('papusCritterNext');

      function show(i) {
        idx = (i + names.length) % names.length;
        api.setCharacter(names[idx]);
        if (nameEl) nameEl.textContent = names[idx];
      }
      if (prevBtn) prevBtn.addEventListener('click', function () { show(idx - 1); });
      if (nextBtn) nextBtn.addEventListener('click', function () { show(idx + 1); });

      // flechas del teclado tambien cambian de personaje, mientras la ventana este abierta
      var win = document.getElementById('papusBlindajeWindow');
      document.addEventListener('keydown', function (e) {
        if (!win || win.classList.contains('hidden')) return;
        if (e.key === 'ArrowLeft') { e.preventDefault(); show(idx - 1); }
        else if (e.key === 'ArrowRight') { e.preventDefault(); show(idx + 1); }
      });

      // BUG-8: equivalente tactil de las flechas -- swipe horizontal sobre el canvas
      var touchStartX = null;
      c.addEventListener('touchstart', function (e) {
        if (e.touches.length === 1) touchStartX = e.touches[0].clientX;
      }, { passive: true });
      c.addEventListener('touchend', function (e) {
        if (touchStartX === null) return;
        var endX = (e.changedTouches && e.changedTouches[0]) ? e.changedTouches[0].clientX : touchStartX;
        var dx = endX - touchStartX;
        touchStartX = null;
        if (Math.abs(dx) < 40) return; // swipe corto: probablemente fue un drag de rotacion
        if (dx < 0) show(idx + 1); else show(idx - 1);
      }, { passive: true });

      // camara glitch / slit-scan: rastro RGB-shift tomado en vivo del canvas 3D
      var glitchOut = document.getElementById('papusGlitchCanvas');
      var glitchBtn = document.getElementById('papusGlitchToggle');
      if (glitchOut && glitchBtn && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        var octx = glitchOut.getContext('2d');
        var feedback = document.createElement('canvas');
        var fctx = feedback.getContext('2d');
        var DECAY = 0.85, SHIFT_R = 3, SHIFT_B = -3;
        var rafId = null;

        function glitchRender() {
          var w = glitchOut.width, h = glitchOut.height;
          if (!w || !h) { rafId = requestAnimationFrame(glitchRender); return; }

          octx.globalAlpha = DECAY;
          octx.drawImage(feedback, 0, 0, w, h);
          octx.globalAlpha = 1;

          octx.globalCompositeOperation = 'lighten';
          octx.drawImage(c, 0, 0, w, h);
          octx.globalCompositeOperation = 'source-over';

          var frame = octx.getImageData(0, 0, w, h);
          var shifted = octx.createImageData(w, h);
          var src = frame.data, dst = shifted.data;
          for (var y = 0; y < h; y++) {
            for (var x = 0; x < w; x++) {
              var i = (y * w + x) * 4;
              var rx = Math.min(w - 1, Math.max(0, x + SHIFT_R));
              var bx = Math.min(w - 1, Math.max(0, x + SHIFT_B));
              var ri = (y * w + rx) * 4;
              var bi = (y * w + bx) * 4;
              dst[i]     = src[ri];
              dst[i + 1] = src[i + 1];
              dst[i + 2] = src[bi + 2];
              dst[i + 3] = 255;
            }
          }
          octx.putImageData(shifted, 0, 0);

          fctx.clearRect(0, 0, w, h);
          fctx.drawImage(glitchOut, 0, 0, w, h);

          rafId = requestAnimationFrame(glitchRender);
        }

        glitchBtn.addEventListener('click', function () {
          var on = glitchBtn.getAttribute('aria-pressed') === 'true';
          if (on) {
            if (rafId) cancelAnimationFrame(rafId);
            rafId = null;
            glitchOut.classList.remove('active');
            glitchBtn.setAttribute('aria-pressed', 'false');
          } else {
            glitchOut.width = feedback.width = c.width;
            glitchOut.height = feedback.height = c.height;
            fctx.clearRect(0, 0, feedback.width, feedback.height);
            glitchOut.classList.add('active');
            glitchBtn.setAttribute('aria-pressed', 'true');
            rafId = requestAnimationFrame(glitchRender);
          }
        });
      } else if (glitchBtn) {
        glitchBtn.style.display = 'none';
      }
    }
  };

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

      if (lazyWindowInit[id]) { lazyWindowInit[id](); lazyWindowInit[id] = null; }

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
      '<header class="title-bar drag-handle"><span class="title">ÍNDICE.LNK</span>' +
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

  var bugClicks = 0;
  document.getElementById('bugIcon').addEventListener('click', function () {
    bugClicks++;
    if (bugClicks >= 3) {
      document.getElementById('bsod').classList.remove('hidden');
      bugClicks = 0;
      return;
    }
    this.classList.add('shake');
    var self = this;
    setTimeout(function () { self.classList.remove('shake'); }, 300);
  });

  document.getElementById('bsod').addEventListener('click', function () {
    this.classList.add('hidden');
  });

  var ghostIcon = document.getElementById('ghostIcon');
  if (ghostIcon) {
    ghostIcon.addEventListener('click', function () {
      var vp = viewportSize();
      spawnPopup('👻 Boo. Encontraste el ícono fantasma — casi nadie lo nota a la primera.', Math.max(20, vp.w / 2 - 150), Math.max(80, vp.h / 2 - 90));
    });
  }

  // código clásico de videojuego: arriba arriba abajo abajo izq der izq der B A
  var konamiSeq = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a'];
  var konamiPos = 0;

  function triggerKonami() {
    document.body.classList.add('konami-flash');
    setTimeout(function () { document.body.classList.remove('konami-flash'); }, 1200);
    openWindow('secretWindow');
  }

  document.addEventListener('keydown', function (e) {
    var key = e.key.length === 1 ? e.key.toLowerCase() : e.key;
    if (key === konamiSeq[konamiPos]) {
      konamiPos++;
      if (konamiPos === konamiSeq.length) {
        konamiPos = 0;
        triggerKonami();
      }
    } else {
      konamiPos = (key === konamiSeq[0]) ? 1 : 0;
    }
  });

  // BUG-8: equivalente tactil del konami code -- 5 toques seguidos en el logo, <3s
  var logoTapCount = 0, logoTapTimer = null;
  document.getElementById('logoBtn').addEventListener('click', function () {
    logoTapCount++;
    if (logoTapTimer) clearTimeout(logoTapTimer);
    logoTapTimer = setTimeout(function () { logoTapCount = 0; }, 3000);
    if (logoTapCount >= 5) {
      logoTapCount = 0;
      clearTimeout(logoTapTimer);
      triggerKonami();
    }
  });

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
    header.innerHTML = '<span class="title">AVISO</span><button class="btn-close" data-close type="button">X</button>';
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

  // tooltip global: un solo elemento fixed que nunca se recorta con el overflow de las ventanas
  var tip = document.createElement('div');
  tip.className = 'global-tooltip';
  document.body.appendChild(tip);

  function positionTooltip(el) {
    var r = el.getBoundingClientRect();
    var tw = tip.offsetWidth, th = tip.offsetHeight;
    var vp = viewportSize();
    var left = r.left + r.width / 2 - tw / 2;
    left = Math.max(6, Math.min(left, vp.w - tw - 6));
    var top = r.top - th - 8;
    if (top < 6) top = r.bottom + 8;
    tip.style.left = left + 'px';
    tip.style.top = top + 'px';
  }

  function showTooltip(el) {
    var txt = el.getAttribute('data-tooltip');
    if (!txt) return;
    tip.textContent = txt;
    tip.classList.add('visible');
    positionTooltip(el);
  }
  function hideTooltip() {
    tip.classList.remove('visible');
  }

  // BUG-4: los tooltips solo existian con mouseenter/mouseleave, invisibles en tactil.
  // hover:hover detecta si el dispositivo realmente puede "pasar el mouse por encima".
  var canHover = window.matchMedia('(hover: hover)').matches;
  document.querySelectorAll('[data-tooltip]').forEach(function (el) {
    if (canHover) {
      el.addEventListener('mouseenter', function () { showTooltip(el); });
      el.addEventListener('mouseleave', hideTooltip);
    } else {
      el.addEventListener('click', function (e) {
        var wasVisible = tip.classList.contains('visible') && tip.textContent === el.getAttribute('data-tooltip');
        hideTooltip();
        if (!wasVisible) { e.stopPropagation(); showTooltip(el); }
      });
    }
    el.addEventListener('focus', function () { showTooltip(el); });
    el.addEventListener('blur', hideTooltip);
  });
  if (!canHover) {
    document.addEventListener('click', function (e) {
      if (!e.target.closest('[data-tooltip]')) hideTooltip();
    });
  }
}
