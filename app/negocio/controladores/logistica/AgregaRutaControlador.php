<?php

namespace MiApp\negocio\controladores\logistica;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\PagoFletesDAO;

class AgregaRutaControlador extends GenericoControlador
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

    public function vista_adicion_ruta()
    {
        parent::cabecera();
        $this->view(
            'logistica/vista_adicion_ruta',
            [
                'personas' => $this->PersonaDAO->consultar_personas(),
            ]
        );
    }

    public function agregar_diligencia()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $agrega_pago_flete = [
            'documento' => $datos['documento'],
            'valor_documento' => 0,
            'valor_flete' => $datos['valor_flete'],
            'id_transportador' => $datos['id_transportador'],
            'observacion' => $datos['observacion'],
            'estado' => 2,
            'fecha_cargue' => date('Y-m-d'),
        ];
        $inserto = $this->PagoFletesDAO->insertar($agrega_pago_flete);
        echo json_encode($inserto);
        return;
    }
}

?>