<?php

    namespace app\presentation\controllers;

    use app\services\Serviciopassword;

    class PasswordController {

        private Serviciopassword $servicio;
        public function __construct(Serviciopassword $servicio) {
            $this->servicio = $servicio;
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
        }

        public function solicitarForm(){
            require __DIR__ . '/../views/request_reset.php';
        }

        public function requestForm(){
            $email = trim($_POST['email'] ?? '');
            error_log("DEBUG Controller: requestReset con email → [{$email}]");
            if(empty($email)){
                $_SESSION['errors'] = [
                    'type' => 'error',
                    'messages' => ['El correo es obligatorio']
                ];
                header('Location: index.php?route=request_reset');
                exit;
            }

            $ok = $this->servicio->requestReset($email);
            if ($ok) {
            $_SESSION['success'] = [
                'type'     => 'success',
                'messages' => ['Token enviado. Revisa tu correo.']
            ];
            header('Location: index.php?route=reset_password&email='.urlencode($email));
            exit;
        } else {
            $_SESSION['errors'] = [
                'type' => 'error',
                'messages' => ['Error al enviar el token']
            ];
            header('Location: index.php?route=request_reset');
            exit;
        }
    }



        public function resetForm(){
            $email = $_GET['email'] ?? $_SESSION['recovery_email'] ?? '';
            require __DIR__ . '/../views/reset_password.php';
        }

        public function perfomReset() {
            $email = trim($_POST['email'] ?? '');
            $token = trim($_POST['token'] ?? '');
            $newPassword = trim($_POST['new_password'] ?? '');
            $confirmPass = trim($_POST['confirm_password'] ?? '');

        if (!$email || !$token || !$newPassword || !$confirmPass) {
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => ['Todos los campos son obligatorios']
            ];
            header("Location: index.php?route=reset_password&email=".urlencode($email)
                  ."&token=".urlencode($token));
            exit;
        }
        if ($newPassword !== $confirmPass) {
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => ['Las contraseñas no coinciden']
            ];
            header("Location: index.php?route=reset_password&email=".urlencode($email)
                  ."&token=".urlencode($token));
            exit;
        }
        try {
            $ok = $this->servicio->resetPassword($email, $token, $newPassword);
            if ($ok) {
                $_SESSION['succes'] = [
                    'type' => 'success',
                    'messages' => ['Contraseña restablecida con éxito']
                ];
                header("Location: index.php?route=login");
                exit;
            } else {
                 $_SESSION['errors'] = [
                    'type'     => 'error',
                    'messages' => ['Error al restablecer la contraseña. Vuelve a intentarlo.']
                ];
                header("Location: index.php?route=reset_password&email=".urlencode($email));
                exit;
            }
            
        } catch (\Exception $e) {
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => [$e->getMessage()]
            ];
            header("Location: index.php?route=reset_password&email=".urlencode($email)
                  ."&token=".urlencode($token));
            exit;
        }
    }
}

?>