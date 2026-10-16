<?php
session_start();

if (isset($_SESSION['id_usuario'])) {
    header('Location: main.php');
    exit;
}

$error = '';
$nombre = $apellido = $correo = $wca = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $correo   = trim($_POST['correo'] ?? '');
    $wca      = strtoupper(trim($_POST['wca'] ?? ''));
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($nombre === '' || $apellido === '' || $correo === '' || $password === '') {
        $error = 'Rellena todos los campos obligatorios.';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo no es válido.';
    } elseif (mb_strlen($nombre) > 50 || mb_strlen($apellido) > 50 || mb_strlen($correo) > 100) {
        $error = 'Algún campo es demasiado largo.';
    } elseif (mb_strlen($password) < 6 || mb_strlen($password) > 50) {
        $error = 'La contraseña debe tener entre 6 y 50 caracteres.';
    } elseif ($password !== $password2) {
        $error = 'Las contraseñas no coinciden.';
    } elseif ($wca !== '' && !preg_match('/^\d{4}[A-Z]{4}\d{2}$/', $wca)) {
        $error = 'El WCA ID no tiene un formato válido (ej: 2019GARC01).';
    } else {
        include("./Conexion/Conexion.php");

        $c = new Conexion();
        $c->conectar();

        $existe = $c->ejecutarCosulta("SELECT ID_Usuario FROM Usuario WHERE Correo = ?", [$correo]);

        if (!empty($existe)) {
            $error = 'Ya existe una cuenta con ese correo.';
        } else {
            $c->ejecutarCosulta(
                "INSERT INTO Usuario (Nombre, Apellido, Correo, Contraseña, WCA_ID) VALUES (?, ?, ?, ?, ?)",
                [$nombre, $apellido, $correo, $password, $wca === '' ? null : $wca]
            );

            $nuevo = $c->ejecutarCosulta("SELECT ID_Usuario AS id FROM Usuario WHERE Correo = ?", [$correo]);
            $c->desconectar();

            session_regenerate_id(true);
            $_SESSION['id_usuario'] = (int)$nuevo[0]['id'];
            $_SESSION['nombre'] = $nombre;
            $_SESSION['apellido'] = $apellido;
            header('Location: main.php');
            exit;
        }

        $c->desconectar();
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

<form class="login-card" method="POST" action="registro.php" autocomplete="on">
  <div class="login-logo">
    <img src="modulos/logo.png" alt="Cubix">
    <div class="brand-name">Cubix</div>
    <div class="login-sub">Crea tu cuenta y empieza a registrar tus tiempos</div>
  </div>

  <?php if ($error !== ''): ?>
    <div class="login-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="row2">
    <div class="field">
      <label for="nombre">Nombre</label>
      <input type="text" id="nombre" name="nombre" maxlength="50" value="<?= htmlspecialchars($nombre) ?>" required autofocus>
    </div>
    <div class="field">
      <label for="apellido">Apellido</label>
      <input type="text" id="apellido" name="apellido" maxlength="50" value="<?= htmlspecialchars($apellido) ?>" required>
    </div>
  </div>

  <div class="field">
    <label for="correo">Correo</label>
    <input type="email" id="correo" name="correo" maxlength="100" value="<?= htmlspecialchars($correo) ?>" required>
  </div>

  <div class="field">
    <label for="wca">WCA ID <span class="opt">(opcional)</span></label>
    <input type="text" id="wca" name="wca" maxlength="10" value="<?= htmlspecialchars($wca) ?>">
  </div>

  <div class="row2">
    <div class="field">
      <label for="password">Contraseña</label>
      <input type="password" id="password" name="password" minlength="6" maxlength="50" required>
    </div>
    <div class="field">
      <label for="password2">Repetir</label>
      <input type="password" id="password2" name="password2" minlength="6" maxlength="50" required>
    </div>
  </div>

  <button type="submit" class="btn btn-primary">Crear cuenta</button>

  <div class="alt-link">¿Ya tienes cuenta? <a href="index.php">Inicia sesión</a></div>
</form>

</body>
</html>
