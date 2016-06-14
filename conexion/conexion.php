<?php
/*
try{
    $conn1 = mysqli_connect('localhost', 'root', '', 'sistShowOnTime_Dev', '3306');
    if (!$conn1) {
        die(mysqli_connect_error());
    } else {
        echo 'Conectado a ';
    }
    mysqli_query($conn1, 'SET NAMES \'utf8\'');
    // TODO: insert your code here.
    mysqli_close($conn1);
    
} catch (Exception $ex) {
    echo 'Error conectando con la base de datos: ' . $ex->getMessage();
}

   */
class conexion {
    
    public function conectar(){
        try{
            $db = new pdo('mysql:host=127.0.0.1;port=3306;dbname=sistShowOnTime_Dev;charset=utf8','root','',array( ));
        }catch(PDOException $pe){
            echo $pe->getMessage();
        }   
    }
    


}