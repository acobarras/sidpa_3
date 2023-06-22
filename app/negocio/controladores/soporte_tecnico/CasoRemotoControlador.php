<?php

namespace MiApp\negocio\controladores\soporte_tecnico;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\SoporteTecnicoDAO;

class CasoRemotoControlador extends GenericoControlador
{
    private $SoporteTecnicoDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->SoporteTecnicoDAO = new SoporteTecnicoDAO($cnn);
    }

    public function vista_caso_remoto()
    {
        parent::cabecera();
        $this->view(
            'soporte_tecnico/vista_caso_remoto'
        );
    }
    public function consulta_caso_remoto()
    {
        header('Content-Type: application/json');
        $estado = 1;
        $casos_remotos = $this->SoporteTecnicoDAO->caso_remoto($estado);
        $arreglo["data"] = $casos_remotos;
        echo json_encode($arreglo);
    }
    public function enviar_observacion()
    {
        // SE CAMBIA EL ESTADO A FINALIZADO YA QUE SE REALIZO LA VISITA REMOTA
        header('Content-Type: application/json');
        $datos = $_POST['envio']['datos'];
        $observacion = $_POST['envio']['observacion'];
        $condicion = 'id_diagnostico =' . $datos['id_diagnostico'];
        $formulario = [
            'estado' => 14,
            'cierre_diag_remoto' => $observacion,
        ];
        $resultado = $this->SoporteTecnicoDAO->editar($formulario, $condicion);

        // SE REGISTRA EL SEGUIMIENTO
        $observacion = 'CIERRE DIAGNOSTICO POR CASO REMOTO';
        $seguimiento = GenericoControlador::agrega_seguimiento_diag($datos['id_diagnostico'], 0, $observacion, $_SESSION['usuario']->getid_usuario());

        if ($resultado == 1) {
            $respu = [
                'status' => 1,
                'msg' => 'Se cerro correctamente el diagnostico remoto',
            ];
        } else {
            $respu = [
                'status' => -1,
                'msg' => 'Algo a ocurrido',
            ];
        }
        echo json_encode($respu);
        return;
    }
}
