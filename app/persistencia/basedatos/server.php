<?php
// include_once('config.php');
define('HOST', '192.168.2.33'); //HOST DE LA EMPRESA
define('PORT', '8081'); //PUERTO DE LA EMPRESA
// prevent the server from timing out
set_time_limit(0);
// php app/persistencia/basedatos/server.php

// include the web sockets server script (the server is started at the far bottom of this file)
// require  dirname(__DIR__) . '/negocio/util/PHPWebSocket.php';
require  'C:\xampp\htdocs\sidpa_3\app\negocio\util\PHPWebSocket.php';

// when a client sends data to the server
function wsOnMessage($clientID, $message, $messageLength, $binary)
{
    // global $Server;
    // // print_r($Server);
    // // print_r ($message);
    // $messageObj = json_decode($message);
    // if ($messageLength == 0) {
    //     $Server->wsClose($clientID);
    //     return;
    // }
    // if (
    //     property_exists($messageObj, 'name')
    //     and property_exists($messageObj, 'message')
    //     and is_callable($messageObj->name)
    // ) {
    //     $messageObj->name($messageObj->message);
    // }
    // foreach ($Server->wsClients as $id => $client);
    // $Server->wsSend($id, $message);
    global $Server;
    $ip = long2ip($Server->wsClients[$clientID][6]);

    // check if message length is 0
    if ($messageLength == 0) {
        $Server->wsClose($clientID);
        return;
    }

    //The speaker is the only person in the room. Don't let them feel lonely.
    if (sizeof($Server->wsClients) == 1)
        $Server->wsSend($clientID, $message);
    else
        //Send the message to everyone but the person who said it
        foreach ($Server->wsClients as $id => $client)
            $Server->wsSend($id, $message);
}

// when a client connects
function wsOnOpen($clientID)
{
    global $Server;
    $ip = long2ip($Server->wsClients[$clientID][6]);

    // $Server->log("$ip ($clientID) has connected.");
    $Server->log("");

    //Send a join notice to everyone but the person who joined
    foreach ($Server->wsClients as $id => $client)
        if ($id != $clientID)
            $Server->wsSend($id, "Visitor $clientID ($ip) has joined the room.");
    // $Server->wsSend($id, "");
}

// when a client closes or lost connection
function wsOnClose($clientID, $status)
{
    global $Server;
    $ip = long2ip($Server->wsClients[$clientID][6]);

    $Server->log("$ip ($clientID) has disconnected.");

    //Send a user left notice to everyone in the room
    foreach ($Server->wsClients as $id => $client)
        $Server->wsSend($id, "Visitor $clientID ($ip) has left the room.");
}

// start the server
$Server = new PHPWebSocket();
$Server->bind('message', 'wsOnMessage');
$Server->bind('open', 'wsOnOpen');
$Server->bind('close', 'wsOnClose');
// for other computers to connect, you will probably need to change this to your LAN IP or external IP,
// alternatively use: gethostbyaddr(gethostbyname($_SERVER['SERVER_NAME']))
$Server->wsStartServer(HOST, PORT);
