<?php

namespace app\domain\entities;

use DateTime;

class Usuario {
    public function __construct(
        private ?int $id,
        private string $name,
        private string $email,
        private string $password,
        private string $rol = 'usuario',
        private DateTime $created_at = new DateTime(),
        private ?DateTime $last_login = null,
        private bool $archived = false
    ) {
        $this->id          = $id;
        $this->name        = $name;
        $this->email       = $email;
        $this->password    = $password;
        $this->rol         = $rol;
        $this->created_at  = $created_at;
        $this->last_login  = $last_login;
        $this->archived    = $archived;
    }

    public static function crearBasico(string $name, string $email, string $password): self
    {
        return new self(
            id:          null,
            name:        $name,
            email:       $email,
            password:    $password,
            rol:         'usuario',
            created_at:  new DateTime(),
            last_login:  null,
            archived:    false
        );
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRol(): string
    {
        return $this->rol;
    }

    public function getCreateAt(): DateTime
    {
        return $this->created_at;
    }
    public function getlastLogin() : ?DateTime{
        return $this->last_login;
    }

    public function getArvhived(): bool {
        return $this->archived;
    }

    public function setlastLogin(DateTime $dateTime): void {
        $this->last_login = $dateTime;
    }

    public function setArvhived(bool $archivado): void {
        $this->archived = $archivado;
    }

    public function setName(string $nombre) : void {
        $this->name = $nombre;
    }

    public function setEmail(string $correo) : void {
        $this->email = $correo;
    }
    public function setRol(string $role) : void {
        $this->rol = $role;
    }
}
