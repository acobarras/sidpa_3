<?php

namespace MiApp\negocio\controladores\produccion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\TintasDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\MaquinasDAO;
use MiApp\persistencia\dao\ItemProducirDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\GestionPqrDAO;

class PendienteOpControlador extends GenericoControlador
{

    private $PedidosDAO;
    private $TintasDAO;
    private $ConsCotizacionDAO;
    private $MaquinasDAO;
    private $ItemProducirDAO;
    private $PedidosItemDAO;
    private $SeguimientoOpDAO;
    private $GestionPqrDAO;
    private $productosDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->PedidosDAO = new PedidosDAO($cnn);
        $this->TintasDAO = new TintasDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
        $this->MaquinasDAO = new MaquinasDAO($cnn);
        $this->ItemProducirDAO = new ItemProducirDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->GestionPqrDAO = new GestionPqrDAO($cnn);
    }

    public function vista_pendientes_op()
    {
        parent::cabecera();
        $this->view(
            'produccion/vista_pendientes_op',
            [
                // 'maquinas' => $this->MaquinasDAO->consultar_maquinas_proembo(),
                'maquinas' => $this->MaquinasDAO->consultar_maquinas(),
            ]
        );
    }

    public function consultar_items_pendientes_op()
    {
        header('Content-Type: application/json');
        $items = $this->PedidosDAO->consultar_items_pendientes_op();
        $num_produccion = $this->ConsCotizacionDAO->consultar_cons_especifico(5);
        $tintas = $this->TintasDAO->consultar_tintas();
        foreach ($items as $valor) {
            // Validar si es una pqr
            $num_pqr = '';
            $valida_pqr = $this->GestionPqrDAO->pqr_produccion($valor->num_pedido);
            if (!empty($valida_pqr)) {
                $num_pqr = $valida_pqr[0]->num_pqr;
            }
            $valor->num_pqr = $num_pqr;
            if ($valor->id_material != 0) {
                $material = $this->productosDAO->consultar_productos_id($valor->id_material);
                if (count($material) > 0) {
                    //codigo material asignado
                    $valor->material = $material[0]->codigo_producto;
                    if ($material[0]->costo == 0) {
                        $valor->precio_material = $material[0]->precio1;
                    } else {
                        $valor->precio_material = $material[0]->costo;
                    }
                } else {
                    $valor->material = "<b>No Existe</b>";
                    $valor->precio_material = 0;
                }
            } else {
                $valor->material = "<b>SIN ASIGNAR</b>";
                $valor->precio_material = 0;
            }
            //mostrar solo tamaño
            $valor->codigoT = strstr($valor->codigo, '-', true);

            //Ml del item m2 del item
            $a = ($valor->cant_op * str_replace(',', '.', $valor->avance));
            if ($valor->cav_montaje == '' || $valor->cav_montaje == 0) {
                $valor->metrosl = "No Hay Cavidad";
                $valor->metros2 = 0;
                $valor->tintas = 0;
            } else {
                $valor->metrosl = "" . $a / ($valor->cav_montaje * 1000) . "";
                $valor->metros2 = "" . (($valor->ancho_material * $valor->metrosl) / 1000) . "";
                $caracter = "-";
                $posicion_coincidencia = strpos($valor->codigo, $caracter);
                $tinta_codigo = substr($valor->codigo, ($posicion_coincidencia + 6), 2);
                foreach ($tintas as $value) {
                    if ($value->numeros == $tinta_codigo) {
                        $valor->tintas = $value->num_tintas;
                    }
                }
            }

            $valor->num_produccion = $num_produccion[0]->numero_guardado;
        }
        $resultado['data'] = $items;
        echo json_encode($resultado);
    }

    public function generar_num_produccion()
    {
        header('Content-Type: application/json');
        $data = array();
        $ml_total = 0;
        $cant_op = 0;
        foreach ($_POST['arrayinfo'] as $key => $value) {
            $data[] = $value['datos'];
            $ml_total = $ml_total + $value['datos']['metrosl'];
            $cant_op = $cant_op + $value['datos']['cant_op'];
        }
        $id_maquina = $_POST['maquina'];
        //validar si ya existe la orden de produccion 
        $info_num = $this->ItemProducirDAO->consultar_item_producir_num($data[0]['num_produccion']);
        $respuesta = false;
        if (empty($info_num)) {
            // Crear registro en item producir
            $item_producir = [
                'num_produccion' => $data[0]['num_produccion'],
                'espera_material' => 0,
                'mL_total' => round($ml_total, 0, PHP_ROUND_HALF_UP),
                'mL_descontado' => 0,
                'ml_retorno' => 0,
                'tamanio_etiq' => $data[0]['codigoT'],
                'cant_op' => $cant_op,
                'ancho_op' => $data[0]['ancho_material'],
                'material' => $data[0]['material'],
                'precio_material' => $data[0]['precio_material'],
                'maquina' => $id_maquina,
                'estado_item_producir' => 1,
                'id_usuario' => $_SESSION['usuario']->getId_usuario(),
                'fecha_crea' => date('Y-m-d H:i:s')
            ];
            $this->ItemProducirDAO->insertar($item_producir);
            //Asignar el numero de la orden 
            foreach ($data as $valor) {
                //asignar el numero de orden de produccion
                $edita_item_pedido = [
                    'n_produccion' => $valor['num_produccion'],
                    'id_estado_item_pedido' => 18,
                ];
                $condicion_pedido_item = 'id_pedido_item =' . $valor['id_pedido_item'];
                $this->PedidosItemDAO->editar($edita_item_pedido, $condicion_pedido_item);
                // se coloca en la tabla seguimientos op
                $observacion = 'No. ' . $valor['num_produccion'];
                $seguimientoOP = [
                    'id_area' => 3, //en planeacion
                    'id_actividad' => 42, //GENERADA O.P
                    'id_persona' => $_SESSION['usuario']->getId_persona(),
                    'id_usuario' => $_SESSION['usuario']->getId_usuario(),
                    'pedido' => $valor['num_pedido'],
                    'item' => $valor['item'],
                    'observacion' => $observacion,
                    'estado' => 1,
                    'fecha_crea' => date('Y-m-d'),
                    'hora_crea' => date('H:i:s'),
                ];
                $this->SeguimientoOpDAO->insertar($seguimientoOP);
                // // Aumentar el consecutivo de produccion
                $nuevo_numero = $data[0]['num_produccion'] + 1;
                $consecutivo = ['numero_guardado' => $nuevo_numero];
                $cond_cons = 'id_consecutivo = 5';
                $num_suma = $this->ConsCotizacionDAO->editar($consecutivo, $cond_cons);
                $respu = array(
                    'estado' => 1,
                    'mensaje' => 'La orden de producción se genero de manera correcta.',
                );
                $respuesta = true;
            }
        } else {
            $respu = array(
                'estado' => -1,
                'mensaje' => 'Lo sentimos no se pudo procesar la petición por favor recargue la pagina e intente nuevamente, la operacion fue cancelada.',
            );
            $respuesta = true;
        }

        if ($respuesta) {
            echo json_encode($respu);
            return;
        }
    }

    public function consultar_op()
    {
        header('Content-Type: application/json');
        $num_produccion = $_POST['num_produccion'];
        $consulta = $this->ItemProducirDAO->consultar_item_producir_num($num_produccion);
        foreach ($consulta as $value) {
            $maqui = $this->MaquinasDAO->consultar_maquina_id($value->maquina);
            $value->nombre_maquina = $maqui[0]->nombre_maquina;
        }
        echo json_encode($consulta);
        return;
    }

    public function modifica_op()
    {
        header('Content-Type: application/json');
        $id_item_producir = $_POST['id'];
        $formulario = Validacion::Decodifica($_POST['form']);
        $condicion = 'id_item_producir =' . $id_item_producir;
        $respu = $this->ItemProducirDAO->editar($formulario, $condicion);
        echo json_encode($respu);
        return;
    }
}
