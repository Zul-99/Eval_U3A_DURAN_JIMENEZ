<?php
include_once dirname (__DIR__, 3) . '/v1.php';
header('Content-Type: application/json');


switch($_method){

    case 'POST':
          if($_authorization === 'Bearer IngresarDatosAlPrePedido.post') {
            include_once dirname(__DIR__,3) . '/conexion.php';
            include_once __DIR__ . '/modeloDET_Pedido.php';
            $modelo = new modeloDET_Pedido();
            $body = json_decode(file_get_contents("php://input"));

            // 1. Validar que vengan los datos obligatorios del frontend
            if (!isset($body->CANT) || !isset($body->SKU) || !isset($body->ID_PEDIDO)) {
                http_response_code(400);
                echo json_encode(['type' => 'error', 'msg' => 'Faltan datos obligatorios: CANT, SKU o ID_PEDIDO']);
                exit;
            }

            // 2. Setear valores al modelo
            if (isset($body->ID_DET_PED)) {
                $modelo->setID_DET_PED($body->ID_DET_PED);
            }
            $modelo->setCANT($body->CANT);
            $modelo->setSKU($body->SKU);
            $modelo->setID_PEDIDO($body->ID_PEDIDO);

            // 3. Ejecutar el método corregido (que ahora calcula precios y descuentos)
            $resultado = $modelo->IngresarDatosAlPrePedido();

            // 4. Responder al cliente
            if ($resultado) {
                http_response_code(201); // 201 Created
                echo json_encode(['type' => 'success', 'msg' => 'Detalle del pedido creado exitosamente y ofertas aplicadas']);
            } else {
                http_response_code(500); // 500 Internal Server Error
                echo json_encode(['type' => 'error', 'msg' => 'Error en el servidor al intentar guardar el detalle']);
            }
        break;
    }
break;

    default:
    http_response_code(501);
    echo json_encode(['type' => 'error', 'msg' => 'Metodo no implementado']);
    break;
}