<?php

namespace MiApp\negocio\controladores\Comercial;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\IntentoPedidoDAO;

class SolicitudPrioritariaControlador extends GenericoControlador
{
    private $IntentoPedidoDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->IntentoPedidoDAO = new IntentoPedidoDAO($cnn);
    }

    public function vista_solicitud_prioritaria()
    {
        parent::cabecera();
        $this->view(
            'Comercial/vista_solicitud_prioritaria'
        );
    }
}
