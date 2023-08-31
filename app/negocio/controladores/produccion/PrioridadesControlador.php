<?php

namespace MiApp\negocio\controladores\produccion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\UsuarioDAO;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\PrioridadesDAO;
use MiApp\persistencia\dao\SeguimientoPrioridadDAO;


class PrioridadesControlador extends GenericoControlador
{
    private $UsuarioDAO;
    private $PrioridadesDAO;
    private $SeguimientoPrioridadDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();
        $this->UsuarioDAO = new UsuarioDAO($cnn);
        $this->PrioridadesDAO = new PrioridadesDAO($cnn);
        $this->SeguimientoPrioridadDAO = new SeguimientoPrioridadDAO($cnn);
    }

    public function vista_prioridades()
    {
        parent::cabecera();
        $this->view(
            'produccion/vista_prioridades',
            [
                'coordinadores' => $this->UsuarioDAO->consultar_coordinadores(),
            ]
        );
    }

    public function vista_gestion_prioridades()
    {
        parent::cabecera();
        $this->view(
            'produccion/vista_gestion_prioridades',
            [
                'coordinadores' => $this->UsuarioDAO->consultar_coordinadores(),
            ]
        );
    }

    public function enviar_prioridad()
    {
        header('Content-Type: application/json');
        $formulario = Validacion::Decodifica($_POST['form']);
        if ($formulario['id_prioridad'] == 0) {
            // CREACION POR PRIMERA VEZ
            $id_usuario = $_SESSION['usuario']->getId_usuario();
            $formulario = [
                'num_produccion' => $formulario['orden_produccion'],
                'item' => $formulario['item'],
                'fecha_comp' =>    $formulario['fecha_compro'],
                'coordinadores' =>    $formulario['coordinador'],
                'observacion' => $_SESSION['usuario']->getNombre() . ' ' . $_SESSION['usuario']->getApellido() . ': ' . $formulario['observacion'] . '<br>',
                'id_usuario' => $id_usuario,
                'estado' => 1,
                'fecha_crea' =>  date('Y-m-d'),
            ];
            $prioridad = $this->PrioridadesDAO->insertar($formulario);
            $seguimiento = [
                'id_prioridad' => $prioridad['id'],
                'id_actividad_area' => 101,
                'fecha_crea' =>  date('Y-m-d'),
            ];
            $seg_prioridad = $this->SeguimientoPrioridadDAO->insertar($seguimiento);
            $respu = [
                'status' => 1,
                'msg' => 'Se ha creado una Prioridad Exitosamente'
            ];
        } else {
            // EDICICION
            $condicion = 'WHERE id_prioridad=' . $formulario['id_prioridad'];
            $prioridad = $this->PrioridadesDAO->consulta_prioridad($condicion);
            if ($formulario['estado'] == 2) {
                $coor = explode(',', $prioridad[0]->coordinadores);
                foreach ($coor as $value) {
                    if ($formulario['coordinador'] != $value) {
                        $coordinadores = $prioridad[0]->coordinadores . ',' . $formulario['coordinador'];
                    } else {
                        $coordinadores = $prioridad[0]->coordinadores;
                    }
                }
                $edita = [
                    'coordinadores' => $coordinadores,
                    'observacion' => $prioridad[0]->observacion . $_SESSION['usuario']->getNombre() . ' ' . $_SESSION['usuario']->getApellido() . ': ' . $formulario['observacion'] . '<br>',
                    'estado' => $formulario['estado'],
                ];
                $seguimiento = [
                    'id_prioridad' => $formulario['id_prioridad'],
                    'id_actividad_area' => 102,
                    'fecha_crea' =>  date('Y-m-d'),
                ];
            } else {
                $edita = [
                    'estado' => $formulario['estado'],
                ];
                $seguimiento = [
                    'id_prioridad' => $formulario['id_prioridad'],
                    'id_actividad_area' => 103,
                    'fecha_crea' =>  date('Y-m-d'),
                ];
            }
            $condicion_edita = 'id_prioridad =' . $formulario['id_prioridad'];
            $prioridad = $this->PrioridadesDAO->editar($edita, $condicion_edita);
            $seg_prioridad = $this->SeguimientoPrioridadDAO->insertar($seguimiento);
            $respu = [
                'status' => 1,
                'msg' => 'Se ha modificado la Prioridad Exitosamente'
            ];
        }
        echo json_encode($respu);
        return;
    }

    public function consultar_op_prioridad()
    {
        header('Content-Type: application/json');
        $num_produccion = $_POST['num_produccion'];
        $datos = $this->PrioridadesDAO->consultar_datos_op($num_produccion);
        if (empty($datos)) {
            $respu = [
                'status' => -1,
                'msg' => 'Esta Orden de Produccion no existe'
            ];
        } else {
            $respu = [
                'status' => 1,
                'data' => $datos
            ];
        }
        echo json_encode($respu);
        return;
    }

    public function consultar_prioridades()
    {
        header('Content-Type: application/json');
        $estado = $_POST['estado'];
        $id_usuario = $_SESSION['usuario']->getId_usuario();
        $id_roll = $_SESSION['usuario']->getId_roll();
        $condicion = 'WHERE t1.estado in(' . $estado . ')';
        if ($id_roll != 1 && $id_roll != 12 && $id_roll != 9) {
            $condicion = "WHERE t1.coordinadores LIKE'%" . $id_usuario . "%'AND t1.estado in(1,2)";
        }
        $datos = $this->PrioridadesDAO->consulta_prioridad($condicion);
        $res['data'] = $datos;
        echo json_encode($res);
        return;
    }
}
