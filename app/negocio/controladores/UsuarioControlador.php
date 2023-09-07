<?php

namespace MiApp\negocio\controladores;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\UsuarioDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\RollDAO;
use MiApp\persistencia\dao\Modulos_hojasDAO;
use MiApp\persistencia\dao\permisosDAO;

class UsuarioControlador extends GenericoControlador
{

  private $usuarioDAO;
  private $PersonaDAO;
  private $RollDAO;
  private $Modulos_hojasDAO;
  private $permisoDAO;

  public function __construct(&$cnn)
  {

    parent::__construct($cnn);
    // parent::validarSesion();

    $this->usuarioDAO = new UsuarioDAO($cnn);
    $this->PersonaDAO = new PersonaDAO($cnn);
    $this->RollDAO = new RollDAO($cnn);
    $this->Modulos_hojasDAO = new Modulos_hojasDAO($cnn);
    $this->permisoDAO = new permisosDAO($cnn);
  }

  public function autenticar()
  {

    header('Content-Type: application/json');
    $respuesta = array();
    $nombre_usuario = $_POST['usu_usuario'];
    $clave = $_POST['usu_pasword'];
    $pasword = Validacion::clave($clave);
    $autenticar = $this->usuarioDAO->autenticar($nombre_usuario, $pasword);
    if (is_null($autenticar)) {
      $respuesta['codigo'] = -1;
      $respuesta['mensaje'] = "Error en usuario o contraseña";
      echo json_encode($respuesta);
      return;
    }
    //var_dump($tiempo);//saber el valor de una variable
    $info = json_encode($autenticar->getAtributos());
    $tiempo = (time() + 1) + (60 * 60 * 24 * 365);
    setcookie('usuario', $info, $tiempo, CARPETA_VIEW);

    $_SESSION['usuario'] = $autenticar;
    $respuesta['codigo'] = 1;
    $respuesta['url'] = RUTA_PRINCIPAL . '/menu';
    echo json_encode($respuesta);
    return;
  }

  /**
   * Función para cerrrar la sesion del usuario logueado.
   */
  public function CerrarSesion()
  {
    setcookie('usuario', '', -1);
    session_unset();
    session_destroy();
    header('location: ' . RUTA_PRINCIPAL);
  }


  /**
   * Función para Actualizar contraseña primer inicio de sesion
   */
  public function actualizar_contrasena()
  {

    header('Content-Type: application/json');
    $id_usuario = $_SESSION['usuario']->getId_usuario();
    $pasword = Validacion::clave($_POST['pasword']);
    $actualizar_contraseña = $this->usuarioDAO->modificar_contraseña($pasword, $id_usuario);
    setcookie('usuario', NULL, -1);
    session_unset();
    session_destroy();
    echo json_encode($actualizar_contraseña);
  }

  public function vista_modulo_usuarios()
  {

    parent::cabecera();
    $this->view(
      'configuracion/vista_modulo_usuarios',
      [
        "p" => $this->PersonaDAO->consultar_personas(),
        "r" => $this->RollDAO->consultar_roll(),
        'lista_inicio' => $this->Modulos_hojasDAO->Consultar_modulo_hojas('inicio'),
      ]
    );
  }

  public function consultar_modulo_usuarios()
  {

    header('Content-Type: application/json');
    $resultado = $this->usuarioDAO->ConsultarUsuarioPersona();
    $arreglo = $resultado;
    echo json_encode($arreglo);
  }

  public function modificar_estado_usuario()
  {

    header('Content-Type: application/json');
    $editar_estado = ['estado_usu' => $_POST['estado']];
    $condicion = 'id_usuario =' . $_POST['id_usuario'];
    $resultado = $this->usuarioDAO->editar($editar_estado, $condicion);
    echo json_encode($resultado);
  }

  public function eliminar_usuario()
  {

    header('Content-Type:application/json');
    $imagen = $_POST['ruta_foto'];
    $ruta = CARPETA_VIEW . CARPETA_IMG . PROYECTO . '/foto_usuarios/' . $imagen;
    unlink($ruta); //elimina acá le damos la direccion exacta del 
    $id = $_POST['id_usuario'];
    $condicion = 'id_usuario =' . $id;
    $resultado = $this->usuarioDAO->eliminar($condicion);
    echo json_encode($resultado);
  }

