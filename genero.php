<?php
require_once 'conexion.php';

class Genero {
    private ConexionBD $db;

    public function __construct() {
        $this->db = new ConexionBD(); // Instancia la clase de conexion
    }

    public function obtenerGeneros() {
        $con = $this->db->obtenerConexion(); // Obtiene la conexion
        $query = "SELECT gen_id, gen_nombre FROM generos";
        $resultado = $con->query($query); // Ejecuta la consulta
        
        $lista = array(); // Arreglo para almacenar generos
        while ($fila = $resultado->fetch_assoc()) { // Ciclo para recorrer filas
            $lista[] = $fila; // Agrega al arreglo
        }
        
        $con->close(); // Cierra conexion
        return $lista; // Devuelve el listado
    }
}
?>