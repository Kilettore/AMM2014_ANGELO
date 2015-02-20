<?php

include_once 'controller/ControllerBase.php';
include_once 'controller/ControllerUser.php';
include_once 'controller/ControllerComm.php';

date_default_timezone_set("Europe/Rome");

ControllerMain::findUserType($_REQUEST);

class ControllerMain
{

    // Punto di accesso unico dell' applicazione, smisto qui le richieste
    public static function findUserType(&$request)
    {         
        // Inizializzo la sessione 
        session_start();
        
        // Entra in questo blocco se viene richiesto il logout
        if(isset($request["logout"]))
        {
            if($request["logout"] === 'Logout') 
            {
                $cont = new ControllerBase();
                $request['login'] = '';
                $cont->handleInput($request);
            }
        }
        else
        {   // Questo test mi permette di determinare se un utente è già loggato nel sito
            // Se è già loggato faccio la ricerca e determino che tipo di utente è
            if(isset($_SESSION['role']))
            {
                switch($_SESSION['role'])
                {
                    // Caso utente
                    case '1':
                        $cont = new ControllerUser();
                        $cont->handleInput($request); 
                        break;

                    // Caso amministratore
                    case '2':
                        $cont = new ControllerComm();
                        $cont->handleInput($request);
                        break;
                }
            }
            else   
            {
                $cont = new ControllerBase();
                $cont->handleInput($request);            
            }
        }
    }
}

?>
