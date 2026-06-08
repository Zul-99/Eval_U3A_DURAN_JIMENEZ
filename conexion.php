<?php
class Conexion {
        private $connection;
        private $host;
        private $username;
        private $password;
        private $db;
        private $port;
        private $server;
    //que no se usen las variables en otras clases


    //Constructor para generar la conexion
    public function __construct(){
        $this -> server = $_SERVER['SERVER_NAME'];
        $this -> connection = null;
        $this -> host = '127.0.0.1'; //localhost basicamente
        $this -> port = '3306';
        $this -> db = 'camiseta';
        $this -> username = 'root';
        $this -> password = '';
    }

    //Funcion para llamar la conexion 
        public function getConnection(){
            try{
                $this-> connection = 
                mysqli_connect($this-> host, $this ->username, $this ->password, $this ->db, $this ->port);
                mysqli_set_charset($this ->connection, 'utf8');
                if(!$this ->connection){
                    echo 'Error de conexion en la BD';
                }//Verificamos si conecta la base de datos
                return $this ->connection;
            }catch (Exception $e){ //Excepcion en caso de emergencia
                error_log($e-> getMessage());
                die ('Error al conectar a la BD por el tryCatch');
            }
        }

    public function closeConnection(){
        if(!$this -> connection){
        myqsli_close($this -> connection);
        echo 'Conexion fallida';
        }
    }

}
?>