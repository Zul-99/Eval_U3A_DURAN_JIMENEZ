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



	public function IngresarDatosAlPrePedido() {
        $con = new Conexion();
        $db = $con->getConnection();


        $id_det_ped = $this->getID_DET_PED();
        $cant = $this->getCANT();
        $sku = $this->getSKU();
        $id_pedido = $this->getID_PEDIDO();

   
        $queryInfo = "SELECT c.PRECIO, cli.POR_OF 
                      FROM camiseta c, pedido p
                      JOIN cliente cli ON p.RUT = cli.RUT 
                      WHERE c.SKU = ? AND p.ID_PEDIDO = ?";
    
        $stmtInfo = $db->prepare($queryInfo);
        $stmtInfo->bind_param("ii", $sku, $id_pedido);
        $stmtInfo->execute();
        $stmtInfo->bind_result($precio_base, $porcentaje_oferta);
        $stmtInfo->fetch();
        $stmtInfo->close();
        $porcentaje_oferta = $porcentaje_oferta ?? 0;
    
        // Cálculos matemáticos
        $descuento = ($precio_base * $porcentaje_oferta) / 100;
        $monto_final = $precio_base - $descuento; 
        $monto_total = $monto_final * $cant;     

        $monto_final_int = (int) round($monto_final);
        $monto_total_int = (int) round($monto_total);


        $queryInsert = "INSERT INTO det_pedido (ID_DET_PED, CANT, MONTO, MONTO_TOTAL, SKU, ID_PEDIDO)
                        VALUES (?, ?, ?, ?, ?, ?)";
        $stmtInsert = $db->prepare($queryInsert);


        $stmtInsert->bind_param("iiiiii",
            $id_det_ped,
            $cant,
            $monto_final_int, 
            $monto_total_int, 
            $sku,
            $id_pedido
        );

      
        try {
            $resultado = $stmtInsert->execute();
        } catch (\mysqli_sql_exception $e) {

            $resultado = false;
        }
    

        $stmtInsert->close();
        $db->close();

        return $resultado;
    }

}