<?php

namespace MiApp\negocio\controladores\indicadores;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\ItemProducirDAO;
use MiApp\persistencia\dao\MaquinasDAO;
use MiApp\persistencia\dao\DesperdicioOpDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\MetrosLinealesDAO;

class DesperdicioControlador extends GenericoControlador
{

    private $PersonaDAO;
    private $ItemProducirDAO;
    private $MaquinasDAO;
    private $DesperdicioOpDAO;
    private $PedidosItemDAO;
    private $MetrosLinealesDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->ItemProducirDAO = new ItemProducirDAO($cnn);
        $this->MaquinasDAO = new MaquinasDAO($cnn);
        $this->DesperdicioOpDAO = new DesperdicioOpDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->MetrosLinealesDAO = new MetrosLinealesDAO($cnn);
    }

    public function vista_desperdicio()
    {
        parent::cabecera();
        $this->view(
            'indicadores/vista_desperdicio',
            [
                'personas' => $this->PersonaDAO->consultar_personas(),
            ]
        );
    }

    public function indicador_desperdicio()
    {
        header("Content-type: application/json; charset=utf-8");
        $form = $_POST['form'];
        $form = Validacion::Decodifica($form);
        $fecha_desde = $form['fecha_desde'];
        $fecha_hasta = $form['fecha_hasta'];
        $orden_p = $this->ItemProducirDAO->ConsultaFechaProduccion($fecha_desde, $fecha_hasta);
        foreach ($orden_p as $respu_p) {
            $todos_empleados = [];
            // Consultar el nombre de la maquina
            $maquina = $this->MaquinasDAO->consultar_maquina_id($respu_p->maquina);
            // Cantidad Etiquetas Embobinadas de la ordend de producción
            $q_etiq = $this->DesperdicioOpDAO->consultar_entrega_etiq($respu_p->num_produccion);
            // Avance de un item de la orden de producción
            $avance = $this->PedidosItemDAO->AvanceOp($respu_p->num_produccion);
            // Datos adicionales de la orden de produccion como lo es los metros lineales reportados por los operarios
            $datos = $this->MetrosLinealesDAO->DatosOp($respu_p->id_item_producir);
            $m2_entregados = 0;
            $m2_retorno = 0;
            foreach ($datos as $respu_datos) {
                if ($respu_datos->estado_ml == 1) {
                    $emple = $this->PersonaDAO->consultar_personas_id($respu_datos->id_persona);
                    if ($respu_datos->ml_usados != 0) {
                        $todos_empleados[] = $emple[0]->nombres;
                    } else {
                        $suma = $respu_datos->metros_lineales * ($respu_datos->ancho / 1000);
                        $m2_entregados = $m2_entregados + $suma;
                    }
                } else {
                    $suma1 = $respu_datos->ml_usados * ($respu_datos->ancho / 1000);
                    $m2_retorno = $m2_retorno + $suma1;
                    $emple = "Sin Registro";
                    $todos_empleados[] = $emple;
                }
            }
            $items_op = $this->PedidosItemDAO->ConsultaItemOp($respu_p->num_produccion);
            $total_venta = 0;
            $tintas = [];
            foreach ($items_op as $res_op) {
                //obtener el valor de las tintas
                $caracter = "-";
                $posicion_coincidencia = strpos($res_op->codigo, $caracter); //posicion empezando desde el (-)
                $tinta_codigo = substr($res_op->codigo, ($posicion_coincidencia + 6), 2);
                $tintas[] = $tinta_codigo;
                $total_venta = $total_venta + $res_op->total;
            }

            $valor_item = $this->PedidosItemDAO->ValorItemVentaOp($respu_p->num_produccion);
            $valor_venta = $valor_item[0]->v_unidad_min * $respu_p->cant_op;
            $porciones = explode("X", $respu_p->tamanio_etiq);
            $ancho = Validacion::ReemplazaCaracter($porciones[0], ',', '.');
            $ancho = ($ancho + GAP_LATERAL) / 1000;
            $alto = $avance[0]->avance / 1000;
            $m2_etiquetas = $ancho * $alto;
            $m2_total_etiq = $m2_etiquetas * $q_etiq[0]->total_etiquetas;
            $m2_utilizados = $m2_entregados - $m2_retorno;
            $m2_desperdicio = $m2_utilizados - $m2_total_etiq;
            $porcentaje_desperdicio = 0;
            if ($m2_utilizados != 0) {
                $porcentaje_desperdicio = ($m2_desperdicio / $m2_utilizados) * 100;
            }
            $material_usado = $respu_p->material;
            if ($respu_p->material_solicitado != '') {
                $material_usado = $respu_p->material_solicitado;
            }
            $precio_mp_desperdicio = $m2_desperdicio * $respu_p->precio_material;
            $total_op = $valor_item[0]->v_unidad_min * $q_etiq[0]->total_etiquetas;
            $porcentaje_venta = 0;
            if ($total_op != 0) {
                $porcentaje_venta = ($precio_mp_desperdicio / $total_op) * 100;
            }

            $respu_p->nombre_maquina = $maquina[0]->nombre_maquina;
            $respu_p->todos_empleados = $todos_empleados;
            $respu_p->m2_entregados = $m2_entregados;
            $respu_p->m2_retorno = $m2_retorno;
            $respu_p->m2_utilizados = $m2_utilizados;
            $respu_p->total_etiquetas = $q_etiq[0]->total_etiquetas;
            $respu_p->m2_total_etiq = $m2_total_etiq;
            $respu_p->m2_desperdicio = $m2_desperdicio;
            $respu_p->porcentaje_desperdicio = $porcentaje_desperdicio;
            $respu_p->material_usado = $material_usado;
            $respu_p->precio_mp_desperdicio = $precio_mp_desperdicio;
            $respu_p->valor_venta = $valor_venta;
            $respu_p->tintas = $tintas;
            $respu_p->v_unidad_max = $valor_item[0]->v_unidad_max;
            $respu_p->v_unidad_min = $valor_item[0]->v_unidad_min;
            $respu_p->v_unidad_prom = $valor_item[0]->v_unidad_prom;
            $respu_p->total_venta = $total_venta;
            $respu_p->total_op = $total_op;
            $respu_p->porcentaje_venta = $porcentaje_venta;
        }

        $respu = $orden_p;
        echo json_encode($respu);
        return;
    }

    public function consulta_op()
    {
        header("Content-type: application/json; charset=utf-8");
        $num_produccion = $_POST['num_op'];
        // Metros lineales Maquinas troquelado
        $ml_empleados = $this->ItemProducirDAO->consultar_item_producir_num($num_produccion);
        if (empty($ml_empleados)) {
            $datos_ml = [];
            $datos_embobinado = [];
        } else {
            $id_item_producir = $ml_empleados[0]->id_item_producir;
            $datos_ml = $this->MetrosLinealesDAO->DatosOp($id_item_producir);
            foreach ($datos_ml as $value) {
                $value->num_produccion = $num_produccion;
                $persona = $this->PersonaDAO->consultar_personas_id($value->id_persona);
                if ($value->estado_ml == 2) {
                    $value->nombre_persona = '<span class="fw-bolder">Devolución A Inventario : </span>' . $persona[0]->nombres . " " . $persona[0]->apellidos;;
                } else {
                    if (!empty($persona)) {
                        $value->nombre_persona = $persona[0]->nombres . " " . $persona[0]->apellidos;
                    } else {
                        $value->nombre_persona = 'Persona Eliminada';
                    }
                }
            }
            // Datos Embobinado
            $avance = $this->PedidosItemDAO->AvanceOp($num_produccion);
            $datos_embobinado = $this->DesperdicioOpDAO->ReportesEmbobinadoOp($num_produccion);
            foreach ($datos_embobinado as $value) {
                $value->avance = $avance[0]->avance;
            }
        }
        $respu = array(
            'datos_troquelado' => $datos_ml,
            'datos_embobinado' => $datos_embobinado
        );
        echo json_encode($respu);
        return;
    }

    public function consulta_fechas_op()
    {
        header("Content-type: application/json; charset=utf-8");
        $fecha_desde = $_POST['fecha_crea'];
        $fecha_hasta = $_POST['fecha_fin'];
        $consulta_item = $this->ItemProducirDAO->consulta_item_fechas($fecha_desde, $fecha_hasta);
        foreach ($consulta_item as $value) {
            if ($value->estado_ml == 2) {
                $value->nombre_persona = '<span class="fw-bolder">Devolución A Inventario : </span>' . $value->nombres . " " . $value->apellidos;
            } else {
                if (!empty($value->estado_persona == 1)) {
                    $value->nombre_persona = $value->nombres . " " . $value->apellidos;
                } else {
                    $value->nombre_persona = 'Persona Eliminada';
                }
            }
        }
        $respu = array(
            'datos_troquelado' => $consulta_item,
        );
        echo json_encode($respu);
        return;
    }
    public function consulta_fechas_embobinado()
    {
        header("Content-type: application/json; charset=utf-8");
        $fecha_desde = $_POST['fecha_crea'];
        $fecha_hasta = $_POST['fecha_fin'];
        $avance = $this->PedidosItemDAO->consulta_avance_op($fecha_desde, $fecha_hasta);
        $respu = array(
            'datos_embobinado' => $avance
        );
        echo json_encode($respu);
        return;
    }

    public function editar_ml_entregados()
    {
        header("Content-type: application/json; charset=utf-8");
        $data = $_POST['data'];
        $cambios = $_POST['cambios'];
        $estado_ml = $data['estado_ml'];
        $datos_item_producir = $this->ItemProducirDAO->consultar_item_producir_num($data['num_produccion']);
        if ($estado_ml == 2) {
            $cambio_ml = array(
                'ml_usados' => $cambios[0]['value']
            );
            $condicion = 'id_metros_lineales =' . $data['id_metros_lineales'];
            $editar_ml = $this->MetrosLinealesDAO->editar($cambio_ml, $condicion);
            $suma_ml_retorno = $this->MetrosLinealesDAO->MetrosLinealesRetorno($data['id_item_producir'], $estado_ml);
            $suma_ml = $suma_ml_retorno[0]->suma_ml; // Devuelve la suma de los metros lineales devueltos
            // Sacar los metros cuadrados para poder editar item producir
            $m2_retorno = ($suma_ml * $datos_item_producir[0]->m2_retorno) / $datos_item_producir[0]->ml_retorno;
            $editar_item_producir = array(
                'm2_retorno' => $m2_retorno,
                'ml_retorno' => $suma_ml
            );
        } else {
            $cambio_ml = array(
                'metros_lineales' => $cambios[0]['value']
            );
            $condicion = 'id_metros_lineales =' . $data['id_metros_lineales'];
            $editar_ml = $this->MetrosLinealesDAO->editar($cambio_ml, $condicion);
            $suma_ml_retorno = $this->MetrosLinealesDAO->MetrosLinealesRetorno($data['id_item_producir'], $estado_ml);
            $suma_ml = $suma_ml_retorno[0]->suma_ml; // Devuelve la suma de los metros lineales entregados 
            // Sacar los metros cuadrados para poder editar item producir
            $m2_retorno = ($suma_ml * $datos_item_producir[0]->m2_inicial) / $datos_item_producir[0]->mL_descontado;
            $editar_item_producir = array(
                'mL_descontado' => $suma_ml,
                'm2_inicial' => $m2_retorno
            );
        }
        $condicion_item_producir = 'id_item_producir =' . $datos_item_producir[0]->id_item_producir;
        $respu = $this->ItemProducirDAO->editar($editar_item_producir, $condicion_item_producir);
        echo json_encode($respu);
        return;
    }

    public function editar_desperdicio()
    {
        header("Content-type: application/json; charset=utf-8");
        $data = $_POST['data'];
        $cambios = $_POST['cambios'];
        $validar = $this->DesperdicioOpDAO->ConsultaIdMetrosLineales($data['id_metros_lineales']);
        // Validamos si se encuentra el id en la tabla desperdicio_op si no se encuentra no realizamos ningun cambio
        if (empty($validar)) {
            $respu = -1;
        } else {
            // creamos el array para editar la tabla metros lineales
            $edita_ml = array(
                'ml_usados' => $cambios[1]['value'],
                'id_persona' => $cambios[0]['value']
            );
            $condicion = 'id_metros_lineales =' . $data['id_metros_lineales'];
            $this->MetrosLinealesDAO->editar($edita_ml, $condicion);
            //  Creamos el array para modificar la tabla desperdicio_op
            $editar_desperdicio = array(
                'id_persona' => $cambios[0]['value'],
                'ml_empleado' => $cambios[1]['value']
            );
            $condicion1 = 'id_desperdicio =' . $validar[0]->id_desperdicio;
            $this->DesperdicioOpDAO->editar($editar_desperdicio, $condicion1);
            $respu = 1;
        }
        echo json_encode($respu);
        return;
    }

    public function editar_etiquetas()
    {
        header("Content-type: application/json; charset=utf-8");
        $data = $_POST['data'];
        $cambios = $_POST['cambios'];
        // sacar los nuevos metros lineales empleados
        $ml_empleados = $cambios[2]['value'];
        $q_etiq_antiguas = $data['cantidad_etiquetas'];
        $q_etiq_cambio = $cambios[1]['value'];
        // $nuevo_ml = ($ml_empleados * $q_etiq_cambio) / $q_etiq_antiguas;
        // Editamos el desperdicio
        $editar_desperdicio = array(
            'id_persona' => $cambios[0]['value'],
            'ml_empleado' => $ml_empleados,
            'cantidad_etiquetas' => $q_etiq_cambio,
        );
        $condicion1 = 'id_desperdicio =' . $data['id_desperdicio'];
        $this->DesperdicioOpDAO->editar($editar_desperdicio, $condicion1);
        $respu = 1;
        echo json_encode($respu);
        return;
    }

    public function consulta_participacion()
    {
        header("Content-type: application/json; charset=utf-8");
        $consulta_op = $this->ItemProducirDAO->participa_op($_POST['num_op']);
        $m2_entregados = 0;
        foreach ($consulta_op as $value) {
            $valor_item = $this->PedidosItemDAO->ValorItemVentaOp($value->num_produccion);
            $avance = $this->PedidosItemDAO->AvanceOp($value->num_produccion);
            $q_etiq = $this->DesperdicioOpDAO->consultar_entrega_etiq($value->num_produccion);
            $porciones = explode("X", $value->tamanio_etiq);
            $ancho = Validacion::ReemplazaCaracter($porciones[0], ',', '.');
            $ancho = ($ancho + GAP_LATERAL) / 1000;
            $alto = $avance[0]->avance / 1000;
            $m2_etiquetas = $ancho * $alto;
            $m2_total_etiq = $m2_etiquetas * $value->cantidad_etiquetas;
            $suma = $value->ml_usados * ($value->ancho / 1000);

            $m2_entregados =  $suma;
            $m2_utilizados = $m2_entregados;
            $m2_desperdicio = $m2_utilizados - $m2_total_etiq;
            $precio_mp_desperdicio = $m2_desperdicio * $value->precio_material;
            $total_op = $valor_item[0]->v_unidad_min * $value->cantidad_etiquetas;
            $value->precio_mp_desperdicio = $precio_mp_desperdicio;
            $value->total_op = $total_op;
        }
        echo json_encode($consulta_op);
        return;
        print_r($consulta_op);
    }
}
