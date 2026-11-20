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

    <div>

    <div class="card widget">
        <h2>Añadir amigos</h2>
        <input type="text" id="amigoBuscar" placeholder="Busca por ID de usuario…" autocomplete="off" inputmode="numeric" oninput="this.value=this.value.replace(/\D/g,'')" style="width:100%; background:var(--bg-elev-2); color:var(--text); border:1px solid var(--border); border-radius:8px; padding:11px 12px; font-family:var(--font-body); font-size:14.5px; outline:none; margin-bottom:6px;">
        <div id="amigoResultados"></div>
    </div>

    </div>

    <aside>

    <div class="card widget ranking" id="rankingAmigos">
        <h2>Ranking de tiempos de tus amigos</h2>

        <div id="rankingLista">
        <?php
        include_once __DIR__ . '/../modulos/ranking.php';

        $c = new Conexion();
        $c->conectar();
        echo htmlRanking($c, (int)$_SESSION["id_usuario"]);
        $c->desconectar();
        ?>
        </div>

    </div>

    </aside>

</div>
</section>
