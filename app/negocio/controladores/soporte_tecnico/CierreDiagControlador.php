<?php

namespace MiApp\negocio\controladores\soporte_tecnico;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\SoporteItemDAO;
use MiApp\persistencia\dao\CotizacionItemSoporteDAO;
use MiApp\persistencia\dao\trmDAO;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\clientes_proveedorDAO;
use MiApp\persistencia\dao\SoporteTecnicoDAO;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\cliente_productoDAO;
use MiApp\persistencia\dao\ActividadAreaDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\EntregasLogisticaDAO;
use MiApp\negocio\util\PDF;

class CierreDiagControlador extends GenericoControlador
{
    private $SoporteItemDAO;
    private $CotizacionItemSoporteDAO;
    private $trmDAO;
    private $ConsCotizacionDAO;
    private $clientes_proveedorDAO;
    private $SoporteTecnicoDAO;
    private $PedidosDAO;
    private $cliente_productoDAO;
    private $ActividadAreaDAO;
    private $SeguimientoOpDAO;
    private $PedidosItemDAO;
    private $EntregasLogisticaDAO;
    public function __construct(&$cnn)
    {
        $this->SoporteItemDAO = new SoporteItemDAO($cnn);
        $this->CotizacionItemSoporteDAO = new CotizacionItemSoporteDAO($cnn);
        $this->trmDAO = new trmDAO($cnn);
        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
        $this->clientes_proveedorDAO = new clientes_proveedorDAO($cnn);
        $this->SoporteTecnicoDAO = new SoporteTecnicoDAO($cnn);
        $this->PedidosDAO = new PedidosDAO($cnn);
        $this->cliente_productoDAO = new cliente_productoDAO($cnn);
        $this->ActividadAreaDAO = new ActividadAreaDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
        parent::__construct($cnn);
        parent::validarSesion();
    }

    public function vista_cierre_diag()
    {
        parent::cabecera();
        $this->view(
            'soporte_tecnico/vista_cierre_diag'
        );
    }
    public function consultar_datos_cierre()
    {
        header('Content-Type: application/json');
        $estado_item = '11,15,16';
        $datos_items = $this->SoporteItemDAO->consultar_aprobacion($estado_item, '');
        $sentencia = 'AND t1.num_acta=0';
        foreach ($datos_items as $value) {
            $repuestos = $this->SoporteItemDAO->consultar_repuestos_item($value->id_diagnostico, $value->item, $sentencia);
            $value->repuestos = $repuestos;
        }
        $res['data'] = $datos_items;
        echo json_encode($res);
        return;
    }
    public function generar_acta()
    {
        header('Content-Type: application/json');
        // SE ADELANTA EL CONSECUTIVO DEL NUMERO DE ACTA
        $data = $_POST['data'];
        $estado_genera_pedido = $_POST['estado'];
        $num_acta = $this->ConsCotizacionDAO->consultar_cons_especifico(17);
        $nuevo_cons = $num_acta[0]->numero_guardado + 1;
        $edita_acta = [
            'numero_guardado' => $nuevo_cons
        ];
        $condicion_acta = 'id_consecutivo =17';
        $this->ConsCotizacionDAO->editar($edita_acta, $condicion_acta);

        // SI VIENE POR ESTADO 15 ES POR QUE LA EJECUCION DE LA REPARACION FUE EXITOSA, SI NO ES POR FALTA DE REPUESTOS O RECHAZO
        if ($estado_genera_pedido == 1) {
            // SE REALIZA EL EACTA Y SE CREA EL PEDIDO
            $iva = $_POST['iva'];
            $estado_pdf = 1;
            $estado_item = 14;
            $estado_cotiza = 8;
            $respu = CierreDiagControlador::crear_pedido_soporte($data, $estado_pdf, $estado_item, $estado_cotiza, $num_acta[0]->numero_guardado, $iva);
        } else {
            // SE REALIZA EL ACTA Y SE CIERRA EL CASO POR ESTA RAZON SE MODIFICAN LOS ESTADOS DE LA TABLA
            $estado_pdf = 2;
            $estado_item = 14;
            $estado_cotiza = 8;
            $respu = CierreDiagControlador::cambiar_estado_cierre($data, $estado_item, $estado_cotiza, $estado_pdf, $num_acta[0]->numero_guardado);
        }
        echo json_encode($respu);
        return;
    }