  public function modificar_usuario()
  {
    header('Content-Type: application/json');
    $datos = Validacion::Decodifica($_POST['form']);
    $id_usuario = $_POST['id_usuario'];
    if ($datos['nuevo_pasword'] == '') {
      $datos['pasword'] = $datos['pasword'];
    } else {
      $datos['pasword'] = Validacion::clave($datos['nuevo_pasword']);
    }
    // Elimino los registros que no requiero enviar para ser editados
    unset($datos['nuevo_pasword']);
    $condicion = 'id_usuario =' . $id_usuario;
    $resultado = $this->usuarioDAO->editar($datos, $condicion);
    echo json_encode($resultado);
  }

  public function insertar_usuarios()
  {
    header('Content-Type: application/json');
    $respuesta = array();
    $usuarioExiste = $this->usuarioDAO->consultarUsuarioExiste($_POST['usuario']);
    if (count($usuarioExiste) > 0) {
      $respuesta['estado'] = false;
    } else {
      $datos = $_POST;
      $persona = $this->PersonaDAO->consultar_personas_id($datos['id_persona']);
      $pasword = Validacion::clave($datos['pasword']);
      $datos['pasword'] = $pasword;
      $datos['fecha_crea'] = date('Y-m-d');
      $datos['id_usuario_crea'] = $_SESSION['usuario']->getid_usuario();
      $id_usuario = $this->usuarioDAO->insertar($datos);
      if (!empty($_FILES['ruta_foto']['name'])) {
        // Cargar la foto 
        $nombre_imagen = $persona[0]->num_documento . '_' . $id_usuario['id'];
        $ruta_foto = Validacion::moverArchivos('ruta_foto', $nombre_imagen);
        $carga_foto = ['ruta_foto' => $ruta_foto];
        $condicion = 'id_usuario =' . $id_usuario['id'];
      } else {
        $carga_foto = ['ruta_foto' => 'photo.jpg'];
        $condicion = 'id_usuario =' . $id_usuario['id'];
      }
      $respuesta = $this->usuarioDAO->editar($carga_foto, $condicion);
    }
    echo json_encode($respuesta);
  }

  public function permisos_usuario()
  {
    header('Content-Type: application/json');
    $id_usuario = $_POST['id_usuario'];
    $permisos = $this->permisoDAO->permisos_usuario($id_usuario);
    foreach ($permisos as $value) {
      $value->nombre_estado = ESTADO_SIMPLE[$value->estado_permisos];
    }
    $respu['data'] = $permisos;
    echo json_encode($respu);
    return;
  }

  public function eliminar_permisos_usuario()
  {
    header('Content-Type: application/json');
    $id_permiso = 'id_permisos =' . $_POST['form'];
    $respuesta = $this->permisoDAO->eliminar($id_permiso);
    echo json_encode($respuesta);
    return;
  }

  public function listar_datos_permisos()
  {
    header('Content-Type: application/json');
    $form = $_POST['form'];
    $id_usuario = $_POST['id_usuario'];
    $datos['data'] = $this->Modulos_hojasDAO->Consultar_modulo_hojas($form);
    echo json_encode($datos);
    return;
  }

  public function add_permisos_usuario()
  {
    header('Content-Type: application/json');
    $form = $_POST['form'];
    $permiso_existe = $this->permisoDAO->ValidarPermiso($form['id_usuario'], $form['id_modulo_hoja']);
    if (empty($permiso_existe)) {
      $insertar = $this->permisoDAO->insertar($form);
      $respu = true;
    } else {
      $respu = false;
    }
    echo json_encode($respu);
    return;
  }
  public function valida_per_respuesta()
  {
    header('Content-Type: application/json');
    if ($_POST['valor'] == 1) {
      $permiso_existe = $this->usuarioDAO->validar_personas_responde($_POST['id_area_trabajo']);
      if ($permiso_existe[0]->cant_per_asig >= $permiso_existe[0]->cant_personas_res) {
        $respu = [
          'status' => -1,
          'msg' => 'La cantidad de personas que pueden responder, ya se encuentran asignadas',
        ];
      } else {
        $form = [
          'res_prioridad' => $_POST['valor'],
        ];
        $condicion = 'id_usuario =' . $_POST['id_usuario'];
        $respu = $this->usuarioDAO->editar($form, $condicion);
      }
    } else {
      $form = [
        'res_prioridad' => $_POST['valor'],
      ];
      $condicion = 'id_usuario =' . $_POST['id_usuario'];
      $respu = $this->usuarioDAO->editar($form, $condicion);
    }
    echo json_encode($respu);
    return;
  }
}
