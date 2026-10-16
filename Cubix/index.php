<?php
session_start();

if (isset($_SESSION['id_usuario'])) {
    header('Location: main.php');
    exit;
}

$error = '';
$correo = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($correo === '' || $password === '') {
        $error = 'Rellena el correo y la contraseña.';
    } else {
        include("./Conexion/Conexion.php");

        $c = new Conexion();
        $c->conectar();
        $res = $c->ejecutarCosulta(
            "SELECT ID_Usuario AS id, Nombre AS nom, Apellido AS ape, Contraseña AS pass FROM Usuario WHERE Correo = ?",
            [$correo]
        );
        $c->desconectar();

        if (!empty($res) && hash_equals((string)$res[0]['pass'], $password)) {
            session_regenerate_id(true);
            $_SESSION['id_usuario'] = (int)$res[0]['id'];
            $_SESSION['nombre'] = $res[0]['nom'];
            $_SESSION['apellido'] = $res[0]['ape'];
            header('Location: main.php');
            exit;
        }

        $error = 'Correo o contraseña incorrectos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<?php include("modulos/head.php"); ?>
<style>
  body{ align-items:center; justify-content:center; padding:24px; }

</style>
</head>
<body>

<form class="login-card" method="POST" action="index.php" autocomplete="on">
  <div class="login-logo">
    <img src="modulos/logo.png" alt="Cubix">
    <div class="brand-name">Cubix</div>
    <div class="login-sub">Inicia sesión para seguir mejorando tus tiempos</div>
  </div>

  <?php if ($error !== ''): ?>
    <div class="login-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="field">
    <label for="correo">Correo</label>
    <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($correo) ?>" required autofocus>
  </div>

  <div class="field">
    <label for="password">Contraseña</label>
    <input type="password" id="password" name="password" required>
  </div>

  <button type="submit" class="btn btn-primary">Entrar</button>

  <div style="text-align:center; font-size:13px; color:var(--text-dim); margin-top:18px;">
    ¿No tienes cuenta? <a href="registro.php" style="color:var(--yellow-face); font-weight:600;">Regístrate</a>
  </div>
</form>

</body>
</html>
