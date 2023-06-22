<?php

namespace MiApp\negocio\controladores\Inventario_final;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\ubicacionesDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\Inventario_finalDAO;
use MiApp\persistencia\dao\PersonaDAO;


class ContabilidadControlador extends GenericoControlador
{
    private $ubicacionesDAO;
    private $Inventario_finalDAO;
    private $PersonaDAO;
    private $productosDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->ubicacionesDAO = new ubicacionesDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->Inventario_finalDAO = new Inventario_finalDAO($cnn);
        $this->PersonaDAO = new PersonaDAO($cnn);
    }
    public function vista_inventario_contabilidad()
    {
        parent::cabecera();
        $this->view(
            'inventario_final/vista_inventario_contabilidad'
        );
    }
    public function inventario_tabla_final()
    {
        header('Content-Type: application/json');
        $inventario = $this->Inventario_finalDAO->consulta_tabla_final();
        foreach ($inventario as  $value) {
            $nombre_producto = $this->productosDAO->ConsultaProductoId($value->id_productos);
            $value->descripcion = $nombre_producto[0]->nombre_articulo . ' ' . $nombre_producto[0]->descripcion_productos;
            if ($value->id_usuario == 0) {
                $value->nombre_conteo = "NO USUARIO CONTEO";
            } else {
                $nombreconteo = $this->PersonaDAO->consultar_personas_id($value->id_usuario);
                $value->nombre_conteo = $nombreconteo[0]->nombres . ' ' . $nombreconteo[0]->apellidos;
            }
            if ($value->id_usuario_verificado == 0) {
                $value->nombre_verificado = "NO USUARIO VERIFICADO";
            } else {
                $nombreverificado = $this->PersonaDAO->consultar_personas_id($value->id_usuario_verificado);
                $value->nombre_verificado = $nombreverificado[0]->nombres . ' ' . $nombreverificado[0]->apellidos;
            }
        }

        $respu['data'] = $inventario;
        echo json_encode($respu);
        return;
    }
    public function inventario_tabla_ubicacion()
    {
        header('Content-Type: application/json');
        $tabla_ubicacion = $this->Inventario_finalDAO->validar_conteo($_POST['ubicacion']);
        foreach ($tabla_ubicacion as  $value) {
            $nombre_producto = $this->productosDAO->ConsultaProductoId($value->id_productos);
            $value->descripcion = $nombre_producto[0]->nombre_articulo . ' ' . $nombre_producto[0]->descripcion_productos;
            if ($value->id_usuario == 0) {
                $value->nombre_conteo = "NO USUARIO CONTEO";
            } else {
                $nombreconteo = $this->PersonaDAO->consultar_personas_id($value->id_usuario);
                $value->nombre_conteo = $nombreconteo[0]->nombres . ' ' . $nombreconteo[0]->apellidos;
            }
            if ($value->id_usuario_verificado == 0) {
                $value->nombre_verificado = "NO USUARIO VERIFICADO";
            } else {
                $nombreverificado = $this->PersonaDAO->consultar_personas_id($value->id_usuario_verificado);
                $value->nombre_verificado = $nombreverificado[0]->nombres . ' ' . $nombreverificado[0]->apellidos;
            }
        }
        $respu['data'] = $tabla_ubicacion;
        echo json_encode($respu);
        return;
    }
    public function inventario_edita_item_inv()
    {
        $condicion = 'id=' . $_POST['id'];
        $respu = $this->Inventario_finalDAO->editar($_POST, $condicion);
        echo json_encode($respu);
        return;
    }
    public function inventario_elimina_item_inv()
    {
        $condicion = 'id=' . $_POST['id'];
        $respu = $this->Inventario_finalDAO->eliminar($condicion);
        echo json_encode($respu);
        return;
    }
    public function inventario_cambio_estado_contab_inv()
    {
        $datos = $_POST['data'];
        $respu = false;
        foreach ($datos as $value) {
            $cambio = array(
                'estado' => 4
            );
            $condicion = 'id=' . $value['id'];
            $res = $this->Inventario_finalDAO->editar($cambio, $condicion);
            $respu = $res;
        }
        echo json_encode($respu);
        return;
    }
}
