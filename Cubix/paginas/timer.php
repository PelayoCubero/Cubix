<!-- TIMER -->
  <section class="view" id="timer">
    <div class="view-header">
      <div class="eyebrow">Cronómetro y sesión</div>
      <h1>Cronómetro</h1>
    </div>

    <div class="timer-layout">
      <div class="card timer-stage">
        <div class="scramble-line" id="timerScramble"></div>
        <div class="time-display" id="timeDisplay">00.00</div>
        <div class="timer-hint" id="timerHint">Pulsa espacio iniciar</div>
        <div class="timer-actions">
          <button class="btn btn-primary" id="startBtn" onclick="toggleTimer()">Iniciar</button>
          <button class="btn btn-outline" onclick="newScramble()">Nueva mezcla</button>
        </div>

        <br><div class="pb-tag">tu record es <b>8.42s</b> rompelo</div>

      </div>

      <div>
        <div class="card" style="margin-bottom:16px;">
          <div class="stat-grid">
            <div class="stat-card"><div class="slabel">Ao5</div><div class="sval" id="ao5">--</div></div>
            <div class="stat-card"><div class="slabel">Ao12</div><div class="sval" id="ao12">--</div></div>
            <div class="stat-card"><div class="slabel">Mejor</div><div class="sval" id="bestT">--</div></div>
            <div class="stat-card"><div class="slabel">Resueltos hoy</div><div class="sval" id="countT">0</div></div>
          </div>

          <div class="spark-wrap">
            <div class="slabel">Progreso · sesión 3x3x3</div>
            <svg viewBox="0 0 260 60" width="100%" height="60" preserveAspectRatio="none">
              <polyline fill="none" stroke="var(--green-face)" stroke-width="2"
                points="0,40 30,44 60,30 90,36 120,22 150,26 180,15 210,20 240,10 260,14" />
            </svg>
          </div>
        </div>

        <div class="card">
          <h2>Últimos resueltos</h2>
          <ul class="solve-list" id="solvesList">
          </ul>
        </div>
      </div>
    </div>
  </section>
