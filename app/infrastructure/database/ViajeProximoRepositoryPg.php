<?php
namespace app\infrastructure\database;

use app\domain\entities\ViajeProximo;
use app\domain\repositories\ViajeProximoRepository;
use PDO;
use PDOException;
use DateTime;

class ViajeProximoRepositoryPg implements ViajeProximoRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function guardar(ViajeProximo $v): bool
    {
        if ($v->getId() === null) {
            $sql = "
              INSERT INTO viajeproximo
                (fecha_salida_proximo, fecha_entrega_proximo, 
                 lugar_salida_proximo, lugar_destino_proximo)
              VALUES
                (:fSalida, :fEntrega, :lSalida, :lDestino)
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':fSalida', $v->getFechaSalida()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->bindValue(':fEntrega', $v->getFechaEntrega()?->format('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->bindValue(':lSalida', $v->getLugarSalida(), PDO::PARAM_STR);
            $stmt->bindValue(':lDestino', $v->getLugarDestino(), PDO::PARAM_STR);
            try {
                return $stmt->execute();
            } catch (PDOException $e) {
                error_log("Error al insertar viajeproximo: " . $e->getMessage());
                return false;
            }
        }

        // (Si quieres implementar edición después, iría el UPDATE aquí)
        return false;
    }

    public function listarTodos(): array
    {
        $sql = "
          SELECT 
            id_viaje_proximo,
            fecha_registro_proximo,
            fecha_salida_proximo,
            fecha_entrega_proximo,
            lugar_salida_proximo,
            lugar_destino_proximo
          FROM viajeproximo
          ORDER BY fecha_salida_proximo ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $lista = [];
        foreach ($rows as $r) {
            $lista[] = [
                'id_viaje_proximo'        => (int)$r['id_viaje_proximo'],
                'fecha_registro_proximo'  => $r['fecha_registro_proximo'],
                'fecha_salida_proximo'    => $r['fecha_salida_proximo'],
                'fecha_entrega_proximo'   => $r['fecha_entrega_proximo'],
                'lugar_salida_proximo'    => $r['lugar_salida_proximo'],
                'lugar_destino_proximo'   => $r['lugar_destino_proximo'],
            ];
        }
        return $lista;
    }
    
    public function obtenerPorId(int $id): ?array
    {
        $sql = "
          SELECT 
            id_viaje_proximo,
            fecha_registro_proximo,
            fecha_salida_proximo,
            fecha_entrega_proximo,
            lugar_salida_proximo,
            lugar_destino_proximo
          FROM viajeproximo
          WHERE id_viaje_proximo = :id
          LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        return $r ? [
            'id_viaje_proximo'        => (int)$r['id_viaje_proximo'],
            'fecha_registro_proximo'  => $r['fecha_registro_proximo'],
            'fecha_salida_proximo'    => $r['fecha_salida_proximo'],
            'fecha_entrega_proximo'   => $r['fecha_entrega_proximo'],
            'lugar_salida_proximo'    => $r['lugar_salida_proximo'],
            'lugar_destino_proximo'   => $r['lugar_destino_proximo'],
        ] : null;
    }

    public function actualizar(ViajeProximo $v): bool
    {
        if ($v->getId() === null) {
            return false;
        }

        $sql = "
          UPDATE viajeproximo SET
            fecha_salida_proximo  = :fSalida,
            fecha_entrega_proximo = :fEntrega,
            lugar_salida_proximo  = :lSalida,
            lugar_destino_proximo = :lDestino
          WHERE id_viaje_proximo = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':fSalida', $v->getFechaSalida()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':fEntrega', $v->getFechaEntrega()?->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':lSalida', $v->getLugarSalida(), PDO::PARAM_STR);
        $stmt->bindValue(':lDestino', $v->getLugarDestino(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $v->getId(), PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar viajeproximo: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM viajeproximo WHERE id_viaje_proximo = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar viajeproximo: " . $e->getMessage());
            return false;
        }
    }
}
