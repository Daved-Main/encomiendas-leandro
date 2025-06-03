<?php
namespace app\presentation\controllers;

use app\domain\entities\Empleado;
use app\domain\repositories\EmpleadoRepository;
use app\infrastructure\security\EncriptadorPG;
use DateTime;

class EmpleadoController
{
    private EmpleadoRepository $repo;
    private EncriptadorPG       $encriptador;

    public function __construct(EmpleadoRepository $repo, EncriptadorPG $encriptador)
    {
        $this->repo         = $repo;
        $this->encriptador  = $encriptador;
    }

    /**
     * Paso 1: Formulario de registro de empleado (solo GET).
     *        El admin ingresa nombre, email, teléfono, puesto, salario, contraseña, etc.
     */
    public function mostrarFormularioRegistro(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require_once __DIR__ . "/../views/empleado/registrar-empleado.php";
    }

    /**
     * Paso 2: Procesar el POST de registro.
     * - Valida que no exista correo duplicado (repo->existeCorreo).
     * - Hashea la contraseña.
     * - Llama a repo->guardar(...) con todos los datos.
     * - Redirige a lista de empleados con mensaje (flash).
     */
    public function registrarEmpleado(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1) Leer datos del POST
        $nombre    = trim($_POST['nombre'] ?? '');
        $correo    = trim($_POST['correo_electronico'] ?? '');
        $telefono  = trim($_POST['telefono'] ?? '');
        $puesto    = trim($_POST['puesto'] ?? '');
        $salario   = trim($_POST['salario'] ?? '');
        $password  = trim($_POST['password'] ?? '');

        // 2) Validar
        $errors = [];
        if (! $nombre || ! $correo || ! $password) {
            $errors[] = "Los campos Nombre, Correo y Contraseña son obligatorios.";
        }
        if ($correo && ! filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "El correo ingresado no es válido.";
        }
        if ($this->repo->existeCorreo($correo)) {
            $errors[] = "Ya existe un empleado con ese correo.";
        }
        if (! empty($errors)) {
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => $errors
            ];
            header("Location: index.php?route=empleado/registrar");
            exit;
        }

        // 3) Preparar la entidad Empleado (hasheando la contraseña)
        $hashedPassword  = $this->encriptador->hash($password);
        $fechaContratacion = new DateTime(); // hoy
        $createdAt       = new DateTime(); // hoy

        $empleado = new Empleado(
            id:                null,
            nombre:            $nombre,
            correoElectronico: $correo,
            telefono:          $telefono ?: null,
            puesto:            $puesto ?: null,
            salario:           $salario !== '' ? (float)$salario : null,
            fechaContratacion: $fechaContratacion,
            activo:            true,
            createdAt:         $createdAt,
            password:          $hashedPassword,
            lastLogin:         null,
            archived:          false
        );

        // 4) Guardar en BD
        $ok = $this->repo->guardar($empleado);
        if ($ok) {
            $_SESSION['success'] = [
                'type'     => 'success',
                'messages' => ["Empleado registrado correctamente."]
            ];
        } else {
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => ["Ocurrió un error al registrar el empleado."]
            ];
        }
        header("Location: index.php?route=empleado/listar&estado=activos");
        exit;
    }

    /**
     * Listar empleados (GET) con filtro: activos, archivados o todos.
     */
    public function listarEmpleados(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $estado = $_GET['estado'] ?? 'activos';
        switch ($estado) {
            case 'archivados':
                $empleados = $this->repo->listarInactivos();
                break;
            case 'todos':
                $empleados = $this->repo->listarTodos();
                break;
            case 'activos':
            default:
                $empleados = $this->repo->listarActivos();
                break;
        }
        require_once __DIR__ . "/../views/empleado/listar-empleados.php";
    }

    /**
     * “Soft‐delete” de empleado: archived = TRUE
     */
    public function desactivar(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $id = (int)($_POST['id_empleado'] ?? 0);
        if ($id > 0) {
            $this->repo->marcarArchived($id);
        }
        header("Location: index.php?route=empleado/listar&estado=activos");
        exit;
    }

    /**
     * Reactivar empleado archivado: archived = FALSE
     */
    public function activar(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $id = (int)($_POST['id_empleado'] ?? 0);
        if ($id > 0) {
            $this->repo->desmarcarArchived($id);
        }
        header("Location: index.php?route=empleado/listar&estado=archivados");
        exit;
    }

    /**
     * (Opción 1) Mostrar el formulario de login exclusivo para empleados.
     * GET /?route=empleado/login
     */
    public function mostrarLoginEmpleado(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require_once __DIR__ . "/../views/empleado/login-empleado.php";
    }

    /**
     * (Opción 2) Procesar POST de login de empleado.
     * POST /?route=empleado/login
     */
    public function loginEmpleado(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $errors = [];
        $correo = trim($_POST['correo_electronico'] ?? '');
        $pass   = trim($_POST['password'] ?? '');

        if (! $correo || ! $pass) {
            $errors[] = "Todos los campos son obligatorios.";
        }
        if ($errors) {
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => $errors
            ];
            header("Location: index.php?route=empleado/login");
            exit;
        }

        // 1) Buscar el empleado por correo
        $empleado = $this->repo->obtenerPorCorreo($correo);
        if (! $empleado) {
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => ["Credenciales inválidas."]
            ];
            header("Location: index.php?route=empleado/login");
            exit;
        }

        // 2) Si está archivado o inactivo, no dejarlos entrar
        if (! $empleado->isActivo() || $empleado->isArchived()) {
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => ["Su cuenta de empleado no está activa."]
            ];
            header("Location: index.php?route=empleado/login");
            exit;
        }

        // 3) Verificar la contraseña hasheada
        if (! $this->encriptador->verificar($pass, $empleado->getPassword())) {
            $_SESSION['errors'] = [
                'type'     => 'error',
                'messages' => ["Credenciales inválidas."]
            ];
            header("Location: index.php?route=empleado/login");
            exit;
        }

        // 4) Autenticación exitosa: actualizar last_login
        $this->repo->actualizarLastLogin($empleado->getId(), new DateTime());

        // 5) Guardar en sesión los datos de empleado para futuras páginas
        $_SESSION['empleado_id']     = $empleado->getId();
        $_SESSION['empleado_nombre'] = $empleado->getNombre();
        $_SESSION['role'] = 'empleado';
        $_SESSION['empleado']        = true; // indicador de “está logueado como empleado”

        // Podrías redirigir a un dashboard de empleados, por ejemplo:
        header("Location: index.php?route=empleado/dashboard");
        exit;
    }

    /**
     * Ejemplo de una página de “dashboard” para empleados (requiere estar logueado).
     */
    public function dashboardEmpleado(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (! isset($_SESSION['empleado']) || ! $_SESSION['empleado']) {
            header("Location: index.php?route=empleado/login");
            exit;
        }
        // Aquí puedes cargar una vista con las opciones/opciones propias del empleado
        require_once __DIR__ . "/../views/empleado/dashboard-empleado.php";
    }

    /**
     * Logout del empleado: destruir solo variables de empleado.
     */
    public function logoutEmpleado(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['empleado']);
        unset($_SESSION['empleado_id']);
        unset($_SESSION['empleado_nombre']);
        unset($_SESSION['role']);
        header("Location: index.php?route=empleado/login");
        exit;
    }
    public function mostrarFormularioEdicion(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $id = (int)($_GET['id_empleado'] ?? 0);
    if ($id <= 0) {
        header("Location: index.php?route=empleado/listar&estado=activos");
        exit;
    }
    $empleado = $this->repo->obtenerPorId($id);
    if (! $empleado) {
        header("Location: index.php?route=empleado/listar&estado=activos");
        exit;
    }
    // Pasamos la entidad $empleado a la vista para precargar datos:
    require_once __DIR__ . "/../views/empleado/editar-empleado.php";
}