    public function crear_pedido_soporte($data, $estado_pdf, $estado_item, $estado_cotiza, $num_acta, $iva)
    {
        header('Content-Type: application/json');
        // SE IDENTIFICA A QUE EMPRESA PERTENECE EL CLIENTE
        $pertenece = $this->clientes_proveedorDAO->consultar_clientes_proveedor($data[0]['id_cli_prov']); //consulta el id_cli_prov para saber que tipo de cliente <es class=""></es>
        if ($pertenece[0]->pertenece == 1) { // si el cliente pertenece a 1 es de acobarras s.a.s
            $id = 3;
            $consecutivo = $this->ConsCotizacionDAO->consultar_cons_especifico($id);
            $num_pedido =  $consecutivo[0]->numero_guardado;
        } elseif ($pertenece[0]->pertenece == 2) { // si el cliente pertenece a 2 es de acobarras colombia
            $id = 4;
            $consecutivo = $this->ConsCotizacionDAO->consultar_cons_especifico($id);
            $num_pedido =  $consecutivo[0]->numero_guardado;
        } else { //de lo contrario seria una cuenta de cobro
            $id = 6;
            $consecutivo = $this->ConsCotizacionDAO->consultar_cons_especifico($id);
            $num_pedido =  $consecutivo[0]->numero_guardado;
        }
        $nuevo_cons['numero_guardado'] = $num_pedido + 1; // aumentamos el consecutivo en 1
        $condicion = 'id_consecutivo=' . $id;
        $this->ConsCotizacionDAO->editar($nuevo_cons, $condicion); // subimos el nuevo consecutivo

        // CONSULTAR LA INFORMACION DEL DIAGNOSTICO PARA OBTENER LA DIRECCIÓN
        $datos_diag = $this->SoporteTecnicoDAO->consultar_diag($data[0]['id_diagnostico']);
        $total_tecn = 0;
        foreach ($data as $value) {
            foreach ($value['repuestos'] as $value_repu) {
                $total_tecn = $total_tecn + $value_repu['valor'] * $value_repu['cantidad'];
            }
        }
        $data_pedido = [
            'num_pedido' => $num_pedido,
            'id_cli_prov' => $data[0]['id_cli_prov'],
            'id_persona' =>  104,
            'id_dire_entre' => $datos_diag[0]->id_direccion,
            'id_dire_radic' => $datos_diag[0]->id_direccion,
            'fecha_compromiso' => date('Y-m-d'),
            'fecha_cierre' => date('Y-m-d'),
            'iva' => $iva, //PREGUNTAR CUANDO EL IVA VENGA EN 1,2
            'observaciones' => 'Este N° de pedido pertenece al diagnostico soporte N°' . $data[0]['num_consecutivo'] . '. Por favor solicitar al area de soporte los respectivos documentos.',
            'total_etiq' => 0,
            'total_tec' => $total_tecn,
            'id_estado_pedido' => 4,
            'id_usuario' => 79,
            'fecha_crea_p' => date('Y-m-d'),
            'hora_crea' => date('h:i:s'),

        ];
        $respu = $this->PedidosDAO->insertar($data_pedido); // inserta los datos a la tabla pedidos
        $parametro = 't1.id_pedido = ' . $respu['id'];
        $cons_pedido = $this->PedidosDAO->consulta_pedidos($parametro); //consultamos la tabla pedido

        $crea_items_pedido = [];
        $crea_seguimiento_items = [];
        $crea_control_facturas = [];
        $crea_entregas_logostica = [];

        // SE OBTIENE LA TRM DEL DIA
        $ConsultaUltimoRegistro = $this->trmDAO->ConsultaUltimoRegistro();
        $trm = $ConsultaUltimoRegistro[0]->valor_trm;

        // SE RECORRE LA DATA PARA REALIZAR EL CAMBIO DE ESTADO DEL DIAGNOSTICO
        foreach ($data as $value) {

            // SE REGISTRA EL SEGUIMIENTO
            $observacion = 'DIAGNOSTICO CERRADO CON NUMERO DE ACTA ENT' . $num_acta . ' Y PEDIDO NUM ' . $num_pedido;
            $seguimiento = GenericoControlador::agrega_seguimiento_diag($value['id_diagnostico'], $value['item'], $observacion, $_SESSION['usuario']->getid_usuario());

            $id_diagnostico_item = ($value['id_diagnostico_item']);
            $formulario_item = [
                'estado' => $estado_item,
            ];
            $condicion = 'id_diagnostico_item =' . $id_diagnostico_item;
            $modificacion = $this->SoporteItemDAO->editar($formulario_item, $condicion);
            if ($modificacion == 1) {
                foreach ($value['repuestos'] as  $repuestos) {
                    $id_cotizacion = ($repuestos['id_cotizacion']);
                    $formulario_cotiza = [
                        'estado' => $estado_cotiza,
                        'num_acta' => 'ENT' . $num_acta,
                    ];
                    $condicion = 'id_cotizacion =' . $id_cotizacion;
                    $modificacion_cotiza = $this->CotizacionItemSoporteDAO->editar($formulario_cotiza, $condicion);
                }
            }
        }

        foreach ($data as $val) {
            foreach ($val['repuestos'] as $product) {
                $product['num_pedido'] = $num_pedido; //asignamos a cada producto el numero de pedido asignado
                $product['id_pedido'] = $respu['id']; //asignamos a cada producto el numero de pedido asignado.
                $product['fecha_crea_p'] = date('Y-m-d'); //asignamos a cada producto la fecha del pedido.
                $product['fecha_compro_pedido'] = date('Y-m-d');
                $total_item = floatval($product['valor']) * floatval($product['cantidad']);

                if ($product['moneda'] == 2) {
                    $total_item = (floatval($product['valor']) * floatval($trm)) * floatval($product['cantidad']);
                }

                //SE REALIZA LA CONSULTA DEL PRODUCTO DEL CLIENTE SI NO EXISTE SE CREA 
                $consultar_cliente_produc = $this->cliente_productoDAO->consultar_produc_cli($data[0]['id_cli_prov'], $product['id_producto']);
                $id_cliente_producto = '';
                if ($consultar_cliente_produc == []) {
                    $crear_cliente_produc = [
                        'id_cli_prov' => $data[0]['id_cli_prov'],
                        'id_producto' => $product['id_producto'],
                        'id_ruta_embobinado' => 18,
                        'id_core' => 10,
                        'presentacion' => 1,
                        'ficha_tecnica' => 'N/A',
                        'moneda' => TIPO_MONEDA[$product['moneda']],
                        'precio_venta' => $product['valor'],
                        'moneda_autoriza' => TIPO_MONEDA[$product['moneda']],
                        'precio_autorizado' => $product['valor'],
                        'cantidad_minima' =>  1,
                        'id_material' => 0,
                        'estado_client_produc' => 1,
                        'id_usuario' => 79,
                        'permiso_product' => 1,
                        'fecha_crea' => date('Y-m-d'),
                    ];
                    $cliente_producto = $this->cliente_productoDAO->insertar($crear_cliente_produc);
                    $id_cliente_producto = $cliente_producto['id'];
                } else {
                    $cliente_producto = $consultar_cliente_produc;
                    $id_cliente_producto = $cliente_producto[0]->id_clien_produc;
                }
                // SE REGISTRA EN LA BASE DE DATOS EN LA TABLA PEDIDO ITEM
                $crea_items_pedido[] = array(
                    'id_pedido' => $product['id_pedido'],
                    'item' => $val['item'],
                    'id_clien_produc' => $id_cliente_producto,
                    'codigo' => $product['codigo_producto'],
                    'Cant_solicitada' => $product['cantidad'],
                    'cant_bodega' => $product['cantidad'],
                    'cant_op' => 0,
                    'ruta_embobinado' => 18,
                    'core' => 10,
                    'cant_x' => $product['cantidad'],
                    'trm' =>  $trm,
                    'moneda' => TIPO_MONEDA[$product['moneda']],
                    'v_unidad' => $product['valor'],
                    'total' => $total_item,
                    'fecha_compro_item' => date('Y-m-d'),
                    'orden_compra' => null,
                    'id_estado_item_pedido' => 17,
                    'id_usuario' => 79, //id usuario de soporte
                    'fecha_crea' => date('Y-m-d'),
                );

                // SE REGISTRA LA ACTIVIDAD DE AREA PARA DESPUES PODER CONSULTARLA
                $actividad_area = $this->ActividadAreaDAO->consultar_id_actividad_area(75);
                $crea_seguimiento_items[] = array(
                    'id_persona' => 202,
                    'id_area' => $actividad_area[0]->id_area_trabajo,
                    'id_actividad' => $actividad_area[0]->id_actividad_area,
                    'pedido' =>  $product['num_pedido'],
                    'item' => $val['item'],
                    'observacion' => $actividad_area[0]->nombre_actividad_area,
                    'estado' => 1,
                    'id_usuario' => 10,
                    'fecha_crea' =>  date('Y-m-d'),
                    'hora_crea' =>  date('H:i:s'),
                );
                foreach ($crea_seguimiento_items as $items) {
                    $this->SeguimientoOpDAO->insertar($items);
                }

                foreach ($crea_items_pedido as $items) {
                    $respu = $this->PedidosItemDAO->insertar($items);
                    $items['id_pedido_item'] = $respu['id'];
                    $crea_items_pedido = $items; // inserta los items en pedidos item


                    $crea_entregas_logostica[] = array(
                        'id_pedido_item' =>  $crea_items_pedido['id_pedido_item'],
                        'cantidad_factura' => $crea_items_pedido['Cant_solicitada'],
                        'id_usuario' => 79,
                        'id_factura' => 0,
                        'estado' => 1,
                        'fecha_crea' =>  date('Y-m-d'),
                        'hora_crea' => date('H:i:s'),
                    );
                }

                foreach ($crea_entregas_logostica as $items) {
                    $this->EntregasLogisticaDAO->insertar($items);
                }

                if ($crea_entregas_logostica != '') {
                    $res = [
                        'num_acta' => 'ENT' . $num_acta,
                        'num_pedido' => $num_pedido,
                    ];
                }
                return $res;
            }
        }
    }

