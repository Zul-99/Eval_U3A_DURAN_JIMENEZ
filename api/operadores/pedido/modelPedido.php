<?php

class modelPedido{


//PEDIDO
    private $ID_PEDIDO;
    private $FECHA;
    private $MONTO;
    //CLIENTE
    private $RUT; 


//DET_PEDIDO
    private $ID_DET_PED;	
    private $CANT;	
    private $MONTO_TOTAL;	
    private $SKU;	





	public function __construct() {
	}

	public function getID_PEDIDO() {
		return $this->ID_PEDIDO;
	}

	public function setID_PEDIDO($value) {
		$this->ID_PEDIDO = $value;
	}

	public function getFECHA() {
		return $this->FECHA;
	}

	public function setFECHA($value) {
		$this->FECHA = $value;
	}

	public function getMONTO() {
		return $this->MONTO;
	}

	public function setMONTO($value) {
		$this->MONTO = $value;
	}

	public function getRUT() {
		return $this->RUT;
	}

	public function setRUT($value) {
		$this->RUT = $value;
	}

    //DET PEDIDO
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



    public function llamarPedidoPorID($ID_PEDIDO){
        $con = new Conexion();
        $db = $con->getConnection();
        $query = "SELECT p.ID_PEDIDO, p.FECHA, p.RUT,
                         COALESCE(SUM(dp.MONTO_TOTAL), 0) AS MONTO
                  FROM pedido p
                  LEFT JOIN det_pedido dp ON p.ID_PEDIDO = dp.ID_PEDIDO
                  WHERE p.ID_PEDIDO = ?
                  GROUP BY p.ID_PEDIDO, p.FECHA, p.RUT";
                  
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $ID_PEDIDO);

        try {
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ($resultado->num_rows > 0) {
                $datos = $resultado->fetch_assoc();
                $stmt->close();
                $con->closeConnection(); 
                return $datos; 
            } else {
                $stmt->close();
                $con->closeConnection();
                return false; 
            }
        } catch (\mysqli_sql_exception $e) {
            return false;
        }
    }



    public function llamarTodosLosPedidos() {
        $con = new Conexion();
        $db = $con->getConnection();
        $lista = [];
        $query = "SELECT p.ID_PEDIDO, p.FECHA, p.RUT, 
                         COALESCE(SUM(dp.MONTO_TOTAL), 0) AS MONTO
                  FROM pedido p
                  LEFT JOIN det_pedido dp ON p.ID_PEDIDO = dp.ID_PEDIDO
                  GROUP BY p.ID_PEDIDO, p.FECHA, p.RUT";

        $rs = $db->query($query);
        if ($rs) {
            while ($registro = $rs->fetch_assoc()) {

                $lista[] = array(
                    'ID DEL PEDIDO'     => $registro['ID_PEDIDO'],
                    'FECHA DEL PEDIDO'  => $registro['FECHA'],
                    'MONTO DEL PEDIDO'  => (int)$registro['MONTO'],
                    'RUT DEL COMPRADOR' => $registro['RUT']
                );
            }
            $rs->free();
        }
        $con->closeConnection();
        return $lista;
    }


    public function IngresarPrePedido(){
        $con = new Conexion();
        $db = $con->getConnection();
        $query = "INSERT INTO pedido (ID_PEDIDO, FECHA, RUT) VALUES (?,?,?)";
        $stmt = $db->prepare($query);
        $id_pedido = $this->getID_PEDIDO();
        $fecha = $this->getFECHA();
        $rut = $this->getRUT();
        $stmt->bind_param("iss", $id_pedido, $fecha, $rut);
        try{
            $rs = $stmt->execute();
            $stmt->close();
            $con->closeConnection();
            return $rs;
        }catch (\mysqli_sql_exception $e) {

        	return false;
        }
    }
	

}   