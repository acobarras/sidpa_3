<?php

namespace MiApp\negocio\controladores\indicadores;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\CodigoRespuestaPqrDAO;
use MiApp\persistencia\dao\GestionPqrDAO;

class IndicadorPqrControlador extends GenericoControlador
{

    private $CodigoRespuestaPqrDAO;
    private $GestionPqrDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->CodigoRespuestaPqrDAO = new CodigoRespuestaPqrDAO($cnn);
        $this->GestionPqrDAO = new GestionPqrDAO($cnn);
    }

    public function vista_indicador_pqr()
    {
        parent::cabecera();
        $this->view(
            'indicadores/vista_indicador_pqr'
        );
    }

    public function tabla_motivo_pqr()
    {
        header("Content-type: application/json; charset=utf-8");
        $ano = $_POST['year'];
        $mes = $_POST['month'];
        $data = $this->CodigoRespuestaPqrDAO->consultar_codigos_pqr();
        foreach ($data as $value) {
            $cantidad = $this->GestionPqrDAO->repite_motivo($value->id_respuesta_pqr, $ano, $mes);
            $cantidad_misma_respuesta = count($cantidad);
            $value->cantidad_pqr = $cantidad_misma_respuesta;
            $value->registros = $cantidad;
        }
        echo json_encode($data);
        return;
    }
    
    public function tabla_general_pqr()
    {
        header("Content-type: application/json; charset=utf-8");
        $ano = $_POST['year'];
        $data = $this->GestionPqrDAO->lista_tabla_pqr($ano);
        foreach ($data as $value) {
            $codigo_motivo = '';
            if ($value->id_respuesta_pqr != '') {
                $mas = $this->CodigoRespuestaPqrDAO->consultar_codigos_pqr_id($value->id_respuesta_pqr);
                $codigo_motivo = $mas[0]->codigo;
            }
            $value->codigo_motivo = $codigo_motivo;
        }
        echo json_encode($data);
        return;
        
    }
}
