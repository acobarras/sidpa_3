<?php

namespace MiApp\negocio\controladores\Gerencia;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\cliente_productoDAO;
use MiApp\persistencia\dao\PeriodoCorteDAO;
use MiApp\persistencia\dao\PortafolioDAO;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\UsuarioDAO;

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
        header('Content-Type:cation/json');
        $asesor = $_POST['asesor'];
        $id_persona_asesor = '';
        if ($asesor == 'sin_pago') {
            $condicion = 'WHERE t1.estado_portafolio in(1,2)';
        } elseif ($asesor == 'cambio') {
            $fecha_inicial = $_POST['fecha_inicial'];
            $fecha_fin = $_POST['fecha_fin'];
            $condicion = "WHERE t1.fecha_factura >= '$fecha_inicial' AND t1.fecha_factura <= '$fecha_fin'";
        }elseif ($asesor == 0 ){
            $condicion = '';
        }else{
            $id_persona = $this->UsuarioDAO->consultarIdPersona($asesor);
            $id_persona_asesor = $id_persona[0]->id_persona;
            $condicion = ' WHERE t1.asesor = ' . $id_persona_asesor;
        }
        $data = $this->PortafolioDAO->Consultaportafolio($condicion);
        foreach ($data as $value) {
            $value->nombre_estado = ESTADO_PORTAFOLIO[$value->estado_portafolio];
        }
        echo json_encode($data);
        return;
    }
}
