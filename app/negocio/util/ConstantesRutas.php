<?php
// PROYECTO
define('RUTA_PRINCIPAL', '/sidpa_3');
define('MODO_PRUEBA', true);
define('CARPETA_APP', dirname(__DIR__));
define('CARPETA_VIEW', dirname(dirname(dirname(__DIR__))));
define('FOLDER_APP', dirname(dirname(__DIR__)));
define('CARPETA_LIBRERIAS', 'public/librerias');
define('CARPETA_IMG', 'public/img');
define('CARPETA_CSS', 'public/css');
define('PUBLICO', './public');
define('ANO', '2023'); //AÑO 

// ACOBARRAS
define('NOMBRE_EMPRESA', 'ACOBARRAS S.A.S.'); //COLOCAR EL NOMBRE EN MAYUSCULA
define('PROYECTO', '/acobarras'); //NOMBRE DEL PROYECTO
define('IMG_CORREO', 'https://www.acobarras.com/sidpa/public/img/principal/sidpa.gif'); //URL PARA OBTENER LAS IMAGENES PARA EL ENVIO DE CORREOS
define('FIRMA_PEDIDO', 'firma_pedido'); //CUANDO SE CREE LA FIRMA GUARDARLA CON ESTE NOMBRE DE ARCHIVO
define('CORREO_SOPORTE_TEC', 'desarrollo@acobarras.com'); //CORREO DE SOPORTE TECNICO
define('JEFE_SER_CLIENTE', 'PABLO ANDRES SUAREZ'); //NOMBRE DEL JEFE DE SERVICIO AL CLIENTE
define('FIRMA_JEFE_SER', 'firma_pablo_andres.png'); // FIRMA PARA ACTAS DE RESPUESTAS DEL JEFE DE SERVICIO AL CLIENTE
define('JEFE_SOPORTE', 'Miguel Angel Aya Zarate'); // NOMBRE PARA ACTAS DEL JEFE DE SOPORTE TECNICO
define('CORREO_PRODUCCION_GRAF', 'producciongrafica@acobarras.com'); //CORREO DE PRODUCCION GRAFICA
define('CORREO_SERV_CLIENTE', 'servicioalcliente@acobarras.com'); //CORREO DE SERVICIO AL CLIENTE PARA COPIAS DE PQR
define('CORREO_ENTREGA', 'soporte_entrega@acobarras.com'); //CORREO DE ENTREGA 
define('CORREO_COMPRAS_MA', 'paola.castaneda@acobarras.com'); //CORREO DE COMPRAS DE MATERIAL
define('CORREO_COMPRAS_TEC', 'marcela.rodriguez@acobarras.com'); // CORREO DE COMPRAS DE TECNOLOGIA
define('CLAVE_CORREOS', '@acobarras123'); //CLAVE DE ENVIO DE CORREOS 
define('HOST_CORREOS', 'smtp.gmail.com'); //HOST PARA EL ENVIO DE CORREOS
define('PLANTILLA_CONTABILIDAD', 'ACOBARRAS SAS'); //PLANTILLA DE CONTALIDAD
define('TEL_EMPRESA', '3847979'); //TELEFONO DE LA EMPRESA


