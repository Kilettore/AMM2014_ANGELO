<?php

include_once 'controller/ControllerBase.php';
include_once 'controller/ControllerUser.php';
include_once 'controller/ControllerComm.php';

date_default_timezone_set("Europe/Rome");

ControllerMain::findUserType($_REQUEST);

/**
 * Classe che controlla il punto unico di accesso all'applicazione
 * @author Davide Spano
 */
class ControllerMain
{

    /**
     * Gestore delle richieste al punto unico di accesso all'applicazione
     * @param array $request i parametri della richiesta
     */
    public static function findUserType(&$request)
    {         
        // Inizializzo la sessione 
        session_start();
        
        // Entra in questo blocco se viene richiesto il logout
        if($request["logout"] === 'Logout') 
        {
            $cont = new ControllerBase();
            $request['login'] = '';
            $cont->handleInput($request);
        }
        else
        {   // Questo test mi permette di determinare se un utente è già loggato nel sito
            // Se è già loggato faccio la ricerca e determino che tipo di utente è
            if(isset($_SESSION[role]))
            {
                switch($_SESSION[role])
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
                /*if(isset($request["login"]))*/
                {
                    $cont = new ControllerBase();
                    $cont->handleInput($request);            
                }
                /*else 
                {
                    self::write404();
                }*/
            }
        }
    }

    /**
     * Crea una pagina di errore quando il path specificato non esiste
     */
    public static function write404() 
    {
        // impostiamo il codice della risposta http a 404 (file not found)
        header('HTTP/1.0 404 Not Found');
        $titolo = "File non trovato!";
        $messaggio = "La pagina che hai richiesto non &egrave; disponibile";
        include_once('error.php');
        exit();
    }

    /**
     * Crea una pagina di errore quando l'utente non ha i privilegi 
     * per accedere alla pagina
     */
    public static function write403() 
    {
        // impostiamo il codice della risposta http a 404 (file not found)
        header('HTTP/1.0 403 Forbidden');
        $titolo = "Accesso negato";
        $messaggio = "Non hai i diritti per accedere a questa pagina";
        $login = true;
        include_once('error.php');
        exit();
    }

}

?>
