// Fase 5.4 (OPTIMIZACION.md): pausa animaciones fuera del viewport y ofrece un
// toggle "MODO RENDIMIENTO" que apaga scanlines/glitch/bloom/visor 3D de un tiron.
// La preferencia se guarda en localStorage y se aplica ANTES del primer pintado con
// un script chiquito inline en el <head> de index.html (agrega la clase .perf-mode a
// <html> antes de que este modulo -- o cualquier otro -- llegue a ejecutarse). Este
// modulo solo sincroniza el boton de la taskbar con ese estado inicial y expone
// isPerfMode()/onPerfModeChange() para que otros modulos (window-manager.js,
// hero-glitch.js) puedan reaccionar sin tener que importarse entre si.
var STORAGE_KEY = 'perfMode';
var listeners = [];

export function isPerfMode() {
  return document.documentElement.classList.contains('perf-mode');
}

export function onPerfModeChange(cb) {
  listeners.push(cb);
}

function setPerfMode(active) {
  document.documentElement.classList.toggle('perf-mode', active);
  try { localStorage.setItem(STORAGE_KEY, active ? '1' : '0'); } catch (_) { /* noop: privado o cuota llena */ }
  listeners.forEach(function (cb) { cb(active); });
}

export function initPerf() {
  var btn = document.getElementById('perfModeBtn');
  if (btn) {
    btn.setAttribute('aria-pressed', isPerfMode() ? 'true' : 'false');
    btn.addEventListener('click', function () {
      setPerfMode(!isPerfMode());
      btn.setAttribute('aria-pressed', isPerfMode() ? 'true' : 'false');
    });
  }

  // Pausa animaciones infinitas ([data-animated], ver css/components.css) en cuanto
  // su contenedor sale del viewport. rootMargin da un colchon de 100px para que el
  // arranque/parada no se note justo en el borde de la pantalla.
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      e.target.classList.toggle('anim-paused', !e.isIntersecting);
    });
  }, { rootMargin: '100px' });
  document.querySelectorAll('[data-animated]').forEach(function (el) { io.observe(el); });
}
