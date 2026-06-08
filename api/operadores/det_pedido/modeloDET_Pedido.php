<?php

class modeloDET_Pedido{


    private $ID_DET_PED;	
    private $CANT;	
    private $MONTO;	
    private $MONTO_TOTAL;	
    private $SKU;	
    private $ID_PEDIDO;	



	public function __construct() {

	}


	public function getID_DET_PED() {
		return $this->ID_DET_PED;
	}

	public function setID_DET_PED($value) {
		$this->ID_DET_PED = $value;
	}

	public function getCANT() {
		return $this->CANT;
	}

	public function setCANT($value) {
		$this->CANT = $value;
	}

	public function getMONTO() {
		return $this->MONTO;
	}

	public function setMONTO($value) {
		$this->MONTO = $value;
	}

	public function getMONTO_TOTAL() {
		return $this->MONTO_TOTAL;
	}

	public function setMONTO_TOTAL($value) {
		$this->MONTO_TOTAL = $value;
	}

	public function getSKU() {
		return $this->SKU;
	}

	public function setSKU($value) {
		$this->SKU = $value;
	}

	public function getID_PEDIDO() {
		return $this->ID_PEDIDO;
	}

	public function setID_PEDIDO($value) {
		$this->ID_PEDIDO = $value;
	}



    public function VerDetallesPedidoPorID(){
        $con = new Conexion();
        $db = $con-> getConnection();
        $query ="SELECT  CANT, MONTO, MONTO_TOTAL, SKU, ID_PEDIDO FROM det_pedido WHERE ID_PEDIDO = ?";
        $stmt = $db->prepare($query);
        $this->getCANT();
        $this->getMONTO();
        $this->getMONTO_TOTAL();
        $this->getSKU();
        $this->getID_PEDIDO();
        $stmt->bind_param("i",
        $ID_PEDIDO
        );

        try{
            $rs = $stmt->execute();
            $filas_llamadas = $stmt->affected_rows;
            $stmt->close();
            return($rs && $filas_llamadas > 0);
        }catch (\mysqli_sql_exception $e) {
        	return false;
        	}
        $stmt->close();
    $con->closeConnection();
    return $rs;
    }

}