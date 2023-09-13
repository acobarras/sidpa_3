<?php

namespace MiApp\negocio\controladores\logistica;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\PDF;
use MiApp\persistencia\dao\control_facturacionDAO;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\cliente_productoDAO;
use MiApp\persistencia\dao\EntregasLogisticaDAO;
use MiApp\persistencia\dao\SerialesDAO;

class DocumentacionControlador extends GenericoControlador
{

    private $control_facturacionDAO;
    private $ConsCotizacionDAO;
    private $cliente_productoDAO;
    private $EntregasLogisticaDAO;
    private $SerialesDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->control_facturacionDAO = new control_facturacionDAO($cnn);
        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
        $this->cliente_productoDAO = new cliente_productoDAO($cnn);
        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
        $this->SerialesDAO = new SerialesDAO($cnn);
    }

    public function vista_documentacion()
    {
        parent::cabecera();
        $this->view(
            'logistica/vista_documentacion'
        );
    }

    public function consulta_documentos()
    {
        header('Content-Type: application/json');
        $num_lista_empaque = $_POST['num_lista_empaque'];
        $datos = $this->control_facturacionDAO->consulta_lista_empaque($num_lista_empaque);
        foreach ($datos as $value) {
            $mas_data = $this->cliente_productoDAO->cliente_producto_id($value->id_clien_produc);
            $value->descripcion_productos = $mas_data[0]->descripcion_productos;
            $value->id_clase_articulo = $mas_data[0]->id_clase_articulo;
            $value->garantia = $mas_data[0]->garantia;
        }
        echo json_encode($datos);
        return;
    }

    public function descarga_certificado()
    {
        header('Content-type: application/pdf');
        $form = Validacion::Decodifica($_POST['form']);
        $datos = $_POST['datos'];
        $fecha = date('d-m-Y');
        $num_certificado = $datos[0]['num_certificado'];
        if ($datos[0]['num_certificado'] != '') {
            $respu = 0;
        } else {
            $data_certificado = $this->ConsCotizacionDAO->consultar_cons_especifico(18);
            $num_certificado = $data_certificado[0]->numero_guardado;
            // Aumentar el consecutivo de produccion
            $nuevo_numero = $num_certificado + 1;
            $consecutivo = ['numero_guardado' => $nuevo_numero];
            $cond_cons = 'id_consecutivo = 18';
            $this->ConsCotizacionDAO->editar($consecutivo, $cond_cons);
            foreach ($datos as $value) {
                $edita = [
                    'num_certificado' => $num_certificado,
                    'lote_usado' => $value['n_produccion'],
                    'vencimiento' => $form['vencimiento'],
                ];
                $condicion = "id_entrega =" . $value['id_entrega'];
                $this->EntregasLogisticaDAO->editar($edita, $condicion);
            }
            if ($datos[0]['id_clase_articulo'] == 2) {
                $respu = PDF::certificado_producto($datos, $fecha, $num_certificado, $form['vencimiento']);
            } else {
                $respu = PDF::certificado_cintas($datos, $fecha, $num_certificado, $form['vencimiento']);
            }
        }
        echo json_encode($respu);
        return;
    }

    public function descarga_carta()
    {
        header('Content-type: application/pdf');
        $form = $_POST['form'];
        $datos = $_POST['items_carta'];
        $fecha = date('d-m-Y');
        $existe = $this->SerialesDAO->validar_carta($datos[0]['id_entrega']);
        if (empty($existe)) {
            if ($datos[0]['garantia'] != 0) {
                $data_certificado = $this->ConsCotizacionDAO->consultar_cons_especifico(19);
                $num_carta = $data_certificado[0]->numero_guardado;
                // Aumentar el consecutivo
                $nuevo_numero = $num_carta + 1;
                $consecutivo = ['numero_guardado' => $nuevo_numero];
                $cond_cons = 'id_consecutivo = 19';
                $this->ConsCotizacionDAO->editar($consecutivo, $cond_cons);
                $seriales = [];
                foreach ($form as $value) {
                    if ($value['name'] == 'seriales') {
                        $serial = [
                            'id_entrega' => $datos[0]['id_entrega'],
                            'n_serial' => $value['value'],
                            'estado' => 1,
                            'descripcion' => $datos[0]['descripcion_productos'],
                            'cantidad' => 1,
                            'garantia' => $datos[0]['garantia']

                        ];
                        $inserta = [
                            'id_entrega' => $datos[0]['id_entrega'],
                            'n_serial' => $value['value'],
                            'num_carta' => $num_carta,
                            'fecha_crea' => date('Y-m-d H:i:s'),
                            'estado' => 1
                        ];
                        $this->SerialesDAO->insertar($inserta);
                        array_push($seriales, $serial);
                    }
                }
                $respu = PDF::carta_garantia($datos, $seriales, $num_carta, $fecha);
            } else {
                $respu = 1;
            }
        } else {
            $respu = PDF::error_pdf('20');
        }
        echo json_encode($respu);
        return;
    }
}
