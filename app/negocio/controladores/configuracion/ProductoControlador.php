<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\Archivo;
use MiApp\persistencia\dao\TipoArticuloDAO;
use MiApp\persistencia\dao\AdhesivoDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\TipoMaterialDAO;
use MiApp\persistencia\dao\PrecioMateriaPrimaDAO;
use MiApp\persistencia\dao\TintasDAO;
use MiApp\persistencia\dao\FormaMaterialDAO;
use MiApp\persistencia\dao\ClaseArticuloDAO;

class ProductoControlador extends GenericoControlador
{

    private $TipoArticuloDAO;
    private $AdhesivoDAO;
    private $productosDAO;
    private $TipoMaterialDAO;
    private $PrecioMateriaPrimaDAO;
    private $TintasDAO;
    private $FormaMaterialDAO;
    private $ClaseArticuloDAO;

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
        $this->ClaseArticuloDAO = new ClaseArticuloDAO($cnn);
    }

    public function vista_productos()
    {
        parent::cabecera();
        $this->view(
            'configuracion/vista_creacion_productos',
            [
                "tipo_articulo" => $this->TipoArticuloDAO->consultar_tipo_articulo(),
                "adhesivo" => $this->AdhesivoDAO->consultar_adhesivo(),
                "tipo_articulo_tecnologia" => $this->TipoArticuloDAO->consultar_articulo_tecnologia(),
                "clase_articulo" => $this->ClaseArticuloDAO->consulta_clase()
            ]
        );
    }

    public function consultar_productos()
    {
        header('Content-Type: application/json');
        $resultado = $this->productosDAO->consultar_productos();
        $forma = $this->FormaMaterialDAO->consultar_forma_material();
        foreach ($resultado as $value) {
            if ($value->id_tipo_articulo == 1) {
                $fecha = $value->fecha_crea;
                $nueva_fecha = date("d/m/Y", strtotime($fecha));
                $value->fecha_crea = $nueva_fecha;
                $value->observaciones_ft = '';
                $forma_material = Validacion::DesgloceCodigo($value->codigo_producto, 1, 1);
                foreach ($forma as $value_forma) {
                    if ($value_forma->id_forma == $forma_material) {
                        $value->forma = $value_forma->nombre_forma;
                    }
                }
            } else {
                $value->forma = '';
            }
        }
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
        $id_productos = $_POST['id_productos'];
        $clase_articulo = $_POST['clase_articulo'];
        $_POST['nombre_color'] = $_POST['nombre_color'][0];
        $_POST['color_producto'] = $_POST['color_producto'][0];
        unset($_POST['id_productos']);
        unset($_POST['clase_articulo']);
        $base64 = self::insertar_img_product($_FILES, $_POST);
        if ($clase_articulo == 2 && $base64 != '') {
            $_POST['img_ficha'] = $base64;
        }
        $condicion = 'id_productos =' . $id_productos;
        $resultado = $this->productosDAO->editar($_POST, $condicion);
        if ($resultado) {
            $respu = [
                'status' => true,
                'msg' => 'Datos modificados correctamente'
            ];
        }
        echo json_encode($respu);
        return;
    }

    public function insertar_img_product($img, $data)
    {
        $base64 = '';
        if ($img['img_ficha_1']['name'][0] != '') {
            $ficha_tecnica = $data['ficha_tecnica_produc'];
            $data_base64 = $data['img_ficha'];
            $respu = Validacion::reArrayFiles($img['img_ficha_1']);
            foreach ($respu as $key => $value) {
                $nombre = "FT-" . $ficha_tecnica . "_" . $key;
                $imagen = $value;
                $imagen = Archivo::subirImagen($imagen, $nombre, '/PDF/ficha_tecnica/');
                if ($data_base64 == '' || $data_base64 == 0) {
                    $data_base64 = $imagen;
                } else {
                    $data_base64 .= ',' . $imagen;
                }
            }
            $base64 = $data_base64;
        }
        return $base64;
    }

    public function insertar_producto()
    {
        header("Content-type: application/json; charset=utf-8");
        if ($_POST['id_productos'] == 0) {
            $datos = $_POST;
            $consumo = $datos['consumo'];
            $clase_articulo = $_POST['clase_articulo'];
            if ($datos['consumo'] == '') {
                $consumo = Validacion::consumo_etiqueta($datos['codigo_producto']);
            }
            $base64 = self::insertar_img_product($_FILES, $_POST);
            if ($clase_articulo == 2 && $base64 != '') {
                $datos['img_ficha'] = $base64;
            }
            $datos['nombre_color'] = $datos['nombre_color'][0];
            $datos['color_producto'] = $datos['color_producto'][0];
            $datos['consumo'] = $consumo;
            $datos['fecha_crea'] = date('Y-m-d');
            $datos['estado_producto'] = 1;
            unset($datos['id_productos']);
            unset($datos['clase_articulo']);
            // Validar si el producto ya fue creado
            $codigo = $this->productosDAO->consultar_productos_especifico($datos['codigo_producto']);
            if (empty($codigo)) {
                $respu = $this->productosDAO->insertar($datos);
            } else {
                $respu = ['estado' => false];
            }
            echo json_encode($respu);
            return;
        } else {
            self::modificar_producto();
        }
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

    public function vista_ficha_tec()
    {
        $data = $_POST;
        $this->view(
            'configuracion/vista_ficha_tec',
            [
                'data' => $data,
            ]
        );
    }

    public function consultar_cod_producto()
    {
        header('Content-Type:application/json');
        $codigo = $_POST['codigo'];
        $area = $_POST['area'];
        $observaciones_ft = $_POST['observacion'];
        $producto = $this->productosDAO->ConsultaProductoCodigo($codigo);
        $forma = $this->FormaMaterialDAO->consultar_forma_material();
        foreach ($producto as $value) {
            if ($value->id_tipo_articulo == 1) {
                $fecha = $value->fecha_crea;
                $nueva_fecha = date("d/m/Y", strtotime($fecha));
                $value->fecha_crea = $nueva_fecha;
                $value->observaciones_ft = $observaciones_ft;
                $forma_material = Validacion::DesgloceCodigo($value->codigo_producto, 1, 1);
                foreach ($forma as $value_forma) {
                    if ($value_forma->id_forma == $forma_material) {
                        $value->forma = $value_forma->nombre_forma;
                    }
                }
            } else {
                $value->forma = '';
            }
        }
        if ($area == 1) {
            echo json_encode($producto);
            return;
        } else {
            echo ('hola');
        }
        // print_r($producto);
    }
}
