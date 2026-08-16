// Extraido del monolito original (Fase 3). Recibe ctx = {mesh, capsule, squash, scene, THREE}.
export function build(ctx) {
  var mesh = ctx.mesh, capsule = ctx.capsule, squash = ctx.squash, scene = ctx.scene, THREE = ctx.THREE;

  var BLUE   = new THREE.MeshStandardMaterial({color:0x5fa0ff, roughness:0.4, metalness:0.1});
  var BLUE_D = new THREE.MeshStandardMaterial({color:0x4a7fe0, roughness:0.4, metalness:0.1});
  var SCREEN = new THREE.MeshStandardMaterial({color:0xeaf4ff, roughness:0.3, metalness:0.05});
  var MARK   = new THREE.MeshStandardMaterial({color:0x1c1a26, roughness:0.6});
  var NOSE   = new THREE.MeshStandardMaterial({color:0xff8f4d, roughness:0.5});
  var PEACH  = new THREE.MeshStandardMaterial({color:0xffc8a3, roughness:0.6});
  var WHITE  = new THREE.MeshStandardMaterial({color:0xf5f8ff, roughness:0.4});
  var PINK   = new THREE.MeshStandardMaterial({color:0xff8fb0, roughness:0.5});
  var GOLD   = new THREE.MeshStandardMaterial({color:0xffd94d, roughness:0.3, metalness:0.3});

  function starShape(){
    var s = new THREE.Shape();
    var R = 0.24, r = 0.1;
    for(var i=0;i<10;i++){
      var rad = (i%2===0)? R : r;
      var a = i*Math.PI/5 - Math.PI/2;
      var x = Math.cos(a)*rad, y = Math.sin(a)*rad;
      if(i===0) s.moveTo(x,y); else s.lineTo(x,y);
    }
    s.closePath();
    return new THREE.ExtrudeGeometry(s, {depth:0.08, bevelEnabled:true, bevelThickness:0.03, bevelSize:0.03, bevelSegments:1});
  }

  // cabeza tipo TV con pantalla y marca en X
  squash.add(mesh(new THREE.BoxGeometry(1.5, 1.25, 0.9), BLUE_D, 0, 1.55, 0));
  squash.add(mesh(new THREE.BoxGeometry(1.18, 0.98, 0.12), SCREEN, 0, 1.55, 0.48));
  function markBar(rot){
    var b = mesh(new THREE.BoxGeometry(0.82, 0.13, 0.05), MARK, 0, 1.55, 0.56);
    b.rotation.z = rot;
    return b;
  }
  squash.add(markBar(Math.PI/4));
  squash.add(markBar(-Math.PI/4));
  var cone = mesh(new THREE.ConeGeometry(0.32, 0.55, 8), BLUE, 0, 2.32, -0.1);
  squash.add(cone);
  squash.add(mesh(new THREE.SphereGeometry(0.42, 16, 12), NOSE, 0, 1.26, 0.72));

  // cuerpo con insignia de estrella
  squash.add(mesh(new THREE.BoxGeometry(1.15, 1.0, 0.75), BLUE, 0, 0.35, 0));
  squash.add(mesh(starShape(), WHITE, -0.12, 0.55, 0.4));

  // bracitos
  function arm(sx){
    var a = capsule(0.17, 0.55, PEACH);
    a.position.set(sx*0.78, 0.4, 0.05);
    a.rotation.z = sx*0.35;
    return a;
  }
  squash.add(arm(1)); squash.add(arm(-1));

  // piernas y tenis
  function leg(sx){
    return mesh(new THREE.BoxGeometry(0.32, 0.42, 0.4), BLUE_D, sx*0.32, -0.38, 0);
  }
  squash.add(leg(1)); squash.add(leg(-1));
  function shoe(sx){
    var g = new THREE.Group();
    g.add(mesh(new THREE.BoxGeometry(0.48, 0.3, 0.62), WHITE, 0, 0, 0.06));
    g.add(mesh(new THREE.BoxGeometry(0.5, 0.1, 0.2), PINK, 0, -0.08, 0.28));
    g.position.set(sx*0.32, -0.72, 0);
    return g;
  }
  squash.add(shoe(1)); squash.add(shoe(-1));

  // estrellitas doradas orbitando
  var floaters = [];
  var floaterDefs = [
    {r:1.1, y: 1.9, sp: 0.5,  ph: 0.0},
    {r:1.0, y: 1.6, sp:-0.42, ph: 2.9}
  ];
  floaterDefs.forEach(function(d){
    var f = mesh(starShape(), GOLD, 0, 0, 0);
    f.scale.setScalar(0.85);
    scene.add(f);
    floaters.push({obj:f, def:d});
  });

  return {
    restY: 0.92,
    floaters: floaters,
    bg: 'linear-gradient(180deg,#d6f0ff 0%,#a9dcff 45%,#7cc4ff 100%)',
    onTick: function(t, dt, state){
      cone.rotation.z = Math.sin(t*2)*0.06;
      floaters.forEach(function(f){
        var d = f.def;
        var a = t*d.sp + d.ph;
        f.obj.position.set(
          state.pos.x + Math.cos(a)*d.r,
          state.pos.y + d.y + Math.sin(t*1.6 + d.ph)*0.15,
          state.pos.z + Math.sin(a)*d.r*0.5 - 0.2
        );
        f.obj.rotation.z = a*1.6;
      });
    }
  };
}
