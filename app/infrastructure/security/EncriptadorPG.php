<?php

    namespace app\infrastructure\security;

    use  app\domain\repositories\Encriptador;

    class EncriptadorPG implements Encriptador {

        public function hash(string $password) : string{
            return password_hash($password, PASSWORD_BCRYPT);
        }

        public function verificar(string $password, string $hash) : bool {
            return password_verify($password, $hash);
        }

    }

?>