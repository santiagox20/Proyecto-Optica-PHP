<?php
session_start(); // Iniciamos o recuperamos la sesion para poder guardar variables globales del usuario
require_once 'rol.php'; // Traemos la clase Rol para cargar el desplegable
require_once 'usuario.php'; // Traemos la clase Usuario para validar las credenciales

$mensaje = ""; // Inicializamos vacia la variable que guardara las alertas en JavaScript

$objRol = new Rol(); // Instanciamos la clase Rol
$roles = $objRol->obtenerRoles(); // Pedimos todos los roles de la BD para pintarlos en el HTML

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Verificamos si el usuario presiono el boton Ingresar (envio por POST)
    $docum = $_POST['documento']; // Capturamos el numero de documento escrito en el input
    $clave = $_POST['clave']; // Capturamos la clave escrita en el input
    $rol_id = $_POST['rol']; // Capturamos el ID del rol seleccionado en el select

    if (empty($docum) || empty($clave) || empty($rol_id)) { // Validamos que ningun campo venga vacio
        $mensaje = "<script>alert('Credenciales incompletas. Intente nuevamente');</script>"; // Preparamos alerta si falta algun dato
    } else { // Si todos los campos tienen datos, pasamos a verificar en la base de datos
        $objUsuario = new Usuario(); // Instanciamos la clase Usuario
        $usuario = $objUsuario->validarLogin($docum, $clave, $rol_id); // Llamamos al metodo que consulta en MySQL

        if ($usuario) { // Si $usuario trae datos, significa que el login fue exitoso
            $nombre_completo = $usuario['usu_nombre'] . " " . $usuario['usu_apellido']; // Concatenamos nombre y apellido
            $_SESSION['usuario'] = $nombre_completo; // Guardamos el nombre en la sesion para mostrarlo en las demas pantallas
            $_SESSION['rol'] = $usuario['rol_nombre']; // Guardamos el rol en la sesion
            
            // Mostramos alerta de bienvenida y redirigimos a la pantalla principal
            echo "<script>
                    alert('Bienvenido a la optica Sr. " . $nombre_completo . "');
                    window.location.href = 'pantalla_principal.php';
                  </script>";
            exit(); // Cortamos la ejecucion del script para que no siga cargando nada mas
        } else { // Si $usuario vino vacio o null, los datos no coinciden
            $mensaje = "<script>alert('Credenciales de acceso incorrectas. Intente nuevamente');</script>"; // Alerta de error
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesion - Optica</title>
    <link rel="stylesheet" href="estilos.css"> <!-- Enlazamos nuestra hoja de estilos -->
</head>
<body>
    <?php echo $mensaje; // Si hubo algun error arriba, aqui se imprime y ejecuta el script de alerta ?>
    <div class="contenedor-form">
        <h2 style="color: #FF6600; text-align: center;">Inicio de Sesion</h2>
        <form method="POST" action="index.php"> <!-- Formulario que envia los datos ocultos por POST a este mismo archivo -->
            <div class="campo-form">
                <label>Documento</label>
                <input type="text" name="documento"> <!-- Caja de texto para el documento -->
            </div>
            <div class="campo-form">
                <label>Password</label>
                <input type="password" name="clave"> <!-- Caja tipo password para ocultar los caracteres -->
            </div>
            <div class="campo-form">
                <label>Rol de Trabajo</label>
                <select name="rol"> <!-- Lista desplegable que se llena dinamicamente desde la BD -->
                    <option value="">-- Seleccione --</option>
                    <?php 
                    foreach ($roles as $r) { // Recorremos el arreglo de roles traido de MySQL
                        echo "<option value='" . $r['rol_id'] . "'>" . $r['rol_nombre'] . "</option>"; // Creamos cada opcion con su ID y nombre
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn-naranja">Ingresar</button> <!-- Boton que dispara el envio del formulario -->
        </form>
    </div>
</body>
</html>