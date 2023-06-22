<?php

namespace MiApp\negocio\controladores\facturacion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\PDF;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\EntregasLogisticaDAO;
use MiApp\persistencia\dao\clientes_proveedorDAO;
use MiApp\persistencia\dao\direccionDAO;
use MiApp\persistencia\dao\control_facturacionDAO;




class InformeColombiaControlador extends GenericoControlador
{

    private $PersonaDAO;
    private $EntregasLogisticaDAO;
    private $ConsCotizacionDAO;
    private $clientes_proveedorDAO;
    private $direccionDAO;
    private $control_facturacionDAO;
    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
        $this->clientes_proveedorDAO = new clientes_proveedorDAO($cnn);
        $this->direccionDAO = new direccionDAO($cnn);
        $this->control_facturacionDAO = new control_facturacionDAO($cnn);
    }

    public function vista_informes_colombia()
    {
        parent::cabecera();
        $this->view(
            'facturacion/vista_informes_colombia'
        );
    }

    //function object_to_array($data)
    //{
    //  if (is_array($data) || is_object($data)) {
    //    $result = array();
    //  foreach ($data as $key => $value) {
    //    $result[$key] = object_to_array($value);
    //}
    //return $result;
    // }
    //return $data;
    // }

    public function informe_colombia()
    {
        header('Content-Type: application/json');
        $data = $this->EntregasLogisticaDAO->informe_colombia($_POST['fecha_crea']);
        echo json_encode($data);
        return;
    }
    public function genera_informe()
    {
        header('Content-Type: application/json');
        $datos_cabecera = $_POST;
        $items = $_POST['data_envio'];
        $tipo_documento = 8;
        $prefijo = PREFIJO[$tipo_documento];
        $documento_relacionado = $this->ConsCotizacionDAO->consultar_cons_especifico($tipo_documento);
        $cliente = $this->clientes_proveedorDAO->consultar_nit_clientes('900489958');
        $direccion = $this->direccionDAO->consulta_direccion_cliente($cliente[0]->nit);
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
        $total_documento = 0;
        $total_items = [];
        foreach ($items as $value) {
            $item_informe = [
                'v_unidad' => $value['precio_venta'] * 0.92,
                'cantidad_por_facturar' => $value['total_factura'],
                'codigo' => $value['codigo_producto'],
                'descripcion_productos' => $value['descripcion_productos']
            ];
            $operacion_linea = $value['total_factura'] * $value['precio_venta'];
            $total_documento = $total_documento + $operacion_linea;
            array_push($total_items, $item_informe);
        };
        $cabecera = [
            'fecha_elaboracion' => date('d/m/Y'),
            'cliente' => $cliente[0]->nombre_empresa,
            'orden_compra' => 'N/A',
            'pais' => $direccion[0]->nombre_pais,
            'departamento' => $direccion[0]->nombre_departamento,
            'ciudad' => $direccion[0]->nombre_ciudad,
            'direccion' => $direccion[0]->direccion,
            'numero_pedido' => 'N/A',
            'numero_lista_empaque' => $lista_empaque_numero[0]->numero_guardado,
            'numero_doc_relacionado' => $prefijo . " " . $documento_relacionado[0]->numero_guardado,
            'tipo_documento' => $tipo_documento,
            'usuario_facturacion' => $_SESSION['usuario']->getnombres() . " " . $_SESSION['usuario']->getapellidos(),
            'total_documento' => $total_documento,
        ];
        $respu = PDF::listaEmpaquePdf($cabecera, $total_items);
        echo json_encode($respu);
        return;
    }
}
