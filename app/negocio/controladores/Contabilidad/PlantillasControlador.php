<?php

namespace MiApp\negocio\controladores\Contabilidad;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\SeguimientoProduccionDAO;
use MiApp\persistencia\dao\DesperdicioOpDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\MetrosLinealesDAO;



class PlantillasControlador extends GenericoControlador
{
    private $PedidosItemDAO;
    private $SeguimientoProduccionDAO;
    private $DesperdicioOpDAO;
    private $ConsCotizacionDAO;
    private $MetrosLinealesDAO;


    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->SeguimientoProduccionDAO = new SeguimientoProduccionDAO($cnn);
        $this->DesperdicioOpDAO = new DesperdicioOpDAO($cnn);
        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
        $this->MetrosLinealesDAO = new MetrosLinealesDAO($cnn);
    }

    public function vista_plantillas()
    {
        parent::cabecera();

        $this->view(
            'Contabilidad/vista_plantillas'
        );
    }

    public function consulta_ordenes_embo_entregadas()
    {
        header('Content-Type:application/json');
        $fecha_desde = $_POST['fecha_desde'];
        $fecha_hasta = $_POST['fecha_hasta'];
        $cons = $this->SeguimientoProduccionDAO->ConsultaOpEntregadasContabilidad($fecha_desde, $fecha_hasta);
        $entra_sale = $this->ConsCotizacionDAO->consultar_cons_especifico(7);
        $numero_guardado = $entra_sale[0]->numero_guardado;
        foreach ($cons as $value) {
            $value->fecha_crea_actividad = date("d/m/Y", strtotime($value->fecha_crea_actividad));
            $salida_mp = $this->material_usado_op($value, $numero_guardado);
            $value->salida_mp = $salida_mp;
            $value->empresa = 'ACOBARRAS SAS';
            $value->encabezado = 'OP';
            $value->campo_vacio = '';
            $value->tercero = RESPONSABLE;
            $value->nit = NIT_CONTABLE;
            $value->principal = 'Principal';
            $value->unidad = 'Und.';
            $value->por_distribucion = 100;
            $qetiq_total = $this->DesperdicioOpDAO->cantidad_etiquetas_op($value->num_produccion);
            $AvanceOp = $this->PedidosItemDAO->AvanceOp($value->num_produccion);
            $value->codigo_producto = $AvanceOp[0]->codigo_producto;
            $value->q_etiquetas = $qetiq_total[0]->q_etiquetas;
            $value->entra_sale = $numero_guardado;
            $value->abierto_cerrado = '0';
            $value->distribucion = 'Manual';
            $items_op = $this->items_op($value->num_produccion, $numero_guardado, $value->fecha_crea_actividad);
            $numero_guardado = $numero_guardado + 1;
            $value->items_op = $items_op;
        }
        echo json_encode($cons);
        return;
    }

    public function material_usado_op($data, $documento)
    {
        $material = [];
        $material_usado = $data->material;
        $ancho_usado = $data->ancho_op;
        $qetiq_total = $this->DesperdicioOpDAO->cantidad_etiquetas_op($data->num_produccion);
        $q_etiquetas = $qetiq_total[0]->q_etiquetas;
        $codigo_producto = $this->PedidosItemDAO->ConsultaNumeroPedidoOp($data->num_produccion);
        $codigo = $codigo_producto[0]->codigo;
        $consumo = $codigo_producto[0]->consumo;
        $avance = $this->PedidosItemDAO->AvanceOp($data->num_produccion);
        $datos = $this->MetrosLinealesDAO->DatosOp($data->id_item_producir);
        $m2_entregados = 0;
        $m2_retorno = 0;
        foreach ($datos as $respu_datos) {
            if ($respu_datos->estado_ml == 1) {
                if ($respu_datos->ml_usados != 0) {
                } else {
                    $suma = $respu_datos->metros_lineales * ($respu_datos->ancho / 1000);
                    $m2_entregados = $m2_entregados + $suma;
                }
            } else {
                $suma1 = $respu_datos->ml_usados * ($respu_datos->ancho / 1000);
                $m2_retorno = $m2_retorno + $suma1;
            }
        }
        $porciones = explode("X", $data->tamanio_etiq);
        $ancho = Validacion::ReemplazaCaracter($porciones[0], ',', '.');
        $ancho = ($ancho + GAP_LATERAL) / 1000;
        $alto = $avance[0]->avance / 1000;
        $m2_etiquetas = $ancho * $alto;
        $m2_total_etiq = $m2_etiquetas * $q_etiquetas;
        $m2_utilizados = $m2_entregados - $m2_retorno;
        $m2_desperdicio = $m2_utilizados - $m2_total_etiq;
        if ($data->material_solicitado != '') {
            $material_usado = $data->material_solicitado;
            $ancho_usado = $data->ancho_confirmado;
        }
        for ($i = 0; $i < 2; $i++) {
            $bodega = 'Principal';
            $cantidad = $q_etiquetas * $consumo;
            if ($i == 1) {
                $bodega = 'Despercicio';
                $cantidad = $m2_desperdicio;
            }
            $datos_op = [
                'empresa' => 'ACOBARRAS SAS 2023',
                'tipo_documento' => 'SA',
                'campo_vacio' => '',
                'abierto_cerrado' => 0,
                'documento' => $documento,
                'fecha_crea_actividad' => $data->fecha_crea_actividad,
                'responsable' => RESPONSABLE,
                'nit' => NIT_CONTABLE,
                'nota' => 'SALIDA DE ALMACEN GENERADA OP ' . $data->num_produccion,
                'forma_pago' => 'Salida MP a Proceso',
                'material' => $material_usado,
                'bodega' => $bodega,
                'unidad' => 'm²',
                'cantidad' => number_format($cantidad, 2, '.', ''),
                'centro_costo' => 'OP' . $data->num_produccion,
                'codigo' => $codigo,
            ];
            array_push($material, $datos_op);
        }
        return  $material;
    }

    public function items_op($num_produccion, $documento, $fecha_crea_actividad)
    {
        $items_op = $this->PedidosItemDAO->ConsultaNumeroPedidoOp($num_produccion);
        foreach ($items_op as $items) {
            $etiquetas = $this->DesperdicioOpDAO->etiquetas_pedido_item($items->id_pedido_item);
            $items->q_etiq_reporte = $etiquetas[0]->q_etiq_item;
            $items->empresa = 'ACOBARRAS SAS';
            $items->tipo_documento = 'EPT';
            $items->documento = $documento;
            $items->responsable = RESPONSABLE;
            $items->nit = NIT_CONTABLE;
            $items->nota = 'EPT GENERADA POR LA OP' . $items->n_produccion;
            $items->forma_pago = 'Entrada de Producto Terminado';
            $items->abierto_cerrado = '0';
            $items->campo_vacio = '';
            $items->principal = 'Principal';
            $items->unidad = 'Und.';
            $items->fecha_crea_actividad = $fecha_crea_actividad;
            $items->centro_costo = 'OP' . $items->n_produccion;
            // Faltan los metros cuadrados que corresponden a la orden de produccion y precio materia prima precio 1
        }
        return $items_op;
    }
}
