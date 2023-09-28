<?php

namespace MiApp\negocio\controladores\logistica;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\VehiculosDAO;
use MiApp\persistencia\dao\ChequeoVehiculoDAO;
use MiApp\negocio\util\PDF;



class ChequeoVehicularControlador extends GenericoControlador
{
    private $VehiculosDAO;
    private $ChequeoVehiculoDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->VehiculosDAO = new VehiculosDAO($cnn);
        $this->ChequeoVehiculoDAO = new ChequeoVehiculoDAO($cnn);
    }

    public function vista_chequeo()
    {
        parent::cabecera();
        $this->view(
            'logistica/vista_chequeo',
            [
                'vehiculos' => $this->VehiculosDAO->consultar_todos_vehiculos(),
            ]
        );
    }
    public function enviar_chequeo()
    {
        // el servicio 1 es particular y el 2 es publico
        header('Content-Type: application/json');
        if ($_POST['tipo_vehiculo'] == 'vehiculo') {
            $constante = PREG_CHEQUEO;
        } else {
            $constante = CHEQUEO_MOTO;
            if ($_POST['refrigeracion_moto'] == 'no') {
                $respu = [
                    'status' => -1,
                    'msg' => 'Formulario Rechazado, la respuesta de la pregunta ¿El nivel del líquido de refrigeración se encuentra entre el máximo y el mínimo? no es valida',
                ];
                echo json_encode($respu);
                return;
            }
        }
        $cumple = false;
        $data = [];
        // el post llega como un objeto; se busca la propiedad en vez de recorrerlo (Y).0
        foreach ($constante as $value_preg) {
            if ($value_preg['tipo'] == 'select') {
                $cumple = ($_POST[$value_preg['name']] == $value_preg['respu_valida']);
                if (!$cumple) {
                    $campo = $value_preg['name'];
                    $respu = [
                        'status' => -1,
                        'msg' => 'Formulario Rechazado, la respuesta de la pregunta ' . $value_preg['pregunta'] . ' no es valida',
                    ];
                    echo json_encode($respu);
                    return;
                }
            } else {
                // valida las fechas
                $cumple = ($_POST[$value_preg['name']] > date('Y-m-d'));
                if (!$cumple) {
                    $campo = $value_preg['name'];
                    $respu = [
                        'status' => -1,
                        'msg' => 'Formulario Rechazado, la respuesta de la pregunta ' . $value_preg['pregunta'] . ' no es valida',
                    ];
                    echo json_encode($respu);
                    return;
                }
            }
        }
        if ($cumple) {
            // insertar
            unset($_POST['propietario']);
            $_POST['fecha_crea'] = date('Y-m-d');
            $inserto = $this->ChequeoVehiculoDAO->insertar($_POST);
            echo json_encode($inserto);
            return;
        }
        return;
    }
    public function pdf_chequeo()
    {
        header('Content-Type: application/pdf');
        $data = $this->ChequeoVehiculoDAO->consultar_chequeo($_POST['id_chequeo']);
        if ($data[0]->tipo_vehiculo == 'moto') {
            // PDF MOTO
            $respu = PDF::certificado_vehiculo(CHEQUEO_MOTO, $data);
        } else {
            // PDF vehiculo
            $respu = PDF::certificado_vehiculo(PREG_CHEQUEO, $data);
        }
        echo json_encode($respu);
        return;
    }
}
