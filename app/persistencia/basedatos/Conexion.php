<?php

namespace MiApp\persistencia\basedatos;

use PDO;

class Conexion
{

    public static function conectar()
    {
        if (MODO_PRUEBA) {
            $cnn = self::prueba();
        } else {
            $cnn = self::produccion();
        }
        return $cnn;
    }

    public static function prueba()
    {
        //CONEXION PRUEBAS
        if (PROYECTO == '/acobarras') {
            $host = 'acobarras.com.co';
            $db = 'acobarra_nueva';
            $user = 'acobarra_root';
            $pass = 'nuevouser01';
            $charset = 'utf8';
            $port = '3306';
        }
        if (PROYECTO == '/eticaribe') {
            $host = 'eticaribe.com.co';
            $db = 'eticarib_local';
            $user = 'eticarib_root';
            $pass = '@Eticaribe2022';
            $charset = 'utf8';
            $port = '3306';
        }
        if (PROYECTO == '/eticomex') {
            $host = 'eticomex.mx';
            $db = 'wwetic_local';
            $user = 'wwetic_root';
            $pass = '@Eticomex2022';
            $charset = 'utf8';
            $port = '3306';
        }
        $cnn = new PDO('mysql:port=' . $port . ';host=' . $host . ';charset=' . $charset . ';dbname=' . $db, $user, $pass);
        $cnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $cnn;
    }

    public static function produccion()
    {
        //CONEXION PRODUCCION
        if (PROYECTO == '/acobarras') {
            $host = 'localhost';
            $db = 'acobarra_sidpa';
            $user = 'acobarra_root';
            $pass = '@Pr&nc&palS&dpa2022';
            $charset = 'utf8';
            $port = '3306';
        }
        if (PROYECTO == '/eticaribe') {
            $host = 'eticaribe.com.co';
            $db = 'eticarib_local';
            $user = 'eticarib_root';
            $pass = '@Eticaribe2022';
            $charset = 'utf8';
            $port = '3306';
        }
        if (PROYECTO == '/eticaribe') {
            $host = 'localhost';
            $db = 'wwetic_sidpa';
            $user = 'wwetic_root';
            $pass = '@Eticomex2022';
            $charset = 'utf8';
            $port = '3306';
        }
        $cnn = new PDO('mysql:port=' . $port . ';host=' . $host . ';charset=' . $charset . ';dbname=' . $db, $user, $pass);
        $cnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $cnn;
    }
}
