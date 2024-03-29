<?php

namespace MiApp\negocio\generico;

use MiApp\persistencia\dao\dias_festivosDAO;
use MiApp\persistencia\dao\Modulos_hojasDAO;
use MiApp\persistencia\dao\permisosDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\trmDAO;
use MiApp\persistencia\dao\PrecioMateriaPrimaDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\TipoMaterialDAO;
use MiApp\persistencia\dao\AdhesivoDAO;
use MiApp\persistencia\dao\TintasDAO;
use MiApp\persistencia\dao\CotizacionItemSoporteDAO;
use MiApp\persistencia\dao\SeguimientoDiagSoporteDAO;
use MiApp\persistencia\dao\SoporteItemDAO;
use MiApp\persistencia\dao\PrioridadesComercialDAO;
use MiApp\persistencia\dao\VehiculosDAO;
use MiApp\persistencia\dao\AreaTrabajoDAO;
use MiApp\persistencia\dao\ValoresCotizadorDAO;
use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\PDF;

abstract class GenericoControlador
{
    /**
     *
     * @var PDO
     */
    protected $cnn;
    private $dias_festivosDAO;
    private $Modulos_hojasDAO;
    private $trmDAO;
    private $PrecioMateriaPrimaDAO;
    private $productosDAO;
    private $TipoMaterialDAO;
    private $AdhesivoDAO;
    private $TintasDAO;
    private $CotizacionItemSoporteDAO;
    private $permisosDAO;
    private $SoporteItemDAO;
    private $SeguimientoDiagSoporteDAO;
    private $PrioridadesComercialDAO;
    private $VehiculosDAO;
    private $AreaTrabajoDAO;
    private $ValoresCotizadorDAO;

    public function __construct(&$cnn)
    {
        session_set_cookie_params(60 * 60 * 24 * 14);
        $this->cnn = $cnn;
        session_start();
        date_default_timezone_set('America/Bogota');
        $this->permisosDAO = new permisosDAO($cnn);
        $this->dias_festivosDAO = new dias_festivosDAO($cnn);
        $this->Modulos_hojasDAO = new Modulos_hojasDAO($cnn);
        $this->trmDAO = new trmDAO($cnn);
        $this->PrecioMateriaPrimaDAO = new PrecioMateriaPrimaDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->TipoMaterialDAO = new TipoMaterialDAO($cnn);
        $this->AdhesivoDAO = new AdhesivoDAO($cnn);
        $this->TintasDAO = new TintasDAO($cnn);
        $this->CotizacionItemSoporteDAO = new CotizacionItemSoporteDAO($cnn);
        $this->SoporteItemDAO = new SoporteItemDAO($cnn);
        $this->SeguimientoDiagSoporteDAO = new SeguimientoDiagSoporteDAO($cnn);
        $this->PrioridadesComercialDAO = new PrioridadesComercialDAO($cnn);
        $this->VehiculosDAO = new VehiculosDAO($cnn);
        $this->AreaTrabajoDAO = new AreaTrabajoDAO($cnn);
        $this->ValoresCotizadorDAO = new ValoresCotizadorDAO($cnn);
    }
    /**
     * Función protected para redireccionar al usuario al inicio de sesión
     */
    protected function  validarSesion()
    {
        if (!isset($_SESSION['usuario']) || is_null($_SESSION['usuario']) || session_status() == 1 || session_status() == 0) {
            session_destroy();
            header('Location:' . RUTA_PRINCIPAL);
            return;
        } else {
            return true;
        }
    }

    public function view($view, $datos = [])
    {
        foreach ($datos as $key => $value) {
            ${$key} = $value;
        }
        include_once  CARPETA_VIEW . '/public/vistas/' . $view . '.php';
    }

