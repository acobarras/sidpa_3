<?php

namespace MiApp\negocio\controladores\logistica;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\PagoFletesDAO;

class ConfirmaCargueControlador extends GenericoControlador
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

    public function vista_confirma_cargue()
    {
        parent::cabecera();
        $this->view(
            'logistica/vista_confirma_cargue',
            [
                'personas' => $this->PersonaDAO->consultar_personas(),
            ]
        );
    }

    public function tabla_confirma_flete()
    {
        header('Content-Type: application/json');
        $datos = $this->PagoFletesDAO->confirmar_pago_flete(3);
        $data['data'] = $datos;
        echo json_encode($data);
    }

    public function aceptar_fletes()
    {
        header('Content-Type: application/json');
        $items = $_POST['items'];
        if ($_POST['estado'] == 1) {
            foreach ($items as $value) {
                $edita_pago_flete = [
                    'estado' => 4
                ];
                $condicion_edita_pago_flete = 'id_pago_flete =' . $value['id_pago_flete'];
                $edita = $this->PagoFletesDAO->editar($edita_pago_flete, $condicion_edita_pago_flete);
            }
        } else {
            foreach ($items as $value) {
                $condicion = 'id_pago_flete =' . $value['id_pago_flete'];
                $edita = $this->PagoFletesDAO->eliminar($condicion);
            }
        }
        echo json_encode($edita);
        return;
    }

    public function editar_valor_flete()
    {
        header('Content-Type: application/json');
        $data = $_POST['data'];
        $numero = $_POST['numero'];
        $edita_pago_flete = [
            'valor_flete' => $numero
        ];
        $condicion_edita_pago_flete = 'id_pago_flete =' . $data['id_pago_flete'];
        $respu = $this->PagoFletesDAO->editar($edita_pago_flete, $condicion_edita_pago_flete);
        echo json_encode($respu);
        return;
    }
}
