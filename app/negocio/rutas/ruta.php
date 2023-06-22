<?php

namespace  MiApp\negocio\rutas;

use MiApp\negocio\util\Route;
use MiApp\persistencia\basedatos\Conexion;

/* * ****************************************************************************************************************************************** */

$cnn = Conexion::conectar();
$union = [];

foreach ($cnn->query('SELECT * from modulo_hoja') as $rutas) {
    $union[] = array(
        'url' => $rutas['url'],
        'controlador' => $rutas['controlador'],
        'metodo' => $rutas['metodo'],
        'tipo_peticion' => $rutas['tipo_peticion'],
        'estado' => $rutas['estado'],
    );
}
foreach ($union as $value) {
    $metodo = $value['tipo_peticion']; // El metodo es el metodo por el cual se realiza la petición puede ser get o post o el metodo creado en la clase route.php
    Route::$metodo($value['url'], $value['controlador'], $value['metodo'],$value['estado']);
}
Route::get('/menu', 'MenuControlador', 'menu', 'MENU');
Route::get('/cerrar_sesion', 'UsuarioControlador', 'CerrarSesion', 'CERRAR_SESION');
Route::get('/pruebas', 'PruebasControlador', 'pruebas');
