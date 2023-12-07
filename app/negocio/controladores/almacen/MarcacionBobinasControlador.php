<?php

namespace MiApp\negocio\controladores\almacen;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\ItemProducirDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\impresora_tamanoDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\negocio\util\Validacion;
use PDO;

final class MarcacionBobinasControlador extends GenericoControlador
{
    private $ItemProducirDAO;
    private $PersonaDAO;
    private $impresora_tamanoDAO;
    private $productosDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->ItemProducirDAO = new ItemProducirDAO($cnn);
        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->impresora_tamanoDAO = new impresora_tamanoDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
    }

    public function vista_marcacion_bobinas()
    {
        parent::cabecera();
        $this->view(
            'almacen/vista_marcacion_bobinas',
            [
                'tamano' => $this->impresora_tamanoDAO->consulta_tamano("100X50"),
                'bobinas' => $this->productosDAO->consulta_bobinas(),
            ]
        );
    }

    public function  consulta_marcacion_bobinas()
    {
        header('Content-Type: application/json');
        $datos = $this->productosDAO->consulta_marcacion_bobinas($_GET['codigo']);
        echo json_encode($datos);
        return;
    }
    public function  consulta_cod_bobinas()
    {
        header('Content-Type: application/json');
        $datos = $_GET;
        $respu = '';
        $consulta = self::conexion_base_datos($datos['codigo']);
        if (!empty($consulta)) {
            $respu = $this->productosDAO->consulta_cod_bobinas($consulta[0]['cod_antiguo'], $consulta[0]['cod_nuevo']);
            $respu[0]->codigo_producto = $consulta[0]['cod_nuevo'];
            $respu[0]->descripcion_productos = $consulta[0]['descripcion'];
        }
        echo json_encode($respu);
        return;
    }

    public function conexion_base_datos($codigo)
    {
        if (MODO_PRUEBA) {
            $host = 'acobarras.com.co';
            $db = 'acobarra_codigos_bobinas';
            $user = 'acobarra_root';
            $pass = 'nuevouser01';
            $charset = 'utf8';
            $port = '3306';
        } else {
            $host = 'localhost';
            $db = 'acobarra_codigos_bobinas';
            $user = 'acobarra_root';
            $pass = '@Pr&nc&palS&dpa2022';
            $charset = 'utf8';
            $port = '3306';
        }
        $cnn2 = new PDO('mysql:port=' . $port . ';host=' . $host . ';charset=' . $charset . ';dbname=' . $db, $user, $pass);
        $cnn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT * FROM `codigo_bobina` WHERE cod_qr='$codigo' OR cod_nuevo='$codigo' OR cod_antiguo='$codigo' ";
        $stmt = $cnn2->query($query);

        // Obtener resultados como un array asociativo
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultados;
    }
}
