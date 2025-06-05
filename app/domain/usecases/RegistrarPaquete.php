<?php
// app/domain/usecases/RegistrarPaquete.php

namespace app\domain\usecases;

use app\domain\BaseCase;
use app\domain\entities\Paquete;
use app\domain\repositories\PaqueteRepository;
use DateTime;
use InvalidArgumentException;

class RegistrarPaquete extends BaseCase
{
    private PaqueteRepository $repository;

    public function __construct(PaqueteRepository $repository)
    {
        $this->repository = $repository;
    }

public function execute(): RegistrarPaquete
{
    $datos = $this->getAttributes();

    if (!isset($datos['contenido_fragil']) || !in_array($datos['contenido_fragil'], ['Sí', 'No', 'Muy fragil'])) {
        throw new InvalidArgumentException("El campo 'contenido_fragil' debe ser 'Sí', 'No' o 'Muy fragil'");
    }

    if (!isset($datos['estado']) || !in_array($datos['estado'], ['Recibido', 'En camino', 'Entregado', 'Retenido'])) {
        $datos['estado'] = 'Recibido';
    }

    // 🔎 Buscar el id_viaje_actual usando id_viaje_mes, mes y año
    $idViajeMes = (int)$datos['id_viaje_mes'];
    $mes = (int)$datos['mes'];
    $anio = (int)$datos['anio'];

    $idViajeActual = $this->repository->obtenerIdViajeActualPorMesAnio($idViajeMes, $mes, $anio);

    if (!$idViajeActual) {
        throw new InvalidArgumentException("No se encontró viaje con id_viaje_mes=$idViajeMes para $mes/$anio");
    }

    // 🔢 Calcular número de paquete en ese viaje
    $numeroSecuencial = $this->repository->contarPaquetesPorViaje($idViajeActual) + 1;

    // 🧾 Generar código de rastreo
    $codigoRastreo = sprintf("PKG-%04d-%02d-%d-%04d", $anio, $mes, $idViajeMes, $numeroSecuencial);

    $paquete = new Paquete(
        id: null,
        idViajeActual: $idViajeActual,
        tipoPaquete: $datos['tipo_paquete'],
        nombreRemitente: $datos['nombre_remitente'],
        telefonoRemitente: $datos['telefono_remitente'],
        nombreDestinatario: $datos['nombre_destinatario'],
        telefonoDestinatario: $datos['telefono_destinatario'],
        ciudadDestino: $datos['ciudad_destino'],
        direccionDestino: $datos['direccion_destino'],
        nombreDelArticulo: $datos['nombre_del_articulo'],
        cantidadBultos: (int)$datos['cantidad_bultos'],
        peso: (float)$datos['peso'],
        alto: (float)$datos['alto'],
        ancho: (float)$datos['ancho'],
        contenidoFragil: $datos['contenido_fragil'],
        codigoRastreo: $codigoRastreo,
        estado: $datos['estado'],
        fechaRegistro: new \DateTime(),
        idUser: $_SESSION['user']['id_user']
    );

    $resultado = $this->repository->guardar($paquete);

    $this->setData([
        'guardado' => $resultado,
        'codigo_rastreo' => $codigoRastreo
    ]);

    return $this;
}


    protected function transform(): array
    {
        return $this->getAttributes(); // puedes sanitizar si quieres
    }
}
