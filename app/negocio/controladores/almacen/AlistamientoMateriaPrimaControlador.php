<?php

namespace MiApp\negocio\controladores\almacen;

use MiApp\negocio\generico\GenericoControlador;

use MiApp\persistencia\dao\ItemProducirDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\TintasDAO;
use MiApp\persistencia\dao\SeguimientoProduccionDAO;
use MiApp\persistencia\dao\MetrosLinealesDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\entrada_tecnologiaDAO;
use MiApp\persistencia\dao\ubicacionesDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\AreaTrabajoDAO;


use MiApp\negocio\util\Envio_Correo;


class AlistamientoMateriaPrimaControlador extends GenericoControlador
{
    private $ItemProducirDAO;
    private $PedidosItemDAO;
    private $TintasDAO;
    private $SeguimientoProduccionDAO;
    private $MetrosLinealesDAO;
    private $productosDAO;
    private $entrada_tecnologiaDAO;
    private $ubicacionesDAO;
    private $SeguimientoOpDAO;
    private $AreaTrabajoDAO;


    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->ItemProducirDAO = new ItemProducirDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->TintasDAO = new TintasDAO($cnn);
        $this->SeguimientoProduccionDAO = new SeguimientoProduccionDAO($cnn);
        $this->MetrosLinealesDAO = new MetrosLinealesDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->entrada_tecnologiaDAO = new entrada_tecnologiaDAO($cnn);
        $this->ubicacionesDAO = new ubicacionesDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->AreaTrabajoDAO = new AreaTrabajoDAO($cnn);
    }

    public function vista_alista_materia_prima_op()
    {
        parent::cabecera();
        $this->view(
            'almacen/vista_alista_materia_prima_op',
            [
               "ubicacion" => $this->entrada_tecnologiaDAO->consultar_ubicacion_bobina_ancho()
            ]
        );
    }

    public function consultar_pendientes_mp_op()
    {
        header('Content-Type: application/json'); //convierte a json
        $sesion = parent::validarSesion();
        $op = $this->ItemProducirDAO->consultar_maquina_produccion('4,5');
        foreach ($op as $valores) {
            $valores->materiales = $this->MetrosLinealesDAO->consultar_metros_lineales_especificos($valores->id_item_producir);
        }
        $resultado['data'] = $op;
        echo json_encode($resultado);
    }
    public function consultar_items_op()
    {
        header('Content-Type: application/json');
        $items = $this->PedidosItemDAO->ConsultaNumeroPedidoOp($_POST['num_produccion']);
        $tintas = $this->TintasDAO->consultar_tintas();
        foreach ($items as $valor) {
            //calcular cantidad faltante 
            $valor->cant_faltante = $valor->Cant_solicitada - $valor->cant_bodega;
            //Ml del item 
            $a = ($valor->cant_faltante * str_replace(',', '.', $valor->avance));
            $valor->metrosl = "" . $a / ($valor->cav_montaje * 1000) . "";
            //----------------------------------------------------------------------
            //m2 del item 
            $valor->metros2 = "" . (($valor->ancho_material * $valor->metrosl) / 1000) . "";

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

        $resultado['data'] = $items;
        echo json_encode($resultado);
    }
    public function proceso_turno_produccion()
    {
        header('Content-Type: application/json'); //convierte a json

        //REGISTRAR SEGUIMIENTO PRODUCCION
        $Segu_Prod['num_produccion'] = $_POST['num_produccion'];
        $Segu_Prod['id_area'] = 3; //produccion
        $Segu_Prod['id_actividad'] = 10; //en turno de producir
        $Segu_Prod['id_maquina'] = $_POST['maquina']; //en turno de producir
        $Segu_Prod['observacion_op'] = '';
        $Segu_Prod['estado_produccion'] = 1;
        $Segu_Prod['id_persona'] = $_SESSION['usuario']->getId_persona();
        $Segu_Prod['fecha_crea'] = date('Y-m-d');
        $Segu_Prod['hora_crea'] = date('H:i:s');
        $this->SeguimientoProduccionDAO->insertar($Segu_Prod);
        
        
        //ENVIAR A 6 "EN TURNO DE PRODUCCION" EN ITEM PRODUCIR
        $condicion = ' num_produccion =' . $_POST['num_produccion'];
        $item_p['estado_item_producir'] = 6;
        $respuesta = $this->ItemProducirDAO->editar($item_p, $condicion);
        $respuesta = 1;

        //ENVIAR A 10 "EN TURNO DE PRODUCCION" EN ITEM PEDIDO
        $condicion = ' n_produccion =' . $_POST['num_produccion'];
        $pedidos_item['id_estado_item_pedido'] = 10;
        $respuesta = $this->PedidosItemDAO->editar($pedidos_item, $condicion);
        $respuesta = 1;

        $respu = [];
        if (!empty($respuesta)) {
            $respu = [
                'status' => '1',
                'msg' => 'Se alisto material O.P Correctamente.',

            ];
        } else {
            $respu = [
                'status' => '2',
                'msg' => 'Error al procesar.',

            ];
        }
        echo json_encode($respu);
        return;
    }

    public function consulta_materiales()
    {
        header('Content-Type: application/json'); //convierte a json
        $material = $this->productosDAO->consultar_productos_especifico($_POST['material']);
        $id_material = $material[0]->id_productos;
        $materiales =  $this->entrada_tecnologiaDAO->consultar_seguimiento_bobina($id_material);
        $respu = [];
        foreach ($materiales as $value) {
            if (intval($value->ML) > 0.0 || intval($value->M2) > 0.0) {
                $value->id_productos = $id_material;
                $respu[] = $value;
            }
        }
        $resultado['data'] = $respu;
        echo json_encode($resultado);
    }
    public function imprimir_etiquetas_bobinas()
    {
        parent::cabecera();
        $this->view(
            'almacen/vista_imprime_etiquetas_bob'
        );
    }
    public function agrega_material_completo()
    {
        header('Content-Type: application/json'); //convierte a json
        if (!isset($_POST['parcial'])) {
            $datos = $_POST['array_cantidad'];
            $num_produccion = $datos[0]['num_produccion'];
            $maquina = $datos[0]['maquina'];
            foreach ($datos as $valor) {
                $entrada_tecno['documento'] = $valor['num_produccion'];
                $entrada_tecno['ubicacion'] = intval($valor['ancho']);
                $entrada_tecno['codigo_producto'] = $valor['codigo'];
                $entrada_tecno['id_productos'] = $valor['id_productos'];
                $entrada_tecno['ancho'] = $valor['ancho'];
                $entrada_tecno['metros'] = $valor['ml'];
                $entrada_tecno['salida'] = $valor['m2'];
                $entrada_tecno['id_usuario'] = $_SESSION['usuario']->getId_usuario();
                $entrada_tecno['fecha_crea'] = date('Y-m-d H:i:s');

                $this->entrada_tecnologiaDAO->insertar($entrada_tecno);


                $metros_lineales['id_item_producir'] = $valor['id_item_producir'];
                $metros_lineales['ancho'] =  $valor['ancho'];
                $metros_lineales['codigo_material'] =  $valor['codigo'];
                $metros_lineales['metros_lineales'] =  $valor['ml'];
                $metros_lineales['estado_ml'] =  1;
                $metros_lineales['id_persona'] =  $_SESSION['usuario']->getId_persona();
                $metros_lineales['fecha_crea'] =  date('Y-m-d H:i:s');

                $this->MetrosLinealesDAO->insertar($metros_lineales);
            }
        } else {
            $num_produccion = $_POST['num_produccion'];
            $maquina = $_POST['maquina'];
        }


        //consultar los item de la orden de produccion
        $consultar_items = $this->PedidosItemDAO->ConsultaNumeroPedidoOp($num_produccion);
        //recorrer los item y insertar seguimiento
        foreach ($consultar_items as $item) {
            $seguimiento_op['id_persona'] = $_SESSION['usuario']->getId_persona();
            $seguimiento_op['id_area'] = 3; //produccion
            $seguimiento_op['id_actividad'] = 10; //en turno de producir
            $seguimiento_op['pedido'] = $item->num_pedido;
            $seguimiento_op['item'] = $item->item;
            $seguimiento_op['observacion'] = '';
            $seguimiento_op['id_usuario'] = $_SESSION['usuario']->getId_usuario();
            $seguimiento_op['fecha_crea'] = date('Y-m-d');
            $seguimiento_op['hora_crea'] = date('H:i:s');
            $seguimiento_op['estado'] = 1;
            $this->SeguimientoOpDAO->insertar($seguimiento_op);
            //ACTUALIZAR ESTADO DEL PEDIDO ITEM 10 "INICIO PRODUCCION"
            $p_item['id_estado_item_pedido'] = 10;
            $this->PedidosItemDAO->editar($p_item, 'id_pedido_item =' . $item->id_pedido_item);
        }

        //REGISTRAR SEGUIMIENTO PRODUCCION
        $segui_prod['num_produccion'] = $num_produccion;
        $segui_prod['id_area'] = 3; //produccion
        $segui_prod['id_actividad'] = 10; //en turno de producir
        $segui_prod['id_maquina'] = $maquina; //en turno de producir
        $segui_prod['observacion_op'] = '';
        $segui_prod['estado_produccion'] = 1;
        $segui_prod['id_persona'] = $_SESSION['usuario']->getId_persona();
        $segui_prod['fecha_crea'] = date('Y-m-d');
        $segui_prod['hora_crea'] = date('H:i:s');

        $this->SeguimientoProduccionDAO->insertar($segui_prod);

        //ACTUALIZAR EL ESTADO DE ITEM PRODUCIR A 6 "INICIO PRODUCCION"
        $condicion = 'num_produccion=' . $num_produccion;
        $item_p['estado_item_producir'] = 6;
        $respuesta = $this->ItemProducirDAO->editar($item_p, $condicion);
        $respu = [];
        if (!empty($respuesta)) {
            $respu = [
                'status' => '1',
                'msg' => 'Exito al alistar.',
            ];
        } else {
            $respu = [
                'status' => '-2',
                'msg' => 'Error al procesar.',

            ];
        }
        echo json_encode($respu);
        return;
    }
    public function retener_op()
    {
        header('Content-Type: application/json');
        $arrayMotivos = $_POST['arrayMotivos'];
        $arrayorden = $_POST['arrayorden'];
        $cantidad_tintas = $_POST['cantidad_tintas'];


        //REGISTRAR SEGUIMIENTO PRODUCCION
        $segui_prod['num_produccion'] = $arrayorden['num_produccion'];
        $segui_prod['id_area'] = 3; //produccion
        $segui_prod['id_actividad'] = 10; //en turno de producir
        $segui_prod['id_maquina'] = $_POST['maquina'];
        $segui_prod['observacion_op'] = '';
        $segui_prod['estado_produccion'] = 1;
        $segui_prod['id_persona'] = $_SESSION['usuario']->getId_persona();
        $segui_prod['fecha_crea'] = date('Y-m-d');
        $segui_prod['hora_crea'] = date('H:i:s');

        $this->SeguimientoProduccionDAO->insertar($segui_prod);

        //ACTUALIZAR EL ESTADO DE ITEM PRODUCIR A 6 "INICIO PRODUCCION"
        $condicion = 'id_item_producir =' . $arrayorden['id_item_producir'];
        $item_p['estado_item_producir'] = 5;
        $respuesta = $this->ItemProducirDAO->editar($item_p, $condicion);

        Envio_Correo::enviar_alistamiento_retenido($arrayMotivos, $arrayorden, $cantidad_tintas);
        echo json_encode(true);
    }

    public function vista_alista_materia_producion()
    {
        parent::cabecera();
        $this->view(
            'almacen/vista_alista_materia_producion',
            [
                "area" => $this->AreaTrabajoDAO->consultar_area_trabajo(),
                "ubicacion_etiq" => $this->ubicacionesDAO->tabla_ubicaciones(),
            ]
        );
    }

    public function ubicacion()
    {
        header('Content-Type: application/json');
        $_POST['id_productos'];
        $ubicacion = $this->ubicacionesDAO->tabla_ubicaciones();
        echo json_encode($ubicacion);
        return;
    }
}
