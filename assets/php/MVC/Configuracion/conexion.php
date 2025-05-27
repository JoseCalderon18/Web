<?php
// Clase para la conexión a la base de datos en IONOS
class Conexion{
    private static $host = "db5017922334.hosting-data.io";
    private static $usuario = "dbu1561636";
    private static $contrasenia = "Bioespacio11@";
    private static $base_de_datos = "dbs14267429";

    public static function conectar(){
        try{
            // Conexión a la base de datos
            $conexion = new PDO("mysql:host=" . self::$host . ";port=3306;dbname=" . self::$base_de_datos, self::$usuario, self::$contrasenia);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conexion->exec("SET NAMES 'utf8'");
            
            // Retorna la conexión
            return $conexion;
        } catch(PDOException $e){
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
?>
