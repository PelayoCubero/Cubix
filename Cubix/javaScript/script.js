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

  // times[i] = tiempo real medido (es lo que se guarda en la base de datos).
  // pens[i]  = penalización de ese intento: 'ninguna' | 'mas2' | 'dnf'.
  let pens = [];

  // Tiempo que cuenta de verdad: +2 suma 2 segundos y DNF no cuenta (Infinity).
  function valor(i){
    if (pens[i] === 'dnf')  return Infinity;
    if (pens[i] === 'mas2') return times[i] + 2;
    return times[i];
  }

  function texto(i){
    if (pens[i] === 'dnf')  return 'DNF';
    if (pens[i] === 'mas2') return (times[i] + 2).toFixed(2) + '+';
    return times[i].toFixed(2);
  }

  function registerSolve(t){
    times.unshift(t);
    pens.unshift('ninguna');
    refrescarSesion();
  }

  // Lo llama guardar_tiempo.js cuando se pulsa +2 / DNF sobre el último tiempo
  window.setPenalizacion = function (tipo){
    if (!times.length) return;
    pens[0] = tipo;            // 'mas2' | 'dnf' | 'ninguna'
    refrescarSesion();
  };

  function refrescarSesion(){
    const validos = times.map((_, i) => valor(i)).filter(v => v !== Infinity);
    document.getElementById('countT').textContent = times.length;
    document.getElementById('bestT').textContent = validos.length ? Math.min(...validos).toFixed(2) : '--';
    document.getElementById('ao5').textContent = avgOf(5);
    document.getElementById('ao12').textContent = avgOf(12);

    const list = document.getElementById('solvesList');
    list.innerHTML = '';
    times.slice(0,12).forEach((_, i)=>{
      const li = document.createElement('li');
      li.innerHTML = `<span class="n">#${times.length - i}</span><span>${texto(i)}${pens[i] === 'dnf' ? '' : 's'}</span>`;
      list.appendChild(li);
    });
  }

  // Media de n: se descartan el mejor y el peor (el DNF cuenta como el peor).
  // Con dos o más DNF en la muestra, el peor descartado es un DNF y queda otro dentro: media DNF.
  function avgOf(n){
    if(times.length < n) return '--';
    const muestra = [];
    for (let i = 0; i < n; i++) muestra.push(valor(i));
    muestra.sort((a, b) => a - b);          // Infinity (DNF) queda al final
    const centro = muestra.slice(1, n - 1); // quita el mejor y el peor
    if (centro.includes(Infinity)) return 'DNF';
    return (centro.reduce((a, b) => a + b, 0) / centro.length).toFixed(2);
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

// ---------- Estadísticas: "Ver más" del historial ----------
(function () {
  var btn = document.getElementById('verMasTiempos');
  if (!btn) return;
  var cuerpo = btn.closest('.card').querySelector('tbody');
  var cargando = false;

  btn.addEventListener('click', function () {
    if (cargando) return;
    cargando = true;
    btn.disabled = true;
    btn.textContent = 'Cargando…';

    fetch('tiempo.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ accion: 'historial', desde: btn.dataset.desde })
    })
      .then(function (r) { return r.json(); })
      .then(function (r) {
        if (!r.ok) throw new Error(r.error || 'error desconocido');
        cuerpo.insertAdjacentHTML('beforeend', r.filas);   // HTML ya escapado en el servidor
        btn.dataset.desde = parseInt(btn.dataset.desde, 10) + r.cantidad;
        if (!r.hay_mas) btn.hidden = true;
      })
      .catch(function (e) { alert('No se pudo cargar más: ' + e.message); })
      .then(function () { cargando = false; btn.disabled = false; btn.textContent = 'Ver más'; });
  });
})();
