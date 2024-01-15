<?php

namespace MiApp\negocio\controladores\Comercial;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\clientes_proveedorDAO;
use MiApp\persistencia\dao\PaisDAO;
use MiApp\persistencia\dao\DepartamentoDAO;
use MiApp\persistencia\dao\CiudadDAO;
use MiApp\persistencia\dao\direccionDAO;
use MiApp\persistencia\dao\cliente_productoDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\ruta_embobinadoDAO;
use MiApp\persistencia\dao\coreDAO;
use MiApp\persistencia\dao\trmDAO;
use MiApp\persistencia\dao\ClaseArticuloDAO;
use MiApp\negocio\util\Envio_Correo;
use MiApp\persistencia\dao\UsuarioDAO;
use MiApp\persistencia\dao\permisosDAO;
use MiApp\persistencia\dao\FormaMaterialDAO;
use MiApp\negocio\util\Validacion;


class MiClienteControlador extends GenericoControlador
{

    private $clientes_proveedorDAO;
    private $paisDAO;
    private $departamentoDAO;
    private $ciudadDAO;
    private $direccionDAO;
    private $cliente_productoDAO;
    private $productosDAO;
    private $ruta_embobinadoDAO;
    private $coreDAO;
    private $trmDAO;
    private $clase_articuloDAO;
    private $UsuarioDAO;
    private $permisosDAO;
    private $FormaMaterialDAO;


    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->clientes_proveedorDAO = new clientes_proveedorDAO($cnn);
        $this->paisDAO = new PaisDAO($cnn);
        $this->departamentoDAO = new DepartamentoDAO($cnn);
        $this->ciudadDAO = new CiudadDAO($cnn);
        $this->direccionDAO = new direccionDAO($cnn);
        $this->cliente_productoDAO = new cliente_productoDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->ruta_embobinadoDAO = new ruta_embobinadoDAO($cnn);
        $this->coreDAO = new coreDAO($cnn);
        $this->trmDAO = new trmDAO($cnn);
        $this->clase_articuloDAO = new ClaseArticuloDAO($cnn);
        $this->UsuarioDAO = new UsuarioDAO($cnn);
        $this->permisosDAO = new permisosDAO($cnn);
        $this->FormaMaterialDAO = new FormaMaterialDAO($cnn);
    }

    /*  
     * Función para cargar la vista (Mis clientes)
     */
    public function vista_mis_clientes()
    {
        parent::validar_permiso();
        parent::cabecera();
        $clientes_proveedor = $this->clientes_proveedorDAO->consultar_clientes();
        $clientes = [];
        if ($_SESSION['usuario']->getId_roll() != 1) {
            foreach ($clientes_proveedor as $value) {
                $asesores = explode(",", $value->id_usuarios_asesor ?? '');

                if (in_array($_SESSION['usuario']->getId_persona(), $asesores)) {
                    // $clientes[] = $value;
                    array_push($clientes, $value);
                }
            }
        } else {
            $clientes = $clientes_proveedor;
        }
        // header('Content-Type: application/json');
        // print_r($clientes);

        $this->view(
            'Comercial/vista_mis_clientes',
            [
                "client" => $clientes,
                "paises" => $this->paisDAO->consultar_pais(),
                "clase_articulo" => $this->clase_articuloDAO->consultar_clase_articulo(),
                "ruta_em" => $this->ruta_embobinadoDAO->consultar_ruta_embobinado(),
                "core" => $this->coreDAO->consultar_core(),
                "trm" => $this->trmDAO->ConsultaUltimoRegistro(),
                "departamento" => $this->departamentoDAO->consultar_departamento(),
                "ciudad" => $this->ciudadDAO->consultar_ciudad()

            ]
        );
    }

    /*     
    * Función para consultar mis productos por tipo de producto 
    */
    public function productos_por_tipo_producto()
    {
        header('Content-Type: application/json');
        $id_tipo_articulo = $_POST['tipo_articulo'];
        $resultado = $this->productosDAO->consultar_productos($id_tipo_articulo);
        echo json_encode($resultado);
    }
    /*     
    * Función para consultar mis clientes 
    */
    public function consultar_mis_clientes()
    {
        header('Content-Type: application/json');
        //Validar si el usuario es administrador  , muestra todos los clientes
        if ($_SESSION['usuario']->getId_roll() != 1) {
            $clientes = $this->clientes_proveedorDAO->consultar_clientes_asesor($_SESSION['usuario']->getId_persona(), $_SESSION['usuario']->getId_usuario());
        } else {
            $clientes = $this->clientes_proveedorDAO->consultar_clientes();
        }
        $arreglo["data"] = $clientes;
        echo json_encode($arreglo);
    }

    /*     
     * Función para crear direcciones del cliente. 
     */

    public function crear_dir_clientes()
    {
        header('Content-Type:application/json');
        $datos = $_POST;
        $datos['fecha_crea'] = date('Y-m-d');
        $respu = $this->direccionDAO->insertar($datos);
        echo json_encode($respu);
    }

    /*
     * Función para consultar las direcciones que tiene cada cliente.
     * Ingresadas por el asesor.
     */

    public function consultar_direccion_cliente()
    {
        header('Content-Type: application/json');
        $data = $_POST;
        $id_clien_prove = $data["id_cliv_prov"];
        $id_usuario = $_SESSION['usuario']->getId_usuario();
        $resultado = $this->clientes_proveedorDAO->consultar_direccion_cliente($id_clien_prove, $id_usuario);
        echo json_encode($resultado);
    }

    /* 
     * Función para modificar una direccion especifica del cliente.
     */

    public function modificar_direccion_cliente()
    {
        header('Content-Type:application/json');
        $datos = $_POST;
        $id_direccion = $datos['id_direccion'];
        // PREGUNTAR POR LA SESION PARA SABER SI ES ADMIN, SERVICIO AL CLIENTE PARA Q NO QUEDE REGISTRO DEL USUARIO QUE HIZO LA MODIFICACION
        $id_usuario = $_SESSION['usuario']->getId_usuario();
        $roll = $_SESSION['usuario']->getId_roll();
        if ($roll != 1 && ($id_usuario != 32 || $id_usuario != 86)) {
            $datos['id_usuario'] = $id_usuario;
        }
        unset($datos['id_direccion']);
        $condicion = 'id_direccion =' . $id_direccion;
        $resultado = $this->direccionDAO->editar($datos, $condicion);
        echo json_encode($resultado);
    }
    /* 
     * Función para modificar el estado a inactivo de una direccion especifica del cliente.
     */

    public function modificar_estado_dir()
    {
        header('Content-Type:application/json');
        $datos = $_POST;
        $id_direccion = $datos['id_direccion'];
        $condicion = 'id_direccion = ' . $id_direccion;
        unset($_POST['id_direccion']);
        $resultado = $this->direccionDAO->editar($datos, $condicion);
        echo json_encode($resultado);
    }

    /*     
     * Función para consultar los productos que tiene cada cliente.
     */

    public function consultar_productos_clientes()
    {
        header('Content-Type: application/json');
        $id_cli_prove = $_REQUEST['id'];
        $cliente = $this->clientes_proveedorDAO->consultar_clientes_proveedor($id_cli_prove);
        $id_asesores = $cliente[0]->id_usuarios_asesor;
        $asesores = explode(",", $id_asesores);
        $existe_asesor = false;
        if (in_array($_SESSION['usuario']->getId_persona(), $asesores)) {
            $existe_asesor = true;
        }
        if ($existe_asesor || $_SESSION['usuario']->getId_roll() == 1 || $_SESSION['usuario']->getId_roll() == 8) {
            $productos = $this->cliente_productoDAO->consultar_productos_clientes_asesor();
            $forma = $this->FormaMaterialDAO->consultar_forma_material();
            $tabla = [];
            foreach ($productos as $value) {
                $forma_material = Validacion::DesgloceCodigo($value->codigo_producto, 1, 1);
                if ($value->id_tipo_articulo == 1) {
                    foreach ($forma as $value_forma) {
                        if ($value_forma->id_forma == $forma_material) {
                            $value->forma = $value_forma->nombre_forma;
                        }
                    }
                } else {
                    $value->forma = '';
                }
                $value->nom_mon_venta = TIPO_MONEDA[$value->moneda];
                $value->nom_mon_autoriza = TIPO_MONEDA[$value->moneda_autoriza];
                if ($value->permiso_product == 0) {
                    if ($value->id_usuario == $_SESSION['usuario']->getId_usuario() || $_SESSION['usuario']->getId_roll() == 1 || $_SESSION['usuario']->getId_roll() == 8) {
                        $tabla[] = $value;
                    }
                } else {
                    $tabla[] = $value;
                }
            }
            $productos = $tabla;
        } else {
            $productos = array(
                'id_clien_produc' => 0,
                'codigo_producto' => 0,
                'descripcion_productos' => 'Usuario sin Permiso para ver los productos del cliente',
                'nombre_r_embobinado' => 0,
                'nombre_core' => 0,
                'presentacion' => 0.00,
                'moneda' => 1,
                'precio_venta' => 0.00,
            );
        }
        $arreglo["data"] = $productos;
        echo json_encode($arreglo);
    }
    /*    
     * Función  para crear los productos del cliente. 
     */

    public function crear_producto_clientes()
    {
        header('Content-Type:application/json');
        $datos = Validacion::Decodifica($_POST['form']);
        $envio = $_POST['envio'];
        $id_tipo_articulo = $datos['id_tipo_producto'];
        if ($datos['id_tipo_producto'] == 2) {
            if ($envio == 1) {
                $datos_venta = $this->validar_precio_venta($datos);
            } else {
                $datos_venta = $this->validar_precio_venta($datos);
                $datos_venta['status'] = 1;
            }
        } else {
            if ($envio == 1) {
                $datos_venta = $this->validar_precio_tecno($datos);
            } else {
                $datos_venta = $this->validar_precio_tecno($datos);
                $datos_venta['status'] = 1;
            }
        }
        if ($datos_venta['status'] == -1) {
            $respu = array(
                'status' => false,
            );
        } else {
            if ($envio == 1) {
                $add_datos = $datos_venta['datos_autoriza'];
                $datos['moneda_autoriza'] = $add_datos['moneda_autoriza'];
                $datos['precio_autorizado'] = $datos['precio_venta'];
                $datos['cantidad_minima'] = $add_datos['cantidad_minima'];
            }
            $extencia = $this->cliente_productoDAO->valida_codigo_cliente($datos);
            if ($extencia == 0) {
                $datos['fecha_crea'] = date('Y-m-d');
                unset($datos['id_tipo_producto']);
                $respu = $this->cliente_productoDAO->insertar($datos);
                if ($envio != 1) {
                    $this->correo_aprobar_precio($respu['id'], $id_tipo_articulo);
                }
            } else {
                $datos['estado_client_produc'] = 1;
                $datos['fecha_crea'] = date('Y-m-d');
                unset($datos['id_tipo_producto']);
                $condicion = 'id_clien_produc=' . $extencia[0]->id_clien_produc;
                $editar = $this->cliente_productoDAO->editar($datos, $condicion);
                if ($envio != 1) {
                    $this->correo_aprobar_precio($extencia[0]->id_clien_produc, $id_tipo_articulo);
                }
                $respu = array(
                    'status' => $editar
                );
            }
        }
        echo json_encode($respu);
    }
    /*    
     * Función para modificar los productos especificos del cliente.
     */

    public function modificar_producto_clientes()
    {
        header('Content-Type: application/json');
        $datos = Validacion::Decodifica($_POST['form']);
        $envio = $_POST['envio'];
        $datos['precio_venta'] = strtr($datos['precio_venta'], ",", ".");
        $extencia = $this->cliente_productoDAO->valida_codigo_cliente($datos);
        $editar = false;
        $id_tipo_articulo = $datos['id_clase_articulo'];
        // PREGUNTAR POR LA SESION PARA SABER SI ES ADMIN, SERVICIO AL CLIENTE PARA Q NO QUEDE REGISTRO DEL USUARIO QUE HIZO LA MODIFICACION
        $id_usuario = $_SESSION['usuario']->getId_usuario();
        $roll = $_SESSION['usuario']->getId_roll();
        if ($roll != 1 || $id_usuario != 32 || $id_usuario != 86) {
            $_POST['id_usuario'] = $id_usuario;
        }
        if ($extencia == 0) {
            $editar = true;
        } else {
            if ($extencia[0]->id_clien_produc == $datos['id_clien_produc']) {
                $editar = true;
            } else {
                $editar = false;
            }
        }
        if ($editar) {
            $product_clien = $this->cliente_productoDAO->cliente_producto_id($datos['id_clien_produc']);
            if ($datos['id_clase_articulo'] == 2) {
                if ($product_clien[0]->ubi_troquel == 'EXTERNO') {
                    $datos_venta = [
                        'status' => 1,
                        'datos_autoriza' => array(
                            'moneda_autoriza' => 0,
                            'precio_autorizado' => 0,
                            'cantidad_minima' => 0,
                        )
                    ];
                } else {
                    if ($envio == 1) {
                        $datos_venta = $this->validar_precio_venta($datos);
                    } else {
                        $datos_venta = $this->validar_precio_venta($datos);
                        $datos_venta['status'] = 1;
                    }
                    if ($product_clien[0]->cantidad_minima == 0) {
                        $datos_venta = self::validar_precio_venta($datos);
                    } else {
                        if ($datos['precio_venta'] >= $product_clien[0]->precio_autorizado) {
                            $datos_venta = [
                                'status' => 1,
                                'datos_autoriza' => array(
                                    'moneda_autoriza' => $product_clien[0]->moneda_autoriza,
                                    'precio_autorizado' => $datos['precio_venta'],
                                    'cantidad_minima' => $product_clien[0]->cantidad_minima,
                                )
                            ];
                        }
                    }
                }
            } else {
                // En este punto se revisa el precio de la tecnologia
                if ($envio == 1) {
                    $datos_venta = $this->validar_precio_tecno($datos);
                } else {
                    $datos_venta = $this->validar_precio_tecno($datos);
                    $datos_venta['status'] = 1;
                }
            }
            if ($datos_venta['status'] == -1) {
                $resultado = false;
            } else {
                if ($envio == 1 && $product_clien[0]->cantidad_minima == 0) {
                    $add_datos = $datos_venta['datos_autoriza'];
                    $datos['moneda_autoriza'] = $add_datos['moneda_autoriza'];
                    $datos['precio_autorizado'] = $datos['precio_venta'];
                    $datos['cantidad_minima'] = $add_datos['cantidad_minima'];
                }
                if ($envio == 1 && $product_clien[0]->cantidad_minima != 0 && $product_clien[0]->moneda_autoriza != 0) {
                    $datos['precio_autorizado'] = $datos['precio_venta'];
                }
                $id_clien_produc = $datos['id_clien_produc'];
                $condicion = 'id_clien_produc=' . $id_clien_produc;
                unset($datos['id_clien_produc']);
                unset($datos['id_clase_articulo']);
                $resultado = $this->cliente_productoDAO->editar($datos, $condicion);
                if ($envio != 1) {
                    $this->correo_aprobar_precio($id_clien_produc, $id_tipo_articulo);
                }
            }
        } else {
            $resultado = false;
        }
        echo json_encode($resultado);
    }
    /*   
     * Función para CAMBIAR ESTADO PRODUCTO ASESOR
     */

    public function cambiar_estado_pro_cli()
    {
        header('Content-Type: application/json');
        $estado['estado_client_produc'] = 0;
        $resultado = $this->cliente_productoDAO->editar($estado, ' id_clien_produc = ' . $_POST['id_clien_produc']);
        echo json_encode($resultado);
    }

    /**
     * Función para consultar los departamentos especificos que tenga cada pais.
     */
    public function consultar_departamento_especifico()
    {
        header('Content-Type: application/json');
        $resultado = $this->departamentoDAO->consultar_departamento_especifico();
        echo json_encode($resultado);
    }
    /**
     * Función para consultar la ciudad especifica.
     */
    public function consultar_ciudad_especifica()
    {
        header("Content-type: application/json; charset=utf-8");
        $resultado = $this->ciudadDAO->consultar_ciudad_especifica();
        echo json_encode($resultado);
    }
    /**
     * Función para enviar correo de aprobacion de precio.
     */
    public function correo_aprobar_precio($id_clien_produc, $id_tipo_articulo)
    {
        if ($id_tipo_articulo == 2) {
            $correo = CORREO_COMPRAS_MA; // correo del encargado de aprobacion de precios
        } else {
            $correo = CORREO_COMPRAS_TEC; // correo del encargado de aprobacion de precios
        }
        $producto = $this->cliente_productoDAO->cliente_producto_id($id_clien_produc);
        $user = $this->UsuarioDAO->consultarIdUsuario($producto[0]->id_usuario);
        $correo = Envio_Correo::envio_correo_aprobacion_precio($producto, $user, $correo);
    }

    /*  
     * Función para cargar la vista (vista_pqr)
     */
    public function vista_pqr()
    {
        parent::validar_permiso();
        parent::cabecera();
        $this->view(
            'Comercial/vista_pqr'
        );
    }

    /*  
     * Función para validar precio autorizado
     */
    public function validar_precio_venta($datos)
    {
        $id_producto = $datos['id_producto'];
        $datos_producto = $this->productosDAO->ConsultaProductoId($id_producto);
        if ($datos_producto[0]->id_tipo_articulo != 1) {
            $datos_res = [
                'moneda_autoriza' => $datos['moneda'],
                'precio_autorizado' => $datos['precio_venta'],
                'cantidad_minima' => $datos['cantidad_minima'],
            ];
            $respu = [
                'status' => 1,
                'datos_autoriza' => $datos_res
            ];
        } else {
            if ($datos_producto[0]->ubi_troquel != 'EXTERNO' && $datos_producto[0]->ubi_troquel != 'EXT') {
                $codigo_producto = $datos_producto[0]->codigo_producto;
                $datos_etiq = parent::precio_etiqueta($codigo_producto, $datos['cantidad_minima']);
                $datos_etiq['moneda_autorizada'] = $datos['moneda'];
                $valor_compara = [
                    1 => $datos_etiq['precio_alto'],
                    2 => $datos_etiq['precio_medio'],
                    3 => $datos_etiq['precio_bajo'],
                    4 => $datos_etiq['precio_variante'],
                ];
                if ($datos['cantidad_minima'] < $datos_etiq['cant_minima_etiq']) {
                    $lista_compara = 4;
                }
                if ($datos['cantidad_minima'] >= $datos_etiq['cant_minima_etiq'] && $datos['cantidad_minima'] < $datos_etiq['cant_minima_etiq1']) {
                    $lista_compara = 1;
                }
                if ($datos['cantidad_minima'] >= $datos_etiq['cant_minima_etiq1'] && $datos['cantidad_minima'] < $datos_etiq['cant_minima_etiq2']) {
                    $lista_compara = 2;
                }
                if ($datos['cantidad_minima'] > $datos_etiq['cant_minima_etiq2']) {
                    $lista_compara = 3;
                }
                $datos_res = [
                    'moneda_autoriza' => $datos['moneda'],
                    'precio_autorizado' => $valor_compara[$lista_compara],
                    'cantidad_minima' => $datos['cantidad_minima'],
                ];
                if ($datos['precio_venta'] < $valor_compara[$lista_compara]) {
                    $respu = [
                        'status' => -1,
                        'datos_autoriza' => $datos_res
                    ];
                } else {
                    $respu = [
                        'status' => 1,
                        'datos_autoriza' => $datos_res
                    ];
                }
            } else {
                $datos_res = [
                    'moneda_autoriza' => $datos['moneda'],
                    'precio_autorizado' => $datos['precio_venta'],
                    'cantidad_minima' => $datos['cantidad_minima'],
                ];
                $respu = [
                    'status' => 1,
                    'datos_autoriza' => $datos_res
                ];
            }
        }
        return $respu;
    }

    public function validar_precio_tecno($datos)
    {
        $trm = $this->trmDAO->ConsultaUltimoRegistro();
        $id_producto = $datos['id_producto'];
        $datos_producto = $this->productosDAO->ConsultaProductoId($id_producto);
        $precio_venta = $datos['precio_venta'];
        if ($datos['moneda'] == 2) {
            $precio_venta = $datos['precio_venta'] * $trm[0]->valor_trm;
        }
        $valor_compara = $datos_producto[0]->precio1;
        if ($datos_producto[0]->moneda_producto == 2) {
            $valor_compara = $datos_producto[0]->precio1 * $trm[0]->valor_trm;
        }
        $datos_res = [
            'moneda_autoriza' => $datos['moneda'],
            'precio_autorizado' => $datos['precio_venta'],
            'cantidad_minima' => $datos['cantidad_minima'],
        ];
        if ($precio_venta >= $valor_compara) {
            $respu = [
                'status' => 1,
                'datos_autoriza' => $datos_res
            ];
        } else {
            $respu = [
                'status' => -1,
                'datos_autoriza' => $datos_res
            ];
        }
        return $respu;
    }
}
