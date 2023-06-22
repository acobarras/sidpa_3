<?php

namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;

class SeguimientoDiagSoporteDAO extends GenericoDAO {
    public function __construct(&$cnn) {
        parent::__construct($cnn, 'seguimiento_diag_soporte');
    }
}