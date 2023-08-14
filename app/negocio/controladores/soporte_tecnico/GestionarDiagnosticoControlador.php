<?php

namespace MiApp\negocio\controladores\soporte_tecnico;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\PDF;
use MiApp\persistencia\dao\SoporteItemDAO;
use MiApp\persistencia\dao\CotizacionItemSoporteDAO;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\SoporteTecnicoDAO;
use MiApp\persistencia\dao\trmDAO;



class GestionarDiagnosticoControlador extends GenericoControlador
{
    private $SoporteItemDAO;
    private $CotizacionItemSoporteDAO;
    private $ConsCotizacionDAO;
    private $SoporteTecnicoDAO;
    private $trmDAO;
    public function __construct(&$cnn)
    {
        $this->SoporteItemDAO = new SoporteItemDAO($cnn);
        $this->CotizacionItemSoporteDAO = new CotizacionItemSoporteDAO($cnn);
        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
        $this->SoporteTecnicoDAO = new SoporteTecnicoDAO($cnn);
        $this->trmDAO = new trmDAO($cnn);
        parent::__construct($cnn);
        parent::validarSesion();
    }

    public function gestionar_diagnostico()
    {
        parent::cabecera();
        $this->view(
            'soporte_tecnico/gestionar_diagnostico'
        );
    }
    public function consultar_datos_item()
    {
        header('Content-Type: application/json');
        $estado = 9;
        $estado_item = 1;
        $datos_items = $this->SoporteItemDAO->consultar_item($estado, $estado_item);
        foreach ($datos_items as $value) {
            $cantidad_items = $this->SoporteItemDAO->cantidad_items($value->id_diagnostico, $value->num_consecutivo);
            $value->total_items = $cantidad_items[0]->cantidad_items;
        }
        $res['data'] =  $datos_items;
        echo json_encode($res);
        return;
    }
    public function cargar_productos()
    {
        header('Content-Type: application/json');
        $datos_items = $this->SoporteItemDAO->consultar_tecno();
        echo json_encode($datos_items);
    }
    public function enviar_items_cotizacion()
    {
        // header('Content-type: application/pdf');
        $datos = $_POST['array_storage'];
        $datos_diagnostico = $_POST['datos'];
        $estado_cotizacion = $_POST['estado'];
        $num_cotizacion = $this->ConsCotizacionDAO->consultar_cons_especifico(16);
        $nuevo_cons = $num_cotizacion[0]->numero_guardado + 1;
        $edita_consecutivo = [
            'numero_guardado' => $nuevo_cons
        ];
        $condicion = 'id_consecutivo =16';
        $this->ConsCotizacionDAO->editar($edita_consecutivo, $condicion);

        if ($estado_cotizacion == 4) {
            for ($i = 0; $i < count($datos_diagnostico); $i++) {
                $id_actividad = 93; // DEVUELVE SIN REPARAR
                $observacion = 'DEVUELVE SIN REPARAR';
                $seguimiento = GenericoControlador::agrega_seguimiento_diag($datos_diagnostico[0]['id_diagnostico'], $datos_diagnostico[$i]['item'], $id_actividad, $observacion, $_SESSION['usuario']->getid_usuario());
                $formulario = [
                    'id_diagnostico' => $datos_diagnostico[0]['id_diagnostico'],
                    'num_cotizacion' => 0,
                    'num_acta' => 0,
                    'item' => $datos_diagnostico[$i]['item'],
                    'moneda' => 0,
                    'valor' => 0,
                    'cantidad' => 0,
                    'id_producto' => 0,
                    'estado' => 7, //devuelve sin reparar 
                    'fecha_crea' => date('Y-m-d'),
                    'hora_crea' => date('H:i:s'),
                ];
                $agregar_item = $this->CotizacionItemSoporteDAO->insertar($formulario);
                $formulario_edita = [
                    'estado' =>  16,
                ];
                $condicion_diag = 'id_diagnostico =' .  $datos_diagnostico[0]['id_diagnostico'] . ' AND ' . 'item=' . $datos_diagnostico[$i]['item'];
                $editar_item = $this->SoporteItemDAO->editar($formulario_edita, $condicion_diag);
            }
        } else {
            for ($i = 0; $i < count($datos); $i++) {
                $id_diagnostico_item = ($datos_diagnostico[$i]['id_diagnostico_item']);
                $formulario_cotiza = [
                    'estado' => 10,
                ];
                $condicion = 'id_diagnostico_item =' . $id_diagnostico_item;
                $this->SoporteItemDAO->editar($formulario_cotiza, $condicion);
                // SE REGISTRA EL SEGUIMIENTO 
                for ($a = 0; $a < count($datos[$i]); $a++) {
                    if ($_POST['estado'] == 2) {
                        $id_actividad = 78; // COTIZACION DE REPUESTOS
                        $observacion = 'COTIZACIÓN MA-' . $num_cotizacion[0]->numero_guardado. ' REPUESTO '. $a+1;
                        $seguimiento = GenericoControlador::agrega_seguimiento_diag($datos_diagnostico[0]['id_diagnostico'], $datos_diagnostico[$i]['item'], $id_actividad, $observacion, $_SESSION['usuario']->getid_usuario());// ESTE SEGUIMIENTO SE REALIZA POR REPUESTO ojo
                        $formulario = [
                            'id_diagnostico' => $datos_diagnostico[0]['id_diagnostico'],
                            'num_cotizacion' => $num_cotizacion[0]->numero_guardado,
                            'num_acta' => 0,
                            'item' => $datos_diagnostico[$i]['item'],
                            'moneda' => $datos[$i][$a]['moneda'],
                            'valor' => $datos[$i][$a]['valor'],
                            'cantidad' => $datos[$i][$a]['cantidad'],
                            'id_producto' => $datos[$i][$a]['producto']['id_productos'],
                            'estado' => $estado_cotizacion,
                            'fecha_crea' => date('Y-m-d'),
                            'hora_crea' => date('H:i:s'),
                        ];
                    } else {
                        $id_actividad = 94; // COMODATO O GARANTIA
                        $observacion = 'COMODATO O GARANTIA';
                        $seguimiento = GenericoControlador::agrega_seguimiento_diag($datos_diagnostico[0]['id_diagnostico'], $datos_diagnostico[$i]['item'], $id_actividad, $observacion, $_SESSION['usuario']->getid_usuario());
                        $formulario = [
                            'id_diagnostico' => $datos_diagnostico[0]['id_diagnostico'],
                            'num_cotizacion' => 0,
                            'num_acta' => 0,
                            'item' => $datos_diagnostico[$i]['item'],
                            'moneda' => $datos[$i][$a]['moneda'],
                            'valor' => $datos[$i][$a]['valor'],
                            'cantidad' => $datos[$i][$a]['cantidad'],
                            'id_producto' => $datos[$i][$a]['producto']['id_productos'],
                            'estado' => $estado_cotizacion,
                            'fecha_crea' => date('Y-m-d'),
                            'hora_crea' => date('H:i:s'),
                        ];
                    }
                    $agregar_item = $this->CotizacionItemSoporteDAO->insertar($formulario);
                }
            }
        }
        if ($agregar_item['status'] && $estado_cotizacion == 2) {
            header('Content-type: application/pdf');
            $crea_cotiza = GestionarDiagnosticoControlador::crear_cotizacion($num_cotizacion[0]->numero_guardado);
            $respu = $crea_cotiza;
        } else if ($agregar_item['status'] && $estado_cotizacion == 4) {
            header('Content-type: application/json');
            $id_cotizacion = $agregar_item['id'];
            // SE EDITA LA TABLA DE COTIZACION PARA COLOCARLE EL NUM DE ACTA DE CIERRE
            $formulario_edita_cotiza = [
                'estado' =>  14,
            ];
            $condicion_diag = 'id_cotizacion =' . $id_cotizacion;
            $editar_cotiza = $this->CotizacionItemSoporteDAO->editar($formulario_edita_cotiza, $condicion_diag);
            $respu = [
                'status' => -1,
                'msg' => 'datos grabados'
            ];
        } else {

            header('Content-type: application/json');
            $respu = [
                'status' => -2,
                'msg' => 'datos grabados'
            ];
        }
        echo json_encode($respu);
        return;
    }

    public function crear_cotizacion($num_cotizacion)
    {
        $consulta_cotizacion = $this->CotizacionItemSoporteDAO->consulta_cotiza($num_cotizacion, 1);
        $ConsultaUltimoRegistro = $this->trmDAO->ConsultaUltimoRegistro();
        $trm = $ConsultaUltimoRegistro[0]->valor_trm;
        $sentencia = 'AND t1.num_acta = 0';
        foreach ($consulta_cotizacion as $value) {
            $repuestos = $this->SoporteItemDAO->consultar_repuestos_item($value->id_diagnostico, $value->item, $sentencia);
            $value->repuestos = $repuestos;
        }
        $fecha = date('Y-m-d');
        $respu = PDF::crea_cotizacion_visita($fecha, $consulta_cotizacion, $num_cotizacion, 1, $trm, '');
        return $respu;
    }
}
