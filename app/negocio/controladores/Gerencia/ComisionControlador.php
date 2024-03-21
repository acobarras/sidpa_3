<?php

namespace MiApp\negocio\controladores\Gerencia;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\cliente_productoDAO;
use MiApp\persistencia\dao\PeriodoCorteDAO;
use MiApp\persistencia\dao\PortafolioDAO;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\UsuarioDAO;
use DateTime;

class ComisionControlador extends GenericoControlador
{
    private $cliente_productoDAO;
    private $PeriodoCorteDAO;
    private $PortafolioDAO;
    private $UsuarioDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->cliente_productoDAO = new cliente_productoDAO($cnn);
        $this->PeriodoCorteDAO = new PeriodoCorteDAO($cnn);
        $this->PortafolioDAO = new PortafolioDAO($cnn);
        $this->UsuarioDAO = new UsuarioDAO($cnn);
    }

    public function vista_liquida_comision()
    {
        parent::cabecera();

        $this->view(
            'Gerencia/vista_liquida_comision',
            [
                'asesores' => $this->cliente_productoDAO->asesores_precios(),
                'periodo' => $this->PeriodoCorteDAO->ConsultaPeriodoCorte()
            ]
        );
    }

    public function liquida_comision()
    {
        header('Content-Type:application/json');
        $asesor = $_POST['asesor'];
        $id_persona_asesor = '';
        if ($asesor == 'sin_pago') {
            $condicion = 'WHERE t1.estado_portafolio in(1,2)';
        } elseif ($asesor == 'con_pago') {
            $fecha_inicial = $_POST['fecha_inicial'];
            $fecha_fin = $_POST['fecha_fin'];
            $condicion = "WHERE t1.fecha_pago!='' AND t1.estado_portafolio=3 AND t1.fecha_pago >= '$fecha_inicial' AND t1.fecha_pago <= '$fecha_fin'";
        } elseif ($asesor == 'cambio') {
            $fecha_inicial = $_POST['fecha_inicial'];
            $fecha_fin = $_POST['fecha_fin'];
            $condicion = "WHERE t1.fecha_factura >= '$fecha_inicial' AND t1.fecha_factura <= '$fecha_fin'";
        } elseif ($asesor == 0) {
            $condicion = '';
        } else {
            $id_persona = $this->UsuarioDAO->consultarIdPersona($asesor);
            $periodo_fin = $this->PeriodoCorteDAO->ConsultaPeriodoId($_POST['periodo']);
            if ($_POST['periodo'] == 1) {
                $periodo_ini = '2022-01-01';
            } else {
                $id_periodo_in = $_POST['periodo'] - 1;
                $periodo_inicial = $this->PeriodoCorteDAO->ConsultaPeriodoId($id_periodo_in);
                $periodo_ini = $periodo_inicial[0]->corte;
            }
            $fecha_inicial = $periodo_ini;
            $fecha_fin = $periodo_fin[0]->corte;
            $id_persona_asesor = $id_persona[0]->id_persona;
            $condicion = "WHERE t1.fecha_pago >= '" . $fecha_inicial . "' AND t1.fecha_pago <= '" . $fecha_fin . "' AND t1.asesor =" . $id_persona_asesor;
        }
        $data = $this->PortafolioDAO->Consultaportafolio($condicion, $fecha_inicial, $fecha_fin);
        echo json_encode($data);
        return;
    }
}

