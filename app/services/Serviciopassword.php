<?php

namespace app\services;

use app\domain\repositories\PasswordResetRepository;
use app\domain\repositories\UsuarioRepository;
use app\infrastructure\mail\EmailJsService;
use app\domain\repositories\Encriptador;

class Serviciopassword {

    const OTP_LENGTH = 12;
    const EXPIRATION_MIN = 15;          
    const MAX_ATTEMPTS = 5;             
    const BLOCK_DURATION_MIN = 15; 

    public function __construct(
      private PasswordResetRepository $resetRepo,
      private UsuarioRepository       $userRepo,
      private Encriptador            $encriptador,
      private EmailJsService         $mailer
    ) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Aca es la funcion para hashear el token
     * y guardarlo para el restablecimiento de la contraseña
     */
    public function requestReset(string $email): bool
    {
        $email = trim($email);
        error_log("DEBUG Service: requestReset llamado con email → [{$email}]");
        $user = $this->userRepo->obtenerPorCorreo($email);

        if (!$user) {
            error_log("DEBUG Service: usuario NO encontrado para [{$email}]");
            return false;
        }
        error_log("DEBUG Service: usuario ENCONTRADO id={$user->getId()} email={$user->getEmail()}");

        // Generar token
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_=+[]{}|;:,.<>?';
        $max   = strlen($chars) - 1;
        $token = '';
        for ($i = 0; $i < self::OTP_LENGTH; $i++) {
            $token .= $chars[random_int(0, $max)];
        }
        $tokenHash = hash('sha256', $token);
        $expiresAt = (new \DateTime())->modify('+'.self::EXPIRATION_MIN.' minutes')->format('Y-m-d H:i:s');

        $this->resetRepo->create($email, $tokenHash, $expiresAt);

        $sent = $this->mailer->sendVerifyEmail(
            $email,
            $user->getName(),
            $token,
            new \DateTime($expiresAt)
        );
        error_log("DEBUG Service: sendPasswordReset devolvió → " . var_export($sent, true));

        if($sent){
            $_SESSION['recovery_email'] = $email;
        }

        return $sent;
    }
   
    //Verifica el token de recuperación de contraseña
    public function resetPassword(string $email, string $token, string $newPass): bool{
        $email = trim($email);
        $token = trim($token);
        $row   = $this->resetRepo->find($email);

        if (!$row) {
            return false;
        }

        $now = new \DateTime();

        if(!empty($row['blocked_until']) && $now < new \DateTime($row['blocked_until'])){
            throw new \Exception('Tu cuenta ha sido bloqueada temporalmente');
        }

        $expiresAt = new \DateTime($row['expires_at']);
        if ($now > $expiresAt) {
            $this->resetRepo->delete($email);
            throw new \Exception('Token de recuperación de contraseña expirado');
        }

        $inputHash = hash('sha256', $token);
        if($inputHash !== $row['token_hash']){
            $this->resetRepo->incrementAttempts($email);
            $attemptsLeft = self::MAX_ATTEMPTS - $row['attempts'] + 1;
            if($attemptsLeft <=0) {
                $blockedUntil = (new \DateTime())->modify('+'.self::BLOCK_DURATION_MIN.' minutes');
                throw new \Exception('Número máximo de intentos alcanzado. Tu cuenta ha sido bloqueada temporalment'.$blockedUntil->format('H:i'));
            }
            throw new \Exception('Token de recuperación de contraseña inválido'.$attemptsLeft.' intentos');
        }

        $hashedNew = $this->encriptador->hash($newPass);
        $this->userRepo->actualizarPassword($email, $hashedNew);

        $this->resetRepo->delete($email);
        return true;
    }
}

?>