<?php

namespace app\domain\repositories;

    interface Encriptador {
        public function hash(string $texto) : string;
        public function verificar(string $texto, string $hash) : bool;
    }

?>