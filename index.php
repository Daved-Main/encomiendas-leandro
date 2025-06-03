<?php
require __DIR__ . '/vendor/autoload.php';

// 2) Carga las variables de .env
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// 4) Luego, carga tus rutas/controladores
require __DIR__ . '/config/route.php';
