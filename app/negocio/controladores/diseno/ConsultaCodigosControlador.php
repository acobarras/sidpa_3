<?php

namespace MiApp\negocio\controladores\diseno;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\AdhesivoDAO;
use MiApp\persistencia\dao\TipoMaterialDAO;
use MiApp\persistencia\dao\FormaMaterialDAO;
use MiApp\persistencia\dao\productosDAO;

final class ConsultaCodigosControlador extends GenericoControlador
{
    private $AdhesivoDAO;
    private $TipoMaterialDAO;
    private $FormaMaterialDAO;
    private $productosDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->AdhesivoDAO = new AdhesivoDAO($cnn);
        $this->TipoMaterialDAO = new TipoMaterialDAO($cnn);
        $this->FormaMaterialDAO = new FormaMaterialDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
    }

    public function vista_consulta_codigos()
    {
        parent::cabecera();
        $this->view(
            'diseno/vista_consulta_codigos',
            [
                "adh" => $this->AdhesivoDAO->consultar_adhesivo(),
                "mat" => $this->TipoMaterialDAO->consultar_tipo_material(),
                'forma_material' => $this->FormaMaterialDAO->consultar_forma_material(),
            ]
        );
    }

    public function busqueda_codigo()
    {
        header('Content-Type: application/json');
        // var_dump($_GET);
        $condicion = "WHERE t1.id_tipo_articulo = 1  AND t1.estado_producto = 1  AND t1.codigo_producto NOT LIKE '%SERVICIO%' AND t1.ubi_troquel NOT LIKE '%EXTERNO%'";
        // condiciones dependiendo de lo que agregen 
        if ($_GET['ancho'] != '') {
            $condicion .= "AND t1.codigo_producto LIKE '" . $_GET['ancho'] . "X%' ";
        };
        if ($_GET['alto'] != '') {
            $condicion .= "AND t1.codigo_producto LIKE '%X" . $_GET['alto'] . "-%' ";
        };
        if ($_GET['cavidad'] != '') {
            $condicion .= " AND t1.codigo_producto LIKE '%-____" . $_GET['cavidad'] . "%' ";
        };
        if ($_GET['color'] != '') {
            $condicion .= " AND t1.descripcion_productos LIKE '%" . $_GET['color'] . "%' ";
        };
        if ($_GET['forma_material'] != '') {
            $condicion .= "AND t1.codigo_producto LIKE '%-" . $_GET['forma_material'] . "%' ";
        };
        if ($_GET['tipo_material'] != '') {
            $condicion .= "AND t1.codigo_producto LIKE '%-_" . $_GET['tipo_material'] . "%' ";
        };
        if ($_GET['adhesivo'] != '') {
            $condicion .= "AND t1.codigo_producto LIKE '%-___" . $_GET['adhesivo'] . "%'"; // para evitar se salga servicio de embobian
        };
        if ($_GET['gaf_cort'] != '') {
            $condicion .= " AND t1.codigo_producto LIKE '%" . $_GET['gaf_cort'] . "__' ";
        };
        if ($_GET['ficha_tecnica'] != '') {
            if ($_GET['ficha_tecnica'] == 1) { // CON FICHA TECNICA
                $con_ficha = 'NOT';
            } else {
                $con_ficha = '';
            }
            $condicion .= " AND t1.ficha_tecnica_produc IS $con_ficha NULL";
        };


        // consulta 
        $datos = $this->productosDAO->busqueda_codigos_comercial($condicion);
        // array para completar la info 
        $adh_array_ant = ['SIN ADHESIVO', 'CORRIENTE', 'SEGURIDAD', 'REMOVIBLE', 'FREEZER', 'HOTMELT', 'LLANTA', 'SEMISEGURIDAD'];
        $adh_array = [];
        $adh = $this->AdhesivoDAO->consultar_adhesivo();
        foreach ($adh as $value_adh) {
            $adh_array[$value_adh->codigo_adh] = $value_adh->nombre_adh;
        }
        $forma_array = [];
        $forma_material = $this->FormaMaterialDAO->consultar_forma_material();
        foreach ($forma_material as $value_forma) {
            $forma_array[$value_forma->id_forma] = $value_forma->nombre_forma;
        }
        $array_material = [];
        $mat = $this->TipoMaterialDAO->consultar_tipo_material();
        foreach ($mat as $value_mat) {
            $array_material[$value_mat->codigo] = $value_mat->nombre_material;
        }

        // completado de info 
        foreach ($datos as $value) {
            $codigo = $value->codigo_producto;
            $cod = explode("-", $codigo);
            if ((strpos($codigo, 'X'))) {
                $tamaño = explode("X", $cod[0]);
            } else {
                $tamaño = explode("x", $cod[0]);
            }
            $partes = str_split($cod[1]);
            if (!((strpos($codigo, 'EXT')) || $value->ubi_troquel == 'EXTERNO') && (count($partes) > 8)) {
                $value->ancho = $tamaño[0] ?? 'NO DISPONIBLE';
                $value->alto = $tamaño[1] ?? 'NO DISPONIBLE';
                $value->forma = $forma_array[$partes[0]] ?? 'NO DISPONIBLE';
                if ($partes[0] == '4') { // TIPO DE FORMA
                    $value->tipo_etiqueta = 'HOJA';
                } else {
                    $value->tipo_etiqueta = 'ETIQUETA';
                }
                $value->material = $array_material[$partes[1] . $partes[2]] ?? 'NO DISPONIBLE'; // LOS ?? non para evitar warning si no encuentra la llave del array - puede ser cuando pasa algun codigo EXTERNO de los viejos 
                if (is_numeric($partes[3])) { // codigo antiguo
                    $value->adhesivo = $adh_array_ant[$partes[3]] ?? 'NO DISPONIBLE';
                } else { // codigo nuevo
                    $value->adhesivo = $adh_array[$partes[3]] ?? 'NO DISPONIBLE';
                }
                if (count($partes) > 10) { // 10 cavidades
                    $value->cavidades = $partes[5] ?? 'NO DISPONIBLE';
                    $value->grafes = GRAF_CORTE[$partes[8]]['nombre'] ?? 'NO DISPONIBLE';
                } else {
                    $value->cavidades = $partes[4] ?? 'NO DISPONIBLE';
                    $value->grafes = GRAF_CORTE[$partes[7]]['nombre'] ?? 'NO DISPONIBLE';
                }
            } else {
                $value->ancho = 'Externo';
                $value->alto = 'Externo';
                $value->forma = 'Externo';
                $value->tipo_etiqueta = 'Externo';
                $value->adhesivo = 'Externo';
                $value->cavidades = 'Externo';
                $value->grafes = 'Externo';
                $value->material = 'Externo';
            }
        }
        echo json_encode($datos);
        return;
    }
}
