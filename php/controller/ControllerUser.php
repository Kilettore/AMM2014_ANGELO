<?php

include_once 'ControllerBase.php';
include_once basename(__DIR__) . '/../model/ProdDatabase.php';

// Controller che gestisce gli utenti loggati
class ControllerUser extends ControllerBase
{
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
                
                case 'cerca':
                    $this->input_search = $request['search'];
                    $vd->setSottoPagina("cerca");
                    break;
            }
        }
        $this->showHomeUser($vd); 
    }
}

?>
