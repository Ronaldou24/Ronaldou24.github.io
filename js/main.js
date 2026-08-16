// Entry point (type="module"). Los modulos ES se ejecutan despues de que el HTML
// termina de parsearse (igual que "defer"), asi que no hace falta esperar
// DOMContentLoaded a mano como hacia el script original.
import { initWindowManager } from './window-manager.js';
import { initHeroGlitch } from './hero-glitch.js';

initWindowManager();
initHeroGlitch();
