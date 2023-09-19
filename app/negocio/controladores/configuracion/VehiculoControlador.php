<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\UsuarioDAO;
use MiApp\persistencia\dao\VehiculosDAO;
use MiApp\negocio\util\Validacion;


class VehiculoControlador extends GenericoControlador
{
    private $UsuarioDAO;
    private $VehiculosDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->UsuarioDAO = new UsuarioDAO($cnn);
        $this->VehiculosDAO = new VehiculosDAO($cnn);
    }

    public function crear_vehiculo()
    {
        parent::cabecera();
        $this->view(
            'configuracion/crear_vehiculo',
            [
                "transportadores" => $this->UsuarioDAO->consultar_roll(11), //El id 11 es el roll de transportador
            ]
        );
    }
    public function consulta_vehiculo()
    {
        $respu['data'] = $this->VehiculosDAO->consultar_vehiculos();
        echo json_encode($respu);
    }
    public function enviar_vehiculo()
    {
        // el servicio 1 es particular y el 2 es publico
        header('Content-Type: application/json');
        $formulario = Validacion::Decodifica($_POST['form']);
        if ($_POST['id_vehiculo'] == 0) {
            $formulario['fecha_crea'] = date('Y-m-d');
            $respu = $this->VehiculosDAO->insertar($formulario);
        } else {
            $edita = [
                'id_usuario' => $formulario['id_usuario'],
            ];
            $condicion = 'id_vehiculo =' . $_POST['id_vehiculo'];
            $respu = $this->VehiculosDAO->editar($edita, $condicion);
        }
        echo json_encode($respu);
    }
}
