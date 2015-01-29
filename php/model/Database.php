<?php

class Database
{
    private function __construct()
    {
        
    }
    
    public static function connectDatabase(){
        $mysqli = new mysqli();
        
        // Da usare quando il sito è online 
        // $mysqli->connect("localhost", "cadoniAngelo", "scimpanze72", "amm14_cadoniAngelo");
        
        // Da usare quando il sito è in locale
        $mysqli->connect("localhost", "root", "davide", "amm14_cadoniAngelo");
        
        // Verifico la presenza di errori
        if($mysqli->errno != 0)
        {
            return null;
        }
        else
        {
            return $mysqli;
        }
    }
}

?>
