<?php

    class Conexion{

        private string $dsn;
        private string $usuario;
        private string $passwd;
        private PDO $conexion;


        public function __construct($ruta = "Conexion/secure/config.php") {
        
            $datos = require $ruta;
            
            $this->dsn = $datos["dsn"];
            $this->usuario = $datos["usuario"];
            $this->passwd = $datos["password"];
        
        }

        public function conectar(){

            try{

                $this->conexion = new PDO($this->dsn, $this->usuario, $this->passwd);
            
            }catch(Exception $e){

                echo $e->getMessage(); 

            }
        }

        public function ejecutarCosulta($sql, $parametros = []) {

            $stament = $this->conexion->prepare($sql);

            $stament->execute($parametros);

            return $stament->fetchAll();

        }



    }


?>
