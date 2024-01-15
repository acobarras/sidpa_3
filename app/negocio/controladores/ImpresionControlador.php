<?php

namespace MiApp\negocio\controladores;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\SubareaImpresionDAO;
use MiApp\persistencia\dao\AreaTrabajoDAO;

class ImpresionControlador extends GenericoControlador
{
    private $SubareaImpresionDAO;
    private $AreaTrabajoDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        $this->SubareaImpresionDAO = new SubareaImpresionDAO($cnn);
        $this->AreaTrabajoDAO = new AreaTrabajoDAO($cnn);
    }

    public function impresion_area()
    {
        header('Content-Type: application/json');
        $id_usuario = $_POST['datos']['id_usuario'];
        $id_roll = $_SESSION['usuario']->getId_roll();

        // Administradores ven todas las subareas
        if ($id_roll == 1) {
            $condicion = '';
        } else {
            $area = $this->AreaTrabajoDAO->consulta_area_usuario($id_usuario);
            if ($area[0]->id_area_trabajo == 3 && $id_roll != 12) {
                $condicion = 'WHERE t4.id_usuario = 0'; // no debe aparecer nada 
            } else {
                $condicion = "WHERE t4.id_usuario = $id_usuario";
            }
        }

        $subareas = $this->SubareaImpresionDAO->subareas_usuario( $condicion);
        $status = (!empty($subareas));
        $respuesta = [
            'status' => $status,
            'datos' => $subareas
        ];
        echo json_encode($respuesta);
        return;
    }
}
