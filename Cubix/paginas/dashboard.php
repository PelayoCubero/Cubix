<section class="view active" id="dashboard">
<div class="view-header">
    <h1>Buenas, esto se mueve hoy</h1>
</div>

<div class="hero-scramble">
    <div>
        <div class="label">Recuerda, si no practicas no mejoraras, dale caña</div>
    </div>
    <button class="btn btn-primary" onclick="showView('timer')">Empieza a entrenar</button>
</div>

<div class="dash-grid">

    <!-- Columna izquierda -->
    <div class="card">
        <h2>Añadir amigos</h2>
        <input type="text" id="amigoBuscar" placeholder="Busca por ID de usuario…" autocomplete="off" inputmode="numeric" oninput="this.value=this.value.replace(/\D/g,'')" style="width:100%; background:var(--bg-elev-2); color:var(--text); border:1px solid var(--border); border-radius:8px; padding:11px 12px; font-family:var(--font-body); font-size:14.5px; outline:none; margin-bottom:6px;">
        <div id="amigoResultados"></div>
    </div>

    <!-- Columna derecha -->
    <aside>
    <div class="card widget" id="listaAmigos">
        <h2>Amigos - Ultimos registros</h2>

        <?php
        $sql = "SELECT U.Nombre AS nom, U.Apellido AS ape,
                       CASE WHEN T.Tiempo >= '00:01:00'
                            THEN CONCAT(TIME_FORMAT(T.Tiempo, '%i:%S'), '.', LEFT(DATE_FORMAT(T.Tiempo, '%f'), 2))
                            ELSE CONCAT(TIME_FORMAT(T.Tiempo, '%S'), '.', LEFT(DATE_FORMAT(T.Tiempo, '%f'), 2))
                       END AS tiempo
                FROM Amigo A
                JOIN Usuario U ON A.ID_Amigo = U.ID_Usuario
                LEFT JOIN Tiempo T ON T.ID_Usuario = U.ID_Usuario
                     AND T.ID_Tiempo = (SELECT MAX(T2.ID_Tiempo) FROM Tiempo T2 WHERE T2.ID_Usuario = U.ID_Usuario)
                WHERE A.ID_Usuario = ?";
        $c = new Conexion();
        $c->conectar();
        $datos = $c->ejecutarCosulta($sql, [$_SESSION["id_usuario"]]);
        $c->desconectar();

        foreach ($datos as $item) {

            $tiempo = $item["tiempo"] ?? "0.0";

            echo "<div class=\"rank-row\"><span class=\"rank-num\"></span><span class=\"rname\"> " . htmlspecialchars($item["nom"] . " " . $item["ape"]) . "</span><span class=\"rtime\">" . htmlspecialchars($tiempo) . "</span></div>";

        }
        ?>

    </div>
    </aside>

</div>
</section>