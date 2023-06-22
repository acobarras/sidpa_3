<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\CodigosEspecialesDAO;

class CodigoEspecialControlador extends GenericoControlador
{

    private $productosDAO;
    private $CodigosEspecialesDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->productosDAO = new productosDAO($cnn);
        $this->CodigosEspecialesDAO = new CodigosEspecialesDAO($cnn);
    }

    public function vista_crear_codigo_especial()
    {
        parent::cabecera();
        $this->view(
            'configuracion/vista_crear_codigo_especial',
            [
                "productos" => $this->productosDAO->consultar_productos_material()
            ]
        );
    }

    public function consultar_codigos_especiales()
    {
        header('Content-Type: application/json');
        $codigos_especiales = $this->CodigosEspecialesDAO->consultar_todos_codigos();
        $data['data'] = $codigos_especiales;
        echo json_encode($data);
        return;
    }

    public function insertar_codigo_especial()
    {
        header("Content-type: application/json; charset=utf-8");
        $datos = $_POST;
        $datos['fecha_crea'] = date('Y-m-d');
        $respu = $this->CodigosEspecialesDAO->insertar($datos);
        echo json_encode($respu);
    }

    public function eliminar_codigos_especiales()
    {
        header('Content-Type: application/json');
        $id = $_POST['id_codigos_especial'];
        $condicion = 'id_codigos_especial ='.$id;
        $codigos_esp = $this->CodigosEspecialesDAO->eliminar($condicion);
        echo json_encode($codigos_esp);
        return;
    }
}
