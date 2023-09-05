<?php

namespace MiApp\negocio\controladores\logistica;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\PDF;
use MiApp\persistencia\dao\control_facturacionDAO;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\cliente_productoDAO;
use MiApp\persistencia\dao\EntregasLogisticaDAO;

class DocumentacionControlador extends GenericoControlador
{

    private $control_facturacionDAO;
    private $ConsCotizacionDAO;
    private $cliente_productoDAO;
    private $EntregasLogisticaDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->control_facturacionDAO = new control_facturacionDAO($cnn);
        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
        $this->cliente_productoDAO = new cliente_productoDAO($cnn);
        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
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
}
