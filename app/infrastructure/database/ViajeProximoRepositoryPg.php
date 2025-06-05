<?php
namespace app\infrastructure\database;

use app\domain\entities\ViajeProximo;
use app\domain\repositories\ViajeProximoRepository;
use PDO;
use PDOException;

class ViajeProximoRepositoryPg implements ViajeProximoRepository
{
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function guardar(ViajeProximo $v): bool {
        $sql = "INSERT INTO viajeactual (
                    fecha_recogida_actual,
                    fecha_entrega_actual,
                    lugar_destino_actual,
                    capacidad_paquetes,
                    fecha_salida_actual,
                    lugar_salida_actual,
                    id_viaje_mes
                ) VALUES (
                    CURRENT_TIMESTAMP, :fEntrega, :lDestino, :capacidad,
                    :fSalida, :lSalida, :idViajeMes
                )";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':fEntrega', $v->getFechaEntrega()?->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':lDestino', $v->getLugarDestino(), PDO::PARAM_STR);
        $stmt->bindValue(':capacidad', $v->getCapacidadPaquetes(), PDO::PARAM_INT);
        $stmt->bindValue(':fSalida', $v->getFechaSalida()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':lSalida', $v->getLugarSalida(), PDO::PARAM_STR);
        $stmt->bindValue(':idViajeMes', $v->getIdViajeMes(), PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al insertar viajeactual: " . $e->getMessage());
            return false;
        }
    }

    public function listarTodos(): array {
        $sql = "SELECT * FROM viajeactual ORDER BY fecha_salida_actual ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): ?array {
        $sql = "SELECT * FROM viajeactual WHERE id_viaje_actual = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        return $r ?: null;
    }

    public function actualizar(ViajeProximo $v): bool {
        if ($v->getId() === null) return false;

        $sql = "UPDATE viajeactual SET 
                    fecha_entrega_actual = :fEntrega,
                    lugar_destino_actual = :lDestino,
                    capacidad_paquetes = :capacidad,
                    fecha_salida_actual = :fSalida,
                    lugar_salida_actual = :lSalida,
                    id_viaje_mes = :idViajeMes
                WHERE id_viaje_actual = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':fEntrega', $v->getFechaEntrega()?->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':lDestino', $v->getLugarDestino(), PDO::PARAM_STR);
        $stmt->bindValue(':capacidad', $v->getCapacidadPaquetes(), PDO::PARAM_INT);
        $stmt->bindValue(':fSalida', $v->getFechaSalida()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':lSalida', $v->getLugarSalida(), PDO::PARAM_STR);
        $stmt->bindValue(':idViajeMes', $v->getIdViajeMes(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $v->getId(), PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar viajeactual: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar(int $id): bool {
        $sql = "DELETE FROM viajeactual WHERE id_viaje_actual = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar viajeactual: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerUltimoViajeActual(): ?array
    {
        $sql = "SELECT id_viaje_actual, id_viaje_mes, fecha_salida_actual 
                FROM viajeactual 
                ORDER BY fecha_salida_actual DESC 
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $viaje = $stmt->fetch(PDO::FETCH_ASSOC);

        return $viaje ?: null;
    }


}
