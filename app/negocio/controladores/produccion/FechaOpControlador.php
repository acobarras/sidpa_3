<?php

namespace MiApp\negocio\controladores\produccion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\Envio_Correo;
use MiApp\persistencia\dao\ItemProducirDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\SeguimientoProduccionDAO;

class FechaOpControlador extends GenericoControlador
{

    private $ItemProducirDAO;
    private $PedidosItemDAO;
    private $SeguimientoOpDAO;
    private $PedidosDAO;
    private $PersonaDAO;
    private $SeguimientoProduccionDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->ItemProducirDAO = new ItemProducirDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->PedidosDAO = new PedidosDAO($cnn);
        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->SeguimientoProduccionDAO = new SeguimientoProduccionDAO($cnn);
    }

    public function vista_asignar_fecha_op()
    {
        parent::cabecera();
        $this->view(
            'produccion/vista_asignar_fecha_op'
        );
    }

    public function consultar_ordenes_producciones()
    {
        header('Content-Type: application/json');
        $ordenes = $this->ItemProducirDAO->consultar_item_producir_ordenes(3);
        $data['data'] = $ordenes;
        echo json_encode($data);
    }

    public function consultar_turno_maquina()
    {
        header('Content-Type: application/json');
        $fecha_produccion = $_POST['fecha_produccion'];
        $maquina = $_POST['id_maquina'];
        $maquinas_turno = $this->ItemProducirDAO->consultar_maquinas($fecha_produccion, $maquina);
        echo json_encode($maquinas_turno);
        return;
    }

    public function asignar_fecha_produccion()
    {
        header('Content-Type: application/json');
        $form = Validacion::Decodifica($_POST['form']);
        $orden = $_POST['orden'][0];
        //crear array para modificar la orden de produccion 
        $item_producir = [
            'fecha_comp' => $form['fecha_compromiso'],
            'fecha_produccion' => $form['fecha_produccion'],
            'estado_item_producir' => 4,
            'turno_maquina' => $form['turno_maquina'],
        ];
        $condicion_item_p = 'id_item_producir =' . $orden['id_item_producir'];
        $this->ItemProducirDAO->editar($item_producir, $condicion_item_p);
        //modificar orden de produccion y asignar turno
        $maquinas_turno = $this->ItemProducirDAO->consultar_maquinas($form['fecha_produccion'], $orden['maquina']);
        //recorrer array para modificar el turno de las ordenes de producción
        foreach ($maquinas_turno as $maquina) {
            if ($orden['id_item_producir'] != $maquina->id_item_producir) {
                if ($form['turno_maquina'] == $maquina->turno_maquina) {
                    $maquina_up['turno_maquina'] = $form['turno_maquina'] + 1;
                    $this->ItemProducirDAO->editar($maquina_up, ' id_item_producir =' . $maquina->id_item_producir);
                    $form['turno_maquina'] = $maquina_up['turno_maquina'];
                }
            }
        }
        //MODIFICAR ESTADO DE LOS ITEMS DE LA ORDEN DE PRODUCCION
        $items = $this->PedidosItemDAO->ConsultaNumeroPedidoOp($orden['num_produccion']);
        foreach ($items as $item) {
            // ESTADOS VALIDACION: 17 FIN DE PRODUCCION, 16 VALIDACION LOGISTICA, 6 FINALIZADO
            if ($item->id_estado_item_pedido == 17 || $item->id_estado_item_pedido == 16 || $item->id_estado_item_pedido == 6) {
                continue;
            } else {
                $pedido_item = [
                    'fecha_compro_item' => $form['fecha_compromiso'],
                    'id_estado_item_pedido' => 4,
                ];
                $condicion_pedido_item = 'id_pedido_item =' . $item->id_pedido_item;
                $this->PedidosItemDAO->editar($pedido_item, $condicion_pedido_item);
                // Validando si la fecha esta programada
                $fecha_programada = $this->PedidosDAO->consulta_pedidos('t1.id_pedido =' . $item->id_pedido);
                if ($fecha_programada[0]->fecha_compromiso == '0000-00-00') {
                    // Validar que todos los item tengan fecha de compromiso para colocarla en el pedido
                    $fecha_compro = $this->PedidosItemDAO->ValidaFechaCompromiso($item->id_pedido);
                    if ($fecha_compro != '0000-00-00') {
                        // Editar el pedido
                        $pedido = [
                            'fecha_compromiso' => $fecha_compro
                        ];
                        $condicion_pedido = 'id_pedido =' . $item->id_pedido;
                        $this->PedidosDAO->editar($pedido, $condicion_pedido);
                        // Envio del correo fecha de compromiso
                        $persona = $this->PersonaDAO->consultar_personas_id($fecha_programada[0]->id_persona);
                        $asesor = $persona[0]->correo; //'desarrollo@acobarras.com';
                        $cliente = $fecha_programada[0]->email; //'edwin.rios@acobarras.com';
                        Envio_Correo::correo_confirmacion_fecha_compromiso($fecha_programada, $fecha_compro, $cliente, $asesor);
                    }
                }
                //registrar seguimiento del item 
                $seguimiento_op = [
                    'id_persona' => $_SESSION['usuario']->getId_persona(),
                    'id_area' => 3, //logistica
                    'id_actividad' => 34, //fecha PROGRAMADA
                    'pedido' => $item->num_pedido,
                    'item' => $item->item,
                    'observacion' => '',
                    'id_usuario' => $_SESSION['usuario']->getId_usuario(),
                    'fecha_crea' => date('Y-m-d'),
                    'hora_crea' => date('H:i:s'),
                    'estado' => 1,
                ];
                $this->SeguimientoOpDAO->insertar($seguimiento_op);
                // realizar el seguimiento de la orden de produccion
                $seguimiento_produccion['num_produccion'] = $orden['num_produccion'];
                $seguimiento_produccion['id_maquina'] = $orden['maquina'];
                $seguimiento_produccion['id_persona'] = $_SESSION['usuario']->getId_persona();
                $seguimiento_produccion['id_area'] = '3'; // PRODUCCION
                $seguimiento_produccion['id_actividad'] = '34'; //FECHA PROGRAMADA
                $seguimiento_produccion['observacion_op'] = '';
                $seguimiento_produccion['fecha_crea'] = date('Y-m-d');
                $seguimiento_produccion['hora_crea'] = date('H:i:s');
                $this->SeguimientoProduccionDAO->insertar($seguimiento_produccion);
            }
        }
        echo json_encode($items);
        return;
    }
}
