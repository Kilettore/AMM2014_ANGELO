<?php

include_once basename(__DIR__) . '/../view/ViewDescriptor.php';
include_once basename(__DIR__) . '/../model/Base.php';
include_once basename(__DIR__) . '/../model/UserDatabase.php';
//include_once basename(__DIR__) . '/../model/UserFactory.php';

//private $vd;
/**
 * Controller che gestisce gli utenti non autenticati, 
 * fornendo le funzionalita' comuni anche agli altri controller
 *
 * @author Davide Spano
 */
class ControllerBase
{
    const user = 'user';
    const role = 'role';
    const impersonato = '_imp';
    private $user;
    
    public function __construct() //Costruttore
    {
        
    }

    /**
     * Metodo per gestire l'input dell'utente. Le sottoclassi lo sovrascrivono
     * @param type $request la richiesta da gestire
     */
    public function handleInput(&$request) 
    {
        // creo il descrittore della vista
        $vd = new ViewDescriptor();

        // imposto la pagina
        //$vd->setPagina($request["page"]);

        // imposto il token per impersonare un utente (nel caso lo stia facendo)
        $this->setImpToken($vd, $request);
        
        //serve per verificare se l' utente è già loggato
        if ($this->loggedIn())
        {
            //se l' utente risulta loggato lo vado a cercare nel database
            //dopodichè carichero' la sua pagina corrispondente
            $user = UserDatabase::instance()->cercaUtentePerId($_SESSION[self::user], $_SESSION[self::role]);
            $this->findPageToShow($vd);
        }
        else
        {
            if ($request["login"] === 'Login') 
            {
                $username = isset($request['username']) ? $request['username'] : '';
                $password = isset($request['password']) ? $request['password'] : '';
                $this->login($vd, $username, $password);
            }
            else 
            {
                // utente non autenticato
                $this->showBasePage($vd);
            }
        }
    }

    /**
     * Verifica se l'utente sia correttamente autenticato
     * @return boolean true se l'utente era gia' autenticato, false altrimenti
     */
    protected function loggedIn() 
    {
        return isset($_SESSION) && array_key_exists(self::user, $_SESSION);
    }
    
    protected function showBasePage($vd)
    {
        
        $vd->setTitle("Computer Shop - Home Page");
        $vd->setLoginContent(basename(__DIR__) . '/../view/Base/login_content.php');
        //$vd->setMenuFile(basename(__DIR__) . '/../view/login/menu.php');
        $vd->setLogoFile(basename(__DIR__) . '/../view/Base/logo.php');
        $vd->setLeftBarFile(basename(__DIR__) . '/../view/Base/leftBar.php');
        //$vd->setRightBarFile(basename(__DIR__) . '/../view/login/rightBar.php');
        $vd->setContentFile(basename(__DIR__) . '/../view/Base/main_content.php');
        
        require basename(__DIR__) . '/../view/master.php';
    }

    protected function showHomeUser($vd)
    {
        $vd->setTitle("Computer Shop - Home Utente");
        $vd->setLoginContent(basename(__DIR__) . '/../view/Utente/login_content.php');
        //$vd->setMenuFile(basename(__DIR__) . '/../view/login/menu.php');
        $vd->setLogoFile(basename(__DIR__) . '/../view/Base/logo.php');
        $vd->setLeftBarFile(basename(__DIR__) . '/../view/Base/leftBar.php');
        //$vd->setRightBarFile(basename(__DIR__) . '/../view/login/rightBar.php');
        $vd->setContentFile(basename(__DIR__) . '/../view/Base/main_content.php');
        
        require basename(__DIR__) . '/../view/master.php';
    }
    
    protected function showHomeComm($vd)
    {
        $vd->setTitle("Computer Shop - Home Commericiante");
        $vd->setLoginContent(basename(__DIR__) . '/../view/Utente/login_content.php');
        //$vd->setMenuFile(basename(__DIR__) . '/../view/login/menu.php');
        $vd->setLogoFile(basename(__DIR__) . '/../view/Base/logo.php');
        $vd->setLeftBarFile(basename(__DIR__) . '/../view/Base/leftBar.php');
        //$vd->setRightBarFile(basename(__DIR__) . '/../view/login/rightBar.php');
        $vd->setContentFile(basename(__DIR__) . '/../view/Base/main_content.php');
        
        require basename(__DIR__) . '/../view/master.php';
    }

   /**
     * Seleziona quale pagina mostrare in base al ruolo dell'utente corrente
     * @param ViewDescriptor $vd il descrittore della vista
     */
    protected function findPageToShow($vd) 
    {
        $user = UserDatabase::instance()->cercaUtentePerId($_SESSION[self::user], $_SESSION[self::role]);
        //echo $user->getTipoUtente();
        switch ($user->getTipoUtente()) 
        {
            case Base::user:
                $this->showHomeUser($vd);
                break;

            case Base::comm:
                $this->showHomeComm($vd);
                break;
        }
    }

    /**
     * Imposta la variabile del descrittore della vista legato 
     * all'utente da impersonare nel caso sia stato specificato nella richiesta
     * @param ViewDescriptor $vd il descrittore della vista
     * @param array $request la richiesta
     */
    protected function setImpToken(ViewDescriptor $vd, &$request)
    {
        if (array_key_exists('_imp', $request)) 
        {
            $vd->setImpToken($request['_imp']);
        }
    }

