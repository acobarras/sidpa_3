<?php

namespace MiApp\negocio\controladores\chatsidpa;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\UsuarioDAO;
use MiApp\persistencia\dao\MensajesChatDAO;

class ChatSidpaControlador extends GenericoControlador
{
    private $UsuarioDAO;
    private $MensajesChatDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->UsuarioDAO = new UsuarioDAO($cnn);
        $this->MensajesChatDAO = new MensajesChatDAO($cnn);
    }

    function vista_chat()
    {
        $this->view(
            'inicio/chat_sidpa',
            [
                'usu_chat' => $this->UsuarioDAO->usuarios_activos()
            ]
        );
    }

    public function mensajes_chat()
    {
        $mensaje = [
            'id_usuario' => $_SESSION['usuario']->getId_usuario(),
            'id_contacto' => $_POST['contacto'],
            'mensaje' => $_POST['message'],
            'estado' => 1,
            'fecha_crea' => date('Y-m-d H:i:s')
        ];
        $this->MensajesChatDAO->insertar($mensaje);
        // $msg_pend = $this->MensajesChatDAO->cant_mensajes($_SESSION['usuario']->getId_usuario(),$_POST['contacto']);
        $msg_pend = $this->MensajesChatDAO->cant_mensajes($_POST['contacto'],$_SESSION['usuario']->getId_usuario());
        $mensaje['cant_pend'] = $msg_pend[0]->cant_pend;
        $mensaje['fecha_crea'] = date_format(date_create($mensaje['fecha_crea']), 'd M Y h:i a');
        $mensaje['name'] = $_POST['name'];
        echo json_encode($mensaje);
        return;
    }
    
    function historico_chat() {
        header('Content-Type: application/json'); //convierte a json
        $persona_conversa = $_POST['persona_conversa'];
        $id_usuario = $_SESSION['usuario']->getId_usuario();
        $edita_mensaje = ['estado' => 2];
        $condicion = 'id_contacto ='.$_SESSION['usuario']->getId_usuario().' AND id_usuario ='.$persona_conversa.' AND estado = 1';
        $this->MensajesChatDAO->editar($edita_mensaje,$condicion);
        $data = $this->MensajesChatDAO->consulta_mensajes($id_usuario,$persona_conversa);
        foreach ($data as $value) {
            $value->fecha_crea = date_format(date_create($value->fecha_crea), 'd M Y h:i a');
        }
        echo json_encode($data);
        return;
    }

    function mensaje_pendiente() {
        header('Content-Type: application/json'); //convierte a json
        $data = $this->UsuarioDAO->usuarios_activos();
        echo json_encode($data);
        return;
    }
}
