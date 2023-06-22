<?php

namespace MiApp\persistencia\generico;

use MiApp\persistencia\generico\IGenericoVO;

abstract class GenericoDAO
{

    /**
     *
     * @var \PDO
     */
    protected $cnn;
    private $tabla;

    public function __construct(&$cnn, $tabla)
    {
        $this->cnn = $cnn;
        $this->tabla = $tabla;
    }

    public function insertar($obj) //obj -> $POST [] array ['nombres']
    {
        $listaAtributos = $obj;
        $listaCampos = '';
        $listaValores = '';
        $info = array();
        foreach ($listaAtributos as $campo => $valor) {
            if (is_null($valor)) {
                continue;
            }
            $listaCampos .= ',' . $campo;
            $listaValores .= ',:' . $campo;
            $info[$campo] = $valor;
        }
        $sql = 'INSERT INTO ' . $this->tabla . ' (' . trim($listaCampos, ',') . ') VALUES(' . trim($listaValores, ',') . ')';
        $sentencia = $this->cnn->prepare($sql);
        $r = $sentencia->execute($info);
        $devuelve = array(
            'status' => $r,
            'id' => $this->cnn->lastInsertId(),
            'msg' => 'Datos grabados correctamente posicion ' . $this->cnn->lastInsertId()
        );
        return $devuelve;
    }

    public function editar($obj, $condicion)
    {
        $listaAtributos = $obj;
        return $this->editarArray($listaAtributos, $condicion);
    }

    public function editarArray($array, $condicion)
    {
        $listaAtributos = $array;
        $listaCampos = '';
        $info = array();
        foreach ($listaAtributos as $campo => $valor) {
            if (is_null($valor)) {
                $listaCampos .= ', ' . $campo . " = NULL ";
                continue;
            }
            $listaCampos .= ', ' . $campo . "= :" . $campo;
            $info[$campo] = $valor;
        }
        $sql = ' UPDATE ' . $this->tabla . ' SET ' . trim($listaCampos, ',') . ' WHERE ' . $condicion;
        $sentencia = $this->cnn->prepare($sql);
        return $sentencia->execute($info);
        //        return $sql;
    }

    public function eliminar($condicion)
    {
        $sql = 'DELETE FROM ' . $this->tabla . ' WHERE  ' . $condicion;
        $sentencia = $this->cnn->prepare($sql);
        $res = $sentencia->execute();
        return $res;
    }
}
