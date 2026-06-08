<?php

// ~ Modelo Camiseta:

class modeloCamiseta {

    //variables  ~ Tabla Camiseta:

    private $sku;
    private $titulo;
    private $club;
    private $pais;
    private $tipo;
    private $color;
    private $precio;
    private $tallas;
    private $detalles;
    private $rut_cliente; // Usado solo en getByCliente

    //constructor:

    public function __construct() {}

    //gets & setters:

    public function getSku() {
        return $this -> sku;        
    }

    public function setSku($value) {
        $this -> sku = $value;
    }

    public function getClub() {
        return $this -> club;
    }

    public function setClub($value) {
        $this -> club = $value;
    }

    public function getColor() {
        return $this -> color;
    }

    public function setColor($value) {
        $this -> color = $value;
    }

    public function getTitulo() {
        return $this -> titulo;
    }

    public function setTitulo($value) {
        $this -> titulo = $value;
    }

    public function getPais() {
        return $this -> pais;
    }

    public function setPais($value) {
        $this -> pais = $value;
    }

    public function getTipo() {
        return $this -> tipo;
    }

    public function setTipo($value) {
        $this -> tipo = $value;
    }

    public function getPrecio() {
        return $this -> precio;
    }

    public function setPrecio($value) {
        $this -> precio = $value;
    }

    public function getTallas() {
        return $this -> tallas;
    }

    public function setTallas($value) {
        $this -> tallas = $value;
    }

    public function getDetalles() {
        return $this -> detalles;
    }

    public function setDetalles($value) {
        $this -> detalles = $value;
    }

    public function getRutCliente() {
        return $this -> rut_cliente;
    }

    public function setRutCliente() {
        $this -> rut_cliente = $value;
    }

    //metodo ~ getAll camisetas:

    public function getAll() {

        $con = new Conexion();

        $query = "SELECT SKU, TITULO, CLUB, PAIS, TIPO, COLOR, PRECIO, TALLAS, DETALLES 
        FROM camiseta
        ORDER BY SKU ASC";

        $stmt = mysqli_prepare($con -> getConnection(), $query);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        $camisetas = [];
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $camisetas[] = $fila;
        }

        mysqli_stmt_close($stmt);
        $con -> closeConnection();
        return $camisetas;
    }

    //metodo ~ GetByCliente - camiseta x cliente

    public function getByCliente($_camiseta) {

        $con = new Conexion();
        $rut = $_camiseta->getRutCliente();

        $query = "SELECT DISTINCT
                    c.SKU,
                    c.TITULO,
                    c.CLUB,
                    c.PAIS,
                    c.TIPO,
                    c.COLOR,
                    c.PRECIO,
                    c.TALLAS,
                    c.DETALLES
                FROM camiseta c
                INNER JOIN det_pedido dp ON dp.SKU = c.SKU
                INNER JOIN pedido p ON p.ID_PEDIDO = dp.ID_PEDIDO
                INNER JOIN cliente cl ON cl.RUT = p.RUT
                WHERE cl.RUT = ?
                ORDER BY c.SKU ASC";

        $stmt = mysqli_prepare($con->getConnection(), $query);
        mysqli_stmt_bind_param($stmt, 'i', $rut);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        $camisetas = [];
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $camisetas[] = $fila;
        }

        mysqli_stmt_close($stmt);
        $con->closeConnection();
        return $camisetas;
    }

    //metodo ~ POST - Agregar nueva camiseta

    public function add(modeloCamiseta $_nuevo) {

        $con = new Conexion();

        $sku = $_nuevo->getSku();
        $titulo = $_nuevo->getTitulo();
        $club = $_nuevo->getClub();
        $pais = $_nuevo->getPais();
        $tipo = $_nuevo->getTipo();
        $color = $_nuevo->getColor();
        $precio = $_nuevo->getPrecio();
        $tallas = $_nuevo->getTallas();
        $detalles = $_nuevo->getDetalles();

        $query = "INSERT INTO camiseta (SKU, TITULO, CLUB, PAIS, TIPO, COLOR, PRECIO, TALLAS, DETALLES)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($con->getConnection(), $query);
        mysqli_stmt_bind_param(
            $stmt, 'isssssiss',
            $sku,
            $titulo,
            $club,
            $pais,
            $tipo,
            $color,
            $precio,
            $tallas,
            $detalles
        );

        $rs = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $con->closeConnection();
        return $rs ? true : false;
    }

    //metodo ~ PUT - Actualizar datos de camiseta

    public function update(modeloCamiseta $_nuevo) {
        $con = new Conexion();
 
        $titulo   = $_nuevo->getTitulo();
        $club     = $_nuevo->getClub();
        $pais     = $_nuevo->getPais();
        $tipo     = $_nuevo->getTipo();
        $color    = $_nuevo->getColor();
        $precio   = $_nuevo->getPrecio();
        $tallas   = $_nuevo->getTallas();
        $detalles = $_nuevo->getDetalles();
        $sku      = $_nuevo->getSku();
 
        $query = "UPDATE camiseta SET
                    TITULO   = ?,
                    CLUB     = ?,
                    PAIS     = ?,
                    TIPO     = ?,
                    COLOR    = ?,
                    PRECIO   = ?,
                    TALLAS   = ?,
                    DETALLES = ?
                  WHERE SKU  = ?";
 
        $stmt = mysqli_prepare($con->getConnection(), $query);
        mysqli_stmt_bind_param(
            $stmt, 'sssssissi',
            $titulo,
            $club,
            $pais,
            $tipo,
            $color,
            $precio,
            $tallas,
            $detalles,
            $sku
        );
 
        $rs = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $con->closeConnection();
        return $rs ? true : false;
    }

    //metodo ~ DELETE - Elimina camiseta por SKU

    public function delete(modeloCamiseta $_camiseta) {

        $con = new Conexion();
        $sku = $_camiseta->getSku();
 
        // Validacion: no eliminar si tiene pedidos asociados
        $queryCheck = "SELECT COUNT(*) as cnt FROM det_pedido WHERE SKU = ?";
        $stmtCheck  = mysqli_prepare($con->getConnection(), $queryCheck);
        mysqli_stmt_bind_param($stmtCheck, 'i', $sku);
        mysqli_stmt_execute($stmtCheck);
        $resultado = mysqli_stmt_get_result($stmtCheck);
        $fila      = mysqli_fetch_assoc($resultado);
        mysqli_stmt_close($stmtCheck);
 
        if ($fila['cnt'] > 0) {
            $con->closeConnection();
            return false; // El controller responderá 409
        }
 
        // Si no tiene pedidos, se elimina
        $query = "DELETE FROM camiseta WHERE SKU = ?";
        $stmt  = mysqli_prepare($con->getConnection(), $query);
        mysqli_stmt_bind_param($stmt, 'i', $sku);
        $rs = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $con->closeConnection();
        return $rs ? true : false;
    }
}