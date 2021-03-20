<?php

    require 'environment.php';

    $config = [];

    if (ENVIRONMENT == 'development') {
        define('BASE_URL', 'http://localhost');
        $config = [
            'dbname' => 'devstagram',
            'host' => 'localhost',
            'dbuser' => 'root',
            'dbpass' => '',
            'jwt_secret_key' => 'andre-moura!'
        ];
    } else {
        define('BASE_URL', 'https://meusite.com.br');
        $config = [
            'dbname' => 'estrutura_mvc',
            'host' => 'localhost',
            'dbuser' => 'andre-moura',
            'dbpass' => 'andre',
            'jwt_secret_key' => 'andre-moura!'
        ];
    }
