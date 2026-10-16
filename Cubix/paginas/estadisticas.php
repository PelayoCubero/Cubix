 <section class="view" id="estadisticas">
    <div class="view-header">
      <h1>Estadisticas</h1>
    </div>

  

    <h2>Tabla de clasificación</h2>

    </div>
    <div class="card">
      <table>
        <thead><tr><th>WCA</th><th>Cubero</th><th>Mezcla</th><th>Mejor tiempo</th><th>Cubo</th></tr></thead>
        <tbody>

        <?php
        
          $sql = "SELECT U.WCA_ID AS wca, U.Nombre AS nom, U.Apellido AS ape, T.Mezcla AS scr, CASE WHEN T.Tiempo >= '00:01:00' THEN CONCAT( TIME_FORMAT(T.Tiempo, '%i:%S'), '.', LEFT(DATE_FORMAT(T.Tiempo, '%f'), 2) ) ELSE CONCAT( TIME_FORMAT(T.Tiempo, '%S'), '.', LEFT(DATE_FORMAT(T.Tiempo, '%f'), 2) ) END AS tiempo, C.Marca AS marca, C.Modelo AS modelo FROM Amigo A JOIN Usuario U ON A.ID_Amigo = U.ID_Usuario JOIN Tiempo T ON U.ID_Usuario = T.ID_Usuario JOIN Cubo C ON T.ID_Cubo = C.ID_Cubo WHERE A.ID_Usuario = ? AND T.Tiempo = ( SELECT MIN(T2.Tiempo) FROM Tiempo T2 WHERE T2.ID_Usuario = U.ID_Usuario ) ORDER BY T.Tiempo ASC;";
          $c = new Conexion();
          $c->conectar();
          $datos = $c->ejecutarCosulta($sql, [$_SESSION["id_usuario"]]);

          foreach($datos as $item){

            $datosWCA = "";

            if($item["wca"] == NULL){$datosWCA = "No disponible";}

            else{$datosWCA = "<a href = \"https://www.worldcubeassociation.org/persons/".$item["wca"]."\" target=\"_blank\">Ver perfil</a>";}
      
            echo "<tr><td>". $datosWCA ."</td><td>".$item["nom"]." ".$item["ape"]."</td><td>". $item["scr"] ."</td><td class=\"mono\">". $item["tiempo"] ."</td><td>".$item["marca"]." ".$item["modelo"]."</td></tr>";
              
          }

          $c->desconectar();

        ?>
         
        </tbody>
      </table>
    </div>
  </section>
