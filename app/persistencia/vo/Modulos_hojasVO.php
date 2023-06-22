<?php

namespace MiApp\persistencia\vo;

use MiApp\persistencia\generico\IGenericoVO;

class Modulos_hojasVO implements IGenericoVO
{




    //private $id_hoja;
    private $nombre_definicion;
    private $nombre_hoja;
    private $posicion;
    private $titulo;
    private $referencia_nombre;
    private $url;
    private $controlador;
    private $metodo;
    private $estado;
    private $fecha_crea;
    //    private $lista_modulos = array();
    function getNombre_definicion()
    {
        return $this->nombre_definicion;
    }

    function setNombre_definicion($nombre_definicion)
    {
        $this->nombre_definicion = $nombre_definicion;
    }

    function getReferencia_nombre()
    {
        return $this->referencia_nombre;
    }

    function setReferencia_nombre($referencia_nombre)
    {
        $this->referencia_nombre = $referencia_nombre;
    }

    //        function getId_hoja() {
    //        return $this->id_hoja;
    //    }
    //
    //    function getNombre_hoja() {
    //        return $this->nombre_hoja;
    //    }

    function getPosicion()
    {
        return $this->posicion;
    }

    function getTitulo()
    {
        return $this->titulo;
    }

    function getUrl()
    {
        return $this->url;
    }

    function getControlador()
    {
        return $this->controlador;
    }

    function getMetodo()
    {
        return $this->metodo;
    }

    function getEstado()
    {
        return $this->estado;
    }

    function getFecha_crea()
    {
        return $this->fecha_crea;
    }

    function setId_hoja($id_hoja)
    {
        $this->id_hoja = $id_hoja;
    }

    function setNombre_hoja($nombre_hoja)
    {
        $this->nombre_hoja = $nombre_hoja;
    }

    function setPosicion($posicion)
    {
        $this->posicion = $posicion;
    }

    function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    function setUrl($url)
    {
        $this->url = $url;
    }

    function setControlador($controlador)
    {
        $this->controlador = $controlador;
    }

    function setMetodo($metodo)
    {
        $this->metodo = $metodo;
    }

    function setEstado($estado)
    {
        $this->estado = $estado;
    }

    function setFecha_crea($fecha_crea)
    {
        $this->fecha_crea = $fecha_crea;
    }

    //    function getLista_modulos() {
    //        return $this->lista_modulos;
    //    }
    //
    //    function setLista_modulos($lista_modulos) {
    //        $this->lista_modulos = $lista_modulos;
    //    }



    public function getAtributos()
    {

        $atributos = array();
        // $atributos['id_hoja'] = $this->id_hoja;
        $atributos['nombre_hoja'] = $this->nombre_hoja;
        $atributos['nombre_definicion'] = $this->nombre_hoja;
        $atributos['posicion'] = $this->posicion;
        $atributos['titulo'] = $this->titulo;
        $atributos['referencia_nombre'] = $this->referencia_nombre;
        $atributos['url'] = $this->url;
        $atributos['controlador'] = $this->controlador;
        $atributos['metodo'] = $this->metodo;
        $atributos['estado'] = $this->estado;
        $atributos['fecha_crea'] = $this->fecha_crea;




        return $atributos;
    }

    public function convertir($info)
    {
        $atributos = array_keys(get_object_vars($this));
        unset($atributos['lista_modulos']);
        foreach ($atributos as $nombreAtributos) {
            if (isset($info['mod_' . $nombreAtributos])) {
                $this->$nombreAtributos = $info['mod_' . $nombreAtributos];
            }
        }
        return $atributos;
    }
}
