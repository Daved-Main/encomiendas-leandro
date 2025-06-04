<?php

    require __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../app/presentation/controllers/UsuarioController.php';

    use app\services\ServicioUsuario;
    use app\infrastructure\database\UsuarioRepositoryPg;
    use app\infrastructure\security\EncriptadorPG;
    use app\presentation\controllers\UsuarioController;
    use app\infrastructure\database\DatabaseConnect;
    use app\presentation\controllers\PasswordController;
    use app\services\Serviciopassword;
    use app\infrastructure\mail\EmailJsService;
    use app\infrastructure\database\PasswordResetRepositoryPg;
    use app\infrastructure\database\TwoFaRepository;
    use app\presentation\controllers\AdminController;
    use app\presentation\controllers\EmpleadoController;
    use app\infrastructure\database\EmpleadoRepositoryPg;
    use app\presentation\controllers\ViajeProximoController;
    use app\infrastructure\database\ViajeProximoRepositoryPg;
    use app\presentation\controllers\ReportController;

// Base de datos y cuestiones
    $pdo = DatabaseConnect::getInstance();
    $repo = new UsuarioRepositoryPg($pdo);
    $auth = new EncriptadorPG();
    $servicio = new ServicioUsuario($repo, $auth, $pdo);
// Controladores
    $mailer = new EmailJsService();
    $reset = new PasswordResetRepositoryPg($pdo);
    $servicePw = new Serviciopassword(
        $reset,
        $repo,
        $auth,
        $mailer
    );
    $pwControl = new PasswordController($servicePw);
    
    $twoFaRepo = new TwoFaRepository($pdo);
    $usuario = new UsuarioController($servicio, $mailer);
    $admin = new AdminController($repo);
    $empleadoRepo = new EmpleadoRepositoryPg($pdo);
    $empleadoController = new EmpleadoController($empleadoRepo, $auth);
    $viajeRepo = new ViajeProximoRepositoryPg($pdo);
    $viajeCtrl = new ViajeProximoController($viajeRepo);
    $reportCtrl = new ReportController();


    // Ruta y método HTTP actuales
    $route = $_GET['route'] ?? 'home';
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    switch ($route) {

        //Home
        case 'home':

            $viajesProximos = $viajeRepo->listarTodos();

            $proximo = null;
            if (!empty($viajesProximos)) {
                usort($viajesProximos, function($a, $b) {
                    return strtotime($a['fecha_salida_proximo']) <=> strtotime($b['fecha_salida_proximo']);
            });
            $proximo = $viajesProximos[0];
            }
            require_once __DIR__ . '/../app/presentation/views/home.php';
            break;

        //Inicio de Sessión
        case 'login' :
            require_once __DIR__ . '/../app/presentation/views/login.php';
            break;
        case 'api/login' :
            $usuario->login();
            break;

        //Creación de usuario
        case 'registrar' :
            if ($method === 'GET') {
                require_once __DIR__ . '/../app/presentation/views/registrar.php';
            } else {
                $usuario->registrar();
            }
            break;
        case 'verificarCodigo':
            if ($method === 'GET') {
                require_once __DIR__ . '/../app/presentation/views/verificar-codigo.php';
        } else { 
            $usuario->verificarCodigo();}
        break;
        
        //Cerrar sessión
        case 'logout':
            $usuario->logout();
        break;

        //Metodos para cambiar contraseña
        case 'request_reset' :
            if ($method === 'GET') {
                $pwControl->solicitarForm();
            } else {
                $pwControl->requestForm();
            }
            break;


        case 'reset_password' :
            if ($method === 'GET') {
                $pwControl->resetForm();
            } else {
                $pwControl->perfomReset();
            }
            break;

        case 'agendaPaquete':
            if (empty($_SESSION['user'])) {
                header('Location: index.php?route=login');
                exit;
            }
            require_once __DIR__ . '/../app/presentation/views/agendaPaquete.php';
            break;
        
        case 'cotizaEnvio' :
                require_once __DIR__ . '/../app/presentation/views/cotizarPaquete.php';
        break;

        //Herramientas de admin
        case 'admin/listarUsuarios':
            if ($method == 'GET') {
                $admin->listarUsuarios();
            }
        break;
        case 'admin/desarchivarUsuario':
            if ($method == 'POST') {
                $admin->desarchivar();
            }
            break;
            
    // 1) Registrar empleado
    case 'empleado/registrar':
        if ($method === 'GET') {
            $empleadoController->mostrarFormularioRegistro();
        } else {
            $empleadoController->registrarEmpleado();
        }
        break;
    // 2) Listar empleados (filtro: activos/archivados/todos)
    case 'empleado/listar':
        if ($method === 'GET') {
            $empleadoController->listarEmpleados();
        }
        break;

    // 3) Desactivar (soft delete) – marcado archived = TRUE
    case 'empleado/desactivar':
        if ($method === 'POST') {
            $empleadoController->desactivar();
        }
        break;

    // 4) Activar de nuevo (archived = FALSE)
    case 'empleado/activar':
        if ($method === 'POST') {
            $empleadoController->activar();
        }
        break;

    // 5) Login empleado
    case 'empleado/login':
        if ($method === 'GET') {
            $empleadoController->mostrarLoginEmpleado();
        } else {
            $empleadoController->loginEmpleado();
        }
        break;

    // 6) Logout empleado
    case 'empleado/logout':
            $empleadoController->logoutEmpleado();
        break;

    // 7) Dashboard/área privada del empleado
    case 'empleado/dashboard':
        if ($method === 'GET') {
            $empleadoController->dashboardEmpleado();
        }
        break;
    // 8) Editar perfil del empleado
    case 'empleado/editar':
        if ($method === 'GET') {
            $empleadoController->mostrarFormularioEdicion($_GET['id_empleado']);
        } else { 
            $empleadoController->guardarEdicion();        
        }
        break;
        //CASOS PARA EL CONTROLADOR DE VIAJES
        case 'proximosViajes' :
            if ($method === 'GET') {
                $viajeCtrl->listarViajesParaUsuario();
            }
        break;
        case 'admin/listarViajeProximo' :
            if ($method === 'GET') {
                $viajeCtrl->listarViajeProximo();
            };
        break;
        case 'admin/nuevoViajeProximo' :
            if ($method === 'GET') {
                $viajeCtrl->mostrarFormularioCrear();
            }
        break;
        case 'admin/guardarViajeProximo' :
            if ($method === 'POST') {
                $viajeCtrl->guardarViajeProximo();
            }
        case 'admin/editarViajeProximo':
            if ($method === 'GET') {
                $viajeCtrl->editarViajeProximo();
            }
            break;
        case 'admin/actualizarViajeProximo':
            if ($method === 'POST') {
                $viajeCtrl->actualizarViajeProximo();
            }
            break;
        case 'admin/eliminarViajeProximo' :
            if ($method === 'GET') {
                $viajeCtrl->eliminarViajeProximo();
            }
            break;
 // Reportes de usuarios
    case 'admin/exportarUsuariosListadoCsv':
        if ($method === 'GET') {
            $reportCtrl->exportUsuariosListadoCsv();
        }
        break;

    case 'admin/exportarUsuariosListadoPdf':
        if ($method === 'GET') {
            $reportCtrl->exportUsuariosListadoPdf();
        }
        break;

    case 'admin/exportarUsuariosNuevosCsv':
        if ($method === 'GET') {
            $reportCtrl->exportUsuariosNuevosCsv();
        }
        break;

    case 'admin/exportarUsuariosNuevosPdf':
        if ($method === 'GET') {
            $reportCtrl->exportUsuariosNuevosPdf();
        }
        break;

    case 'admin/exportarUsuariosHistorialLoginsCsv':
        if ($method === 'GET') {
            $reportCtrl->exportUsuariosHistorialLoginsCsv();
        }
        break;

    case 'admin/exportarUsuariosHistorialLoginsPdf':
        if ($method === 'GET') {
            $reportCtrl->exportUsuariosHistorialLoginsPdf();
        }
        break;

    case 'admin/exportarUsuariosMasActivosCsv':
        if ($method === 'GET') {
            $reportCtrl->exportUsuariosMasActivosCsv();
        }
        break;

    case 'admin/exportarUsuariosMasActivosPdf':
        if ($method === 'GET') {
            $reportCtrl->exportUsuariosMasActivosPdf();
        }
        break;

    // Reporte de empleados
    case 'admin/exportarEmpleadosListadoCsv':
        if ($method === 'GET') {
            $reportCtrl->exportEmpleadosListadoCsv();
        }
        break;

    case 'admin/exportarEmpleadosListadoPdf':
        if ($method === 'GET') {
            $reportCtrl->exportEmpleadosListadoPdf();
        }
        break;

    case 'admin/exportarEmpleadosAntiguedadCsv':
        if ($method === 'GET') {
            $reportCtrl->exportEmpleadosAntiguedadCsv();
        }
        break;

    case 'admin/exportarEmpleadosAntiguedadPdf':
        if ($method === 'GET') {
            $reportCtrl->exportEmpleadosAntiguedadPdf();
        }
        break;

    case 'admin/exportarEmpleadosSalariosCsv':
        if ($method === 'GET') {
            $reportCtrl->exportEmpleadosSalariosCsv();
        }
        break;

    case 'admin/exportarEmpleadosSalariosPdf':
        if ($method === 'GET') {
            $reportCtrl->exportEmpleadosSalariosPdf();
        }
        break;

    case 'admin/exportarEmpleadosAniversariosCsv':
        if ($method === 'GET') {
            $reportCtrl->exportEmpleadosAniversariosCsv();
        }
        break;

    case 'admin/exportarEmpleadosAniversariosPdf':
        if ($method === 'GET') {
            $reportCtrl->exportEmpleadosAniversariosPdf();
        }
        break;
    case 'seguimientoPaquete' :
        if (empty($_SESSION['user'])) {
            header('Location: index.php?route=login');
            exit;
    }
        require_once __DIR__ . '/../app/presentation/views/seguimientoPaquete.php';
        break;
        default:
            echo "Ruta no encontrada";
        //case '' :
            //require_once __DIR__ . '';
            //break;

    }

?>