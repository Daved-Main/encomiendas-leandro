<?php
// app/domain/usecases/ListarPaquetes.php

namespace app\domain\usecases;

use app\domain\BaseCase;
use app\domain\repositories\PaqueteRepository;

class ListarPaquetes extends BaseCase
{
    private PaqueteRepository $repository;

    public function __construct(PaqueteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(): BaseCase
    {
        $paquetes = $this->repository->listarTodos();

        // Validar y asegurarse que se asignen datos
        if (!is_array($paquetes)) {
            throw new \RuntimeException("La lista de paquetes no es un array válido.");
        }

        $this->setData($paquetes);
        return $this;
    }

    protected function transform(): array
    {
        return $this->getAttributes();
    }
}