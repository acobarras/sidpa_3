<?php

namespace MiApp\persistencia\dao;

use PDO;
use MiApp\persistencia\generico\GenericoDAO;
use MiApp\persistencia\dao\GestionPqrDAO;
use MiApp\persistencia\dao\PedidosDAO;

class ConsCotizacionDAO extends GenericoDAO
{

    private $GestionPqrDAO;
    private $PedidosDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'cons_cotizacion');
        $this->GestionPqrDAO = new GestionPqrDAO($cnn);
        $this->PedidosDAO = new PedidosDAO($cnn);
    }

    public function consultar_cons_especifico($param)
    {
        $sql = "SELECT numero_guardado, id_consecutivo FROM cons_cotizacion WHERE id_consecutivo =" . $param;
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }

    public function Consultar()
    {
        $roll = $_SESSION['usuario']->getId_roll();
        if ($roll == 1) {
            $sql = "SELECT * FROM cons_cotizacion";
        } else if ($roll == 8) {
            $sql = "SELECT * FROM cons_cotizacion WHERE id_consecutivo IN(7)";
        } else {
            $sql = "SELECT * FROM cons_cotizacion WHERE id_consecutivo IN(8,9,11,12)";
        }
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consecutivoPqr($fecha_dia)
    {
        $numero_pqr = $this->consultar_cons_especifico(14);
        $num_pqr = $numero_pqr[0]->numero_guardado;
        $fecha = new \DateTime($fecha_dia);
        $fecha->modify('last day of this month');
        $dia_semana = $fecha->format('l');
        $mes_ano = $fecha->format('F');
        $ano = $fecha->format('Y');
        $dias_resta = RESTA_DIAS_PQR[$dia_semana];
        $letra_mes = PQR_MES[$mes_ano];
        $fecha_fin_mes = $fecha->format('Y-m-d');
        $fecha_cierre = date("Y-m-d", strtotime($fecha_fin_mes . "- " . $dias_resta . " days"));
        if ($fecha_dia == $fecha_cierre) {
            $cambio_mes = new \DateTime(date("Y-m-d", strtotime($fecha_dia . "+ 1 month")));
            $mes_nuevo = $cambio_mes->format('F');
            $letra_mes = PQR_MES[$mes_nuevo];
            $valida = 'PQR-' . $letra_mes . '01-' . $ano;
            $existe = $this->GestionPqrDAO->valida_numero_pqr($valida);
            if (empty($existe)) {
                $num_pqr = 1;
            } else {
                $num_pqr = $num_pqr + 1;
            }
            $canbio_numero = $num_pqr;
        } elseif ($fecha_dia >= $fecha_cierre) {
            $cambio_mes = new \DateTime(date("Y-m-d", strtotime($fecha_dia . "+ 1 month")));
            $mes_nuevo = $cambio_mes->format('F');
            $letra_mes = PQR_MES[$mes_nuevo];
            $canbio_numero = $num_pqr + 1;
        } else {
            $canbio_numero = $num_pqr + 1;
        }
        $replazo = [1 => '01', 2 => '02', 3 => '03', 4 => '04', 5 => '05', 6 => '06', 7 => '07', 8 => '08', 9 => '09'];
        if ($num_pqr == 1 || $num_pqr == 2 || $num_pqr == 3 || $num_pqr == 4 || $num_pqr == 5 || $num_pqr == 6 || $num_pqr == 7 || $num_pqr == 8 || $num_pqr == 9) {
            $num_pqr = $replazo[$num_pqr];
        }
        $respu = 'PQR-' . $letra_mes . $num_pqr . '-' . $ano;
        $creado = $this->GestionPqrDAO->valida_numero_pqr($respu);
        $retorno = '';
        if (empty($creado)) {
            $retorno = $respu;
            $edita_numero = ['numero_guardado' => $canbio_numero];
            $condicion = 'id_consecutivo = 14';
            $this->editar($edita_numero, $condicion);
        }
        return $retorno;
    }

    public function consecutivoPedido($id_consecutivo)
    {
        $num_pedido = $this->consultar_cons_especifico($id_consecutivo);
        $valida_numero = $this->PedidosDAO->consultar_descarga_pedido($num_pedido[0]->numero_guardado);
        $numero_pedido = 0;
        if (empty($valida_numero)) {
            $numero_pedido = $num_pedido[0]->numero_guardado;
            $canbio_numero = $num_pedido[0]->numero_guardado + 1;
            $edita_numero = ['numero_guardado' => $canbio_numero];
            $condicion = 'id_consecutivo = ' . $num_pedido[0]->id_consecutivo;
            $this->editar($edita_numero, $condicion);
        }
        return $numero_pedido;
    }
}
