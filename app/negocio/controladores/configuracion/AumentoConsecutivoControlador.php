<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\ConsCotizacionDAO;

class AumentoConsecutivoControlador extends GenericoControlador
{

    private $ConsCotizacionDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
    }

    public function vista_aumento_consecutivo()
    {
        parent::cabecera();
        $this->view(
            'configuracion/vista_aumento_consecutivo'
        );
    }

    public function consultar_consecutivo()
    {
        header('Content-type: application/json');
        $estados_item['data'] = $this->ConsCotizacionDAO->Consultar();
        echo json_encode($estados_item);
        return;
    }

    public function editar_consecutivo()
    {
        header('Content-type: application/json');
        $datos = $_POST;
        $edicion = array('numero_guardado' => $datos['nuevo_numero']);
        $condicion = 'id_consecutivo =' . $datos['id_consecutivo'];
        $grabo = $this->ConsCotizacionDAO->editar($edicion, $condicion);
        echo json_encode($grabo);
    }
}
