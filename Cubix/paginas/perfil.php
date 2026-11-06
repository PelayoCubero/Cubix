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

<?php
  $c = new Conexion();
  $c->conectar();

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["anadir_cubo"])) {
      $idCubo = (int)$_POST["anadir_cubo"];
      $existe = $c->ejecutarCosulta("SELECT 1 FROM Cubo WHERE ID_Cubo = ?", [$idCubo]);
      if ($existe) {
          $c->ejecutarCosulta("INSERT IGNORE INTO Usuario_Cubo (ID_Usuario, ID_Cubo) VALUES (?, ?)",[$_SESSION["id_usuario"], $idCubo]);
      }
  }

  // Cubos del catálogo que el usuario aún no tiene
  $catalogo = $c->ejecutarCosulta(
      "SELECT ID_Cubo, Marca, Modelo FROM Cubo
       WHERE ID_Cubo NOT IN (SELECT ID_Cubo FROM Usuario_Cubo WHERE ID_Usuario = ?)
       ORDER BY Marca, Modelo",
      [$_SESSION["id_usuario"]]
  );
?>

<h2>Vitrina de cubos</h2>

<form method="POST" style="display:flex; gap:10px; flex-wrap:wrap; margin:14px 0 20px;">

  <select id="filtroMarca" required style="background:var(--bg-elev-2); color:var(--text); border:1px solid var(--border); border-radius:8px; padding:10px;">
    <option value="">Marca…</option>
    <?php foreach (array_unique(array_column($catalogo, "Marca")) as $m): ?>
      <option value="<?= htmlspecialchars($m) ?>"><?= htmlspecialchars($m) ?></option>
    <?php endforeach; ?>
  </select>

  <select id="filtroModelo" name="anadir_cubo" required disabled style="background:var(--bg-elev-2); color:var(--text); border:1px solid var(--border); border-radius:8px; padding:10px;">
    <option value="">Modelo…</option>
    <?php foreach ($catalogo as $cubo): ?>
      <option value="<?= $cubo["ID_Cubo"] ?>" data-marca="<?= htmlspecialchars($cubo["Marca"]) ?>"><?= htmlspecialchars($cubo["Modelo"]) ?></option>
    <?php endforeach; ?>
  </select>

  <button type="submit" class="btn btn-primary">Añadir cubo</button>
</form>

<script>
(function () {
  var marca  = document.getElementById('filtroMarca');
  var modelo = document.getElementById('filtroModelo');
  var todos  = Array.from(modelo.querySelectorAll('option[data-marca]'));

  marca.addEventListener('change', function () {
    modelo.innerHTML = '<option value="">Modelo…</option>';
    todos.filter(function (o) { return o.dataset.marca === marca.value; })
         .forEach(function (o) { modelo.appendChild(o); });
    modelo.disabled = (marca.value === '');
  });
})();

</script>

<div class="cube-shelf">
  <?php
    $datos = $c->ejecutarCosulta(
        "SELECT C.Marca AS marca, C.Modelo AS modelo
         FROM Usuario_Cubo UC JOIN Cubo C ON C.ID_Cubo = UC.ID_Cubo
         WHERE UC.ID_Usuario = ?",
        [$_SESSION["id_usuario"]]
    );
    $c->desconectar();

    foreach ($datos as $item) {
        $marca = strtolower($item["marca"]);
        echo "<div class=\"cube-item\">
                <div class=\"cube-logo\"><img src=\"./imagenes/$marca.png\"></div>
                <div class=\"cname\">" . $item["marca"]." ".$item["modelo"] . "</div>
              </div>";
    }
  ?>
  <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["anadir_cubo"])): ?>
  <script>
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
    window.addEventListener('load', () => showView('perfil'));
  </script>
<?php endif; ?>
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


          ?>


        </tbody>
      </table>
    </div>
  </section>
