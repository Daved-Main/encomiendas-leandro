<?php

namespace app\infrastructure\database;
use app\domain\repositories\PasswordResetRepository;
use PDO;


class PasswordResetRepositoryPg implements PasswordResetRepository {

    private PDO $db;

    public function __construct(PDO $db) {
      $this->db = $db;
    }

    public function create(string $email, string $tokenHash, string $expiresAt) :void {
        $sql = <<<SQL
        INSERT INTO password_resets (email, token_hash, expires_at)
        VALUES (:email, :hash, :expiresAt)
        ON CONFLICT (email) DO UPDATE
          SET token_hash = EXCLUDED.token_hash,
              created_at = CURRENT_TIMESTAMP,
              expires_at = EXCLUDED.expires_at,
              used_at = NULL,
              attempts = 0,
              blocked_until = NULL
        SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':email'     => $email,
            ':hash'      => $tokenHash,
            ':expiresAt' => $expiresAt, 
        ]);
    }

    public function find(string $email): ?array {
        $stmt = $this->db->prepare(
            'SELECT token_hash, created_at, expires_at, used_at, attempts, blocked_until
             FROM password_resets
             WHERE email = :email'
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function delete(string $email): void {
      $stmt = $this->db->prepare('DELETE FROM password_resets WHERE email = :email');
      $stmt->execute([':email' => $email]);
  }
  
  public function incrementAttempts(string $email): void {
        $stmt = $this->db->prepare(
            'UPDATE password_resets
             SET attempts = attempts + 1
             WHERE email = :email'
        );
        $stmt->execute([':email' => $email]);
    }
    public function blockUntil(string $email, string $blockedUntil): void
    {
        $stmt = $this->db->prepare(
            'UPDATE password_resets
             SET blocked_until = :blockedUntil
             WHERE email = :email'
        );
        $stmt->execute([
            ':email'       => $email,
            ':blockedUntil'=> $blockedUntil 
        ]);
    }
}?>