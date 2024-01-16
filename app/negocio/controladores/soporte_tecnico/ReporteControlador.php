<?php

namespace MiApp\negocio\controladores\soporte_tecnico;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\SoporteTecnicoDAO;
use MiApp\persistencia\dao\clientes_proveedorDAO;
use MiApp\negocio\util\PDF;

class ReporteControlador extends GenericoControlador
{
    private $SoporteTecnicoDAO;
    private $clientes_proveedorDAO;

    public function __construct(&$cnn)
    {
        $this->SoporteTecnicoDAO = new SoporteTecnicoDAO($cnn);
        $this->clientes_proveedorDAO = new clientes_proveedorDAO($cnn);
        parent::__construct($cnn);
        parent::validarSesion();
    }

    public function vista_reportes()
    {
        parent::cabecera();
        $this->view(
            'soporte_tecnico/vista_reportes',
            [
                'clientes' => $this->clientes_proveedorDAO->consultar_clientes(),
            ]
        );
    }

    public function consultas_reporte()
    {
        header('Content-Type: application/json');
        $num_consulta = $_POST["formulario"]["consulta"];
        $year =  date("Y");
        if (isset($_POST["formulario"]["mes"])) {
            $mes_actual = date("n");
            $mes_consulta = $_POST["formulario"]["mes"];
            if ($mes_consulta > $mes_actual) {
                $year =  date("Y") - 1;
            }
        }
        switch ($num_consulta) {
            case '1': // visitas
                $mes = $_POST["formulario"]["mes"];
                $respuesta['data'] = $this->SoporteTecnicoDAO->consulta_indivisitas($mes, $year);
                break;
            case '2': // autorizaciones
                $mes = $_POST["formulario"]["mes"];
                $respuesta['data'] = $this->SoporteTecnicoDAO->consultas_indiautorizaciones($mes, $year);
                break;
            case '3': // pendientes
                $respuesta['data'] = $this->SoporteTecnicoDAO->consulta_casospendientes();
                break;
            case '4': // comisiones
                $mes = $_POST["formulario"]["mes"];
                $id_persona = $_POST["formulario"]["id_persona_reparacion"];
                $id_tecnico = $this->SoporteTecnicoDAO->constultar_idusuario($id_persona);
                $respuesta['data'] = $this->SoporteTecnicoDAO->consulta_reportecomisiones($id_tecnico[0]->id_usuario, $mes, $year);
                break;
            case '5': // clientes
                $mes = $_POST["formulario"]["mes"];
                $id_cliente = $_POST["formulario"]["id_cliente"];
                $respuesta['data'] = $this->SoporteTecnicoDAO->consulta_soporte_clientes($id_cliente, $mes, $year);
                break;
            case '6': // seguimiento
                $id_diagnostico = $_POST["formulario"]["diagnostico"];
                $item = $_POST["formulario"]["item"];
                $respuesta['data'] = $this->SoporteTecnicoDAO->consulta_soporte_clientes_seguimiento($id_diagnostico, $item);
                break;

            default:
                $respuesta['data'] = '';
                break;
        }
        echo json_encode($respuesta);
        return;
    }

    public function reimpresion_remision()
    {
        $num_remision = $_POST['num_remision'];
        $datos = $this->SoporteTecnicoDAO->consulta_reimpresionremision($num_remision);
        if (empty($datos)) {
            header('Content-Type: application/json');
            $respu = [
                'status' => -1,
                'msg' => 'sin datos'
            ];
        } else {
            header('Content-type: application/pdf');
            $estado = $datos[0]->estado;
            $nombre_usuario = $datos[0]->nombre_usuario;
            $sede = '';
            $nota = '';
            $apellido_usuario = $datos[0]->apellido_usuario;
            $imagen = $datos[0]->firma_cli;
            $recibido = $datos[0]->recibido;
            for ($i = 0; count($datos) > $i; $i++) {
                $datos[$i] = get_object_vars($datos[$i]);
            };
            $respu = PDF::crea_remision_equipos($datos, $estado, $nombre_usuario, $sede, $nota, $apellido_usuario, $imagen, $recibido);
        }
        echo json_encode($respu);
        return;
    }
}
