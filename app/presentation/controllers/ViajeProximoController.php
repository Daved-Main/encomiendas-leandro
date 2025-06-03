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

    /** 
     * Vista pública: lista de próximos viajes (para el usuario genérico). 
     * En prox-viajes.php se mostrará $viajesProximos. 
     */
    public function listarViajesParaUsuario(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $viajesProximos = $this->repo->listarTodos();
        require_once __DIR__ . '/../views/proximos-viajes.php';
    }

    /** 
     * 1) Admin: Mostrar tabla (lista) de viajes próximos 
     */
    public function listarViajeProximo(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $viajes = $this->repo->listarTodos(); 
        require_once __DIR__ . '/../views/admin/listar-viaje-proximo.php';
    }

    /** 2) Admin: Mostrar formulario para crear un viaje próximo */
    public function mostrarFormularioCrear(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require_once __DIR__ . '/../views/admin/crear-viaje-proximo.php';
    }

    /** 3) Admin: Procesar POST y guardar **/
    public function guardarViajeProximo(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $lugarSalida   = trim($_POST['lugar_salida_proximo'] ?? '');
        $lugarDestino  = trim($_POST['lugar_destino_proximo'] ?? '');
        $fSalida       = trim($_POST['fecha_salida_proximo'] ?? '');
        $fEntrega      = trim($_POST['fecha_entrega_proximo'] ?? '');

        $errors = [];
        if (! $lugarSalida || ! $lugarDestino || ! $fSalida) {
            $errors[] = "Los campos Lugar de salida, Lugar de destino y Fecha de salida son obligatorios.";
        }
        // Validar formato datetime-local: ’Y-m-d\TH:i’
        if ($fSalida && DateTime::createFromFormat('Y-m-d\TH:i', $fSalida) === false) {
            $errors[] = "Formato de fecha de salida inválido.";
        }
        if ($fEntrega && DateTime::createFromFormat('Y-m-d\TH:i', $fEntrega) === false) {
            $errors[] = "Formato de fecha de entrega inválido.";
        }
        if (! empty($fSalida) && ! empty($fEntrega)) {
            $dtSalida  = new DateTime($fSalida);
            $dtEntrega = new DateTime($fEntrega);
            if ($dtEntrega < $dtSalida) {
                $errors[] = "La fecha de entrega no puede ser anterior a la fecha de salida.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = [
                'type' => 'error',
                'messages' => $errors
            ];
            header("Location: index.php?route=admin/nuevoViajeProximo");
            exit;
        }

        $viaje = new ViajeProximo(
            id: null,
            fechaRegistro: new DateTime(),                     
            fechaSalida: new DateTime($fSalida),
            fechaEntrega: $fEntrega ? new DateTime($fEntrega) : null,
            lugarSalida: $lugarSalida,
            lugarDestino: $lugarDestino
        );

        $ok = $this->repo->guardar($viaje);
        if ($ok) {
            $_SESSION['success'] = [
                'type'     => 'success',
                'messages' => ["Viaje próximo agendado correctamente."]
            ];
        } else {
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => ["Ocurrió un error al guardar el viaje próximo."]
            ];
        }
        header("Location: index.php?route=admin/listarViajeProximo");
        exit;
    }

    //Edicion de Viaje
 public function editarViajeProximo(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: index.php?route=admin/listarViajeProximo");
            exit;
        }

        $fila = $this->repo->obtenerPorId($id);
        if (!$fila) {
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => ["Viaje próximo no encontrado."]
            ];
            header("Location: index.php?route=admin/listarViajeProximo");
            exit;
        }

        // $fila es un array asociativo con: 
        //   id_viaje_proximo, fecha_salida_proximo, fecha_entrega_proximo, lugar_salida_proximo, lugar_destino_proximo
        require_once __DIR__ . '/../views/admin/editar-viaje-proximo.php';
    }

    // 6) Admin: Procesar POST de edición
    public function actualizarViajeProximo(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id            = (int)($_POST['id_viaje_proximo'] ?? 0);
        $lugarSalida   = trim($_POST['lugar_salida_proximo'] ?? '');
        $lugarDestino  = trim($_POST['lugar_destino_proximo'] ?? '');
        $fSalida       = trim($_POST['fecha_salida_proximo'] ?? '');
        $fEntrega      = trim($_POST['fecha_entrega_proximo'] ?? '');

        $errors = [];
        if ($id <= 0) {
            $errors[] = "ID inválido.";
        }
        if (! $lugarSalida || ! $lugarDestino || ! $fSalida) {
            $errors[] = "Los campos Lugar de salida, Lugar de destino y Fecha de salida son obligatorios.";
        }
        if ($fSalida && DateTime::createFromFormat('Y-m-d\TH:i', $fSalida) === false) {
            $errors[] = "Formato de fecha de salida inválido.";
        }
        if ($fEntrega && DateTime::createFromFormat('Y-m-d\TH:i', $fEntrega) === false) {
            $errors[] = "Formato de fecha de entrega inválido.";
        }
        if (! empty($fSalida) && ! empty($fEntrega)) {
            $dtSalida  = new DateTime($fSalida);
            $dtEntrega = new DateTime($fEntrega);
            if ($dtEntrega < $dtSalida) {
                $errors[] = "La fecha de entrega no puede ser anterior a la fecha de salida.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => $errors
            ];
            header("Location: index.php?route=admin/editarViajeProximo&id={$id}");
            exit;
        }

        $viaje = new ViajeProximo(
            id: $id,
            fechaRegistro: new DateTime(), // no se usa en la actualización
            fechaSalida: new DateTime($fSalida),
            fechaEntrega: $fEntrega ? new DateTime($fEntrega) : null,
            lugarSalida: $lugarSalida,
            lugarDestino: $lugarDestino
        );

        $ok = $this->repo->actualizar($viaje);
        if ($ok) {
            $_SESSION['success'] = [
                'type'     => 'success',
                'messages' => ["Viaje próximo actualizado correctamente."]
            ];
        } else {
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => ["Ocurrió un error al actualizar el viaje próximo."]
            ];
        }
        header("Location: index.php?route=admin/listarViajeProximo");
        exit;
    }

    // 7) Admin: Eliminar viaje
    public function eliminarViajeProximo(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: index.php?route=admin/listarViajeProximo");
            exit;
        }

        $ok = $this->repo->eliminar($id);
        if ($ok) {
            $_SESSION['success'] = [
                'type'     => 'success',
                'messages' => ["Viaje próximo eliminado correctamente."]
            ];
        } else {
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => ["Ocurrió un error al eliminar el viaje próximo."]
            ];
        }
        header("Location: index.php?route=admin/listarViajeProximo");
        exit;
    }

}
