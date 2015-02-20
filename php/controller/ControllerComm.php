<?php

include_once 'ControllerBase.php';
include_once basename(__DIR__) . '/../model/ProdDatabase.php';

// Controller commerciante, si occupa della gestione degli admin
class ControllerComm extends ControllerBase 
{
    public $prodotto;
    public $user;

    // Costruttore
    public function __construct() 
    {
        parent::__construct();
    }
    
    public function handleInput(&$request) 
    {
        // creo il descrittore della vista
        $vd = new ViewDescriptor();
        
        // Imposto la sottopagina iniziale
        $vd->setSottoPagina('home');
        
        // Qui arrivano le richieste per inserire un prodotto nel carrello
        if(isset($request["carrello"]))
        {
            // Salvo il prodotto selezionato nel carrello dell' utente
            $user = UserDatabase::instance()->cercaUtentePerId($_SESSION[self::user], $_SESSION[self::role]);          
            
            ProdDatabase::instance()->saveUserAndProduct($user->getId(), $request["carrello"]);          
            
            $vd->setSottoPagina("carrello");
        }
        
        // Eliminato il prodotto selezionato dall' utente dal carrello
        if(isset($request['elimina_da_carrello']))
        {
            // Elimino il prodotto selezionato dal carrello
            ProdDatabase::instance()->deleteFromCart($request["elimina_da_carrello"]);          

            $vd->setSottoPagina("carrello");
        }
        
        // L' utente acquista i prodotti contenuti nel carrello
        if(isset($request['end_shop']))
        {
            // Elimino i prodotti acquistati dall' utente
            ProdDatabase::instance()->deleteUserCart($request["end_shop"]);          

            $vd->setSottoPagina("carrello");
        }
        
        // Modifica un elemento del database
        if(isset($request['modifica']))
        {
            $this->prodotto = ProdDatabase::instance()->loadOneProduct($request['modifica']);
            $vd->setSottoPagina("modifica");
        }
                    
        // Elimina un elemento dal database
        if(isset($request['elimina']))
        {
            ProdDatabase::instance()->deleteProduct($request['elimina']);
            $vd->setSottoPagina("result");
        }
        
        if(isset($request['subpage']))
        {
            switch ($request['subpage'])
            {
                case 'chisiamo':
                    $vd->setSottoPagina("chisiamo");
                    break;
                
                case 'carrello':
                    $vd->setSottoPagina("carrello");
                    break;
                
                case 'partner':
                    $vd->setSottoPagina("partner");
                    break;
                
                case 'add':
                    $vd->setSottoPagina("add");
                    break;
                
                // Aggiungo il prodotto al database
                case 'added':
                    ProdDatabase::instance()->addProduct($request['nome'], 
                                                         $request['tipologia'], 
                                                         $request['schermo'], 
                                                         $request['ram'],
                                                         $request['cpu'],
                                                         $request['hard_disk'], 
                                                         $request['os'], 
                                                         $request['descrizione'], 
                                                         $request['art_disponibili'], 
                                                         $request['prezzo']);
                    $vd->setSottoPagina("result");
                    break;
                
                // Aggiorno la riga selezionata dall' admin per la modifica
                case 'updated':
                    ProdDatabase::instance()->updateProduct($request['nome'], 
                                                            $request['tipologia'], 
                                                            $request['schermo'], 
                                                            $request['ram'],
                                                            $request['cpu'],
                                                            $request['hard_disk'], 
                                                            $request['os'], 
                                                            $request['descrizione'], 
                                                            $request['art_disponibili'], 
                                                            $request['prezzo'],
                                                            $request['id_prodotto']);                    
                    $vd->setSottoPagina("result");
                    break;
                
                case 'cerca':
                    $this->input_search = $request['search'];
                    $vd->setSottoPagina("cerca");
                    break;
                
                // Apre la pagina per cambiare i dati dell' utente
                case 'impostazioni_user':
                    $this->user = UserDatabase::instance()->cercaUtentePerId($_SESSION['user'], $_SESSION['role']);
                    $vd->setSottoPagina("modifica_utente");
                    break;
                
                // Pagina di conferma per il cambio dei dati dell' utente
                case 'updated_user':
                    UserDatabase::instance()->updateUser($request['id_user'],
                                                         $request['nome'],
                                                         $request['cognome'],
                                                         $request['username'],
                                                         $request['password'],
                                                         $request['indirizzo'],
                                                         $request['mail'],
                                                         $request['civico'],
                                                         $request['citta'],
                                                         $request['cap'],
                                                         $request['provincia']);
                    $vd->setSottoPagina("result_user");
                    break;
            }
        }
        $this->showHomeComm($vd); 
    }
}
