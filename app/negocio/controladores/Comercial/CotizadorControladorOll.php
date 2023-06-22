<?php

namespace MiApp\negocio\controladores\Comercial;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\AdhesivoDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\TipoMaterialDAO;
use MiApp\persistencia\dao\PrecioMateriaPrimaDAO;
use MiApp\negocio\util\Validacion;


class CotizadorControlador extends GenericoControlador
{
    private $AdhesivoDAO;
    private $productosDAO;
    private $TipoMaterialDAO;
    private $PrecioMateriaPrimaDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->AdhesivoDAO = new AdhesivoDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->TipoMaterialDAO = new TipoMaterialDAO($cnn);
        $this->PrecioMateriaPrimaDAO = new PrecioMateriaPrimaDAO($cnn);
    }

    /*  
     * Función para cargar la vista (vista_cotizador_etiquetas)
     */
    // "mat" => $this->productosDAO->consultar_productos_mat(),
    public function vista_cotizador_etiquetas()
    {
        parent::cabecera();
        $this->view(
            'Comercial/vista_cotizador_etiquetas',
            [
                "adh" => $this->AdhesivoDAO->consultar_adhesivo(),
                "mat" => $this->TipoMaterialDAO->consultar_tipo_material(),
                "precio" => $this->PrecioMateriaPrimaDAO->consultar_precio_materia_prima()
            ]
        );
    }


    /**
     * Función para CALCULAR cotizador de etiquetas.
     */
    public function calcular_cotizacion_etiquetas()
    {
        header("Content-type: application/json; charset=utf-8");
        $hora = date('H:i:s');
        $id_usuario = $_SESSION['usuario']->getId_usuario();
        $id_persona = $_SESSION['usuario']->getId_persona();
        $fecha = $_POST["fecha"];
        $tipo_cotiza = $_POST['tipo_cotiza'];
        $ancho = $_POST["ancho"];
        $aumento = 3; // Es la constante del gap a utilizar
        if ($tipo_cotiza == 1) {
            $alto = Validacion::datos_avance($_POST["alto"]);
            if(empty($alto)) {
                $data = [];
                echo json_encode($data);
                return;
            }
            $altgap = $alto['avance']; //28
            // $alto = $_POST["alto"];
            // $altgap = $alto + $aumento; //28
        } else {
            $alto = $_POST["alto"] * 1000;
            $altgap = $alto + $aumento; //28
        }
        $material = $_POST["material"];
        $adh = $_POST["adh"];
        $tintas = $_POST["tintas"];
        $estcalor = $_POST["estcalor"];
        $estfrio = $_POST["estfrio"];
        if (isset($_POST["cantestcalor"])) {
            $cantestcalor = $_POST["cantestcalor"];
        }
        $cyrel = $_POST["cyrel"];
        $troquel = $_POST["troquel"];
        $cantidad = $_POST["cantidad"];
        // TRAEMOS EL PRECIO DE LA MATERIA PRIMA DE LA BASE DE DATOS 
        $con_precio = $this->PrecioMateriaPrimaDAO->valida_precio($material, $adh);
        $precio_m2 = $con_precio[0]->valor_material;

        if ($estcalor == 1) {
            $cinta_calor = VALORES_COTIZADOR['cinta_calor']; //1143;
            if (empty($precio_m2)) {
                $precio_m2 = 0;
            } else {
                $precio_m2 = $precio_m2 + $cinta_calor;
            }
        }
        if ($estfrio == 1) {
            $cinta_frio = VALORES_COTIZADOR['cinta_frio']; //1380;
            if (empty($precio_m2)) {
                $precio_m2 = 0;
            } else {
                $precio_m2 = $precio_m2 + $cinta_frio;
            }

            $tintas = $tintas + 1;
        }
        // print_r($precio_m2." 1<br>");
        $cons = 1000000; // Son los metros cuadrados en milimetros
        $ancgap = $ancho + $aumento; //35
        $ml = 1000;
        if ($ancho > 110) {
            $cavidad = 1;
        } else {
            $cavidad = number_format(round((110 / $ancgap), 0), 0, ".", ".");
        }
        $cav_ml = $cavidad * $ml;
        $areamm2 = (($altgap * $ancgap) / $cons); // Area en milimetro 2
        $costomp = ($areamm2 * $precio_m2) * VALORES_COTIZADOR['costo_desperdicio']; // multiplicado 1.1
        $precos = ($costomp / VALORES_COTIZADOR['utili_inicial']); // dividido en .6
        $precio_bajo = $precos;
        $precio_medio = ($precio_bajo / VALORES_COTIZADOR['utili_medio']); // dividido en .85
        $precio_alto = ($precio_bajo / VALORES_COTIZADOR['utili_alto']); // dividido en .7
        $monto_blanco = VALORES_COTIZADOR['monto_blanco']; // 80000
        $monto_q = $monto_blanco;

        // AUMENTAR EL PRECIO POR LAS TINTAS
        if ($tintas != 0) {
            if ($tintas == 1) {
                $monto_q = VALORES_COTIZADOR['monto_1tinta'];
                $precio_bajo = $precio_bajo / VALORES_COTIZADOR['utili_medio'];
                $precio_medio = $precio_medio / VALORES_COTIZADOR['utili_medio'];
                $precio_alto = $precio_alto / VALORES_COTIZADOR['utili_medio'];
                if ((($precio_bajo / VALORES_COTIZADOR['utili_medio']) * $cantidad) > VALORES_COTIZADOR['monto_1tinta']) {
                    $precio_variante = '$ 0';
                } else {
                    $precio_variante = '$ ' . number_format((VALORES_COTIZADOR['monto_1tinta'] / $cantidad), 2);
                }
            } else if ($tintas == 2) {
                $monto_q = VALORES_COTIZADOR['monto_2tinta'];
                $precio_bajo = $precio_bajo / VALORES_COTIZADOR['utili_2tintas'];
                $precio_medio = $precio_medio / VALORES_COTIZADOR['utili_2tintas'];
                $precio_alto = $precio_alto / VALORES_COTIZADOR['utili_2tintas'];
                if ((($precio_bajo / VALORES_COTIZADOR['utili_2tintas']) * $cantidad) > VALORES_COTIZADOR['monto_2tinta']) {
                    $precio_variante = '$ 0';
                } else {
                    $precio_variante = '$ ' . number_format((VALORES_COTIZADOR['monto_2tinta'] / $cantidad), 2);
                }
            } else if ($tintas == 3) {
                $monto_q = VALORES_COTIZADOR['monto_3tinta'];
                $precio_bajo = $precio_bajo / VALORES_COTIZADOR['utili_3tintas'];
                $precio_medio = $precio_medio / VALORES_COTIZADOR['utili_3tintas'];
                $precio_alto = $precio_alto / VALORES_COTIZADOR['utili_3tintas'];
                if ((($precio_bajo / VALORES_COTIZADOR['utili_3tintas']) * $cantidad) > VALORES_COTIZADOR['monto_3tinta']) {
                    $precio_variante = '$ 0';
                } else {
                    $precio_variante = '$ ' . number_format((VALORES_COTIZADOR['monto_3tinta'] / $cantidad), 2);
                }
            } else if ($tintas == 4) {
                $monto_q = VALORES_COTIZADOR['monto_4tinta'];
                $precio_bajo = $precio_bajo / VALORES_COTIZADOR['utili_4tintas'];
                $precio_medio = $precio_medio / VALORES_COTIZADOR['utili_4tintas'];
                $precio_alto = $precio_alto / VALORES_COTIZADOR['utili_4tintas'];
                if ((($precio_bajo / VALORES_COTIZADOR['utili_4tintas']) * $cantidad) > VALORES_COTIZADOR['monto_4tinta']) {
                    $precio_variante = '$ 0';
                } else {
                    $precio_variante = '$ ' . number_format((VALORES_COTIZADOR['monto_4tinta'] / $cantidad), 2);
                }
            } else if ($tintas == 5) {
                $monto_q = VALORES_COTIZADOR['monto_5tinta'];
                $precio_bajo = $precio_bajo / VALORES_COTIZADOR['utili_5tintas'];
                $precio_medio = $precio_medio / VALORES_COTIZADOR['utili_5tintas'];
                $precio_alto = $precio_alto / VALORES_COTIZADOR['utili_5tintas'];
                if ((($precio_bajo / VALORES_COTIZADOR['utili_5tintas']) * $cantidad) > VALORES_COTIZADOR['monto_5tinta']) {
                    $precio_variante = '$ 0';
                } else {
                    $precio_variante = '$ ' . number_format((VALORES_COTIZADOR['monto_5tinta'] / $cantidad), 2);
                }
            } else if ($tintas == 6) {
                $monto_q = VALORES_COTIZADOR['monto_6tinta'];
                $precio_bajo = $precio_bajo / VALORES_COTIZADOR['utili_6tintas'];
                $precio_medio = $precio_medio / VALORES_COTIZADOR['utili_6tintas'];
                $precio_alto = $precio_alto / VALORES_COTIZADOR['utili_6tintas'];
                if ((($precio_bajo / VALORES_COTIZADOR['utili_6tintas']) * $cantidad) > VALORES_COTIZADOR['monto_6tinta']) {
                    $precio_variante = '$ 0';
                } else {
                    $precio_variante = '$ ' . number_format((VALORES_COTIZADOR['monto_6tinta'] / $cantidad), 2);
                }
            } else if ($tintas == 7) {
                $monto_q = VALORES_COTIZADOR['monto_7tinta'];
                $precio_bajo = $precio_bajo / VALORES_COTIZADOR['utili_7tintas'];
                $precio_medio = $precio_medio / VALORES_COTIZADOR['utili_7tintas'];
                $precio_alto = $precio_alto / VALORES_COTIZADOR['utili_7tintas'];
                if ((($precio_bajo / VALORES_COTIZADOR['utili_7tintas']) * $cantidad) > VALORES_COTIZADOR['monto_7tinta']) {
                    $precio_variante = '$ 0';
                } else {
                    $precio_variante = '$ ' . number_format((VALORES_COTIZADOR['monto_7tinta'] / $cantidad), 2);
                }
            } else if ($tintas == 8) {
                $monto_q = VALORES_COTIZADOR['monto_8tinta'];
                $precio_bajo = $precio_bajo / VALORES_COTIZADOR['utili_8tintas'];
                $precio_medio = $precio_medio / VALORES_COTIZADOR['utili_8tintas'];
                $precio_alto = $precio_alto / VALORES_COTIZADOR['utili_8tintas'];
                if ((($precio_bajo / VALORES_COTIZADOR['utili_8tintas']) * $cantidad) > VALORES_COTIZADOR['monto_8tinta']) {
                    $precio_variante = '$ 0';
                } else {
                    $precio_variante = '$ ' . number_format((VALORES_COTIZADOR['monto_8tinta'] / $cantidad), 2);
                }
            }
        }

        // SE CALCULA LA CANTIDAD DE ETIQUETAS ANTES DE AGREGAR COSTOS DE REQUERIMIENTOS ADICIONALES (CYREL TROQUEL ETC)
        if (empty($precio_m2)) {
            $cant_minima_etiq = 0;
            $cant_minima_etiq1 = 0;
            $cant_minima_etiq2 = 0;
        } else {
            $cant_minima_etiq = (($cavidad * $ml) * (($monto_q / $precio_alto) * $altgap) / ($cavidad * $ml) / $altgap);
            $cant_minima_etiq1 = (($cavidad * $ml) * ((($monto_q / $precio_alto) * $altgap) / ($cavidad * $ml) + 1000) / $altgap);
            $cant_minima_etiq2 = (($cavidad * $ml) * ((($monto_q / $precio_alto) * $altgap) / ($cavidad * $ml) + 2000) / $altgap);
        }

        // CALCULO DEL PRIMER PRECIO
        if (($precio_alto * $cantidad) > $monto_q) {
            $precio_variante = 0;
        } else {
            if ($tipo_cotiza == 1) {
                if (round($cant_minima_etiq, -3) < $cantidad) {
                    $precio_variante = 0;
                } else {
                    $precio_variante = $monto_q / $cantidad;
                }
            } else {
                if (round($cant_minima_etiq, -0) < $cantidad) {
                    $precio_variante = 0;
                } else {
                    $precio_variante = $monto_q / $cantidad;
                }
            }
        }

        // AUMENTAR EL PRECIO DEL METRO CUADRADO SEGUN LAS VARIABLES


        if ($tintas != 0) {
            if ($cyrel == 2) {
                $costo_cyrel = 0;
            } else {
                $precio_cyrel = VALORES_COTIZADOR['precio_cyrel']; // 50000
                $precio_cyreles = $precio_cyrel * $tintas;
                $costo_cyrel = $precio_cyreles / $cantidad;
            }
            $precio_bajo = $precio_bajo + $costo_cyrel;
            $precio_medio = $precio_medio + $costo_cyrel;
            $precio_alto = $precio_alto + $costo_cyrel;
            if ($precio_variante != 0) {
                $precio_variante = $precio_variante + $costo_cyrel;
            }
        }
        if ($troquel == 1) {
            $ml_requeridos = ($cantidad * $altgap) / $cav_ml;
            if ($ml_requeridos > VALORES_COTIZADOR['ml_req_troq_rotativo']) {
                $precio_troquel = VALORES_COTIZADOR['troquel_rotativo']; // Rotativo
            } else {
                $precio_troquel = VALORES_COTIZADOR['troquel_plano']; // Plano
            }
            $costo_troquel = $precio_troquel / $cantidad;
            $precio_bajo = $precio_bajo + $costo_troquel;
            $precio_medio = $precio_medio + $costo_troquel;
            $precio_alto = $precio_alto + $costo_troquel;
            if ($precio_variante != 0) {
                $precio_variante = $precio_variante + $costo_troquel;
            }
        }
        if ($estcalor == 1) {
            $precio_clice = VALORES_COTIZADOR['precio_clice'];
            $precio_clices = $precio_clice * $cantestcalor;
            $costo_clice = $precio_clices / $cantidad;
            $precio_bajo = $precio_bajo + $costo_clice;
            $precio_medio = $precio_medio + $costo_clice;
            $precio_alto = $precio_alto + $costo_clice;
            if ($precio_variante != 0) {
                $precio_variante = $precio_variante + $costo_clice;
            }
        }
        //echo $precio_variante;

        // print_r($precio_bajo." bajo ".$precio_medio." Medio ".$precio_alto." Alto<br>");
        if ($tipo_cotiza == 1) {
            $cant_minima_etiq = number_format(round($cant_minima_etiq, - 3), 0, ".", ".");
            $cant_minima_etiq1 = number_format(round($cant_minima_etiq1, - 3), 0, ".", ".");
            $cant_minima_etiq2 = number_format(round($cant_minima_etiq2, - 3), 0, ".", ".");
            $texto = 'ETIQUETAS:';
        } else {
            $cant_minima_etiq = number_format(round($cant_minima_etiq, - 0), 0, ".", ".");
            $cant_minima_etiq1 = number_format(round($cant_minima_etiq1, - 0), 0, ".", ".");
            $cant_minima_etiq2 = number_format(round($cant_minima_etiq2, - 0), 0, ".", ".");
            $texto = 'ROLLOS:';
        }


        if (empty($precio_m2)) {
            $precio_alto = 0;
            $precio_medio = 0;
            $precio_bajo = 0;
        }

        $data = [
            'cant_minima_etiq' => $cant_minima_etiq,
            'texto' => $texto,
            'precio_variante' => number_format($precio_variante, 2),
            'cant_minima_etiq' => $cant_minima_etiq,
            'cant_minima_etiq1' => $cant_minima_etiq1,
            'precio_alto' => number_format($precio_alto, 2),
            'cant_minima_etiq2' => $cant_minima_etiq2,
            'precio_medio' => number_format($precio_medio, 2),
            'precio_bajo' => number_format($precio_bajo, 2)
        ];
        echo json_encode($data);
        return;
    }
}
