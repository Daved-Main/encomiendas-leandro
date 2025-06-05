<?php
use app\infrastructure\database\DatabaseConnect;

$codigo = $_GET['codigo_rastreo'] ?? null;
$seguimiento = null;

if ($codigo) {
    $pdo = DatabaseConnect::getInstance();
    $stmt = $pdo->prepare("
        SELECT sp.*, p.fecha_registro 
        FROM seguimiento_paquete sp
        JOIN paquete p ON sp.id_paquete = p.id_paquete
        WHERE p.codigo_rastreo = :codigo
        LIMIT 1
    ");
    $stmt->execute([':codigo' => $codigo]);
    $seguimiento = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Seguimiento de Paquete</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="/app/presentation/views/iu/style.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

    <?php include __DIR__ . '/iu/navbar.php'; ?>


  <div class="bg-gradient-to-r from-[#1E90FF] via-[#B3DAFF] to-[#A680FF] py-10 text-center">
    <h2 class="text-3xl md:text-4xl font-bold text-white">Movemos tus encomiendas<br> con precisión y confianza.</h2>
  </div>

  <div class="max-w-4xl mx-auto px-6 py-10">
    <h1 class="text-4xl font-bold text-center mb-6">Consulta el estado de tu paquete</h1>
    <p class="text-center mb-10">Ingresa tu código de seguimiento para ver la ruta del envío</p>

    <!-- Formulario -->
    <form method="GET" action="index.php" class="bg-white shadow p-6 rounded-lg mb-10">
      <input type="hidden" name="route" value="seguimientoPaquete">
      <label for="codigo" class="block text-sm font-medium text-gray-600 mb-1">Código de seguimiento</label>
      <div class="flex gap-4 items-end">
        <input
          type="text"
          id="codigo"
          name="codigo_rastreo"
          placeholder="Ej: PKG-2025-06-1-0001"
          class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        >
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium">
          Revisar estado
        </button>
      </div>
    </form>

    <!-- Seguimiento -->
    <?php if ($seguimiento): ?>
    <div class="bg-white p-6 rounded-lg shadow">
      <h2 class="text-xl font-semibold mb-6">Historial de seguimiento</h2>
      <div class="space-y-8">

        <?php if ($seguimiento['solicitud_registrada']): ?>
          <?= paso("Solicitud registrada", $seguimiento['fecha_entrega'], "fa-check", "green") ?>
        <?php endif; ?>

        <?php if ($seguimiento['paquete_recibido']): ?>
          <?= paso("Paquete recibido", $seguimiento['fecha_entrega'], "fa-box", "green") ?>
        <?php endif; ?>

        <?php if ($seguimiento['verificacion']): ?>
          <?= paso("Verificación completada", $seguimiento['fecha_entrega'], "fa-clipboard-check", "green") ?>
        <?php endif; ?>

        <?php if ($seguimiento['en_transito']): ?>
          <?= paso("En tránsito hacia destino", $seguimiento['fecha_entrega'], "fa-truck", "blue") ?>
        <?php endif; ?>

        <?php if ($seguimiento['paquete_entregado']): ?>
          <?= paso("Paquete entregado", $seguimiento['fecha_entrega'], "fa-box-open", "gray") ?>
        <?php endif; ?>

      </div>
    </div>
    <?php elseif (isset($_GET['codigo_rastreo'])): ?>
      <div class="text-center text-red-500 mt-10">❌ Código no encontrado o sin seguimiento registrado.</div>
    <?php endif; ?>
  </div>
</body>

<?php
function paso($titulo, $fecha, $icono, $color) {
  $colores = [
    'green' => 'bg-green-500',
    'blue' => 'bg-blue-500',
    'gray' => 'bg-gray-400',
  ];
  return <<<HTML
  <div class="flex items-start">
    <div class="flex flex-col items-center mr-4">
      <div class="w-8 h-8 flex items-center justify-center {$colores[$color]} rounded-full text-white">
        <i class="fas {$icono}"></i>
      </div>
      <div class="w-px h-full bg-gray-300 mt-2"></div>
    </div>
    <div class="flex-grow">
      <div class="flex justify-between items-start">
        <h3 class="font-medium text-gray-800">{$titulo}</h3>
        <span class="text-sm text-gray-500">{$fecha}</span>
      </div>
    </div>
  </div>
HTML;
}
?>

<?php include __DIR__ . '/iu/footer.php'; ?>
</html>
