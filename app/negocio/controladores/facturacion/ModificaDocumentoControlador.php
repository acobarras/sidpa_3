<?php

namespace MiApp\negocio\controladores\facturacion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\PDF;
use MiApp\persistencia\dao\EntregasLogisticaDAO;
use MiApp\persistencia\dao\direccionDAO;
use MiApp\persistencia\dao\control_facturacionDAO;

class ModificaDocumentoControlador extends GenericoControlador
{

    private $EntregasLogisticaDAO;
    private $direccionDAO;
    private $control_facturacionDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
        $this->direccionDAO = new direccionDAO($cnn);
        $this->control_facturacionDAO = new control_facturacionDAO($cnn);
    }

    public function vista_modificar_documentos()
    {
        parent::cabecera();
        $this->view(
            'facturacion/vista_modificar_documentos'
        );
    }

    public function editar_lista_empaque()
    {
        header('Content-Type: application/json');
        $datos = $_POST['data'];
        $id_entrega = $datos['id_entrega'];
        $edita_entrega_logistica = [
            'cantidad_factura' => $_POST['numero']
        ];
        $condicion = 'id_entrega = ' . $datos['id_entrega'];
        $grabo = $this->EntregasLogisticaDAO->editar($edita_entrega_logistica, $condicion);
        if ($grabo) {
            $respu = [
                'status' => 1,
                'msg' => 'Dato Modificado correctamente.',
                'table' => ''
            ];
        } else {
            $respu = [
                'status' => -1,
                'msg' => 'Lo sentimos este item ya fue cargado y no puede ser modificado.',
                'table' => ''
            ];
        }
        echo json_encode($respu);
        return;
    }
    public function cambio_fecha_fac()
    {
        header('Content-Type: application/json');
        $edita_entrega_logistica = [
            'fecha_factura' => $_POST['nueva_fecha']
        ];
        $condicion = 'id_factura = ' . $_POST['id_control_factura'];
        $grabo = $this->EntregasLogisticaDAO->editar($edita_entrega_logistica, $condicion);
        if ($grabo) {
            $respu = [
                'status' => 1,
                'msg' => 'Fecha Modificada Correctamente',
                'table' => ''
            ];
        } else {
            $respu = [
                'status' => -1,
                'msg' => 'Lo sentimos algo a pasado',
                'table' => ''
            ];
        }
        echo json_encode($respu);
        return;
    }
}
