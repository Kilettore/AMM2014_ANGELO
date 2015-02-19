<?php

include_once 'ControllerBase.php';
include_once basename(__DIR__) . '/../model/ProdDatabase.php';

/**
 * Controller che gestisce la modifica dei dati dell'applicazione relativa ai 
 * Docenti da parte di utenti con ruolo Docente o Amministratore 
 *
 * @author Davide Spano
 */
class ControllerComm extends ControllerBase 
{
    public $prodotto;
    //private $input_search;

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
            echo "Il nome del prodotto e': ".$this->prodotto->getNome()."<br>";
            
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
                
                case 'added':
                    // Qui devo aggiungere il prodotto al database
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
                
                case 'updated':
                    // Aggiorno la riga modificata del database
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
            }
        }
        $this->showHomeComm($vd); 
    }
}
