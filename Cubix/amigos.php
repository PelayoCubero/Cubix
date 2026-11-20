<?php
session_start();
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

try {

    include("./Conexion/Conexion.php");
    include_once __DIR__ . '/modulos/ranking.php';

    $yo = (int)$_SESSION['id_usuario'];
    $accion = $_POST['accion'] ?? '';

    $c = new Conexion();
    $c->conectar();

    // ---------- Buscar por ID un usuario que aún no es amigo ----------
    if ($accion === 'buscar') {

        $q = trim($_POST['q'] ?? '');

        if ($q === '') {
            $usuarios = [];
        } elseif (ctype_digit($q) && strlen($q) <= 10) {
            $usuarios = $c->ejecutarCosulta(
                "SELECT U.ID_Usuario AS id, U.Nombre AS nom, U.Apellido AS ape
                 FROM Usuario U
                 WHERE U.ID_Usuario = ?
                   AND U.ID_Usuario <> ?
                   AND U.ID_Usuario NOT IN (SELECT ID_Amigo FROM Amigo WHERE ID_Usuario = ?)",
                [(int)$q, $yo, $yo]
            );
        } else {
            $usuarios = [];
        }
        $c->desconectar();

        $salida = [];
        foreach ($usuarios as $u) {
            $salida[] = ['id' => (int)$u['id'], 'nom' => $u['nom'], 'ape' => $u['ape']];
        }
        responder(true, ['usuarios' => $salida]);
    }

    // ---------- Añadir un amigo ----------
    if ($accion === 'anadir') {

        $idAmigo = (int)($_POST['id'] ?? 0);

        if ($idAmigo <= 0 || $idAmigo === $yo) {
            responder(false, ['error' => 'Usuario no válido'], 400);
        }

        $existe = $c->ejecutarCosulta("SELECT 1 FROM Usuario WHERE ID_Usuario = ?", [$idAmigo]);
        if (!$existe) {
            responder(false, ['error' => 'Ese usuario no existe'], 400);
        }

        $c->ejecutarCosulta(
            "INSERT IGNORE INTO Amigo (ID_Usuario, ID_Amigo) VALUES (?, ?)",
            [$yo, $idAmigo]
        );

        $ranking = htmlRanking($c, $yo);
        $c->desconectar();

        responder(true, ['ranking' => $ranking]);
    }

    $c->desconectar();
    responder(false, ['error' => 'Acción desconocida'], 400);

} catch (Throwable $e) {
    responder(false, ['error' => $e->getMessage()], 500);
}
