<?php
require_once 'conexion.php';

class Estrato {
    private ConexionBD $db;

    public function __construct() {
        $this->db = new ConexionBD(); // Instancia la clase de conexion
    }

    public function obtenerEstratos() {
        $con = $this->db->obtenerConexion(); // Conecta con la BD
        $query = "SELECT estr_id, estr_nombre FROM estratos";
        $resultado = $con->query($query); // Consulta SQL
        
        $lista = array(); // Arreglo de estratos
        while ($fila = $resultado->fetch_assoc()) { // Recorre los resultados de la consulta
            $lista[] = $fila;
        }
        
        $con->close(); // Cierra la conexion
        return $lista; // Retorna los estratos
    }
}
?>