    public function cabecera()
    {
        $usuario = $_SESSION['usuario'];
        $modulo = new Modulos_hojasDAO($this->cnn);
        $id = $usuario->getId_persona();
        $parametro = 'inicio';
        $todas_hojas = array();
        // CONSULTA DIAS FESTIVOS
        $dias_festivos = $this->dias_festivosDAO->consultar_dias_festivos();
        $prioridades_mostrar = $this->PrioridadesComercialDAO->mostrar_modal_prio($usuario->getId_usuario());
        $disableddates = '';
        for ($i = 0; $i < count($dias_festivos); $i++) {
            $disableddates .= $dias_festivos[$i]->fecha_dia . ' ';
        }
        $dia_festivo = substr($disableddates, 0, -1);
        //si es administrador se habilitan todos los permisos
        //--------------------------------------------------------------------------------
        //--------------------------------------------------------------------------------
        // CONSULTA TRM
        $dato_trm = $this->trmDAO->ConsultaUltimoRegistro();
        if (empty($dato_trm)) {
            $dato_vacio = [
                'fecha_crea' => "31/12/1969",
                'valor_trm' => 0
            ];
            $Objeto = (object)$dato_vacio;
            array_push($dato_trm, $Objeto);
        }
        if ($usuario->getId_roll() == 11) {
            $chequeo = $this->VehiculosDAO->consultar_chequeos($usuario->getId_usuario());
        } else {
            $chequeo = $this->VehiculosDAO->consultar_chequeos($usuario->getId_usuario());
        }
        if ($usuario->getId_roll() == 1) {
            $this->view(
                '/plantilla/header',
                [ /*variables para la vista*/
                    "todas_hojas" => $modulo->todas_hojas(),
                    "usuario" => $usuario,
                    "modulo_hojas" => $modulo->Consultar_modulo_hojas($parametro),
                    "id" => $id,
                    "dia_festivo" => $dia_festivo,
                    "trm" => $this->trmDAO->ConsultaUltimoRegistro(),
                    "chequeo" => $chequeo,
                    "prioridades_mostrar" => $prioridades_mostrar,
                ]
            );
        } else {
            $modulo_hojas = $this->permisosDAO->consultar_permisos();
            $todas_hojas = $modulo->todas_hojas_especifica();

            $this->view(
                '/plantilla/header',
                [
                    "modulo_hojas" => $modulo_hojas,
                    "todas_hojas" => $todas_hojas,
                    "usuario" => $usuario,
                    "modulo" => $modulo,
                    "id" => $id,
                    "dia_festivos" => $dia_festivo,
                    "trm" => $this->trmDAO->ConsultaUltimoRegistro(),
                    "chequeo" => $chequeo,
                    "prioridades_mostrar" => $prioridades_mostrar,
                ]
            );
        }
    }

    public function precio_etiqueta($codigo, $cantidad_cotiza = 0, $avance = 0)
    {
        $codigo_producto = $codigo;
        $tipo_cotizacion = Validacion::DesgloceCodigo($codigo_producto, 1, 1);
        $tipo_cotiza = 1;
        if ($tipo_cotizacion == 6) {
            $tipo_cotiza = 2;
        }
        $tamanos = Validacion::TamanoCodigo($codigo_producto);
        $ancho = $tamanos['ancho'];
        $alto_tex = $tamanos['alto'];
        $alto = Validacion::ReemplazaCaracter($tamanos['alto'], ',', '.');
        $material = Validacion::DesgloceCodigo($codigo_producto, 2, 2);
        $datos_material = $this->TipoMaterialDAO->consulta_id_codigo($material);
        $adh = Validacion::DesgloceCodigo($codigo_producto, 4, 1);
        if (is_numeric($adh)) {
            $datos_etiq = [
                'status' => -1,
                'msg' => 'la estructura del codigo es errada'
            ];
        } else {
            $datos_adh = $this->AdhesivoDAO->validar_adhesivo($adh);
            $tintas = Validacion::DesgloceCodigo($codigo_producto, 6, 2);
            $datos_tinta = $this->TintasDAO->consulta_tintas($tintas);
            $data = [
                "fecha" => date('Y-m-d H:i:s'),
                'tipo_cotiza' => $tipo_cotiza,
                "ancho" => $ancho,
                "alto" => $alto,
                "material" => $datos_material[0]->id_tipo_material,
                "adh" => $datos_adh[0]->id_adh,
                "tintas" => $datos_tinta[0]->cantidad,
                "cyrel" => 1,
                "troquel" => 2,
                "estcalor" => 2,
                "estfrio" => 2,
                "cantidad" => $cantidad_cotiza,
                "laminado" => $datos_tinta[0]->variable,
            ];
            $datos_etiq = self::calculo_cotizador_etiquetas($data, $avance);
            $datos_etiq['tamano'] = $ancho . 'X' . $alto_tex;
        }
        return $datos_etiq;
    }

