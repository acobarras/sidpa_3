<?php

namespace MiApp\negocio\controladores\produccion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\MaquinasDAO;
use MiApp\persistencia\dao\ProgramacionOperarioDAO;

class ProgOperarioControlador extends GenericoControlador
{

    private $PersonaDAO;
    private $MaquinasDAO;
    private $ProgramacionOperarioDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->MaquinasDAO = new MaquinasDAO($cnn);
        $this->ProgramacionOperarioDAO = new ProgramacionOperarioDAO($cnn);
    }

    public function vista_programacion_operario()
    {
        parent::cabecera();
        $this->view(
            'produccion/vista_programacion_operario'
        );
    }

    public function listar_operarios()
    {
        header('Content-Type: application/json');
        $operarios = $this->PersonaDAO->PersonalRotativo();
        $maquinas = $this->MaquinasDAO->consultar_maquinas();
        $res['operario'] = $operarios;
        $res['maquinas'] = $maquinas;
        echo json_encode($res);
        return;
    }

    public function registrar_programacion_operario()
    {
        header('Content-Type: application/json');
        $operarios = $_POST['DATA_FINAL'];
        $fechas_operario = $_POST['DATA_FECHAS'];
        $turno = $_POST['turno'];
        $horario = $_POST['horario'];
        $i = 0;
        $res = array();
        foreach ($operarios as $operario) {
            foreach ($fechas_operario as $fecha) {
                $array_fechas = explode('-', $fecha['fecha']); //obtener dia/mes/año
                $res[$i++] = 'Turno Asignado '. $fecha['fecha'].' '.$operario['nombres'].' '.$operario['apellidos'].' '.$operario['nombre_maquina'];
                $programacion_operario['id_persona'] = $operario['id_persona'];
                $programacion_operario['id_maquina'] = $operario['id_maquina'];
                $programacion_operario['turno_hora'] = $turno;
                $programacion_operario['horario_turno'] = $horario;
                $programacion_operario['fecha_program'] = $fecha['fecha'];
                $programacion_operario['program_mes'] = $array_fechas[1]; //mes
                $programacion_operario['program_anio'] = $array_fechas[0]; //año
                $this->ProgramacionOperarioDAO->insertar($programacion_operario);
            }
        }
        echo json_encode($res);
        return;
    }

    public function vista_consulta_operario_pro()
    {
        parent::cabecera();
        $this->view(
            'produccion/vista_consulta_operario'
        );
    }

    public function consulta_operario_programacion()
    {
        header('Content-Type: application/json');
        $data_operario = $this->PersonaDAO->PersonalRotativo();
        foreach ($data_operario as $value) {
            $turno_persona = $this->ProgramacionOperarioDAO->consultar_turno_operario($value->id_persona);
            $value->turnos_operario = $turno_persona;
        }
        $res['data'] = $data_operario;
        echo json_encode($res);
        return;
    }

    public function eliminar_registro_operario()
    {
        header('Content-Type: application/json');
        $id = $_POST['id'];
        $condicion = 'id_program_operario ='.$id;
        $res = $this->ProgramacionOperarioDAO->eliminar($condicion);
        echo json_encode($res);
        return;
    }

    public function modificar_turno_operario()
    {
        header('Content-Type: application/json');
        $obj = ['turno_hora' => $_POST['cambio']];
        $condicion = 'id_program_operario ='.$_POST['data']['id_program_operario'];
        $res = $this->ProgramacionOperarioDAO->editar($obj, $condicion);
        echo json_encode($res);
        return;
    }

    public function consultar_fechas_operarios()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $fechas = $this->ProgramacionOperarioDAO->consultar_fechas_operarios($datos);
        $response['data'] = $fechas;
        echo json_encode($response);
        return;
    }
}

?>