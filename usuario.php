<?php
require_once 'conexion.php'; // Importamos el archivo de conexion una sola vez

class Usuario { // Clase modelo para gestionar todo lo relacionado con los usuarios del sistema
    private ConexionBD $db; // Atributo privado donde guardaremos el objeto de conexion

    public function __construct() { // Constructor que se ejecuta automaticamente al hacer new Usuario()
        $this->db = new ConexionBD(); // Preparamos la instancia de conexion apenas nace el objeto
    }

    public function validarLogin(string $docum, string $clave, int $rol_id) { // Metodo que recibe los 3 datos del login para verificarlos en la BD
        $con = $this->db->obtenerConexion(); // Abrimos la conexion con MySQL
        
        // Armamos la consulta cruzando usuarios y roles para traer el nombre completo y el nombre del cargo
        $query = "SELECT u.usu_nombre, u.usu_apellido, r.rol_nombre 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.rol_id 
                  WHERE u.usu_docum = '$docum' AND u.usu_clave = '$clave' AND r.rol_id = '$rol_id'";
        
        $resultado = $con->query($query); // Ejecutamos la consulta SQL en la base de datos
        $usuario = $resultado->fetch_assoc(); // Extraemos la fila encontrada en forma de arreglo asociativo (si no existe devuelve null)
        
        $con->close(); // Cerramos la conexion para liberar memoria del servidor
        return $usuario; // Devolvemos los datos del usuario a index.php para que decida si lo deja entrar
    }
}
?>