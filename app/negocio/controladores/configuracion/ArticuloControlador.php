<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\ArticuloDAO;
use MiApp\persistencia\dao\TipoArticuloDAO;
use MiApp\persistencia\dao\ClaseArticuloDAO;

class ArticuloControlador extends GenericoControlador
{

    private $ArticuloDAO;
    private $TipoArticuloDAO;
    private $ClaseArticuloDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->ArticuloDAO = new ArticuloDAO($cnn);
        $this->TipoArticuloDAO = new TipoArticuloDAO($cnn);
        $this->ClaseArticuloDAO = new ClaseArticuloDAO($cnn);
    }

    public function vista_creacion_articulo()
    {
        parent::cabecera();
        $this->view(
            'configuracion/vista_creacion_articulo',
            [
                "clase_articulo" => $this->ClaseArticuloDAO->consultar_clase_articulo(),
            ]
        );
    }

    public function consultar_articulo()
    {
        header('Content-Type: application/json');
        $resultado = $this->ArticuloDAO->consultar_articulo();
        $arreglo["data"] = $resultado;
        echo json_encode($arreglo);
    }

    public function modificar_estados_tipo_articulo()
    {
        header('Content-Type: application/json');
        $editar_estado = ['estado_articulo' => $_POST['estado_articulo']];
        $condicion = 'id_tipo_articulo =' . $_POST['id_tipo_articulo'];
        $resultado = $this->TipoArticuloDAO->editar($editar_estado, $condicion);
        echo json_encode($resultado);
        return;
    }

    public function modificar_tipo_articulo()
    {
        header("Content-type: application/json; charset=utf-8");
        $id_tipo_articulo = $_POST['id'];
        $formulario = Validacion::Decodifica($_POST['form']);
        $condicion = 'id_tipo_articulo =' . $id_tipo_articulo;
        $resultado = $this->TipoArticuloDAO->editar($formulario, $condicion);
        echo json_encode($resultado);
    }

    public function insertar_tipo_articulo()
    {
        header("Content-type: application/json; charset=utf-8");
        $datos = $_POST;
        $datos['fecha_crea'] = date('Y-m-d');
        $datos['id_usuario'] = $_SESSION['usuario']->getid_usuario();
        // Validar si el tipo articulo ya fue creado
        $respuesta = $this->ArticuloDAO->validar_articulo($datos['nombre_articulo']);
        if (empty($respuesta)) {
            $respu = $this->ArticuloDAO->insertar($datos);
        } else {
            $respu = ['estado' => false];    
        }
        echo json_encode($respu);
    }
}
