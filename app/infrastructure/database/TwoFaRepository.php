<?php

    namespace app\infrastructure\database;

    use PDO;
    use DateTime;

    class TwoFaRepository {
        private PDO $db;
        public function __construct(PDO $db) {
        $this->db = $db;    }

        //Guardar token OTP
        public function guardarToken(int $userId, string $code, DateTime $expiresAt): void{
            $this->deleteCode($userId);
            $sql = "
                INSERT INTO two_fa_codes (id_user, code, expires_at)
                VALUES (:userId, :code, :expiresAt)
                    ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':userId'    => $userId,
                ':code'      => $code,
                ':expiresAt' => $expiresAt->format('Y-m-d H:i:s'),
            ]);
        }


        //Encontrar el valor del token
        public function findValue(int $userId): ?object{
            $stmt = $this->db->prepare("
                SELECT id_user, code, attempts, blocked_until, expires_at
                FROM two_fa_codes
                WHERE id_user = :userId
            ");
            $stmt->execute([':userId' => $userId]);
            return $stmt->fetch(PDO::FETCH_OBJ) ?: null;
           
        }


        //Numero de intentos
        public function incrementarIntentos(int $userId): void {
            $stmt = $this->db->prepare("
                UPDATE two_fa_codes
                SET attempts = attempts + 1
                WHERE id_user = :userId
            ");
            $stmt->execute([':userId' => $userId]);
        }


        //Bloqueat el usuario
        public function bloquearUsuario(int $userId, DateTime $blockedUntil): void {
        $stmt = $this->db->prepare("
          UPDATE two_fa_codes
          SET blocked_until = :blockedUntil
          WHERE id_user = :userId
        ");
        $stmt->execute([
          ':userId'       => $userId,
          ':blockedUntil' => $blockedUntil->format('Y-m-d H:i:s'),
        ]);
        }

        //eliminar el token
        public function deleteCode(int $userId): void{
            $stmt = $this->db->prepare("
            DELETE FROM two_fa_codes
            WHERE id_user = :userId
            ");
            $stmt->execute([':userId' => $userId]);
        }

        //Obtener el usuario activo
        public function getActiveUser(int $userId): ?object {
            $sql = "
                SELECT id, id_user, code, attempts, blocked_until, expires_at
                FROM two_fa_codes
                WHERE id_user = :userId
                AND expires_at >= NOW()
                AND (blocked_until IS NULL OR blocked_until <= NOW())
                LIMIT 1
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':userId' => $userId]);
            $row = $stmt->fetch(PDO::FETCH_OBJ);
            return $row ?: null;
        }

        //Invalidar el token
        public function invalidateToken(int $userId): void {
            $this->deleteCode($userId);
        }
    }
?>