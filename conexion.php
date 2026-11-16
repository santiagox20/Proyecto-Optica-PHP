<?php
class ConexionBD { // Creamos la clase principal para manejar la conexion a la base de datos
    private $host = "localhost"; // Servidor local donde corre MySQL (XAMPP)
    private $user = "root"; // Usuario por defecto de la base de datos
    private $password = ""; // Contrasena de MySQL (vacia por defecto en local)
    private $database = "historia_clinica"; // Nombre de nuestra base de datos de la optica

    public function obtenerConexion() { // Metodo publico para que otras clases puedan pedir la conexion
        $conexion = new mysqli($this->host, $this->user, $this->password, $this->database); // Instanciamos mysqli usando los atributos privados de arriba
        
        if ($conexion->connect_error) { // Revisamos si hubo algun fallo al intentar conectar
            die("Error de conexion: " . $conexion->connect_error); // Si falla, frenamos todo el programa y mostramos el error
        }
        
        return $conexion; // Si todo sale bien, devolvemos el puente de conexion listo para usar
    }
}
?>