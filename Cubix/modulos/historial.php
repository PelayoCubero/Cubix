<?php

function historialTiempos(Conexion $c, int $idUsuario, int $desde, int $limite): array {
    $desde  = max(0, $desde);      
    $limite = max(1, $limite);
    return $c->ejecutarCosulta(
        "SELECT CASE WHEN T.Tiempo >= '00:01:00'
                     THEN CONCAT(TIME_FORMAT(T.Tiempo, '%i:%S'), '.', LEFT(DATE_FORMAT(T.Tiempo, '%f'), 2))
                     ELSE CONCAT(TIME_FORMAT(T.Tiempo, '%S'), '.', LEFT(DATE_FORMAT(T.Tiempo, '%f'), 2))
                END AS tiempo,
                C.Marca AS marca, C.Modelo AS modelo, T.Mezcla AS mezcla, T.Fecha AS fecha
         FROM Tiempo T JOIN Cubo C ON T.ID_Cubo = C.ID_Cubo
         WHERE T.ID_Usuario = ?
         ORDER BY T.Fecha DESC, T.ID_Tiempo DESC
         LIMIT $limite OFFSET $desde",
        [$idUsuario]
    );
}

function htmlFilaHistorial(array $item): string {
    return '<tr><td>' . htmlspecialchars($item['mezcla']) . '</td>'
         . '<td class="mono"> ' . htmlspecialchars($item['tiempo']) . '</td>'
         . '<td>' . htmlspecialchars($item['fecha']) . '</td>'
         . '<td>' . htmlspecialchars($item['marca'] . ' ' . $item['modelo']) . '</td></tr>';
}
