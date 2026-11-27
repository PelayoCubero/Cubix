<aside class="sidebar">
  <div class="brand" onclick="showView('dashboard')">
    <div class="scene"><div class="cube3d">
      <div class="f front"></div><div class="f back"></div><div class="f right"></div>
      <div class="f left"></div><div class="f top"></div><div class="f bottom"></div>
    </div></div>
    <div class="brand-name">Cubix</div>
  </div>

  <nav class="mainnav">
    <button class="navbtn active" data-view="dashboard"><span class="navdot" style="background:var(--white-face)"></span>Inicio</button>
    <button class="navbtn" data-view="timer"><span class="navdot" style="background:var(--yellow-face)"></span>Cronómetro</button>
    <button class="navbtn" data-view="algos"><span class="navdot" style="background:var(--green-face)"></span>Algoritmos</button>
    <button class="navbtn" data-view="estadisticas"><span class="navdot" style="background:var(--red-face)"></span>Estadisticas</button>
    <button class="navbtn" data-view="perfil"><span class="navdot" style="background:var(--blue-face)"></span>Perfil</button>
  </nav>
  <div class="sidebar-footer" onclick="showView('perfil')">
    <div class="mini-avatar"><?php echo htmlspecialchars(mb_strtoupper(mb_substr($_SESSION["nombre"],0,1).mb_substr($_SESSION["apellido"],0,1))) ?></div>
    <div>
      <div class="name"><?php echo htmlspecialchars($_SESSION["nombre"] . " " . $_SESSION["apellido"]) ?>
      </div>
    </div>
  </div>
</aside>
