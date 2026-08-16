// Extraido del monolito original (Fase 3): motor generico de los personajes 3D
// (camara, fisica de agarrar/aventar, drag para rotar). three.js sigue siendo un
// script clasico cargado desde CDN (no modulo), por eso se lee de window.THREE.
import { build as buildGatito } from './gatito.js';
import { build as buildMomo } from './momo.js';
import { build as buildTvbot } from './tvbot.js';

const CRITTER_BUILDERS = { gatito: buildGatito, momo: buildMomo, tvbot: buildTvbot };

export function initCritterViewer(canvas, container, builderKey) {
  var THREE = window.THREE;
  'use strict';
  if (!container || !canvas || !window.THREE) return null;

  var renderer = new THREE.WebGLRenderer({canvas:canvas, antialias:true, alpha:true, preserveDrawingBuffer:true});
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

  var scene = new THREE.Scene();
  var camera = new THREE.PerspectiveCamera(38, 1, 0.1, 100);
  camera.position.set(0, 1.0, 7.2);
  camera.lookAt(0, 0.5, 0);

  scene.add(new THREE.HemisphereLight(0xbfd8ff, 0x2a2140, 0.9));
  var key = new THREE.DirectionalLight(0xffffff, 0.9);
  key.position.set(3, 5, 4);
  scene.add(key);
  var rim = new THREE.DirectionalLight(0x29adff, 0.4);
  rim.position.set(-4, 2, -3);
  scene.add(rim);
  var rim2 = new THREE.DirectionalLight(0xff4fa0, 0.25);
  rim2.position.set(4, -1, -3);
  scene.add(rim2);

  function mesh(geo, mat, x, y, z){
    var m = new THREE.Mesh(geo, mat);
    m.position.set(x||0, y||0, z||0);
    return m;
  }
  // "capsula" a mano (r128 no trae CapsuleGeometry): cilindro + 2 esferas
  function capsule(r, len, mat){
    var g = new THREE.Group();
    g.add(mesh(new THREE.CylinderGeometry(r, r, len, 14), mat));
    g.add(mesh(new THREE.SphereGeometry(r, 14, 12), mat, 0,  len/2, 0));
    g.add(mesh(new THREE.SphereGeometry(r, 14, 12), mat, 0, -len/2, 0));
    return g;
  }

  var cat = new THREE.Group();     // posicion (fisica)
  var spin = new THREE.Group();    // rotacion (drag izquierdo)
  var squash = new THREE.Group();  // squash & stretch
  cat.add(spin); spin.add(squash);
  scene.add(cat);

  var shadow = mesh(new THREE.CircleGeometry(1, 28),
    new THREE.MeshBasicMaterial({color:0x000000, transparent:true, opacity:0.28}), 0, 0, 0);
  shadow.rotation.x = -Math.PI/2;
  scene.add(shadow);

  // ---------- interaccion ----------
  var FLOOR = -1.35;               // altura del "piso"
  var pos = new THREE.Vector3(0, 0, 0);   // offset fisico del personaje
  var vel = new THREE.Vector3();
  var grabbing = false, rotating = false;
  var grabOffset = new THREE.Vector3();
  var lastGrab = new THREE.Vector3();
  var raycaster = new THREE.Raycaster();
  var ndc = new THREE.Vector2();
  var dragPlane = new THREE.Plane(new THREE.Vector3(0,0,1), 0);
  var hit = new THREE.Vector3();
  var px = 0, py = 0;
  var rotVelY = 0.25;

  function setNDC(e){
    var r = canvas.getBoundingClientRect();
    var cx = (e.touches? e.touches[0].clientX : e.clientX);
    var cy = (e.touches? e.touches[0].clientY : e.clientY);
    ndc.x = ((cx - r.left)/r.width)*2 - 1;
    ndc.y = -((cy - r.top)/r.height)*2 + 1;
    return {x:cx, y:cy};
  }
  function planeHit(){
    raycaster.setFromCamera(ndc, camera);
    raycaster.ray.intersectPlane(dragPlane, hit);
    return hit;
  }

  canvas.addEventListener('contextmenu', function(e){ e.preventDefault(); });

  canvas.addEventListener('pointerdown', function(e){
    var p = setNDC(e);
    px = p.x; py = p.y;
    if(e.button === 2){                       // clic derecho: agarrar
      grabbing = true;
      dragPlane.constant = -pos.z;
      planeHit();
      grabOffset.copy(pos).sub(hit);
      lastGrab.copy(pos);
      vel.set(0,0,0);
      canvas.style.cursor = 'grabbing';
    } else {                                  // izquierdo: rotar
      rotating = true;
      canvas.style.cursor = 'grab';
    }
    canvas.setPointerCapture(e.pointerId);
  });

  canvas.addEventListener('pointermove', function(e){
    var p = setNDC(e);
    if(grabbing){
      planeHit();
      lastGrab.copy(pos);
      pos.copy(hit).add(grabOffset);
      pos.x = Math.max(-0.6, Math.min(0.6, pos.x));    // no salirse del encuadre chico
      pos.y = Math.max(FLOOR, Math.min(0.6, pos.y));   // ni por el piso ni por arriba
      vel.copy(pos).sub(lastGrab).multiplyScalar(12);
    } else if(rotating){
      var dx = (p.x - px) * 0.011;
      var dy = (p.y - py) * 0.009;
      spin.rotation.y += dx;
      spin.rotation.x = Math.max(-0.6, Math.min(0.6, spin.rotation.x + dy));
      rotVelY = dx * 34;
    }
    px = p.x; py = p.y;
  });

  function release(e){
    if(grabbing){ grabbing = false; }
    rotating = false;
    canvas.style.cursor = 'default';
    if(e && e.pointerId !== undefined){
      try{ canvas.releasePointerCapture(e.pointerId); }catch(_){/*noop*/}
    }
  }
  canvas.addEventListener('pointerup', release);
  canvas.addEventListener('pointercancel', release);

  canvas.addEventListener('wheel', function(e){
    e.preventDefault();
    camera.position.z = Math.max(4.4, Math.min(9.5, camera.position.z + e.deltaY*0.003));
  }, {passive:false});

  function resize(){
    var w = container.clientWidth || 230, h = container.clientHeight || 300;
    renderer.setSize(w, h, false);
    camera.aspect = w/h;
    camera.updateProjectionMatrix();
  }
  window.addEventListener('resize', resize);
  resize();

  var clock = new THREE.Clock();
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var sq = 1, sqTarget = 1;

  // ---------- selector de personaje ----------
  var floaters = [];
  var onTick = function(){};
  var restY = 1.35;

  function disposeDeep(obj){
    obj.traverse(function(o){
      if (o.geometry) o.geometry.dispose();
      if (o.material){
        if (Array.isArray(o.material)) o.material.forEach(function(m){ m.dispose(); });
        else o.material.dispose();
      }
    });
  }

  function setCharacter(name){
    var build = CRITTER_BUILDERS[name];
    if (!build) return;
    while (squash.children.length){
      var c = squash.children.pop();
      disposeDeep(c);
    }
    floaters.forEach(function(f){ scene.remove(f.obj); disposeDeep(f.obj); });
    floaters = [];
    onTick = function(){};
    pos.set(0,0,0); vel.set(0,0,0);
    spin.rotation.set(0,0,0);
    squash.position.set(0,0,0);
    squash.scale.set(1,1,1);
    sq = 1; sqTarget = 1;

    var built = build({ mesh: mesh, capsule: capsule, squash: squash, scene: scene, THREE: THREE }) || {};
    floaters = built.floaters || [];
    onTick = built.onTick || function(){};
    restY = (built.restY !== undefined) ? built.restY : 1.35;
    container.style.background = built.bg || '';
    container.style.borderRadius = built.bg ? '14px' : '';
  }

  function animate(){
    requestAnimationFrame(animate);
    var dt = Math.min(clock.getDelta(), 0.05);
    var t = clock.elapsedTime;

    if(!grabbing){
      vel.y -= 22 * dt;
      pos.addScaledVector(vel, dt);
      vel.x *= 0.995; vel.z *= 0.995;
      if(pos.x >  0.6){ pos.x =  0.6; vel.x *= -0.5; }
      if(pos.x < -0.6){ pos.x = -0.6; vel.x *= -0.5; }
      if(pos.y > 0.65){ pos.y = 0.65; vel.y *= -0.4; }
      if(pos.y <= FLOOR + restY){
        pos.y = FLOOR + restY;
        if(vel.y < -1){
          sqTarget = 0.72;
          vel.y = -vel.y * 0.45;
          if(Math.abs(vel.y) < 1.2) vel.y = 0;
        } else { vel.y = 0; }
      }
    }
    cat.position.copy(pos);

    if(!rotating && !grabbing){
      spin.rotation.y += rotVelY * dt;
      rotVelY *= 0.96;
      if(Math.abs(rotVelY) < 0.06) rotVelY = reduce ? 0 : 0.25;
      spin.rotation.x *= 0.97;
      if(!reduce && Math.abs(vel.y) < 0.01){
        squash.position.y = Math.sin(t*2.2)*0.06;
      }
    } else {
      squash.position.y = 0;
    }

    sqTarget += (1 - sqTarget) * Math.min(1, dt*8);
    sq += (sqTarget - sq) * Math.min(1, dt*14);
    squash.scale.set(1/Math.sqrt(sq), sq, 1/Math.sqrt(sq));

    shadow.position.x = pos.x;
    shadow.position.z = pos.z;
    shadow.position.y = FLOOR - 0.001;
    var hgt = Math.max(0, pos.y - 0);
    var sScale = Math.max(0.45, 1 - hgt*0.18);
    shadow.scale.set(sScale, sScale, 1);
    shadow.material.opacity = 0.28 * sScale;

    onTick(t, dt, { rotating: rotating, grabbing: grabbing, reduce: reduce, pos: pos });

    renderer.render(scene, camera);
  }

  setCharacter(builderKey || 'gatito');
  animate();

  return { setCharacter: setCharacter };
}
