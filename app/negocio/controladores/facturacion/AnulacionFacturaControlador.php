<?php

namespace MiApp\negocio\controladores\facturacion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\PDF;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\control_facturacionDAO;

class AnulacionFacturaControlador extends GenericoControlador
{

    private $PersonaDAO;
    private $control_facturacionDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->control_facturacionDAO = new control_facturacionDAO($cnn);
    }

    public function vista_anulacion_factura()
    {
        parent::cabecera();
        $this->view(
            'facturacion/vista_anulacion_factura'
        );
    }

    public function anular_factura()
    {
        header('Content-Type: application/json');
        $condi = 'WHERE num_factura=' . $_POST['numero_factura_consulta'];
        $factura = $this->control_facturacionDAO->ConsultaEspecifica($condi);
        foreach ($factura as $value) {
            $datos_fac = $this->control_facturacionDAO->consulta_datos_fac($value->id_control_factura);
            $value->datos_fact = $datos_fac;
        }
        echo json_encode($factura);
        return;
    }
    public function envia_anulacion()
    {
        header('Content-Type: application/json');
        $data = $_POST['data_fac'];
        foreach ($data as $value) {
            print_r($value['datos_fact'][0]['id_cli_prov']);
        }
    }
}
