<?php

session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

include("./Conexion/Conexion.php");


$id = (int)($_GET['id'] ?? 0);

$c = new Conexion();
$c->conectar();

$u = $id > 0 ? $c->ejecutarCosulta("SELECT Nombre AS nom, Apellido AS ape, WCA_ID AS wca FROM Usuario WHERE ID_Usuario = ?", [$id]) : [];

if (!$u) {
    $c->desconectar();
    http_response_code(404);
}

$base = htmlspecialchars(rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/');
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <base href="<?= $base ?>">
    <?php include("modulos/head.php"); ?>
  </head>
  <body>
    <main style="margin:0 auto; max-width:900px;">
<?php if (!$u): ?>

      <section class="view active">
        <div class="view-header"><h1>Usuario no encontrado</h1></div>
        <p>No existe ningún usuario con ID <?= (int)$id ?>.</p><br>
        <a href="main.php" class="btn btn-outline btn-small">Volver a Cubix</a>
      </section>

<?php else:
    $nom = $u[0]['nom']; $ape = $u[0]['ape']; $wcaId = trim((string)$u[0]['wca']);

    $r = $c->ejecutarCosulta(
        "SELECT CASE WHEN T.Tiempo >= '00:01:00'
                     THEN CONCAT(TIME_FORMAT(T.Tiempo, '%i:%S'), '.', LEFT(DATE_FORMAT(T.Tiempo, '%f'), 2))
                     ELSE CONCAT(TIME_FORMAT(T.Tiempo, '%S'), '.', LEFT(DATE_FORMAT(T.Tiempo, '%f'), 2))
                END AS tiempo
         FROM Tiempo T WHERE T.ID_Usuario = ? AND T.DNF = 0 ORDER BY T.Tiempo ASC LIMIT 1", [$id]);
    $record = $r[0]['tiempo'] ?? '--';

    $nAlgos = (int)$c->ejecutarCosulta(
        "SELECT COUNT(*) AS n FROM Usuario_Algoritmo WHERE ID_Usuario = ?", [$id])[0]['n'];

    $cubos = $c->ejecutarCosulta(
        "SELECT C.Marca AS marca, C.Modelo AS modelo
         FROM Usuario_Cubo UC JOIN Cubo C ON C.ID_Cubo = UC.ID_Cubo WHERE UC.ID_Usuario = ?", [$id]);

    $tiempos = $c->ejecutarCosulta(
        "SELECT CASE WHEN T.Tiempo >= '00:01:00'
                     THEN CONCAT(TIME_FORMAT(T.Tiempo, '%i:%S'), '.', LEFT(DATE_FORMAT(T.Tiempo, '%f'), 2))
                     ELSE CONCAT(TIME_FORMAT(T.Tiempo, '%S'), '.', LEFT(DATE_FORMAT(T.Tiempo, '%f'), 2))
                END AS tiempo, T.DNF AS dnf, T.Fecha AS fecha, C.Marca AS marca, C.Modelo AS modelo
         FROM Tiempo T JOIN Cubo C ON T.ID_Cubo = C.ID_Cubo
         WHERE T.ID_Usuario = ? ORDER BY T.Fecha DESC, T.ID_Tiempo DESC LIMIT 5", [$id]);
    $c->desconectar();
?>
      <section class="view active" id="perfil">
        <div class="view-header">
          <a href="main.php" class="btn btn-outline btn-small">← Volver</a> <br><br>
          <h1>Perfil</h1>
        </div>

        <div class="profile-head">
          <div class="avatar-big"><?= htmlspecialchars(strtoupper(mb_substr($nom, 0, 1) . mb_substr($ape, 0, 1))) ?></div>
          <div>
            <div class="pname"><?= htmlspecialchars($nom . ' ' . $ape) ?></div>
            <div class="phandle">
              ID <?= $id ?>
              <?php if ($wcaId !== ''): ?>
                · WCA ID <a href="https://www.worldcubeassociation.org/persons/<?= rawurlencode($wcaId) ?>" target="_blank"><?= htmlspecialchars($wcaId) ?></a>
              <?php endif; ?>
            </div>
            <span class="badge-inline"><?= htmlspecialchars($record) ?> record</span>
            <span class="badge-inline"><?= $nAlgos ?> algoritmos sabidos</span>
          </div>
        </div>

        <h2>Vitrina de cubos</h2>
        <div class="cube-shelf">
          <?php foreach ($cubos as $item): $marca = strtolower($item['marca']); ?>
            <div class="cube-item">
              <div class="cube-logo"><img src="imagenes/<?= htmlspecialchars($marca) ?>.png" alt=""></div>
              <div class="cname"><?= htmlspecialchars($item['marca'] . ' ' . $item['modelo']) ?></div>
            </div>
          <?php endforeach; ?>
          <?php if (!$cubos): ?><p style="color:var(--text-dim)">Aún no tiene cubos.</p><?php endif; ?>
        </div>

        <h2>Últimos tiempos</h2>
        <div class="card">
          <table>
            <thead><tr><th>Tiempo</th><th>Fecha</th><th>Cubo</th></tr></thead>
            <tbody>
              <?php foreach ($tiempos as $t): ?>
                <tr>
                  <td class="mono"><?= $t['dnf'] ? 'DNF' : htmlspecialchars($t['tiempo']) ?></td>
                  <td><?= htmlspecialchars($t['fecha']) ?></td>
                  <td><?= htmlspecialchars($t['marca'] . ' ' . $t['modelo']) ?></td>
                </tr>
              <?php endforeach; ?>
              <?php if (!$tiempos): ?><tr><td colspan="3" class="vacio">Aún no tiene tiempos guardados.</td></tr><?php endif; ?>
            </tbody>
          </table>
        </div>
        <br><br>
      </section>
<?php endif; ?>
    </main>
  </body>
</html>
