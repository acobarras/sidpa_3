<?php

namespace MiApp\negocio\controladores\facturacion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\PDF;
use MiApp\persistencia\dao\EntregasLogisticaDAO;
use MiApp\persistencia\dao\direccionDAO;
use MiApp\persistencia\dao\control_facturacionDAO;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\SeguimientoFacturaDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;

class PendientesRadicarControlador extends GenericoControlador
{

    private $EntregasLogisticaDAO;
    private $direccionDAO;
    private $control_facturacionDAO;
    private $ConsCotizacionDAO;
    private $SeguimientoFacturaDAO;
    private $SeguimientoOpDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
        $this->direccionDAO = new direccionDAO($cnn);
        $this->control_facturacionDAO = new control_facturacionDAO($cnn);
        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
        $this->SeguimientoFacturaDAO = new SeguimientoFacturaDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
    }

    public function vista_pendientes_radicar()
    {
        parent::cabecera();
        $this->view(
            'facturacion/vista_pendientes_radicar'
        );
    }

    public function pendiente_radicar()
    {
        header('Content-Type: application/json');
        $res = $this->control_facturacionDAO->consulta_pendientes_por_facturar();
        $tab['data'] = $res;
        echo json_encode($tab);
        return;
    }

    public function cambio_remision_factura()
    {
        header('Content-Type: application/json');
        $tipo_documento = $_POST['tipo_documento'];
        $num_documento = $_POST['num_documento'];
        $documentos = $_POST['elegidos'];
        if ($tipo_documento == 99) {
            $this->CerrarSinFactura($documentos);
            $respu = [
                'status' => 1,
                'msg' => "Datos modificados correctamente.",
            ];
        } else {
            $prefijo = PREFIJO[$tipo_documento];
            // Validar si el consecutivo ya fue utilizado
            $documento_relacionado = $this->ConsCotizacionDAO->consultar_cons_especifico($tipo_documento);
            // Se valida si el documento ya se utilizo
            if ($documento_relacionado[0]->numero_guardado == $num_documento && $documento_relacionado[0]->id_consecutivo == $tipo_documento) {
                //Aumento el consecutivo para evitar sea utilizado el documento
                $doc_relacionado = [
                    'numero_guardado' => $documento_relacionado[0]->numero_guardado + 1
                ];
                $condicion_documento = ' id_consecutivo =' . $tipo_documento;
                $this->ConsCotizacionDAO->editar($doc_relacionado, $condicion_documento);
                // Se recorre los item para su respectivo proceso
                foreach ($documentos as $value) {
                    // Editamos control facturas
                    $control_factura_edita = [
                        'tipo_documento' => $tipo_documento,
                        'num_factura' => $num_documento,
                    ];
                    $condicion_control_factura = "id_control_factura =" . $value['id_control_factura'];
                    $this->control_facturacionDAO->editar($control_factura_edita, $condicion_control_factura);
                    // Cambiamos el tipo de documento, la fecha e la factura el dia q se realiza esta y la persona que lo factura  en entregas_logistica
                    $edita_entregas_logistica = [
                        'tipo_documento' => $prefijo,
                        'fecha_factura' => date('Y-m-d'),
                        'fact_por' => $_SESSION['usuario']->getid_usuario(),
                    ];
                    $condicion_entregas_logistica = "id_factura =" . $value['id_control_factura'];
                    $this->EntregasLogisticaDAO->editar($edita_entregas_logistica, $condicion_entregas_logistica);
                    $items_factura = $this->EntregasLogisticaDAO->ItemFactura($value['id_control_factura']);
                    foreach ($items_factura as $items) {
                        $seguimiento_op = [
                            'id_persona' => $_SESSION['usuario']->getid_persona(),
                            'id_area' => 2,
                            'id_actividad' => 24,
                            'pedido' => $items->num_pedido,
                            'item' => $items->item,
                            'observacion' => $prefijo . " " . $num_documento,
                            'estado' => 1,
                            'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                            'fecha_crea' => date('Y-m-d'),
                            'hora_crea' => date('H:i:s')
                        ];
                        $this->SeguimientoOpDAO->insertar($seguimiento_op);
                    }
                    $seg_factura = array(
                        'id_control_factura' => $value['id_control_factura'],
                        'actividad' => 24,
                        'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                        'fecha_crea' => date('Y-m-d H:i:s')

                    );
                    $this->SeguimientoFacturaDAO->insertar($seg_factura);
                }
                $respu = [
                    'status' => 1,
                    'msg' => "Datos modificados correctamente.",
                ];
            } else { // Los documentos ya se utilizaron
                $respu = [
                    'status' => -1,
                    'msg' => "Lo sentimos este numero de documento ya fue utilizado intentelo nuevamente.",
                ];
            }
        }
        echo json_encode($respu);
        return;
    }

    public function CerrarSinFactura($documentos)
    {
        header('Content-Type: application/json');
        foreach ($documentos as $value) {
            $control_factura_edita = [
                'estado' => 2
            ];
            $condicion_control_factura = "id_control_factura =" . $value['id_control_factura'];
            $this->control_facturacionDAO->editar($control_factura_edita, $condicion_control_factura);
            $edita_entregas_logistica = [
                'estado' => 7,
            ];
            $condicion_entregas_logistica = "id_factura =" . $value['id_control_factura'];
            $this->EntregasLogisticaDAO->editar($edita_entregas_logistica, $condicion_entregas_logistica);
            $items_factura = $this->EntregasLogisticaDAO->ItemFactura($value['id_control_factura']);
            foreach ($items_factura as $items) {
                $seguimiento_op = [
                    'id_persona' => $_SESSION['usuario']->getid_persona(),
                    'id_area' => 2,
                    'id_actividad' => 27,
                    'pedido' => $items->num_pedido,
                    'item' => $items->item,
                    'observacion' => 'ENTREGADO PARA ALMACENAR',
                    'estado' => 1,
                    'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                    'fecha_crea' => date('Y-m-d'),
                    'hora_crea' => date('H:i:s')
                ];
                $this->SeguimientoOpDAO->insertar($seguimiento_op);
            }
        }
        return true;
    }
}
