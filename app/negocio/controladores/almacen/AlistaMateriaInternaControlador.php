<?php

namespace MiApp\negocio\controladores\almacen;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\entrada_tecnologiaDAO;
use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\PDF;

class AlistaMateriaInternaControlador extends GenericoControlador
{
  private $ConsCotizacionDAO;
  private $entrada_tecnologiaDAO;

  public function __construct(&$cnn)
  {
    $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
    $this->entrada_tecnologiaDAO = new entrada_tecnologiaDAO($cnn);
    parent::__construct($cnn);
    parent::validarSesion();
  }

  public function genera_pdf()
  {
    $id_memorando = 13; //id de consecutivo de base de datos de memorando interno entrega
    $consecutivo = $this->ConsCotizacionDAO->consultar_cons_especifico($id_memorando);
    $nuevo_cons['numero_guardado'] = $consecutivo[0]->numero_guardado + 1; // aumentamos el consecutivo en 1
    $condicion = 'id_consecutivo=' . $id_memorando;
    // $this->ConsCotizacionDAO->editar($nuevo_cons, $condicion); // subimos el nuevo consecutivo


    foreach ($_POST['storage'] as $value) {
      $data_descuento[] = [
        'documento' => $value['documento'],
        'codigo_producto' => $value['codigo_producto'],
        'ubicacion' =>  $value['ubicacion'],
        'id_productos' => $value['id_producto'],
        'salida' => $value['salida'],
        'estado_inv' => 1,
        'fecha_crea' => date('Y-m-d H:i:s'),
        'id_usuario' => $_SESSION['usuario']->getId_usuario(),
        'fecha_alista' => date('Y-m-d H:i:s'),
      ];
    }
    foreach ($data_descuento as $items) {
      $this->entrada_tecnologiaDAO->insertar($items);
    }
    PDF::memorando_interno_entrega($_POST['storage'], $_POST['datos'], $nuevo_cons['numero_guardado']);
  }
}
