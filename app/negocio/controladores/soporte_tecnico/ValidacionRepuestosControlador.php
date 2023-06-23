<?php

namespace MiApp\negocio\controladores\soporte_tecnico;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\CotizacionItemSoporteDAO;
use MiApp\persistencia\dao\entrada_tecnologiaDAO;
use MiApp\persistencia\dao\SoporteItemDAO;

use MiApp\negocio\util\Envio_Correo;
use MiApp\negocio\util\PDF;



class ValidacionRepuestosControlador extends GenericoControlador
{
    private $ConsCotizacionDAO;
    private $CotizacionItemSoporteDAO;
    private $entrada_tecnologiaDAO;
    private $SoporteItemDAO;

    public function __construct(&$cnn)
    {
        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
        $this->CotizacionItemSoporteDAO = new CotizacionItemSoporteDAO($cnn);
        $this->entrada_tecnologiaDAO = new entrada_tecnologiaDAO($cnn);
        $this->SoporteItemDAO = new SoporteItemDAO($cnn);
        parent::__construct($cnn);
        parent::validarSesion();
    }

    public function validacion_repuestos()
    {
        parent::cabecera();
        $this->view(
            'soporte_tecnico/validacion_repuestos'
        );
    }

    public function consultar_repuestos()
    {
        header('Content-Type: application/json');
        $estado_item = '12';
        $estado_cotiza = '3,4';
        $datos_items = $this->SoporteItemDAO->consultar_repuestos($estado_cotiza, $estado_item);
        foreach ($datos_items as $value) {
            $id_producto = $value->id_producto;
            $value->cantidad_inventario = $this->entrada_tecnologiaDAO->consultar_inv_product($id_producto);
        }
        $res['data'] = $datos_items;
        echo json_encode($res);
        return;
    }

    public function compras_diag()
    {
        header('Content-Type: application/json');
        $datos = $_POST['array_item'];
        $editar_item = [
            'estado' => $_POST['estado_item'],
        ];
        $condicion_item = 'id_diagnostico_item=' . $datos['id_diagnostico_item'];
        $editar = $this->SoporteItemDAO->editar($editar_item, $condicion_item);

        // SE REGISTRA EL SEGUIMIENTO
        $observacion = 'REPUESTO COMPRADO Y PENDIENTE DE REPARACION';
        $seguimiento = GenericoControlador::agrega_seguimiento_diag($datos['id_diagnostico'], $datos['item'], $observacion, $_SESSION['usuario']->getid_usuario());

        if ($editar == 1) {
            $formulario_cotiza = [
                'estado' => $_POST['estado_cotiza'],
            ];
            $condicion = 'id_cotizacion =' . $datos['id_cotizacion'];
            $modificacion = $this->CotizacionItemSoporteDAO->editar($formulario_cotiza, $condicion);
            $respu = true;
        } else {
            $respu = false;
        }
        echo json_encode($respu);
        return;
    }

    public function cancelar_diagnostico()
    {
        header('Content-Type: application/json');
        $datos = $_POST['array_item'];
        $editar_item = [
            'estado' => $_POST['estado_item'],
        ];
        $condicion_item = 'id_diagnostico_item=' . $datos['id_diagnostico_item'];
        $editar = $this->SoporteItemDAO->editar($editar_item, $condicion_item);
        // $editar = 1;
        if ($editar == 1) {
            $items = $this->SoporteItemDAO->consultar_items_diag($datos['id_diagnostico'], $datos['item']);

            // SE REGISTRA EL SEGUIMIENTO
            $observacion = 'DIAGNOSTICO CANCELADO Y DEVUELTO SIN REPARAR';
            $seguimiento = GenericoControlador::agrega_seguimiento_diag($datos['id_diagnostico'], $datos['item'], $observacion, $_SESSION['usuario']->getid_usuario());

            foreach ($items as $value) {
                $id_cotizacion = ($value->id_cotizacion);
                $formulario_cotiza = [
                    'estado' => $_POST['estado_cotiza'],
                ];
                $condicion = 'id_cotizacion =' . $id_cotizacion;
                $modificacion = $this->CotizacionItemSoporteDAO->editar($formulario_cotiza, $condicion);

                if ($modificacion == true) {
                    $inventario = $this->entrada_tecnologiaDAO->consultar_producto_diag($value->id_producto, $datos['num_consecutivo'], $value->item);
                    if (!empty($inventario)) {
                        $data_descuento = [
                            'documento' => $datos['num_consecutivo'] . '-' . $value->item,
                            'id_proveedor' => $datos['id_cli_prov'],
                            'ubicacion' => $inventario[0]->ubicacion,
                            'codigo_producto' => $inventario[0]->codigo_producto,
                            'id_productos' => $value->id_producto,
                            'entrada' => $inventario[0]->salida,
                            'estado_inv' =>  $inventario[0]->estado_inv,
                            'id_usuario' => $_SESSION['usuario']->getId_usuario(),
                            'fecha_crea' => date('Y-m-d H:i:s')
                        ];
                        $sumar = $this->entrada_tecnologiaDAO->insertar($data_descuento);
                        $res = $sumar;
                    } else {
                        $res = [
                            'status' => 1,
                        ];
                    }
                } else {
                    $res = [
                        'status' => -1,
                    ];
                }
            }
        } else {
            $res = [
                'status' => -1,
            ];
        }
        echo json_encode($res);
        return;
    }

