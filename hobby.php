<?php
require_once 'conexion.php';

class Hobby {
    private ConexionBD $db;

    public function __construct() {
        $this->db = new ConexionBD(); // Instancia la clase de conexion
    }

    public function obtenerHobbies() {
        $con = $this->db->obtenerConexion(); // Abre conexion
        $query = "SELECT hob_id, hob_nombre FROM hobbies";
        $resultado = $con->query($query); // Realiza la consulta
        
        $lista = array(); // Inicializa el arreglo
        while ($fila = $resultado->fetch_assoc()) { // Ciclo while para traer cada hobby
            $lista[] = $fila;
        }
        
        $con->close(); // Cierra conexion
        return $lista; // Devuelve el arreglo con los hobbies
    }
}
?>