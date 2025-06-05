<?php
// app/infrastructure/Database.php

namespace app\infrastructure\database;

use PDO;
use PDOException;
use Dotenv\Dotenv;

class DatabaseConnect
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            // 🔐 Si estás en local y el archivo .env existe, cargalo
            $envPath = __DIR__ . '/../../../';
            if (file_exists($envPath . '.env')) {
                $dotenv = Dotenv::createImmutable($envPath);
                $dotenv->safeLoad();
            }

            // 🔍 Obtené las variables desde el entorno (Render las inyecta así)
            $host     = getenv('DB_HOST');
            $port     = getenv('DB_PORT') ?: '5432'; // por si no hay puerto seteado
            $database = getenv('DB_DATABASE');
            $username = getenv('DB_USERNAME');
            $password = getenv('DB_PASSWORD');

            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                $host,
                $port,
                $database
            );

            $opts = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, $username, $password, $opts);
            } catch (PDOException $e) {
                die("❌ Error de conexión: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
