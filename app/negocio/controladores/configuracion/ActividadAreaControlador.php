<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\AreaTrabajoDAO;
use MiApp\persistencia\dao\ActividadAreaDAO;

class ActividadAreaControlador extends GenericoControlador
{

    private $AreaTrabajoDAO;
    private $ActividadAreaDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->AreaTrabajoDAO = new AreaTrabajoDAO($cnn);
        $this->ActividadAreaDAO = new ActividadAreaDAO($cnn);
    }

    public function vista_ciclos_proceso()
    {
        parent::cabecera();
        $this->view(
            'configuracion/vista_ciclos_proceso',
            [
                "area_trabajo" => $this->AreaTrabajoDAO->consultar_area_trabajo()
            ]
        );
    }

    public function consultar_actividad_area()
    {
        header('Content-Type: application/json');
        $resultado = $this->ActividadAreaDAO->consultar_actividad_area();
        $arreglo["data"] = $resultado;
        echo json_encode($arreglo);
    }

    public function consultar_area_trabajo()
    {
        header('Content-Type: application/json');
        $resultado = $this->AreaTrabajoDAO->consultar_area_trabajo();
        $arreglo["data"] = $resultado;
        echo json_encode($arreglo);
    }
    
    public function crear_nueva_actividad()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $datos['estado_actividad_area'] = 1;
        $datos['id_usuario'] = $_SESSION['usuario']->getId_usuario();
        $datos['fecha_crea'] = date('Y-m-d');
        $respu = $this->ActividadAreaDAO->insertar($datos);
        echo json_encode($respu);
    }

    public function crear_nueva_area()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $datos['estado_area_trabajo'] = 1;
        $datos['id_usuario'] = $_SESSION['usuario']->getId_usuario();
        $datos['fecha_crea'] = date('Y-m-d');
        $respu = $this->AreaTrabajoDAO->insertar($datos);
        echo json_encode($respu);
    }

    public function modificar_estados_area()
    {
        header('Content-Type: application/json');
        $editar_estado = ['estado_area_trabajo' => $_POST['estado_area_trabajo']];
        $condicion = 'id_area_trabajo =' . $_POST['id_area_trabajo'];
        $resultado = $this->AreaTrabajoDAO->editar($editar_estado, $condicion);
        echo json_encode($resultado);
        return;
    }

    public function modificar_estados_actividad()
    {
        header('Content-Type: application/json');
        $editar_estado = ['estado_actividad_area' => $_POST['estado_actividad_area']];
        $condicion = 'id_actividad_area =' . $_POST['id_actividad_area'];
        $resultado = $this->ActividadAreaDAO->editar($editar_estado, $condicion);
        echo json_encode($resultado);
        return;       
    }

    public function modificar_area_trabajo()
    {
        header("Content-type: application/json; charset=utf-8");
        $id_area_trabajo = $_POST['id'];
        $formulario = Validacion::Decodifica($_POST['form']);
        $condicion = 'id_area_trabajo =' . $id_area_trabajo;
        $resultado = $this->AreaTrabajoDAO->editar($formulario, $condicion);
        echo json_encode($resultado);
    }

    public function modificar_actividad_area()
    {
        header("Content-type: application/json; charset=utf-8");
        $id_actividad_area = $_POST['id'];
        $formulario = Validacion::Decodifica($_POST['form']);
        $condicion = 'id_actividad_area =' . $id_actividad_area;
        $resultado = $this->ActividadAreaDAO->editar($formulario, $condicion);
        echo json_encode($resultado);
    }
}