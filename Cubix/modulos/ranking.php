<?php

function fmtMejor($t) {
    [$h, $m, $s] = explode(':', $t);
    $min = (int)$h * 60 + (int)$m;
    $seg = (float)$s;
    return $min > 0 ? sprintf('%d:%05.2f', $min, $seg) : sprintf('%05.2f', $seg);
}

function htmlRanking(Conexion $c, int $yo): string {

    $sql = "SELECT U.ID_Usuario AS id, U.Nombre AS nom, U.Apellido AS ape, MIN(T.Tiempo) AS mejor FROM Usuario U LEFT JOIN Tiempo T ON T.ID_Usuario = U.ID_Usuario AND T.DNF = 0 WHERE U.ID_Usuario = ? OR U.ID_Usuario IN (SELECT ID_Amigo FROM Amigo WHERE ID_Usuario = ?) GROUP BY U.ID_Usuario, U.Nombre, U.Apellido ORDER BY MIN(T.Tiempo) IS NULL, MIN(T.Tiempo) ASC, U.Nombre, U.Apellido";

    $ranking = $c->ejecutarCosulta($sql, [$yo, $yo]);

    $html = "";
    $pos = 0; $visto = 0; $anterior = null;

    foreach ($ranking as $fila) {
        $visto++;
        $mejor = $fila["mejor"];

        if ($mejor !== null && $mejor !== $anterior) { $pos = $visto; }
        $anterior = $mejor;

        $esYo   = ((int)$fila["id"] === $yo);
        $clases = "rank-row" . ($mejor !== null && $pos <= 3 ? " p$pos" : "") . ($esYo ? " me" : "");
        $numero = $mejor !== null ? $pos : "-";
        $texto  = $mejor !== null ? fmtMejor($mejor) : "--";
        $nombre = htmlspecialchars($fila["nom"] . " " . $fila["ape"]);
        $tag    = $esYo ? "<span class=\"me-tag\">tú</span>" : "";

        $html .= "<div class=\"$clases\"><span class=\"rank-num\">$numero</span><span class=\"rname\">$nombre$tag</span><span class=\"rtime" . ($mejor === null ? " sin" : "") . "\">$texto</span><a href=". $fila["id"]." class=\"btn btn-outline btn-small\">Ver perfil</a></div>";
    }

    if (count($ranking) <= 1) {
        $html .= "<div class=\"hint\">Añade amigos para empezar a competir.</div>";
    }

    return $html;
}
