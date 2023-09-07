<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class SeguimientoPrioridadesDAO extends GenericoDAO
{
    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'seguimiento_prioridades');
    }
}
