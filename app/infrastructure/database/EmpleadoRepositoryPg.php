<?php
namespace app\infrastructure\database;

use app\domain\entities\Empleado;
use app\domain\repositories\EmpleadoRepository;
use PDO;
use PDOException;
use DateTime;

class EmpleadoRepositoryPg implements EmpleadoRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function guardar(Empleado $empleado): bool
    {
        //hacemos una insercion de datos a la tabla
        //con una estructura de control if
        //para combrobar si el empleado ya existe
        if ($empleado->getId() === null) {
            $sql = "
                INSERT INTO empleado
                    (nombre, correo_electronico, telefono, puesto, salario,
                     fecha_contratacion, activo, created_at, password, last_login, archived)
                VALUES
                    (:nombre, :correo, :telefono, :puesto, :salario,
                     :fechaContratacion, :activo, :createdAt, :password, :lastLogin, :archived)
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':nombre',            trim($empleado->getNombre()), PDO::PARAM_STR);
            $stmt->bindValue(':correo',            trim($empleado->getCorreoElectronico()), PDO::PARAM_STR);
            $stmt->bindValue(':telefono',          $empleado->getTelefono(), PDO::PARAM_STR);
            $stmt->bindValue(':puesto',            $empleado->getPuesto(), PDO::PARAM_STR);
            $stmt->bindValue(':salario',           $empleado->getSalario(), PDO::PARAM_STR);
            $stmt->bindValue(':fechaContratacion', $empleado->getFechaContratacion()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->bindValue(':activo',            $empleado->isActivo() ? 't' : 'f', PDO::PARAM_STR);
            $stmt->bindValue(':createdAt',         $empleado->getCreatedAt()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->bindValue(':password',          $empleado->getPassword(), PDO::PARAM_STR);
            $stmt->bindValue(':lastLogin',         $empleado->getLastLogin() ? $empleado->getLastLogin()->format('Y-m-d H:i:s'): null, PDO::PARAM_STR);
            $stmt->bindValue(':archived',          $empleado->isArchived() ? 't' : 'f', PDO::PARAM_STR);

            /**
             * control try catch
             * para ver si ya existe el empleado 
             * si no pasamos al error
             */
            try {
                return $stmt->execute();
            } catch (PDOException $e) {
                error_log("Error en guardado de empleado: " . $e->getMessage());
                return false;
            }
        } else {
            /**
             * Aca si el if no se cumple podemos hacer un update
             */
            $sql = "
                UPDATE empleado SET
                    nombre             = :nombre,
                    telefono           = :telefono,
                    puesto             = :puesto,
                    salario            = :salario,
                    activo             = :activo,
                    password           = :password,
                    last_login         = :lastLogin,
                    archived           = :archived
                WHERE id_empleado     = :id_empleado
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':nombre',     trim($empleado->getNombre()), PDO::PARAM_STR);
            $stmt->bindValue(':telefono',   $empleado->getTelefono(), PDO::PARAM_STR);
            $stmt->bindValue(':puesto',     $empleado->getPuesto(), PDO::PARAM_STR);
            $stmt->bindValue(':salario',    $empleado->getSalario(), PDO::PARAM_STR);
            $stmt->bindValue(':activo',     $empleado->isActivo() ? 't' : 'f', PDO::PARAM_STR);
            $stmt->bindValue(':password',   $empleado->getPassword(), PDO::PARAM_STR);
            $stmt->bindValue(':lastLogin',  $empleado->getLastLogin() ? $empleado->getLastLogin()->format('Y-m-d H:i:s'): null, PDO::PARAM_STR);
            $stmt->bindValue(':archived',   $empleado->isArchived() ? 't' : 'f', PDO::PARAM_STR);
            $stmt->bindValue(':id_empleado',$empleado->getId(), PDO::PARAM_INT);
            try {
                return $stmt->execute();
            } catch (PDOException $e) {
                error_log("Error al actualizar empleado: " . $e->getMessage());
                return false;
            }
        }
    }

    /**
     * Funcion para obtern el correo del empleado
     * esto hace lo mismo que el de usuario
     */
    public function existeCorreo(string $correo): bool {
        $sql = "SELECT 1 FROM empleado WHERE LOWER(correo_electronico)=LOWER(:c) LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':c'=>trim($correo)]);
        return (bool) $stmt->fetchColumn();
    }
    public function obtenerPorCorreo(string $correo): ?Empleado
    {
        $sql = "
            SELECT
                id_empleado,
                nombre,
                correo_electronico,
                telefono,
                puesto,
                salario,
                fecha_contratacion,
                activo,
                created_at,
                password,
                last_login,
                archived
            FROM empleado
            WHERE LOWER(correo_electronico) = LOWER(:correo)
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':correo', trim($correo), PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (! $row) {
            return null;
        }

        return new Empleado(
            id:                (int)$row['id_empleado'],
            nombre:            $row['nombre'],
            correoElectronico: $row['correo_electronico'],
            telefono:          $row['telefono'],
            puesto:            $row['puesto'],
            salario:           $row['salario'] !== null ? (float)$row['salario'] : null,
            fechaContratacion: new DateTime($row['fecha_contratacion']),
            activo:            (bool)$row['activo'],
            createdAt:         new DateTime($row['created_at']),
            password:          $row['password'],
            lastLogin:         $row['last_login'] !== null ? new DateTime($row['last_login']) : null,
            archived:          (bool)$row['archived']
        );
    }

    /**
     * Funcion para obeter los id de los empleado 
     * se usa para validadciones y manejar errores de repeticion
     */
    public function obtenerPorId(int $id_empleado): ?Empleado
    {
        $sql = "
            SELECT
                id_empleado,
                nombre,
                correo_electronico,
                telefono,
                puesto,
                salario,
                fecha_contratacion,
                activo,
                created_at,
                password,
                last_login,
                archived
            FROM empleado
            WHERE id_empleado = :id_empleado
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_empleado', $id_empleado, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (! $row) {
            return null;
        }

        return new Empleado(
            id:                (int)$row['id_empleado'],
            nombre:            $row['nombre'],
            correoElectronico: $row['correo_electronico'],
            telefono:          $row['telefono'],
            puesto:            $row['puesto'],
            salario:           $row['salario'] !== null ? (float)$row['salario'] : null,
            fechaContratacion: new DateTime($row['fecha_contratacion']),
            activo:            (bool)$row['activo'],
            createdAt:         new DateTime($row['created_at']),
            password:          $row['password'],
            lastLogin:         $row['last_login'] !== null ? new DateTime($row['last_login']) : null,
            archived:          (bool)$row['archived']
        );
    }


    /**
     * Funciones especiañizadas para listar todo el apartado
     * del empleado y despues mostralos en tablas
     * asu vez de marcar cosas especiales
     * como sof-delete
     */
    public function listarActivos(): array
    {
        $sql = "
            SELECT 
                id_empleado,
                nombre,
                correo_electronico,
                telefono,
                puesto,
                salario,
                fecha_contratacion,
                activo,
                created_at,
                password,
                last_login,
                archived
            FROM empleado
            WHERE activo = TRUE AND archived = FALSE
            ORDER BY id_empleado
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Convertimos a un array asociativo simple (para la vista):
        $lista = [];
        foreach ($rows as $r) {
            $lista[] = [
                'id_empleado'       => (int)$r['id_empleado'],
                'nombre'            => $r['nombre'],
                'correo_electronico'=> $r['correo_electronico'],
                'telefono'          => $r['telefono'],
                'puesto'            => $r['puesto'],
                'salario'           => $r['salario'],
                'fecha_contratacion'=> $r['fecha_contratacion'],
                'activo'            => (bool)$r['activo'],
                'created_at'        => $r['created_at'],
                'archived'          => (bool)$r['archived']
            ];
        }
        return $lista;
    }

    public function listarInactivos(): array
    {
        $sql = "
            SELECT 
                id_empleado,
                nombre,
                correo_electronico,
                telefono,
                puesto,
                salario,
                fecha_contratacion,
                activo,
                created_at,
                password,
                last_login,
                archived
            FROM empleado
            WHERE (activo = FALSE) OR (archived = TRUE)
            ORDER BY id_empleado
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $lista = [];
        foreach ($rows as $r) {
            $lista[] = [
                'id_empleado'       => (int)$r['id_empleado'],
                'nombre'            => $r['nombre'],
                'correo_electronico'=> $r['correo_electronico'],
                'telefono'          => $r['telefono'],
                'puesto'            => $r['puesto'],
                'salario'           => $r['salario'],
                'fecha_contratacion'=> $r['fecha_contratacion'],
                'activo'            => (bool)$r['activo'],
                'created_at'        => $r['created_at'],
                'archived'          => (bool)$r['archived']
            ];
        }
        return $lista;
    }

    public function listarTodos(): array
    {
        $sql = "
            SELECT 
                id_empleado,
                nombre,
                correo_electronico,
                telefono,
                puesto,
                salario,
                fecha_contratacion,
                activo,
                created_at,
                password,
                last_login,
                archived
            FROM empleado
            ORDER BY id_empleado
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $lista = [];
        foreach ($rows as $r) {
            $lista[] = [
                'id_empleado'       => (int)$r['id_empleado'],
                'nombre'            => $r['nombre'],
                'correo_electronico'=> $r['correo_electronico'],
                'telefono'          => $r['telefono'],
                'puesto'            => $r['puesto'],
                'salario'           => $r['salario'],
                'fecha_contratacion'=> $r['fecha_contratacion'],
                'activo'            => (bool)$r['activo'],
                'created_at'        => $r['created_at'],
                'archived'          => (bool)$r['archived']
            ];
        }
        return $lista;
    }

    public function desactivar(int $id_empleado): bool
    {
        $sql = "UPDATE empleado SET activo = FALSE WHERE id_empleado = :id_empleado";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id_empleado' => $id_empleado]);
    }

    public function activar(int $id_empleado): bool
    {
        $sql = "UPDATE empleado SET activo = TRUE WHERE id_empleado = :id_empleado";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id_empleado' => $id_empleado]);
    }

    public function marcarArchived(int $id_empleado): bool
    {
        $sql = "UPDATE empleado SET archived = TRUE WHERE id_empleado = :id_empleado";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id_empleado' => $id_empleado]);
    }

    public function desmarcarArchived(int $id_empleado): bool
    {
        $sql = "UPDATE empleado SET archived = FALSE WHERE id_empleado = :id_empleado";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id_empleado' => $id_empleado]);
    }

    public function actualizarLastLogin(int $id_empleado, DateTime $dt): bool
    {
        $sql = "
            UPDATE empleado
            SET last_login = :lastLogin
            WHERE id_empleado = :id_empleado
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':lastLogin', $dt->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':id_empleado', $id_empleado, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
