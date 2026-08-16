// Extraido del monolito original (Fase 3): burst de glitch aleatorio en el hero.
export function initHeroGlitch() {
  var hero = document.getElementById('glitchHero');
  if (!hero) return;
  if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  var MIN_DELAY = 4000;
  var MAX_DELAY = 9000;
  var BURST_DURATION = 500;

  function triggerBurst() {
    hero.classList.add('burst');
    setTimeout(function () { hero.classList.remove('burst'); }, BURST_DURATION);
    scheduleNextBurst();
  }

  function scheduleNextBurst() {
    var delay = MIN_DELAY + Math.random() * (MAX_DELAY - MIN_DELAY);
    setTimeout(triggerBurst, delay);
  }

  scheduleNextBurst();

  var lastScrollBurst = 0;
  window.addEventListener('scroll', function () {
    var now = Date.now();
    if (now - lastScrollBurst > 2000) {
      lastScrollBurst = now;
      triggerBurst();
    }
  }, { passive: true });
}
