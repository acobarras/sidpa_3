<?php

namespace MiApp\negocio\generico;

use MiApp\persistencia\basedatos\Conexion;
use const CARPETA_APP;

class Core
{

    private $cnn;

    public function __construct()
    {
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Headers:Authorization, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Allow-Request-Method');
        header('Access-Control-Allow-Methods:GET, POST, OPTIONS, PUT, DELETE');
        header('Allow:GET, POST, OPTIONS, PUT, DELETE');
        require_once './app/negocio/util/ConstantesRutas.php';
        require './app/negocio/rutas/ruta.php';
        // IMPORTACION DE LIBRERIAS ESPECIFICAS ANTES DE CUALQUIER CONEXION
        require_once './app/negocio/util/Constantes.php';

        $this->cnn = Conexion::conectar();
        $this->URL();
    }

    public function URL()
    {
        if ($_SERVER['QUERY_STRING'] != '') {
            $query_string = explode("=", $_SERVER['QUERY_STRING']); //valores de url
            foreach (get_defined_constants() as $constant) {
                if (!is_array($constant)) {
                    continue;
                }
                if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                    $url = explode('&', $query_string[1]);
                    
                    // $url = explode("/", $uri[0]); //nombre de url
                    $ruta = explode("/:", $constant['url']); //obtener el nombre
                    
                    if ('/' . $url[0] == $ruta[0]) { //validar que la ruta sea igual
                        if ($constant['estado'] === 0) {
                            return header('location:' . RUTA_PRINCIPAL);
                        }
                        if ($constant['HTTP'] == $_SERVER['REQUEST_METHOD']) { //validar que el metodo sea igual
                            if (count($url) > 1) {

                                if (count($ruta) == count($url)) { //validar si ruta tiene mas parametros
                                    unset($ruta[0]); //eliminar el nomrbe de ruta
                                    unset($url[0]); //eliminar el nomrbe de ruta

                                    foreach ($url as $key => $valor) {
                                        $request[$ruta[$key]] = $url[$key];
                                    }
                                } else if (count($ruta) < count($url)) {
                                    unset($ruta[0]); //eliminar el nomrbe de ruta
                                    if (count($ruta) == 0) {
                                        $request = '';
                                    } else {
                                        foreach ($ruta as $key => $valor) {
                                            $request[$valor] = $url[$key];  //asignar valores                              
                                        }
                                    }
                                } else {

                                    unset($url[0]); //eliminar el nomrbe de ruta
                                    unset($ruta[0]);
                                    foreach ($url as $key => $valor) {
                                        $request[$ruta[$key]] = $url[$key];
                                    }
                                }
                            } else {
                                $request = '';
                            }
                            //instanciar los controladores de metodo GET
                            $class = '\\MiApp\\negocio\\controladores\\' . $constant['controlador'];
                            $obj = new $class($this->cnn);
                            $method = $constant['method'];
                            $m = (method_exists($obj, $method));
                            if (!$m) {
                                http_response_code(500);
                                echo json_encode(array("Error" => "Error 500 no existe metodo => $method"));
                                return;
                            }
                            $obj->$method();
                            return;
                        } else {
                            continue;
                        }
                    }
                } else {
                    if ('/' . $query_string[1] == $constant['url']) {
                        if ($_SERVER['REQUEST_METHOD'] == $constant['HTTP']) { //VALIDAR QUE EL METODO SEA IGUAL
                            $request = $constant['request'];
                            $class = '\\MiApp\\negocio\\controladores\\' . $constant['controlador'];
                            $obj = new $class($this->cnn);
                            $method = $constant['method'];
                            $m = (method_exists($obj, $method));
                            if (!$m) {

                                http_response_code(500);
                                echo json_encode(array("Error" => "Error 500 no existe metodo => $method"));
                                return;
                            }
                            $obj->$method();
                            return;
                        }
                    }
                }
            }
            // http_response_code(404);
            include_once FOLDER_APP . '/vista/errores.php';
            return;
        }
        if (!isset($_SERVER['PATH_INFO'])) {
            session_start();
            if (isset($_SESSION['usuario'])) {
                return header('location:' . RUTA_PRINCIPAL . '/menu');
            }
            include_once './public/vistas/inicio/inicio.php';
            return;
        }
        include_once './public/vistas/inicio/inicio.php';
    }
}