    public function calculo_cotizador_etiquetas($datos, $cambio_avance = 0)
    {
        $datos_cotizador = $this->ValoresCotizadorDAO->consultar_valores_cotizador();
        $valores_cotizador = [];
        foreach ($datos_cotizador as $value) {
            $valores_cotizador[$value->nombre_variable] = $value->valor_campo;
        }
        $fecha = $datos["fecha"];
        $tipo_cotiza = $datos['tipo_cotiza'];
        $ancho = $datos["ancho"];
        $alto = $datos["alto"];
        $material = $datos["material"];
        $adh = $datos["adh"];
        $tintas = $datos["tintas"];
        $cyrel = $datos["cyrel"];
        $troquel = $datos["troquel"];
        $estcalor = $datos["estcalor"];
        $estfrio = $datos["estfrio"];
        $cantidad_cotizada = $datos["cantidad"];
        $laminado = $datos["laminado"];
        $aumento = 3; // Es la constante del gap a utilizar
        $ml = 1000;
        if (isset($datos["cantestcalor"])) {
            $cantestcalor = $datos["cantestcalor"];
        }
        $magnetico = '';
        if ($tipo_cotiza == 1) {
            $alto = Validacion::datos_avance($alto);
            if (empty($alto)) {
                $data = [];
                echo json_encode($data);
                return;
            }
            $magnetico = $alto['cilindro'];
            $avance = $alto['avance']; //28
        } else {
            $alto = $alto * 1000;
            $magnetico = 80;
            $avance = $alto + $aumento; //28
        }
        if ($cambio_avance != 0) {
            $avance = $cambio_avance;
        }
        // TRAEMOS EL PRECIO DE LA MATERIA PRIMA DE LA BASE DE DATOS 
        $datos_materia_prima = $this->PrecioMateriaPrimaDAO->valida_precio($material, $adh);
        $ConsultaUltimoRegistro = $this->trmDAO->ConsultaUltimoRegistro();
        $trm = $ConsultaUltimoRegistro[0]->valor_trm;
        if ($datos_materia_prima[0]->moneda == 2) {
            $valor = $datos_materia_prima[0]->valor_material;
            $valor_material = $valor * $trm;
        } else {
            $valor_material = $datos_materia_prima[0]->valor_material;
        }
        if ($estcalor == 1) {
            $cinta_calor = $valores_cotizador['cinta_calor']; //1143;
            if (empty($valor_material)) {
                $valor_material = 0;
            } else {
                $valor_material = $valor_material + $cinta_calor;
            }
        }
        if ($estfrio == 1) {
            $cinta_frio = $valores_cotizador['cinta_frio']; //1380;
            if (empty($valor_material)) {
                $valor_material = 0;
            } else {
                $valor_material = $valor_material + $cinta_frio;
            }
            $tintas = $tintas + 1;
        }
        if ($laminado != 0) {
            if ($laminado == 2) {
                $valor_material = $valor_material + $valores_cotizador['laminado_brillante'];
            } else {
                $valor_material = $valor_material + $valores_cotizador['laminado_mate'];
            }
        }
        $ancho_m2 = ($ancho + $aumento) / 1000;
        $avance_m2 = $avance / 1000;
        $m2 = ($ancho_m2) * ($avance_m2);
        $costo = ($m2 * $valor_material) * $valores_cotizador['costo_desperdicio']; // 1.1;
        $montaje_minimo = $valores_cotizador['monto_blanco'];
        if ($tintas != 0) {
            $texto_tinta = 'monto_' . $tintas . 'tinta';
            $montaje_minimo = $valores_cotizador[$texto_tinta];
            // $costo = Validacion::costo_tinta($costo, $tintas);
            $utilidad_tintas = 'utili_' . $tintas . 'tintas';
            $costo = ($costo / $valores_cotizador[$utilidad_tintas]);
        }
        $ancgap = $ancho + $aumento; //35
        if ($ancho > 110) {
            $cavidad = 1;
        } else {
            $cavidad = number_format(round((110 / $ancgap), 0), 0, ".", ".");
        }
        $cavidad_montaje = number_format(round(($valores_cotizador['ancho_montaje'] / $ancgap), 0), 0, ".", ".");
        $cav_ml = $cavidad * $ml;
        // Se calculan las cantidades y los precios despues del costo
        $precio_bajo = $costo / $valores_cotizador['utili_inicial']; //0.6;
        $precio_medio = $precio_bajo / $valores_cotizador['utili_medio'];
        $precio_alto = $precio_bajo / $valores_cotizador['utili_alto'];
        $monto_q = round(((($montaje_minimo * 1000) / $avance) * $cavidad_montaje) * $precio_alto, -2);
        $cant_minima_etiq = (($cavidad * $ml) * (($monto_q / $precio_alto) * $avance) / ($cavidad * $ml) / $avance);
        $cant_minima_etiq1 = (($cavidad * $ml) * ((($monto_q / $precio_alto) * $avance) / ($cavidad * $ml) + 1000) / $avance);
        $cant_minima_etiq2 = (($cavidad * $ml) * ((($monto_q / $precio_alto) * $avance) / ($cavidad * $ml) + 2000) / $avance);
        $precio_variante = 0;
        if ($cantidad_cotizada == 0) {
            $cantidad_cotizada = $cant_minima_etiq;
        }
        if ($cantidad_cotizada < $cant_minima_etiq) {
            $monto_cliente = ($precio_bajo / $valores_cotizador['utili_medio']) * $cantidad_cotizada;
            if ($monto_cliente < $monto_q) {
                $precio_variante = ($monto_q / $cantidad_cotizada);
            }
        }
        // Se valida para el cobrar en costo del cyrel
        if ($tintas != 0) {
            if ($cyrel == 2) {
                $costo_cyrel = 0;
            } else {
                $precio_cyrel = $valores_cotizador['precio_cyrel']; // 50000
                $precio_cyreles = $precio_cyrel * $tintas;
                $cantidad_cotizada = $cantidad_cotizada * $valores_cotizador['tiempo_cobro_pre_prensa'];
                $costo_cyrel = $precio_cyreles / $cantidad_cotizada;
            }
            $precio_bajo = $precio_bajo + $costo_cyrel;
            $precio_medio = $precio_medio + $costo_cyrel;
            $precio_alto = $precio_alto + $costo_cyrel;
            if ($precio_variante != 0) {
                $precio_variante = $precio_variante + $costo_cyrel;
            }
        }
        // Se valida para el cobrar en costo del troquel
        if ($troquel == 1) {
            $ml_requeridos = ($cantidad_cotizada * $avance) / $cav_ml;
            if ($ml_requeridos > $valores_cotizador['ml_req_troq_rotativo']) {
                $precio_troquel = $valores_cotizador['troquel_rotativo']; // Rotativo
            } else {
                $precio_troquel = $valores_cotizador['troquel_plano']; // Plano
            }
            $cantidad_cotizada = $cantidad_cotizada * $valores_cotizador['tiempo_cobro_pre_prensa'];
            $costo_troquel = $precio_troquel / $cantidad_cotizada;
            $precio_bajo = $precio_bajo + $costo_troquel;
            $precio_medio = $precio_medio + $costo_troquel;
            $precio_alto = $precio_alto + $costo_troquel;
            if ($precio_variante != 0) {
                $precio_variante = $precio_variante + $costo_troquel;
            }
        }
        // Cuanto tiene estampado al calor segun la cantidad adicional de estampacion
        if ($estcalor == 1) {
            $precio_clice = $valores_cotizador['precio_clice'];
            $precio_clices = $precio_clice * $cantestcalor;
            $costo_clice = $precio_clices / $cantidad_cotizada;
            $precio_bajo = $precio_bajo + $costo_clice;
            $precio_medio = $precio_medio + $costo_clice;
            $precio_alto = $precio_alto + $costo_clice;
            if ($precio_variante != 0) {
                $precio_variante = $precio_variante + $costo_clice;
            }
        }

        if ($tipo_cotiza != 2) {
            $cant_minima_etiq = round(intval($cant_minima_etiq), -3);
            $cant_minima_etiq1 = round(intval($cant_minima_etiq1), -3);
            $cant_minima_etiq2 = round(intval($cant_minima_etiq2), -3);
            $texto = 'ETIQUETAS:';
        } else {
            $cant_minima_etiq = round($cant_minima_etiq, -0);
            $cant_minima_etiq1 = round($cant_minima_etiq1, -0);
            $cant_minima_etiq2 = round($cant_minima_etiq2, -0);
            $texto = 'ROLLOS:';
        }
        if (empty($valor_material)) {
            $precio_alto = 0;
            $precio_medio = 0;
            $precio_bajo = 0;
        }
        $data = [
            'texto' => $texto,
            'precio_variante' => number_format($precio_variante, DECIMALES),
            'precio_alto' => number_format($precio_alto, DECIMALES),
            'precio_medio' => number_format($precio_medio, DECIMALES),
            'precio_bajo' => number_format($precio_bajo, DECIMALES),
            'precio_variante_mil' => number_format($precio_variante * 1000, 2),
            'precio_alto_mil' => number_format($precio_alto * 1000, 2),
            'precio_medio_mil' => number_format($precio_medio * 1000, 2),
            'precio_bajo_mil' => number_format($precio_bajo * 1000, 2),
            'costo' => number_format($costo, DECIMALES, '.', ''),
            'precio1' => number_format($precio_alto, DECIMALES, '.', ''),
            'precio2' => number_format($precio_medio, DECIMALES, '.', ''),
            'precio3' => number_format($precio_bajo, DECIMALES, '.', ''),
            'cant_minima_etiq' => $cant_minima_etiq,
            'cant_minima_etiq1' => $cant_minima_etiq1,
            'cant_minima_etiq2' => $cant_minima_etiq2,
            'avance' => number_format($avance, '3', '.', ''),
            'magnetico' => $magnetico,
            'monto_minimo' => $monto_q,
        ];
        return $data;
    }

