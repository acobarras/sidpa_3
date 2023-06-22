<?php

namespace MiApp\negocio\util;

/**
 * Clase para controlar las rutas creadas en el archivo ruta.php
 */
class Route
{

    public static function get($url, $controlador, $metodo, $estado = '', $nombre = '')
    {
        if ($nombre == '') {
            $nombre = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 70);
        }
        //--------------------------------------------------------------
        $data[1] = 'index';
        $method = $_SERVER['REQUEST_METHOD'];
        if ('GET' === $method) {
            $request = $_GET;
        } else {
            $request = array();
        }
        define($nombre, array(
            'url' => $url,
            'controlador' => $controlador,
            'method' => $metodo,
            'request' => $request,
            'GET' => 'GET',
            'HTTP' => 'GET',
            'estado' => $estado
        ));
    }

    public static function post($url, $controlador, $metodo, $estado = '', $nombre = '')
    {
        if ($nombre == '') {
            $nombre = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 70);
        }
        //--------------------------------------------------------------
        $data[1] = 'index';
        $method = $_SERVER['REQUEST_METHOD'];
        if ('POST' === $method) {
            // parse_str(file_get_contents('php://input'), $_POST);
            $request = $_POST;
        } else {
            $request = array();
        }

        define($nombre, array(
            'url' => $url,
            'controlador' => $controlador,
            'method' => $metodo,
            'request' => $request,
            'HTTP' => 'POST',
            'estado' => $estado
        ));
    }

    public static function put($url, $controlador, $metodo, $nombre = '')
    {
        if ($nombre == '') {
            $nombre = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 300);
        }
        //--------------------------------------------------------------
        $method = $_SERVER['REQUEST_METHOD'];
        if ('PUT' === $method) {
            parse_str(file_get_contents('php://input'), $_PUT);
            $request = $_PUT;
        } else {
            $request = array();
        }

        define($nombre, array(
            'url' => $url,
            'controlador' => $controlador,
            'method' => $metodo,
            'request' => $request,
            'HTTP' => 'PUT'
        ));
    }
}