    public function validacion_inv()
    {
        header('Content-Type: application/json');
        $data = $_POST['datos_envio'];
        if ($_POST['valor'] == 2) {
            $correo = CORREO_COMPRAS_TEC;
            $envio_correo = Envio_Correo::Solicitud_compras_soporte($correo, $data);
            if ($envio_correo['state'] == 1) {
                $id_cotizacion = ($data['id_cotizacion']);
                $formulario_cotiza = [
                    'estado' => 4,
                ];
                $condicion = 'id_cotizacion =' . $id_cotizacion;
                $modificacion = $this->CotizacionItemSoporteDAO->editar($formulario_cotiza, $condicion);
                // SE REGISTRA EL SEGUIMIENTO
                $observacion = 'REPUESTO ' . $data['codigo_producto'] . ' EN COMPRAS';
                $seguimiento = GenericoControlador::agrega_seguimiento_diag($data['id_diagnostico'], $data['item'], $observacion, $_SESSION['usuario']->getid_usuario());
                $respu = $modificacion;
            } else {
                $respu = -1;
            }
        } else {
            $data_descuento = [];
            $cantidad_req = $data['cantidad'];
            $ubicaciones = $this->entrada_tecnologiaDAO->consultar_seguimiento_producto($data['id_productos']);
            $estado_inv = 1;
            foreach ($ubicaciones as $ubicacion) {
                if ($cantidad_req > 0) {
                    if ($ubicacion->total > 0) {
                        if ($cantidad_req <= $ubicacion->total) { //se decuenta porque en la ubicacion esta la cantidad requerida    
                            $salida = $cantidad_req;
                            $cantidad_req = $cantidad_req - $cantidad_req;
                        } else { //descuenta de esa ubicacion y sigue buscando
                            $salida = $ubicacion->total;
                            $cantidad_req = $cantidad_req - $ubicacion->total;
                        }
                        $data_descuento[] = [
                            'documento' => $data['num_consecutivo'] . '-' . $data['item'],
                            'ubicacion' => $ubicacion->ubicacion,
                            'codigo_producto' => $data['codigo_producto'],
                            'id_productos' => $data['id_productos'],
                            'salida' => $salida,
                            'estado_inv' => $estado_inv,
                            'id_usuario' => $_SESSION['usuario']->getId_usuario(),
                            'fecha_crea' => date('Y-m-d H:i:s')
                        ];
                    }
                }
            }
            foreach ($data_descuento as $items) {
                $descontar = $this->entrada_tecnologiaDAO->insertar($items);
                $descontar = ['status' => 1];
                $items['descripcion'] = 1;
            }
            $data_descuento[0]['descripcion'] = $data['descripcion_productos'];
            $data_descuento[0]['cav'] = 0;
            $data_descuento[0]['cor'] = 0;
            $data_descuento[0]['rollos_x'] = 0;
            if ($descontar['status'] == 1) {
                $id_memorando = 13; //id de consecutivo de base de datos de memorando interno entrega
                $consecutivo = $this->ConsCotizacionDAO->consultar_cons_especifico($id_memorando);
                $nuevo_cons['numero_guardado'] = $consecutivo[0]->numero_guardado + 1; // aumentamos el consecutivo en 1
                $condicion = 'id_consecutivo=' . $id_memorando;
                $this->ConsCotizacionDAO->editar($nuevo_cons, $condicion); // subimos el nuevo consecutivo
                // se modifica el item diagnostico a estado de ejecucion
                $id_cotizacion = ($data['id_cotizacion']);
                $formulario_cotiza = [
                    'estado' => 5,
                ];
                $condicion = 'id_cotizacion =' . $id_cotizacion;
                $modificacion = $this->CotizacionItemSoporteDAO->editar($formulario_cotiza, $condicion);

                // SE REGISTRA EL SEGUIMIENTO
                $observacion = 'REPUESTO' . $data['codigo_producto'] . 'DESCONTADO DEL INVENTARIO';
                $seguimiento = GenericoControlador::agrega_seguimiento_diag($data['id_diagnostico'], $data['item'], $observacion, $_SESSION['usuario']->getid_usuario());

                $datos_sopo = [
                    'nombre' => 'Miguel Aya',
                    'area' => 'Soporte Tecnico',
                    'obseveciones' => $data['nombre_empresa'],
                ];
                $respu = PDF::memorando_interno_entrega($data_descuento, $datos_sopo, $nuevo_cons['numero_guardado']);
            }
        }
        echo json_encode($respu);
        return;
    }
}
