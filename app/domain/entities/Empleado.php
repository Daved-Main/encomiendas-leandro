<?php

namespace app\domain\entities;

use DateTime;

class Empleado {

    // Campos instacioados y referenciados a la tabla
    private ?int     $id;
    private string   $nombre;
    private string   $correoElectronico;
    private ?string  $telefono;
    private ?string  $puesto;
    private ?float   $salario;
    private DateTime $fechaContratacion;
    private bool     $activo;
    private DateTime $createdAt;
    private string   $password;      
    private ?DateTime $lastLogin;    
    private bool     $archived;
    
    public function __construct(
        ?int     $id,
        string   $nombre,
        string   $correoElectronico,
        ?string  $telefono,
        ?string  $puesto,
        ?float   $salario,
        DateTime $fechaContratacion,
        bool     $activo,
        DateTime $createdAt,
        string   $password,
        ?DateTime $lastLogin,
        bool     $archived        
    ) {
        $this->id                = $id;
        $this->nombre            = $nombre;
        $this->correoElectronico = $correoElectronico;
        $this->telefono          = $telefono;
        $this->puesto            = $puesto;
        $this->salario           = $salario;
        $this->fechaContratacion = $fechaContratacion;
        $this->activo            = $activo;
        $this->createdAt         = $createdAt;

        $this->password          = $password;
        $this->lastLogin         = $lastLogin;
        $this->archived          = $archived;
    }
    // Metodo Getter
 public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getCorreoElectronico(): string
    {
        return $this->correoElectronico;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function getPuesto(): ?string
    {
        return $this->puesto;
    }

    public function getSalario(): ?float
    {
        return $this->salario;
    }

    public function getFechaContratacion(): DateTime
    {
        return $this->fechaContratacion;
    }

    public function isActivo(): bool
    {
        return $this->activo;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getLastLogin(): ?DateTime
    {
        return $this->lastLogin;
    }

    public function isArchived(): bool
    {
        return $this->archived;
    }

    // Metodo Setter
    public function setPassword(string $hashedPassword): void
    {
        $this->password = $hashedPassword;
    }

    public function setLastLogin(DateTime $dt): void
    {
        $this->lastLogin = $dt;
    }

    public function setArchived(bool $arch): void
    {
        $this->archived = $arch;
    }

    public function setActivo(bool $activo): void
    {
        $this->activo = $activo;
    }
    public function setPuesto(string $puesto): void{
        $this->puesto = $puesto;
    }
    public function setSalario(float $salario): void{
        $this->salario = $salario;
    }
}
?>