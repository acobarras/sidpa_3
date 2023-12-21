<?php

namespace MiApp\persistencia\vo;

use MiApp\persistencia\generico\IGenericoVO;

class UsuarioVO implements IGenericoVO
{


    function getId_usuario()
    {
        return $this->id_usuario;
    }

    function getUsuario()
    {
        return $this->usuario;
    }

    function getNombre()
    {
        return $this->nombre;
    }

    function getApellido()
    {
        return $this->apellido;
    }

    function getResPrioridad()
    {
        return $this->res_prioridad;
    }

    function getPasword()
    {
        return $this->pasword;
    }

    function getId_roll()
    {
        return $this->id_roll;
    }

    function getId_persona()
    {
        return $this->id_persona;
    }

    function getFecha_caduca()
    {
        return $this->fecha_caduca;
    }

    function getEstado()
    {
        return $this->estado;
    }

    function getFecha_crea()
    {
        return $this->fecha_crea;
    }

    function setId_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    function setResPrioridad($res_prioridad)
    {
        $this->res_prioridad = $res_prioridad;
    }

    function setPasword($pasword)
    {
        $this->pasword = $pasword;
    }

    function setId_roll($id_roll)
    {
        $this->id_roll = $id_roll;
    }

    function setId_persona($id_persona)
    {
        $this->id_persona = $id_persona;
    }

    function setFecha_caduca($fecha_caduca)
    {
        $this->fecha_caduca = $fecha_caduca;
    }

    function setEstado($estado)
    {
        $this->estado = $estado;
    }

    function setFecha_crea($fecha_crea)
    {
        $this->fecha_crea = $fecha_crea;
    }

    function getRuta_foto()
    {
        return $this->ruta_foto;
    }

    function setRuta_foto($ruta_foto)
    {
        $this->ruta_foto = $ruta_foto;
    }
    function getNombres()
    {
        return $this->nombres;
    }

    function getApellidos()
    {
        return $this->apellidos;
    }

    function setNombres($nombres)
    {
        $this->nombres = $nombres;
    }

    function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }


    private $id_usuario;
    private $usuario;
    private $nombre;
    private $apellido;
    private $pasword;
    private $id_roll;
    private $id_persona;
    private $fecha_caduca;
    private $estado;
    private $fecha_crea;
    private $ruta_foto;
    private $nombres;
    private $apellidos;
    private $tipo_clave;
    private $res_prioridad;

    function getTipo_clave()
    {
        return $this->tipo_clave;
    }

    function setTipo_clave($tipo_clave)
    {
        $this->tipo_clave = $tipo_clave;
    }




    public function getAtributos()
    {

        $atributos = array();
        $atributos['id_usuario'] = $this->id_usuario;
        $atributos['usuario'] = $this->usuario;
        $atributos['nombre'] = $this->nombre;
        $atributos['apellido'] = $this->apellido;
        $atributos['pasword'] = $this->pasword;
        $atributos['id_roll'] = $this->id_roll;
        $atributos['id_persona'] = $this->id_persona;
        $atributos['fecha_caduca'] = $this->fecha_caduca;
        $atributos['estado_usu'] = $this->estado;
        $atributos['fecha_crea'] = $this->fecha_crea;
        $atributos['res_prioridad'] = $this->res_prioridad;



        return $atributos;
    }

    public function convertir($info)
    {
        $atributos = array_keys(get_object_vars($this));
        unset($atributos['listaUsuario']);
        foreach ($atributos as $nombreAtributos) {
            if (isset($info['usu_' . $nombreAtributos])) {
                $this->$nombreAtributos = $info['usu_' . $nombreAtributos];
            }
        }
    }
}
