// Fase 5.4: separado de js/window-manager.js y cargado con import() diferido, en la
// primera interaccion real del usuario (ver loadEasterEggs() en window-manager.js) --
// BSOD, icono fantasma y Konami code son puramente reactivos, nadie los dispara en el
// primer instante en que la pagina termina de cargar. openWindow/spawnPopup se pasan
// por parametro en vez de importarse porque viven en el closure de
// initWindowManager() y no se exportan.
export function initEasterEggs(openWindow, spawnPopup) {
  'use strict';

  var bugClicks = 0;
  var bugIcon = document.getElementById('bugIcon');
  if (bugIcon) {
    bugIcon.addEventListener('click', function () {
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
  }

  var bsod = document.getElementById('bsod');
  if (bsod) {
    bsod.addEventListener('click', function () {
      this.classList.add('hidden');
    });
  }

  var ghostIcon = document.getElementById('ghostIcon');
  if (ghostIcon) {
    ghostIcon.addEventListener('click', function () {
      var vv = window.visualViewport;
      var vp = { w: vv ? vv.width : window.innerWidth, h: vv ? vv.height : window.innerHeight };
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
  var logoBtn = document.getElementById('logoBtn');
  if (logoBtn) {
    logoBtn.addEventListener('click', function () {
      logoTapCount++;
      if (logoTapTimer) clearTimeout(logoTapTimer);
      logoTapTimer = setTimeout(function () { logoTapCount = 0; }, 3000);
      if (logoTapCount >= 5) {
        logoTapCount = 0;
        clearTimeout(logoTapTimer);
        triggerKonami();
      }
    });
  }
}