    public function validar_permiso()
    {
        $host = $_SERVER["HTTP_HOST"];
        $url = $_SERVER["REQUEST_URI"];
        $search = RUTA_PRINCIPAL . "/";
        $replace = "/";
        $new_sentence = str_replace($search, $replace, $url);
        if ($_SESSION['usuario']->getId_roll() != 1) {
            $cons_hoja = $this->Modulos_hojasDAO->consultar_hojas_url($new_sentence);
            $permiso = $this->permisosDAO->ValidarPermiso($_SESSION['usuario']->getId_usuario(), $cons_hoja[0]->id_hoja);
            if (empty($permiso) || $permiso[0]->estado_permisos == 0) {
                header('location:' . RUTA_PRINCIPAL);
                return;
            }
        }
    }

    public function crear_acta_entrega($num_acta, $firma)
    {
        $num_acta = $num_acta;
        $consulta_datos = $this->CotizacionItemSoporteDAO->consulta_acta_entrega($num_acta);
        if ($consulta_datos != []) {
            header('Content-Type: application/pdf');
            if ($firma == '') {
                if ($consulta_datos[0]->firma_cli != 2 || $consulta_datos[0]->firma_cli != '') {
                    $firma = $consulta_datos[0]->firma_cli;
                } else {
                    $firma = '';
                }
            }
            $ConsultaUltimoRegistro = $this->trmDAO->ConsultaUltimoRegistro();
            $trm = $ConsultaUltimoRegistro[0]->valor_trm;
            $sentencia = '';
            foreach ($consulta_datos as $value) {
                $repuestos = $this->SoporteItemDAO->consultar_repuestos_item($value->id_diagnostico, $value->item, $sentencia);
                $value->repuestos = $repuestos;
            }
            $fecha = date('Y-m-d');
            $respu = PDF::acta_entrega_soporte($num_acta, $consulta_datos, $trm, $firma);
        } else {
            header('Content-Type: application/json');
            $respu = ['status' => -1];
        }
        echo json_encode($respu);
        return;
    }

