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

class DesperdicioMaquinaControlador extends GenericoControlador
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
            'indicadores/vista_desperdicio_maquina',
        );
    }

    public function indicador_desperdicio_maquina()
    {
        header("Content-type: application/json; charset=utf-8");
        $fecha_desde = $_POST['fecha_desde'];
        $fecha_hasta = $_POST['fecha_hasta'];
        $orden_p = $this->ItemProducirDAO->ConsultaFechaProduccion($fecha_desde, $fecha_hasta);
        foreach ($orden_p as $respu_p) {
            // Adisionamos el nombre de la maquina
            $maquina = $this->MaquinasDAO->consultar_maquina_id($respu_p->maquina);
            //Sacamos los metros lineales y cuadrados de la orden de produccion
            $datos_ml_maquina = $this->MetrosLinealesDAO->metros_lineales_maquina($respu_p->id_item_producir);
            $ml_orden = 0;
            $m2_orden = 0;
            foreach ($datos_ml_maquina as $value) {
                $ml_orden = $ml_orden + $value->ml_usados;
                $m2_orden = $m2_orden + $value->m2_item;
            }
            // Etiquetas reportadas de la orden
            $etiquetas = $this->DesperdicioOpDAO->consultar_entrega_etiq($respu_p->num_produccion);
            $total_etiq_orden = $etiquetas[0]->total_etiquetas;
            $avance = $this->PedidosItemDAO->AvanceOp($respu_p->num_produccion);
            $porciones = explode("X", $respu_p->tamanio_etiq);
            $ancho = Validacion::ReemplazaCaracter($porciones[0], ',', '.');
            $ancho = ($ancho + GAP_LATERAL) / 1000;
            $alto = $avance[0]->avance / 1000;
            $m2_etiquetas = $ancho * $alto;
            $teorico_etiq = $m2_orden / $m2_etiquetas;
            $m2_desperdicio_orden = ($teorico_etiq - $total_etiq_orden) * $m2_etiquetas;
            if ($m2_orden == 0) {
                $porcentaje_desperdicio = 0;
            } else {
                $porcentaje_desperdicio = ($m2_desperdicio_orden / $m2_orden) * 100;
            }
            $precio_desperdicio = $respu_p->precio_material * $m2_desperdicio_orden;

            $respu_p->nombre_maquina = $maquina[0]->nombre_maquina;
            $respu_p->m2_desperdicio_orden = $m2_desperdicio_orden;
            $respu_p->ml_orden = $ml_orden;
            $respu_p->m2_orden = $m2_orden;
            $respu_p->total_etiq_orden = $total_etiq_orden;
            $respu_p->porcentaje_desperdicio = $porcentaje_desperdicio;
            $respu_p->precio_desperdicio = $precio_desperdicio;
        }

        //Se dejan los datos del indicador en una variable aparte
        $indicador = [];
        foreach ($orden_p as $indica) {
            if ($indica->total_etiq_orden != '') {
                $repeat = false;
                for ($i = 0; $i < count($indicador); $i++) {
                    if ($indicador[$i]->maquina == $indica->maquina) {
                        $indicador[$i]->ml_orden += $indica->ml_orden;
                        $indicador[$i]->m2_orden += $indica->m2_orden;
                        $indicador[$i]->total_etiq_orden += $indica->total_etiq_orden;
                        $indicador[$i]->m2_desperdicio_orden += $indica->m2_desperdicio_orden;
                        $indicador[$i]->porcentaje_desperdicio += $indica->porcentaje_desperdicio;
                        $indicador[$i]->precio_desperdicio += $indica->precio_desperdicio;
                        $repeat = true;
                        break;
                    }
                }
                if ($repeat == false)
                $indicador[] = $indica;
            }
        }

        $respu = [
            'datos_indicador' => $orden_p,
            'indicador' => $indicador
        ];
        echo json_encode($respu);
        return;
    }
}
