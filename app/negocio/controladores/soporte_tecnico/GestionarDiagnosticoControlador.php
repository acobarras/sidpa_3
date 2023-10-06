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
        $estado_item = 9; // se cambia de 1 a 9 pendiente cotizacion// de deben cambiar de 1 a 9
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
        $datos = $_POST['array_storage']; // trae los item cotizacion
        $datos_diagnostico = $_POST['datos']; // trae los item equipos
        $estado_cotizacion = $_POST['estado']; //estado del switch
        if ($estado_cotizacion == 2) { // aumento consecutivo cotizacion solo caso 2 cuando se cotiza
            $num_cotizacion = $this->ConsCotizacionDAO->consultar_cons_especifico(16);
            $nuevo_cons = $num_cotizacion[0]->numero_guardado + 1;
            $edita_consecutivo = [
                'numero_guardado' => $nuevo_cons
            ];
            $condicion = 'id_consecutivo = 16';
            $this->ConsCotizacionDAO->editar($edita_consecutivo, $condicion);
        }
        for ($i = 0; $i < count($datos_diagnostico); $i++) { // for de item-equipo
            $item_repuestos = [
                'id_diagnostico' => $datos_diagnostico[0]['id_diagnostico'],
                'num_acta' => 0,
                'item' => $datos_diagnostico[$i]['item'],
                'fecha_crea' => date('Y-m-d'),
                'hora_crea' => date('H:i:s'),
            ];
            if ($estado_cotizacion == 2) { // cotizar
                $id_actividad = 78; // COTIZACION DE REPUESTOS
                $observacion = 'COTIZACIÓN MA-' . $num_cotizacion[0]->numero_guardado;
                $estado_itemequipo['estado'] = 10;
                for ($a = 0; $a < count($datos[$i]); $a++) { // for de item-repuestos
                    $item_repuestos['num_cotizacion'] = $num_cotizacion[0]->numero_guardado;
                    $item_repuestos['moneda'] = $datos[$i][$a]['moneda'];
                    $item_repuestos['valor'] = $datos[$i][$a]['valor'];
                    $item_repuestos['cantidad'] = $datos[$i][$a]['cantidad'];
                    $item_repuestos['id_producto'] = $datos[$i][$a]['producto']['id_productos'];
                    $item_repuestos['estado'] = 2;// en espera de aprobacion
                    $agregar_repuestos = $this->CotizacionItemSoporteDAO->insertar($item_repuestos);
                };
            } elseif ($estado_cotizacion == 6) { // DEVUELVE SIN COTIZAR
                $id_actividad = 103; //DEVUELVE SIN COTIZAR
                $observacion = 'DEVUELVE SIN COTIZAR';
                $estado_itemequipo['estado'] = 15;// reparacion efectuada
                for ($a = 0; $a < count($datos[$i]); $a++) { // for de item-repuestos
                    $item_repuestos['num_cotizacion'] = 0;
                    $item_repuestos['moneda'] = $datos[$i][$a]['moneda'];
                    $item_repuestos['valor'] = $datos[$i][$a]['valor'];
                    $item_repuestos['cantidad'] = $datos[$i][$a]['cantidad'];
                    $item_repuestos['id_producto'] = $datos[$i][$a]['producto']['id_productos'];
                    $item_repuestos['estado'] = 6; // devuelve sin reparar   
                    $agregar_repuestos = $this->CotizacionItemSoporteDAO->insertar($item_repuestos);
                };
            } else { // COMODATO
                $id_actividad = 94; // COMODATO O GARANTIA
                $observacion = 'COMODATO O GARANTIA';
                $estado_itemequipo['estado'] = 12;
                $estado_diagnostico['estado'] = 17;
                for ($a = 0; $a < count($datos[$i]); $a++) { // for de item-repuestos
                    $item_repuestos['num_cotizacion'] = 0;
                    $item_repuestos['moneda'] = $datos[$i][$a]['moneda'];
                    $item_repuestos['valor'] = $datos[$i][$a]['valor'];
                    $item_repuestos['cantidad'] = $datos[$i][$a]['cantidad'];
                    $item_repuestos['id_producto'] = $datos[$i][$a]['producto']['id_productos'];
                    $item_repuestos['estado'] = 3; // 'Validación Piezas Inventario    
                    $agregar_repuestos = $this->CotizacionItemSoporteDAO->insertar($item_repuestos);
                };
                $condiciondiag = 'id_diagnostico =' .  $datos_diagnostico[0]['id_diagnostico'];
                $editar_diagnostico = $this->SoporteTecnicoDAO->editar($estado_diagnostico,$condiciondiag);
            }
            // SEGUIMIENTO
            $seguimiento = GenericoControlador::agrega_seguimiento_diag($datos_diagnostico[0]['id_diagnostico'], $datos_diagnostico[$i]['item'], $id_actividad, $observacion, $_SESSION['usuario']->getid_usuario());
            // EDITAR estado-item equipos 
            $condicion_diag = 'id_diagnostico =' .  $datos_diagnostico[0]['id_diagnostico'] . ' AND ' . 'item=' . $datos_diagnostico[$i]['item'];
            $editar_item = $this->SoporteItemDAO->editar($estado_itemequipo, $condicion_diag);  
        }
        //=========================== RESPUESTA CONTROLADOR ======================================
        if ($agregar_repuestos['status'] && $estado_cotizacion == 2) {
            header('Content-type: application/pdf');
            $crea_cotiza = GestionarDiagnosticoControlador::crear_cotizacion($num_cotizacion[0]->numero_guardado);
            $respu = $crea_cotiza;
        }elseif ($agregar_repuestos['status'] && $estado_cotizacion == 6) {
            header('Content-type: application/json');
            $respu = [
                'status' => -1,
                'msg' => 'datos grabados'
            ];
        }elseif ($agregar_repuestos['status'] && $estado_cotizacion == 3){
            header('Content-type: application/json');
            $respu = [
                'status' => -2,
                'msg' => 'datos grabados'
            ];
        }else{
            header('Content-type: application/json');
            $respu = [
                'status' => -3,
                'msg' => 'ocurrio un error'
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
