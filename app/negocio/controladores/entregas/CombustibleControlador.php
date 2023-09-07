<?php

namespace MiApp\negocio\controladores\entregas;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\CombustibleDAO;

class CombustibleControlador extends GenericoControlador
{

    private $CombustibleDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->CombustibleDAO = new CombustibleDAO($cnn);
    }

    public function combustible()
    {
        parent::cabecera();
        $registro = $this->CombustibleDAO->consulta_ultimo_combusti($_SESSION['usuario']->getid_usuario());
        if (empty($registro)) {
            $registro[] = (object) [
                'id_combustible' => 0,
                'kilometraje' => 0,
                'kilometraje_ant' => 0,
            ];
        }
        $this->view(
            'entregas/combustible',
            [
                'ultimo' => $registro
            ]
        );
    }
    public function consulta_combustible()
    {
        header('Content-Type: application/json');
        $id_usuario = $_SESSION['usuario']->getid_usuario();
        $respu = $this->CombustibleDAO->consulta_combusti($id_usuario);
        if (!empty($respu)) {
            foreach ($respu as $value) {
                $value->km_galon = ($value->kilometraje_ant - $value->kilometraje) / $value->cant_galones;
            }
        }
        $res['data'] = $respu;
        echo json_encode($res);
    }

    public function enviar_combustible()
    {
        header('Content-Type: application/json');
        $formulario = Validacion::Decodifica($_POST['form']);
        $ultimo = json_decode($formulario['ultimo_registro']);
        if ($ultimo->id_combustible != 0) {
            $km = $formulario['kilometraje'];
            $condicion = "id_combustible=" . $ultimo->id_combustible;
            $edit = [
                'kilometraje_ant' => $km,
            ];
            $respu = $this->CombustibleDAO->editar($edit, $condicion);
        }
        unset($formulario['ultimo_registro']);
        unset($formulario['kilometraje_ant']);
        $formulario['id_user'] = $_SESSION['usuario']->getid_usuario();
        $respu = $this->CombustibleDAO->insertar($formulario);
        echo json_encode($respu);
        return;
    }
}
