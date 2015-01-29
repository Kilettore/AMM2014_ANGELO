<?php

include_once 'controller/ControllerBase.php';
include_once 'controller/ControllerUser.php';
//include_once 'controller/Commerciante_Controller.php';

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
        // inizializziamo la sessione 
        session_start();
        
        // controllo le richieste degli utenti a cascata
        // in questo modo posso evitare l' utilizzo del rewrite engine
        if($request["logout"] === 'Logout') 
        {
            $cont = new ControllerUser();
            $request['login'] = '';
            if (isset($_SESSION[ControllerBase::role]) && $_SESSION[ControllerBase::role] != Base::user) 
            {
                self::write403();
            }
            $cont->handleInput($request);
        }
        else   
        {
            if(isset($request["login"]) )
            {
                $cont = new ControllerBase();
                $cont->handleInput($request);            
            }
            else 
            {
                self::write404();
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
