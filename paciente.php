<?php
require_once 'conexion.php'; // Importamos la clase de conexion

class Paciente { // Modelo encargado de todas las operaciones CRUD de los pacientes
    private ConexionBD $db; // Propiedad privada tipada para guardar la conexion

    public function __construct() { // Constructor de la clase
        $this->db = new ConexionBD(); // Instanciamos la conexion apenas se crea el objeto Paciente
    }

    // Metodo para registrar un paciente nuevo y asociarle sus hobbies en la tabla intermedia
    public function guardarPaciente($doc, $nom, $ape, $dir, $tel, $cor, $gen_id, $estr_id, $hobbies) {
        $con = $this->db->obtenerConexion(); // Abrimos conexion a MySQL
        
        // Preparamos el INSERT en la tabla paciente dejando su estado inicial como 'Activo'
        $query = "INSERT INTO paciente (pac_docum, pac_nombre, pac_apellido, pac_direccion, pac_telefono, pac_correo, gen_id, estr_id, pac_estado) 
                VALUES ('$doc', '$nom', '$ape', '$dir', '$tel', '$cor', '$gen_id', '$estr_id', 'Activo')";
        
        if ($con->query($query)) { // Si el paciente se guardo correctamente en la tabla principal
            $pac_id = $con->insert_id; // Capturamos el ID autoincrementable que MySQL le acaba de asignar

            if (!empty($hobbies)) { // Verificamos si el usuario marco al menos un checkbox de hobbies
                foreach ($hobbies as $hob_id) { // Recorremos uno por uno los IDs de hobbies seleccionados
                    $q_h = "INSERT INTO paciente_hobbies (pac_id, hob_id) VALUES ('$pac_id', '$hob_id')"; // Insertamos en la tabla rompimiento muchos a muchos
                    $con->query($q_h); // Ejecutamos la insercion de cada hobby
                }
            }
            $con->close(); // Cerramos la conexion
            return true; // Retornamos true indicando que todo el proceso fue exitoso
        } else {
            $con->close(); // Cerramos la conexion si fallo
            return false; // Retornamos false para avisar del error
        }
    }

    // Metodo para listar en la pantalla principal solo los pacientes que no han sido anulados
    public function obtenerPacientesActivos() {
        $con = $this->db->obtenerConexion(); // Abrimos conexion
        // Unimos paciente con generos y estratos para mostrar nombres en vez de IDs, y concatenamos nombre y apellido
        $query = "SELECT p.pac_id, p.pac_docum, CONCAT(p.pac_nombre, ' ', p.pac_apellido) AS nom, p.pac_telefono, g.gen_nombre, e.estr_nombre 
                FROM paciente p 
                INNER JOIN generos g ON p.gen_id = g.gen_id 
                INNER JOIN estratos e ON p.estr_id = e.estr_id 
                WHERE p.pac_estado = 'Activo'";
        
        $resultado = $con->query($query); // Ejecutamos la consulta
        $lista = array(); // Creamos un arreglo vacio para ir metiendo los pacientes
        
        while ($fila = $resultado->fetch_assoc()) { // Recorremos fila por fila mientras existan registros
            $lista[] = $fila; // Agregamos cada paciente al arreglo
        }
        
        $con->close(); // Cerramos conexion
        return $lista; // Devolvemos la lista completa a la vista
    }

    // Metodo para buscar un paciente especifico por su ID (usado al editar o al crearle historia)
    public function obtenerPacientePorId($pac_id) {
        $con = $this->db->obtenerConexion(); // Abrimos conexion
        // Traemos todos los datos del paciente junto con el nombre de su genero y estrato
        $query = "SELECT p.*, g.gen_nombre, e.estr_nombre 
                FROM paciente p 
                INNER JOIN generos g ON p.gen_id = g.gen_id 
                INNER JOIN estratos e ON p.estr_id = e.estr_id 
                WHERE p.pac_id = '$pac_id'";
        
        $resultado = $con->query($query); // Ejecutamos el SELECT
        $paciente = $resultado->fetch_assoc(); // Como es un solo ID, sacamos directamente esa unica fila
        $con->close(); // Cerramos conexion
        return $paciente; // Retornamos los datos del paciente encontrado
    }

    // Metodo para guardar los cambios cuando se edita un paciente
    public function actualizarPaciente($pac_id, $nom, $ape, $tel, $gen_id, $estr_id) {
        $con = $this->db->obtenerConexion(); // Abrimos conexion
        // Ejecutamos UPDATE filtrando estrictamente por el ID del paciente para no modificar otros registros
        $query = "UPDATE paciente SET pac_nombre = '$nom', pac_apellido = '$ape', pac_telefono = '$tel', gen_id = '$gen_id', estr_id = '$estr_id' 
                WHERE pac_id = '$pac_id'";
        
        $res = $con->query($query); // Aplicamos la actualizacion en MySQL
        $con->close(); // Cerramos conexion
        return $res; // Devolvemos true o false segun si actualizo bien
    }

    // Metodo de borrado logico: no borra el registro de la BD para proteger sus historias clinicas
    public function anularPaciente($pac_id) {
        $con = $this->db->obtenerConexion(); // Abrimos conexion
        $query = "UPDATE paciente SET pac_estado = 'Inactivo' WHERE pac_id = '$pac_id'"; // Cambiamos su estado a Inactivo
        
        $res = $con->query($query); // Ejecutamos el cambio de estado
        $con->close(); // Cerramos conexion
        return $res; // Retornamos el resultado de la operacion
    }
}
?>