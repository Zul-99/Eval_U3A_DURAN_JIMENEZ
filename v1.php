<?php
//v1= autorizaciones para acceder a la api
$_method = $_SERVER[ 'REQUEST_METHOD'];//obtiene el servidor en el cual esta posicionado
$_uri = $_SERVER ['REQUEST_URI']; //identificador uniforme de recursos
$_carpetas = explode('/',$_uri); //explorador de carpetas

/*
request: la informaciona pedir 
repsonse: la respuesta del servidor 
*/

header('Access-Control-Allow-Origin: *');//Da acceso al original / restriccion 
header('Access-Control-Allow-Method: GET, POST, PUT, PATCH, DELETE');//Metodos
header('Content-Type: application/json; charset=UTF-8'); //Config del idioma

$_authorization = null;
try{
    if(isset(getallheaders()['Authorization'])){ //Busca si existe la validacion
            //echo json_encode(['holi' => 'holita a todos']); Mensaje en caso de que si
        $_authorization = getallheaders()['Authorization'];
    }else{
        http_response_code(401);//Error de Authorizacion
        echo json_encode(['error' => 'Sin Autorizacion']);
    }
}catch (Exception $e){//Exception por si falla todo
    echo 'Error sin control'. $e;
}
?>