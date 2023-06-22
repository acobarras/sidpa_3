<?php

namespace MiApp\negocio\controladores\entregas;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\PagoFletesDAO;

class MisIngresosControlador extends GenericoControlador
{

    private $PersonaDAO;
    private $PagoFletesDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->PagoFletesDAO = new PagoFletesDAO($cnn);
    }

    public function vista_mis_ingresos()
    {
        parent::cabecera();
        $this->view(
            'entregas/vista_mis_ingresos',
            [
                'personas' => $this->PersonaDAO->consultar_personas(),
            ]
        );
    }

    public function consulta_mis_ingresos()
    {
        header('Content-Type: application/json');
        $transportador = $_POST['transportador'];
        $fecha_desde = $_POST['desde'];
        $fecha_hasta = $_POST['hasta'];
        $respu = $this->PagoFletesDAO->consulta_flete_transportador($transportador, $fecha_desde, $fecha_hasta);
        echo json_encode($respu);
        return;
    }
}

?>