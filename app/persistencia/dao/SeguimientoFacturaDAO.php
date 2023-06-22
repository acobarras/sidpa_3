<?php 

namespace MiApp\persistencia\dao;

use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class SeguimientoFacturaDAO extends GenericoDAO {

    public function __construct(&$cnn) {
        parent::__construct($cnn, 'seguimiento_factura');
    }

    
}



?>