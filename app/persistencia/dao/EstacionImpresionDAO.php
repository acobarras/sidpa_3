<?php

namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;

class EstacionImpresionDAO extends GenericoDAO 
{
    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'estaciones_impresion');
    }

    
}
