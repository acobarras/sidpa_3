<?php

namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;

class SubareaImpresionDAO extends GenericoDAO
{
    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'subarea_trabajo');
    }

    public function subareas_usuario($condicion)
    {
        $sql = "SELECT t1.* 
        FROM subarea_trabajo t1
        INNER JOIN area_trabajo t2 ON t1.id_area_trabajo = t2.id_area_trabajo
        INNER JOIN persona t3 ON t3.id_area_trabajo = t2.id_area_trabajo
        INNER JOIN usuarios t4 ON t3.id_persona = t4.id_persona
        $condicion
        GROUP BY t1.id_subarea";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}
