<?php

namespace MiApp\negocio\util;

require_once 'vendor/phpqrcode/qrlib.php';

use QRcode;

class Validacion
{

    /**
     * Función statica para encriptar la clave del nuevo usuario.
     * @param type $pasword
     * @return type
     */
    public static function clave($pass)
    {
        $long = strlen($pass);
        $str = '';
        for ($x = 0; $x < $long; $x++) {
            $str .= ($x % 2) != 0 ? md5($pass[$x]) : $x;
        }
        return md5($str);
    }
    // Pasa una variable get a un array
    public static function Decodifica($get)
    {
        $listaAtributos = $get;
        $lista = array();

        foreach (explode('&', $listaAtributos) as $chunk) {
            $param = explode("=", $chunk);
            if ($param) {
                $lista[urldecode($param[0])] = urldecode($param[1]);
            }
        }
        return $lista;
    }

    public static function moverArchivos($nombre, $nombre_imagen)
    {
        $nombreFinal = '';
        if (isset($_FILES[$nombre])) {
            $foto = $_FILES[$nombre];
            $ext = str_replace('image/', '', $foto['type']);
            $nombreFinal =  $nombre_imagen . '.' . $ext;
            $cache = $_FILES[$nombre]['tmp_name'];
            $ruta = CARPETA_VIEW .  CARPETA_IMG . PROYECTO . '/foto_usuarios/' . $nombreFinal;
            move_uploaded_file($cache, $ruta);
        }
        return $nombreFinal;
    }

    public static function QR($data)
    {
        //verificar la carpeta QR
        $dir = CARPETA_IMG . PROYECTO . "/img_qr";
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        $filename = $dir . $data . '.png';
        $tamaño = 1;
        $level = 'M';
        $frameSize = 1;
        $contenido = "" . $data . "";
        QRcode::png($contenido, $filename, $level, $tamaño, $frameSize);
        return $filename;
    }

    public static function DELETE_QR()
    {

        //verificar la carpeta QR
        $dir = CARPETA_IMG . PROYECTO . "/img_qr/QR/";
        if (file_exists($dir)) {
            $files = glob(CARPETA_IMG . PROYECTO . "/img_qr/QR/*"); //obtenemos todos los nombres de los ficheros
            foreach ($files as $file) {
                if (is_file($file))
                    unlink($file); //elimino el fichero
            }
        }
        return;
    }

    public static function GeneraQR($codigo, $nombre)
    {
        QRcode::png($codigo, CARPETA_IMG . PROYECTO . "/img_qr/QR/" . $nombre . ".png", QR_ECLEVEL_L, 2, 1);
    }

    // Pasa una variable get a un array
    public static function aumento_fechas($fecha, $dias)
    {
        date_default_timezone_set('America/Bogota');
        $fecha_nueva = date_add($fecha, date_interval_create_from_date_string($dias . " days"));
        $fecha_final = date_format($fecha_nueva, "Y-m-d");
        return $fecha_final;
    }
    // Pasa una variable get a un array
    public static function resto_fechas($fechaEnvio, $fechaActual)
    {
        date_default_timezone_set('America/Bogota');
        $datetime1 = date_create($fechaEnvio);
        $datetime2 = date_create($fechaActual);
        $contador = date_diff($datetime1, $datetime2);
        $differenceFormat = '%a';
        $fecha_final = $contador->format($differenceFormat);
        return $fecha_final;
    }

    public static function DesgloceCodigo($codigo, $aumento, $datos_requeridos)
    {
        // $codigo = '100X75-101A101103';
        $caracter = "-";
        $posicion_inicio = strpos($codigo, $caracter);
        $respu = substr($codigo, ($posicion_inicio + $aumento), $datos_requeridos);
        return $respu;
    }

    public static function ReemplazaCaracter($variable, $busca, $remplaza)
    {
        $cambio = str_replace($busca, $remplaza, $variable);
        return $cambio;
    }

    public static function TamanoCodigo($codigo)
    {
        $codigo = strtoupper($codigo);
        $caracter = "X";
        $guion = "-";
        $posicion_equis = strpos($codigo, $caracter);
        $posicion_guion = strpos($codigo, $guion);
        $campo_alto = $posicion_guion - $posicion_equis;
        $ancho = substr($codigo, 0, $posicion_equis);
        $alto = substr($codigo, ($posicion_equis + 1), ($campo_alto - 1));
        if ($ancho == '' || $alto == '') {
            $respu = false;
        } else {
            $respu = [
                'ancho' => $ancho,
                'alto' => $alto
            ];
        }
        return $respu;
    }

