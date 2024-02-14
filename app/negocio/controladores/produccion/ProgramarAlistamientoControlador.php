<?php

namespace MiApp\negocio\controladores\produccion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\ItemProducirDAO;

class ProgramarAlistamientoControlador extends GenericoControlador
{

    private $ItemProducirDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->ItemProducirDAO = new ItemProducirDAO($cnn);
    }

    public function vista_programar_alistamiento()
    {
        parent::cabecera();
        $this->view(
            'produccion/vista_programar_alistamiento'
        );
    }
}