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

    /**
     * Función para guardar y asignar el nombre de foto del usuario.
     * @param type $nombre
     * @return string
     */
    // public static function moverArchivos($nombre) {
    //     $nombreFinal = '';
    //     if (isset($_FILES[$nombre])) {
    //         $foto = $_FILES[$nombre];
    //         $ext = str_replace('image/', '', $foto['type']);
    //         $nombreFinal = round(microtime(TRUE) * 1000) . '_' . rand(0, 1000) . '.' . $ext;
    //         $ruta = CARPETA_PRINCIPAL . '/public/img/usuarios_foto/' . $nombreFinal;
    //         move_uploaded_file($foto['tmp_name'], $ruta);
    //     }
    //     return $nombreFinal;
    // }

    /**
     * Función para guardar la cookie del usuario logueado.
     * @param type $info
     * @return type
     */
    //     public static function cookies($info) {
    //         if (isset($_COOKIE[$info])) {
    //             $infoUsuario = json_decode($_COOKIE['usuario'], TRUE);
    //             $usuario = new UsuarioVO();
    //             $usuario->setId_usuario($infoUsuario['id_usuario']);
    //             $usuario->setUsuario($infoUsuario['usuario']);
    //             $usuario->setId_roll($infoUsuario['id_roll']);
    //             $usuario->setNombre($infoUsuario['nombre']);
    //             $usuario->setId_persona($infoUsuario['id_persona']);
    //             $_SESSION['usuario'] = $usuario;
    // //            header('location: ' . MENU['url']);
    //             return;
    //         }
    //     }

    // public static function QR($data) {

    //     //verificar la carpeta QR
    //     $dir = 'public/QR/';
    //     if (!file_exists($dir)) {
    //         mkdir($dir);
    //     }
    //     $filename = $dir . $data . '.png';

    //     $tamaño = 1;
    //     $level = 'M';
    //     $frameSize = 1;
    //     $contenido = "" . $data . "";

    //     QRcode::png($contenido, $filename, $level, $tamaño, $frameSize);
    //     return $filename;
    // }

    // public static function DELETE_QR() {

    //     //verificar la carpeta QR
    //     $dir = 'public/QR/';
    //     if (file_exists($dir)) {
    //         $files = glob('public/QR/*'); //obtenemos todos los nombres de los ficheros
    //         foreach ($files as $file) {
    //             if (is_file($file))
    //                 unlink($file); //elimino el fichero
    //         }
    //     }
    //     return;
    // }

    // public static function GeneraQR($codigo,$nombre) {
    //     QRcode::png($codigo,"public/QR/".$nombre.".png",QR_ECLEVEL_L,2,1);
    // }


}
