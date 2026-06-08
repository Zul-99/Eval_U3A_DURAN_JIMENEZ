<?php
include_once dirname(__DIR__, 3) . '/v1.php';
header('Content-Type: application/json');



switch($_method){
    

    case 'GET':
        if($_authorization ===  'Bearer llamartodoslospedidos.get'){
            include_once dirname(__DIR__,3 ) . '/conexion.php';
            include_once __DIR__ . '/modelPedido.php'; 
            $modelo = new modelPedido();
            http_response_code(200);
            echo json_encode(['data' => $modelo->llamarTodosLosPedidos()]);
        }else{
            http_response_code(403);
            echo $_authorization;
            echo json_encode(['type' => 'error', 'msg' => 'Acceso Prohibido']);
        }
    break;



    case 'POST':
        switch($_authorization){

        case 'Bearer llamarUnPedido.get':
        include_once dirname(__DIR__, 3) . '/conexion.php';
        include_once __DIR__ . '/modelPedido.php';
                
        $modelo = new modelPedido();
        $body = json_decode(file_get_contents("php://input"));
                
        if (!isset($body->ID_PEDIDO)) {
            http_response_code(400);
            echo json_encode([
                'type' => 'error',
                'msg'  => 'El cuerpo del JSON es incorrecto. Se requiere "ID_PEDIDO"'
            ]);
            exit;
        }
                
        $resultadoPedido = $modelo->llamarPedidoPorID($body->ID_PEDIDO);
                
        if ($resultadoPedido !== false) {
            http_response_code(200);
            echo json_encode([
                'type'  => 'success',
                'datos' => $resultadoPedido
            ], JSON_PRETTY_PRINT);
        } else {
            http_response_code(404);
            echo json_encode([
                'type' => 'error',
                'msg'  => 'No se encontró ningún pedido con el ID proporcionado'
            ]);
        }
break;

            default:
        http_response_code(403);
        echo json_encode(['type' => 'error', 'msg' => 'Acceso Prohibido o Token Invalido']);
        break;



        case 'Bearer IngresarPrePedido.post':
            include_once dirname(__DIR__,3) . '/conexion.php';
            include_once __DIR__ . '/modelPedido.php';
            $modelo = new modelPedido();
            $body = json_decode(file_get_contents("php://input"));

            if (!$body) {
                http_response_code(400);
                echo json_encode(['type' => 'error', 'msg' => 'JSON inválido o cuerpo vacío.']);
                exit;
            }
 
            $modelo->setID_PEDIDO($body->ID_PEDIDO);
            $modelo->setFECHA($body->FECHA);
            $modelo->setRUT($body->RUT);
 
            $resultadopedido = $modelo->IngresarPrePedido();

            if($resultadopedido){
                http_response_code(200);
                echo json_encode(['type' => 'succes', 'msg' =>'Su PrePedido se a agendado con exito']);
            }else{
                http_response_code(404);
                echo json_encode(['type' => 'error', 'msg' => 'No se pueden insertar datos, puede que no existan datos en la base de datos']);
            }
            break;
        }
    break;



    default:
    http_response_code(501);
    echo json_encode(['type' => 'error', 'msg' => 'Metodo no implementado']);
    break;
}