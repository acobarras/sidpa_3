<?php

namespace MiApp\negocio\controladores;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\negocio\util\Validacion;


class MenuControlador extends GenericoControlador
{
    private $personaDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->personaDAO = new PersonaDAO($cnn);
    }

    /**
     * Función para cargar el Menu de inicio de sesion con las opciones correspondientes.
     */

    public function menu()
    {
        parent::cabecera();
        $this->view(
            'inicio/menu',
            [
                "cumpleanios" => $this->personaDAO->consultar_cumpleanios(date('-m-d')),
                "lista_cumpleanios" => $this->personaDAO->lista_cumpleanios(date('m')),
                'copasst' => $this->personaDAO->personas_comite(1),
                'comite' => $this->personaDAO->personas_comite(2),
                'brigada' => $this->personaDAO->personas_comite(3)
            ]
        );
    }
    
}
