// Extraido del monolito original (Fase 3). Recibe ctx = {mesh, capsule, squash, scene, THREE}.
export function build(ctx) {
  var mesh = ctx.mesh, squash = ctx.squash, scene = ctx.scene, THREE = ctx.THREE;

  var PEACH = new THREE.MeshStandardMaterial({color:0xffb3ae, roughness:0.65, metalness:0.02, flatShading:true});
  var CREAM = new THREE.MeshStandardMaterial({color:0xffe4d0, roughness:0.7,  flatShading:true});
  var PLUM  = new THREE.MeshStandardMaterial({color:0x5c3a6e, roughness:0.5,  flatShading:true});
  var INK   = new THREE.MeshStandardMaterial({color:0x241a20, roughness:0.5});
  var GLINT = new THREE.MeshBasicMaterial({color:0xfff2ea});
  var BLUSH = new THREE.MeshStandardMaterial({color:0xff7d9c, roughness:0.75});
  var STEM  = new THREE.MeshStandardMaterial({color:0x6e4a30, roughness:0.7,  flatShading:true});
  var LEAF  = new THREE.MeshStandardMaterial({color:0x7fc98a, roughness:0.6,  flatShading:true, side:THREE.DoubleSide});
  var PETAL = new THREE.MeshStandardMaterial({color:0xffc9d6, roughness:0.6,  flatShading:true, side:THREE.DoubleSide});

  var body = mesh(new THREE.SphereGeometry(1.15, 14, 11), PEACH, 0, 0.55, 0);
  body.scale.set(1.1, 1.02, 1.06);
  squash.add(body);
  var belly = mesh(new THREE.SphereGeometry(1.15, 14, 11), CREAM, 0, 0.38, 0.12);
  belly.scale.set(0.78, 0.66, 0.98);
  squash.add(belly);

  var stalk = mesh(new THREE.CylinderGeometry(0.05, 0.07, 0.34, 6), STEM, 0.02, 1.85, 0);
  stalk.rotation.z = 0.18;
  squash.add(stalk);
  var leaf = mesh(new THREE.SphereGeometry(0.24, 8, 6), LEAF, 0.28, 2.0, 0);
  leaf.scale.set(1.5, 0.5, 0.7);
  leaf.rotation.z = 0.5;
  squash.add(leaf);

  function eye(sx){
    var g = new THREE.Group();
    var e = mesh(new THREE.SphereGeometry(0.17, 12, 10), INK);
    e.scale.set(1, 1.05, 0.5);
    var g1 = mesh(new THREE.SphereGeometry(0.06, 8, 6), GLINT, 0.045, 0.055, 0.07);
    var g2 = mesh(new THREE.SphereGeometry(0.028, 8, 6), GLINT, -0.04, -0.05, 0.07);
    g.add(e); g.add(g1); g.add(g2);
    g.position.set(sx*0.42, 0.78, 1.02);
    g.rotation.y = sx*0.25;
    return g;
  }
  squash.add(eye(1)); squash.add(eye(-1));

  function blushy(sx){
    var b = mesh(new THREE.SphereGeometry(0.15, 10, 8), BLUSH, sx*0.74, 0.5, 0.9);
    b.scale.set(1.25, 0.85, 0.35);
    b.rotation.y = sx*0.45;
    return b;
  }
  squash.add(blushy(1)); squash.add(blushy(-1));

  var mouth = mesh(new THREE.TorusGeometry(0.055, 0.026, 8, 12), INK, 0, 0.5, 1.12);
  mouth.rotation.x = 0.2;
  squash.add(mouth);

  function arm(sx){
    var a = mesh(new THREE.SphereGeometry(0.22, 10, 8), PEACH, sx*0.95, 0.32, 0.45);
    a.scale.set(0.85, 1.1, 0.85);
    a.rotation.z = sx*0.5;
    return a;
  }
  squash.add(arm(1)); squash.add(arm(-1));

  function foot(sx){
    var f = mesh(new THREE.SphereGeometry(0.24, 10, 8), PLUM, sx*0.42, -0.5, 0.2);
    f.scale.set(1, 0.65, 1.2);
    return f;
  }
  squash.add(foot(1)); squash.add(foot(-1));

  var petalGeo = new THREE.CircleGeometry(0.14, 7);
  var floaters = [];
  var defs = [
    {r:0.95, y: 0.75, sp: 0.5,  ph:0.0},
    {r:1.05, y: 0.2,  sp:-0.4,  ph:1.7},
    {r:0.9,  y:-0.3,  sp: 0.42, ph:3.1},
    {r:1.1,  y: 0.45, sp:-0.5,  ph:4.4},
    {r:1.0,  y:-0.45, sp: 0.55, ph:5.6}
  ];
  defs.forEach(function(d){
    var p = mesh(petalGeo, PETAL, 0, 0, 0);
    p.scale.set(1, 0.7, 1);
    scene.add(p);
    floaters.push({obj:p, def:d});
  });

  return {
    restY: 1.15,
    floaters: floaters,
    onTick: function(t, dt, state){
      leaf.rotation.z = 0.5 + (state.reduce? 0 : Math.sin(t*2.6)*0.18);
      leaf.rotation.y = state.reduce? 0 : Math.sin(t*1.3)*0.15;
      floaters.forEach(function(f){
        var d = f.def;
        var a = t*d.sp + d.ph;
        f.obj.position.set(
          state.pos.x + Math.cos(a)*d.r,
          state.pos.y + d.y + (state.reduce? 0 : Math.sin(t*0.9 + d.ph)*0.3),
          state.pos.z + Math.sin(a)*d.r*0.55 - 0.3
        );
        f.obj.rotation.x = a*0.8 + 0.4;
        f.obj.rotation.y = a*1.2;
        f.obj.rotation.z = Math.sin(t + d.ph)*0.6;
      });
    }
  };
}
