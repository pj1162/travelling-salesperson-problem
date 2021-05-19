<?php 
    session_start();

    // globala filvägar
    define ('ROOT_PATH', realpath(dirname(__FILE__)));
    if (strcmp($_SERVER['SERVER_NAME'], "localhost") == 0) {
        define('BASE_URL', 'http://localhost:8888/mat05/travelling-salesperson-problem/');
    } else {
        define('BASE_URL', '');     // Länken till startsidan ska finnas här
    }
?>