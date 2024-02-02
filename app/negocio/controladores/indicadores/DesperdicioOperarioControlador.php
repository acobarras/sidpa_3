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

class DesperdicioOperarioControlador extends GenericoControlador
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
            'indicadores/vista_desperdicio_operario',
            [
                'personas' => $this->PersonaDAO->consultar_personas(),
            ]
        );
    }

    public function indicador_desperdicio_operario()
    {
        header("Content-type: application/json; charset=utf-8");
        $fecha_desde = $_POST['fecha_desde'];
        $fecha_hasta = $_POST['fecha_hasta'];
        $orden_p = $this->ItemProducirDAO->ConsultaFechaProduccion($fecha_desde, $fecha_hasta);
        // Se buscan los operarios que realizaron la orden de produccion y se agregan datos para comparacion
        $e = [];
        foreach ($orden_p as $respu_p) {
            $datos_ml_operario = $this->MetrosLinealesDAO->metros_lineales_operario_op($respu_p->id_item_producir);
            foreach ($datos_ml_operario as $value) {
                $value->num_produccion = $respu_p->num_produccion;
                $value->mL_total = $respu_p->mL_total;
                $value->tamanio_etiq = $respu_p->tamanio_etiq;
                $value->precio_mp = $respu_p->precio_material;
                $e[] = $value;
            }
        }
        // Se unifican los registros y agrego las etiquetas por empleado
        $result = array();
        foreach ($e as $t) {
            $etiq = $this->DesperdicioOpDAO->EtiqOperarioTroq($t->num_produccion, $t->id_persona);
            $t->total_etiquetas = $etiq[0]->total_etiquetas;
            $repeat = false;
            for ($i = 0; $i < count($result); $i++) {
                if ($result[$i]->id_persona == $t->id_persona && $result[$i]->num_produccion == $t->num_produccion) {
                    $result[$i]->ml_usados += $t->ml_usados;
                    $result[$i]->m2_item += $t->m2_item;
                    $repeat = true;
                    break;
                }
            }
            if ($repeat == false)
                $result[] = $t;
        }
        foreach ($result as $calculo) {
            $avance = $this->PedidosItemDAO->AvanceOp($calculo->num_produccion);
            $porciones = explode("X", $calculo->tamanio_etiq);
            $ancho = Validacion::ReemplazaCaracter($porciones[0], ',', '.');
            $ancho = ($ancho + GAP_LATERAL) / 1000;
            $alto = $avance[0]->avance / 1000;
            $ancho_op = $avance[0]->ancho_op;
            $m2_etiquetas = $ancho * $alto;
            $teorico_etiq = $calculo->m2_item / $m2_etiquetas;
            $m2_desperdicio = ($teorico_etiq - $calculo->total_etiquetas) * $m2_etiquetas;
            $ml_desperdicio = ($m2_desperdicio / $ancho_op)*1000;
            $porcentaje_desperdicio = ($m2_desperdicio / $calculo->m2_item) * 100;
            $precio_desperdicio = $calculo->precio_mp * $m2_desperdicio;
            if ($calculo->total_etiquetas == '') {
                $porcentaje_desperdicio = 0;
                $precio_desperdicio = 0;
            }


            $calculo->m2_desperdicio = $m2_desperdicio;
            $calculo->avance = $avance[0]->avance;
            $calculo->porcentaje_desperdicio = $porcentaje_desperdicio;
            $calculo->precio_desperdicio = $precio_desperdicio;
            $calculo->ml_desperdicio = $ml_desperdicio;
            $calculo->ancho_op = $ancho_op;
        }
        $indice = $result;
        //Se dejan los datos del indicador en una variable aparte
        $indicador = [];
        foreach ($indice as $indica) {
            if ($indica->total_etiquetas != '') {
                foreach ($indicador as &$value) {
                    $repeat = false;
                    if ($indica->id_persona == $value->id_persona) {
                        $value->m2_desperdicio += $indica->m2_desperdicio;
                        $value->m2_item += $indica->m2_item;
                        $value->ml_usados += $indica->ml_usados;
                        $value->porcentaje_desperdicio += $indica->porcentaje_desperdicio;
                        $value->precio_desperdicio += $indica->precio_desperdicio;
                        $value->total_etiquetas += $indica->total_etiquetas;
                        $repeat = true;
                        break;
                    }
                }
            }
            if (!$repeat) {
                $indicador[] = clone $indica;
            }
        }


        $respu = [
            'datos_indicador' => $result,
            'indicador' => $indicador
        ];
        echo json_encode($respu);
        return;
    }
}
