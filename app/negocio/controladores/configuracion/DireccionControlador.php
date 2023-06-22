<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\clientes_proveedorDAO;
use MiApp\persistencia\dao\CiudadDAO;
use MiApp\persistencia\dao\PaisDAO;
use MiApp\persistencia\dao\DepartamentoDAO;
use MiApp\persistencia\dao\direccionDAO;

class DireccionControlador extends GenericoControlador
{

    private $clientes_proveedorDAO;
    private $CiudadDAO;
    private $PaisDAO;
    private $DepartamentoDAO;
    private $direccionDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->clientes_proveedorDAO = new clientes_proveedorDAO($cnn);
        $this->CiudadDAO = new CiudadDAO($cnn);
        $this->PaisDAO = new PaisDAO($cnn);
        $this->DepartamentoDAO = new DepartamentoDAO($cnn);
        $this->direccionDAO = new direccionDAO($cnn);
    }

    public function vista_direccion()
    {
        parent::cabecera();
        $this->view(
            'configuracion/vista_direcciones',
            [
                "client" => $this->clientes_proveedorDAO->consultar_clientes(),
                "ciud" => $this->CiudadDAO->consultar_ciudad(),
                "paises" => $this->PaisDAO->consultar_pais(),
                "departamento" => $this->DepartamentoDAO->consultar_departamento()
            ]
        );
    }

    public function consultar_direcciones()
    {
        header('Content-Type: application/json');
        $resultado = $this->direccionDAO->consultar_direccion();
        foreach ($resultado as $value) {
            $value->nombre_ruta = RUTA_ENTREGA[$value->ruta];
        }
        $arreglo["data"] = $resultado;
        echo json_encode($arreglo);
    }

    public function modificar_estados_direccion()
    {
        header('Content-Type: application/json');
        $editar_estado = ['estado_direccion' => $_POST['estado_direccion']];
        $condicion = 'id_direccion =' . $_POST['id_direccion'];
        $resultado = $this->direccionDAO->editar($editar_estado, $condicion);
        echo json_encode($resultado);
        return;
    }

    public function modificar_direccion1()
    {
        header("Content-type: application/json; charset=utf-8");
        $id_direccion = $_POST['id'];
        $formulario = Validacion::Decodifica($_POST['form']);
        $condicion = 'id_direccion =' . $id_direccion;
        $resultado = $this->direccionDAO->editar($formulario, $condicion);
        echo json_encode($resultado);
    }
}
