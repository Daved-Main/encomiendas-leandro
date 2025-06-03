<?php
namespace app\presentation\controllers;

use app\infrastructure\mail\EmailJsService;
use app\infrastructure\database\TwoFaRepository;
use app\services\ServicioUsuario;
use DateTime;
use PDO;

class LoginController {
    private PDO $pdo;
    private ServicioUsuario $servicio; 
    private EmailJsService $emailJsService;
    private TwoFaRepository $twoFaRepository;

    public function __construct(PDO $pdo, ServicioUsuario $servicio){
        $this->pdo             = $pdo;
        $this->servicio        = $servicio;
        $this->twoFaRepository = new TwoFaRepository($pdo);
        $this->emailJsService  = new EmailJsService();
    }

    public function login(array $post): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $correo   = trim($post['email']    ?? '');
        $password =            $post['password'] ?? '';

        // 1) Autenticar
        $user = $this->servicio->auth($correo, $password);
        if (!$user) {
            $_SESSION['error'] = 'Credenciales inválidas';
            header('Location: /login.php');
            exit;
        }

        // 2) Generar OTP
        $code    = str_pad((string) random_int(0,999999), 6, '0', STR_PAD_LEFT);
        $expires = new DateTime('+5 minutes');

        // 3) Guardar en BD
        $this->twoFaRepository->guardarToken(
            $user->getId(),
            $code,
            $expires
        );

        // 4) Enviar OTP y loguear
        error_log("[LoginController] → Enviando OTP a: $correo  Código: $code");
        $sent = $this->emailJsService->sendVerifyEmail(
            $user->getEmail(),
            $user->getName(),
            $code,
            $expires
        );
        error_log("[LoginController] ← Resultado sendVerifyEmail: " . ($sent ? 'OK' : 'FAIL'));

        // 5) Redirigir a verificación
        $_SESSION['pending_user_id'] = $user->getId();
        header('Location: /verify-2fa.php');
        exit;
    }
}
