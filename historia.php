<?php
require_once 'conexion.php'; // Importamos la clase de conexion

class HistoriaClinica { // Modelo encargado de guardar y consultar las historias clinicas optometricas
    private ConexionBD $db; // Atributo privado para la instancia de conexion

    public function __construct() { // Constructor de la clase
        $this->db = new ConexionBD(); // Inicializamos la conexion a MySQL
    }

    // Metodo que recibe el ID del paciente y toda su formula visual para guardarla en la tabla historias
    public function guardarHistoria(int $pac_id, string $mot, string $esf_od, string $cil_od, string $eje_od, string $esf_oi, string $cil_oi, string $eje_oi, string $diag_od, string $diag_oi, string $rec) {
        $con = $this->db->obtenerConexion(); // Abrimos conexion
        // Ejecutamos el INSERT relacionando la historia con el paciente mediante la llave foranea pac_id
        $query = "INSERT INTO historias (pac_id, hist_motv, hist_esfod, hist_cilod, hist_ejeod, hist_esfoi, hist_ciloi, hist_ejeoi, hist_diaod, hist_diaoi, hist_recom) 
                  VALUES ('$pac_id', '$mot', '$esf_od', '$cil_od', '$eje_od', '$esf_oi', '$cil_oi', '$eje_oi', '$diag_od', '$diag_oi', '$rec')";
        
        $res = $con->query($query); // Ejecutamos la insercion
        $con->close(); // Cerramos conexion
        return $res; // Devolvemos true o false
    }

    // Metodo para listar el resumen de todas las historias en la pantalla principal
    public function obtenerHistoriasTodas() {
        $con = $this->db->obtenerConexion(); // Abrimos conexion
        // Hacemos INNER JOIN con paciente para traer su nombre completo en vez de solo su numero de ID
        $query = "SELECT h.hist_id, CONCAT(p.pac_nombre, ' ', p.pac_apellido) AS paciente, h.hist_motv, h.hist_recom 
                  FROM historias h 
                  INNER JOIN paciente p ON h.pac_id = p.pac_id";
        
        $resultado = $con->query($query); // Ejecutamos consulta
        $lista = array(); // Arreglo vacio para almacenar el listado
        
        while ($fila = $resultado->fetch_assoc()) { // Recorremos cada fila devuelta por MySQL
            $lista[] = $fila; // Guardamos la fila en el arreglo
        }
        
        $con->close(); // Cerramos conexion
        return $lista; // Retornamos el arreglo con todas las historias
    }

    // Metodo para consultar toda la informacion completa de una sola historia clinica al darle clic en Ver Historia
    public function obtenerHistoriaDetalle(int $hist_id) {
        $con = $this->db->obtenerConexion(); // Abrimos conexion
        // Hacemos doble INNER JOIN (con paciente y con generos) para traer todos los campos de la historia y datos del paciente
        $query = "SELECT h.*, CONCAT(p.pac_nombre, ' ', p.pac_apellido) AS paciente, g.gen_nombre 
                  FROM historias h 
                  INNER JOIN paciente p ON h.pac_id = p.pac_id 
                  INNER JOIN generos g ON p.gen_id = g.gen_id 
                  WHERE h.hist_id = '$hist_id'";
        
        $resultado = $con->query($query); // Ejecutamos el SELECT
        $detalle = $resultado->fetch_assoc(); // Extraemos la unica fila encontrada
        $con->close(); // Cerramos conexion
        return $detalle; // Retornamos el detalle completo a ver_historia.php
    }
}
?>