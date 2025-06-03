<?php
namespace app\domain\repositories;

use app\domain\entities\ViajeProximo;

interface ViajeProximoRepository
{

    public function guardar(ViajeProximo $v): bool;
    public function listarTodos(): array;

    public function obtenerPorId(int $id): ?array;
    public function actualizar(ViajeProximo $v): bool;
    public function eliminar(int $id): bool;

}
