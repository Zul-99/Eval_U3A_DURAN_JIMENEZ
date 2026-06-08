ente · PHP
<?php
include_once __DIR__ . '/../../v1.php';
header('Content-Type: application/json');
 
// Tokens para CLIENTE:
// GET    -> Bearer todocamisetas.cliente.get
// POST   -> Bearer todocamisetas.cliente.post
// PUT    -> Bearer todocamisetas.cliente.put
// DELETE -> Bearer todocamisetas.cliente.delete
 
switch ($_method) {
 
    // GET - Listar todos los clientes

    case 'GET':
        if ($_authorization === 'Bearer todocamisetas.cliente.get') {
            include_once __DIR__ . '/../../conexion.php';
            include_once __DIR__ . '/modelCliente.php';
 
            $modelo    = new modeloCliente();
            $clientes  = $modelo->getAll();
 
            if (!empty($clientes)) {
                http_response_code(200);
                echo json_encode(['type' => 'success', 'data' => $clientes], JSON_PRETTY_PRINT);
            } else {
                http_response_code(404);
                echo json_encode(['type' => 'error', 'msg' => 'No hay clientes registrados.']);
            }
 
        } else {
            http_response_code(403);
            echo json_encode(['type' => 'error', 'msg' => 'Acceso Prohibido o Token Inválido.']);
        }
        break;
 
    // POST - Registrar nuevo cliente

    case 'POST':
        if ($_authorization === 'Bearer todocamisetas.cliente.post') {
            include_once __DIR__ . '/../../conexion.php';
            include_once __DIR__ . '/modelCliente.php';
 
            $body = json_decode(file_get_contents('php://input'), true);
 
            if (!$body) {
                http_response_code(400);
                echo json_encode(['type' => 'error', 'msg' => 'JSON inválido o cuerpo vacío.']);
                exit;
            }
 
            // Validación de campos obligatorios
            $camposRequeridos = ['rut', 'nombre', 'direccion', 'categoria', 'contacto', 'pro_of'];
 
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
            if (!is_numeric($body['rut']) || (int)$body['rut'] <= 0) {
                http_response_code(400);
                echo json_encode(['type' => 'error', 'msg' => 'El RUT debe ser un número entero positivo.']);
                exit;
            }
 
            if (!is_numeric($body['pro_of']) || (int)$body['pro_of'] < 0 || (int)$body['pro_of'] > 100) {
                http_response_code(400);
                echo json_encode(['type' => 'error', 'msg' => 'El porcentaje de oferta debe ser un número entre 0 y 100.']);
                exit;
            }
 
            // Validación de categoría
            $categoriasValidas = ['Regular', 'Preferencial'];
            if (!in_array($body['categoria'], $categoriasValidas)) {
                http_response_code(400);
                echo json_encode(['type' => 'error', 'msg' => 'La categoría debe ser "Regular" o "Preferencial".']);
                exit;
            }
 
            $modelo = new modeloCliente();
            $modelo->setRut((int) $body['rut']);
            $modelo->setNombre($body['nombre']);
            $modelo->setDireccion($body['direccion']);
            $modelo->setCategoria($body['categoria']);
            $modelo->setContacto($body['contacto']);
            $modelo->setProOf((int) $body['pro_of']);
 
            $respuesta = $modelo->add($modelo);
 
            if ($respuesta) {
                http_response_code(201);
                echo json_encode(['type' => 'success', 'msg' => 'Cliente registrado correctamente.']);
            } else {
                http_response_code(422);
                echo json_encode(['type' => 'error', 'msg' => 'Error al registrar el cliente. El RUT puede que ya exista.']);
            }
 
        } else {
            http_response_code(403);
            echo json_encode(['type' => 'error', 'msg' => 'Acceso Prohibido o Token Inválido.']);
        }
        break;
 
    // PUT - Actualizar cliente existente (requiere RUT en el body)

    case 'PUT':
        if ($_authorization === 'Bearer todocamisetas.cliente.put') {
            include_once __DIR__ . '/../../conexion.php';
            include_once __DIR__ . '/modelCliente.php';
 
            $body = json_decode(file_get_contents('php://input'), true);
 
            if (!$body) {
                http_response_code(400);
                echo json_encode(['type' => 'error', 'msg' => 'JSON inválido o cuerpo vacío.']);
                exit;
            }
 
            // Validación de campos obligatorios (todos requeridos en PUT)
            $camposRequeridos = ['rut', 'nombre', 'direccion', 'categoria', 'contacto', 'pro_of'];
 
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
 
            if (!is_numeric($body['pro_of']) || (int)$body['pro_of'] < 0 || (int)$body['pro_of'] > 100) {
                http_response_code(400);
                echo json_encode(['type' => 'error', 'msg' => 'El porcentaje de oferta debe ser un número entre 0 y 100.']);
                exit;
            }
 
            $categoriasValidas = ['Regular', 'Preferencial'];
            if (!in_array($body['categoria'], $categoriasValidas)) {
                http_response_code(400);
                echo json_encode(['type' => 'error', 'msg' => 'La categoría debe ser "Regular" o "Preferencial".']);
                exit;
            }
 
            $modelo = new modeloCliente();
            $modelo->setRut((int) $body['rut']);
            $modelo->setNombre($body['nombre']);
            $modelo->setDireccion($body['direccion']);
            $modelo->setCategoria($body['categoria']);
            $modelo->setContacto($body['contacto']);
            $modelo->setProOf((int) $body['pro_of']);
 
            $respuesta = $modelo->update($modelo);
 
            if ($respuesta) {
                http_response_code(200);
                echo json_encode(['type' => 'success', 'msg' => 'Cliente actualizado correctamente.']);
            } else {
                http_response_code(422);
                echo json_encode(['type' => 'error', 'msg' => 'Error al actualizar el cliente. Verifique que el RUT exista.']);
            }
 
        } else {
            http_response_code(403);
            echo json_encode(['type' => 'error', 'msg' => 'Acceso Prohibido o Token Inválido.']);
        }
        break;
 
    // DELETE - Eliminar cliente por RUT (requiere RUT en el body)
 
    case 'DELETE':
        if ($_authorization === 'Bearer todocamisetas.cliente.delete') {
            include_once __DIR__ . '/../../conexion.php';
            include_once __DIR__ . '/modelCliente.php';
 
            $body = json_decode(file_get_contents('php://input'), true);
 
            if (!isset($body['rut']) || $body['rut'] === '' || $body['rut'] === null) {
                http_response_code(400);
                echo json_encode(['type' => 'error', 'msg' => 'El campo RUT es obligatorio.']);
                exit;
            }
 
            if (!is_numeric($body['rut']) || (int)$body['rut'] <= 0) {
                http_response_code(400);
                echo json_encode(['type' => 'error', 'msg' => 'El RUT debe ser un número entero positivo.']);
                exit;
            }
 
            $modelo = new modeloCliente();
            $modelo->setRut((int) $body['rut']);
 
            $respuesta = $modelo->delete($modelo);
 
            if ($respuesta) {
                http_response_code(200);
                echo json_encode(['type' => 'success', 'msg' => 'Cliente eliminado correctamente.']);
            } else {
                http_response_code(409);
                echo json_encode(['type' => 'error', 'msg' => 'No se puede eliminar el cliente. Puede que tenga pedidos asociados o el RUT no exista.']);
            }
 
        } else {
            http_response_code(403);
            echo json_encode(['type' => 'error', 'msg' => 'Acceso Prohibido o Token Inválido.']);
        }
        break;

    // DEFAULT - Método HTTP no permitido

    default:
        http_response_code(405);
        echo json_encode(['type' => 'error', 'msg' => 'Método HTTP no permitido.']);
        break;
}
