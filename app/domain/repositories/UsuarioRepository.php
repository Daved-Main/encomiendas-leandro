<?php

namespace app\domain\repositories;

use PDO;
use app\domain\entities\Usuario;
use DateTime;

    interface UsuarioRepository {

        public function existeCorreo(string $correo) : bool;
        public function guardar(Usuario $usuario) : bool;
        public function obtenerPorCorreo(string $correo) : ?Usuario;
        public function actualizarPassword(string $correo, string $hashedPassword) : bool;
        public function obtenerPorId(int $id_user): ?Usuario;

        public function listarActivos() : array;
        public function listarTodos() : array;
        public function listarInactivos() : array;
        public function actualizar(Usuario $usuario) : bool;
        public function archivar(int $id_user): bool;
        public function actualizarLastLogin(int $id_user, DateTime $fecha): bool;
        public function desarchivar(int $id_user) : bool;
    }

?>