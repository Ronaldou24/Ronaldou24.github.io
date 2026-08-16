// Fase 5.4: separado de js/window-manager.js y cargado con import() diferido en la
// primera interaccion real (ver loadTooltip() en window-manager.js) -- un tooltip no
// hace falta en el instante en que la pagina termina de cargar, solo cuando el
// usuario de verdad pasa el mouse/toca/enfoca algo. No depende de nada del window
// manager (no recibe parametros), asi que vive completamente autocontenido.
export function initTooltip() {
  'use strict';

  // tooltip global: un solo elemento fixed que nunca se recorta con el overflow de las ventanas
  var tip = document.createElement('div');
  tip.className = 'global-tooltip';
  document.body.appendChild(tip);

  function viewportSize() {
    var vv = window.visualViewport;
    return { w: vv ? vv.width : window.innerWidth, h: vv ? vv.height : window.innerHeight };
  }

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
