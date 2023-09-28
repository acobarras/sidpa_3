<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\PDF;
use MiApp\persistencia\dao\UsuarioDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\TrazPedidoDAO;
use MiApp\persistencia\dao\clientes_proveedorDAO;
use MiApp\persistencia\dao\direccionDAO;
use MiApp\persistencia\dao\ItemProducirDAO;
use MiApp\persistencia\dao\control_facturacionDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\GestionPqrDAO;

class DescargaControlador extends GenericoControlador
{

    private $UsuarioDAO;
    private $PersonaDAO;
    private $PedidosDAO;
    private $PedidosItemDAO;
    private $TrazPedidoDAO;
    private $clientes_proveedorDAO;
    private $direccionDAO;
    private $ItemProducirDAO;
    private $control_facturacionDAO;
    private $productosDAO;
    private $GestionPqrDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->UsuarioDAO = new UsuarioDAO($cnn);
        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->PedidosDAO = new PedidosDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->TrazPedidoDAO = new TrazPedidoDAO($cnn);
        $this->clientes_proveedorDAO = new clientes_proveedorDAO($cnn);
        $this->direccionDAO = new direccionDAO($cnn);
        $this->ItemProducirDAO = new ItemProducirDAO($cnn);
        $this->control_facturacionDAO = new control_facturacionDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->GestionPqrDAO = new GestionPqrDAO($cnn);
    }

    public function vista_descargar_pdf()
    {
        parent::cabecera();
        $this->view(
            'configuracion/vista_descargar_pdf'
        );
    }

    public function generar_pdf_num_pedido()
    {
        header('Content-type: application/pdf');
        //crear variable para guardar num pedido
        $num_pedido = $_POST['num_pedido'];
        //consultar pedido a descargar
        $pedido = $this->PedidosDAO->consultar_descarga_pedido($num_pedido); //necesario
        //si el pedido llega vacio no se genera pdf
        if ($pedido == NULL) {
            header('Content-type: application/json');
            echo json_encode('0');
            return;
        }
        //crear variables de consulta para armar el pdf del pedido 
        $id_persona = $this->UsuarioDAO->consultarIdPersona($pedido[0]->id_usuario);
        $persona = $this->PersonaDAO->consultar_personas_id($id_persona[0]->id_persona); //necesario
        $items = $this->PedidosItemDAO->ConsultaIdPedido($pedido[0]->id_pedido); //necesario
        $pertenece = $this->clientes_proveedorDAO->cliente_pertenece($pedido[0]->id_cli_prov); //necesario
        $direc_entre = $this->direccionDAO->consultaIdDireccion($pedido[0]->id_dire_entre);
        $direc_radic = $this->direccionDAO->consultaIdDireccion($pedido[0]->id_dire_radic);
        // $traz = $this->TrazPedidoDAO->consultar_traz_pedido_id($pedido[0]->id_pedido);
        //agregar campos faltantes para armar el pdf 
        $pedido[0]->pertenece = $pertenece[0]->pertenece;
        $pedido[0]->forma_pago = $pertenece[0]->forma_pago;
        $pedido[0]->dias_dados = $pertenece[0]->dias_dados;
        $pedido[0]->nombre_empresa = $pertenece[0]->nombre_empresa;
        $pedido[0]->nit = $pertenece[0]->nit;

        $pedido[0] = (array) $pedido[0];
        //funcion para generar el pdf 
        PDF::pdf_pedidos($persona[0], $pedido[0], $items, $direc_entre[0], $direc_radic[0]);
    }

    public function generar_pdf_orden_produccion()
    {
        header('Content-type: application/pdf');
        $num_produccion = $_REQUEST['orden_produccion'];
        //consultar items de la orden de porduccion 
        $items = $this->PedidosItemDAO->ConsultaNumeroPedidoOp($num_produccion);
        $num_pqr = '';
        $valida_pqr = $this->GestionPqrDAO->pqr_produccion($items[0]->num_pedido);
        if (!empty($valida_pqr)) {
            $num_pqr = $valida_pqr[0]->num_pqr;
        }
        //consultar datos ->fecha crea  del OP 
        $item_producir = $this->ItemProducirDAO->consultar_item_producir_num($num_produccion);
        // Datos de la cabecera del pdf
        $tamano_op = strstr($items[0]->codigo, '-', true);
        $magnetico = $items[0]->magnetico;
        $avance = $items[0]->avance;
        $cav_montaje = $items[0]->cav_montaje;
        $material = $item_producir[0]->material;
        if ($item_producir[0]->material_solicitado != '') {
            $material = $item_producir[0]->material_solicitado;
        }
        $material = $item_producir[0]->material;
        $ancho_material = $item_producir[0]->ancho_op;
        $cant_op = $item_producir[0]->cant_op;
        $m2_total = (($cant_op * $avance) / ($cav_montaje * 1000) * $ancho_material) / 1000;
        // Se genera el qr de la cabecera del pedido
        $qr_produccion = Validacion::QR($num_produccion);
        $cabecera_pdf = array(
            'fecha_compromiso_global' => $item_producir[0]->fecha_comp,
            'fecha_op' => $item_producir[0]->fecha_crea,
            'num_produccion' => $num_produccion,
            'qr_produccion' => $qr_produccion,
            'tamano_etiqueta' => $tamano_op,
            'magnetico' => $magnetico,
            'avance' => $avance,
            'codigo_material' => $material,
            'ancho_material' => $ancho_material,
            'm2_total' => number_format($m2_total, 2, ',', '.'),
            'cant_op' => $cant_op,
            'num_pqr' => $num_pqr
        );
        // Generar la informacion de troquelado

        //for para convertir cada item en un array 
        for ($i = 0; $i < count($items); $i++) {
            $array[$i] = (array) $items[$i];
        }
        //GENERAR LOS CODIGOS QR PARA LOS CAMPOS REQUERIDOS
        foreach ($array as $key => $v) {
            $array[$key]['QR_item_codigo'] = Validacion::QR($v['codigo']);
            $array[$key]['QR_pedido_item'] = Validacion::QR($v['num_pedido'] . "-" . $v['item']);
            $cavidad_cliente = Validacion::DesgloceCodigo($v['codigo'], 5, 1);
            $etiq_por_avance = $v['cant_x']  * $v['avance'];
            $eti_cav = $etiq_por_avance / $cavidad_cliente;
            $ml_item = $eti_cav / 1000;
            $array[$key]['ml_item'] = $ml_item;
        }
        //----------------------------------------------------------------------
        //GENERAR PDF ORDEN DE PRODUCCION
        // print_r($cabecera_pdf);
        // return;
        PDF::pdf_num_produccion($cabecera_pdf, $array);

        // For para recorrer los codigos QR e ir a eliminarlos si existen en el directorio 
        unlink($qr_produccion);
        foreach ($array as $v) {
            if (file_exists($v['QR_pedido_item'])) {
                unlink($v['QR_pedido_item']);
                unlink($v['QR_item_codigo']);
            }
        }
    }

    public function generar_pdf_lista_empaque()
    {
        header('Content-type: application/pdf');
        $num_lista_empaque = $_POST['lista_empaque'];
        $totaliza = $_POST['totaliza'];
        $datos = $this->control_facturacionDAO->consulta_lista_empaque($num_lista_empaque);
        $datos_direccion = $this->direccionDAO->consultaIdDireccion($datos[0]->id_dire_entre);

        $documento = $datos[0]->num_factura;
        if ($datos[0]->num_factura == 0) {
            $documento = $datos[0]->num_remision;
        }
        $id_persona = $this->UsuarioDAO->consultarIdPersona($datos[0]->fact_por);
        $persona = $this->PersonaDAO->consultar_personas_id($id_persona[0]->id_persona); //necesario
        $cabecera = [
            'fecha_elaboracion' => $datos[0]->fecha_factura,
            'cliente' => $datos[0]->nombre_empresa,
            'orden_compra' => $datos[0]->orden_compra,
            'pais' => $datos_direccion[0]->nombre_pais,
            'departamento' => $datos_direccion[0]->nombre_departamento,
            'ciudad' => $datos_direccion[0]->nombre_ciudad,
            'direccion' => $datos_direccion[0]->direccion,
            'numero_pedido' => $datos[0]->num_pedido,
            'numero_lista_empaque' => $num_lista_empaque,
            'numero_doc_relacionado' => $datos[0]->tipo_documento_letra . " " . $documento,
            'tipo_documento' => $datos[0]->tipo_documento,
            'usuario_facturacion' => $persona[0]->nombres . " " . $persona[0]->apellidos,
            'iva' => $datos[0]->iva,
        ];
        $items_factura = [];
        foreach ($datos as $value) {
            $consu_des = $this->productosDAO->consultar_productos_especifico($value->codigo);
            $items_factura[] = [
                'codigo' => $value->codigo,
                'v_unidad' => $value->v_unidad,
                'cantidad_por_facturar' => $value->cantidad_factura,
                'descripcion_productos' => $consu_des[0]->descripcion_productos
            ];
        }
        // $items_factura = json_decode( json_encode( $datos ), true );
        $respu = PDF::listaEmpaquePdf($cabecera, $items_factura, $totaliza);
        Validacion::DELETE_QR();
        echo json_encode($respu);
        return;
    }
}
