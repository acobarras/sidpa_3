<?php

namespace MiApp\negocio\controladores\produccion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\ItemProducirDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\impresora_tamanoDAO;
use MiApp\negocio\util\Validacion;


class MarcacionColasControlador extends GenericoControlador
{
    private $ItemProducirDAO;
    private $PersonaDAO;
    private $impresora_tamanoDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->ItemProducirDAO = new ItemProducirDAO($cnn);
        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->impresora_tamanoDAO = new impresora_tamanoDAO($cnn);
    }

    public function vista_marcacion_cola()
    {
        parent::cabecera();
        $this->view(
            'produccion/vista_marcacion_cola',
            [
                'tamano' => $this->impresora_tamanoDAO->consulta_tamano("100X50"),
            ]
        );
    }

    public function  consulta_marcacion_cola()
    {
        header('Content-Type: application/json');
        $datos = $this->ItemProducirDAO->consulta_marcacion_cola($_GET['op']);
        if (empty($datos)) {
            $res = [
                'status' => false,
                'msg' => 'Datos no encontrados, verifique la información'
            ];
        } else {
            $res = [
                'status' => true,
                'msg' => 'Datos encontrados',
                'datos' => $datos
            ];
        }
        echo json_encode($res);
        return;
    }

    public function impresoras_marcacion_colas()
    {
        $formnulario = Validacion::Decodifica($_POST['formulario']);
        $_POST['formulario'] = $formnulario;
        $datos_persona = $this->PersonaDAO->consultar_personas_id($formnulario['id_persona']);
        $_POST['datos_persona'] = $datos_persona[0];
        $this->zpl('etiqueta_marcacion_cola');
    }
}
