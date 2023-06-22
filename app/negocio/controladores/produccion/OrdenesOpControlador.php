<?php

namespace MiApp\negocio\controladores\produccion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\ItemProducirDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\entrada_tecnologiaDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\TintasDAO;
use MiApp\persistencia\dao\CodigosEspecialesDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\MetrosLinealesDAO;
use MiApp\persistencia\dao\SeguimientoProduccionDAO;


class OrdenesOpControlador extends GenericoControlador
{

    private $ItemProducirDAO;
    private $SeguimientoOpDAO;
    private $entrada_tecnologiaDAO;
    private $PedidosItemDAO;
    private $TintasDAO;
    private $CodigosEspecialesDAO;
    private $productosDAO;
    private $MetrosLinealesDAO;
    private $SeguimientoProduccionDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->ItemProducirDAO = new ItemProducirDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->entrada_tecnologiaDAO = new entrada_tecnologiaDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->TintasDAO = new TintasDAO($cnn);
        $this->CodigosEspecialesDAO = new CodigosEspecialesDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->MetrosLinealesDAO = new MetrosLinealesDAO($cnn);
        $this->SeguimientoProduccionDAO = new SeguimientoProduccionDAO($cnn);
    }

    public function vista_orden_produccion()
    {
        parent::cabecera();
        $this->view(
            'produccion/vista_orden_produccion'
        );
    }

    public function ordenes_produccion()
    {
        header('Content-Type: application/json');
        $ordenes = $this->ItemProducirDAO->consultar_item_producir_ordenes(1);
        foreach ($ordenes as $valor) {
            $valor->cantidad_inventario = $this->entrada_tecnologiaDAO->consultar_inv_product_codigo($valor->material);
            if ($valor->cantidad_inventario == NULL) {
                $valor->cantidad_inventario = 0;
            }
        }
        $data['data'] = $ordenes;
        echo json_encode($data);
    }

    public function consultar_items_orden()
    {
        header('Content-Type: application/json');
        $items = $this->PedidosItemDAO->ConsultaNumeroPedidoOp($_POST['num_produccion']);
        $tintas = $this->TintasDAO->consultar_tintas();
        foreach ($items as $valor) {
            //calcular cantidad faltante 
            $valor->cant_faltante = $valor->cant_op;
            //Ml del item 
            $a = ($valor->cant_faltante * $valor->avance);
            $valor->metrosl = $a / ($valor->cav_montaje * 1000);
            //----------------------------------------------------------------------
            //m2 del item 
            $valor->metros2 = (($valor->ancho_material * $valor->metrosl) / 1000);
            //obtener el valor de las tintas
            $caracter = "-";
            $posicion_coincidencia = strpos($valor->codigo, $caracter); //posicion empezando desde el (-)
            $tinta_codigo = substr($valor->codigo, ($posicion_coincidencia + 6), 2);
            //cav de presentacion 
            $cav_presentacion = substr($valor->codigo, ($posicion_coincidencia + 5), 1); //obtener la cav presentacion
            $valor->cav_presentacion = $cav_presentacion;
            foreach ($tintas as $value) {
                if ($value->numeros == $tinta_codigo) {
                    $valor->tintas = $value->num_tintas;
                }
            }
        }
        echo json_encode($items);
        return;
    }

    public function consultar_inventario_anchos_material()
    {
        header('Content-Type: application/json');
        $codigo_especial = $this->CodigosEspecialesDAO->consultar_codigos($_POST['codigo']);
        $i = 0;
        if ($codigo_especial != []) {
            foreach ($codigo_especial as $valor) {
                $material[$i++] = $valor->codigo_relacion;
            }
        } else {
            $material = $_POST['codigo'];
        }
        $data = $material;
        echo json_encode($data);
    }

    public function inventario_anchos_material()
    {
        header('Content-Type: application/json');
        $codigo = $_POST['codigo'];
        $material_final = $this->productosDAO->ConsultaProductoCodigo($codigo);
        $id_productos = $material_final[0]->id_productos;
        $material =  $this->entrada_tecnologiaDAO->consultar_seguimiento_bobina($id_productos);
        $data = [];
        foreach ($material as $key => $value) {
            if (floatval($value->M2) > 0) {
                $value->id_productos = $id_productos;
                $value->id_posicion = $key;
                $data[] = $value;
            }
        }
        echo json_encode($data);
    }

    public function separa_materia_prima()
    {
        header('Content-Type: application/json');
        $datos = $_POST['array_cant_m'];
        $item_producir = $this->ItemProducirDAO->consultar_item_producir_num($datos[0]['num_produccion']);
        $datos_material = $this->productosDAO->ConsultaProductoCodigo($datos[0]['codigo']);
        $m2_inicial = 0;
        $ml_descontado = $item_producir[0]->mL_descontado;
        $id_item_producir = $item_producir[0]->id_item_producir;
        $this->descuento_inventario_bobinas($datos, $id_item_producir);
        foreach ($datos as $value) {
            $m2_inicial = $m2_inicial + $value['m2_sacados'];
            $ml_descontado = $ml_descontado + $value['cantidad'];
        }
        if ($datos_material[0]->costo != 0) {
            $precio_material = $datos_material[0]->costo;
        } else {
            $precio_material = $datos_material[0]->precio1;
        }
        // Array para editar item_producir
        $item_p = [
            'estado_item_producir' => 3,
            'm2_inicial' => round($m2_inicial, 2),
            'mL_descontado' => $ml_descontado,
            'material' => $datos[0]['codigo'],
            'precio_material' => $precio_material,
        ];
        $condicion_item_p = ' id_item_producir =' . $id_item_producir;
        $respuesta = $this->ItemProducirDAO->editar($item_p, $condicion_item_p);
        $seguimiento_produccion = [
            'num_produccion' => $datos[0]['num_produccion'],
            'id_maquina' => $item_producir[0]->maquina,
            'id_persona' => $_SESSION['usuario']->getId_persona(),
            'id_area' => 1,
            'id_actividad' => 4,
            'observacion_op' => 'MATERIA PRIMA SEPARADA',
            'fecha_crea' => date('Y-m-d'),
            'hora_crea' => date('H:i:s')
        ];
        $this->SeguimientoProduccionDAO->insertar($seguimiento_produccion);
        echo json_encode($respuesta);
    }

    public function separa_compra_materia_prima()
    {
        header('Content-Type: application/json');
        $material = $_POST['material'];
        $m2_inicial = 0;
        $ml_descontado = 0;
        if (is_array($_POST['datos'])) {
            $datos = $_POST['datos'];
            $num_produccion = $datos[0]['num_produccion'];
            $item_producir = $this->ItemProducirDAO->consultar_item_producir_num($num_produccion);
            $id_item_producir = $item_producir[0]->id_item_producir;
            $this->descuento_inventario_bobinas($datos,$id_item_producir);
            foreach ($datos as $value) {
                $m2_inicial = $m2_inicial + $value['m2_sacados'];
                $ml_descontado = $ml_descontado + $value['cantidad'];
            }
        } else {
            $num_produccion = $_POST['datos'];
            $item_producir = $this->ItemProducirDAO->consultar_item_producir_num($num_produccion);
            $id_item_producir = $item_producir[0]->id_item_producir;
        }
        $datos_material = $this->productosDAO->ConsultaProductoCodigo($material);
        if (intval($datos_material[0]->costo) != 0) {
            $precio_material = $datos_material[0]->costo;
        } else {
            $precio_material = $datos_material[0]->precio1;
        }
        // Array para editar item_producir
        $item_p = [
            'estado_item_producir' => 2,
            'm2_inicial' => round($m2_inicial, 2),
            'mL_descontado' => $ml_descontado,
            'material' => $material,
            'precio_material' => $precio_material,
        ];
        $condicion_item_p = ' id_item_producir =' . $id_item_producir;
        $respuesta = $this->ItemProducirDAO->editar($item_p, $condicion_item_p);
        $seguimiento_produccion = [
            'num_produccion' => $num_produccion,
            'id_maquina' => $item_producir[0]->maquina,
            'id_persona' => $_SESSION['usuario']->getId_persona(),
            'id_area' => 5,
            'id_actividad' => 2,
            'observacion_op' => 'MATERIAL EN COMPRA',
            'fecha_crea' => date('Y-m-d'),
            'hora_crea' => date('H:i:s')
        ];
        $this->SeguimientoProduccionDAO->insertar($seguimiento_produccion);
        echo json_encode($respuesta);
    }

    public function descuento_inventario_bobinas($datos, $id_item_producir)
    {
        foreach ($datos as $value) {
            // array para Insertar en entrada tecnologia
            $entrada_tecnologia = [
                'documento' => $value['num_produccion'],
                'ubicacion' => intval($value['ancho']),
                'codigo_producto' => $value['codigo'],
                'id_productos' => $value['id_productos'],
                'ancho' => $value['ancho'],
                'metros' => $value['cantidad'],
                'salida' => $value['m2_sacados'],
                'estado_inv' => 4,
                'id_usuario' => $_SESSION['usuario']->getId_usuario(),
                'fecha_crea' => date('Y-m-d H:i:s')
            ];
            $this->entrada_tecnologiaDAO->insertar($entrada_tecnologia);
            // array para insertar los metros lineales entregados a produccion
            $metros_lineales = [
                'id_item_producir' => $id_item_producir,
                'ancho' => $value['ancho'],
                'codigo_material' => $value['codigo'],
                'metros_lineales' => $value['cantidad'],
                'estado_ml' => 1,
                'id_persona' => $_SESSION['usuario']->getId_persona(),
                'fecha_crea' => date('Y-m-d H:i:s')
            ];
            $this->MetrosLinealesDAO->insertar($metros_lineales);
        }
    }
}
