<?php
namespace app\presentation\controllers;

use app\services\ServicioUsuario;
use app\infrastructure\mail\EmailJsService;
use app\infrastructure\database\DuplicateUserException;
use DateTime;
use PDO;
class UsuarioController
{
    private ServicioUsuario $servicio;
    private EmailJsService $mailer;

    public function __construct(ServicioUsuario $servicio, EmailJsService $mailer)
    {
        $this->servicio = $servicio;
        $this->mailer = $mailer;
    }

    public function registrar(): void
    {

        $this->servicio->limpiarExpirados();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../views/registrar.php';
            return;
        }

        $errors   = [];
        $nombre   = trim($_POST['name']     ?? '');
        $correo   = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!$nombre || !$correo || !$password) {
            $errors[] = "Todos los campos son obligatorios";
        }
        if ($correo && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Correo electrónico no válido";
        }
        if (!empty($errors)) {
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => $errors
            ];
            header("Location: index.php?route=registrar");
            exit;
        }

        try {
            if ($this->servicio->existeCorreo($correo)) {
                throw new DuplicateUserException("El correo ya está registrado");
            }

            if ($this->servicio->correoPendienteExiste($correo)) {
                // Obtené el ID pendiente
                $pendiente = $this->servicio->obtenerPendientePorCorreo($correo);
                if ($pendiente) {
                    $_SESSION['pending_user_id'] = $pendiente['id_pending'];
                    $_SESSION['errors'] = [
                        'type'     => 'info',
                        'messages' => ['Ya se envió un código a este correo. Revisa tu bandeja o vuelve a ingresarlo.']
                    ];
                    header("Location: index.php?route=verificarCodigo");
                    exit;
                }
            }
            
            $idPending = $this->servicio->crearPendiente($nombre, $correo, $password);
            $pendiente = $this->servicio->obtenerPendiente($idPending);
            if (!$pendiente) {
                throw new \Exception("Error interno al generar código de verificación.");
            }

            $otp = $pendiente['otp_code'];
            $this->mailer->sendVerifyEmail(
                $correo,
                $nombre,
                $otp,
                new DateTime($pendiente['otp_expires_at'])
            );

            $_SESSION['pending_user_id'] = $idPending;
            header("Location: index.php?route=verificarCodigo");
            exit;

        } catch (DuplicateUserException $e) {
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => [$e->getMessage()]
            ];
            header("Location: index.php?route=registrar");
            exit;

        } catch (\Throwable $e) {
            unset($_SESSION['pending_user_id']);
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => ['Error inesperado. Intenta más tarde.' . $e->getMessage() ]
            ];
            header("Location: index.php?route=registrar");
            exit;
        }
    }

    public function verificarCodigo(): void {

        $this->servicio->limpiarExpirados();


        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $idPending = $_SESSION['pending_user_id'] ?? null;
        if (!$idPending) {
            header('Location: index.php?route=registrar');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../views/verificar-codigo.php';
            return;
        }

        $codigoIngresado = trim($_POST['code'] ?? '');
        if ($codigoIngresado === '') {
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => ['Debes ingresar el código.']
            ];
            header('Location: index.php?route=verificarCodigo');
            exit;
        }

        try {
            $pendiente = $this->servicio->obtenerPendiente($idPending);
            if (!$pendiente) {
                throw new \Exception("No se encontró el registro de verificación.");
            }

            $ahora  = new DateTime();
            $caduca = new DateTime($pendiente['otp_expires_at']);

            if ($ahora > $caduca) {
                // Eliminar el código viejo
                $this->servicio->eliminarPendiente($idPending);
            
                // Regenerar uno nuevo
                $nuevoId = $this->servicio->crearPendiente(
                    $pendiente['nombre'],
                    $pendiente['correo'],
                    $pendiente['password_hash'] // ya está hasheado
                );
            
                $nuevo = $this->servicio->obtenerPendiente($nuevoId);
                $this->mailer->sendVerifyEmail(
                    $nuevo['correo'],
                    $nuevo['nombre'],
                    $nuevo['otp_code'],
                    new \DateTime($nuevo['otp_expires_at'])
                );
            
                $_SESSION['pending_user_id'] = $nuevoId;
                $_SESSION['errors'] = [
                    'type'     => 'info',
                    'messages' => ['El código expiró, pero ya te enviamos uno nuevo. Revisa tu bandeja.']
                ];
                header("Location: index.php?route=verificarCodigo");
                exit;
            }


            if ($codigoIngresado !== $pendiente['otp_code']) {
                throw new \Exception("El código ingresado es incorrecto.");
            }

            $ok = $this->servicio->confirmarYPasarUsuario($idPending);
            if (!$ok) {
                throw new \Exception("No se pudo crear el usuario. Intenta nuevamente.");
            }

            unset($_SESSION['pending_user_id']);
            $_SESSION['errors'] = [
                'type'     => 'success',
                'messages' => ['¡Registro completado! Ya puedes iniciar sesión.']
            ];
            header("Location: index.php?route=login");
            exit;

        } catch (\Throwable $e) {
            unset($_SESSION['pending_user_id']);
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => [$e->getMessage()]
            ];
            header("Location: index.php?route=registrar");
            exit;
        }
    }

    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $errors   = [];
        $correo   = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!$correo || !$password) {
            $errors[] = "Todos los campos son obligatorios";
        }

        if ($errors) {
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => $errors
            ];
            header("Location: index.php?route=login");
            exit;
        }

        $usuario = $this->servicio->auth($correo, $password);
        if ($usuario) {
            $_SESSION['user'] = [
                'id_user' => $usuario->getId(),     
                'nombre'  => $usuario->getName()            
            ];            
            $_SESSION['role'] = $usuario->getRol();
            $_SESSION['success'] = [
                'type'     => 'success',
                'messages' => ["Bienvenido, {$usuario->getName()}!"]
            ];
            header("Location: index.php?route=home");
            exit;
        }

        $_SESSION['errors'] = [
            'type'     => 'error',
            'messages' => ["Credenciales inválidas"]
        ];
        header("Location: index.php?route=login");
        exit;
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: index.php?route=home");
        exit;
    }
}
