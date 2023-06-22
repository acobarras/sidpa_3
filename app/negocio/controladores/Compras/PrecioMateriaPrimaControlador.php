<?php

namespace MiApp\negocio\controladores\Compras;

use MiApp\negocio\util\Validacion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\PrecioMateriaPrimaDAO;
use MiApp\persistencia\dao\TipoMaterialDAO;
use MiApp\persistencia\dao\AdhesivoDAO;

class PrecioMateriaPrimaControlador extends GenericoControlador
{
    private $PrecioMateriaPrimaDAO;
    private $TipoMaterialDAO;
    private $AdhesivoDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->PrecioMateriaPrimaDAO = new PrecioMateriaPrimaDAO($cnn);
        $this->TipoMaterialDAO = new TipoMaterialDAO($cnn);
        $this->AdhesivoDAO = new AdhesivoDAO($cnn);
    }

    public function vista_precio_materia_prima()
    {
        parent::cabecera();
        parent::validarSesion();
        $this->view(
            'Compras/vista_precio_materia_prima',
            [
                'tipo_material' => $this->TipoMaterialDAO->consultar_tipo_material(),
                'adhesivo' => $this->AdhesivoDAO->consultar_adhesivo(),
            ]
        );
    }

    public function crea_precio_material()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $id_precio = $datos['id_precio'];
        unset($datos['id_precio']);
        $valida =$this->PrecioMateriaPrimaDAO->valida_precio($datos['id_tipo_material'], $datos['id_adhesivo']);
        if ($id_precio == 0 && empty($valida)) {
            // Insertar una persona
            $respu = $this->PrecioMateriaPrimaDAO->insertar($datos);
        } else {
            // Editar una Persona
            if ($valida[0]->id_precio == $id_precio) {
                $condicion = 'id_precio =' . $id_precio;
                $edita = $this->PrecioMateriaPrimaDAO->editar($datos, $condicion);
                if ($edita) {
                    $respu = [
                        'status' => 1,
                        'msg' => 'Datos editados de manera correcta'
                    ];
                }
            } else {
                $respu = [
                    'status' => -1,
                    'msg' => 'Lo sentimos al validar este material y adhesivo encontramos que ya se encuentra en la base de datos'
                ];
            }
        }
        echo json_encode($respu);
        return;
    }

    public function consulta_precio_materia_prima()
    {
        header('Content-Type: application/json');
        $resultado = $this->PrecioMateriaPrimaDAO->consultar_precio_materia_prima();
        $arreglo["data"] = $resultado;
        echo json_encode($arreglo);
    }
}
