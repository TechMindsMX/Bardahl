<?php
    $id = $_GET['id'];
    $name = $_GET['name'];
    
    
    
    
    try
    {
        $cn = new PDO("mysql:dbname=bardahl;host=localhost","root","");
        $cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch( PDOException $e)
        {

        echo "Error Connection: " . $e->getMessage();

    }

    if ($name == 'up') {
        echo "entro aqui";
       // $query = $cn->prepare("SELECT *FROM user WHERE status=?");

        //AQUI LE PASAMOS EL PARAMETRO
        //$query->execute(array("A"));
        
    } else {
    echo $sql = "UPDATE [tabla] SET down=down+1 WHERE [tabla].
    [columna] = '" . $id . "'";
    }
   
 
    /*/Esto es lo que le devuelve el PHP al AJAX para que actualice el HTML
    echo "<a href='javascript:void(0)' class='vote' name='up' id='",
    $id."['columna id']",
    "' onclick='vote(this.id, this.name)'>up", 
    $entradas['up'], "</a> / <a href='javascript:void(0)' class='vote' name='down' id='", $entradas['columna id'], 
    "'onclick='vote(this.id, this.name)'>down ", $entradas['down'], 
    "</a> Gracias por votar";*/
