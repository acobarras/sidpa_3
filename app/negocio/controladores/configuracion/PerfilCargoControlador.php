<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\PerfilCargoDAO;

class PerfilCargoControlador extends GenericoControlador
{

    private $PerfilCargoDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->PerfilCargoDAO = new PerfilCargoDAO($cnn);
    }

    public function vista_perfil_cargo()
    {
        parent::cabecera();
        $this->view(
            'configuracion/vista_perfil_cargo'
        );
    }

    public function tabla_perfil_cargo()
    {
        header("Content-type: application/json; charset=utf-8");
        $datos = $this->PerfilCargoDAO->ConsultaPerfilCargo();
        $respu['data'] = $datos;
        echo json_encode($respu);
        return;
    }

    public function insertar_perfil_cargo()
    {
        header("Content-type: application/json; charset=utf-8");
        $form = $_POST;
        $form['id_usuario'] = $_SESSION['usuario']->getId_usuario();
        $form['estado'] = 1;
        $form['fecha_crea'] = date('Y-m-d');
        $grabo = $this->PerfilCargoDAO->insertar($form);
        echo json_encode($grabo);
        return;
    }

    public function modificar_perfil_cargo()
    {
        header("Content-type: application/json; charset=utf-8");
        $id_perfil = $_POST['id'];
        $formulario = Validacion::Decodifica($_POST['form']);
        $condicion = 'id_perfil =' . $id_perfil;
        $resultado = $this->PerfilCargoDAO->editar($formulario, $condicion);
        echo json_encode($resultado);
    }

    
}
