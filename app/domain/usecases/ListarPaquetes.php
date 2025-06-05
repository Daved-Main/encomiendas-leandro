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

    public function execute(): ListarPaquetes
    {
        // Usamos directamente el método que no requiere parámetros
        $paquetes = $this->repository->listarTodos();

        $this->setData($paquetes);

        return $this;
    }

    protected function transform(): array
    {
        return $this->getAttributes(); // Podés aplicar filtros si después lo necesitás
    }
}
