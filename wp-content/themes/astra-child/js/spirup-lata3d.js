/**
 * SPIRUP · Lata 3D (Citrus Blue) para la portada.
 *
 * Versión embebida del visor original (spir-up-3d.html): se monta dentro de un
 * contenedor (#spirup-lata3d), con fondo TRANSPARENTE y giro automático, para
 * encajar en el hueco central de la sección "Una lata con ciencia adentro".
 *
 * Requiere: three.js (r128) cargado antes, y window.SPIRUP_LATA.wrapUrl con la
 * URL de la textura del envoltorio.
 */
(function () {
  if (typeof THREE === 'undefined') {
    console.warn('SPIRUP: three.js no está cargado.');
    return;
  }

  var stage = document.getElementById('spirup-lata3d');
  if (!stage) return;

  var WRAP = (window.SPIRUP_LATA && window.SPIRUP_LATA.wrapUrl) || '';
  if (!WRAP) {
    console.warn('SPIRUP: falta la URL de la textura (SPIRUP_LATA.wrapUrl).');
    return;
  }

  function cw() { return stage.clientWidth || 1; }
  function ch() { return stage.clientHeight || 1; }

  /* ---------- escena / cámara / render ---------- */
  var scene = new THREE.Scene();
  var camera = new THREE.PerspectiveCamera(30, cw() / ch(), 0.1, 100);
  camera.position.set(0, 0.15, 13.2);

  var renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
  renderer.setPixelRatio(Math.min(devicePixelRatio, 2));
  renderer.setSize(cw(), ch());
  renderer.setClearColor(0x000000, 0); // transparente
  stage.appendChild(renderer.domElement);
  var el = renderer.domElement;
  el.style.display = 'block';
  el.style.width = '100%';
  el.style.height = '100%';
  el.style.touchAction = 'none';

  /* ---------- iluminación: plana y pareja, sin brillos duros ---------- */
  scene.add(new THREE.AmbientLight(0xffffff, 0.92));

  var key = new THREE.DirectionalLight(0xffffff, 0.26);
  key.position.set(-3, 4, 7);
  scene.add(key);

  var fill = new THREE.DirectionalLight(0xdff4f5, 0.20);
  fill.position.set(5, 0, 5);
  scene.add(fill);

  var edge = new THREE.DirectionalLight(0xcdeef0, 0.16);
  edge.position.set(0, 1, -6);
  scene.add(edge);

  /* ---------- la lata ----------
     canLean = inclinacion fija (como en la foto de referencia).
     can     = grupo que gira sobre su eje vertical (dentro de canLean), asi la
               lata se ve inclinada pero rota en su sitio sin bambolearse. */
  var canLean = new THREE.Group();
  canLean.rotation.z = -0.17;  // inclinacion lateral (top hacia la derecha)
  canLean.rotation.x = 0.05;   // leve inclinacion hacia atras
  scene.add(canLean);

  var can = new THREE.Group();
  canLean.add(can);

  var R = 1.06;   // radio del cuerpo (mas esbelta, tipo sleek 355ml)
  var H = 4.5;    // altura del cuerpo (proporción 355 ml)
  var SEG = 128;

  var texLoader = new THREE.TextureLoader();
  var wrapTex = texLoader.load(WRAP);
  wrapTex.wrapS = THREE.RepeatWrapping;
  wrapTex.wrapT = THREE.ClampToEdgeWrapping;
  wrapTex.anisotropy = renderer.capabilities.getMaxAnisotropy();
  wrapTex.offset.x = 0.43; // centra el panel frontal SPIR UP hacia la camara

  /* cuerpo */
  var body = new THREE.Mesh(
    new THREE.CylinderGeometry(R, R, H, SEG, 1, true),
    new THREE.MeshPhongMaterial({
      map: wrapTex,
      shininess: 6,
      specular: 0x16343a,
      side: THREE.DoubleSide
    })
  );
  can.add(body);

  /* ---- materiales: hombro/base en CIAN (combinan con el cuerpo) + aro plateado fino ---- */
  // hombro y banda inferior: cian claro que se funde con la etiqueta (no blanco)
  var aluLight = new THREE.MeshPhongMaterial({ color: 0xc6e9ec, shininess: 16, specular: 0x7fb9bf, side: THREE.DoubleSide });
  // tapa (recesada): cian un poco mas oscuro
  var aluMid   = new THREE.MeshPhongMaterial({ color: 0xaddade, shininess: 14, specular: 0x7fb9bf });
  // aro/corte plateado fino (arriba y abajo): el unico metal visible
  var aluDark  = new THREE.MeshPhongMaterial({ color: 0xacb5ba, shininess: 65, specular: 0xffffff });

  /* ---- arriba: hombro plateado + borde ---- */
  var topY = H / 2;
  var shoulderH = 0.40;
  var neckR = R * 0.72;

  var shoulderPts = [];
  var SSTEPS = 24;
  for (var i = 0; i <= SSTEPS; i++) {
    var t = i / SSTEPS;
    var rr = R + (neckR - R) * (t * t * (3 - 2 * t)); // smoothstep
    shoulderPts.push(new THREE.Vector2(rr, t * shoulderH));
  }
  // hombro metalico (borde plateado arriba)
  var shoulder = new THREE.Mesh(new THREE.LatheGeometry(shoulderPts, SEG), aluLight);
  shoulder.position.y = topY;
  can.add(shoulder);

  // aro plateado del borde superior (el "corte" de arriba)
  var rimRing = new THREE.Mesh(new THREE.TorusGeometry(neckR, 0.028, 14, SEG), aluDark);
  rimRing.rotation.x = Math.PI / 2;
  rimRing.position.y = topY + shoulderH;
  can.add(rimRing);

  // tapa
  var lid = new THREE.Mesh(new THREE.CircleGeometry(neckR, SEG), aluMid);
  lid.rotation.x = -Math.PI / 2;
  lid.position.y = topY + shoulderH;
  can.add(lid);

  /* ---- abajo: estrechamiento plateado + base (el "corte" de abajo) ---- */
  var botH = 0.30;
  var botR = R * 0.88;
  // banda plateada inferior
  var bottom = new THREE.Mesh(
    new THREE.CylinderGeometry(R, botR, botH, SEG, 1, true),
    aluLight
  );
  bottom.position.y = -topY - botH / 2;
  can.add(bottom);

  // aro/corte plateado de la base
  var baseRing = new THREE.Mesh(new THREE.TorusGeometry(botR, 0.05, 14, SEG), aluDark);
  baseRing.rotation.x = Math.PI / 2;
  baseRing.position.y = -topY - botH;
  can.add(baseRing);

  // fondo concavo de la base
  var baseCap = new THREE.Mesh(new THREE.CircleGeometry(botR * 0.92, SEG), aluDark);
  baseCap.rotation.x = Math.PI / 2;
  baseCap.position.y = -topY - botH - 0.02;
  can.add(baseCap);

  /* ---- sombra de contacto ---- */
  (function () {
    var c = document.createElement('canvas');
    c.width = c.height = 256;
    var g = c.getContext('2d');
    var grad = g.createRadialGradient(128, 128, 4, 128, 128, 124);
    grad.addColorStop(0, 'rgba(6,42,51,0.42)');
    grad.addColorStop(0.55, 'rgba(6,42,51,0.16)');
    grad.addColorStop(1, 'rgba(6,42,51,0)');
    g.fillStyle = grad; g.fillRect(0, 0, 256, 256);
    var sh = new THREE.Mesh(
      new THREE.PlaneGeometry(5.4, 5.4),
      new THREE.MeshBasicMaterial({ map: new THREE.CanvasTexture(c), transparent: true, depthWrite: false })
    );
    sh.rotation.x = -Math.PI / 2;
    sh.position.y = -topY - botH - 0.06;
    sh.scale.set(1, 0.55, 1);
    scene.add(sh);
  })();

  /* la inclinacion la aplica canLean; este grupo solo gira sobre Y */

  /* ---------- burbujas ---------- */
  var bubbleTex = (function () {
    var s = 128, c = document.createElement('canvas');
    c.width = c.height = s;
    var g = c.getContext('2d');
    var grad = g.createRadialGradient(s * 0.5, s * 0.5, s * 0.10, s * 0.5, s * 0.5, s * 0.50);
    grad.addColorStop(0.00, 'rgba(255,255,255,0.30)');
    grad.addColorStop(0.55, 'rgba(226,250,252,0.26)');
    grad.addColorStop(0.82, 'rgba(255,255,255,0.72)');
    grad.addColorStop(0.95, 'rgba(255,255,255,1.00)');
    grad.addColorStop(1.00, 'rgba(255,255,255,0)');
    g.fillStyle = grad;
    g.beginPath(); g.arc(s / 2, s / 2, s / 2, 0, Math.PI * 2); g.fill();
    var hl = g.createRadialGradient(s * 0.36, s * 0.32, 0, s * 0.36, s * 0.32, s * 0.15);
    hl.addColorStop(0, 'rgba(255,255,255,0.98)');
    hl.addColorStop(1, 'rgba(255,255,255,0)');
    g.fillStyle = hl;
    g.beginPath(); g.arc(s * 0.36, s * 0.32, s * 0.15, 0, Math.PI * 2); g.fill();
    return new THREE.CanvasTexture(c);
  })();

  var MAX_B = 340;
  var bGeo = new THREE.BufferGeometry();
  var bPos = new Float32Array(MAX_B * 3);
  var bSize = new Float32Array(MAX_B);
  var bAlpha = new Float32Array(MAX_B);
  bGeo.setAttribute('position', new THREE.BufferAttribute(bPos, 3));
  bGeo.setAttribute('size', new THREE.BufferAttribute(bSize, 1));
  bGeo.setAttribute('alpha', new THREE.BufferAttribute(bAlpha, 1));

  var bMat = new THREE.ShaderMaterial({
    uniforms: {
      map: { value: bubbleTex },
      uOpacity: { value: 0 },
      uScale: { value: ch() * 0.5 }
    },
    vertexShader: [
      'attribute float size;',
      'attribute float alpha;',
      'varying float vAlpha;',
      'uniform float uScale;',
      'void main(){',
      '  vAlpha = alpha;',
      '  vec4 mv = modelViewMatrix * vec4(position, 1.0);',
      '  gl_PointSize = size * uScale / max(-mv.z, 0.001);',
      '  gl_Position = projectionMatrix * mv;',
      '}'
    ].join('\n'),
    fragmentShader: [
      'uniform sampler2D map;',
      'uniform float uOpacity;',
      'varying float vAlpha;',
      'void main(){',
      '  vec4 t = texture2D(map, gl_PointCoord);',
      '  gl_FragColor = vec4(t.rgb, t.a * vAlpha * uOpacity);',
      '  if (gl_FragColor.a < 0.01) discard;',
      '}'
    ].join('\n'),
    transparent: true,
    depthWrite: false
  });

  var bubbles = new THREE.Points(bGeo, bMat);
  scene.add(bubbles);

  var bData = [];
  for (var j = 0; j < MAX_B; j++) bData.push({ alive: false, x: 0, y: 0, z: 0, vy: 0, drift: 0, ph: 0, r: 0.1, fade: 0 });

  function spawn() {
    for (var k = 0; k < bData.length; k++) {
      var b = bData[k];
      if (b.alive) continue;
      var a = Math.random() * Math.PI * 2;
      var rad = R * (0.90 + Math.random() * 0.62);
      b.x = Math.cos(a) * rad;
      b.z = Math.sin(a) * rad;
      b.y = -H / 2 - 0.5 + Math.random() * 1.0;
      b.vy = 1.1 + Math.random() * 1.9;
      b.drift = (Math.random() - 0.5) * 0.9;
      b.ph = Math.random() * Math.PI * 2;
      b.r = 0.085 + Math.random() * 0.20;
      b.fade = 0;
      b.alive = true;
      return;
    }
  }

  /* ---------- interacción ---------- */
  var autoSpin = false;         // sin giro automatico: queda de frente (SPIR UP)
  var hovering = false;
  var spinVel = 0;              // se puede girar arrastrando con el cursor
  var dragging = false;
  var lastX = 0, momentum = 0;
  var bubbleAmt = 0;

  function setHover(on) {
    if (hovering === on) return;
    hovering = on;
    autoSpin = !on;
  }

  el.addEventListener('pointerenter', function () { setHover(true); });
  el.addEventListener('pointerleave', function () { if (!dragging) setHover(false); });

  /* Rotacion BLOQUEADA: la lata queda siempre de frente (SPIR UP), nunca muestra
     el reverso (informacion nutricional). Solo reacciona el hover (burbujas). */

  /* ---------- loop ---------- */
  var clock = new THREE.Clock();

  function frame() {
    var dt = Math.min(clock.getDelta(), 0.05);
    var t = clock.elapsedTime;

    if (!dragging) {
      if (Math.abs(momentum) > 0.0002) {
        can.rotation.y += momentum;
        momentum *= 0.94;
      } else if (autoSpin) {
        can.rotation.y += spinVel * (dt * 60);
      }
    }

    can.position.y = Math.sin(t * 0.8) * 0.035;

    var target = hovering ? 1 : 0;
    bubbleAmt += (target - bubbleAmt) * Math.min(1, dt * 4.5);

    if (bubbleAmt > 0.02) {
      var rate = 78 * bubbleAmt;
      var budget = rate * dt;
      while (budget > 0) {
        if (budget >= 1 || Math.random() < budget) spawn();
        budget -= 1;
      }
    }

    var topOut = H / 2 + 1.6;
    var n = 0;
    for (var k = 0; k < bData.length; k++) {
      var b = bData[k];
      if (!b.alive) continue;
      b.y += b.vy * dt;
      if (b.y > topOut) { b.alive = false; continue; }
      b.fade = Math.min(1, b.fade + dt * 5);
      var nearTop = Math.min(1, Math.max(0, (topOut - b.y) / 1.1));
      var sway = Math.sin(t * 1.7 + b.ph) * 0.13;
      bPos[n * 3] = b.x + sway * b.drift;
      bPos[n * 3 + 1] = b.y;
      bPos[n * 3 + 2] = b.z + Math.cos(t * 1.3 + b.ph) * 0.09 * b.drift;
      bSize[n] = b.r;
      bAlpha[n] = b.fade * nearTop;
      n++;
    }
    bGeo.setDrawRange(0, n);
    bGeo.attributes.position.needsUpdate = true;
    bGeo.attributes.size.needsUpdate = true;
    bGeo.attributes.alpha.needsUpdate = true;
    bMat.uniforms.uOpacity.value = 0.95 * bubbleAmt;

    renderer.render(scene, camera);
    requestAnimationFrame(frame);
  }
  frame();

  /* ---------- responsive ---------- */
  function resize() {
    var w = cw(), h = ch();
    camera.aspect = w / h;
    var base = 13.2;
    var k = w < 620 ? Math.min(1.55, 620 / Math.max(w, 320)) : 1;
    camera.position.z = base * k;
    camera.updateProjectionMatrix();
    renderer.setSize(w, h);
    bMat.uniforms.uScale.value = h * 0.5;
  }
  addEventListener('resize', resize);
  if (typeof ResizeObserver !== 'undefined') {
    new ResizeObserver(resize).observe(stage);
  }
  resize();

  /* movimiento reducido */
  if (matchMedia('(prefers-reduced-motion: reduce)').matches) {
    spinVel = 0;
    autoSpin = false;
  }
})();
