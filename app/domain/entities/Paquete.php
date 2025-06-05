<?php
// app/domain/entities/Paquete.php
namespace app\domain\entities;

use DateTime;
use InvalidArgumentException;

class Paquete
{
    public function __construct(
        private ?int $id,
        private int $idViajeActual, // ✅ Antes era idEnvio, ahora correcto
        private string $tipoPaquete,
        private string $nombreRemitente,
        private string $telefonoRemitente,
        private string $nombreDestinatario,
        private string $telefonoDestinatario,
        private string $ciudadDestino,
        private string $direccionDestino,
        private string $nombreDelArticulo,
        private int $cantidadBultos,
        private float $peso,
        private float $alto,
        private float $ancho,
        private string $contenidoFragil,
        private string $codigoRastreo,
        private string $estado, // ✅ Incluido correctamente
        private int $idUser,
        private DateTime $fechaRegistro = new DateTime()
    ) {
        $this->validarContenidoFragil($contenidoFragil);
    }

    private function validarContenidoFragil(string $valor): void
    {
        if (!in_array($valor, ['Sí', 'No', 'Muy fragil'])) {
            throw new InvalidArgumentException("El campo contenido_fragil solo puede ser 'Sí', 'No' o 'Muy fragil'");
        }
    }

    // ✅ Getters

    public function getIdUser(): int {
    return $this->idUser;
    }


    public function getId(): ?int { return $this->id; }

    public function getIdViajeActual(): int { return $this->idViajeActual; }

    public function getTipoPaquete(): string { return $this->tipoPaquete; }

    public function getNombreRemitente(): string { return $this->nombreRemitente; }

    public function getTelefonoRemitente(): string { return $this->telefonoRemitente; }

    public function getNombreDestinatario(): string { return $this->nombreDestinatario; }

    public function getTelefonoDestinatario(): string { return $this->telefonoDestinatario; }

    public function getCiudadDestino(): string { return $this->ciudadDestino; }

    public function getDireccionDestino(): string { return $this->direccionDestino; }

    public function getNombreDelArticulo(): string { return $this->nombreDelArticulo; }

    public function getCantidadBultos(): int { return $this->cantidadBultos; }

    public function getPeso(): float { return $this->peso; }

    public function getAlto(): float { return $this->alto; }

    public function getAncho(): float { return $this->ancho; }

    public function getContenidoFragil(): string { return $this->contenidoFragil; }

    public function getCodigoRastreo(): string { return $this->codigoRastreo; }

    public function getEstado(): string { return $this->estado; }

    public function getFechaRegistro(): DateTime { return $this->fechaRegistro; }

    // Utilidades

    public function esFragil(): bool
    {
        return $this->contenidoFragil === 'Sí' || $this->contenidoFragil === 'Muy fragil';
    }

    public function calcularVolumen(): float
    {
        return $this->alto * $this->ancho * $this->peso;
    }
}
