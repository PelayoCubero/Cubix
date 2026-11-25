<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

function responder($ok, $extra = [], $codigo = 200) {
    http_response_code($codigo);
    echo json_encode(array_merge(['ok' => $ok], $extra));
    exit;
}

if (!isset($_SESSION['id_usuario'])) responder(false, ['error' => 'Sesión no iniciada'], 403);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') responder(false, ['error' => 'Método no permitido'], 405);

try {
    include("./Conexion/Conexion.php");

    $yo  = (int)$_SESSION['id_usuario'];
    $id  = (int)($_POST['id'] ?? 0);
    if ($id <= 0) responder(false, ['error' => 'Datos no válidos'], 400);

    $c = new Conexion();
    $c->conectar();

    if (!$c->ejecutarCosulta("SELECT 1 FROM Algoritmo WHERE ID_Algoritmo = ?", [$id])) {
        responder(false, ['error' => 'Ese algoritmo no existe'], 404);
    }

    $ya = $c->ejecutarCosulta(
        "SELECT 1 FROM Usuario_Algoritmo WHERE ID_Usuario = ? AND ID_Algoritmo = ?", [$yo, $id]
    );

    if ($ya) {   
        $c->ejecutarCosulta("DELETE FROM Usuario_Algoritmo WHERE ID_Usuario = ? AND ID_Algoritmo = ?", [$yo, $id]);
        $sabido = false;
    } else {     
        $c->ejecutarCosulta("INSERT INTO Usuario_Algoritmo (ID_Usuario, ID_Algoritmo) VALUES (?, ?)", [$yo, $id]);
        $sabido = true;
    }
    $c->desconectar();

    responder(true, ['sabido' => $sabido]);

} catch (Throwable $e) {
    responder(false, ['error' => $e->getMessage()], 500);
}
