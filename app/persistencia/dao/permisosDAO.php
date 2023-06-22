<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class permisosDAO extends GenericoDAO {

    public function __construct(&$cnn) {
        parent::__construct($cnn, 'permisos');
    }

    public function consultar_permisos() {
        $id_usuario = $_SESSION['usuario']->getId_usuario();
        $sql = "SELECT t2.* FROM permisos t1 
		INNER JOIN modulo_hoja t2 ON t1.id_modulo_hoja= t2.id_hoja
		INNER JOIN usuarios t3 ON t1.id_usuario = t3.id_usuario 
        WHERE t3.id_usuario = '$id_usuario'
        AND  t1.estado_permisos = t2.estado 
         AND t2.nombre_hoja ='inicio' ";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function permisos_usuario($id_usuario) {
        $sql = "SELECT t1.id_permisos, t1.estado_permisos, t2.* 
            FROM permisos t1 
            INNER JOIN modulo_hoja t2 ON t1.id_modulo_hoja = t2.id_hoja 
            WHERE t1.id_usuario = '$id_usuario' AND nombre_hoja != ''";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ValidarPermiso($id_usuario,$id_hoja) {
        $sql = "SELECT id_permisos, estado_permisos FROM permisos WHERE id_usuario = '$id_usuario' AND id_modulo_hoja = '$id_hoja'";
		$sentencia = $this->cnn->prepare($sql);
		$sentencia->execute();
		$consulta = $sentencia->fetchAll(PDO::FETCH_OBJ);
		return $consulta;
    }
    
    
}
