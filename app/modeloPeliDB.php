<?php

include_once 'config.php';
include_once 'Pelicula.php';

class ModeloPeliDB {

     private static $dbh = null; 

     private static $consulta_peli = "Select * from peliculas where codigo_pelicula = ?";
     //Busquedas
     private static $peli_nombre = "select * from peliculas where nombre=?";
     private static $peli_genero = "select * from peliculas where genero=?";
     private static $peli_director = "select * from peliculas where director=?";

     private static $insert_peli   = "Insert into peliculas (nombre,director,genero,imagen)".
                                     " VALUES (?,?,?,?)";
     //Borrar
     private static $delete_peli = "Delete * from peliculas where codigo_pelicula = ?";

    
  /*
     private static $delete_peli   = "Delete from Usuarios where id = ?"; 
     
     private static $update_user    = "UPDATE Usuarios set  clave=?, nombre =?, ".
                                     "email=?, plan=?, estado=? where id =?";
 */    
     
public static function init(){
   
    if (self::$dbh == null){
        try {
            // Cambiar  los valores de las constantes en config.php
            $dsn = "mysql:host=".DBSERVER.";dbname=".DBNAME.";charset=utf8";
            self::$dbh = new PDO($dsn,DBUSER,DBPASSWORD);
            // Si se produce un error se genera una excepción;
            self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            echo "Error de conexión ".$e->getMessage();
            exit();
        }
        
    }
    
}

public static function insert($peli):bool{
    $stmt = self::$dbh->prepare(self::$insert_peli);
    $stmt->bindValue(1,$peli->nombre);
    $stmt->bindValue(2,$peli->director);
    $stmt->bindValue(3,$peli->genero);
    $stmt->bindValue(4,$peli->imagen );
    if ($stmt->execute()){
       return true;
    }
    return false; 
}



// Borrar un usuario (boolean)
public static function PeliDel($peli){
    $stmt = self::$dbh->prepare(self::$delete_peli);
    $stmt->bindValue(1,$peli);
    $stmt->execute();
    if ($stmt->rowCount() > 0 ){
        return true;
    }
    return false;
}
// Añadir un nuevo usuario (boolean)

/***
// Actualizar un nuevo usuario (boolean)
// GUARDAR LA CLAVE CIFRADA
public static function UserUpdate ($userid, $userdat){
    $clave = $userdat[0];
    // Si no tiene valor la cambio
    if ($clave == ""){ 
        $stmt = self::$dbh->prepare(self::$update_usernopw);
        $stmt->bindValue(1,$userdat[1] );
        $stmt->bindValue(2,$userdat[2] );
        $stmt->bindValue(3,$userdat[3] );
        $stmt->bindValue(4,$userdat[4] );
        $stmt->bindValue(5,$userid);
        if ($stmt->execute ()){
            return true;
        }
    } else {
        $clave = Cifrador::cifrar($clave);
        $stmt = self::$dbh->prepare(self::$update_user);
        $stmt->bindValue(1,$clave );
        $stmt->bindValue(2,$userdat[1] );
        $stmt->bindValue(3,$userdat[2] );
        $stmt->bindValue(4,$userdat[3] );
        $stmt->bindValue(5,$userdat[4] );
        $stmt->bindValue(6,$userid);
        if ($stmt->execute ()){
            return true;
        }
    }
    return false; 
}
****/




// Tabla de objetos con todas las peliculas
public static function GetAll ():array{
    // Genero los datos para la vista que no muestra la contraseña
    
    $stmt = self::$dbh->query("select * from peliculas");
    
    $tpelis = [];
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Pelicula');
    while ( $peli = $stmt->fetch()){
        $tpelis[] = $peli;       
    }
    return $tpelis;
}

// Tabla de objetos con todas las peliculas por titulo
public static function GetTitulo ():array{
    // Genero los datos para la vista que no muestra la contraseña
    
    $stmt = self::$dbh->query("select * from peliculas where titulo = ?");
    
    $tpelis = [];
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Pelicula');
    while ( $peli = $stmt->fetch()){
        $tpelis[] = $peli;       
    }
    return $tpelis;
}



// Datos de una película para visualizar
public static function GetOne ($codigo){
    $stmt = self::$dbh->prepare(self::$consulta_peli);
    $stmt->bindValue(1,$codigo);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Pelicula');
    $peli = $stmt->fetch();
    return $peli; // Devuele una pelicula o false    
}

public static function closeDB(){
    self::$dbh = null;
}

//Buscar por Titulo, Genero, Director, cojo una al igual que getOne

public static function ctlPeliBuscarNombre($nombre){
    $stmt = self::$dbh->prepare(self::$peli_nombre);
    $stmt->bindValue(1, $nombre);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Pelicula');
    $peli = $stmt->fetch();
    return $peli; 
}

public static function ctlPeliBuscarGenero($genero){
    $stmt = self::$dbh->prepare(self::$peli_genero);
    $stmt->bindValue(1, $genero);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Pelicula');
    $peli = $stmt->fetch();
    return $peli;
}



    

public static function ctlPeliBuscarDirector($director){
    $stmt = self::$dbh->prepare(self::$peli_director);
    $stmt->bindValue(1, $director);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Pelicula');
    $peli = $stmt->fetch();
    return $peli;  
}

}//Class