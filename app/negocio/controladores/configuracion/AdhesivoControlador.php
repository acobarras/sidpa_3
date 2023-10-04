<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\AdhesivoDAO;

class AdhesivoControlador extends GenericoControlador
{

    private $AdhesivoDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->AdhesivoDAO = new AdhesivoDAO($cnn);
    }

    public function vista_creacion_adhesivo()
    {
        parent::cabecera();
        $this->view(
            'configuracion/vista_creacion_adhesivo'
        );
    }

    public function consultar_adhesivo()
    {
        header('Content-Type: application/json');
        $resultado = $this->AdhesivoDAO->consultar_adhesivo();
        $arreglo["data"] = $resultado;
        echo json_encode($arreglo);
        return;
    }

    public function insertar_adhesivo()
    {
        header("Content-type: application/json; charset=utf-8");
        $datos = $_POST;
        if ($_POST['id_adh'] == 0) {
            unset($datos['id_adh']);
            // Validar si el tipo articulo ya fue creado
            $respuesta = $this->AdhesivoDAO->validar_adhesivo($datos['codigo_adh']);
            if (empty($respuesta)) {
                $respu = $this->AdhesivoDAO->insertar($datos);
            } else {
                $respu = ['estado' => false];    
            }
        } else {
           $respu = self::modificar_adhesivo();
        }
        echo json_encode($respu);
    }
    
    public function modificar_adhesivo()
    {
        header("Content-type: application/json; charset=utf-8");
        $id_adh = $_POST['id_adh'];
        unset($_POST['id_adh']);
        $condicion = 'id_adh =' . $id_adh;
        $resultado = $this->AdhesivoDAO->editar($_POST, $condicion);
        return $resultado;
    }
    

    
}
