<?php
require_once 'conexion.php';

class Rol {
    private ConexionBD $db;

    public function __construct() {
        $this->db = new ConexionBD(); // Instancia la clase de conexion
    }

    public function obtenerRoles() {
        $con = $this->db->obtenerConexion(); // Llama al metodo de conexion
        $query = "SELECT * FROM roles";
        $resultado = $con->query($query); // Ejecuta la consulta SQL
        
        $lista = array(); // Arreglo vacio para guardar los registros
        while ($fila = $resultado->fetch_assoc()) { // Ciclo while para iterar cada registro encontrado
            $lista[] = $fila; // Agrega el registro al arreglo
        }
        
        $con->close(); // Cierra la conexion activa
        return $lista; // Devuelve el arreglo con los roles
    }
}
?>