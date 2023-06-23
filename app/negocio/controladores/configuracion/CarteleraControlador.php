<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\ConsCotizacionDAO;

class CarteleraControlador extends GenericoControlador
{

    private $ConsCotizacionDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
    }

    public function vista_crea_cartelera()
    {
        parent::cabecera();
        $this->view(
            'configuracion/vista_crea_cartelera'
        );
    }

    public function insertar_cartelera()
    {
        header('Content-type: application/json');
        $gestor = CARPETA_IMG . PROYECTO . '/cartelera';
        if (isset($_POST['archivo'])) {
            $ruta = $gestor . "/" . $_POST['archivo'];
            unlink($ruta);
            $respu = [
                'status' => 1,
                'msg' => 'Su Archivo fue Eliminado exitosamente'
            ];
        } else {
            $name_img = $_FILES['file']['name'];
            $file = $_FILES['file']['tmp_name'];
            $imagen = getimagesize($file);    //Sacamos la información
            $ancho = $imagen[0];              //Ancho
            $alto = $imagen[1];
            $ruta = $gestor . "/" . $name_img;
            if ($ancho >= 790 and $ancho <= 940) {
                if ($alto >= 1050 and $alto <= 1300) {
                    $existe = file_exists($ruta);
                    if ($existe) {
                        $respu = [
                            'status' => -1,
                            'msg' => 'Lo sentimos el nombre de archivo ya existe por favor renombre el archivo e intente nuevamente'
                        ];
                    } else {
                        move_uploaded_file($file, $ruta);
                        $respu = [
                            'status' => 1,
                            'msg' => 'Su Archivo fue cargado exitosamente'
                        ];
                    }
                } else {
                    $respu = [
                        'status' => -1,
                        'msg' => 'Lo sentimos el alto no esta en el rango optimo 1050 a 1300'
                    ];
                }
            } else {
                $respu = [
                    'status' => -1,
                    'msg' => 'Lo sentimos el ancho no esta en el rango optimo 790 a 940'
                ];
            }
        }
        echo json_encode($respu);
        return;
    }
}
