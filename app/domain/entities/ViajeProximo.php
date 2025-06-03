<?php
namespace app\domain\entities;

use DateTime;

class ViajeProximo
{
    public function __construct(
        private ?int      $id,
        private DateTime  $fechaRegistro,
        private DateTime  $fechaSalida,
        private ?DateTime $fechaEntrega,   // <-- Cambiado a DateTime|null
        private string    $lugarSalida,
        private string    $lugarDestino
    ) {}

    public function getId(): ?int {
        return $this->id;
    }

    public function getFechaRegistro(): DateTime {
        return $this->fechaRegistro;
    }

    public function getFechaSalida(): DateTime {
        return $this->fechaSalida;
    }

    public function getFechaEntrega(): ?DateTime {
        return $this->fechaEntrega;
    }

    public function getLugarSalida(): string {
        return $this->lugarSalida;
    }

    public function getLugarDestino(): string {
        return $this->lugarDestino;
    }

    // Si necesitas setter:
    public function setFechaRegistro(DateTime $r): void {
        $this->fechaRegistro = $r;
    }

    public function setFechaSalida(DateTime $s): void {
        $this->fechaSalida = $s;
    }

    public function setFechaEntrega(?DateTime $e): void {
        $this->fechaEntrega = $e;
    }

    public function setLugarSalida(string $l): void {
        $this->lugarSalida = $l;
    }

    public function setLugarDestino(string $d): void {
        $this->lugarDestino = $d;
    }
}
