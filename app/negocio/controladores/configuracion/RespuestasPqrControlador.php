<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\CodigoRespuestaPqrDAO;

class RespuestasPqrControlador extends GenericoControlador
{

    private $CodigoRespuestaPqrDAO;
    private $CodigosEspecialesDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->CodigoRespuestaPqrDAO = new CodigoRespuestaPqrDAO($cnn);
    }

    public function vista_crea_respu_pqr()
    {
        parent::cabecera();
        $this->view(
            'configuracion/vista_crea_respu_pqr'
        );
    }

    public function consultar_codigos_pqr()
    {
        header('Content-Type: application/json');
        $codigos_especiales = $this->CodigoRespuestaPqrDAO->consultar_codigos_pqr();
        foreach ($codigos_especiales as $value) {
            $nombre_tipo_pqr = 'General';
            if ($value->tipo_pqr == 1) {
                $nombre_tipo_pqr = 'Comité';
            }
            $value->nombre_tipo_pqr = $nombre_tipo_pqr;
        }
        $data['data'] = $codigos_especiales;
        echo json_encode($data);
        return;
    }
    
    public function insertar_codigo_pqr()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $respu = $this->CodigoRespuestaPqrDAO->insertar($datos);
        echo json_encode($respu);
        return;
    }

    public function modificar_codigo_pqr()
    {
        header('Content-type: application/json');
        $id_respuesta_pqr = $_POST['id'];
        $formulario = Validacion::Decodifica($_POST['form']);
        $condicion = 'id_respuesta_pqr =' . $id_respuesta_pqr;
        $resultado = $this->CodigoRespuestaPqrDAO->editar($formulario, $condicion);
        echo json_encode($resultado);
    }

}

?>
