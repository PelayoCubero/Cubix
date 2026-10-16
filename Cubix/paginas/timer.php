<?php

  $sql = "SELECT CASE WHEN Tiempo.Tiempo >= '00:01:00' THEN CONCAT( TIME_FORMAT(Tiempo.Tiempo, '%i:%S'), '.', LEFT(DATE_FORMAT(Tiempo.Tiempo, '%f'), 2) ) ELSE CONCAT( TIME_FORMAT(Tiempo.Tiempo, '%S'), '.', LEFT(DATE_FORMAT(Tiempo.Tiempo, '%f'), 2) ) END AS tiempo FROM Usuario JOIN Tiempo ON Usuario.ID_Usuario = Tiempo.ID_Usuario JOIN Cubo ON Tiempo.ID_Cubo = Cubo.ID_Cubo WHERE Usuario.ID_Usuario = 1 AND Tiempo.Tiempo = ( SELECT MIN(T2.Tiempo) FROM Tiempo T2 WHERE T2.ID_Usuario = ? );";
  $c = new Conexion();
  $c->conectar();

  $datos = $c->ejecutarCosulta($sql, [$_SESSION["id_usuario"]]);
  $tiempo = NULL;
 
  foreach($datos as $item){

    $tiempo = $item["tiempo"];
      
  }

  $c->desconectar();

  if($tiempo == NULL){$tiempo = 0.0;}

?>

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

        <br><div class="pb-tag">tu record es <b><?php echo $tiempo ?></b> rompelo</div>

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

