<?php

namespace MiApp\negocio\controladores\entregas;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\EntregasLogisticaDAO;
use MiApp\persistencia\dao\direccionDAO;
use MiApp\persistencia\dao\control_facturacionDAO;
use MiApp\persistencia\dao\PagoFletesDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\SeguimientoFacturaDAO;
use MiApp\persistencia\dao\UsuarioDAO;

class MisEntregasControlador extends GenericoControlador
{

    private $PersonaDAO;
    private $EntregasLogisticaDAO;
    private $direccionDAO;
    private $control_facturacionDAO;
    private $PagoFletesDAO;
    private $SeguimientoOpDAO;
    private $SeguimientoFacturaDAO;
    private $UsuarioDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
        $this->direccionDAO = new direccionDAO($cnn);
        $this->control_facturacionDAO = new control_facturacionDAO($cnn);
        $this->PagoFletesDAO = new PagoFletesDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->SeguimientoFacturaDAO = new SeguimientoFacturaDAO($cnn);
        $this->UsuarioDAO = new UsuarioDAO($cnn);
    }

    public function vista_mis_entregas()
    {
        parent::cabecera();
        $this->view(
            'entregas/vista_mis_entregas',
            [
                'personas' => $this->PersonaDAO->consultar_personas(),
                'transportadores' => $this->UsuarioDAO->consultar_roll(11),
            ]
        );
    }

    public function consulta_mis_entregas()
    {
        header('Content-Type: application/json');
        $id_persona = $_POST['IDPERSONA'];
        $tabla  = $this->EntregasLogisticaDAO->consultar_mis_emtregas($id_persona, $estado = 1);
        $transporta = [];
        foreach ($tabla as $value) {
            if (!in_array($value->entre_por, $transporta)) {
                array_push($transporta, $value->entre_por);
            }
            $nombre_persona = $this->PersonaDAO->consultar_personas_id($value->entre_por);
            $dir_entrega = $this->direccionDAO->consultaIdDireccion($value->id_dire_entre);
            if ($dir_entrega[0]->nombre_departamento == 'BOGOTÁ D.C') {
                $value->direccion = $dir_entrega[0]->nombre_ciudad . " " . $dir_entrega[0]->direccion;
            } else {
                $value->direccion = $dir_entrega[0]->nombre_departamento . " " . $dir_entrega[0]->nombre_ciudad . " " . $dir_entrega[0]->direccion;
            }
            $num_documento = $value->num_factura;
            if ($value->num_factura == 0) {
                $num_documento = $value->num_remision;
            }
            $value->transportador = $nombre_persona[0]->nombres;
            $value->documento = $value->tipo_documento . " " . $num_documento;
            $value->parcial = RECIBE_PARCIAL[$value->parcial];
            $value->ruta = RUTA_ENTREGA[$dir_entrega[0]->ruta];
            $value->forma_pago = FORMA_PAGO[$value->forma_pago];
            $value->roll = $_SESSION['usuario']->getId_roll();
            $value->id_logeado = $_SESSION['usuario']->getid_usuario();
        }
        if ($_SESSION['usuario']->getId_roll() == 1 || $_SESSION['usuario']->getId_roll() == 10) {
            $id_personas = implode(',', $transporta);
        }
        $mas_diligencias = $this->PagoFletesDAO->ruta_adicional_transportador($id_persona);
        foreach ($mas_diligencias as $diligencia) {
            $persona = $this->PersonaDAO->consultar_personas_id($diligencia->id_transportador);
            $nueva_diligencia = [
                'id_tipo_documento' => 10,
                'num_pedido' => 'N/A',
                'id_pago_flete' => $diligencia->id_pago_flete,
                'documento' => $diligencia->documento,
                'nombre_empresa' => 'Encargo ' . NOMBRE_EMPRESA,
                'ruta' => 'N/A',
                'orden_ruta' => 'N/A',
                'direccion' => $diligencia->observacion,
                'transportador' => $persona[0]->nombres,
                'forma_pago' => 'N/A',
                'nombre_estado' => 'En Ruta',
                'roll' => $_SESSION['usuario']->getId_roll(),
                'id_logeado' => $_SESSION['usuario']->getid_usuario()
            ];
            $tabla[] = $nueva_diligencia;
        }
        $res['data'] = $tabla;
        echo json_encode($res);
        return;
    }

    public function orden_ruta_entrega()
    {
        header('Content-Type: application/json');
        $datos_factura = $_POST['data_fila'];
        $num_orden_entrega = $_POST['numero'];
        $orden_entrega = $this->control_facturacionDAO->consultar_orden_entregas($datos_factura['entre_por'], $num_orden_entrega);
        if (empty($orden_entrega)) {
            $edita_orden['orden_ruta'] = $num_orden_entrega;
            $this->control_facturacionDAO->editar($edita_orden, ' id_control_factura =' . $datos_factura['id_control_factura']);
        } else {
            // se edita el numero de orden de la lista de empaque
            $edita_orden['orden_ruta'] = $num_orden_entrega;
            $this->control_facturacionDAO->editar($edita_orden, ' id_control_factura =' . $datos_factura['id_control_factura']);

            // se edita la lista de empaque que tenia el numero y se cambia por el numero de la lista de empaque modificada
            $editar_orden_ant['orden_ruta'] = $datos_factura['orden_ruta'];
            $this->control_facturacionDAO->editar($editar_orden_ant, ' id_control_factura =' . $orden_entrega[0]->id_control_factura);
        }
        $respu = [
            'status' => 1,
            'msg' => 'Modificacion exitosa'
        ];
        echo json_encode($respu);
        return;
    }

    public static function inserta_img($nombre, $ubi, $nuevo_nombre)
    {
        if (isset($nombre)) {
            $ruta = $nombre['tmp_name'];
            $des = CARPETA_IMG . PROYECTO . "/fotos_entregas/" . $nuevo_nombre;
            move_uploaded_file($ruta, $des);
        }
        return $nuevo_nombre;
    }

    public function movimiento_entrega()
    {
        header('Content-Type: application/json');
        // se editan 2 tablas
        // se insertan 2 tablas
        $datos = $_POST['data'];
        $boton = $_POST['id'];
        $nuevo_nombre = '';
        // INSERTAR IMAGEN
        if ($boton != 3) {
            $data = $_POST['data'];
            $datos = json_decode($data, true);
            $foto = $_FILES['file'];
            $extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
            $foto['name'] = 'entrega_documento' . $datos['id_control_factura'] . '.' . $extension;
            $nuevo_nombre = 'entrega_documento' . $datos['id_control_factura'] . '.' . $extension;
            $ubicacion = 'fotos_entregas';
            $img = MisEntregasControlador::inserta_img($foto, $ubicacion, $nuevo_nombre);
        }

        // VALIDACION DE HORA DEL REPORTE
        $fecha_hoy = date('Y-m-d');
        $hora = date('H:i:s');
        if ($hora <= '07:00:00') {
            $fecha_reporte = date("d-m-Y", strtotime($fecha_hoy . "- 1 days"));
            $hora_reporte = '17:00:00';
        } else {
            $fecha_reporte = date('Y-m-d');
            $hora_reporte = date('H:i:s');
        }

        if ($datos['id_control_factura'] != 0) {
            $edita_campo_img = [
                'img_entrega' => $nuevo_nombre,
            ];
            $condicion_img = 'id_control_factura =' . $datos['id_control_factura'];
            $edita_control = $this->control_facturacionDAO->editar($edita_campo_img, $condicion_img);
        }

        $observacion = $_POST['observacion'];
        $estado_entregas = ENTREGA[$boton]['estado'];
        $id_actividad = ENTREGA[$boton]['id_actividad'];
        $condicion = "WHERE num_factura =" . $datos['num_factura'];
        if ($datos['num_factura'] == 0) {
            $condicion = "WHERE num_remision =" . $datos['num_remision'];
        }
        $cons_factura = $this->control_facturacionDAO->ConsultaEspecifica($condicion);
        $num_lista_empaque = [];
        $id_control_factura = [];
        $entre_logistica = [];
        $entre_logistica['estado'] = $estado_entregas;
        if ($boton == 1 || $boton == 2) {
            $entre_logistica['fecha_entrega'] = $fecha_reporte;
            $entre_logistica['fecha_entrega'] = date('Y-m-d');
        }
        // Saco la cantidad de documentos que hay en la tabla y cambio el estado de la tabla entregas logistica
        foreach ($cons_factura as $item) {
            if (!in_array($item->num_lista_empaque, $num_lista_empaque)) {
                array_push($num_lista_empaque, $item->num_lista_empaque);
            }
            if (!in_array($item->id_control_factura, $id_control_factura)) {
                array_push($id_control_factura, $item->id_control_factura);
            }
            $condicion_entre_logistica = 'id_factura =' . $item->id_control_factura;
            $modi_entrega = $this->EntregasLogisticaDAO->editar($entre_logistica, $condicion_entre_logistica);
            // Se realiza el seguimiento a el control factura
            $segui_fact = [
                'id_control_factura' => $item->id_control_factura,
                'actividad' => $id_actividad,
                'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                'fecha_crea' => date('Y-m-d H:i:s')
            ];
            $this->SeguimientoFacturaDAO->insertar($segui_fact);
        }

        if ($modi_entrega) {
            foreach ($num_lista_empaque as $value) {
                $edita_pago_flete = [
                    'estado' => 3
                ];
                $condicion_pago_flete = "documento = $value";
                $this->PagoFletesDAO->editar($edita_pago_flete, $condicion_pago_flete);
            }
            foreach ($id_control_factura as $respu_control) {
                $entrega = $this->EntregasLogisticaDAO->ItemFactura($respu_control);
                // Se realiza el seguimiento a op 
                foreach ($entrega as $key => $respu_entrega) {
                    $seguimiento_op = [
                        'id_persona' => $_SESSION['usuario']->getid_persona(),
                        'id_area' => 2,
                        'id_actividad' => $id_actividad,
                        'pedido' => $respu_entrega->num_pedido,
                        'item' => $respu_entrega->item,
                        'observacion' => $observacion,
                        'estado' => 1,
                        'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                        'fecha_crea' => $fecha_reporte,
                        'hora_crea' => $hora_reporte
                    ];
                    $this->SeguimientoOpDAO->insertar($seguimiento_op);
                }
            }
            $respu = true;
        } else {
            $respu = false;
        }
        echo json_encode($respu);
        return;
    }

    public function entrega_encargos()
    {
        header('Content-Type: application/json');
        $datos = $_POST['data'];
        $estado = $_POST['estado'];
        if ($estado == 4) {
            $elimina_pago_flete = 'id_pago_flete =' . $datos['id_pago_flete'];
            $this->PagoFletesDAO->eliminar($elimina_pago_flete);
        } else {
            $edita_pago_flete = [
                'estado' => 3
            ];
            $condicion_pago_flete = 'id_pago_flete =' . $datos['id_pago_flete'];
            $this->PagoFletesDAO->editar($edita_pago_flete, $condicion_pago_flete);
        }
        $respu = true;
        echo json_encode($respu);
        return;
    }
}
