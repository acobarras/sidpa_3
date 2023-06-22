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
        $estado = 7;
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
        $estado_diagnostico = 9;
        $condicion = 'id_diagnostico =' . $datos[0]['id_diagnostico'];
        $formulario = [
            'estado' => $estado_diagnostico,
        ];
        $resultado = $this->SoporteTecnicoDAO->editar($formulario, $condicion);

        $numero = count($datos);
        $contador = 0;
        $usuario = $_SESSION['usuario']->getid_usuario();
        $user = $this->UsuarioDAO->consultarIdUsuario($usuario);
        $nombre_usuario = $user[0]->nombre;
        $apellido_usuario = $user[0]->apellido;
        foreach ($datos as $value) {
            if ($contador <= $numero) {
                $contador = $contador + 1;
                if ($_POST['estado'] == 1) {
                    $observacion = 'INGRESO DE EQUIPOS LABORATORIO';
                    $seguimiento = GenericoControlador::agrega_seguimiento_diag($datos[0]['id_diagnostico'], $contador, $observacion, $_SESSION['usuario']->getid_usuario());
                } else {
                    $observacion = 'INGRESO DE EQUIPOS VISITA';
                    $seguimiento = GenericoControlador::agrega_seguimiento_diag($datos[0]['id_diagnostico'], $contador, $observacion, $_SESSION['usuario']->getid_usuario());
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
                    'estado' => 1,
                    'firma_cli' => $imagen,
                    'fecha_crea' => date('Y-m-d'),
                    'hora_crea' => date('H:i:s'),
                ];
            }
            $agregar_item = $this->SoporteItemDAO->insertar($formulario);
        }
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
        $empresa = $datos[0]['nombre_empresa'];
        $fecha = $datos[0]['fecha_ingreso'];
        if (strlen($empresa) > 23) {
            $nombre1 = substr($empresa, 0, 21);
            $nombre2 = substr($empresa, 22, 45);
        } else {
            $nombre1 = substr($empresa, 0, 45);
            $nombre2 = "";
        }
        $item = count($datos);
        $respu = [];
        for ($i = 1; $i <= $item; $i++) {
            $prueba =
                "^XA" .
                "^PW831" .
                "^LL0200" .
                "^LS0" .
                "^FT26,31^A0N,23,24^FH\^FDACOBARRAS^FS" .
                "^FT185,32^A0N,23,24^FH\^FD" . $consecutivo . "-" . $i . "^FS" .
                "^FT26,62^A0N,20,19^FH\^FDPROPIEDAD DEL CLIENTE^FS" .
                "^FO26,73^GB234,98,4^FS" .
                "^FT34,143^A0N,17,19^FH\^FD" . $nombre2 . "^FS" .
                "^FT34,113^A0N,17,19^FH\^FD" . $nombre1 . "^FS" .
                "^FT74,193^A0N,20,19^FH\^FDFECHA:^FS" .
                "^FT137,192^A0N,20,19^FH\^FD" . $fecha . "^FS" .

                "^FT298,31^A0N,23,24^FH\^FDACOBARRAS^FS" .
                "^FT457,32^A0N,23,24^FH\^FD" . $consecutivo . "-" . $i . "^FS" .
                "^FT298,62^A0N,20,19^FH\^FDPROPIEDAD DEL CLIENTE^FS" .
                "^FO298,73^GB234,98,4^FS" .
                "^FT306,143^A0N,17,19^FH\^FD" . $nombre2 . "^FS" .
                "^FT306,113^A0N,17,19^FH\^FD" . $nombre1 . "^FS" .
                "^FT346,193^A0N,20,19^FH\^FDFECHA:^FS" .
                "^FT409,192^A0N,20,19^FH\^FD" . $fecha . "^FS" .

                "^FT569,31^A0N,23,24^FH\^FDACOBARRAS^FS" .
                "^FT728,32^A0N,23,24^FH\^FD" . $consecutivo . "-" . $i . "^FS" .
                "^FT569,62^A0N,20,19^FH\^FDPROPIEDAD DEL CLIENTE^FS" .
                "^FO569,73^GB234,98,4^FS" .
                "^FT577,143^A0N,17,19^FH\^FD" . $nombre2 . "^FS" .
                "^FT577,113^A0N,17,19^FH\^FD" . $nombre1 . "^FS" .
                "^FT617,193^A0N,20,19^FH\^FDFECHA:^FS" .
                "^FT680,192^A0N,20,19^FH\^FD" . $fecha . "^FS" .
                "^PQ1,0,1,Y^XZ";
            array_push($respu, $prueba);
        }
        echo json_encode($respu);
    }
}
