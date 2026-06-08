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
    private $MONTO;	
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



    public function llamarPedidoPorID($ID_PEDIDO){
        $con = new Conexion();
        $db = $con-> getConnection();
        $query = "SELECT ID_PEDIDO, FECHA, MONTO, RUT FROM pedido WHERE ID_PEDIDO = ?";
        $stmt = $db->prepare($query);
        $this->getID_PEDIDO();
        $this->getFECHA();
        $this->getMONTO();
        $this->getRUT();
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



    public function llamarTodosLosPedidos(){
    $con = new Conexion();
    $db= $con ->getConnection();
    $lista = [];
    $query = "SELECT ID_PEDIDO, FECHA, MONTO, RUT FROM pedido";
    

    $rs = mysqli_query($db,$query);

    if($rs){
        while($registro = mysqli_fetch_assoc($rs)){
            $tupla = new modelPedido();
            $tupla-> setID_PEDIDO($registro['ID_PEDIDO']);
            $tupla -> setFECHA($registro['FECHA']);
            $tupla-> setMONTO($registro['MONTO']);
            $tupla-> setRUT($registro['RUT']);

            array_push($lista,
                array(
                    'ID DEL PEDIDO' => $tupla ->getID_PEDIDO(),
                    'FECHA DEL PEDIDO' => $tupla->getFECHA(),
                    'MONTO DEL PEDIDO' => $tupla->getMONTO(),
                    'RUT DEL COMPRADOR'=>$tupla->getRUT()
                    )
                );
            }
            mysqli_free_result($rs);
        }
        $con-> closeConnection();
        return $lista;
    }


    public function IngresarPedidos(){
        $con = new Conexion();
        $db = $con->getConnection();
        $query = "";
    }
	

}   