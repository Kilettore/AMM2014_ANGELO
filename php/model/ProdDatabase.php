<?php

include_once 'Base.php';
include_once 'Database.php';
include_once 'Prodotto.php';

// Classe che si occupa della gestione dei prodotti nel database
class ProdDatabase 
{

    private static $singleton;

    private function __constructor()
    {
        
    }

    // Restiuisce un singleton per creare prodotti
    public static function instance() {
        if (!isset(self::$singleton)) {
            self::$singleton = new ProdDatabase();
        }

        return self::$singleton;
    }

    // Carica tutti i prodotti presenti nel database e li visualizza nella home
    public function loadEveryProduct() 
    {
        // eseguo la connessione al database
        $mysqli = Database::connectDatabase();
        if (!isset($mysqli))
        {
            error_log("[loadEveryProduct] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        // definisco la query per selezionare tutti i prodotti nel database
        $query = 
            "SELECT *
             FROM prodotto";
        
        //eseguo la query
        $result = $mysqli->query($query);
        
        // chiudo la connessione con il database
        $mysqli->close();
            
        return $result;
    }
    
    // Salva nel database il prodotto scelto dall' utente che dovrà restare nel carrello
    public function saveUserAndProduct($id_user, $id_prodotto)
    { 
        $mysqli = Database::connectDatabase();
        if (!isset($mysqli))
        {
            error_log("[saveUserAndProduct] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        // definisco la query per salvare i dati di utente e prodotto nel carrello
        $query = 
            "INSERT INTO carrello (id_user, id_prodotto) 
             VALUES (?, ?)";
        
        // Precompilo la query con il prepared statement
        $precomp = $mysqli->stmt_init();
        $precomp->prepare($query);
        if (!$precomp) 
        {
            error_log("[saveUserAndProduct] impossibile inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }
        
        // Con il bind lego i punti di domanda presenti nella query e li sostituisco con le variabili che mi servono
        if (!$precomp->bind_param('ii', $id_user, $id_prodotto)) 
        {
            error_log("[saveUserAndProduct] impossibile effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        // Eseguo la query che non deve restituire nulla in uscita
        $precomp->execute();
 
        $precomp->close();       
    }
    
    // Carica carrello
    public function &loadCart($id_user)
    {
        $mysqli = Database::connectDatabase();
        if (!isset($mysqli))
        {
            error_log("[loadCart] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        // definisco la query per caricare il carrello di un determinato utente
        $query = 
            "SELECT prodotto.*, carrello.id_carrello
             FROM carrello JOIN prodotto
             WHERE carrello.id_user = ? and carrello.id_prodotto = prodotto.id_prodotto";
        
        // Precompilo la query con il prepared statement
        $precomp = $mysqli->stmt_init();
        $precomp->prepare($query);
        if (!$precomp) 
        {
            error_log("[loadCart] impossibile inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }
        
        // Con il bind lego i punti di domanda presenti nella query e li sostituisco con le variabili che mi servono
        if (!$precomp->bind_param('i', $id_user)) 
        {
            error_log("[loadCart] impossibile effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        // Eseguo la query che non deve restituire nulla in uscita
        $prodotti = self::caricaProdottiDaStatement($precomp);
        if (isset($prodotti))
        {
            // ho trovato un utente
            $mysqli->close();
            return $prodotti;
        }
    }
    
    // Aggiunge un prodotto al database
    public function addProduct($nome, $tipologia, $schermo, $ram, $cpu, $hard_disk, $os, $descrizione, $art_disponibili, $prezzo)
    {
        $mysqli = Database::connectDatabase();
        if (!isset($mysqli))
        {
            error_log("[addProduct] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        // definisco la query per aggiungere il prodotto
        $query = 
            "INSERT INTO prodotto (nome, tipologia, schermo, ram, cpu, hard_disk, os, descrizione, art_disponibili, prezzo) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Precompilo la query con il prepared statement
        $precomp = $mysqli->stmt_init();
        $precomp->prepare($query);
        if (!$precomp) 
        {
            error_log("[addProduct] impossibile inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }
        
        // Con il bind lego i punti di domanda presenti nella query e li sostituisco con le variabili che mi servono
        if (!$precomp->bind_param('ssssssssid', $nome, $tipologia, $schermo, $ram, $cpu, $hard_disk, $os, $descrizione, $art_disponibili, $prezzo)) 
        {
            error_log("[addProduct] impossibile effettuare il binding in input");
            $mysqli->close();
            return null;
        }      

        // Eseguo la query che non deve restituire nulla in uscita
        $precomp->execute();
       
        $precomp->close();       
    }
    
    // Funzione che carica un prodotto in base all' id
    public function loadOneProduct($id_prodotto)
    {       
        $mysqli = Database::connectDatabase();
        if (!isset($mysqli))
        {
            error_log("[loadOneProduct] Impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        // definisco la query per trovare il prodotto tramite id
        $query = 
            "SELECT *
             FROM prodotto
             WHERE prodotto.id_prodotto = ?";
        
        // Precompilo la query con il prepared statement
        $precomp = $mysqli->stmt_init();
        $precomp->prepare($query);
        if (!$precomp) 
        {
            error_log("[loadOneProduct] Impossibile inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }
        
        // Con il bind lego i punti di domanda presenti nella query e li sostituisco con le variabili che mi servono
        if (!$precomp->bind_param('i', $id_prodotto))
        {
            error_log("[loadOneProduct] Impossibile effettuare il binding in input");
            $mysqli->close();
            return null;
        }
        
        $prodotto = self::caricaProdottoDaStatement($precomp);
        return $prodotto;
    }
    
    // Aggiorna un prodotto del database
    public function updateProduct($nome, $tipologia, $schermo, $ram, $cpu, $hard_disk, $os, $descrizione, $art_disponibili, $prezzo, $id_prodotto)
    {
        $mysqli = Database::connectDatabase();
        if (!isset($mysqli))
        {
            error_log("[updateProduct] Impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        // definisco la query per aggiornare un prodotto
        $query = 
            "UPDATE prodotto 
             SET prodotto.nome = ?, 
                 prodotto.tipologia = ?, 
                 prodotto.schermo = ?, 
                 prodotto.ram = ?, 
                 prodotto.cpu = ?, 
                 prodotto.hard_disk = ?, 
                 prodotto.os = ?, 
                 prodotto.descrizione = ?,
                 prodotto.art_disponibili = ?, 
                 prodotto.prezzo = ? 
             WHERE prodotto.id_prodotto = ?";
        
        // Precompilo la query con il prepared statement
        $precomp = $mysqli->stmt_init();
        $precomp->prepare($query);
        if (!$precomp) 
        {
            error_log("[updateProduct] Impossibile inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }
        
        // Con il bind lego i punti di domanda presenti nella query e li sostituisco con le variabili che mi servono
        if (!$precomp->bind_param('ssssssssidi', $nome, $tipologia, $schermo, $ram, $cpu, $hard_disk, $os, $descrizione, $art_disponibili, $prezzo, $id_prodotto)) 
        {
            error_log("[updateProduct] Impossibile effettuare il binding in input");
            $mysqli->close();
            return null;
        }      

        // Eseguo la query che non deve restituire nulla in uscita
        $precomp->execute();
 
        $precomp->close();       
    }
    
    // Elimina un prodotto dal database
    public function deleteProduct($id_prodotto)
    {       
        $mysqli = Database::connectDatabase();
        if (!isset($mysqli))
        {
            error_log("[deleteProduct] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        // definisco la query per eliminare il prodotto in base all' id
        $query = 
            "DELETE FROM prodotto
             WHERE prodotto.id_prodotto = ?";
        
        // Precompilo la query con il prepared statement
        $precomp = $mysqli->stmt_init();
        $precomp->prepare($query);
        if (!$precomp) 
        {
            error_log("[deleteProduct] Impossibile inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }
        
        // Con il bind lego i punti di domanda presenti nella query e li sostituisco con le variabili che mi servono
        if (!$precomp->bind_param('i', $id_prodotto))
        {
            error_log("[deleteProduct] Impossibile effettuare il binding in input");
            $mysqli->close();
            return null;
        }
        
        $precomp->execute();
    }
    
    // Elimina un prodotto dal carrello
    public function deleteFromCart($id_carrello)
    {               
        $mysqli = Database::connectDatabase();
        if (!isset($mysqli))
        {
            error_log("[deleteFromCart] Impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        // definisco la query per eliminare il prodotto selezionato nel carrello
        $query = 
            "DELETE
             FROM carrello
             WHERE carrello.id_carrello = ?";
        
        // Precompilo la query con il prepared statement
        $precomp = $mysqli->stmt_init();
        $precomp->prepare($query);
        if (!$precomp) 
        {
            error_log("[deleteFromCart] Impossibile inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }
        
        // Con il bind lego i punti di domanda presenti nella query e li sostituisco con le variabili che mi servono
        if (!$precomp->bind_param('i', $id_carrello))
        {
            error_log("[deleteFromCart] Impossibile effettuare il binding in input");
            $mysqli->close();
            return null;
        }
        
        $precomp->execute();
    }
    
    // Funzione che si attiva quando un utente acquista i prodotti contenuti nel carrello
    // Viene eseguita una transazione
    public function deleteUserCart($id_user)
    {               
        $mysqli = Database::connectDatabase();
        if (!isset($mysqli))
        {
            error_log("[deleteUserCart] Impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        // Inizio la transazione
        mysql_query("START TRANSACTION");

        // Aggiorno gli articoli disponibili per ciascun prodotto
        $query = 
            "UPDATE prodotto
             SET art_disponibili = art_disponibili - 1
             WHERE id_prodotto IN (SELECT id_prodotto
                                   FROM carrello
                                   WHERE id_user = ?)";
        
        // Precompilo la query con il prepared statement
        $precomp = $mysqli->stmt_init();
        $precomp->prepare($query);
        if (!$precomp) 
        {
            error_log("[deleteUserCart] Impossibile inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }
        
        // Con il bind lego i punti di domanda presenti nella query e li sostituisco con le variabili che mi servono
        if (!$precomp->bind_param('i', $id_user))
        {
            error_log("[deleteUserCart] Impossibile effettuare il primo binding in input");
            $mysqli->close();
            return null;
        }
        
        $precomp->execute();
        $precomp->close();
        
        // Passo alla query successiva
        // Ora devo eliminare tutti i prodotti dell' utente contenuti nel carrello
        $query = 
            "DELETE 
             FROM carrello
             WHERE carrello.id_user = ?";
        
        // Precompilo la query con il prepared statement
        $precomp = $mysqli->stmt_init();
        $precomp->prepare($query);
        if (!$precomp) 
        {
            error_log("[deleteUserCart] Impossibile inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }
        
        // Con il bind lego i punti di domanda presenti nella query e li sostituisco con le variabili che mi servono
        if (!$precomp->bind_param('i', $id_user))
        {
            error_log("[deleteUserCart] Impossibile effettuare il secondo binding in input");
            $mysqli->close();
            return null;
        }
        
        $precomp->execute();
        
        // Chiudo la transazione
        mysql_query("COMMIT");
        
        $precomp->close();
    }
    
    // Ricerca del prodotto
    public function &searchProduct($input_search)
    {
        // Creo una stringa che contiene i '%', per facilitare la ricerca
        // Questa stringa la passerò al bind come paramentro di input
        $param = "%{$input_search}%";
        
        $mysqli = Database::connectDatabase();
        if (!isset($mysqli))
        {
            error_log("[searchProduct] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        // definisco la query per cercare il prodotto
        $query = 
            "SELECT *
             FROM prodotto
             WHERE prodotto.nome LIKE ?";
        
        // Precompilo la query con il prepared statement
        $precomp = $mysqli->stmt_init();
        $precomp->prepare($query);
        if (!$precomp) 
        {
            error_log("[searchProduct] Impossibile inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }
        
        // Con il bind lego i punti di domanda presenti nella query e li sostituisco con le variabili che mi servono
        if (!$precomp->bind_param('s', $param)) 
        {
            error_log("[searchProduct] Impossibile effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        // Eseguo la query che non deve restituire nulla in uscita
        $prodotti = self::caricaCercaDaStatement($precomp);
        if (isset($prodotti))
        {
            // ho trovato un utente
            $mysqli->close();
            return $prodotti;
        }
    }
    
    // Restituisce un array che contiene i prodotti del carrello, più l' id del carrello
    private function &caricaProdottiDaStatement(mysqli_stmt $precomp) 
    {
        $prodotti = array();
        if (!$precomp->execute())
        {
            error_log("[caricaProdottiDaStatementt] Impossibile eseguire lo statement");
            return null;
        }
        
        // Dichiaro un array che conterra' i dati dei prodotti da visualizzare nel carrello
        $row = array();
        $bind = $precomp->bind_result(
                $row['prodotto.nome'], 
                $row['prodotto.tipologia'], 
                $row['prodotto.schermo'], 
                $row['prodotto.ram'], 
                $row['prodotto.cpu'], 
                $row['prodotto.hard_disk'], 
                $row['prodotto.os'], 
                $row['prodotto.descrizione'], 
                $row['prodotto.art_disponibili'], 
                $row['prodotto.prezzo'], 
                $row['prodotto.id_prodotto'],
        
                $row['carrello.id_carrello']);
        if (!$bind)
        {
            error_log("[caricaProdottiDaStmt] impossibile effettuare il binding in output");
            return null;
        }
        
        //row contiene una tupla, prodotti contiene tutte le tuple
        while ($precomp->fetch()) 
        {
            $prodotti[] = self::creaProdottoDaArray($row);
        }

        $precomp->close();
        
        return $prodotti;
    }
    
    // Restituisce un array che contiene i prodotti cercati
    private function &caricaCercaDaStatement(mysqli_stmt $precomp) 
    {
        $prodotti = array();
        if (!$precomp->execute())
        {
            error_log("[caricaCercaDaStatementt] Impossibile eseguire lo statement");
            return null;
        }
        
        // Dichiaro un array che conterra' i dati dei prodotti da visualizzare nel carrello
        $row = array();
        $bind = $precomp->bind_result(
                $row['prodotto.nome'], 
                $row['prodotto.tipologia'], 
                $row['prodotto.schermo'], 
                $row['prodotto.ram'], 
                $row['prodotto.cpu'], 
                $row['prodotto.hard_disk'], 
                $row['prodotto.os'], 
                $row['prodotto.descrizione'], 
                $row['prodotto.art_disponibili'], 
                $row['prodotto.prezzo'], 
                $row['prodotto.id_prodotto']);

        if (!$bind)
        {
            error_log("[caricaCercaDaStatement] Impossibile effettuare il binding in output");
            return null;
        }
        
        //row contiene una tupla, prodotti contiene tutte le tuple
        while ($precomp->fetch()) 
        {
            $prodotti[] = self::creaProdottoDaArray($row);
        }

        $precomp->close();
        
        return $prodotti;
    }
    
    // Restituisce un solo prodotto
    private function caricaProdottoDaStatement(mysqli_stmt $precomp) 
    {   
        if (!$precomp->execute())
        {
            error_log("[caricaProdottoDaStatementt] Impossibile eseguire lo statement");
            return null;
        }
        
        // Dichiaro un array che conterra' i dati dei prodotti da visualizzare nel carrello
        $row = array();
        $bind = $precomp->bind_result(
                $row['prodotto.nome'], 
                $row['prodotto.tipologia'], 
                $row['prodotto.schermo'], 
                $row['prodotto.ram'], 
                $row['prodotto.cpu'], 
                $row['prodotto.hard_disk'], 
                $row['prodotto.os'], 
                $row['prodotto.descrizione'], 
                $row['prodotto.art_disponibili'], 
                $row['prodotto.prezzo'], 
                $row['prodotto.id_prodotto']);
        
        if (!$bind)
        {
            error_log("[caricaProdottoDaStmt] Impossibile effettuare il binding in output");
            return null;
        }        
        
        $precomp->fetch();
        $prodotto = self::creaProdottoDaArray($row);
        return $prodotto;
    }
    
    // Crea un oggetto prodotto partendo da un array
    public function creaProdottoDaArray($row) 
    {
        $prodotto = new Prodotto();
        $prodotto->setNome($row['prodotto.nome']);
        $prodotto->setTipologia($row['prodotto.tipologia']);
        $prodotto->setSchermo($row['prodotto.schermo']);
        $prodotto->setRam($row['prodotto.ram']);
        $prodotto->setCpu($row['prodotto.cpu']);
        $prodotto->setHardDisk($row['prodotto.hard_disk']);
        $prodotto->setOs($row['prodotto.os']);
        $prodotto->setDescrizione($row['prodotto.descrizione']);
        $prodotto->setArtDisponibili($row['prodotto.art_disponibili']);
        $prodotto->setPrezzo($row['prodotto.prezzo']);
        $prodotto->setIdProdotto($row['prodotto.id_prodotto']);
        
        if(isset($row['carrello.id_carrello']))
        {
            $prodotto->setIdCarrello($row['carrello.id_carrello']);
        }
        
        return $prodotto;
    }
}

?>

