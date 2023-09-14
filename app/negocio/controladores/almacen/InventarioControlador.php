<?php

namespace MiApp\negocio\controladores\almacen;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\entrada_tecnologiaDAO;


class InventarioControlador extends GenericoControlador
{
    private $entrada_tecnologiaDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->entrada_tecnologiaDAO = new entrada_tecnologiaDAO($cnn);
    }

    /**
     * Función para cargar metodos de inicio de sesion con las opciones correspondientes.
     */
    public function vista_ingreso_inventario()
    {
        parent::cabecera();
        $this->view(
            'almacen/vista_ingreso_inventario',
        );
    }

    public function cargar_inventario()
    {
        header('Content-Type: application/json'); //convierte a json
        $data = $_POST;
        $data['estado_inv'] = 1;
        $data['fecha_crea'] = date('Y-m-d-H-i-s');
        $data['documento'] = 'ING' . date('Ymd');
        $data['id_usuario'] = $_SESSION['usuario']->getId_usuario();
        $consulta = $this->entrada_tecnologiaDAO->consultar_ingreso($data['id_productos'], $data['ubicacion'], date('Y-m-d'));
        if (empty($consulta)) {
            $insertar = $this->entrada_tecnologiaDAO->insertar($data);
        } else {
            $suma = $data['entrada'] + $consulta[0]->entrada;
            $edit = [
                'entrada' => $suma,
                'fecha_crea' =>  date('Y-m-d-H-i-s'),
            ];
            $condicion = 'id_ingresotec=' . $consulta[0]->id_ingresotec;
            $editar = $this->entrada_tecnologiaDAO->editar($edit, $condicion);
        }
        $respu = [
            'status' => 1,
            'msg' => 'Registro Exitoso',
        ];
        echo json_encode($respu);
        return;
    }
}
