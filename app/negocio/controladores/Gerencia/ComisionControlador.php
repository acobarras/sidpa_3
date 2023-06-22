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
        $id_periodo = $_POST['periodo'];
        $id_persona_asesor = '';
        if ($asesor != 'cambio') {
            if ($asesor != 0) {
                $id_persona = $this->UsuarioDAO->consultarIdPersona($asesor);
                $id_persona_asesor = $id_persona[0]->id_persona;
            }
            $data = $this->PortafolioDAO->ConsultarPortafolioAsesor($id_persona_asesor);
        } else {
            $fecha_inicial = $_POST['fecha_inicial'];
            $fecha_fin = $_POST['fecha_fin'];
            $data = $this->PortafolioDAO->ConsultaPorFecha($fecha_inicial, $fecha_fin);
        }
        foreach ($data as $value) {
            $value->nombre_estado = ESTADO_PORTAFOLIO[$value->estado_portafolio];
        }
        echo json_encode($data);
        return;
    }
}
