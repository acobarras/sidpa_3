<?php

namespace MiApp\negocio\controladores\facturacion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\PDF;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\control_facturacionDAO;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;

class AnulacionFacturaControlador extends GenericoControlador
{

    private $PersonaDAO;
    private $control_facturacionDAO;
    private $cons_cotizacionDAO;
    private $SeguimientoOpDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->control_facturacionDAO = new control_facturacionDAO($cnn);
        $this->cons_cotizacionDAO = new ConsCotizacionDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
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
        if (!empty($factura)) {
            $item = [];
            foreach ($factura as $value) {
                $datos_fac = $this->control_facturacionDAO->consulta_datos_fac($value->id_control_factura);
                foreach ($datos_fac as $value_fact) {
                    $value_fact->num_lista = $value->num_lista_empaque;
                    $value_fact->num_fact = $value->num_factura;
                    array_push($item, $value_fact);
                }
            }
            $respu = [
                'data_factura' => $factura,
                'items' => $item,
            ];
        } else {
            $respu = [
                'status' => -1,
                'msg' => 'Lo sentimos el numero de factura digitado no tiene datos'
            ];
        }
        echo json_encode($respu);
        return;
    }

    public function envia_anulacion()
    {
        header('Content-Type: application/json');
        $data = $_POST['data_fac']['items'];
        $data_factura = $_POST['data_fac']['data_factura'];
        if (count($data) == 1) {
            $cumple = true;
        } else {
            for ($i = 0; $i < count($data) - 1; $i++) {
                if (($data[$i]['id_cli_prov'] == $data[$i + 1]['id_cli_prov'])
                    && ($data[$i]['tipo_documento'] == $data[$i + 1]['tipo_documento'])
                    && ($data[$i]['pertenece'] == $data[$i + 1]['pertenece'])
                ) {
                    $cumple = true;
                } else {
                    $cumple = false;
                }
            }
            if ($cumple) {
                $pertenece = $data[0]['pertenece'];
                if ($pertenece == 1) { //pertenece a acobarras sas
                    $id = 8;
                    $consecutivo = $this->cons_cotizacionDAO->consultar_cons_especifico($id);
                    $num_cons =  $consecutivo[0]->numero_guardado;
                } else { // si el cliente pertenece a 2 es de acobarras colombia
                    $id = 9;
                    $consecutivo = $this->cons_cotizacionDAO->consultar_cons_especifico($id);
                    $num_cons =  $consecutivo[0]->numero_guardado;
                }
                $nuevo_cons['numero_guardado'] = $num_cons + 1; // aumentamos el consecutivo en 1
                $condicion = 'id_consecutivo=' . $id;
                $this->cons_cotizacionDAO->editar($nuevo_cons, $condicion); // subimos el nuevo consecutivo
                $items = [];
                foreach ($data as $value) {
                    $item = $value;
                    if (empty($items)) {
                        array_push($items, $item);
                    } else {
                        foreach ($items as $value_item) {
                            if ($item['id_pedido_item'] != $value_item['id_pedido_item']) {
                                array_push($items, $item);
                            }
                        }
                    }
                }
                // agregar seguimiento
                foreach ($items as $value_seg) {
                    // 102 es la actividad del cambio de factura
                    $seguimiento_op = [
                        'id_persona' => $_SESSION['usuario']->getid_persona(),
                        'id_area' => 2,
                        'id_actividad' => 102,
                        'pedido' => $value_seg['num_pedido'],
                        'item' => $value_seg['item'],
                        'observacion' => $value_seg['tipo_documento'] . " " . $consecutivo[0]->numero_guardado,
                        'estado' => 1,
                        'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                        'fecha_crea' => date('Y-m-d'),
                        'hora_crea' => date('H:i:s')
                    ];
                    $seg = $this->SeguimientoOpDAO->insertar($seguimiento_op);
                }
                // editar num_factura
                foreach ($data_factura as $value_fact) {
                    $edit_fact = [
                        'num_factura' => $num_cons,
                    ];
                    $condicion = 'id_control_factura=' . $value_fact['id_control_factura'];
                    $edit = $this->control_facturacionDAO->editar($edit_fact, $condicion);
                }
                $respu = [
                    'status' => 1,
                    'msg' => 'Se a realizado el cambio de factura al N°' . $num_cons,
                ];
            } else {
                $respu = [
                    'status' => -1,
                    'msg' => 'Lo sentimos esta factura no se puede anular',
                ];
            }
            echo json_encode($respu);
            return;
        }
    }
}
