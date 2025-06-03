<?php

    namespace app\domain\repositories;

    interface PasswordResetRepository {
        public function create(string $email, string $tokenHash, string $expiresAt): void;
        public function find(string $email): ?array;
        public function delete(string $email): void;
        public function incrementAttempts(string $email) : void;
        public function blockUntil(string $email, string $blockedUntil): void;
    }

?>