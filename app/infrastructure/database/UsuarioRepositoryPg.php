<?php

namespace app\infrastructure\database;

use app\domain\entities\Usuario;
use app\domain\repositories\UsuarioRepository;
use PDO;
use PDOException;
use DateTime;
use app\infrastructure\database\DuplicateUserException;

class UsuarioRepositoryPg implements UsuarioRepository {
    private PDO $db;

    public function __construct(PDO $db){
        $this->db = $db;
    }

    //funcion para obtener los correos de los usuarios
    public function existeCorreo(string $correo): bool {
    $sql = "SELECT * FROM usuario WHERE correo = :correo LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':correo', trim($correo), PDO::PARAM_STR);
    $stmt->execute();

    return (bool) $stmt->fetchColumn();
    }



    //funcion para guardar los usuarios
    public function guardar(Usuario $usuario) : bool {
        $sql = "
            INSERT INTO usuario
                (nombre, correo, password, rol, created_at, last_login, archived)
            VALUES
                (:nombre, :correo, :password, :rol, :created_at, :last_login, :archived)
        ";

        $stmt = $this->db->prepare($sql);
        
    
        $stmt->bindValue(':nombre',     trim($usuario->getName()), PDO::PARAM_STR);
        $stmt->bindValue(':correo',     trim($usuario->getEmail()), PDO::PARAM_STR);
        $stmt->bindValue(':password', $usuario->getPassword(), PDO::PARAM_STR);
        $stmt->bindValue(':rol',      $usuario->getRol(), PDO::PARAM_STR);
        $stmt->bindValue(':created_at', $usuario->getCreateAt()->format('Y-m-d H:i:s'), PDO::PARAM_STR);

        if ($usuario->getlastLogin() instanceof DateTime) {
            $stmt->bindValue(
                ':last_login',
                $usuario->getlastLogin()->format('Y-m-d H:i:s'),
                PDO::PARAM_STR
            );
        } else {
            $stmt->bindValue(':last_login', null, PDO::PARAM_NULL);        
        }
        
        $stmt->bindValue(':archived', $usuario->getArvhived() ? 't' : 'f', PDO::PARAM_STR);

        try {
        return $stmt->execute();
    } catch (PDOException $e) {
        if ($e->getCode() === '23505') {
            throw new DuplicateUserException('El correo ya está registrado.');
        }
        error_log("Error al guardar usuario: " . $e->getMessage());
        return false;
    }
            
    }

    //Funcion para obtener el correo del usuario
    public function obtenerPorCorreo(string $correo): ?Usuario {
    $correoTrim = trim($correo);
    error_log("DEBUG Repo: buscar usuario con correo → [{$correoTrim}]");

    $sql = "
        SELECT id_user,
               nombre,
               correo,
               password,
               rol,
               created_at,
               last_login,
               archived
        FROM usuario
        WHERE LOWER(correo) = LOWER(:correo)
        LIMIT 1
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':correo', $correoTrim, PDO::PARAM_STR);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    error_log('DEBUG Repo: fetch = '. var_export($row, true));

    if (!$row) {
        return null;
    }

    return new Usuario(
        id:             (int) $row['id_user'],
        name:           $row['nombre'],
        email:          $row['correo'],
        password:       $row['password'],
        rol:            $row['rol'],
        created_at: new DateTime($row['created_at']),
        last_login:     isset($row['last_login']) ? new DateTime($row['last_login']) : null,
        archived:      (bool) $row['archived']
    );
}

public function obtenerPorId(int $id_user): ?Usuario {
        $sql = "
            SELECT id_user, nombre, correo, password, rol, created_at, last_login, archived
            FROM usuario
            WHERE id_user = :id_user
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (! $row) {
            return null;
        }
        return new Usuario(
            id:             (int) $row['id_user'],
            name:           $row['nombre'],
            email:          $row['correo'],
            password:       $row['password'],
            rol:            $row['rol'],
            created_at: new DateTime($row['created_at']),
            last_login: isset($row['last_login']) ? new DateTime($row['last_login']) : null,
            archived:   (bool) $row['archived']
        );
    }

    /**
     * Actualizar la pass
     * 
     * @param string $email
     * @param string $hashedPassword
     * @return bool
     */
    public function actualizarPassword(string $email,string $hashedPassword): bool{
        $sql = 'UPDATE usuario
                SET password = :password
                WHERE correo = :correo';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindValue(':correo', trim($email), PDO::PARAM_STR);

        try{
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar la contraseña: " . $e->getMessage());
            return false;
        }
    }

    //Funcion de update
    public function actualizar(Usuario $usuario): bool
    {
        $sql = "
          UPDATE usuario
          SET nombre     = :nombre,
              correo     = :correo,
              rol        = :rol,
              last_login = :last_login
          WHERE id_user  = :id_user
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nombre', $usuario->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':correo', $usuario->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':rol',    $usuario->getRol(), PDO::PARAM_STR);
        $stmt->bindValue(':last_login', $usuario->getLastLogin()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':id_user', $usuario->getId(), PDO::PARAM_INT);
        try {
            return $stmt->execute();        }
            catch (PDOException $e) {
            echo "Error al actualizar el usuario: " . $e->getMessage();
            return false;
        }

    }

    public function actualizarLastLogin(int $id_user, DateTime $fecha): bool {
    $sql = "
      UPDATE usuario
      SET last_login = :last_login
      WHERE id_user  = :id_user
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':last_login', $fecha->format('Y-m-d H:i:s'), PDO::PARAM_STR);
    $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar last_login: " . $e->getMessage());
            return false;
        }
}


    public function archivar(int $id_user): bool
    {
        $sql = "UPDATE usuario SET archived = TRUE WHERE id_user = :id_user";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id_user' => $id_user]);
    }

    public function listarActivos(): array
    {
        $sql = "
        SELECT id_user, nombre, correo, password, rol, created_at, last_login, archived
        FROM usuario
        WHERE archived = FALSE
        ORDER BY id_user
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarInactivos() : array {
        $sql = "
        SELECT id_user, nombre, correo, password, rol, created_at, last_login, archived
        FROM usuario
        WHERE archived = TRUE
        ORDER BY id_user
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    public function listarTodos(): array
    {
        $sql = "
            SELECT id_user, nombre, correo, password, rol, created_at, last_login, archived
            FROM usuario
            ORDER BY id_user
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function desarchivar(int $id_user): bool
    {
    $sql  = "UPDATE usuario SET archived = FALSE WHERE id_user = :id_user";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([':id_user' => $id_user]);
    }

}

?>