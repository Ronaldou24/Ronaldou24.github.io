// Extraido del monolito original (Fase 3). Recibe ctx = {mesh, capsule, squash, scene, THREE}.
export function build(ctx) {
  var mesh = ctx.mesh, capsule = ctx.capsule, squash = ctx.squash, scene = ctx.scene, THREE = ctx.THREE;

  var WHITE  = new THREE.MeshStandardMaterial({color:0xf4f1ec, roughness:0.55, metalness:0.02});
  var NAVY   = new THREE.MeshStandardMaterial({color:0x232744, roughness:0.4,  metalness:0.15});
  var NAVY2  = new THREE.MeshStandardMaterial({color:0x2e3358, roughness:0.35, metalness:0.2});
  var PINK   = new THREE.MeshStandardMaterial({color:0xffa8c5, roughness:0.7});
  var INK    = new THREE.MeshStandardMaterial({color:0x1c1a26, roughness:0.6});
  var SILVER = new THREE.MeshStandardMaterial({color:0xd8dce6, roughness:0.22, metalness:0.9});

  var headGeo = new THREE.SphereGeometry(1, 28, 22);
  var head = mesh(headGeo, WHITE, 0, 1.05, 0);
  head.scale.set(1.28, 1.1, 1.15);
  squash.add(head);

  function ear(sx){
    var e = mesh(new THREE.ConeGeometry(0.34, 0.62, 4), WHITE, sx*0.78, 2.02, 0);
    e.rotation.y = Math.PI/4;
    e.rotation.z = -sx*0.32;
    return e;
  }
  squash.add(ear(1)); squash.add(ear(-1));

  var band = mesh(new THREE.TorusGeometry(1.22, 0.13, 12, 32, Math.PI), NAVY, 0, 1.12, 0.02);
  band.scale.set(1.06, 1.02, 1);
  squash.add(band);
  function cup(sx){
    var g = new THREE.Group();
    var outer = mesh(new THREE.CylinderGeometry(0.5, 0.5, 0.34, 20), NAVY);
    outer.rotation.z = Math.PI/2;
    var pad = mesh(new THREE.CylinderGeometry(0.42, 0.42, 0.1, 20), NAVY2, -sx*0.2, 0, 0);
    pad.rotation.z = Math.PI/2;
    g.add(outer); g.add(pad);
    g.position.set(sx*1.28, 1.02, 0.02);
    g.rotation.z = sx*0.08;
    return g;
  }
  squash.add(cup(1)); squash.add(cup(-1));

  function eye(sx){
    var e = mesh(new THREE.TorusGeometry(0.14, 0.028, 8, 16, Math.PI), INK, sx*0.42, 1.02, 1.04);
    e.rotation.x = -0.12;
    return e;
  }
  squash.add(eye(1)); squash.add(eye(-1));

  function blush(sx){
    var b = mesh(new THREE.SphereGeometry(0.14, 12, 10), PINK, sx*0.66, 0.78, 0.95);
    b.scale.set(1.4, 0.8, 0.35);
    b.rotation.y = sx*0.35;
    return b;
  }
  squash.add(blush(1)); squash.add(blush(-1));

  function mw(sx){
    var w = mesh(new THREE.TorusGeometry(0.07, 0.02, 8, 14, Math.PI), INK, sx*0.065, 0.72, 1.12);
    w.rotation.z = Math.PI;
    w.rotation.x = 0.25;
    return w;
  }
  squash.add(mw(1)); squash.add(mw(-1));

  var body = mesh(new THREE.SphereGeometry(0.62, 22, 18), WHITE, 0, -0.28, 0);
  body.scale.set(1, 1.12, 0.92);
  squash.add(body);

  function arm(sx){
    var a = capsule(0.16, 0.52, WHITE);
    a.position.set(sx*0.72, 0.12, 0.1);
    a.rotation.z = sx*2.35;
    return a;
  }
  squash.add(arm(1)); squash.add(arm(-1));

  function leg(sx){
    var l = capsule(0.18, 0.34, WHITE);
    l.position.set(sx*0.3, -0.98, 0.05);
    return l;
  }
  squash.add(leg(1)); squash.add(leg(-1));

  var tail = capsule(0.11, 0.4, WHITE);
  tail.position.set(0.15, -0.75, -0.62);
  tail.rotation.x = -1.0;
  tail.rotation.z = -0.4;
  squash.add(tail);

  function musicNote(){
    var g = new THREE.Group();
    var headN = mesh(new THREE.SphereGeometry(0.11, 10, 8), NAVY2, 0, 0, 0);
    headN.scale.set(1.25, 0.85, 0.6);
    var stem = mesh(new THREE.BoxGeometry(0.045, 0.5, 0.045), NAVY2, 0.12, 0.26, 0);
    var flag = mesh(new THREE.BoxGeometry(0.2, 0.06, 0.045), NAVY2, 0.2, 0.49, 0);
    flag.rotation.z = -0.4;
    g.add(headN); g.add(stem); g.add(flag);
    return g;
  }
  function starShape(){
    var s = new THREE.Shape();
    var R = 0.28, r = 0.115;
    for(var i=0;i<10;i++){
      var rad = (i%2===0)? R : r;
      var a = i*Math.PI/5 - Math.PI/2;
      var x = Math.cos(a)*rad, y = Math.sin(a)*rad;
      if(i===0) s.moveTo(x,y); else s.lineTo(x,y);
    }
    s.closePath();
    return new THREE.ExtrudeGeometry(s, {depth:0.1, bevelEnabled:true, bevelThickness:0.04, bevelSize:0.04, bevelSegments:2});
  }
  var floaters = [];
  var floaterDefs = [
    {mk:musicNote, r:1.05, y: 1.7, sp: 0.55, ph: 0.0},
    {mk:musicNote, r:1.15, y:-0.3, sp:-0.4,  ph: 2.6},
    {mk:function(){return mesh(starShape(), SILVER,0,0,0);}, r:1.25, y: 1.1, sp: 0.35, ph: 4.1},
    {mk:function(){return mesh(starShape(), SILVER,0,0,0);}, r:1.1,  y:-0.75,sp:-0.5,  ph: 1.4}
  ];
  floaterDefs.forEach(function(d){
    var f = d.mk();
    scene.add(f);
    floaters.push({obj:f, def:d});
  });

  return {
    restY: 1.35,
    floaters: floaters,
    onTick: function(t, dt, state){
      tail.rotation.z = -0.4 + (state.reduce? 0 : Math.sin(t*3)*0.25);
      floaters.forEach(function(f){
        var d = f.def;
        var a = t*d.sp + d.ph;
        f.obj.position.set(
          state.pos.x + Math.cos(a)*d.r,
          state.pos.y + d.y + (state.reduce? 0 : Math.sin(t*1.4 + d.ph)*0.18),
          state.pos.z + Math.sin(a)*d.r*0.55 - 0.3
        );
        f.obj.rotation.y = a*1.4;
        f.obj.rotation.z = Math.sin(t + d.ph)*0.2;
      });
    }
  };
}
