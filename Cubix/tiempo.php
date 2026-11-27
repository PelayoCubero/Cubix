<?php
require_once __DIR__ . '/modulos/seguridad.php';
header('Content-Type: application/json; charset=utf-8');

function responder($ok, $extra = [], $codigo = 200) {
    http_response_code($codigo);
    echo json_encode(array_merge(['ok' => $ok], $extra));
    exit;
}

if (!isset($_SESSION['id_usuario'])) {
    responder(false, ['error' => 'Sesión no iniciada'], 403);
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(false, ['error' => 'Método no permitido'], 405);
}
if (!csrf_valido()) {
    responder(false, ['error' => 'Token de seguridad no válido: recarga la página'], 403);
}

try {

    include("./Conexion/Conexion.php");

    $idUsuario = (int)$_SESSION['id_usuario'];
    $accion = $_POST['accion'] ?? '';

    $c = new Conexion();
    $c->conectar();

    // ---------- Guardar un tiempo nuevo ----------
    if ($accion === 'guardar') {

        $ms     = (int)($_POST['ms'] ?? 0);
        $mezcla = trim($_POST['mezcla'] ?? '');
        $idCubo = (int)($_POST['idcubo'] ?? 0);

        if ($ms <= 0 || $ms > 86400000 || $mezcla === '' || mb_strlen($mezcla) > 100 || !preg_match("/^[RLUDFB'2 ]+$/", $mezcla) || $idCubo <= 0) {
            responder(false, ['error' => 'Datos no válidos'], 400);
        }

        // El cubo tiene que ser del usuario
        $propio = $c->ejecutarCosulta(
            "SELECT 1 FROM Usuario_Cubo WHERE ID_Usuario = ? AND ID_Cubo = ?",
            [$idUsuario, $idCubo]
        );
        if (!$propio) {
            responder(false, ['error' => 'Ese cubo no está en tu vitrina'], 400);
        }

        // ms -> 'HH:MM:SS.cc' (columna TIME(2))
        $cs = (int)round($ms / 10);
        $tiempo = sprintf('%02d:%02d:%02d.%02d',
            intdiv($cs, 360000),
            intdiv($cs % 360000, 6000),
            intdiv($cs % 6000, 100),
            $cs % 100
        );

        $c->ejecutarCosulta(
            "INSERT INTO Tiempo (Tiempo, Fecha, Mezcla, Mas_2, DNF, ID_Usuario, ID_Cubo)
             VALUES (?, CURDATE(), ?, 0, 0, ?, ?)",
            [$tiempo, $mezcla, $idUsuario, $idCubo]
        );

        $ultimo = $c->ejecutarCosulta(
            "SELECT ID_Tiempo AS id FROM Tiempo WHERE ID_Usuario = ? ORDER BY ID_Tiempo DESC LIMIT 1",
            [$idUsuario]
        );
        $c->desconectar();

        responder(true, ['id' => (int)$ultimo[0]['id']]);
    }

    // ---------- +2 / DNF sobre un tiempo ya guardado ----------
    if ($accion === 'penalizar') {

        $id   = (int)($_POST['id'] ?? 0);
        $mas2 = !empty($_POST['mas2']) ? 1 : 0;
        $dnf  = !empty($_POST['dnf']) ? 1 : 0;

        if ($id <= 0) {
            responder(false, ['error' => 'Datos no válidos'], 400);
        }

        $c->ejecutarCosulta(
            "UPDATE Tiempo SET Mas_2 = ?, DNF = ? WHERE ID_Tiempo = ? AND ID_Usuario = ?",
            [$mas2, $dnf, $id, $idUsuario]
        );
        $c->desconectar();

        responder(true);
    }

    // ---------- Eliminar un tiempo (lo usan el perfil y el cronómetro) ----------
    if ($accion === 'eliminar') {

        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            responder(false, ['error' => 'Datos no válidos'], 400);
        }

        // Solo se pueden borrar tiempos propios
        $propio = $c->ejecutarCosulta(
            "SELECT 1 FROM Tiempo WHERE ID_Tiempo = ? AND ID_Usuario = ?",
            [$id, $idUsuario]
        );
        if (!$propio) {
            responder(false, ['error' => 'Ese tiempo no existe'], 404);
        }

        $c->ejecutarCosulta(
            "DELETE FROM Tiempo WHERE ID_Tiempo = ? AND ID_Usuario = ?",
            [$id, $idUsuario]
        );

        // Nuevo récord (sin DNF), con el mismo formato que muestran el perfil y el cronómetro
        $r = $c->ejecutarCosulta(
            "SELECT CASE WHEN T.Tiempo >= '00:01:00'
                         THEN CONCAT(TIME_FORMAT(T.Tiempo, '%i:%S'), '.', LEFT(DATE_FORMAT(T.Tiempo, '%f'), 2))
                         ELSE CONCAT(TIME_FORMAT(T.Tiempo, '%S'), '.', LEFT(DATE_FORMAT(T.Tiempo, '%f'), 2))
                    END AS tiempo
             FROM Tiempo T
             WHERE T.ID_Usuario = ? AND T.DNF = 0
             ORDER BY T.Tiempo ASC
             LIMIT 1",
            [$idUsuario]
        );
        $c->desconectar();

        responder(true, ['record' => $r[0]['tiempo'] ?? '--']);
    }

    // ---------- "Ver más" del historial: siguientes filas ----------
    if ($accion === 'historial') {

        include_once __DIR__ . '/modulos/historial.php';

        $desde  = max(0, (int)($_POST['desde'] ?? 0));
        $pagina = 10;

        $filas = historialTiempos($c, $idUsuario, $desde, $pagina + 1);
        $c->desconectar();

        $html = '';
        foreach (array_slice($filas, 0, $pagina) as $item) {
            $html .= htmlFilaHistorial($item);
        }
        responder(true, ['filas' => $html, 'cantidad' => min(count($filas), $pagina), 'hay_mas' => count($filas) > $pagina]);
    }

    $c->desconectar();
    responder(false, ['error' => 'Acción desconocida'], 400);

} catch (Throwable $e) {
    registrar_error($e);
    responder(false, ['error' => 'Error interno del servidor'], 500);
}
