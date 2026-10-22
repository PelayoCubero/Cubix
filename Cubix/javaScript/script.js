  // ---------- Scramble generator ----------
  const FACES = ['R',"R'",'R2','L',"L'",'L2','U',"U'",'U2','D',"D'",'D2','F',"F'",'F2','B',"B'",'B2'];
  function faceOf(m){ return m[0]; }
  function genScramble(n){
    const seq = []; let last = '';
    while(seq.length < n){
      const m = FACES[Math.floor(Math.random()*FACES.length)];
      if(faceOf(m) === last) continue;
      seq.push(m); last = faceOf(m);
    }
    return seq.join(' ');
  }
  function newScramble(){
    const s = genScramble(20);
    document.getElementById('timerScramble').textContent = s;
  }

// ---------- Navegación entre ventanas ----------
  function showView(id){
    document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    document.querySelectorAll('.navbtn').forEach(b => b.classList.toggle('active', b.dataset.view === id));
  }
  document.querySelectorAll('.navbtn').forEach(btn=>{
    btn.addEventListener('click', ()=> showView(btn.dataset.view), newScramble());
  });

  // ---------- Cronómetro funcional ----------
  let running = false, startTs = 0, rafId = null;
  const timeDisplay = document.getElementById('timeDisplay');
  const startBtn = document.getElementById('startBtn');
  const timerHint = document.getElementById('timerHint');
  let times = [];

  function fmt(ms){ return (ms/1000).toFixed(2); }

  function tick(){
    timeDisplay.textContent = fmt(Date.now() - startTs);
    rafId = requestAnimationFrame(tick);
  }

  function toggleTimer(){
    if(!running){
      running = true;
      startTs = Date.now();
      timeDisplay.classList.add('running');
      startBtn.textContent = 'Detener';
      timerHint.innerHTML = 'Resolviendo… pulsa <b>espacio</b> para detener';
      tick();
    } else {
      running = false;
      cancelAnimationFrame(rafId);
      const elapsed = Date.now() - startTs;
      timeDisplay.classList.remove('running');
      timeDisplay.textContent = fmt(elapsed);
      startBtn.textContent = 'Iniciar';
      timerHint.innerHTML = 'Pulsa <b>espacio</b> o el botón para iniciar';
      registerSolve(elapsed/1000);
      newScramble();
    }
  }

  function registerSolve(t){
    times.unshift(t);
    document.getElementById('countT').textContent = times.length;
    document.getElementById('bestT').textContent = Math.min(...times).toFixed(2);
    document.getElementById('ao5').textContent = avgOf(5);
    document.getElementById('ao12').textContent = avgOf(12);

    const list = document.getElementById('solvesList');
    list.innerHTML = '';
    times.slice(0,8).forEach((val, i)=>{
      const li = document.createElement('li');
      li.innerHTML = `<span class="n">#${times.length - i}</span><span>${val.toFixed(2)}s</span>`;
      list.appendChild(li);
    });
  }

  function avgOf(n){
    if(times.length < n) return '--';
    const sample = times.slice(0, n);
    const sum = sample.reduce((a,b)=>a+b,0);
    return (sum/n).toFixed(2);
  }

  window.addEventListener('keydown', (e)=>{
    if(e.code === 'Space' && document.getElementById('timer').classList.contains('active')){
      e.preventDefault();
      toggleTimer();
    }
  });

  document.querySelectorAll('.session-chips .chip').forEach(chip=>{
    chip.addEventListener('click', ()=>{
      document.querySelectorAll('.session-chips .chip').forEach(c=>c.classList.remove('active'));
      chip.classList.add('active');
    });
  });

  // ---------- Filtros de algoritmos ----------
  document.querySelectorAll('.stage-tabs .stage').forEach(tab=>{
    tab.addEventListener('click', ()=>{
      document.querySelectorAll('.stage-tabs .stage').forEach(t=>t.classList.remove('active'));
      tab.classList.add('active');
      const stage = tab.dataset.stage;
      document.querySelectorAll('.algo-card').forEach(card=>{
        card.classList.toggle('show', card.dataset.stage === stage);
      });
    });
  });

  // ---------- Cube nets (mini rejillas 3x3 decorativas por caso) ----------
  const nets = {
    'net-f2l-1': ['g','g','w','g','w','w','w','w','b'],
    'net-f2l-2': ['w','r','w','w','w','g','g','w','w'],
    'net-oll-1': ['d','d','d','d','y','d','y','d','y'],
    'net-oll-2': ['d','y','d','d','y','d','d','y','d'],
    'net-oll-3': ['y','y','y','y','y','y','d','d','d'],
    'net-pll-1': ['r','r','g','b','y','b','g','r','r'],
    'net-pll-2': ['g','o','b','r','y','r','b','o','g'],
    'net-pll-3': ['b','r','g','o','y','o','g','r','b'],
  };
  const colorMap = { g:'var(--green-face)', w:'var(--white-face)', b:'var(--blue-face)', r:'var(--red-face)', o:'var(--orange-face)', y:'var(--yellow-face)', d:'#33353f' };
  Object.keys(nets).forEach(id=>{
    const el = document.getElementById(id);
    if(!el) return;
    nets[id].forEach(c=>{
      const sq = document.createElement('div');
      sq.style.background = colorMap[c];
      el.appendChild(sq);
    });
  });
  