// ETICARIBE
// define('NOMBRE_EMPRESA', 'ETICARIBE'); //COLOCAR EL NOMBRE EN MAYUSCULA
// define('PROYECTO', '/eticaribe'); //NOMBRE DEL PROYECTO
// define('IMG_CORREO', 'https://www.eticaribe.com.co/public/img/login/sidpa.gif'); //URL PARA OBTENER LAS IMAGENES PARA EL ENVIO DE CORREOS
// define('FIRMA_PEDIDO', 'firma_pedido'); //CUANDO SE CREE LA FIRMA GUARDARLA CON ESTE NOMBRE DE ARCHIVO
// define('CORREO_SOPORTE_TEC', 'desarrollo@acobarras.com'); //CORREO SOPORTE TECNICO
// define('JEFE_SER_CLIENTE', 'PABLO ANDRES SUAREZ'); //NOMBRE DEL JEFE DE SERVICIO AL CLIENTE
// define('FIRMA_JEFE_SER', 'firma_pablo_andres.png'); // FIRMA PARA ACTAS DE RESPUESTAS DEL JEFE DE SERVICIO AL CLIENTE
// define('JEFE_SOPORTE', 'Miguel Angel Aya Zarate'); // NOMBRE PARA ACTAS DEL JEFE DE SOPORTE TECNICO
// define('CORREO_PRODUCCION_GRAF', 'info@eticaribe.com.co'); //CORREO DE PRODUCCION GRAFICA
// define('CORREO_SERV_CLIENTE', 'servicioalcliente@eticaribe.com.co'); //CORREO DE SERVICIO AL CLIENTE PARA COPIAS DE PQR
// define('CORREO_ENTREGA', 'notificacion@eticaribe.com.co'); //CORREO DE ENTREGA 
// define('CORREO_COMPRAS_MA', 'paola.castaneda@acobarras.com'); //CORREO DE COMPRAS DE MATERIAL
// define('CORREO_COMPRAS_TEC', 'marcela.rodriguez@acobarras.com'); // CORREO DE COMPRAS DE TECNOLOGIA
// define('CLAVE_CORREOS', '@Notificacion2022'); //CLAVE DE ENVIO DE CORREOS
// define('HOST_CORREOS', 'smtp.titan.email'); //HOST PARA EL ENVIO DE CORREOS
// define('PLANTILLA_CONTABILIDAD', 'ETICARIBE'); //PLANTILLA DE CONTALIDAD
// define('TEL_EMPRESA', '3847979'); //TELEFONO DE LA EMPRESA


// ETICOMEX
// define('NOMBRE_EMPRESA', 'ETICOMEX'); //COLOCAR EL NOMBRE EN MAYUSCULA
// define('PROYECTO', '/eticomex'); //NOMBRE DEL PROYECTO
// define('IMG_CORREO', 'https://www.eticomex.mx/public/img/login/sidpa.gif'); //URL PARA OBTENER LAS IMAGENES PARA EL ENVIO DE CORREOS
// define('FIRMA_PEDIDO', 'firma_pedido'); //CUANDO SE CREE LA FIRMA GUARDARLA CON ESTE NOMBRE DE ARCHIVO
// define('CORREO_SOPORTE_TEC', 'desarrollo@acobarras.com'); //CORREO SOPORTE TECNICO
// define('JEFE_SER_CLIENTE', 'PABLO ANDRES SUAREZ'); //NOMBRE DEL JEFE DE SERVICIO AL CLIENTE
// define('FIRMA_JEFE_SER', 'firma_pablo_andres.png'); // FIRMA PARA ACTAS DE RESPUESTAS DEL JEFE DE SERVICIO AL CLIENTE
// define('JEFE_SOPORTE', 'Miguel Angel Aya Zarate'); // NOMBRE PARA ACTAS DEL JEFE DE SOPORTE TECNICO
// define('CORREO_PRODUCCION_GRAF', 'info@eticaribe.com.co'); //CORREO DE PRODUCCION GRAFICA
// define('CORREO_SERV_CLIENTE', 'servicioalcliente@eticaribe.com.co'); //CORREO DE SERVICIO AL CLIENTE PARA COPIAS DE PQR
// define('CORREO_ENTREGA', 'notificacion@eticaribe.com.co'); //CORREO DE ENTREGA 
// define('CORREO_COMPRAS_MA', 'paola.castaneda@acobarras.com'); //CORREO DE COMPRAS DE MATERIAL
// define('CORREO_COMPRAS_TEC', 'marcela.rodriguez@acobarras.com'); // CORREO DE COMPRAS DE TECNOLOGIA
// define('CLAVE_CORREOS', '@Notificacion2022'); //CLAVE DE ENVIO DE CORREOS
// define('HOST_CORREOS', 'smtp.titan.email'); //HOST PARA EL ENVIO DE CORREOS
// define('PLANTILLA_CONTABILIDAD', 'ETICOMEX'); //PLANTILLA DE CONTALIDAD
// define('TEL_EMPRESA', '3847979'); //TELEFONO DE LA EMPRESA