    public static function consumo_etiqueta($codigo)
    {
        $tamanos = self::TamanoCodigo($codigo);
        $consumo_m2 = 0;
        if ($tamanos != false) {
            $forma_material = self::DesgloceCodigo($codigo, 1, 1);
            $ancho = str_replace(',', '.', $tamanos['ancho']);
            $alto = str_replace(',', '.', $tamanos['alto']);
            $aumento = true;
            if ($forma_material == 6) {
                $aumento = false;
                $ancho = $ancho + 2;
                $alto = ($alto * 1000);
            }
            if ($forma_material == 4) {
                $aumento = false;
            }
            if ($forma_material == 7) {
                $aumento = false;
                $ancho = $ancho + 2;
                $alto = $alto;
            }
            if ($aumento) {
                $ancho = $ancho + 3;
                $alto = $alto + 3;
            }
            $consumo_mc = ($ancho * $alto) / 100;
            $consumo_m2 = $consumo_mc / 10000;
        }
        return $consumo_m2;
    }

    public static function datos_avance($alto, $constante = '')
    {
        $obj = json_decode(json_encode(CILINDROS));
        if ($constante == '') {
            $constante = 3;
        }
        $alto_minimo = $alto + $constante;
        $gap_real = [];
        foreach ($obj as $value) {
            $dato_calculo = $value->desarrollo / $alto_minimo;
            $repeticiones = intval($dato_calculo);
            $decimal = $dato_calculo - $repeticiones;
            if ($repeticiones != 0) {
                $avance = $value->desarrollo / $repeticiones;
                $gap_valor = $avance - $alto;
                if (empty($gap_real)) {
                    $gap_real = [
                        'cilindro' => $value->cilindro,
                        'avance' => $avance,
                        'decimal' => $decimal,
                        'gap' => $gap_valor,
                        'repeticiones' => $repeticiones
                    ];
                } else {
                    if ($decimal < $gap_real['decimal']) {
                        $gap_real = [
                            'cilindro' => $value->cilindro,
                            'avance' => $avance,
                            'decimal' => $decimal,
                            'gap' => $gap_valor,
                            'repeticiones' => $repeticiones
                        ];
                    }
                }
            }
        }
        return $gap_real;
    }

    public static function costo_tinta($costo, $tintas)
    {
        $nuevo_costo = $costo;
        for ($i = 0; $i < $tintas; $i++) {
            $nuevo_costo = $nuevo_costo / CONST_TINTA;
        }
        return $nuevo_costo;
    }

    public static function reArrayFiles($file_post)
    {
        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);
        for ($i = 0; $i < $file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }
        return $file_ary;
    }
    // /**
    //  * Funtión statica para validar la fecha de compromiso del pedido retenido.
    //  * @param type $dias_produccion
    //  * @param type $dias_festivos
    //  * @return type
    //  */
    // public static function validar_fecha_compromiso($dias_produccion, $dias_festivos) {
    //     //crear variable segundos
    //     $Segundos = 0;
    //     //establecer el tiempo de Colombia Bogotá
    //     date_default_timezone_set('America/Bogota');
    //     //agregar los días festivos de Colombia.
    //     $festivos = $dias_festivos;
    //     //Cantidad de dias maximo para la fecha de compromiso. 
    //     $MaxDias = $dias_produccion[0]->cantidad_color;
    //     //Creamos un for desde 0 hasta dias de producción  
    //     for ($i = 0; $i < $MaxDias; $i++) {
    //         //Acumulamos la cantidad de segundos que tiene un dia en cada vuelta del for  
    //         $Segundos = $Segundos + 86400;
    //         //Obtenemos el dia de la fecha, aumentando el tiempo en N cantidad de dias, segun la vuelta en la que estemos  
    //         $caduca = date("D", time() + $Segundos);
    //         //Comparamos si estamos en sabado o domingo, si es asi restamos una vuelta al for, para brincarnos los dias  
    //         if ($caduca == "Sun") {
    //             $i--;
    //         } else {
    //             //Si no es sabado o domingo, el for termina y nos muestra la nueva fecha  
    //             $FechaFinal = (date("Y-m-d", time() + $Segundos));
    //             $Fecha = date("Y-m-d", strtotime($FechaFinal . "- 1 days"));
    //         }
    //     }

    //     //Funcion para validar los días festivos 
    //     function festivos($Fecha, $festivos) {
    //         for ($j = 0; $j < count($festivos); $j++) {
    //             //si es festivo se le agrega otro dia , sino se deja tal cual la fecha
    //             if ($Fecha == $festivos[$j]->fecha_dia) {
    //                 $Fecha = date("Y-m-d", strtotime($Fecha . "+ 1 days"));
    //             } else {
    //                 $Fecha = $Fecha;
    //             }
    //         }
    //         /* retornar la nueva fecha */
    //         return $Fecha;
    //     }

    //     return festivos($Fecha, $festivos);
    // }
    public static function quitarTildes($cadena) {
        $tildes = array(
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'Á' => 'A',
            'É' => 'E',
            'Í' => 'I',
            'Ó' => 'O',
            'Ú' => 'U',
            'ü' => 'u',
            'Ü' => 'U',
            'ñ' => 'n',
            'Ñ' => 'N'
        );
    
        return strtr($cadena, $tildes);
    }

}
