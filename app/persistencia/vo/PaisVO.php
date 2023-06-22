<?php

namespace MiApp\persistencia\vo;

use MiApp\persistencia\generico\IGenericoVO;

class PaisVO implements IGenericoVO
{

    private $id_pais;
    private $codigo;
    private $nombre;
    private $estado;
    private $id_usuario;
    private $fecha_crea;

    function getId_pais()
    {
        return $this->id_pais;
    }

    function getCodigo()
    {
        return $this->codigo;
    }

    function getNombre()
    {
        return $this->nombre;
    }

    function getEstado()
    {
        return $this->estado;
    }

    function getId_usuario()
    {
        return $this->id_usuario;
    }

    function getFecha_crea()
    {
        return $this->fecha_crea;
    }

    function setId_pais($id_pais)
    {
        $this->id_pais = $id_pais;
    }

    function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    function setEstado($estado)
    {
        $this->estado = $estado;
    }

    function setId_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    function setFecha_crea($fecha_crea)
    {
        $this->fecha_crea = $fecha_crea;
    }

    public function getAtributos()
    {

        $atributos = array();
        $atributos['id_pais'] = $this->id_pais;
        $atributos['codigo'] = $this->codigo;
        $atributos['nombre'] = $this->nombre;
        $atributos['estado'] = $this->estado;
        $atributos['id_usuario'] = $this->id_usuario;
        $atributos['fecha_crea'] = $this->fecha_crea;


        return $atributos;
    }

    public function convertir($info)
    {
        $atributos = array_keys(get_object_vars($this));
        unset($atributos['listaUsuario']);
        return $atributos;
    }
}
