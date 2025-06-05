<?php

// app/infrastucture/database/PaqueteRepositoryPgsql


namespace app\infrastructure\database;

use PDO;
use app\domain\entities\Paquete;
use app\domain\repositories\PaqueteRepository;

class PaqueteRepositoryPgsql implements PaqueteRepository
{
    public function __construct(private PDO $conexion) {}

    public function guardar(Paquete $paquete): bool
    {
        $sql = "INSERT INTO paquete (
            id_viaje_actual, tipo_paquete, nombre_remitente, telefono_remitente,
            nombre_destinatario, telefono_destinatario, ciudad_destino,
            direccion_destino, nombre_del_articulo, cantidad_bultos,
            peso, alto, ancho, contenido_fragil, codigo_rastreo, fecha_registro, estado
        ) VALUES (
            :id_viaje_actual, :tipo_paquete, :nombre_remitente, :telefono_remitente,
            :nombre_destinatario, :telefono_destinatario, :ciudad_destino,
            :direccion_destino, :nombre_del_articulo, :cantidad_bultos,
            :peso, :alto, :ancho, :contenido_fragil, :codigo_rastreo, :fecha_registro, :estado
        )";

        $stmt = $this->conexion->prepare($sql);

        $success = $stmt->execute([
            ':id_viaje_actual' => $paquete->getIdViajeActual(),
            ':tipo_paquete' => $paquete->getTipoPaquete(),
            ':nombre_remitente' => $paquete->getNombreRemitente(),
            ':telefono_remitente' => $paquete->getTelefonoRemitente(),
            ':nombre_destinatario' => $paquete->getNombreDestinatario(),
            ':telefono_destinatario' => $paquete->getTelefonoDestinatario(),
            ':ciudad_destino' => $paquete->getCiudadDestino(),
            ':direccion_destino' => $paquete->getDireccionDestino(),
            ':nombre_del_articulo' => $paquete->getNombreDelArticulo(),
            ':cantidad_bultos' => $paquete->getCantidadBultos(),
            ':peso' => $paquete->getPeso(),
            ':alto' => $paquete->getAlto(),
            ':ancho' => $paquete->getAncho(),
            ':contenido_fragil' => $paquete->getContenidoFragil(),
            ':codigo_rastreo' => $paquete->getCodigoRastreo(),
            ':fecha_registro' => $paquete->getFechaRegistro()->format('Y-m-d H:i:s'),
            ':estado' => $paquete->getEstado()
        ]);

        if (!$success) {
            $error = $stmt->errorInfo();
            echo "<pre>❌ Error SQL al guardar paquete: " . implode(' | ', $error) . "</pre>";
            exit;
        }

        return $success;
    }

    public function obtenerPorCodigoRastreo(string $codigoRastreo): ?Paquete
    {
        $sql = "SELECT * FROM paquete WHERE codigo_rastreo = :codigo LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':codigo' => $codigoRastreo]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$fila) return null;

        return $this->hidratarPaquete($fila);
    }

    public function listarPorViaje(int $idViajeActual): array
    {
        $sql = "SELECT * FROM paquete WHERE id_viaje_actual = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $idViajeActual]);

        $paquetes = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $paquetes[] = $this->hidratarPaquete($fila);
        }

        return $paquetes;
    }

    public function listarTodos(): array
    {
        $sql = "SELECT * FROM paquete ORDER BY fecha_registro DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        $paquetes = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $paquetes[] = $this->hidratarPaquete($fila);
        }

        return $paquetes;
    }

    public function actualizarEstado(int $id, string $estado): bool 
    {
        $sql = "UPDATE paquete SET estado = :estado WHERE id_paquete = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':estado' => $estado,
            ':id' => $id
        ]);
    }

    public function actualizarTodosEstado(string $estado): bool 
    {
        $sql = "UPDATE paquete SET estado = :estado";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':estado' => $estado]);
    }

    public function eliminarPorId(int $id): bool
    {
        $sql = "DELETE FROM paquete WHERE id_paquete = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }


                        public function obtenerIdViajeActualPorNumeroLogico(int $idViajeMes): ?int
                        {
                            $sql = "SELECT id_viaje_actual 
                                    FROM viajeactual 
                                    WHERE id_viaje_mes = :mes 
                                    ORDER BY id_viaje_actual DESC 
                                    LIMIT 1";

                            $stmt = $this->conexion->prepare($sql);
                            $stmt->execute([':mes' => $idViajeMes]);
                            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

                            return $result ? (int)$result['id_viaje_actual'] : null;
                        }



    private function hidratarPaquete(array $fila): Paquete
    {
        return new Paquete(
            id: $fila['id_paquete'],
            idViajeActual: $fila['id_viaje_actual'],
            tipoPaquete: $fila['tipo_paquete'],
            nombreRemitente: $fila['nombre_remitente'],
            telefonoRemitente: $fila['telefono_remitente'],
            nombreDestinatario: $fila['nombre_destinatario'],
            telefonoDestinatario: $fila['telefono_destinatario'],
            ciudadDestino: $fila['ciudad_destino'],
            direccionDestino: $fila['direccion_destino'],
            nombreDelArticulo: $fila['nombre_del_articulo'],
            cantidadBultos: $fila['cantidad_bultos'],
            peso: $fila['peso'],
            alto: $fila['alto'],
            ancho: $fila['ancho'],
            contenidoFragil: $fila['contenido_fragil'],
            codigoRastreo: $fila['codigo_rastreo'],
            estado: $fila['estado'],
            fechaRegistro: new \DateTime($fila['fecha_registro'])
        );
    }

public function obtenerIdViajeActualPorMesAnio(int $idViajeMes, int $mes, int $anio): ?int
{
    $sql = "SELECT id_viaje_actual 
            FROM viajeactual 
            WHERE id_viaje_mes = :idViajeMes
                AND EXTRACT(MONTH FROM fecha_salida_actual) = :mes
                AND EXTRACT(YEAR FROM fecha_salida_actual) = :anio
            ORDER BY id_viaje_actual DESC 
            LIMIT 1";

    $stmt = $this->conexion->prepare($sql);
    $stmt->execute([
        ':idViajeMes' => $idViajeMes,
        ':mes' => $mes,
        ':anio' => $anio
    ]);
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);

    return $result ? (int)$result['id_viaje_actual'] : null;
}

public function contarPaquetesPorViaje(int $idViajeActual): int
{
    $sql = "SELECT COUNT(*) AS total FROM paquete WHERE id_viaje_actual = :id";
    $stmt = $this->conexion->prepare($sql);
    $stmt->execute([':id' => $idViajeActual]);
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);

    return $result ? (int)$result['total'] : 0;
}

}
