<?php

namespace MiApp\negocio\controladores\almacen;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\impresora_tamanoDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\negocio\util\Validacion;


class MarcacionTecnologiaControlador extends GenericoControlador
{
    private $impresora_tamanoDAO;
    private $productosDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->impresora_tamanoDAO = new impresora_tamanoDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
    }

    public function vista_marcacion_tecnologia()
    {
        parent::cabecera();
        $this->view(
            'almacen/vista_marcacion_tecnologia',
            [
                'tamano' => $this->impresora_tamanoDAO->consulta_tamano("52X33"),
                'tecnologia' => $this->productosDAO->consultar_productos('3'),
            ]
        );
    }

    public function  consulta_marcacion_bobinas()
    {
        header('Content-Type: application/json');
        $datos = $this->productosDAO->consulta_marcacion_bobinas($_GET['codigo']);
        echo json_encode( $datos);
        return;
    }

    public function impresoras_marcacion_tecnologia()
    {
        $formnulario = Validacion::Decodifica($_POST['formulario']);
        $_POST['formulario'] = $formnulario;
        $_POST['formulario']["descripcion1"]= substr(($_POST['formulario']["descripcion"]), 0, 15);
        $_POST['formulario']["descripcion2"]= str_split(substr(($_POST['formulario']["descripcion"]), 15), 23);
        $_POST['formulario']["nparte2"] = str_split(($_POST['formulario']["nparte"]), 18);
        $this->zpl('etiqueta_marcacion_tecnologia');
    }
    
}
