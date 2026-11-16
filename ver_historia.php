<?php
session_start(); // Activamos sesion
if (!isset($_SESSION['usuario'])) { // Verificamos que el usuario este autenticado
    header("Location: index.php");
    exit();
}

require_once 'historia.php'; // Importamos el modelo HistoriaClinica

$hist_id = $_GET['id']; // Capturamos el ID de la historia clinica que viene en la URL
$objHistoria = new HistoriaClinica(); // Instanciamos HistoriaClinica
$h = $objHistoria->obtenerHistoriaDetalle($hist_id); // Traemos todos los datos de esa historia especifica
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Historia Clinica</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <!-- Vista de solo lectura (sin inputs ni formularios) para imprimir o revisar el diagnostico -->
    <div class="contenedor-form">
        <h2>Informacion Historia Clinica</h2>
        <p><strong>No. Historia:</strong> <?php echo $h['hist_id']; ?> | <strong>Paciente:</strong> <?php echo $h['paciente']; ?> | <strong>Género:</strong> <?php echo $h['gen_nombre']; ?></p>
        <p><strong>Motivo Visita:</strong> <?php echo $h['hist_motv']; ?></p>

        <hr>
        <h4>Ojo Derecho (OD)</h4>
        <p>Esfera: <?php echo $h['hist_esfod']; ?> | Cilindro: <?php echo $h['hist_cilod']; ?> | Eje: <?php echo $h['hist_ejeod']; ?></p>
        <p>Diagnóstico: <?php echo $h['hist_diaod']; ?></p>

        <hr>
        <h4>Ojo Izquierdo (OI)</h4>
        <p>Esfera: <?php echo $h['hist_esfoi']; ?> | Cilindro: <?php echo $h['hist_ciloi']; ?> | Eje: <?php echo $h['hist_ejeoi']; ?></p>
        <p>Diagnóstico: <?php echo $h['hist_diaoi']; ?></p>

        <hr>
        <p><strong>Recomendaciones:</strong> <?php echo $h['hist_recom']; ?></p>

        <!-- Enlace para regresar directamente a la tabla de historias clinicas -->
        <a href="pantalla_principal.php?vista=historias" class="btn-naranja" style="display:block; text-align:center; text-decoration:none;">Volver</a>
    </div>
</body>
</html>