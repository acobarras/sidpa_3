<?php

namespace MiApp\negocio\util;

// require_once './app/vendor/phpqrcode/qrlib.php';

// use persistencia\vo\UsuarioVO;
// use QRcode;
// use const CARPETA_PRINCIPAL;

class Archivo
{


    /**
     * Funcion para guardar el PDF del Pedido si tiene orden de compra.
     * @param type $nombre
     * @param type $id_pedido
     * @param type $orden_compra
     * @return string
     */
    public static function moverArchivos_ocompra($nombre, $id_pedido, $orden_compra)
    {
        $nombreFinal = '';
        if (isset($_FILES[$nombre])) {
            $pdf = $_FILES[$nombre];
            $ext = str_replace('application/', '', $pdf['type']);
            $nombreFinal = "" . $orden_compra . "_" . $id_pedido . "." . $ext;
            $ruta = CARPETA_IMG . PROYECTO . '/PDF/ocompra/' . $nombreFinal;
            move_uploaded_file($pdf['tmp_name'], $ruta);
        }
        return $nombreFinal;
    }

    public static function subirImagen($imagen, $nombre, $ubicacion)
    {
        $ext = str_replace('application/', '', $imagen['type']);
        if ($ext == 'image/png') {
            $ext = 'png';
        }
        $nombreFinal = $nombre . "." . $ext;
        $ruta = CARPETA_IMG . PROYECTO . $ubicacion . $nombreFinal;
        move_uploaded_file($imagen['tmp_name'], $ruta);
        // $ruta2 = 'C:/xampp/htdocs' . RUTA_PRINCIPAL . '/' . CARPETA_IMG . $ubicacion . $nombreFinal;
        return $nombreFinal;
    }

    public static function convierteB64($ruta_imagen)
    {
        $path = $ruta_imagen;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = base64_encode($data);
        unlink($ruta_imagen); //elimino el fichero
        return $base64;
    }
}
