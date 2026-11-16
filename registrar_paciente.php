<?php
session_start(); // Iniciamos sesion
if (!isset($_SESSION['usuario'])) { // Validamos que haya un usuario logueado
    header("Location: index.php"); // Si no hay sesion, lo mandamos al login
    exit();
}

require_once 'genero.php'; // Importamos modelo Genero
require_once 'estrato.php'; // Importamos modelo Estrato
require_once 'hobby.php'; // Importamos modelo Hobby
require_once 'paciente.php'; // Importamos modelo Paciente

$objGenero = new Genero(); // Instanciamos Genero
$generos = $objGenero->obtenerGeneros(); // Cargamos lista de generos para el select

$objEstrato = new Estrato(); // Instanciamos Estrato
$estratos = $objEstrato->obtenerEstratos(); // Cargamos lista de estratos para los radio buttons

$objHobby = new Hobby(); // Instanciamos Hobby
$hobbies = $objHobby->obtenerHobbies(); // Cargamos lista de hobbies para los checkboxes

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Verificamos si se envio el formulario de registro
    $doc = $_POST['documento']; // Capturamos documento
    $nom = $_POST['nombre']; // Capturamos nombre
    $ape = $_POST['apellido']; // Capturamos apellido
    $dir = $_POST['direccion']; // Capturamos direccion
    $tel = $_POST['telefono']; // Capturamos telefono
    $cor = $_POST['correo']; // Capturamos correo
    $gen_id = $_POST['genero']; // Capturamos ID del genero elegido
    $estr_id = $_POST['estrato']; // Capturamos ID del estrato elegido
    // Si marcaron algun checkbox recibimos el arreglo; si no marcaron ninguno, asignamos un array vacio para evitar errores
    $hobbies_sel = isset($_POST['hobbies']) ? $_POST['hobbies'] : array();

    if (empty($doc) || empty($nom) || empty($ape)) { // Validamos que al menos documento, nombre y apellido esten llenos
        echo "<script>alert('Por favor, digite el documento, nombre y apellido del paciente....');</script>";
    } else {
        $objPaciente = new paciente(); // Instanciamos el modelo Paciente
        // Llamamos a guardarPaciente pasando todos los datos incluyendo el arreglo de hobbies
        if ($objPaciente->guardarPaciente($doc, $nom, $ape, $dir, $tel, $cor, $gen_id, $estr_id, $hobbies_sel)) {
            echo "<script>
                    alert('Paciente registrado exitosamente...');
                    window.location.href = 'pantalla_principal.php';
                </script>"; // Si guarda bien, avisamos y volvemos a la tabla principal
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Paciente</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="contenedor-form" style="width: 500px;">
        <h2>Registro de Paciente</h2>
        <form method="POST" action="registrar_paciente.php"> <!-- Formulario que envia datos por POST -->
            <div class="campo-form"><label>Documento:</label><input type="text" name="documento"></div>
            <div class="campo-form"><label>Nombre:</label><input type="text" name="nombre"></div>
            <div class="campo-form"><label>Apellido:</label><input type="text" name="apellido"></div>
            <div class="campo-form"><label>Direccion:</label><input type="text" name="direccion"></div>
            <div class="campo-form"><label>Telefono:</label><input type="text" name="telefono"></div>
            <div class="campo-form"><label>Correo:</label><input type="text" name="correo"></div>

            <div class="campo-form">
                <label>Genero:</label>
                <select name="genero"> <!-- Lista desplegable de seleccion unica -->
                    <?php foreach ($generos as $g) { echo "<option value='".$g['gen_id']."'>".$g['gen_nombre']."</option>"; } // Pintamos cada genero desde la BD ?>
                </select>
            </div>

            <div class="campo-form">
                <label>Estrato (Radiobuttons):</label><br>
                <?php 
                foreach ($estratos as $e) { // Pintamos botones de radio con el mismo name='estrato' para que solo dejen elegir uno
                    echo "<input type='radio' name='estrato' value='".$e['estr_id']."' checked> ".$e['estr_nombre']." "; 
                } 
                ?>
            </div>

            <div class="campo-form">
                <label>Hobbies:</label><br>
                <?php 
                foreach ($hobbies as $h) { // Pintamos checkboxes con name='hobbies[]' en corchetes para enviar multiples opciones como arreglo
                    echo "<input type='checkbox' name='hobbies[]' value='".$h['hob_id']."'> ".$h['hob_nombre']." "; 
                } 
                ?>
            </div>

            <button type="submit" class="btn-naranja">Registar Paciente</button>
        </form>
    </div>
</body>
</html>