    /**
     * Procedura di autenticazione 
     * @param ViewDescriptor $vd descrittore della vista
     * @param string $username lo username specificato
     * @param string $password la password specificata
     */
    protected function login($vd, $username, $password)
    {
        // user contiene l' utente appena caricato dal database
        $user = UserDatabase::instance()->caricaUtente($username, $password);
        if (isset($user) && $user->esiste()) 
        {
            //self::user contiene l' id dell' utente
            $_SESSION[self::user] = $user->getId();
            //self::role contiene il ruolo dell' utente (1 -> user, 2 -> comm)
            $_SESSION[self::role] = $user->getTipoUtente();
            
            //echo $user->getTipoUtente();
            
            //copio l' utente in ambiente globale
            $this->user = $user;
            
            $this->findPageToShow($vd);
        } 
        else 
        {
            $vd->setMessaggioErrore("Utente sconosciuto o password errata");
            $this->showBasePage($vd);
        }
    }

    /**
     * Procedura di logout dal sistema 
     * @param type $vd il descrittore della pagina
     */
    protected function logout($vd) 
    {
        // reset array $_SESSION
        $_SESSION = array();
        // termino la validita' del cookie di sessione
        if (session_id() != '' || isset($_COOKIE[session_name()])) {
            // imposto il termine di validita' al mese scorso
            setcookie(session_name(), '', time() - 2592000, '/');
        }
        // distruggo il file di sessione
        session_destroy();
        $this->showBasePage($vd);
    }

    /**
     * Aggiorno l'indirizzo di un utente (comune a Studente e Docente)
     * @param User $user l'utente da aggiornare
     * @param array $request la richiesta http da gestire
     * @param array $msg riferimento ad un array da riempire con eventuali
     * messaggi d'errore
     */
    protected function aggiornaIndirizzo($user, &$request, &$msg) {

        if (isset($request['via'])) {
            if (!$user->setVia($request['via'])) {
                $msg[] = '<li>La via specificata non &egrave; corretta</li>';
            }
        }
        if (isset($request['civico'])) {
            if (!$user->setNumeroCivico($request['civico'])) {
                $msg[] = '<li>Il formato del numero civico non &egrave; corretto</li>';
            }
        }
        if (isset($request['citta'])) {
            if (!$user->setCitta($request['citta'])) {
                $msg[] = '<li>La citt&agrave; specificata non &egrave; corretta</li>';
            }
        }
        if (isset($request['provincia'])) {
            if (!$user->setProvincia($request['provincia'])) {
                $msg[] = '<li>La provincia specificata &egrave; corretta</li>';
            }
        }
        if (isset($request['cap'])) {
            if (!$user->setCap($request['cap'])) {
                $msg[] = '<li>Il CAP specificato non &egrave; corretto</li>';
            }
        }

        // salviamo i dati se non ci sono stati errori
        if (count($msg) == 0) {
            if (UserFactory::instance()->salva($user) != 1) {
                $msg[] = '<li>Salvataggio non riuscito</li>';
            }
        }
    }

    /**
     * Aggiorno l'indirizzo email di un utente (comune a Studente e Docente)
     * @param User $user l'utente da aggiornare
     * @param array $request la richiesta http da gestire
     * @param array $msg riferimento ad un array da riempire con eventuali
     * messaggi d'errore
     */
    protected function aggiornaEmail($user, &$request, &$msg) {
        if (isset($request['email'])) {
            if (!$user->setEmail($request['email'])) {
                $msg[] = '<li>L\'indirizzo email specificato non &egrave; corretto</li>';
            }
        }
        
        // salviamo i dati se non ci sono stati errori
        if (count($msg) == 0) {
            if (UserFactory::instance()->salva($user) != 1) {
                $msg[] = '<li>Salvataggio non riuscito</li>';
            }
        }
    }

    /**
     * Aggiorno la password di un utente (comune a Studente e Docente)
     * @param User $user l'utente da aggiornare
     * @param array $request la richiesta http da gestire
     * @param array $msg riferimento ad un array da riempire con eventuali
     * messaggi d'errore
     */
    protected function aggiornaPassword($user, &$request, &$msg) {
        if (isset($request['pass1']) && isset($request['pass2'])) {
            if ($request['pass1'] == $request['pass2']) {
                if (!$user->setPassword($request['pass1'])) {
                    $msg[] = '<li>Il formato della password non &egrave; corretto</li>';
                }
            } else {
                $msg[] = '<li>Le due password non coincidono</li>';
            }
        }
        
        // salviamo i dati se non ci sono stati errori
        if (count($msg) == 0) {
            if (UserFactory::instance()->salva($user) != 1) {
                $msg[] = '<li>Salvataggio non riuscito</li>';
            }
        }
    }

    /**
     * Crea un messaggio di feedback per l'utente 
     * @param array $msg lista di messaggi di errore
     * @param ViewDescriptor $vd il descrittore della pagina
     * @param string $okMsg il messaggio da mostrare nel caso non ci siano errori
     */
    protected function creaFeedbackUtente(&$msg, $vd, $okMsg) {
        if (count($msg) > 0) {
            // ci sono messaggi di errore nell'array,
            // qualcosa e' andato storto...
            $error = "Si sono verificati i seguenti errori \n<ul>\n";
            foreach ($msg as $m) {
                $error = $error . $m . "\n";
            }
            // imposto il messaggio di errore
            $vd->setMessaggioErrore($error);
        } else {
            // non ci sono messaggi di errore, la procedura e' andata
            // quindi a buon fine, mostro un messaggio di conferma
            $vd->setMessaggioConferma($okMsg);
        }
    }

}

?>
