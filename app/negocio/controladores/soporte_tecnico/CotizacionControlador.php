<?php

namespace MiApp\negocio\controladores\soporte_tecnico;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\SoporteTecnicoDAO;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\CotizacionItemSoporteDAO;
use MiApp\persistencia\dao\SoporteItemDAO;
use MiApp\persistencia\dao\trmDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\negocio\util\PDF;
use MiApp\negocio\util\Validacion;



class CotizacionControlador extends GenericoControlador
{
    private $SoporteTecnicoDAO;
    private $ConsCotizacionDAO;
    private $CotizacionItemSoporteDAO;
    private $trmDAO;
    private $PersonaDAO;
    private $SoporteItemDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->SoporteTecnicoDAO = new SoporteTecnicoDAO($cnn);
        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
        $this->CotizacionItemSoporteDAO = new CotizacionItemSoporteDAO($cnn);
        $this->SoporteItemDAO = new SoporteItemDAO($cnn);
        $this->trmDAO = new trmDAO($cnn);
        $this->PersonaDAO = new PersonaDAO($cnn);
    }

    public function vista_cotizacion()
    {
        parent::cabecera();
        $this->view(
            'soporte_tecnico/vista_cotizacion'
        );
    }
    public function carga_cotizacion()
    {
        header('Content-Type: application/json');
        $estado = 2;
        $id_usuario = $_SESSION['usuario']->getid_usuario();
        $casos_cotiza = $this->SoporteTecnicoDAO->consultar_casos($estado, $id_usuario);
        foreach ($casos_cotiza as $value) {
            $forma_pago = FORMA_PAGO[$value->forma_pago];
            $value->nombre_pago = $forma_pago;
        }
        $arreglo = [
            'data' => $casos_cotiza,
        ];
        echo json_encode($arreglo);
    }
    public function producto_serman()
    {
        header('Content-Type: application/json');
        $producto = $this->SoporteTecnicoDAO->consulta_productos_serman();
        echo json_encode($producto);
    }
    public function personal_soporte()
    {
        header('Content-Type: application/json');
        $personal = $this->SoporteTecnicoDAO->consul_personal();
        echo json_encode($personal);
    }

    public function cotizacion_visita()
    {
        header('Content-type: application/pdf');
        $datos = $_POST;
        $form = Validacion::Decodifica($datos['form']);
        $data_completa = $datos['datos'];

        $num_cotizacion = $this->ConsCotizacionDAO->consultar_cons_especifico(16);
        $nuevo_cons = $num_cotizacion[0]->numero_guardado + 1;
        $edita_consecutivo = [
            'numero_guardado' => $nuevo_cons
        ];
        $condicion = 'id_consecutivo =16';
        $this->ConsCotizacionDAO->editar($edita_consecutivo, $condicion);
        $formulario = [
            'id_diagnostico' => $data_completa['id_diagnostico'],
            'num_cotizacion' => $num_cotizacion[0]->numero_guardado,
            'item' => 0,
            'moneda' => 1,
            'valor' => $form['valor_cotiza_visita'],
            'cantidad' => 1,
            'id_producto' => $form['codigo_producto'],
            'estado' => 8,
            'fecha_crea' => date('Y-m-d'),
            'hora_crea' => date('H:i:s'),
        ];
        $agregar_item = $this->CotizacionItemSoporteDAO->insertar($formulario);
        $formulario_diag = [
            'estado' => 3,
        ];
        $condicion_diag = 'id_diagnostico =' . $data_completa['id_diagnostico'];
        $resultado = $this->SoporteTecnicoDAO->editar($formulario_diag, $condicion_diag);

        if ($agregar_item['status'] == 1) {
            $crea_cotiza = CotizacionControlador::crear_cotizacion($num_cotizacion[0]->numero_guardado);
            $respu = $crea_cotiza;
        } else {
            $respu = [
                'status' == -1
            ];
        }
        echo json_encode($respu);
        return;
    }
    public function crear_cotizacion($num_cotizacion)
    {
        $consulta_cotizacion = $this->CotizacionItemSoporteDAO->consulta_cotiza($num_cotizacion, 2);
        $ConsultaUltimoRegistro = $this->trmDAO->ConsultaUltimoRegistro();
        $trm = $ConsultaUltimoRegistro[0]->valor_trm;
        $sentencia = 'AND t1.num_acta=0';
        foreach ($consulta_cotizacion as $value) {
            $repuestos = $this->SoporteItemDAO->consultar_repuestos_item($value->id_diagnostico, $value->item, $sentencia);
            $value->repuestos = $repuestos;
        }
        $fecha = date('Y-m-d');
        $respu = PDF::crea_cotizacion_visita($fecha, $consulta_cotizacion, $num_cotizacion, 2, $trm, '');
        return $respu;
    }

    public function aprueba_cotizacion()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $condicion = 'id_diagnostico =' . $datos['id'];
        $formulario = [
            'estado' => $datos['estado'],
        ];
        $resultado = $this->SoporteTecnicoDAO->editar($formulario, $condicion);
        if ($resultado == 'true') {
            $respu = [
                'status' => 1,
            ];
        }
        echo json_encode($respu);
        return;
    }
    public function agendar_visita()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $id_diagnostico = $datos['id_diagnostico'];
        $form = Validacion::Decodifica($datos['form']);
        $persona = $this->PersonaDAO->consultar_personas_id($form['persona_visita']);

        // SE REALIZA EL INGRESO DEL SEGUIMIENTO
        $observacion = 'VISITA AGENDADA ' . $persona[0]->nombres . ' ' . $persona[0]->apellidos . ' ' . $form['fecha_visita'];
        $seguimiento = GenericoControlador::agrega_seguimiento_diag($id_diagnostico, 0, $observacion, $_SESSION['usuario']->getid_usuario());

        // SE EDITA EL DIAGNOSTICO PARA COLOCAR LOS DATOS PUESTOS EN EL FORMULARIO Y SE CAMBIA A ESTADO 5
        $condicion = 'id_diagnostico =' . $id_diagnostico;
        $formulario = [
            'estado' => 5,
            'id_usuario_visita' => $form['persona_visita'],
            'fecha_agendamiento' => $form['fecha_visita'],
        ];
        $resultado = $this->SoporteTecnicoDAO->editar($formulario, $condicion);
        if ($resultado == 1) {
            $respu = [
                'status' => 1,
            ];
        }
        echo json_encode($respu);
        return;
    }
    public function reagendar_visita()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $condicion = 'id_diagnostico =' . $datos['id_diagnostico'];
        $formulario = [
            'estado' => 5,
            'id_usuario_visita' => $datos['persona_visita'],
            'fecha_agendamiento' => $datos['fecha_visita'],
        ];
        $resultado = $this->SoporteTecnicoDAO->editar($formulario, $condicion);
        if ($resultado == 1) {
            $respu = [
                'status' => 1,
            ];
        }
        echo json_encode($respu);
        return;
    }
    public function agendamiento()
    {
        header('Content-Type: application/json');
        $datos = $_POST['data'];
        $condicion = 'id_diagnostico =' . $datos['id_diagnostico'];
        $estado = $_POST['estado'];
        $formulario = [
            'estado' => $estado,
        ];
        if ($estado == 6) {
            $observacion = 'REAGENDAMIENTO DE VISITA';
            $seguimiento = GenericoControlador::agrega_seguimiento_diag($datos['id_diagnostico'], 0, $observacion, $_SESSION['usuario']->getid_usuario());
        } else {
            $observacion = 'VISITA EN EJECUCIÓN';
            $seguimiento = GenericoControlador::agrega_seguimiento_diag($datos['id_diagnostico'], 0, $observacion, $_SESSION['usuario']->getid_usuario());
        }

        $resultado = $this->SoporteTecnicoDAO->editar($formulario, $condicion);
        if ($resultado == 1) {
            $respu = [
                'status' => 1,
            ];
        }
        echo json_encode($respu);
        return;
    }
}
