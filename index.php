<?php
require __DIR__ . '/vendor/autoload.php';

// Si estás en entorno local, intenta cargar el archivo .env
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
}

// Verifica que las variables del entorno existan (Render las inyecta automáticamente)
if (!getenv('DB_HOST')) {
    die('Falta una variable de entorno crítica');
}


// 4) Luego, carga tus rutas/controladores
require __DIR__ . '/config/route.php';