    public function agrega_seguimiento_diag($diagnostico, $item, $id_actividad, $observacion, $id_usuario)
    {
        $formulario_seg = [
            'id_diagnostico' => $diagnostico,
            'item' => $item,
            'id_actividad_area' => $id_actividad,
            'observacion' => $observacion,
            'id_usuario' => $id_usuario,
            'fecha_crea' => date('Y-m-d'),
        ];
        $agregar_seg = $this->SeguimientoDiagSoporteDAO->insertar($formulario_seg);
    }
    public function zpl($view, $datos = [])
    {
        foreach ($datos as $key => $value) {
            ${$key} = $value;
        }
        include_once  CARPETA_IMG . PROYECTO . '/ZPL/' . $view . '.php';
    }

    function eliminar_acentos($cadena)
    {
        //Reemplazamos la A y a
        $cadena = str_replace(
            array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
            array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
            $cadena
        );
        //Reemplazamos la E y e
        $cadena = str_replace(
            array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
            array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
            $cadena
        );
        //Reemplazamos la I y i
        $cadena = str_replace(
            array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
            array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
            $cadena
        );
        //Reemplazamos la O y o
        $cadena = str_replace(
            array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
            array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
            $cadena
        );
        //Reemplazamos la U y u
        $cadena = str_replace(
            array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
            array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
            $cadena
        );
        //Reemplazamos la N, n, C y c
        $cadena = str_replace(
            array('Ñ', 'ñ', 'Ç', 'ç'),
            array('N', 'n', 'C', 'c'),
            $cadena
        );
        return $cadena;
    }
}
