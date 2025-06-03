<?php

namespace app\services;

use app\domain\entities\Usuario;
use app\domain\usecases\LoginUsuario;
use app\domain\repositories\UsuarioRepository;
use app\domain\repositories\Encriptador;

use DateTime;
use PDO;

class ServicioUsuario implements  LoginUsuario {
    private UsuarioRepository $repo;
    private Encriptador $encriptador;
    private PDO $pdo;

    public function __construct(
        UsuarioRepository $repo,
        Encriptador $encriptador,
        PDO $pdo
    ) {
        $this->repo = $repo;
        $this->encriptador = $encriptador;
        $this->pdo = $pdo;
    }

    public function crearPendiente(string $nombre, string $correo, string $passwordPlain): int
    {
        $passwordHash = $this->encriptador->hash($passwordPlain);

        // Generar código OTP de 6 dígitos (000000 – 999999)
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Caduca en 15 minutos
        $expiresAt = (new DateTime('+15 minutes'))->format('Y-m-d H:i:s');

        $sql = <<<SQL
            INSERT INTO pending_users 
              (nombre, correo, password_hash, rol, otp_code, otp_expires_at)
            VALUES 
              (:nombre, :correo, :password_hash, :rol, :otp_code, :otp_expires_at)
            RETURNING id_pending;
            SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nombre'         => $nombre,
            ':correo'         => $correo,
            ':password_hash'  => $passwordHash,
            ':rol'            => 'usuario',
            ':otp_code'       => $otp,
            ':otp_expires_at' => $expiresAt
        ]);

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($fila['id_pending']);
    }

    public function obtenerPendiente(int $idPending): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT nombre, correo, password_hash, rol, otp_code, otp_expires_at
            FROM pending_users
            WHERE id_pending = :id_pending
        ");
        $stmt->execute([':id_pending' => $idPending]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ?: null;
    }

    public function confirmarYPasarUsuario(int $idPending): bool
    {
        $datos = $this->obtenerPendiente($idPending);
        if (! $datos) {
            return false;
        }

        $ahora       = new DateTime();
        $vencimiento = new DateTime($datos['otp_expires_at']);
        if ($ahora > $vencimiento) {
            // OTP ya vencido
            return false;
        }

        $usuario = new Usuario(
            id: null,
            name: $datos['nombre'],
            email: $datos['correo'],
            password: $datos['password_hash'],
            rol: $datos['rol'],
            created_at: new \DateTime(),
            last_login: null,
            archived: false
            );
        
        $exito = $this->repo->guardar($usuario);


        if (! $exito) {
            return false;
        }

        $stmtDel = $this->pdo->prepare("
            DELETE FROM pending_users
            WHERE id_pending = :id_pending
        ");
        $stmtDel->execute([':id_pending' => $idPending]);

        return true;
    }

    public function existeCorreo(string $correo): bool {
        return $this->repo->existeCorreo($correo);
    }

    public function auth(string $correo, string $password): ?Usuario {
        $usuario = $this->repo->obtenerPorCorreo($correo);
        if (! $usuario || $usuario->getArvhived()) {
            return null;
        }
        if ($this->encriptador->verificar($password, $usuario->getPassword())) {
            $usuario->setlastLogin(new DateTime());
            $this->repo->actualizar($usuario);
            $_SESSION['name'] = $usuario->getName();
            return $usuario;
        }
        return null;
    }

    public function obtenerPorCorreo(string $correo): ?Usuario {
        return $this->repo->obtenerPorCorreo($correo);
    }

    public function obtenerPorId(int $id_user): ?Usuario {
        return $this->repo->obtenerPorId($id_user);
    }

    public function archiveUser(int $id_user): void {
        $this->repo->archivar($id_user);
    }

    public function correoPendienteExiste(string $correo): bool {
    $stmt = $this->pdo->prepare("SELECT 1 FROM pending_users WHERE LOWER(correo) = LOWER(:correo) LIMIT 1");
    $stmt->execute([':correo' => $correo]);
    return (bool) $stmt->fetchColumn();
    }

    public function obtenerPendientePorCorreo(string $correo): ?array {
    $sql = "SELECT * FROM pending_users WHERE LOWER(correo) = LOWER(:correo) LIMIT 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':correo' => $correo]);
    return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
}

public function limpiarExpirados(): void {
    $stmt = $this->pdo->prepare("DELETE FROM pending_users WHERE otp_expires_at < NOW()");
    $stmt->execute();
}

public function eliminarPendiente(int $idPending): void {
    $stmt = $this->pdo->prepare("DELETE FROM pending_users WHERE id_pending = :id");
    $stmt->execute([':id' => $idPending]);
}

}
