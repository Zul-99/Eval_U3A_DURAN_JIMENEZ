<?php

// ~ Modelo Cliente:

class modeloCliente {

    //Variables ~ Tabla Cliente

    private $rut;
    private $nombre;
    private $direccion;
    private $categoria;
    private $contacto;
    private $por_of;

    //Constructor

    public function __construct() {}

    //Gets & Setters:

    public function getRut() {
        return $this -> rut;
    }

    public function setRut($value) {
        $this -> rut = $value;
    }

    public function getNombre() {
        return $this -> nombre;
    }

    public function setNombre($value) {
        $this -> nombre = $value;
    }

    public function getDireccion() {
        return $this -> direccion;
    }

    public function setDireccion($value) {
        $this -> direccion = $value;
    }

    public function getCategoria() {
        return $this -> categoria;
    }

    public function setCategoria($value) {
        $this -> categoria = $value;
    }

    public function getContacto() {
        return $this -> contacto;
    }

    public function setContacto($value) {
        $this -> contacto = $value;
    }

    public function getProOf() {
        return $this -> pro_of;
    }

    public function setProOf($value) {
        $this -> pro_of = $value;
    }

    //metodo ~ getAll clientes:

    public function getAll() {
        $con = new Conexion();
 
        $query = "SELECT RUT, NOMBRE, DIRECCION, CATEGORIA, CONTACTO, PRO_OF
                  FROM cliente
                  ORDER BY RUT ASC";
 
        $stmt = mysqli_prepare($con->getConnection(), $query);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
 
        $clientes = [];
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $clientes[] = $fila;
        }
 
        mysqli_stmt_close($stmt);
        $con->closeConnection();
        return $clientes;
    }
    
    //metodo ~ POST - Listar nuevos clientes:

    public function add(modeloCliente $_nuevo) {
        $con = new Conexion();
 
        $rut       = $_nuevo->getRut();
        $nombre    = $_nuevo->getNombre();
        $direccion = $_nuevo->getDireccion();
        $categoria = $_nuevo->getCategoria();
        $contacto  = $_nuevo->getContacto();
        $pro_of    = $_nuevo->getProOf();
 
        $query = "INSERT INTO cliente (RUT, NOMBRE, DIRECCION, CATEGORIA, CONTACTO, PRO_OF)
                  VALUES (?, ?, ?, ?, ?, ?)";
 
        // RUT(i), NOMBRE(s), DIRECCION(s), CATEGORIA(s), CONTACTO(s), PRO_OF(i)
        $stmt = mysqli_prepare($con->getConnection(), $query);
        mysqli_stmt_bind_param(
            $stmt, 'issssi', // <- el bind nos permite esto
            $rut,
            $nombre,
            $direccion,
            $categoria,
            $contacto,
            $pro_of
        );
 
        $rs = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $con->closeConnection();
        return $rs ? true : false;
    }

    //metodo ~ PUT - Actualizar clientes existentes:

        public function update(modeloCliente $_nuevo) {
        $con = new Conexion();
 
        $nombre    = $_nuevo->getNombre();
        $direccion = $_nuevo->getDireccion();
        $categoria = $_nuevo->getCategoria();
        $contacto  = $_nuevo->getContacto();
        $pro_of    = $_nuevo->getProOf();
        $rut       = $_nuevo->getRut();
 
        $query = "UPDATE cliente SET
                    NOMBRE    = ?,
                    DIRECCION = ?,
                    CATEGORIA = ?,
                    CONTACTO  = ?,
                    PRO_OF    = ?
                  WHERE RUT   = ?";
 
        // NOMBRE(s), DIRECCION(s), CATEGORIA(s), CONTACTO(s), PRO_OF(i), RUT(i)
        $stmt = mysqli_prepare($con->getConnection(), $query);
        mysqli_stmt_bind_param(
            $stmt, 'ssssii',
            $nombre,
            $direccion,
            $categoria,
            $contacto,
            $pro_of,
            $rut
        );
 
        $rs = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $con->closeConnection();
        return $rs ? true : false;
    }

    //Metodo ~ DELETE - elimina cliente x rut:

    public function delete(modeloCliente $_cliente) {
        $con = new Conexion();
        $rut = $_cliente->getRut();
 
        // Validacion: no eliminar si tiene pedidos asociados
        $queryCheck = "SELECT COUNT(*) as cnt FROM pedido WHERE RUT = ?";
        $stmtCheck  = mysqli_prepare($con->getConnection(), $queryCheck);
        mysqli_stmt_bind_param($stmtCheck, 'i', $rut);
        mysqli_stmt_execute($stmtCheck);
        $resultado = mysqli_stmt_get_result($stmtCheck);
        $fila      = mysqli_fetch_assoc($resultado);
        mysqli_stmt_close($stmtCheck);
 
        if ($fila['cnt'] > 0) {
            $con->closeConnection();
            return false; // El controller responderá 409
        }
 
        $query = "DELETE FROM cliente WHERE RUT = ?";
        $stmt  = mysqli_prepare($con->getConnection(), $query);
        mysqli_stmt_bind_param($stmt, 'i', $rut);
        $rs = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $con->closeConnection();
        return $rs ? true : false;
    }



}