<?php

namespace MiApp\negocio\controladores\Inventario_final;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\ubicacionesDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\Inventario_finalDAO;


class EtiquetaControlador extends GenericoControlador
{
    private $ubicacionesDAO;
    private $Inventario_finalDAO;
    private $productosDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->ubicacionesDAO = new ubicacionesDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->Inventario_finalDAO = new Inventario_finalDAO($cnn);
    }
    public function vista_inventario_final_etiquetas()
    {
        parent::cabecera();
        $this->view(
            'inventario_final/vista_inventario_final_etiquetas',
            [
                "ubicaciones_etiq" => $this->ubicacionesDAO->tipo_producto_ubicaciones(2)
            ]
        );
    }

    /**
     * Función para consultar el codigo del producto especifico .
     */
    public function consultar_codigos()
    {
        header('Content-Type: application/json');
        // EL 1 es bobinas e insumos, 2 son etiquetas, 3 es tecnologia
        $producto = $this->productosDAO->consultar_productos_inv($_POST['tipo_producto']);
        echo json_encode($producto);
    }
    public function ValidarConteo()
    {
        header('Content-Type: application/json');
        $ubicacion = $_POST['ubicacion'];
        $inventario = $this->Inventario_finalDAO->validar_conteo($ubicacion);
        echo json_encode($inventario);
    }
    public function RegistrarConteo()
    {
        header('Content-Type: application/json');
        date_default_timezone_set('America/Bogota');
        $obj = $_POST['storage'];
        foreach ($obj as $registrar_conteo) {
            unset($registrar_conteo["num_usuario"]);
            $registrar_conteo['fecha_crea'] = date('Y-m-d H:i:s');
            $registrar_conteo = $this->Inventario_finalDAO->insertar($registrar_conteo);
        }
        echo json_encode($registrar_conteo);
    }
    public function RegistrarVerificacion()
    {
        $obj = $_POST['storage'];

        foreach ($obj as $value) {
            unset($value["num_usuario"]);
            $validar = $this->Inventario_finalDAO->verificacion_conteo($value['ubicacion'], $value['id_productos']);
            if (!empty($validar)) {
                $obj2 = array(
                    'entrada_verificado' => $value['entrada'],
                    'estado' => $value['estado'],
                    'id_usuario_verificado' => $value['id_usuario'],
                    'fecha_crea_verificado' => date('Y-m-d H:i:s'),
                );
                if ($value['tipo'] == 3) {
                    $obj2['ancho_verificado'] = $value['ancho'];
                    $obj2['metros_verificado'] = $value['metros'];
                }
                $condicion = 'id=' . $validar[0]->id;
                $validar = $this->Inventario_finalDAO->editar($obj2, $condicion);
            } else {
                $obj3 = array(
                    'id_usuario_verificado' => $value['id_usuario'],
                    'codigo_producto' => $value['codigo_producto'],
                    'ubicacion' => $value['ubicacion'],
                    'documento' => $value['documento'],
                    'id_proveedor' => $value['id_proveedor'],
                    'id_productos' => $value['id_productos'],
                    'estado' => $value['estado'],
                    'tipo' => $value['tipo'],
                    'entrada_verificado' => $value['entrada'],
                    'fecha_crea_verificado' => date('Y-m-d H:i:s'),
                );
                if ($value['tipo'] == 3) {
                    $obj3['ancho_verificado'] = $value['ancho'];
                    $obj3['metros_verificado'] = $value['metros'];
                }
                $registrar_verifi = $this->Inventario_finalDAO->insertar($obj3);
            }
        }
    }
}
