// Extraido del monolito original (Fase 3): window manager + taskbar + tooltip
// global + easter eggs (BSOD, icono fantasma, konami code). Se mantiene junto en un
// solo modulo a proposito -- todas estas piezas comparten estado interno (zTop,
// openedWindows, la funcion openWindow/spawnPopup/makeDraggable) tal como en el
// script original, y separarlas en archivos independientes hubiera requerido
// exportar/importar ese estado compartido entre modulos sin poder verificarlo tan
// a fondo. La unica dependencia externa real (window.initCritterViewer) ahora es
// un import real de ES modules en vez de una variable global.
import { initCritterViewer } from './critters/viewer.js';

export function initWindowManager() {
  'use strict';

  function updateClock() {
    var d = new Date();
    var h = String(d.getHours()).padStart(2, '0');
    var m = String(d.getMinutes()).padStart(2, '0');
    document.getElementById('clockText').textContent = h + ':' + m;
  }
  updateClock();
  setInterval(updateClock, 30000);

  var zTop = 100;

  function clampPos(x, y, w, h) {
    var maxX = window.innerWidth - w - 8;
    var maxY = window.innerHeight - h - 8;
    return [
      Math.min(Math.max(8, x), Math.max(8, maxX)),
      Math.min(Math.max(56, y), Math.max(56, maxY))
    ];
  }

  function bringToFront(win) {
    zTop++;
    win.style.zIndex = zTop;
  }

  function estimateWindowSize(win) {
    var base = win.classList.contains('cute') ? 440 : (win.classList.contains('popup') ? 300 : 380);
    var w = Math.min(base, window.innerWidth - 32);
    var h = Math.min(300, window.innerHeight * 0.7);
    return [w, h];
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

  function openWindow(id) {
    var win = document.getElementById(id);
    if (!win) return;
    if (win.classList.contains('hidden')) {
      var pos = (win.dataset.defaultPos || '60,110').split(',').map(Number);
      var size = estimateWindowSize(win);
      var clamped = clampPos(pos[0], pos[1], size[0], size[1]);
      win.style.left = clamped[0] + 'px';
      win.style.top = clamped[1] + 'px';
      if (lazyWindowInit[id]) { lazyWindowInit[id](); lazyWindowInit[id] = null; }

      openedWindows[id] = true;
      if (!achievementUnlocked && Object.keys(openedWindows).length >= ACHIEVEMENT_THRESHOLD) {
        achievementUnlocked = true;
        setTimeout(function () {
          spawnPopup('🏆 ¡Explorador de cartucho completo! Ya viste casi todo el escritorio — gracias por curiosear tanto.', 40, Math.max(80, window.innerHeight - 200));
        }, 500);
      }
    }
    win.classList.remove('hidden');
    bringToFront(win);
  }

  function makeDraggable(win) {
    var handle = win.querySelector('.drag-handle');
    if (!handle) return;
    var startX = 0, startY = 0, activeId = null;
    handle.addEventListener('pointerdown', function (e) {
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
    win.addEventListener('pointerdown', function () { bringToFront(win); });
    var closeBtn = win.querySelector('[data-close]');
    if (closeBtn) {
      closeBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        win.classList.add('hidden');
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

  document.getElementById('openAllBtn').addEventListener('click', function () {
    ['sobreMiWindow', 'statusWindow', 'inventarioWindow', 'misionesWindow', 'contactoWindow', 'moodboardWindow', 'refsWindow', 'retroWindow', 'coleccionWindow'].forEach(openWindow);
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
      spawnPopup('👻 Boo. Encontraste el ícono fantasma — casi nadie lo nota a la primera.', Math.max(20, window.innerWidth / 2 - 150), Math.max(80, window.innerHeight / 2 - 90));
    });
  }

  // código clásico de videojuego: arriba arriba abajo abajo izq der izq der B A
  var konamiSeq = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a'];
  var konamiPos = 0;
  document.addEventListener('keydown', function (e) {
    var key = e.key.length === 1 ? e.key.toLowerCase() : e.key;
    if (key === konamiSeq[konamiPos]) {
      konamiPos++;
      if (konamiPos === konamiSeq.length) {
        konamiPos = 0;
        document.body.classList.add('konami-flash');
        setTimeout(function () { document.body.classList.remove('konami-flash'); }, 1200);
        openWindow('secretWindow');
      }
    } else {
      konamiPos = (key === konamiSeq[0]) ? 1 : 0;
    }
  });

  function spawnPopup(text, x, y) {
    var p = document.createElement('div');
    p.className = 'window popup';
    var popupW = Math.min(300, window.innerWidth - 32);
    var clamped = clampPos(x, y, popupW, 140);
    p.style.left = clamped[0] + 'px';
    p.style.top = clamped[1] + 'px';
    p.style.zIndex = ++zTop;
    p.innerHTML =
      '<header class="title-bar drag-handle"><span class="title">AVISO</span>' +
      '<button class="btn-close" data-close type="button">X</button></header>' +
      '<div class="window-content"><p>' + text + '</p></div>';
    document.body.appendChild(p);
    makeDraggable(p);
    p.querySelector('[data-close]').addEventListener('click', function () { p.remove(); });
    p.addEventListener('pointerdown', function () { bringToFront(p); });
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
    var left = r.left + r.width / 2 - tw / 2;
    left = Math.max(6, Math.min(left, window.innerWidth - tw - 6));
    var top = r.top - th - 8;
    if (top < 6) top = r.bottom + 8;
    tip.style.left = left + 'px';
    tip.style.top = top + 'px';
  }

  document.querySelectorAll('[data-tooltip]').forEach(function (el) {
    el.addEventListener('mouseenter', function () {
      var txt = el.getAttribute('data-tooltip');
      if (!txt) return;
      tip.textContent = txt;
      tip.classList.add('visible');
      positionTooltip(el);
    });
    el.addEventListener('mouseleave', function () {
      tip.classList.remove('visible');
    });
  });
}
