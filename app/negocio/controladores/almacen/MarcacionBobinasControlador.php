<?php

namespace MiApp\negocio\controladores\almacen;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\ItemProducirDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\impresora_tamanoDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\negocio\util\Validacion;

final class MarcacionBobinasControlador extends GenericoControlador
{
    private $ItemProducirDAO;
    private $PersonaDAO;
    private $impresora_tamanoDAO;
    private $productosDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->ItemProducirDAO = new ItemProducirDAO($cnn);
        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->impresora_tamanoDAO = new impresora_tamanoDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
    }

    public function vista_marcacion_bobinas()
    {
        parent::cabecera();
        $this->view(
            'almacen/vista_marcacion_bobinas',
            [
                'tamano' => $this->impresora_tamanoDAO->consulta_tamano("100X50"),
                'bobinas' => $this->productosDAO->consulta_bobinas(),
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
}
