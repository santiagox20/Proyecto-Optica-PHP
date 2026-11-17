<?php
session_start(); // Activamos el manejo de sesiones en esta vista
if (!isset($_SESSION['usuario'])) { // Filtro de seguridad: si no existe la variable de sesion, no ha iniciado sesion
    header("Location: index.php"); // Lo devolvemos a la fuerza al login
    exit(); // Detenemos el codigo para que no vea nada de esta pagina
}

require_once 'paciente.php'; // Importamos el modelo Paciente
require_once 'historia.php'; // Importamos el modelo HistoriaClinica

$objPaciente = new Paciente(); // Instanciamos el objeto Paciente

// Procesa la anulacion  antes de consultar pacientes o imprimir HTML
if (isset($_GET['anular_id'])) {
    $id_anular = (int) $_GET['anular_id']; // Convierte el ID a numero entero
    $objPaciente->anularPaciente($id_anular); // Llama al metodo que cambia el estado a Inactivo
    header("Location: pantalla_principal.php"); // Recarga la pagina limpia sin el parametro en la URL
    exit();
}

$pacientes = $objPaciente->obtenerPacientesActivos(); // Traemos solo los pacientes que tienen estado Activo

$objHistoria = new HistoriaClinica(); // Instanciamos el objeto HistoriaClinica
$historias = $objHistoria->obtenerHistoriasTodas(); // Traemos todas las historias clinicas registradas

// Operador ternario: si viene ?vista= en la URL usamos ese valor, si no, mostramos 'pacientes' por defecto
$vista = isset($_GET['vista']) ? $_GET['vista'] : 'pacientes';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Optica Sena - Pantalla Principal</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <div class="cabecera">
        <!-- Menu lateral desplegable hecho solo con HTML y CSS usando un checkbox oculto -->
        <div class="menu-hamburguesa">
            <input type="checkbox" id="toggle-menu"> <!-- Interruptor invisible que controla si el menu abre o cierra -->
            <label for="toggle-menu" class="icono-hamburguesa">&#9776;</label> <!-- Icono de las 3 rayitas que activa el checkbox -->

            <div class="panel-lateral"> <!-- Menu que se desliza desde la izquierda con las 3 opciones principales -->
                <a href="registrar_paciente.php" class="btn-naranja">Registrar Paciente</a>
                <a href="pantalla_principal.php?vista=pacientes" class="btn-cyan">Consultar Pacientes</a>
                <a href="pantalla_principal.php?vista=historias" class="btn-cyan">Consultar Historias</a>
            </div>
        </div>

        <h2>Bienvenidos a la Optica Sena</h2>

        <div>
            <span>Usuario conectado: <?php echo $_SESSION['usuario']; ?> - Rol:
                <?php echo $_SESSION['rol']; ?></span> <!-- Imprimimos el nombre y cargo guardados en la sesion -->
            <a href="logout.php" style="color: white; margin-left: 15px;">Cerrar Sesion</a> <!-- Enlace al archivo que destruye la sesion -->
        </div>
    </div>


    <?php if ($vista == 'pacientes'): // Si la vista activa es pacientes, dibujamos la tabla de pacientes 
    ?>
        <h3 style="margin-left: 20px;">Listado de Pacientes</h3>
        <table>
            <thead>
                <tr>
                    <th>DOCUMENTO</th>
                    <th>NOMBRE PACIENTE</th>
                    <th>TELEFONO</th>
                    <th>GENERO</th>
                    <th>ESTRATO</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($pacientes as $p) { // Recorremos cada paciente activo para crear su fila en la tabla
                    echo "<tr>";
                    echo "<td>" . $p['pac_docum'] . "</td>"; // Mostramos documento
                    echo "<td>" . $p['nom'] . "</td>"; // Mostramos nombre completo concatenado desde SQL
                    echo "<td>" . $p['pac_telefono'] . "</td>"; // Mostramos telefono
                    echo "<td>" . $p['gen_nombre'] . "</td>"; // Mostramos nombre del genero traido con INNER JOIN
                    echo "<td>" . $p['estr_nombre'] . "</td>"; // Mostramos nombre del estrato traido con INNER JOIN
                    echo "<td>
                            <a href='crear_historia.php?id=" . $p['pac_id'] . "' class='btn-cyan'>Crear Historia</a>
                            <a href='editar_paciente.php?id=" . $p['pac_id'] . "' class='btn-yellow'>Editar</a>
                            <a href='pantalla_principal.php?anular_id=" . $p['pac_id'] . "' onclick=\"return confirm('Realmente desea anular el paciente. Esta accion no se puede deshacer...');\" class='btn-red'>Anular</a>
                        </td>"; // Pasamos el ID del paciente por GET en los botones de accion; Anular pide confirmacion con JS
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    <?php else: // Si la vista no es pacientes (es decir, vista=historias), dibujamos la tabla de historias clinicas 
    ?>
        <h3 style="margin-left: 20px;">Listado de Historias Clinicas</h3>
        <table>
            <thead>
                <tr>
                    <th>No. Historia</th>
                    <th>NOMBRE PACIENTE</th>
                    <th>MOTIVO DE VISITA</th>
                    <th>RECOMENDACIONES</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($historias as $h) { // Recorremos todas las historias clinicas para pintar cada fila
                    echo "<tr>";
                    echo "<td>" . $h['hist_id'] . "</td>"; // Numero consecutivo de la historia
                    echo "<td>" . $h['paciente'] . "</td>"; // Nombre del paciente traido con INNER JOIN
                    echo "<td>" . $h['hist_motv'] . "</td>"; // Motivo de la consulta
                    echo "<td>" . $h['hist_recom'] . "</td>"; // Recomendaciones del optometra
                    echo "<td><a href='ver_historia.php?id=" . $h['hist_id'] . "' class='btn-cyan'>Ver Historia</a></td>"; // Enviamos el ID de la historia por GET para ver el detalle
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>

</html>