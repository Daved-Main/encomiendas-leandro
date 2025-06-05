<?php
namespace app\presentation\controllers;

use app\domain\entities\ViajeProximo;
use app\domain\repositories\ViajeProximoRepository;
use DateTime;

class ViajeProximoController
{
    private ViajeProximoRepository $repo;

    public function __construct(ViajeProximoRepository $repo)
    {
        $this->repo = $repo;
    }

    public function listarViajesParaUsuario(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $viajesProximos = $this->repo->listarTodos();
        require_once __DIR__ . '/../views/proximos-viajes.php';
    }

    public function listarViajeProximo(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $viajes = $this->repo->listarTodos();
        require_once __DIR__ . '/../views/admin/listar-viaje-proximo.php';
    }

    public function mostrarFormularioCrear(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require_once __DIR__ . '/../views/admin/crear-viaje-proximo.php';
    }

    public function guardarViajeProximo(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $lugarSalida   = trim($_POST['lugar_salida_proximo'] ?? '');
        $lugarDestino  = trim($_POST['lugar_destino_proximo'] ?? '');
        $fSalida       = trim($_POST['fecha_salida_proximo'] ?? '');
        $fEntrega      = trim($_POST['fecha_entrega_proximo'] ?? '');
        $capacidad     = (int)($_POST['capacidad_paquetes'] ?? 0);
        $idViajeMes    = (int)($_POST['id_viaje_mes'] ?? 0);

        $errors = [];

        if (!$lugarSalida || !$lugarDestino || !$fSalida || $capacidad <= 0 || $idViajeMes <= 0) {
            $errors[] = "Todos los campos son obligatorios y deben ser válidos.";
        }

        if ($fSalida && DateTime::createFromFormat('Y-m-d\TH:i', $fSalida) === false) {
            $errors[] = "Formato de fecha de salida inválido.";
        }

        if ($fEntrega && DateTime::createFromFormat('Y-m-d\TH:i', $fEntrega) === false) {
            $errors[] = "Formato de fecha de entrega inválido.";
        }

        if (!empty($fSalida) && !empty($fEntrega)) {
            $dtSalida  = new DateTime($fSalida);
            $dtEntrega = new DateTime($fEntrega);
            if ($dtEntrega < $dtSalida) {
                $errors[] = "La fecha de entrega no puede ser anterior a la de salida.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = ['type' => 'error', 'messages' => $errors];
            header("Location: index.php?route=admin/nuevoViajeProximo");
            exit;
        }

        $viaje = new ViajeProximo(
            id: null,
            fechaRecogida: new DateTime(),
            fechaEntrega: $fEntrega ? new DateTime($fEntrega) : null,
            lugarDestino: $lugarDestino,
            capacidadPaquetes: $capacidad,
            fechaSalida: new DateTime($fSalida),
            lugarSalida: $lugarSalida,
            idViajeMes: $idViajeMes
        );

        $ok = $this->repo->guardar($viaje);

        $_SESSION[$ok ? 'success' : 'errors'] = [
            'type' => $ok ? 'success' : 'error',
            'messages' => [$ok ? "Viaje agendado correctamente." : "Ocurrió un error al guardar."]
        ];
        header("Location: index.php?route=admin/listarViajeProximo");
        exit;
    }

    public function editarViajeProximo(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: index.php?route=admin/listarViajeProximo");
            exit;
        }

        $fila = $this->repo->obtenerPorId($id);
        if (!$fila) {
            $_SESSION['errors'] = ['type' => 'error', 'messages' => ["Viaje no encontrado."]];
            header("Location: index.php?route=admin/listarViajeProximo");
            exit;
        }

        require_once __DIR__ . '/../views/admin/editar-viaje-proximo.php';
    }

    public function actualizarViajeProximo(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $id            = (int)($_POST['id_viaje_proximo'] ?? 0);
        $lugarSalida   = trim($_POST['lugar_salida_proximo'] ?? '');
        $lugarDestino  = trim($_POST['lugar_destino_proximo'] ?? '');
        $fSalida       = trim($_POST['fecha_salida_proximo'] ?? '');
        $fEntrega      = trim($_POST['fecha_entrega_proximo'] ?? '');
        $capacidad     = (int)($_POST['capacidad_paquetes'] ?? 0);
        $idViajeMes    = (int)($_POST['id_viaje_mes'] ?? 0);

        $errors = [];

        if ($id <= 0 || !$lugarSalida || !$lugarDestino || !$fSalida || $capacidad <= 0 || $idViajeMes <= 0) {
            $errors[] = "Todos los campos son obligatorios y deben ser válidos.";
        }

        if ($fSalida && DateTime::createFromFormat('Y-m-d\TH:i', $fSalida) === false) {
            $errors[] = "Formato de fecha de salida inválido.";
        }

        if ($fEntrega && DateTime::createFromFormat('Y-m-d\TH:i', $fEntrega) === false) {
            $errors[] = "Formato de fecha de entrega inválido.";
        }

        if (!empty($fSalida) && !empty($fEntrega)) {
            $dtSalida  = new DateTime($fSalida);
            $dtEntrega = new DateTime($fEntrega);
            if ($dtEntrega < $dtSalida) {
                $errors[] = "La fecha de entrega no puede ser anterior a la de salida.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = ['type' => 'error', 'messages' => $errors];
            header("Location: index.php?route=admin/editarViajeProximo&id={$id}");
            exit;
        }

        $viaje = new ViajeProximo(
            id: $id,
            fechaRecogida: new DateTime(), // No se usa, se ignora
            fechaEntrega: $fEntrega ? new DateTime($fEntrega) : null,
            lugarDestino: $lugarDestino,
            capacidadPaquetes: $capacidad,
            fechaSalida: new DateTime($fSalida),
            lugarSalida: $lugarSalida,
            idViajeMes: $idViajeMes
        );

        $ok = $this->repo->actualizar($viaje);

        $_SESSION[$ok ? 'success' : 'errors'] = [
            'type' => $ok ? 'success' : 'error',
            'messages' => [$ok ? "Viaje actualizado correctamente." : "Ocurrió un error al actualizar."]
        ];
        header("Location: index.php?route=admin/listarViajeProximo");
        exit;
    }

    public function eliminarViajeProximo(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: index.php?route=admin/listarViajeProximo");
            exit;
        }

        $ok = $this->repo->eliminar($id);

        $_SESSION[$ok ? 'success' : 'errors'] = [
            'type' => $ok ? 'success' : 'error',
            'messages' => [$ok ? "Viaje eliminado correctamente." : "Error al eliminar el viaje."]
        ];
        header("Location: index.php?route=admin/listarViajeProximo");
        exit;
    }
}
