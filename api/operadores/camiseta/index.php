<?php
include_once dirname(__DIR__, 3) . '/v1.php';
header('Content-type: application/json');

// index ~ Modelo Camiseta:

// Tokens para CAMISETA:
// GET    -> Bearer todocamisetas.camiseta.get
// POST   -> Bearer todocamisetas.camiseta.post
// PUT    -> Bearer todocamisetas.camiseta.put
// DELETE -> Bearer todocamisetas.camiseta.delete

switch ($_method) {

    //GET:

    case 'GET':
    if ($_authorization === 'Bearer todocamisetas.camiseta.get') {
        include_once __DIR__ . '/../../../conexion.php';
        include_once __DIR__ . '/modelCamiseta.php';

        $modelo = new modeloCamiseta();

        if (isset($_GET['rut']) && $_GET['rut'] !== '') {

            $rut = (int) $_GET['rut'];

            if ($rut <= 0) {
                http_response_code(400);
                echo json_encode(['type' => 'error', 'msg' => 'El Rut ingresado no es valido.']);
                exit;
            }

            $modelo->setRutCliente($rut);
            $camisetas = $modelo->getByCliente($modelo);

            if (!empty($camisetas)) {
                http_response_code(200);
                echo json_encode(['type' => 'success', 'data' => $camisetas], JSON_PRETTY_PRINT);
            } else {
                http_response_code(404);
                echo json_encode(['type' => 'error', 'msg' => 'No se encontraron camisetas para el cliente indicado.']);
            }

        } else {
            $camisetas = $modelo->getAll();

            if (!empty($camisetas)) {
                http_response_code(200);
                echo json_encode(['type' => 'success', 'data' => $camisetas], JSON_PRETTY_PRINT);
            } else {
                http_response_code(404);
                echo json_encode(['type' => 'error', 'msg' => 'No hay camisetas registradas.']);
            }
        }

    } else {
        http_response_code(403);
        echo json_encode(['type' => 'error', 'msg' => 'Acceso Prohibido o Token Invalido.']);
    }
    break;

    //POST:

    case 'POST':
        if ($_authorization === 'Bearer todocamisetas.camiseta.post') {
            include_once __DIR__ . '/../../../conexion.php';
            include_once __DIR__ . '/modelCamiseta.php';
 
            $body = json_decode(file_get_contents('php://input'), true);
 
            if (!$body) {
                http_response_code(400);
                echo json_encode(['type' => 'error', 'msg' => 'JSON inválido o cuerpo vacío.']);
                exit;
            }
 
            // Validación de campos obligatorios
            $camposRequeridos = ['sku', 'titulo', 'club', 'pais', 'tipo', 'color', 'precio', 'tallas', 'detalles'];
 
            foreach ($camposRequeridos as $campo) {
                if (!isset($body[$campo]) || $body[$campo] === '' || $body[$campo] === null) {
                    http_response_code(400);
                    echo json_encode([
                        'type'           => 'error',
                        'msg'            => 'Faltan campos obligatorios.',
                        'campo_faltante' => $campo
                    ]);
                    exit;
                }
            }
 
            // Validación de tipos
            if (!is_numeric($body['sku']) || (int)$body['sku'] <= 0) {
                http_response_code(400);
                echo json_encode(['type' => 'error', 'msg' => 'El SKU debe ser un número entero positivo.']);
                exit;
            }
 
            if (!is_numeric($body['precio']) || (int)$body['precio'] <= 0) {
                http_response_code(400);
                echo json_encode(['type' => 'error', 'msg' => 'El precio debe ser un número entero positivo.']);
                exit;
            }
 
            $modelo = new modeloCamiseta();
            $modelo->setSku((int) $body['sku']);
            $modelo->setTitulo($body['titulo']);
            $modelo->setClub($body['club']);
            $modelo->setPais($body['pais']);
            $modelo->setTipo($body['tipo']);
            $modelo->setColor($body['color']);
            $modelo->setPrecio((int) $body['precio']);
            $modelo->setTallas($body['tallas']);
            $modelo->setDetalles($body['detalles']);
 
            $respuesta = $modelo->add($modelo);
 
            if ($respuesta) {
                http_response_code(201);
                echo json_encode(['type' => 'success', 'msg' => 'Camiseta registrada correctamente.']);
            } else {
                http_response_code(422);
                echo json_encode(['type' => 'error', 'msg' => 'Error al registrar la camiseta. El SKU puede que ya exista.']);
            }
 
        } else {
            http_response_code(403);
            echo json_encode(['type' => 'error', 'msg' => 'Acceso Prohibido o Token Inválido.']);
        }
        break;

    //PUT:
    case 'PUT':
        if ($_authorization === 'Bearer todocamisetas.camiseta.put') {
            include_once __DIR__ . '/../../../conexion.php';
            include_once __DIR__ . '/modelCamiseta.php';
 
            $body = json_decode(file_get_contents('php://input'), true);
 
            if (!$body) {
                http_response_code(400);
                echo json_encode(['type' => 'error', 'msg' => 'JSON inválido o cuerpo vacío.']);
                exit;
            }
 
            // Validación de campos obligatorios (todos requeridos en PUT)
            $camposRequeridos = ['sku', 'titulo', 'club', 'pais', 'tipo', 'color', 'precio', 'tallas', 'detalles'];
 
            foreach ($camposRequeridos as $campo) {
                if (!isset($body[$campo]) || $body[$campo] === '' || $body[$campo] === null) {
                    http_response_code(400);
                    echo json_encode([
                        'type'           => 'error',
                        'msg'            => 'Faltan campos obligatorios.',
                        'campo_faltante' => $campo
                    ]);
                    exit;
                }
            }
 
            if (!is_numeric($body['precio']) || (int)$body['precio'] <= 0) {
                http_response_code(400);
                echo json_encode(['type' => 'error', 'msg' => 'El precio debe ser un número entero positivo.']);
                exit;
            }
 
            $modelo = new modeloCamiseta();
            $modelo->setSku((int) $body['sku']);
            $modelo->setTitulo($body['titulo']);
            $modelo->setClub($body['club']);
            $modelo->setPais($body['pais']);
            $modelo->setTipo($body['tipo']);
            $modelo->setColor($body['color']);
            $modelo->setPrecio((int) $body['precio']);
            $modelo->setTallas($body['tallas']);
            $modelo->setDetalles($body['detalles']);
 
            $respuesta = $modelo->update($modelo);
 
            if ($respuesta) {
                http_response_code(200);
                echo json_encode(['type' => 'success', 'msg' => 'Camiseta actualizada correctamente.']);
            } else {
                http_response_code(422);
                echo json_encode(['type' => 'error', 'msg' => 'Error al actualizar la camiseta. Verifique que el SKU exista.']);
            }
 
        } else {
            http_response_code(403);
            echo json_encode(['type' => 'error', 'msg' => 'Acceso Prohibido o Token Inválido.']);
        }
        break;

    //DELETE
    case 'DELETE':
        if ($_authorization === 'Bearer todocamisetas.camiseta.delete') {
            include_once __DIR__ . '/../../../conexion.php';
            include_once __DIR__ . '/modelCamiseta.php';
 
            $body = json_decode(file_get_contents('php://input'), true);
 
            if (!isset($body['sku']) || $body['sku'] === '' || $body['sku'] === null) {
                http_response_code(400);
                echo json_encode(['type' => 'error', 'msg' => 'El campo SKU es obligatorio.']);
                exit;
            }
 
            if (!is_numeric($body['sku']) || (int)$body['sku'] <= 0) {
                http_response_code(400);
                echo json_encode(['type' => 'error', 'msg' => 'El SKU debe ser un número entero positivo.']);
                exit;
            }
 
            $modelo = new modeloCamiseta();
            $modelo->setSku((int) $body['sku']);
 
            $respuesta = $modelo->delete($modelo);
 
            if ($respuesta) {
                http_response_code(200);
                echo json_encode(['type' => 'success', 'msg' => 'Camiseta eliminada correctamente.']);
            } else {
                // El modelo retorna false si tiene pedidos asociados O si falla la query
                http_response_code(409);
                echo json_encode(['type' => 'error', 'msg' => 'No se puede eliminar la camiseta. Puede que tenga pedidos asociados o el SKU no exista.']);
            }
 
        } else {
            http_response_code(403);
            echo json_encode(['type' => 'error', 'msg' => 'Acceso Prohibido o Token Inválido.']);
        }
        break;
 
    // DEFAULT ~ Metodo HTTP no permitido
    default:
        http_response_code(405);
        echo json_encode(['type' => 'error', 'msg' => 'Método HTTP no permitido.']);
        break;
}