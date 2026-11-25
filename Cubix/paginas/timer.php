<?php

  // Mejor tiempo (sin contar DNF), con el mismo criterio que el perfil y el borrado de tiempos
  $sql = "SELECT CASE WHEN T.Tiempo >= '00:01:00' THEN CONCAT( TIME_FORMAT(T.Tiempo, '%i:%S'), '.', LEFT(DATE_FORMAT(T.Tiempo, '%f'), 2) ) ELSE CONCAT( TIME_FORMAT(T.Tiempo, '%S'), '.', LEFT(DATE_FORMAT(T.Tiempo, '%f'), 2) ) END AS tiempo FROM Tiempo T WHERE T.ID_Usuario = ? AND T.DNF = 0 ORDER BY T.Tiempo ASC LIMIT 1;";
  $c = new Conexion();
  $c->conectar();

  $datos = $c->ejecutarCosulta($sql, [$_SESSION["id_usuario"]]);
  $tiempo = NULL;
 
  foreach($datos as $item){

    $tiempo = $item["tiempo"];
      
  }

  $c->desconectar();

  if($tiempo == NULL){$tiempo = "--";}

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
          
          <button class="btn btn-outline" id="btnMas2">+2</button>
          <button class="btn btn-outline" id="btnDNF">DNF</button>

        </div>
        <br>
          <div class="pb-tag" style= "font-size:15px;"><b id="recordT"><?php echo $tiempo ?></b> record</div>
          <br><br>
          <h2>ultima sesión</h2>
          <ul class="solve-list" id="solvesList"></ul>
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

        <div class="card" style ="display: flex; justify-content : center;">

          <button class="btn btn-primary" onclick="showView('estadisticas')">ver todos mis tiempos</button>

          
        </div>

        
        <div class="card" style ="justify-content:center;">

          <h2>Elige tu cubo</h2>
      
          <?php
            $c = new Conexion();
            $c->conectar();
            $datos = $c->ejecutarCosulta(
                "SELECT C.Marca AS marca, C.Modelo AS modelo, C.id_Cubo as idc
                FROM Usuario_Cubo UC JOIN Cubo C ON C.ID_Cubo = UC.ID_Cubo
                WHERE UC.ID_Usuario = ?",
                [$_SESSION["id_usuario"]]
            );
             echo  "<select id=\"selCubo\" style = \"width: 100%;\">";
            foreach ($datos as $item) {    

              echo "<option value = ".$item["idc"].">" .$item["marca"]." ".$item["modelo"]. "</option>";
            
            }

            echo  "</select>";
            
            $c->desconectar();

          ?>

<br>
          <br>
          <div class="timer-hint" id="timerHint">Actualiza para ver todos tus cubos</div>

          
        </div>
      </div>
    </div>
<br>
  </section>

