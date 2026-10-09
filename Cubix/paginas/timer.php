  <section class="view" id="timer">
    <div class="view-header">
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
        </div>

        <div class="card">
          <h2>ultima sesión</h2>
          <ul class="solve-list" id="solvesList">
          </ul>
        </div>
      </div>
    </div>

  </section>
