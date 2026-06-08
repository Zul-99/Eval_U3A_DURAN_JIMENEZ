<?php
include_once dirname(__DIR__, 3) . '/v1.php';
header('Content-Type: application/json');



switch($_method){
    

    case 'GET':
        if($_authorization ===  'Bearer llamarTodosLosClientes.get'){
            include_once dirname(__DIR__,3 ) . '/conexion.php';
            include_once __DIR__ . './modelPedido.php'; 

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
            include_once dirname(__DIR__,3) . '/conexion.php';
            include_once __DIR__ . '/modelPedido.php';
            $modelo = new modelPedido();
            $body = json_decode(file_get_contents("php://input"));

            if(!isset ($body ->ID_PEDIDO)){
                http_response_code(400);
                json_encode (['type' => 'error', 'msg' => 'El cuerpo del archivo JSON es incorrecto (ejemplo :  "ID_PEDIDO" :"el id del pedido"']);
                exit;
            }

            $resultadopedido = $modelo->llamarPedidoPorID($body->ID_PEDIDO);

            if($resultadopedido){
                http_response_code(200);
                echo json_encode(['type' => 'succes', 'datos'=> $ID_PEDIDO],JSON_PRETTY_PRINT);
            }else{
                http_response_code(404);
                echo json_encode(['type' => 'error', 'msg' => 'No se encontraron datos']);
            }
            break;

            default:
        http_response_code(403);
        echo json_encode(['type' => 'error', 'msg' => 'Acceso Prohibido o Token Invalido']);
        break;
        }
    break;



    default:
    http_response_code(501);
    echo json_encode(['type' => 'error', 'msg' => 'Metodo no implementado']);
    break;
}