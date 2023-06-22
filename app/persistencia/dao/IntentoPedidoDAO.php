<?php


namespace MiApp\persistencia\dao;

use MiApp\FontLib\Table\Type\name;
use MiApp\persistencia\generico\GenericoDAO;

class IntentoPedidoDAO extends GenericoDAO
{
  public function __construct(&$cnn)
  {
    parent::__construct($cnn, 'intento_pedidos');
  }
  public function consulta_intento_pedido_cliente($parametro = '')
  {
    header('Content-Type:cation/json');

    $sql = "SELECT t1.id_cli_prov, t2.nit, t2.nombre_empresa,t1.observacion,COUNT(t1.id_cli_prov) as cant_intento_p FROM intento_pedidos t1
          INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov=t2.id_cli_prov $parametro GROUP BY t2.nombre_empresa";

    $sentencia = $this->cnn->prepare($sql);
    $sentencia->execute();
    $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

    return $resultado;
  }
  public function consulta_intento_pedido_asesor($parametro = '')
  {
    header('Content-Type:cation/json');

    $sql = "SELECT t1.id_cli_prov,t1.asesor, t2.nombre,t2.apellido,t1.observacion,COUNT(t2.id_usuario) as cant_intento_a FROM intento_pedidos t1
          INNER JOIN usuarios t2 ON t1.asesor=t2.id_usuario $parametro GROUP BY t2.nombre";

    $sentencia = $this->cnn->prepare($sql);
    $sentencia->execute();
    $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

    return $resultado;
  }

  public function consul_intentos($parametro)
  {

    header('Content-Type:cation/json');
    if ($parametro == 1) {
      $sql = "SELECT t1.*,t2.nombre_empresa, COUNT(t1.id_cli_prov) as cant_intento_p FROM intento_pedidos t1
    INNER JOIN cliente_proveedor t2 on t1.id_cli_prov=t2.id_cli_prov GROUP BY t1.id_cli_prov";
    } else {
      $sql = "SELECT t1.*,t2.nombre,t2.apellido, COUNT(t1.asesor) as cant_intento_p FROM intento_pedidos t1
  INNER JOIN usuarios t2 ON t1.asesor=t2.id_usuario GROUP BY t1.asesor";
    }

    $sentencia = $this->cnn->prepare($sql);
    $sentencia->execute();
    $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
    return $resultado;
  }
  public function consul_intentos_especifico($parametro)
  {
    $sql = "SELECT t1.*,t2.nombre_empresa,t3.nombre FROM intento_pedidos t1
    INNER JOIN cliente_proveedor t2 on t1.id_cli_prov=t2.id_cli_prov 
    INNER JOIN usuarios t3 on t1.asesor=t3.id_usuario
    WHERE $parametro";
    $sentencia = $this->cnn->prepare($sql);
    $sentencia->execute();
    $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
    return $resultado;
  }
}
