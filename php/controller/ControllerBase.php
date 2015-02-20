<?php

include_once basename(__DIR__) . '/../view/ViewDescriptor.php';
include_once basename(__DIR__) . '/../model/Base.php';
include_once basename(__DIR__) . '/../model/UserDatabase.php';

// Controller che si occupa degli utenti non loggati
class ControllerBase
{
    const user = 'user';
    const role = 'role';
    
    public $input_search;
    
    public function __construct() //Costruttore
    {
        
    }

    // Gestisce l' input degli utenti
    public function handleInput(&$request) 
    {
        // creo una nuova istanza per il descrittore
        $vd = new ViewDescriptor();

        // Imposto la sottopagina di default su 'home'
        $vd->setSottoPagina('home');

        // Test per verificare se l' utente ha richiesto un logout
        if (isset($request["logout"])) 
        {
            $request["logout"] = '';
            $request["login"] = null;
            $this->logout();
        }

        if(isset($request["login"]))
        {
            if ($request["login"] === 'Login') 
            {
                $username = isset($request['username']) ? $request['username'] : '';
                $password = isset($request['password']) ? $request['password'] : '';
                $this->login($vd, $username, $password);
            }
        }
        else 
        {
            if(isset($request['subpage']))
            {
                switch ($request['subpage'])
                {
                    case 'chisiamo':
                        $vd->setSottoPagina("chisiamo");
                        break;

                    case 'partner':
                        $vd->setSottoPagina("partner");
                        break;

                    case 'cerca':
                        $this->input_search = $request['search'];
                        $vd->setSottoPagina("cerca");
                        break;
                }
            }
            // Caso di utente non autenticato
            $this->showBasePage($vd);
        }
    }
    
    // Pagina base
    protected function showBasePage($vd)
    {
        
        $vd->setTitle("Computer Shop - Home Page");
        $vd->setLoginContent(basename(__DIR__) . '/../view/Base/login_content.php');
        $vd->setMenuFile(basename(__DIR__) . '/../view/Base/menu.php');
        $vd->setLogoFile(basename(__DIR__) . '/../view/Base/logo.php');
        $vd->setLeftBarFile(basename(__DIR__) . '/../view/Base/leftBar.php');
        $vd->setContentFile(basename(__DIR__) . '/../view/Base/main_content.php');
        $vd->setFooter(basename(__DIR__) . '/../view/Base/footer.php');
        
        require basename(__DIR__) . '/../view/master.php';
    }

    // Pagina utente loggato
    protected function showHomeUser($vd)
    {
        $vd->setTitle("Computer Shop - Home Utente");
        $vd->setLoginContent(basename(__DIR__) . '/../view/Utente/login_content.php');
        $vd->setMenuFile(basename(__DIR__) . '/../view/Utente/menu.php');
        $vd->setLogoFile(basename(__DIR__) . '/../view/Utente/logo.php');
        $vd->setLeftBarFile(basename(__DIR__) . '/../view/Utente/leftBar.php');
        $vd->setContentFile(basename(__DIR__) . '/../view/Utente/main_content.php');
        $vd->setFooter(basename(__DIR__) . '/../view/Utente/footer.php');
        
        require basename(__DIR__) . '/../view/master.php';
    }
    
    // Pagina admin
    protected function showHomeComm($vd)
    {
        $vd->setTitle("Computer Shop - Home Commericiante");
        $vd->setLoginContent(basename(__DIR__) . '/../view/Commerciante/login_content.php');
        $vd->setMenuFile(basename(__DIR__) . '/../view/Commerciante/menu.php');
        $vd->setLogoFile(basename(__DIR__) . '/../view/Commerciante/logo.php');
        $vd->setLeftBarFile(basename(__DIR__) . '/../view/Commerciante/leftBar.php');
        $vd->setContentFile(basename(__DIR__) . '/../view/Commerciante/main_content.php');
        $vd->setFooter(basename(__DIR__) . '/../view/Commerciante/footer.php');
        
        require basename(__DIR__) . '/../view/master.php';
    }

    // Funzione che serve per stabilire quale tipo di utente ha effettuato il login
    protected function findPageToShow() 
    {
        $user = UserDatabase::instance()->cercaUtentePerId($_SESSION[self::user], $_SESSION[self::role]);
        
        switch ($user->getTipoUtente()) 
        {
            case Base::user:
                $new_user = new ControllerUser();
                $new_user -> handleInput($request);
                break;

            case Base::comm:
                $new_comm = new ControllerComm();
                $new_comm -> handleInput($request);
                break;
        }
    }

    // Login utente
    // Verrà dopo cercata la pagina corrispondente al tipo di utente
    // Se l' utente non esiste viene ricaricata la pagina base
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
            
            // Cerco la pagina che corrisponde a quella del tipo di utente
            $this->findPageToShow();
        } 
        else 
        {
            $vd->setMessaggioErrore("Utente sconosciuto o password errata");
            $this->showBasePage($vd);
        }
    }

    // Funzione di logout
    protected function logout() 
    {
        // reset array $_SESSION
        $_SESSION = array();
        // termino la validita' del cookie di sessione
        if (session_id() != '' || isset($_COOKIE[session_name()])) 
        {
            // imposto il termine di validita' al mese scorso
            setcookie(session_name(), '', time() - 2592000, '/');
        }
        // distruggo il file di sessione
        session_destroy();
    }
}

?>
