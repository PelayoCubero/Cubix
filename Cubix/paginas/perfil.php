  <?php

  $sql = "SELECT usuario.WCA_ID as wca, CASE WHEN Tiempo.Tiempo >= '00:01:00' THEN CONCAT( TIME_FORMAT(Tiempo.Tiempo, '%i:%S'), '.', LEFT(DATE_FORMAT(Tiempo.Tiempo, '%f'), 2) ) ELSE CONCAT( TIME_FORMAT(Tiempo.Tiempo, '%S'), '.', LEFT(DATE_FORMAT(Tiempo.Tiempo, '%f'), 2) ) END AS tiempo FROM Usuario JOIN Tiempo ON Usuario.ID_Usuario = Tiempo.ID_Usuario JOIN Cubo ON Tiempo.ID_Cubo = Cubo.ID_Cubo WHERE Usuario.ID_Usuario = 1 AND Tiempo.Tiempo = ( SELECT MIN(T2.Tiempo) FROM Tiempo T2 WHERE T2.ID_Usuario = ? );";
  $c = new Conexion();
  $c->conectar();

  $datos = $c->ejecutarCosulta($sql, [$_SESSION["id_usuario"]]);
  $tiempo = NULL;
  $wca = NULL;
 
  foreach($datos as $item){

    $tiempo = $item["tiempo"];
    $wca = $item["wca"];

      
  }

  $c->desconectar();

  if($tiempo == NULL){$tiempo = 0.0;}
  if($wca == NULL){$wca = " ";}
  else {$wca = "WCA ID <a href = \"https://www.worldcubeassociation.org/persons/" . $wca . "\" target=\"_blank\">" . $wca . "</a>";}


?>
  
  <section class="view" id="perfil">
    <div class="view-header">
      <h1>Perfil</h1>
    </div>

    <div class="profile-head">
      <div class="avatar-big"><?php echo $_SESSION["nombre"][0] . $_SESSION["apellido"][0] ?></div>
      <div>
        <div class="pname"><?php echo $_SESSION["nombre"] . " " .$_SESSION["apellido"] ?></div>
        <div class="phandle"><?php echo $wca ?></div>
        <span class="badge-inline"><?php echo $tiempo ?> record</span>
      <a href="logout.php" class="btn btn-outline btn-small">Cerrar sesión</a>

      </div>
    </div>

    <h2>Vitrina de cubos</h2>
    <div class="cube-shelf">
      <div class="cube-item">
        <div class="cube-swatch" style="background:#101114;">
          <div style="background:var(--red-face)"></div><div style="background:#101114"></div><div style="background:var(--blue-face)"></div>
          <div style="background:#101114"></div><div style="background:var(--white-face)"></div><div style="background:#101114"></div>
          <div style="background:var(--green-face)"></div><div style="background:#101114"></div><div style="background:var(--yellow-face)"></div>
        </div>
        <div class="cname">GAN 14 M Pro</div>
        <div class="ctag main">Principal</div>
      </div>
      <div class="cube-item">
        <div class="cube-swatch" style="background:#efe9dc;">
          <div style="background:var(--orange-face)"></div><div style="background:#efe9dc"></div><div style="background:var(--white-face)"></div>
          <div style="background:#efe9dc"></div><div style="background:var(--yellow-face)"></div><div style="background:#efe9dc"></div>
          <div style="background:var(--blue-face)"></div><div style="background:#efe9dc"></div><div style="background:var(--green-face)"></div>
        </div>
        <div class="cname">MoYu WeiLong WRM</div>
      </div>
      <div class="cube-item">
        <div class="cube-swatch" style="background:#101114;">
          <div style="background:var(--green-face)"></div><div style="background:#101114"></div><div style="background:var(--red-face)"></div>
          <div style="background:#101114"></div><div style="background:var(--orange-face)"></div><div style="background:#101114"></div>
          <div style="background:var(--white-face)"></div><div style="background:#101114"></div><div style="background:var(--blue-face)"></div>
        </div>
        <div class="cname">QiYi X-Man Tornado v3</div>
      </div>
    </div>

    

    <h2>Historial tiempos</h2>
    <div class="card">
      <table>
        <thead><tr><th>Mezcla</th><th>Tiempo</th><th>Fecha</th><th>Cubo</th></tr></thead>
        <tbody>

         <?php

            $sql = "SELECT CASE WHEN T.Tiempo >= '00:01:00' THEN CONCAT( TIME_FORMAT(T.Tiempo, '%i:%S'), '.', LEFT(DATE_FORMAT(T.Tiempo, '%f'), 2) ) ELSE CONCAT( TIME_FORMAT(T.Tiempo, '%S'), '.', LEFT(DATE_FORMAT(T.Tiempo, '%f'), 2) ) END AS tiempo, C.Marca AS marca, C.Modelo AS modelo, T.Mezcla AS mezcla, T.Fecha AS fecha FROM Tiempo T JOIN Cubo C ON T.ID_Cubo = C.ID_Cubo WHERE T.ID_Usuario = ? ORDER BY T.Fecha DESC LIMIT 5;";
            $c = new Conexion();
            $c->conectar();

            $datos = $c->ejecutarCosulta($sql, [$_SESSION["id_usuario"]]);
            $tiempo = NULL;
            $wca = NULL;
          
            foreach($datos as $item){

            echo "<tr><td>" .$item["mezcla"]. "</td><td class=\"mono\"> ".$item["tiempo"]. " </td><td>" .$item["fecha"]. "</td><td>" .$item["marca"]." ".$item["modelo"]. "</td></tr>";
                
            }

            $c->desconectar();

            // UPDATE `usuario` SET `Contraseña` = '123456' WHERE `usuario`.`ID_Usuario` = 10;

          ?>


        </tbody>
      </table>
    </div>
  </section>
