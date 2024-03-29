<?php

namespace MiApp\negocio\controladores\soporte_tecnico;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\SoporteTecnicoDAO;
use MiApp\persistencia\dao\SoporteItemDAO;
use MiApp\persistencia\dao\UsuarioDAO;
use MiApp\negocio\util\PDF;

class LaboratorioControlador extends GenericoControlador
{
    private $SoporteTecnicoDAO;
    private $SoporteItemDAO;
    private $UsuarioDAO;
    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->SoporteTecnicoDAO = new SoporteTecnicoDAO($cnn);
        $this->SoporteItemDAO = new SoporteItemDAO($cnn);
        $this->UsuarioDAO = new UsuarioDAO($cnn);
    }
    public function laboratorio_soporte()
    {
        parent::cabecera();
        $this->view(
            'soporte_tecnico/laboratorio_soporte'
        );
    }

    public function carga_laboratorio()
    {
        header('Content-Type: application/json');
        $estado = 7; // agregar equipos laboratorio
        $laboratorio = $this->SoporteTecnicoDAO->datos_laboratorio($estado);
        $res['data'] =  $laboratorio;
        echo json_encode($res);
        return;
    }
    public function enviar_equipo_soporte()
    {
        header('Content-Type: application/json');
        $imagen = $_POST['firma'];
        $datos = $_POST['nuevo_storage'];
        $sede = $_POST['sede'];
        $nota = $_POST['nota'];
        $recibido = $_POST['recibido'];
        $estado_diagnostico = 9; // pendiente cotizar
        $condicion = 'id_diagnostico =' . $datos[0]['id_diagnostico'];
        $formulario = [
            'estado' => $estado_diagnostico,
        ];
        $resultado = $this->SoporteTecnicoDAO->editar($formulario, $condicion);
        $contador = $this->SoporteItemDAO->items_ingresados($datos[0]['id_diagnostico']);
        $contador = $contador[0]->contador + 1;


        $usuario = $_SESSION['usuario']->getid_usuario();
        $user = $this->UsuarioDAO->consultarIdUsuario($usuario);
        $nombre_usuario = $user[0]->nombre;
        $apellido_usuario = $user[0]->apellido;
        foreach ($datos as $clave => $value) {
            if ($_POST['estado'] == 1) {
                $id_actividad = 77; //INGRESO DE EQUIPOS LABORATORIO
                $observacion = 'INGRESO DE EQUIPOS LABORATORIO';
                $seguimiento = GenericoControlador::agrega_seguimiento_diag($datos[0]['id_diagnostico'], $contador, $id_actividad, $observacion, $_SESSION['usuario']->getid_usuario());
            } else {
                $id_actividad = 89; //INGRESO DE EQUIPOS VISITA
                $observacion = 'INGRESO DE EQUIPOS VISITA';
                $seguimiento = GenericoControlador::agrega_seguimiento_diag($datos[0]['id_diagnostico'], $contador, $id_actividad, $observacion, $_SESSION['usuario']->getid_usuario());
            }
            $formulario = [
                'id_diagnostico' => $value['id_diagnostico'],
                'num_consecutivo' => $value['num_consecutivo'],
                'item' => $contador,
                'sede' => $sede,
                'fecha_ingreso' => $value['fecha_ingreso'],
                'id_cli_prov' => $value['id_cli_prov'],
                'equipo' => $value['equipo'],
                'serial_equipo' => $value['serial_equipo'],
                'procedimiento' => $value['procedimiento'],
                'accesorios' => $value['accesorios'],
                'id_persona_reparacion' => 0,
                'fecha_ejecucion' => '0000-00-00',
                'id_persona_recibe' => $_SESSION['usuario']->getid_usuario(),
                'estado' => 9, // cambio de 1 a 9 pendiente cotización
                'firma_cli' => $imagen,
                'fecha_crea' => date('Y-m-d'),
                'hora_crea' => date('H:i:s'),
            ];
            //}
            $agregar_item = $this->SoporteItemDAO->insertar($formulario);
            ++$contador ;
        }
        return;
        if ($agregar_item['status'] == 1) {
            $estado = $_POST['estado'];
            if ($imagen == 2) {
                $respu = PDF::crea_remision_equipos($datos, $estado, $nombre_usuario, $sede, $nota, $apellido_usuario, $imagen, $recibido);
            } else {
                $respu = PDF::crea_remision_equipos($datos, $estado, $nombre_usuario, $sede, $nota, $apellido_usuario, $imagen, $recibido);
            }
        }
        return $respu;
    }

    public function impresion_etiqueta_equipo()
    {
        header('Content-Type: application/json');
        $consecutivo = $_POST['consecutivo'];
        $datos = $_POST['datos'];
        if ($datos === "consultar") {
            $datos = $this->SoporteTecnicoDAO->reimpresion_etiquetas($consecutivo);
            if ($datos === []) {
                $item = 0;
                $empresa = '';
                $fecha = '';
            } else {
                $empresa = $datos[0]->nombre_empresa;
                $fecha = $datos[0]->fecha_crea;
                $item = $datos[0]->cantidad_item;
            }
        } else {
            $empresa = $datos[0]['nombre_empresa'];
            $fecha = $datos[0]['fecha_ingreso'];
            $item = count($datos);
        }
        if (strlen($empresa) > 23) {
            $nombre1 = substr($empresa, 0, 21);
            $nombre2 = substr($empresa, 22, 45);
        } else {
            $nombre1 = substr($empresa, 0, 45);
            $nombre2 = "";
        }

        $respu = [];
        for ($i = 1; $i <= $item; $i++) {
            $prueba =
                "^XA" .
                "^FT38,24^A0N,23,24^FH\^FD" . NOMBRE_EMPRESA . "^FS" .
                "^FT79,53^A0N,23,24^FH\^FD" . $consecutivo . "|"  . $i . "^FS" .
                "^FT20,84^A0N,25,24^FH\^FDPropiedad del cliente:^FS" .
                "^FT12,152^A0N,25,24^FH\^FD" . $nombre2 . "^FS" .
                "^FT12,120^A0N,25,24^FH\^FD" . $nombre1 . "^FS" .
                "^FO3,91^GB249,72,4^FS" .
                "^FT1,191^A0N,28,28^FH\^FDFecha: " . $fecha . "^FS" .

                "^FT318,24^A0N,23,24^FH\^FD" . NOMBRE_EMPRESA . "^FS" .
                "^FT359,53^A0N,23,24^FH\^FD" . $consecutivo . "|" . $i . "^FS" .
                "^FT300,84^A0N,25,24^FH\^FDPropiedad del cliente:^FS" .
                "^FT292,152^A0N,25,24^FH\^FD" . $nombre2 . "^FS" .
                "^FT292,120^A0N,25,24^FH\^FD" . $nombre1 . "^FS" .
                "^FO283,91^GB249,72,4^FS" .
                "^FT281,191^A0N,28,28^FH\^FDFecha: " . $fecha . "^FS" .

                "^FT597,24^A0N,23,24^FH\^FD" . NOMBRE_EMPRESA . "^FS" .
                "^FT638,53^A0N,23,24^FH\^FD" . $consecutivo . "|" . $i . "^FS" .
                "^FT579,84^A0N,25,24^FH\^FDPropiedad del cliente:^FS" .
                "^FT571,152^A0N,25,24^FH\^FD" . $nombre2 . "^FS" .
                "^FT571,120^A0N,25,24^FH\^FD" . $nombre1 . "^FS" .
                "^FO562,91^GB249,72,4^FS" .
                "^FT560,191^A0N,28,28^FH\^FDFecha: " . $fecha . "^FS" .
                "^PQ1,0,1,Y^XZ";
            array_push($respu, $prueba);
        }
        echo json_encode($respu);
    }
}
