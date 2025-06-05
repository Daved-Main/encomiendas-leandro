<?php

namespace app\presentation\controllers;

use app\domain\usecases\RegistrarPaquete;
use app\domain\usecases\ListarPaquetes;
use app\infrastructure\database\DatabaseConnect;
use app\infrastructure\database\PaqueteRepositoryPgsql;
use app\infrastructure\database\ViajeProximoRepositoryPg;
use DateTime;
use IntlDateFormatter;
use PDO;
use TCPDF;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;


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

        $stmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM viajeactual 
            WHERE id_viaje_mes = :id_viaje_mes
              AND EXTRACT(MONTH FROM fecha_salida_actual) = :mes
              AND EXTRACT(YEAR FROM fecha_salida_actual) = :anio
        ");

        $stmt->execute([
            ':id_viaje_mes' => $idViajeMes,
            ':mes' => $mes,
            ':anio' => $anio
        ]);

        $numeroViaje = $stmt->fetchColumn();

        $fechaSalida = new DateTime();
        $fmt = new IntlDateFormatter(
            'es_ES',
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            'America/El_Salvador',
            IntlDateFormatter::GREGORIAN,
            'LLLL'
        );

        $nombreMes = $fmt->format($fechaSalida);

        extract([
            'idViajeActual' => $idViajeActual,
            'idViajeMes' => $idViajeMes,
            'mes' => $mes,
            'anio' => $anio,
            'numeroViaje' => $numeroViaje,
            'nombreMes' => $nombreMes,
        ]);

        require_once __DIR__ . '/../views/agendaPaquete.php';
    }

    // ✅ 2. Registrar un nuevo paquete
public function registrar()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user']['id_user'])) {
        header("Location: index.php?route=login");
        exit;
    }

    $_POST['id_user'] = $_SESSION['user']['id_user'];
    $_POST['contenido_fragil'] = isset($_POST['contenido_fragil']) ? 'Sí' : 'No';

    $errors = [];

    $campos = [
        'id_viaje_mes', 'mes', 'anio',
        'tipo_paquete', 'nombre_remitente', 'telefono_remitente',
        'nombre_destinatario', 'telefono_destinatario', 'ciudad_destino',
        'direccion_destino', 'nombre_del_articulo', 'cantidad_bultos',
        'peso', 'alto', 'ancho', 'contenido_fragil', 'id_user'
    ];

    foreach ($campos as $campo) {
        if (empty(trim($_POST[$campo] ?? ''))) {
            $errors[] = "El campo '$campo' es obligatorio.";
        }
    }

    // 💥 Verificar duplicado
    $pdo = DatabaseConnect::getInstance();
    $checkStmt = $pdo->prepare("
        SELECT COUNT(*) FROM paquete
        WHERE nombre_remitente = :remitente
        AND nombre_destinatario = :destinatario
        AND nombre_del_articulo = :articulo
        AND ciudad_destino = :ciudad
        AND fecha_registro::date = CURRENT_DATE
    ");

    $checkStmt->execute([
        ':remitente' => $_POST['nombre_remitente'],
        ':destinatario' => $_POST['nombre_destinatario'],
        ':articulo' => $_POST['nombre_del_articulo'],
        ':ciudad' => $_POST['ciudad_destino'],
    ]);

    if ($checkStmt->fetchColumn() > 0) {
        $errors[] = "Ya existe un paquete similar registrado hoy.";
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


    private function generarCodigoRastreo(array $data): string
    {
        $viaje = str_pad((string)$data['id_viaje_actual'], 2, '0', STR_PAD_LEFT);
        $mes = date('m');
        $random = rand(10, 99);
        return $viaje . $mes . $random;
    }

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

        $fecha = $_GET['fecha'] ?? null;

        if ($fecha) {
            $stmt = $pdo->prepare("SELECT * FROM paquete WHERE fecha_registro::date = :fecha ORDER BY fecha_registro DESC");
            $stmt->execute([':fecha' => $fecha]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $paquetes = array_map(fn($r) => $repo->hidratarPaquete($r), $rows);
        } else {
            $useCase = new ListarPaquetes($repo);
            $useCase->execute();
            $paquetes = $useCase->getResponse()->getData();
        }

            require_once __DIR__ . '/../views/paquetes-recibidos.php';
        } catch (\Throwable $e) {
            echo "<h3>Error al listar paquetes</h3>";
            echo "<pre>{$e->getMessage()}</pre>";
        }
    }

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

    public function actualizarEstadoMasivo()
    {
        $repo = new PaqueteRepositoryPgsql(DatabaseConnect::getInstance());
        $repo->actualizarTodosEstado($_POST['nuevo_estado']);
        header("Location: index.php?route=paquetes-recibidos");
        exit;
    }

    public function historialUsuario()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $idUser = $_SESSION['user']['id_user'] ?? null;

        if (!$idUser) {
            header('Location: index.php?route=login');
            exit;
        }

        $pdo = DatabaseConnect::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM paquete WHERE id_user = :id ORDER BY fecha_registro DESC");
        $stmt->execute([':id' => $idUser]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $paquetes = array_map(fn($r) => (new PaqueteRepositoryPgsql($pdo))->hidratarPaquete($r), $rows);

        require_once __DIR__ . '/../views/historialPaquetes.php';
    }

    public function generarVineta()
{
    $codigo = $_POST['codigo_rastreo'] ?? null;

    if (!$codigo) {
        die("Código de rastreo no proporcionado.");
    }

    $repo = new PaqueteRepositoryPgsql(DatabaseConnect::getInstance());
    $paquete = $repo->obtenerPorCodigoRastreo($codigo);

    if (!$paquete) {
        die("Paquete no encontrado.");
    }

    // Generar QR
    $qr = new QrCode($codigo);
    $writer = new PngWriter();
    $qrPath = sys_get_temp_dir() . "/{$codigo}.png";
    $writer->write($qr)->saveToFile($qrPath);

    // Crear PDF con TCPDF
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Encomiendas Leandro');
    $pdf->SetTitle("Etiqueta - $codigo");
    $pdf->SetMargins(15, 20, 15);
    $pdf->AddPage();

    // Título
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, "Etiqueta de Envío", 0, 1, 'C');
    $pdf->Ln(5);

    // Datos del paquete
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 8, "Código de Rastreo: $codigo", 0, 1);
    $pdf->Cell(0, 8, "Remitente: " . $paquete->getNombreRemitente(), 0, 1);
    $pdf->Cell(0, 8, "Destinatario: " . $paquete->getNombreDestinatario(), 0, 1);
    $pdf->Cell(0, 8, "Artículo: " . $paquete->getNombreDelArticulo(), 0, 1);
    $pdf->Cell(0, 8, "Ciudad destino: " . $paquete->getCiudadDestino(), 0, 1);
    $pdf->Ln(5);

    // Insertar QR
    $pdf->Image($qrPath, $pdf->GetX(), $pdf->GetY(), 40, 40);
    unlink($qrPath); // Limpieza del archivo temporal

    $pdf->Output("vineta_{$codigo}.pdf", 'I');
}

}