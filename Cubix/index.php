<?php
require_once __DIR__ . '/modulos/seguridad.php';

if (isset($_SESSION['id_usuario'])) {
    header('Location: main.php');
    exit;
}

$error = '';
$correo = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!csrf_valido()) {
        $error = 'La sesión ha caducado. Vuelve a intentarlo.';
    } elseif (($_SESSION['bloqueo_hasta'] ?? 0) > time()) {
        $error = 'Demasiados intentos. Espera ' . ($_SESSION['bloqueo_hasta'] - time()) . ' segundos.';
    } elseif ($correo === '' || $password === '') {
        $error = 'Rellena el correo y la contraseña.';
    } else {
        include("./Conexion/Conexion.php");

        $c = new Conexion();
        $c->conectar();
        $res = $c->ejecutarCosulta("SELECT ID_Usuario AS id, Nombre AS nom, Apellido AS ape, Contraseña AS pass FROM Usuario WHERE Correo = ?",[$correo]);

        $valida = false;
        if (!empty($res)) {
            $guardada = (string)$res[0]['pass'];
            if (preg_match('/^\$(2y|argon2)/', $guardada)) {
                $valida = password_verify($password, $guardada);
            } elseif (hash_equals($guardada, $password)) {
                // Contraseña antigua guardada en claro: entra y se guarda ya cifrada
                $valida = true;
                $c->ejecutarCosulta("UPDATE Usuario SET Contraseña = ? WHERE ID_Usuario = ?",
                    [password_hash($password, PASSWORD_DEFAULT), (int)$res[0]['id']]);
            }
        }
        $c->desconectar();

        if ($valida) {
            session_regenerate_id(true);
            unset($_SESSION['intentos'], $_SESSION['bloqueo_hasta']);
            $_SESSION['id_usuario'] = (int)$res[0]['id'];
            $_SESSION['nombre'] = $res[0]['nom'];
            $_SESSION['apellido'] = $res[0]['ape'];
            header('Location: main.php');
            exit;
        }

        // Freno sencillo: 5 fallos seguidos -> 60 segundos de espera
        $_SESSION['intentos'] = ($_SESSION['intentos'] ?? 0) + 1;
        if ($_SESSION['intentos'] >= 5) {
            $_SESSION['bloqueo_hasta'] = time() + 60;
            $_SESSION['intentos'] = 0;
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
    <?= csrf_campo() ?>
    <div class="login-logo">
      <img src="imagenes/logo.png" alt="Cubix">
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

  <script src="javaScript/seguridad.js"></script>
  <script src="javaScript/script.js"></script>

  </body>
</html>
