<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\TipoArticuloDAO;
use MiApp\persistencia\dao\AdhesivoDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\TipoMaterialDAO;
use MiApp\persistencia\dao\PrecioMateriaPrimaDAO;
use MiApp\persistencia\dao\TintasDAO;
use MiApp\persistencia\dao\FormaMaterialDAO;

class ProductoControlador extends GenericoControlador
{

    private $TipoArticuloDAO;
    private $AdhesivoDAO;
    private $productosDAO;
    private $TipoMaterialDAO;
    private $PrecioMateriaPrimaDAO;
    private $TintasDAO;
    private $FormaMaterialDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->TipoArticuloDAO = new TipoArticuloDAO($cnn);
        $this->AdhesivoDAO = new AdhesivoDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->TipoMaterialDAO = new TipoMaterialDAO($cnn);
        $this->PrecioMateriaPrimaDAO = new PrecioMateriaPrimaDAO($cnn);
        $this->TintasDAO = new TintasDAO($cnn);
        $this->FormaMaterialDAO = new FormaMaterialDAO($cnn);
    }

    public function vista_productos()
    {
        parent::cabecera();
        $this->view(
            'configuracion/vista_creacion_productos',
            [
                "tipo_articulo" => $this->TipoArticuloDAO->consultar_tipo_articulo(),
                "adhesivo" => $this->AdhesivoDAO->consultar_adhesivo(),
                "tipo_articulo_tecnologia" => $this->TipoArticuloDAO->consultar_articulo_tecnologia()
            ]
        );
    }

    public function consultar_productos()
    {
        header('Content-Type: application/json');
        $resultado = $this->productosDAO->consultar_productos();
        $arreglo["data"] = $resultado;
        echo json_encode($arreglo);
    }

    public function modificar_estados_producto()
    {
        header('Content-Type: application/json');
        $editar_estado = ['estado_producto' => $_POST['estado_producto']];
        $condicion = 'id_productos =' . $_POST['id_productos'];
        $resultado = $this->productosDAO->editar($editar_estado, $condicion);
        echo json_encode($resultado);
        return;
    }

    public function modificar_producto()
    {
        header("Content-type: application/json; charset=utf-8");
        $id_productos = $_POST['id'];
        $formulario = Validacion::Decodifica($_POST['form']);
        unset($formulario['id_clase_articulo']);
        if ($formulario['id_tipo_articulo'] == 1) {
            $codigo_producto = $formulario['codigo_producto'];
            $avance = $formulario['avance'];
            $datos_etiq = parent::precio_etiqueta($codigo_producto, 1, $avance);
            if ($formulario['avance'] == 0.000) {
                $formulario['avance'] = $datos_etiq['avance'];
            }
            $formulario['costo'] = $datos_etiq['costo'];
            $formulario['precio1'] = $datos_etiq['precio1'];
            $formulario['precio2'] = $datos_etiq['precio2'];
            $formulario['precio3'] = $datos_etiq['precio3'];
        }
        $condicion = 'id_productos =' . $id_productos;
        $resultado = $this->productosDAO->editar($formulario, $condicion);
        echo json_encode($resultado);
    }

    public function insertar_producto()
    {
        header("Content-type: application/json; charset=utf-8");
        $datos = $_POST;
        $consumo = $datos['consumo'];
        if ($datos['consumo'] == '') {
            $consumo = Validacion::consumo_etiqueta($datos['codigo_producto']);
        }
        $datos['consumo'] = $consumo;
        if ($datos['paso'] == 'form_etiquetas') {
            $datos['moneda_producto'] = 1;
        }
        $datos['fecha_crea'] = date('Y-m-d');
        // Validar si el producto ya fue creado
        $codigo = $this->productosDAO->consultar_productos_especifico($datos['codigo_producto']);
        if (empty($codigo)) {
            unset($datos['paso']);
            $respu = $this->productosDAO->insertar($datos);
        } else {
            $respu = ['estado' => false];
        }
        echo json_encode($respu);
    }

    public function valida_precio_codigo()
    {
        header("Content-type: application/json; charset=utf-8");
        $codigo = $_POST['codigo'];
        $avance = $_POST['avance'];
        $respu = parent::precio_etiqueta($codigo, 0, $avance);
        echo json_encode($respu);
    }

    public function vista_crea_codigo_etiqueta()
    {
        $this->view(
            'configuracion/vista_crea_codigo_etiqueta',
            [
                'forma_material' => $this->FormaMaterialDAO->consultar_forma_material(),
                'tipo_material' => $this->TipoMaterialDAO->consultar_tipo_material(),
                'adh' => $this->AdhesivoDAO->consultar_adhesivo(),
                'tintas' => $this->TintasDAO->consultar_tintas()
            ]
        );
    }

    public function valida_repeticion_codigo()
    {
        header("Content-type: application/json; charset=utf-8");
        $codigo_final = '';
        foreach ($_POST as $key => $value) {
            if ($key == 'ancho') {
                $codigo_final = $value . "X";
            } else {
                if ($key == 'alto') {
                    $codigo_final .= $value . "-";
                } else {
                    if ($key == 'tipo_product' || $key == 'desc_etiq') {
                    } else {
                        $codigo_final .= $value;
                    }
                }
            }
        }
        $ancho = $_POST['ancho'];
        $alto = $_POST['alto'];
        $tipo_product = $_POST['tipo_product'];
        $nomre_tipo_product = 'ET';
        if ($tipo_product == 2) {
            $nomre_tipo_product = 'HJ';
        }
        $forma_material = $_POST['forma_material'];
        $cons_abrev = $this->FormaMaterialDAO->consulta_id_forma($forma_material);
        $nom_form_abrev = $cons_abrev[0]->nombre_corto;
        $tipo_material = $_POST['tipo_material'];
        $cons_abrev_nomb = $this->TipoMaterialDAO->consulta_id_codigo($tipo_material);
        $tipo_nombre_abrev = $cons_abrev_nomb[0]->nombre_corto;
        $id_adh = $_POST['id_adh'];
        $cons_adh_nomb = $this->AdhesivoDAO->validar_adhesivo($id_adh);
        $nom_adh_abrev = $cons_adh_nomb[0]->nombre_corto;
        $cavidad = $_POST['cavidad'];
        $cant_tintas = $_POST['cant_tintas'];
        $nom_tintas = 'BLANCA';
        if ($cant_tintas != '00' || $_POST['desc_etiq'] != '') {
            $nom_tintas = strtoupper($_POST['desc_etiq']);
        }
        $gaf_cort = $_POST['gaf_cort'];
        $nom_graf_corte = GRAF_CORTE[$gaf_cort]['nombre_corto'];
        $descripcion = $nomre_tipo_product . " " . $ancho . "X" . $alto . " " . $nom_form_abrev . " " . $cavidad . "C " . $tipo_nombre_abrev . " " . $nom_adh_abrev . " " . $nom_graf_corte . $nom_tintas;
        $consulta = $this->productosDAO->consulta_product_descripcion($descripcion);
        if (empty($consulta)) {
            $consulta_codigo = $this->productosDAO->cons_prod_codigo($codigo_final);
            $digito = 0;
            if (!empty($consulta_codigo)) {
                $digito = substr($consulta_codigo[0]->codigo_producto, -2);
            }
            $digito = $digito + 1;
            $replazo = [1 => '01', 2 => '02', 3 => '03', 4 => '04', 5 => '05', 6 => '06', 7 => '07', 8 => '08', 9 => '09'];
            if ($digito == 1 || $digito == 2 || $digito == 3 || $digito == 4 || $digito == 5 || $digito == 6 || $digito == 7 || $digito == 8 || $digito == 9) {
                $digito = $replazo[$digito];
            }
            $codigo_final = $codigo_final . $digito;
            $res = [
                'status' => 1,
                'codigo_nuevo' => $codigo_final,
                'descripcion' => $descripcion,
                'msg' => 'No se encontro datos iguales'
            ];
        } else {
            $res = [
                'status' => -1,
                'codigo_nuevo' => $consulta[0]->codigo_producto,
                'descripcion' => $consulta[0]->descripcion_productos,
                'msg' => 'Este codigo que intenta crear ya se encuentra creado'
            ];
        }
        echo json_encode($res);
        return;
    }
}
