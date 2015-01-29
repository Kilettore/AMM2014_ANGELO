<?php

include_once 'controller/ControllerBase.php';
include_once 'controller/ControllerUser.php';
//include_once 'controller/Commerciante_Controller.php';

date_default_timezone_set("Europe/Rome");
echo "il vero inizio<br>";
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
        
        if (isset($request["page"])) 
        {
            switch ($request["page"]) 
            {
                case 'login':
                    // la pagina di login e' accessibile a tutti,
                    // la facciamo gestire al BaseController
                    $cont = new ControllerBase();
                    $cont->handleInput($request);
                    break;

                // studente
                case 'user':
                    // la pagina degli studenti e' accessibile solo agli studenti
                    // agli studenti ed agli amminstratori
                    // il controllo viene fatto dal controller apposito
                    $cont = new ControllerUser();
                    if (isset($_SESSION[ControllerBase::role]) && $_SESSION[ControllerBase::role] != Base::user) 
                    {
                        self::write403();
                    }
                    $cont->handleInput($request);
                    break;

                // docente
                /*case 'docente':
                    // la pagina dei docenti e' accessibile solo
                    // ai docenti ed agli amminstratori
                    // il controllo viene fatto dal controller apposito
                    $controller = new DocenteController();
                    if (isset($_SESSION[BaseController::role]) &&
                        $_SESSION[BaseController::role] != User::Docente)  {
                        self::write403();
                    }
                    $controller->handleInput($request);
                    break;

                default:
                    self::write404();
                    break;*/
            }
        }
        else 
        {
            /*$cont = new ControllerBase();
            $cont->handleInput($request); */
            echo "errore<br>";
            self::write404();
        }
    }

    /**
     * Crea una pagina di errore quando il path specificato non esiste
     */
    public static function write404() {
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
    public static function write403() {
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
