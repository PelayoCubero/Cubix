 <section class="view" id="estadisticas">
    <div class="view-header">
      <h1>Estadisticas</h1>
    </div>
    
     <h2>Historial tiempos propios</h2>
    <div class="card">
      <table>
        <thead><tr><th>Mezcla</th><th>Tiempo</th><th>Fecha</th><th>Cubo</th></tr></thead>
        <tbody>

         <?php
            include_once __DIR__ . '/../modulos/historial.php';

            $c = new Conexion();
            $c->conectar();
            $filas = historialTiempos($c, (int)$_SESSION["id_usuario"], 0, 6);   // 6 para saber si hay más
            $c->desconectar();

            $hayMas = count($filas) > 5;
            foreach (array_slice($filas, 0, 5) as $item) {
              echo htmlFilaHistorial($item);
            }

            if (!$filas) {
              echo "<tr><td colspan=\"4\" class=\"vacio\">Aún no tienes tiempos guardados.</td></tr>";
            }
          ?>

        </tbody>
      </table>
      <div style="text-align:center; margin-top:14px;">
        <button type="button" class="btn btn-outline btn-small" id="verMasTiempos" data-desde="5"<?php echo $hayMas ? '' : ' hidden' ?>>Ver más</button>
      </div>
    </div>
    <br><br><br>

    <h2>Mejores tiempos de mis amigos</h2>

    </div>
    <div class="card">
      <table>
        <thead><tr><th>WCA</th><th>Amigo</th><th>Mezcla</th><th>Mejor tiempo</th><th>Cubo</th></tr></thead>

        <?php
        
          $sql = "SELECT U.WCA_ID AS wca, U.Nombre AS nom, U.Apellido AS ape, T.Mezcla AS scr, CASE WHEN T.Tiempo >= '00:01:00' THEN CONCAT( TIME_FORMAT(T.Tiempo, '%i:%S'), '.', LEFT(DATE_FORMAT(T.Tiempo, '%f'), 2) ) ELSE CONCAT( TIME_FORMAT(T.Tiempo, '%S'), '.', LEFT(DATE_FORMAT(T.Tiempo, '%f'), 2) ) END AS tiempo, C.Marca AS marca, C.Modelo AS modelo FROM Amigo A JOIN Usuario U ON A.ID_Amigo = U.ID_Usuario JOIN Tiempo T ON T.ID_Tiempo = ( SELECT T2.ID_Tiempo FROM Tiempo T2 WHERE T2.ID_Usuario = U.ID_Usuario AND T2.DNF = 0 ORDER BY T2.Tiempo ASC, T2.ID_Tiempo ASC LIMIT 1 ) JOIN Cubo C ON T.ID_Cubo = C.ID_Cubo WHERE A.ID_Usuario = ? ORDER BY T.Tiempo ASC;";
          $c = new Conexion();
          $c->conectar();
          $datos = $c->ejecutarCosulta($sql, [$_SESSION["id_usuario"]]);

          foreach($datos as $item){

            $datosWCA = "";

            if($item["wca"] == NULL){$datosWCA = "No disponible";}

            else{$datosWCA = "<a href = \"https://www.worldcubeassociation.org/persons/".rawurlencode($item["wca"])."\" target=\"_blank\">Ver perfil</a>";}
      
            echo "<tr><td>". $datosWCA ."</td><td>" . htmlspecialchars($item["nom"]." ".$item["ape"]) . "</td><td>" . htmlspecialchars($item["scr"]) . "</td><td class=\"mono\">" . htmlspecialchars($item["tiempo"]) . "</td><td>" . htmlspecialchars($item["marca"]." ".$item["modelo"]) . "</td></tr>";
              
          }

          $c->desconectar();
            
          if (!$datos) {
              echo "<tr><td colspan=\"5\" class=\"vacio\">Aún no hay tiempos guardados.</td></tr>";
            }

        ?>
         
      </table>

      
    </div>
  </section>
