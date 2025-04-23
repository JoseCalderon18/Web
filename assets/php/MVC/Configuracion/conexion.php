<?php
// Clase para la conexion a la base de datos
class Conexion{
    private static $host = "localhost";
    private static $usuario = "root";
    private static $contrasenia = "";
    private static $base_de_datos = "bioespacio";

    public static function conectar(){
        try{
            // Conexion a la base de datos
            $conexion = new PDO("mysql:host=". self::$host . ";dbname=" . self::$base_de_datos, self::$usuario, self::$contrasenia);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conexion->exec("SET NAMES 'utf8'");
            
            // Retorna la conexion
            return $conexion;
            
        }catch(PDOException $e){
            die("Error de conexion: " . $e->getMessage());
        }
    }
}
?>