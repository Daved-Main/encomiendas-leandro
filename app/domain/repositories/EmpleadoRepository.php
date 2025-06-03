<?php
namespace app\domain\repositories;

use app\domain\entities\Empleado;
use DateTime;

interface EmpleadoRepository
{
    public function guardar(Empleado $empleado): bool;

    /**
     * Busca un empleado según correo electrónico ignorando mayúsculas/minúsculas
     * Retorna el objeto Empleado (con su password hashed) o null si no existe
     * Esto se aplica y ejecuta para intaciar los metodos necesarios ára CRUD 
     * y Autentificación
     */
    public function obtenerPorCorreo(string $correo): ?Empleado;
    public function existeCorreo(string $correo): bool;
    public function obtenerPorId(int $id_empleado): ?Empleado;

    public function listarActivos(): array;    // Devuelve array asociativo de empleados con activo=TRUE y archived=FALSE
    public function listarInactivos(): array;  // Devuelve array de empleados con activo=FALSE o archived=TRUE
    public function listarTodos(): array;      // Todos, sin filtrar

    public function desactivar(int $id_empleado): bool;  // activo → FALSE
    public function activar(int $id_empleado): bool;    // activo → TRUE

    public function marcarArchived(int $id_empleado): bool;   // archived → TRUE
    public function desmarcarArchived(int $id_empleado): bool; // archived → FALSE

    public function actualizarLastLogin(int $id_empleado, DateTime $dt): bool;
}
