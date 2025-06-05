<?php

// app/domain/repositories/PaqueteRepository.php

namespace app\domain\repositories;

use app\domain\entities\Paquete;

interface PaqueteRepository
{
    public function guardar(Paquete $paquete): bool;
    public function obtenerPorCodigoRastreo(string $codigoRastreo): ?Paquete;
    public function listarPorViaje(int $idViaje): array;
    public function eliminarPorId(int $id): bool;
    // Método para el listado de paquetes
    public function listarTodos(): array;
    
    public function obtenerIdViajeActualPorMesAnio(int $idViajeMes, int $mes, int $anio): ?int;
    public function contarPaquetesPorViaje(int $idViajeActual): int;


}