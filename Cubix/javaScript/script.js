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
      timerHint.innerHTML = 'Resolviendo… pulsa <b>espacio</b> parar';
      tick();
    } else {
      running = false;
      cancelAnimationFrame(rafId);
      const elapsed = Date.now() - startTs;
      timeDisplay.classList.remove('running');
      timeDisplay.textContent = fmt(elapsed);
      startBtn.textContent = 'Iniciar';
      timerHint.innerHTML = 'Pulsa <b>espacio</b> o el botón para iniciar la inspección';
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
  