    public function cambiar_estado_cierre($data, $estado_item, $estado_cotiza, $estado_pdf, $num_acta)
    {
        header('Content-Type: application/json');
        foreach ($data as $value) {
            $id_diagnostico_item = ($value['id_diagnostico_item']);
            $formulario_item = [
                'estado' => $estado_item,
            ];
            $condicion = 'id_diagnostico_item =' . $id_diagnostico_item;
            $modificacion = $this->SoporteItemDAO->editar($formulario_item, $condicion);

            // SE REGISTRA EL SEGUIMIENTO
            $observacion = 'DIAGNOSTICO CERRADO CON NUMERO DE ACTA ENT' . $num_acta;
            $seguimiento = GenericoControlador::agrega_seguimiento_diag($value['id_diagnostico'], $value['item'], $observacion, $_SESSION['usuario']->getid_usuario());

            if ($modificacion == 1) {
                if (isset($value['repuestos'])) {
                    foreach ($value['repuestos'] as  $repuestos) {
                        $id_cotizacion = ($repuestos['id_cotizacion']);
                        $formulario_cotiza = [
                            'estado' => $estado_cotiza,
                            'num_acta' => 'ENT' . $num_acta,
                        ];
                        $condicion = 'id_cotizacion =' . $id_cotizacion;
                        $modificacion_cotiza = $this->CotizacionItemSoporteDAO->editar($formulario_cotiza, $condicion);
                    }
                } else {
                    $consulta_cotiza = $this->CotizacionItemSoporteDAO->consultar_datos($value['id_diagnostico'], $value['item']);
                    foreach ($consulta_cotiza as $value_cotiza) {
                        $formulario_cotiza = [
                            'estado' => $estado_cotiza,
                            'num_acta' => 'ENT' . $num_acta,
                        ];
                        $condicion = 'id_cotizacion =' . $value_cotiza->id_cotizacion;
                        $modificacion_cotiza = $this->CotizacionItemSoporteDAO->editar($formulario_cotiza, $condicion);
                    }
                }
            }
        }
        $ConsultaUltimoRegistro = $this->trmDAO->ConsultaUltimoRegistro();
        $trm = $ConsultaUltimoRegistro[0]->valor_trm;
        $res = [
            'num_acta' => 'ENT' . $num_acta,
            'num_pedido' => '',
        ];
        return $res;
    }

