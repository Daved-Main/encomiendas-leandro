<?php

namespace app\presentation\controllers;

use app\domain\usecases\RegistrarPaquete;
use app\domain\usecases\ListarPaquetes;
use app\infrastructure\database\DatabaseConnect;
use app\infrastructure\database\PaqueteRepositoryPgsql;
use app\infrastructure\database\ViajeProximoRepositoryPg;

class PaqueteController
{
    // ✅ 1. Mostrar formulario con ID del último viaje
public function mostrarFormulario()
{
    $pdo = DatabaseConnect::getInstance();
    $viajeRepo = new ViajeProximoRepositoryPg($pdo);

    $viaje = $viajeRepo->obtenerUltimoViajeActual();

    if (!$viaje) {
        die("❌ No se encontró ningún viaje registrado.");
    }

    $idViajeActual = $viaje['id_viaje_actual'];
    $idViajeMes = $viaje['id_viaje_mes'];
    $fechaSalida = new \DateTime($viaje['fecha_salida_actual']);
    $mes = (int)$fechaSalida->format('m');
    $anio = (int)$fechaSalida->format('Y');

    // ✅ Extraer las variables para que la vista pueda usarlas
    extract([
        'idViajeActual' => $idViajeActual,
        'idViajeMes' => $idViajeMes,
        'mes' => $mes,
        'anio' => $anio,
    ]);

    require_once __DIR__ . '/../views/agendaPaquete.php';
}




    // ✅ 2. Registrar un nuevo paquete
    public function registrar()
    {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $errors = [];

    $_POST['contenido_fragil'] = isset($_POST['contenido_fragil']) ? 'Sí' : 'No';

    // Validar campos obligatorios
    $campos = [
        'id_viaje_mes', 'mes', 'anio', // ← AÑADIDOS
        'tipo_paquete', 'nombre_remitente', 'telefono_remitente',
        'nombre_destinatario', 'telefono_destinatario', 'ciudad_destino',
        'direccion_destino', 'nombre_del_articulo', 'cantidad_bultos',
        'peso', 'alto', 'ancho', 'contenido_fragil'
    ];

    foreach ($campos as $campo) {
        if (empty(trim($_POST[$campo] ?? ''))) {
            $errors[] = "El campo '$campo' es obligatorio.";
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = [
            'type' => 'error',
            'messages' => $errors
        ];
        header("Location: index.php?route=enviarPaquete");
        exit;
    }

    try {
        $pdo = DatabaseConnect::getInstance();
        $repo = new PaqueteRepositoryPgsql($pdo);
        $useCase = new RegistrarPaquete($repo);

        $useCase->setAttributes($_POST)->execute();

        $_SESSION['errors'] = [
            'type' => 'success',
            'messages' => ['Paquete registrado correctamente ✅']
        ];

        header("Location: index.php?route=home");
        exit;
    } catch (\Throwable $e) {
        $_SESSION['errors'] = [
            'type' => 'error',
            'messages' => [
                'Error inesperado al guardar el paquete.',
                $e->getMessage()
            ]
        ];
        error_log($e->getMessage());
        header("Location: index.php?route=enviarPaquete");
        exit;
    }
}

    // ✅ 3. Generador simple de código de rastreo
    private function generarCodigoRastreo(array $data): string
    {
        $viaje = str_pad((string)$data['id_viaje_actual'], 2, '0', STR_PAD_LEFT);
        $mes = date('m');
        $random = rand(10, 99);
        return $viaje . $mes . $random;
    }

    // ✅ 4. Listar paquetes
    public function listar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            $pdo = DatabaseConnect::getInstance();
            $repo = new PaqueteRepositoryPgsql($pdo);
            $useCase = new ListarPaquetes($repo);

            $useCase->execute();
            $paquetes = $useCase->getResponse()->getData();

            require_once __DIR__ . '/../views/paquetes-recibidos.php';
        } catch (\Throwable $e) {
            echo "<h3>Error al listar paquetes</h3>";
            echo "<pre>{$e->getMessage()}</pre>";
        }
    }

    // ✅ 5. Actualizar estado individual
    public function actualizarEstado()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_paquete'] ?? null;
            $estado = $_POST['estado'] ?? null;

            if ($id && $estado) {
                $pdo = DatabaseConnect::getInstance();
                $repo = new PaqueteRepositoryPgsql($pdo);

                $resultado = $repo->actualizarEstado((int)$id, $estado);

                $_SESSION['errors'] = [
                    'type' => $resultado ? 'success' : 'error',
                    'messages' => [$resultado ? 'Estado actualizado correctamente ✅' : 'No se pudo actualizar el estado ❌']
                ];
            } else {
                $_SESSION['errors'] = [
                    'type' => 'error',
                    'messages' => ['Faltan datos para actualizar el estado ❌']
                ];
            }
        }

        header("Location: index.php?route=listar_paquetes");
        exit;
    }

    // ✅ 6. Actualizar estado masivo
    public function actualizarEstadoMasivo()
    {
        $repo = new PaqueteRepositoryPgsql(DatabaseConnect::getInstance());
        $repo->actualizarTodosEstado($_POST['nuevo_estado']);
        header("Location: index.php?route=paquetes-recibidos");
        exit;
    }
}
