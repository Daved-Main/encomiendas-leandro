<?php
namespace app\services;

use app\domain\repositories\EmpleadoRepository;
use app\domain\entities\Empleado;
use app\domain\repositories\Encriptador;
use DateTime;

class EmpleadoService {
    public function __construct(
        private EmpleadoRepository $repo,
        private Encriptador $encriptador
    ) {}

    public function registrar(Empleado $e): bool {
        $hashed = $this->encriptador->hash($e->getPassword());
        $empHash = new Empleado(
            id: null,
            nombre: $e->getNombre(),
            correoElectronico:$e->getCorreoElectronico(),
            telefono: $e->getTelefono(),
            puesto: $e->getPuesto(),
            salario: $e->getSalario(),
            fechaContratacion: $e->getFechaContratacion(),
            activo: true,
            createdAt: new \DateTime(),
            password: $hashed,
            lastLogin: null,
            archived: false
        );
        return $this->repo->guardar($empHash);
    }

    public function auth(string $correo, string $password, int $id_empleado, DateTime $dt): ?Empleado {
        $emp = $this->repo->obtenerPorCorreo($correo);
        if (!$emp || $emp->isArchived()) return null;
        if ($this->encriptador->verificar($password, $emp->getPassword())) {
            $emp->setLastLogin(new \DateTime());
            $this->repo->actualizarLastLogin($id_empleado, $dt);
            return $emp;
        }
        return null;
    }
}


?>