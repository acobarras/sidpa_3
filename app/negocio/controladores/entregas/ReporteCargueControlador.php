<?php

namespace MiApp\negocio\controladores\entregas;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\control_facturacionDAO;
use MiApp\persistencia\dao\direccionDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\EntregasLogisticaDAO;
use MiApp\persistencia\dao\PagoFletesDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\GestionPqrDAO;

class ReporteCargueControlador extends GenericoControlador
{

    private $control_facturacionDAO;
    private $direccionDAO;
    private $productosDAO;
    private $EntregasLogisticaDAO;
    private $PagoFletesDAO;
    private $SeguimientoOpDAO;
    private $GestionPqrDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->control_facturacionDAO = new control_facturacionDAO($cnn);
        $this->direccionDAO = new direccionDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
        $this->PagoFletesDAO = new PagoFletesDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->GestionPqrDAO = new GestionPqrDAO($cnn);
    }

    public function vista_reporte_cargue()
    {
        parent::cabecera();
        $this->view(
            'entregas/vista_reporte_cargue'
        );
    }

    public function consulta_lista_empaque()
    {
        header('Content-Type: application/json');
        $num_lista_empaque = $_POST['numero_lista_de_empaque'];
        $datos = $this->control_facturacionDAO->consulta_lista_empaque($num_lista_empaque);
        foreach ($datos as $value) {
            $consu_des = $this->productosDAO->consultar_productos_especifico($value->codigo);
            $datos_direccion = $this->direccionDAO->consultaIdDireccion($value->id_dire_entre);
            $value->nombre_pais = $datos_direccion[0]->nombre_pais;
            $value->nombre_departamento = $datos_direccion[0]->nombre_departamento;
            $value->nombre_ciudad = $datos_direccion[0]->nombre_ciudad;
            $value->direccion = $datos_direccion[0]->direccion;
            $value->descripcion_productos = $consu_des[0]->descripcion_productos;
        }
        $cabecera = $datos[0];
        $envio = [
            'cabecera' => $cabecera,
            'items' => $datos
        ];
        echo json_encode($envio);
        return;
    }

    public function reporte_cargue_transportador()
    {
        header('Content-Type: application/json');
        $valor_flete = $_POST['valor_flete'];
        $items = $_POST['items'];
        $contador = [];
        $id_persona = $_SESSION['usuario']->getId_persona();
        // VALIDACION DE QUE NO TENGA ENTREGAS PENDIENTES
        $tabla  = $this->EntregasLogisticaDAO->consultar_mis_emtregas($id_persona, $estado = 2);
        if (!empty($tabla)) {
            $respu = -1;
        } else {
            // se consulta para obtener el ultimo numero de orden de entrega 
            $consulta_orden = $this->control_facturacionDAO->consulta_orden_entrega($id_persona);
            $contar = 0;
            if (!empty($consulta_orden)) {
                $contar = $consulta_orden[0]->orden_ruta;
            }
            // Saco la cantidad de documentos que hay en la tabla 
            foreach ($items as $item) {
                if (!in_array($item['num_lista_empaque'], $contador)) {
                    array_push($contador, $item['num_lista_empaque']);
                    $contar = $contar + 1;
                    $edita_orden_control = [
                        'orden_ruta' => $contar,
                    ];
                    $condicion_edita_orden = 'id_control_factura =' . $item['id_control_factura'];
                    $this->control_facturacionDAO->editar($edita_orden_control, $condicion_edita_orden);
                }
                // Se cambia el estado a entregas logistica y se coloca la fecha de cargue
                $edita_entregas_logistica = [
                    'entre_por' => $_SESSION['usuario']->getId_persona(),
                    'fecha_cargue' => date('Y-m-d'),
                    'estado' => 3
                ];
                $condicion_edita_entregas_logistica = 'id_entrega =' . $item['id_entrega'];
                $this->EntregasLogisticaDAO->editar($edita_entregas_logistica, $condicion_edita_entregas_logistica);
                // 2462
                $inserta_seguimiento = [
                    'id_persona' => $_SESSION['usuario']->getId_persona(),
                    'id_area' => 2,
                    'id_actividad' => 26,
                    'pedido' => $item['num_pedido'],
                    'item' => $item['item'],
                    'observacion' => 'TRANSPORTADOR ' . $_SESSION['usuario']->getNombres(), //.$_SESSION['usuario']->getApellidos(),
                    'estado' => 1,
                    'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                    'fecha_crea' => date('Y-m-d'),
                    'hora_crea' => date('H:i:s')
                ];
                $this->SeguimientoOpDAO->insertar($inserta_seguimiento);
            }
            // Calculo el valor del flete segun la cantidad de documentos
            $valor_flete_documento = $valor_flete / count($contador);
            // Saco los datos de la tabla pago de fletes
            $tabla_fletes = [];
            foreach ($items as $value) {
                if (in_array($item['num_lista_empaque'], $contador, true)) {
                    $pqr = $this->GestionPqrDAO->consulta_id_pedido_item($value['id_pedido_item']);
                    $valor_item = $value['cantidad_factura'] * $value['v_unidad'];
                    if (!empty($pqr)) {
                        $valor_item = 0;
                    }
                    $flete = [
                        'documento' => $value['num_lista_empaque'],
                        'valor_documento' => $valor_item,
                        'valor_flete' => $valor_flete_documento,
                        'id_transportador' => $_SESSION['usuario']->getId_persona(),
                        'estado' => 1,
                        'fecha_cargue' => date('Y-m-d'),
                    ];
                    if (empty($tabla_fletes)) {
                        $tabla_fletes[$value['num_lista_empaque']] = $flete;
                    } else {
                        if (array_key_exists($item['num_lista_empaque'], $tabla_fletes)) {
                            $tabla_fletes[$item['num_lista_empaque']]['valor_documento'] = $tabla_fletes[$item['num_lista_empaque']]['valor_documento'] + $valor_item;
                        } else {
                            $tabla_fletes[$value['num_lista_empaque']] = $flete;
                        }
                    }
                }
            }
            // Insertamos los datos de la tabla pago_fletes
            foreach ($tabla_fletes as $dato) {
                $inserta_pago_fletes = $dato;
                $this->PagoFletesDAO->insertar($inserta_pago_fletes);
            }
            $respu = 1;
        }
        echo json_encode($respu);
        return;
    }
}
