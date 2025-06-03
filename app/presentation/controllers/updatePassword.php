<?php

use app\infrastructure\database\PasswordResetRepositoryPg;
use app\infrastructure\security\EncriptadorPG;
use app\infrastructure\database\DatabaseConnect;
use app\infrastructure\database\UsuarioRepositoryPg;
use app\infrastructure\mail\EmailJsService;
use app\services\Serviciopassword;
use DateTime;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$email = $_POST['recovery_email' ?? ''];
$codeOk = !empty($_SESSION['cede_valid']);
$newPass = $_POST['new_pass' ?? ''];
$confirm = $_POST['confirm_pass' ?? ''];

if(!$codeOk || $newPass !== $confirm){
    $_SESSION['error_reset'] = 'Las contraseñas no coinciden o el código de recuperación no es válido';
    header('Location: resetPassword.php');
    exit;
}

$pdo = DatabaseConnect::getInstance();
$reset = new PasswordResetRepositoryPg($pdo);
$usuarioRepository = new UsuarioRepositoryPg($pdo);
$encriptador = new EncriptadorPG();
$mailer = new EmailJsService();

$service = new Serviciopassword(
    $reset,
    $usuarioRepository,
    $encriptador,
    $mailer
);
$token = $_GET['token'] ?? '';
if ($service->resetPassword($email, $token, $newPass)) {
    unset($_SESSION['recovery_email'], $_SESSION['code_valid']);
    header('Location: login.php?reset=success');
} else {
    $_SESSION['error_reset'] = 'No se pudo resetear la contraseña';
    header('Location: resetPassword.php');
}
exit;
?>