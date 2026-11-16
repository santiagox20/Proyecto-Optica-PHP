<?php
session_start(); // Activamos sesion
if (!isset($_SESSION['usuario'])) { // Validamos que haya sesion iniciada
    header("Location: index.php");
    exit();
}

require_once 'paciente.php'; // Importamos modelo Paciente para mostrar los datos del paciente atendido
require_once 'historia.php'; // Importamos modelo HistoriaClinica para guardar la consulta

$pac_id = $_GET['id']; // Capturamos el ID del paciente que viene en la URL desde el boton Crear Historia
$objPaciente = new Paciente(); // Instanciamos Paciente
$paciente = $objPaciente->obtenerPacientePorId($pac_id); // Traemos los datos del paciente para mostrarlos arriba del formulario

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Verificamos si el optometra envio el formulario (POST)
    $mot = $_POST['motivo']; // Capturamos motivo de visita
    $esf_od = $_POST['esf_od']; // Capturamos esfera ojo derecho
    $cil_od = $_POST['cil_od']; // Capturamos cilindro ojo derecho
    $eje_od = $_POST['eje_od']; // Capturamos eje ojo derecho
    $esf_oi = $_POST['esf_oi']; // Capturamos esfera ojo izquierdo
    $cil_oi = $_POST['cil_oi']; // Capturamos cilindro ojo izquierdo
    $eje_oi = $_POST['eje_oi']; // Capturamos eje ojo izquierdo
    $diag_od = $_POST['diag_od']; // Capturamos diagnostico ojo derecho
    $diag_oi = $_POST['diag_oi']; // Capturamos diagnostico ojo izquierdo
    $rec = $_POST['recom']; // Capturamos recomendaciones generales

    $objHistoria = new HistoriaClinica(); // Instanciamos HistoriaClinica
    // Llamamos al metodo guardarHistoria enviando el $pac_id de la URL y todos los datos clinicos del POST
    if ($objHistoria->guardarHistoria($pac_id, $mot, $esf_od, $cil_od, $eje_od, $esf_oi, $cil_oi, $eje_oi, $diag_od, $diag_oi, $rec)) {
        echo "<script>
                alert('Historia clinica registrada con éxito...');
                window.location.href = 'pantalla_principal.php?vista=historias';
              </script>"; // Si guarda con exito, redirigimos directo a la pestana de historias clinicas
    } else {
        echo "<script>alert('Ocurrio un error en el registro');</script>"; // Alerta en caso de fallo en MySQL
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Historia Clinica</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="contenedor-form" style="width: 600px;">
        <h2>Historia Clinica</h2>
        <!-- Mostramos en azul la informacion basica del paciente al que se le esta creando la historia -->
        <p style="color: blue;">
            Doc: <?php echo $paciente['pac_docum']; ?> | Paciente: <?php echo $paciente['pac_nombre']." ".$paciente['pac_apellido']; ?><br>
            Teléfono: <?php echo $paciente['pac_telefono']; ?> | Género: <?php echo $paciente['gen_nombre']; ?> | Estrato: <?php echo $paciente['estr_nombre']; ?>
        </p>
        <form method="POST"> <!-- Formulario para digitar la formula optometrica -->
            <div class="campo-form"><label>Motivo de Visita:</label><input type="text" name="motivo"></div>

            <h4>Ojo Derecho (OD)</h4>
            <div class="campo-form"><label>Esfera OD:</label><input type="text" name="esf_od"></div>
            <div class="campo-form"><label>Cilindro OD:</label><input type="text" name="cil_od"></div>
            <div class="campo-form"><label>Eje OD:</label><input type="text" name="eje_od"></div>
            <div class="campo-form"><label>Diagnóstico OD:</label><input type="text" name="diag_od"></div>

            <h4>Ojo Izquierdo (OI)</h4>
            <div class="campo-form"><label>Esfera OI:</label><input type="text" name="esf_oi"></div>
            <div class="campo-form"><label>Cilindro OI:</label><input type="text" name="cil_oi"></div>
            <div class="campo-form"><label>Eje OI:</label><input type="text" name="eje_oi"></div>
            <div class="campo-form"><label>Diagnóstico OI:</label><input type="text" name="diag_oi"></div>

            <div class="campo-form"><label>Recomendaciones:</label><input type="text" name="recom"></div>

            <button type="submit" class="btn-naranja">Registar Historia Clinica</button>
        </form>
    </div>
</body>
</html>