<?php

namespace MiApp\negocio\controladores\Inventario_final;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\ubicacionesDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\Inventario_finalDAO;


class BobinaControlador extends GenericoControlador
{
    private $ubicacionesDAO;
    private $Inventario_finalDAO;
    private $productosDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->ubicacionesDAO = new ubicacionesDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->Inventario_finalDAO = new Inventario_finalDAO($cnn);
    }
    public function vista_inventario_final_bobinas()
    {
        parent::cabecera();
        $this->view(
            'inventario_final/vista_inventario_final_bobinas',
            [
                "ubicaciones_bob" => $this->ubicacionesDAO->tipo_producto_ubicaciones(1)
            ]
        );
    }

}
