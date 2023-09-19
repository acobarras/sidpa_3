<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\Archivo;
use MiApp\negocio\util\insertar_generico;
use MiApp\negocio\util\Validacion;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;
use MiApp\persistencia\vo\UsuarioVO;

class UsuarioDAO extends GenericoDAO
{

    private $usuarioVO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'usuarios');
        $this->usuarioVO = new UsuarioVO($cnn);
    }

    public function autenticar($nombre_usuario, $pasword)
    {

        $sql = 'SELECT * FROM usuarios AS usus 
            INNER JOIN persona AS per ON usus.id_persona =per.id_persona 
            WHERE  usus.usuario = :usuario and usus.pasword = :pasword and usus.estado_usu = 1';
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->bindParam(':usuario', $nombre_usuario);
        $sentencia->bindParam(':pasword', $pasword);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll();

        if (empty($resultado)) {
            return;
        }
        $registro = $resultado[0];
        $usuario = new UsuarioVO;
        $usuario->setId_usuario($registro['id_usuario']);
        $usuario->setUsuario($registro['usuario']);
        $usuario->setId_roll($registro['id_roll']);
        $usuario->setNombre($registro['nombre']);
        $usuario->setApellido($registro['apellido']);
        $usuario->setRuta_foto($registro['ruta_foto']);
        $usuario->setNombres($registro['nombres']);
        $usuario->setApellidos($registro['apellidos']);
        $usuario->setId_persona($registro['id_persona']);
        $usuario->setTipo_clave($registro['tipo_clave']);
        return $usuario;
    }

    public function consultarUsuarioExiste($param)
    {
        $sql = "SELECT usuario FROM usuarios WHERE usuario = '" . $param . "'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    // Usado por edwin
    public function ConsultarUsuario()
    {
        $sql = 'SELECT t1.*,t2.nombre_roll FROM usuarios t1  
            INNER JOIN roll t2 ON t1.id_roll = t2.id_roll
            WHERE t1.id_roll <> 1';
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ConsultarUsuarioModulos()
    {

        $sql = "select perm.*,mh.titulo,mh.icono,mh.nombre_hoja from usuarios usu
		INNER JOIN permisos perm ON usu.id_usuario=perm.id_usuario
		INNER JOIN modulo_hoja mh ON perm.id_modulo_hoja=mh.id_hoja
                    where id_roll <> 1 and perm.id_usuario = " . $_GET['id'];
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);

        return $resultado;
    }

    public function ConsultarUsuarioPersona()
    {
        //administrador = 1
        $rollpermitido = $_SESSION['usuario']->getId_Roll();
        if ($rollpermitido == 1) {
            $sql = 'SELECT * FROM usuarios t1 
                INNER JOIN persona t2 ON t1.id_persona = t2.id_persona 
                INNER JOIN roll t3 ON t1.id_roll = t3.id_roll ';
            $sentencia = $this->cnn->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
            return $resultado;
        }
        return;
    }

    public function modificar_contraseña($pasword, $id_usuario)
    {

        $sql = "UPDATE usuarios SET pasword =:pasword,tipo_clave = 0 WHERE id_usuario =:id_usuario ";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->bindParam(':id_usuario', $id_usuario);
        $sentencia->bindParam(':pasword', $pasword);
        $sentencia->execute();
        return 1;
    }


    public function consultarIdPersona($param)
    {
        $sql = "SELECT id_persona FROM usuarios WHERE id_usuario =" . $param;
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultarIdUsuario($param)
    {
        $sql = "SELECT * FROM usuarios AS t1
                INNER JOIN persona AS t2 ON t1.id_persona=t2.id_persona
                WHERE t1.id_usuario =" . $param;
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultarIdUsuarioMultiple($param)
    {
        $sql = "SELECT * FROM persona WHERE id_persona IN (" . $param . ")";
        // $sql = "SELECT * FROM usuarios AS t1
        //         INNER JOIN persona AS t2 ON t1.id_persona=t2.id_persona
        //         WHERE t1.id_usuario IN (" . $param . ")";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function usuarios_activos()
    {
        $sql = "SELECT *, (SELECT COUNT(t2.mensaje) FROM mensajes_chat t2 WHERE t2.id_contacto = " . $_SESSION['usuario']->getId_usuario() . " AND t2.id_usuario = t1.id_usuario AND t2.estado = 1) AS msg_pend FROM usuarios t1 WHERE t1.estado_usu != 0 AND t1.id_usuario != " . $_SESSION['usuario']->getId_usuario();
        // $sql = "SELECT * FROM usuarios WHERE estado_usu != 0 AND id_usuario != " . $_SESSION['usuario']->getId_usuario();
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_coordinadores()
    {
        $sql = "SELECT * FROM `usuarios` WHERE id_roll=12 AND estado_usu=1";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function validar_personas_responde($id_area_trabajo)
    {
        $sql = "SELECT t1.cant_personas_res,(SELECT COUNT(t1.id_persona) FROM persona t1 LEFT JOIN usuarios t2 ON t1.id_persona=t2.id_persona 
        WHERE t1.id_area_trabajo=8 AND t2.res_prioridad=1) AS cant_per_asig 
        FROM area_trabajo t1 WHERE id_area_trabajo=$id_area_trabajo";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_roll($id_roll)
    {
        $sql = "SELECT * FROM `usuarios` WHERE id_roll=$id_roll";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
}
