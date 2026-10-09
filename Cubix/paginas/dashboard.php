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
    <div class="card">
    <h2>Timeline de progresos</h2>

    <div class="feed-item">
        <div class="avatar" style="background:var(--green-face)">LC</div>
        <div class="feed-body">
        <div class="who"><b>Lucía Campos</b> rompió su récord personal en 3x3x3</div>
        <div class="pb-tag"><b>8.42s</b> single · antes 9.01s</div>
        </div>
    </div>

    <div class="feed-item">
        <div class="avatar" style="background:var(--orange-face)">DP</div>
        <div class="feed-body">
        <div class="who"><b>Diego Paz</b> añadió un cubo a su colección</div>
        <div class="pb-tag" style="color:var(--text-dim)">GAN 14 M Pro</div>
        </div>
    </div>

    <div class="feed-item">
        <div class="avatar" style="background:var(--blue-face)">NR</div>
        <div class="feed-body">
        <div class="who"><b>Noa Reyes</b> bajó su Ao12 de OH por primera vez a sub-20</div>
        <div class="pb-tag"><b>19.87s</b> Ao12 · One-Handed</div>
        </div>
    </div>

    <div class="feed-item">
        <div class="avatar" style="background:var(--red-face)">JM</div>
        <div class="feed-body">
        <div class="who"><b>Jorge Mena</b> comentó en el grupo de Hardware</div>
        <div class="when">"Con lubricante de silicona el GAN 12 vuela en corte."</div>
        </div>
    </div>
    </div>

    <aside>
    <div class="card widget">
        <h2>Amigos</h2>

        <?php
        $sql = "SELECT Usuario.Nombre as nom, Usuario.Apellido as ape FROM Amigo JOIN Usuario ON Amigo.ID_Amigo = Usuario.ID_Usuario WHERE Amigo.ID_Usuario = 1;";
        $c = new Conexion();
        $c->conectar();
        $datos = $c->ejecutarCosulta($sql);

        foreach($datos as $item){
    
            echo "<div class=\"rank-row\"><span class=\"rank-num\"></span><span class=\"rname\"> " . $item["nom"] . " " . $item["ape"] . "</span><span class=\"rtime\">0.0</span></div>";
            
        }

        ?>

    </div>

    
    </aside>
</div>
</section>
