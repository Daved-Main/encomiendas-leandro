<?php

namespace app\domain\usecases;

use app\domain\entities\Usuario;


interface LoginUsuario {
    public function auth(string $nombre, string $password) : ?Usuario ;
}

?>