    public function generar_pdf_acta()
    {
        // header('Content-Type: application/json');
        $num_acta = $_POST['num_acta'];
        $estado_pdf = $_POST['estado_pdf'];
        $respu = GenericoControlador::crear_acta_entrega($num_acta, $estado_pdf, '');
        return $respu;
    }
    public function generar_cotizacion()
    {
        // header('Content-Type: application/json');
        header('Content-Type: application/pdf');
        $num_cotizacion = $_POST['num_cotiza'];
        $consulta_cotizacion = $this->CotizacionItemSoporteDAO->consulta_cotiza($num_cotizacion, 1);
        $ConsultaUltimoRegistro = $this->trmDAO->ConsultaUltimoRegistro();
        $trm = $ConsultaUltimoRegistro[0]->valor_trm;
        $sentencia = 'AND t1.num_acta=0';
        if (!empty($consulta_cotizacion)) {
            foreach ($consulta_cotizacion as $value) {
                if ($value->item != 0) {
                    $consulta_item = $this->CotizacionItemSoporteDAO->consulta_item_cotiza($value->item);
                    foreach ($consulta_item as $item) {
                        $value->equipo = $item->equipo;
                        $value->serial_equipo = $item->serial_equipo;
                    }
                } else {
                    $value->equipo = '';
                    $value->serial_equipo = '';
                }
                $repuestos = $this->SoporteItemDAO->consultar_repuestos_item($value->id_diagnostico, $value->item, $sentencia);
                $value->repuestos = $repuestos;
            }
            $fecha = date('Y-m-d');
            $respu = PDF::crea_cotizacion_visita($fecha, $consulta_cotizacion, $num_cotizacion, 1, $trm, '');
        } else {
            header('Content-Type: application/json');
            $respu = [
                'status' => -1,
                'data' => 'Lo siento este pdf no existe'
            ];
        }
        echo json_encode($respu);
        return;
    }
}
