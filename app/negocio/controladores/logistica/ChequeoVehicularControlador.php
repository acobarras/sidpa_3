<?php

namespace MiApp\negocio\controladores\logistica;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\VehiculosDAO;


class ChequeoVehicularControlador extends GenericoControlador
{
    private $VehiculosDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->VehiculosDAO = new VehiculosDAO($cnn);
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
        $cumple = false;
        // el post llega como un objeto; se busca la propiedad en vez de recorrerlo (Y).0
        foreach (PREG_CHEQUEO as $value_preg) {
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
        }
        return;
    }
}
