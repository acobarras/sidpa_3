<?php

namespace MiApp\negocio\controladores\facturacion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\PDF;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\EntregasLogisticaDAO;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\control_facturacionDAO;
use MiApp\persistencia\dao\SeguimientoFacturaDAO;
use MiApp\persistencia\dao\direccionDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\clientes_proveedorDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\EmpresasDAO;

class FacturacionControlador extends GenericoControlador
{

    private $EntregasLogisticaDAO;
    private $ConsCotizacionDAO;
    private $PersonaDAO;
    private $PedidosDAO;
    private $control_facturacionDAO;
    private $SeguimientoFacturaDAO;
    private $direccionDAO;
    private $SeguimientoOpDAO;
    private $clientes_proveedorDAO;
    private $PedidosItemDAO;
    private $EmpresasDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->PedidosDAO = new PedidosDAO($cnn);
        $this->control_facturacionDAO = new control_facturacionDAO($cnn);
        $this->SeguimientoFacturaDAO = new SeguimientoFacturaDAO($cnn);
        $this->direccionDAO = new direccionDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->clientes_proveedorDAO = new clientes_proveedorDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->EmpresasDAO = new EmpresasDAO($cnn);
    }

    public function vista_pendiente_facturacion()
    {
        parent::cabecera();
        $this->view(
            'facturacion/vista_pendiente_facturacion',
            [
                'personas' => $this->PersonaDAO->consultar_personas(),
                'usuario' => $_SESSION['usuario']->getnombres() . " " . $_SESSION['usuario']->getapellidos()
            ]
        );
    }

    public function tabla_facturacion()
    {
        header('Content-Type: application/json');
        $res = $this->EntregasLogisticaDAO->ConsultarPedidosFacturar();
        foreach ($res as $item) {
            $items = $this->EntregasLogisticaDAO->CantidadItemPedido($item->id_pedido);
            $empresa = $this->EmpresasDAO->consulta_empresa_id($item->pertenece);
            $item->cantidad_item = $items->q_item;
            $item->cantidad_reporte = $items->q_reporte;
            $item->forma_pago = FORMA_PAGO[$item->forma_pago];
            $item->parcial = RECIBE_PARCIAL[$item->parcial];
            $item->empresa_pertenece = $empresa[0]->nombre_compania;
        }
        $tabla['data'] = $res;
        echo json_encode($tabla);
        return;
    }

    public function pedido_item_facturacion($id_pedido = '')
    {
        header('Content-Type: application/json');
        if ($id_pedido == '') {
            $id_pedido = $_POST['id_pedido'];
            $res = false;
        } else {
            $res = true;
        }
        $resultado = $this->PedidosItemDAO->ConsultaItemPedido($id_pedido);
        foreach ($resultado as $value) {
            $reporte_factura = $this->EntregasLogisticaDAO->CantidadFactura($value->id_pedido_item);
            $value->cantidad_facturada = $reporte_factura[0]->cantidad_facturada;
            $value->cantidad_por_facturar = $reporte_factura[0]->cantidad_por_facturar;
        }
        if ($res) {
            return $resultado;
        } else {
            $data = $resultado;
            echo json_encode($data);
            return;
        }
    }

    public function consecutivo_documento()
    {
        header('Content-Type: application/json');
        $dato_consulta = $_POST['dato_consulta'];
        $num_fact = $this->ConsCotizacionDAO->consultar_cons_especifico($dato_consulta);
        $num_fact[0]->prefijo = PREFIJO[$num_fact[0]->id_consecutivo];
        echo json_encode($num_fact);
        return;
    }

    public function descargar_orden_compra()
    {
        $num_pedido = $_POST['num_pedido'];
        $data = $this->PedidosDAO->consultar_descarga_pedido($num_pedido);
        $id_pedido = $data[0]->id_pedido;
        $orden_compra = $data[0]->orden_compra;
        $nombre = $orden_compra . "_" . $id_pedido . ".pdf";
        $ruta_archivo = CARPETA_IMG . PROYECTO . "/PDF/ocompra/" . $nombre;
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=\"$nombre\"");
        readfile($ruta_archivo);
    }

    public function lista_empaque()
    {
        header("Content-Type: application/pdf");
        $datos = $_POST['envio'];
        $datos_pendiente_pedido = $_POST['storage'];
        $items_factura = $datos['items'];
        $tipo_documento = $datos['tipo_documento'];
        $num_pedido = $datos['num_pedido'];
        $parametro = "t1.num_pedido =" . $num_pedido;
        $datos_cabecera = $this->PedidosDAO->consulta_pedidos($parametro);
        $datos_direccion = $this->direccionDAO->consultaIdDireccion($datos_cabecera[0]->id_dire_entre);
        $prefijo = PREFIJO[$tipo_documento];
        // Validar si el consecutivo ya fue utilizado
        $documento_relacionado = $this->ConsCotizacionDAO->consultar_cons_especifico($tipo_documento);
        // Se valida si el documento ya se utilizo
        if ($documento_relacionado[0]->numero_guardado == $datos['numero_factura_consulta'] && $documento_relacionado[0]->id_consecutivo == $datos['tipo_documento']) {
            //Aumento el consecutivo para evitar sea utilizado el documento y la lista de empaque
            $lista_empaque_numero = $this->ConsCotizacionDAO->consultar_cons_especifico(10);
            $num_lista_empaque = $lista_empaque_numero[0]->numero_guardado;
            $nuevo_num_lista = $num_lista_empaque + 1;
            $edita_lista_empaque = [
                'numero_guardado' => $nuevo_num_lista
            ];
            $condicion_lista_empaque = 'id_consecutivo =10';
            $this->ConsCotizacionDAO->editar($edita_lista_empaque, $condicion_lista_empaque);
            $doc_relacionado = [
                'numero_guardado' => $documento_relacionado[0]->numero_guardado + 1
            ];
            $condicion_documento = ' id_consecutivo =' . $tipo_documento;
            $this->ConsCotizacionDAO->editar($doc_relacionado, $condicion_documento);
            // SE REGISTRA EL NUMERO DE DOCUMENTO PARA SU CONTROL
            $num_factura = $documento_relacionado[0]->numero_guardado;
            $num_remision = 0;
            if ($tipo_documento == 11 || $tipo_documento == 12) {
                $num_factura = 0;
                $num_remision = $documento_relacionado[0]->numero_guardado;
            }
            $datos_factura = [
                'tipo_documento' => $tipo_documento,
                'num_factura' => $num_factura,
                'num_remision' => $num_remision,
                'num_lista_empaque' => $num_lista_empaque,
                'estado' => 1,
                'fecha_crea' => date('Y-m-d H:i:s'),
                'id_usuario' => $_SESSION['usuario']->getid_usuario()
            ];
            $respu_control_factura = $this->control_facturacionDAO->insertar($datos_factura);
            $id_control_factura = $respu_control_factura['id'];
            // Se recorre los item para su respectivo proceso
            $total_documento = 0;
            foreach ($items_factura as $value) {
                $total_documento = $total_documento + $value['total'];
                // Sacar el id del item a facturar en la tabla de entregas logistica
                $item_factura = $this->EntregasLogisticaDAO->ItemFacturacionId($value['id_pedido_item']);
                // SE EDITA LA TABLA ENTREGAS LOGISTICA CON LOS DATOS QUIEN FACTURO Y CON QUE DOCUMENTO
                $entregas_logistica = [
                    'tipo_documento' => $prefijo,
                    'id_factura' => $id_control_factura,
                    'fact_por' => $_SESSION['usuario']->getid_usuario(),
                    'fecha_factura' => date('Y-m-d'),
                    'estado' => 2
                ];
                $condicion = 'id_entrega =' . $item_factura[0]->id_entrega;
                $reporte_factura = $this->EntregasLogisticaDAO->CantidadFactura($value['id_pedido_item']);
                $cantidad_facturada = $reporte_factura[0]->cantidad_facturada;
                $cantidad_por_facturar = $reporte_factura[0]->cantidad_por_facturar;
                $cant_tabla_fact = $cantidad_por_facturar + $cantidad_facturada;
                $cantidad_cliente = $value['Cant_solicitada'];
                if ($value['difer_menos'] != 0) {
                    $cantidad_cliente = $value['Cant_solicitada'] - (($value['Cant_solicitada'] * $value['porcentaje']) / 100);
                }
                if ($cant_tabla_fact >= $cantidad_cliente) {
                    $id_actividad = ID_ACTIVIDAD[$tipo_documento];
                } else {
                    $id_actividad = ID_ACTIVIDAD_PARCIAL[$tipo_documento];
                }
                $seguimiento_op = [
                    'id_persona' => $_SESSION['usuario']->getid_persona(),
                    'id_area' => 2,
                    'id_actividad' => $id_actividad,
                    'pedido' => $value['num_pedido'],
                    'item' => $value['item'],
                    'observacion' => $prefijo . " " . $documento_relacionado[0]->numero_guardado . " LE-" . $num_lista_empaque,
                    'estado' => 1,
                    'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                    'fecha_crea' => date('Y-m-d'),
                    'hora_crea' => date('H:i:s')
                ];
                $this->EntregasLogisticaDAO->editar($entregas_logistica, $condicion);
                $this->SeguimientoOpDAO->insertar($seguimiento_op);
            }
            // SE COMIENZA CON EL SEGUIMIENTO A LAS FACTURA
            $seg_factura = array(
                'id_control_factura' => $id_control_factura,
                'actividad' => $id_actividad,
                'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                'fecha_crea' => date('Y-m-d H:i:s')

            );
            $segui_fact = $this->SeguimientoFacturaDAO->insertar($seg_factura);
            $cabecera = [
                'fecha_elaboracion' => date('d/m/Y'),
                'cliente' => $datos_cabecera[0]->nombre_empresa,
                'orden_compra' => $datos_cabecera[0]->orden_compra,
                'pais' => $datos_direccion[0]->nombre_pais,
                'departamento' => $datos_direccion[0]->nombre_departamento,
                'ciudad' => $datos_direccion[0]->nombre_ciudad,
                'direccion' => $datos_direccion[0]->direccion,
                'numero_pedido' => $datos_cabecera[0]->num_pedido,
                'numero_lista_empaque' => $lista_empaque_numero[0]->numero_guardado,
                'numero_doc_relacionado' => $prefijo . " " . $documento_relacionado[0]->numero_guardado,
                'tipo_documento' => $tipo_documento,
                'usuario_facturacion' => $_SESSION['usuario']->getnombres() . " " . $_SESSION['usuario']->getapellidos(),
                'total_documento' => $total_documento,
            ];
            if ($tipo_documento === '6') {
                $respu = PDF::cuentaCobroPdf($cabecera, $items_factura);
            } else {
                $respu = PDF::listaEmpaquePdf($cabecera, $items_factura);
            }
            // Aca iria
            if ($datos_pendiente_pedido != 0) {
                foreach ($datos_pendiente_pedido as $value) {
                    $inserta_sobrante_pedido = [
                        'id_pedido_item' => $value['id_pedido_item'],
                        'cantidad_factura' => $value['cantidad_factura'],
                        'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                        'estado' => 1,
                        'fecha_crea' => date('Y-m-d H:i:s'),
                        'hora_crea' => date('H:i:s'),
                    ];
                    $this->EntregasLogisticaDAO->insertar($inserta_sobrante_pedido);
                }
            }
            Validacion::DELETE_QR();
            return;
        } else { // Los documentos ya se utilizaron
            $respu = '';
        }
        echo json_encode($respu);
        return;
    }

    public function editar_cantidad_envio()
    {
        header('Content-Type: application/json');
        $datos = $_POST['data'];
        $id_pedido_item = $datos['id_pedido_item'];
        $consulta_item_factura = $this->EntregasLogisticaDAO->valida_edicion_item($id_pedido_item);
        if (!empty($consulta_item_factura)) {
            $edita_entrega_logistica = [
                'cantidad_factura' => $_POST['numero']
            ];
            $condicion = 'id_entrega = ' . $consulta_item_factura[0]->id_entrega;
            $grabo = $this->EntregasLogisticaDAO->editar($edita_entrega_logistica, $condicion);
            $tabla = $this->pedido_item_facturacion($consulta_item_factura[0]->id_pedido);
            $respu = [
                'status' => 1,
                'msg' => 'Dato Modificado correctamente.',
                'table' => $tabla,
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
}
