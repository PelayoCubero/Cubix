<?php

    require_once __DIR__ . '/modulos/seguridad.php';

    if (!isset($_SESSION['id_usuario'])) {
        header('Location: index.php');
        exit;
    }

?>
<!DOCTYPE html>
<html lang="es">
  <head>

    <?php include ("modulos/head.php");?>
    <?php include ("./Conexion/Conexion.php");?>


  </head>
  <body>

    <?php include ("modulos/navbar.php");?>

    <main>

      <?php 
      
        include ("paginas/dashboard.php");
        include ("paginas/timer.php");
        include ("paginas/algoritmos.php");
        include ("paginas/estadisticas.php");
        include ("paginas/perfil.php");
      
      ?>

    
    </main>

    <script src="javaScript/seguridad.js"></script>
    <script src="javaScript/script.js"></script>
    <script src="javaScript/amigos.js"></script>
    <script src="javaScript/guardar_tiempo.js"></script>
    <script src="javaScript/eliminar_tiempo.js"></script>
    <script src="javaScript/algoritmos.js"></script>

  </body>
</html>