/**
 * Procesar el POST de edición (cambiar salario/puesto/contraseña):
 */
public function guardarEdicion(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $id = (int)($_POST['id_empleado'] ?? 0);
    if ($id <= 0) {
        header("Location: index.php?route=empleado/listar&estado=activos");
        exit;
    }
    $empleadoExistente = $this->repo->obtenerPorId($id);
    if (! $empleadoExistente) {
        header("Location: index.php?route=empleado/listar&estado=activos");
        exit;
    }

    // Leer campos de POST
    $puesto    = trim($_POST['puesto'] ?? '');
    $salario   = trim($_POST['salario'] ?? '');
    $password  = trim($_POST['password'] ?? '');

    $errors = [];
    // Validaciones simples
    if ($salario !== '' && ! is_numeric($salario)) {
        $errors[] = "El salario debe ser un número válido.";
    }
    if (! empty($errors)) {
        $_SESSION['errors'] = [
            'type'     => 'error',
            'messages' => $errors
        ];
        header("Location: index.php?route=empleado/editar&id_empleado={$id}");
        exit;
    }

    // Actualizamos la entidad:
    if ($puesto !== '') {
        $empleadoExistente->setPuesto($puesto);
    }
    if ($salario !== '') {
        $empleadoExistente->setSalario((float)$salario);
    }
    if ($password !== '') {
        // Si se ingresó contraseña nueva, la hasheamos
        $empleadoExistente->setPassword(
            $this->encriptador->hash($password)
        );
    }
    // Finalmente, persistimos:
    $this->repo->guardar($empleadoExistente);

    $_SESSION['success'] = [
        'type'     => 'success',
        'messages' => ["Empleado actualizado correctamente."]
    ];
    header("Location: index.php?route=empleado/listar&estado=activos");
    exit;
}
}
