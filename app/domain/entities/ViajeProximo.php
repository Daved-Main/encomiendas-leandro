<?php
namespace app\domain\entities;

use DateTime;

class ViajeProximo
{
    public function __construct(
        private ?int      $id,
        private DateTime  $fechaRecogida,
        private ?DateTime $fechaEntrega,
        private string    $lugarDestino,
        private int       $capacidadPaquetes,
        private DateTime  $fechaSalida,
        private string    $lugarSalida,
        private ?int      $idViajeMes
    ) {}

    public function getId(): ?int { return $this->id; }
    public function getFechaRecogida(): DateTime { return $this->fechaRecogida; }
    public function getFechaEntrega(): ?DateTime { return $this->fechaEntrega; }
    public function getLugarDestino(): string { return $this->lugarDestino; }
    public function getCapacidadPaquetes(): int { return $this->capacidadPaquetes; }
    public function getFechaSalida(): DateTime { return $this->fechaSalida; }
    public function getLugarSalida(): string { return $this->lugarSalida; }
    public function getIdViajeMes(): ?int { return $this->idViajeMes; }

    // Setters si los necesitas
    public function setFechaEntrega(?DateTime $f): void { $this->fechaEntrega = $f; }
    public function setLugarDestino(string $l): void { $this->lugarDestino = $l; }
    public function setCapacidadPaquetes(int $c): void { $this->capacidadPaquetes = $c; }
    public function setFechaSalida(DateTime $fs): void { $this->fechaSalida = $fs;}
    public function setLugarSalida(string $ls): void { $this->lugarDestino = $ls; }
    public function setIdViajeMes(?int $idV): void { $this->idViajeMes = $idV;}
}
