<?php

    namespace app\presentation\controllers;

    use app\domain\repositories\UsuarioRepository;
    use app\infrastructure\database\UsuarioRepositoryPg;
    use app\domain\entities\Usuario;

    class AdminController {
        private UsuarioRepository $repo;
        private UsuarioRepository $usuario;

        public function __construct(UsuarioRepository $repo) {
            $this->repo = $repo;
        }

        public function listarUsuarios() : void{
            $estado = $_GET['estado'] ?? 'activos';

            switch ($estado){
                case 'archivados' :
                    $usuarios = $this->repo->listarInactivos();
                break;
                case 'todos':
                    $usuarios = $this->repo->listarTodos();
                break;
                case 'activos':
                default:
                    $usuarios = $this->repo->listarActivos();
                break;
            }

            require_once __DIR__ . "/../views/admin/listar-usuarios.php";
        }

        public function desarchivar() : void{
            $id = (int) ($_POST['id_user'] ?? 0);
            if ($id > 0) {
                $this->repo->desarchivar($id);
            }
        header("Location: index.php?route=admin/listarUsuarios&estado=todos");
        exit;
        }
            
        }

?>