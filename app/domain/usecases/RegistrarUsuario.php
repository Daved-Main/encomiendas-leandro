<?php

namespace app\domain\usecases;

use app\domain\entities\Usuario;

interface RegistrarUsuario {
    public function registrar(Usuario $usuario) : bool;
}

?>