// ---------- limpiar inputs ----------


(function () {
 
  var PROHIBIDOS = /[()\/\\"<>]/;
 
  function limpiar(el) {
    var original = el.value;
    var limpio = original.replace(PROHIBIDOS, '');
    if (original === limpio) return;
 
    var pos = null;
    try { pos = el.selectionStart; } catch (e) {}
 
    if (pos !== null && pos !== undefined) {
      var nuevaPos = original.slice(0, pos).replace(PROHIBIDOS, '').length;
      el.value = limpio;
      try { el.setSelectionRange(nuevaPos, nuevaPos); } catch (e) {}
    } else {
      el.value = limpio;
    }
  }
 
  var SELECTOR = 'input:not([type=checkbox]):not([type=radio]):not([type=file]):not([type=submit]):not([type=button]), textarea';
 
  document.addEventListener('input', function (e) {
    if (e.target.matches(SELECTOR)) limpiar(e.target);
  });
 
  document.addEventListener('submit', function (e) {
    e.target.querySelectorAll(SELECTOR).forEach(limpiar);
  }, true);
 
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll(SELECTOR).forEach(limpiar);
  });
 
})();
 
// ver contraseña limpia

(function () {

  var OJO = '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>';
  var OJO_TACHADO = '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 19C5 19 1 12 1 12a18.5 18.5 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><path d="M14.12 14.12A3 3 0 1 1 9.88 9.88"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';

  var estilo = document.createElement('style');
  estilo.textContent =
    '.pw-wrap{position:relative;display:block;}' +
    '.pw-wrap input{padding-right:42px !important;}' +
    '.pw-toggle{position:absolute;top:50%;right:8px;transform:translateY(-50%);' +
      'display:flex;align-items:center;justify-content:center;width:30px;height:30px;' +
      'background:none;border:none;border-radius:6px;padding:0;cursor:pointer;' +
      'color:var(--text-faint,#5c5e69);transition:color .15s;}' +
    '.pw-toggle:hover,.pw-toggle:focus-visible{color:var(--text,#eae8e1);outline:none;}' +
    '.pw-toggle.activo{color:var(--yellow-face,#ffd43b);}';
  document.head.appendChild(estilo);

  function añadirOjo(input) {
    if (input.dataset.ojo) return;
    input.dataset.ojo = '1';

    var wrap = document.createElement('span');
    wrap.className = 'pw-wrap';
    input.parentNode.insertBefore(wrap, input);
    wrap.appendChild(input);

    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'pw-toggle';
    btn.innerHTML = OJO;
    btn.setAttribute('aria-label', 'Mostrar contraseña');
    btn.setAttribute('aria-pressed', 'false');
    btn.title = 'Mostrar contraseña';
    wrap.appendChild(btn);

    // Evita que el botón le quite el foco al input
    btn.addEventListener('mousedown', function (e) { e.preventDefault(); });

    btn.addEventListener('click', function () {
      var visible = input.type === 'text';
      input.type = visible ? 'password' : 'text';
      btn.innerHTML = visible ? OJO : OJO_TACHADO;
      btn.classList.toggle('activo', !visible);
      btn.setAttribute('aria-pressed', String(!visible));
      var txt = visible ? 'Mostrar contraseña' : 'Ocultar contraseña';
      btn.setAttribute('aria-label', txt);
      btn.title = txt;
      input.focus();
    });

    // Al enviar el formulario, vuelve a ocultarla por seguridad
    if (input.form) {
      input.form.addEventListener('submit', function () {
        input.type = 'password';
      });
    }
  }

  function iniciar() {
    document.querySelectorAll('input[type=password]').forEach(añadirOjo);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', iniciar);
  } else {
    iniciar();
  }

})();