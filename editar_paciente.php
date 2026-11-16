<?php
session_start(); // Activamos sesion
if (!isset($_SESSION['usuario'])) { // Protegemos la ruta contra accesos sin login
    header("Location: index.php");
    exit();
}

require_once 'paciente.php'; // Importamos modelo Paciente
require_once 'genero.php'; // Importamos modelo Genero
require_once 'estrato.php'; // Importamos modelo Estrato

$objPaciente = new Paciente(); // Instanciamos Paciente
$pac_id = $_GET['id']; // Capturamos el ID del paciente que viene en la URL desde el boton Editar
$paciente = $objPaciente->obtenerPacientePorId($pac_id); // Consultamos los datos actuales de ese paciente para precargar el formulario

$objGenero = new Genero(); // Instanciamos Genero
$generos = $objGenero->obtenerGeneros(); // Traemos todos los generos para armar el select

$objEstrato = new Estrato(); // Instanciamos Estrato
$estratos = $objEstrato->obtenerEstratos(); // Traemos todos los estratos para armar los radio buttons

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Si el usuario presiono Guardar Cambios (POST)
    $nom = $_POST['nombre']; // Capturamos el nuevo nombre
    $ape = $_POST['apellido']; // Capturamos el nuevo apellido
    $tel = $_POST['telefono']; // Capturamos el nuevo telefono
    $gen_id = $_POST['genero']; // Capturamos el nuevo genero
    $estr_id = $_POST['estrato']; // Capturamos el nuevo estrato

    // Ejecutamos el UPDATE en la base de datos usando el $pac_id de la URL y los nuevos valores
    if ($objPaciente->actualizarPaciente($pac_id, $nom, $ape, $tel, $gen_id, $estr_id)) {
        echo "<script>
                alert('Paciente editado exitosamente');
                window.location.href = 'pantalla_principal.php';
              </script>"; // Confirmamos la edicion y volvemos a la tabla principal
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Paciente</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="contenedor-form">
        <h2>Editar Paciente</h2>
        <form method="POST"> <!-- Envia por POST a esta misma pagina conservando el ?id= en la URL -->
            <!-- En cada input usamos value para mostrar el dato que ya estaba guardado en MySQL -->
            <div class="campo-form"><label>Nombre:</label><input type="text" name="nombre" value="<?php echo $paciente['pac_nombre']; ?>"></div>
            <div class="campo-form"><label>Apellido:</label><input type="text" name="apellido" value="<?php echo $paciente['pac_apellido']; ?>"></div>
            <div class="campo-form"><label>Telefono:</label><input type="text" name="telefono" value="<?php echo $paciente['pac_telefono']; ?>"></div>
            
            <div class="campo-form">
                <label>Genero:</label>
                <select name="genero">
                    <?php 
                    foreach ($generos as $g) { // Recorremos todos los generos
                        // Comparamos si el genero del ciclo es igual al que tiene el paciente para dejarlo marcado con 'selected'
                        $sel = ($g['gen_id'] == $paciente['gen_id']) ? "selected" : "";
                        echo "<option value='".$g['gen_id']."' $sel>".$g['gen_nombre']."</option>"; 
                    } 
                    ?>
                </select>
            </div>

            <div class="campo-form">
                <label>Estrato:</label><br>
                <?php 
                foreach ($estratos as $e) { // Recorremos todos los estratos
                    // Comparamos si el estrato del ciclo coincide con el del paciente para dejar el circulito marcado con 'checked'
                    $chk = ($e['estr_id'] == $paciente['estr_id']) ? "checked" : "";
                    echo "<input type='radio' name='estrato' value='".$e['estr_id']."' $chk> ".$e['estr_nombre']." "; 
                } 
                ?>
            </div>

            <button type="submit" class="btn-naranja">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>