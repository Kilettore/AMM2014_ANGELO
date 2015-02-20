<?php

include_once 'Base.php';
include_once 'Database.php';

// Classe che gestisce gli utenti di sistema
class UserDatabase 
{

    private static $singleton;

    private function __constructor()
    {
        
    }

    // Restituisce un istanza per poter lavorare con gli utenti
    public static function instance()
    {
        if (!isset(self::$singleton)) 
        {
            self::$singleton = new UserDatabase();
        }

        return self::$singleton;
    }

    // Carica un utente che fa il login
    public function caricaUtente($username, $password) 
    {
        // eseguo la connessione al database
        $mysqli = Database::connectDatabase();
        if (!isset($mysqli))
        {
            error_log("[caricaUtente] Impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        // definisco la query per caricare l' utente
        $query = 
            "select *
            from user 
            where user.username = ? and user.password = ?";
        
        // Precompilo la query con il prepared statement
        $precomp = $mysqli->stmt_init();
        $precomp->prepare($query);
        if (!$precomp) 
        {
            error_log("[caricaUtente] Impossibile inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }
        
        // Con il bind lego i punti di domanda presenti nella query e li sostituisco con le variabili che mi servono
        if (!$precomp->bind_param('ss', $username, $password)) 
        {
            error_log("[caricaUtente] Impossibile effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $utente = self::caricaUtenteDaStatement($precomp);
        if (isset($utente))
        {
            // ho trovato un utente
            $mysqli->close();
            return $utente;
        }
    }
    
    // Aggiorna i dati dell' utente
    public function updateUser($id_user, $nome, $cognome, $username, $password, $indirizzo, $email, $civico, $citta, $cap, $provincia)
    {
        $mysqli = Database::connectDatabase();
        if (!isset($mysqli))
        {
            error_log("[updateUser] Impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        // definisco la query per aggiornare i dati dell' utente
        $query = 
            "UPDATE user 
             SET user.nome = ?, 
                 user.cognome = ?, 
                 user.username = ?, 
                 user.password = ?, 
                 user.indirizzo = ?, 
                 user.email = ?, 
                 user.numero_civico = ?, 
                 user.citta = ?,
                 user.cap = ?, 
                 user.provincia = ? 
             WHERE user.id_user = ?";
        
        // Precompilo la query con il prepared statement
        $precomp = $mysqli->stmt_init();
        $precomp->prepare($query);
        if (!$precomp) 
        {
            error_log("[updateUser] Impossibile inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }
        
        // Con il bind lego i punti di domanda presenti nella query e li sostituisco con le variabili che mi servono
        if (!$precomp->bind_param('ssssssisisi', $nome, $cognome, $username, $password, $indirizzo, $email, $civico, $citta, $cap, $provincia, $id_user)) 
        {
            error_log("[updateUser] Impossibile effettuare il binding in input");
            $mysqli->close();
            return null;
        }      

        // Eseguo la query che non deve restituire nulla in uscita
        $precomp->execute();
 
        $precomp->close();       
    }

    // Cerca un utente in base al suo id
    // Funzione molto utile che in combinazione con $_SESSION consente di mantenere al sito la sessione
    public function cercaUtentePerId($id, $role) 
    {
        $intval = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (!isset($intval)) 
        {
            return null;
        }
        
        $mysqli = Database::connectDatabase();
        if (!isset($mysqli)) 
        {
            error_log("[cercaUtentePerId] Impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }
        
        // Definisco la query per cercare l' utente tramite id
        $query = 
            "select *
            from user 
            where user.id_user = ?";
        
        $precomp = $mysqli->stmt_init();
        $precomp->prepare($query);
        if (!$precomp) 
        {
            error_log("[cercaUtentePerId] Impossibile inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$precomp->bind_param('i', $intval)) 
        {
            error_log("[cercaUtentePerId] Impossibile effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $utente = self::caricaUtenteDaStatement($precomp);
        if (isset($utente))
        {
            // ho trovato un utente
            $mysqli->close();
            return $utente;
        }
    }

    // Crea un oggetto utente a partire da un array
    public function creaUtenteDaArray($row) 
    {
        $utente = new Base();
        $utente->setId($row['user.id_user']);
        $utente->setNome($row['user.nome']);
        $utente->setCognome($row['user.cognome']);
        $utente->setUsername($row['user.username']);
        $utente->setPassword($row['user.password']);
        if($row['user.tipo_utente'] == 1)
        {
            $utente->setTipoUtente(Base::user);
        }
        else
        {
            $utente->setTipoUtente(Base::comm);
        }
        $utente->setIndirizzo($row['user.indirizzo']);
        $utente->setEmail($row['user.email']);
        $utente->setCivico($row['user.numero_civico']);
        $utente->setCitta($row['user.citta']);
        $utente->setCap($row['user.cap']);
        $utente->setProvincia($row['user.provincia']);

        return $utente;
    }

    // Restituisce un utente
    private function caricaUtenteDaStatement(mysqli_stmt $precomp) 
    {
        if (!$precomp->execute())
        {
            error_log("[caricaUtenteDaStmt] impossibile eseguire lo statement");
            return null;
        }
        
        // Dichiaro un array che conterr' i dati dell- utente appena loggato
        $row = array();
        $bind = $precomp->bind_result($row['user.id_user'], $row['user.nome'], $row['user.cognome'], $row['user.username'], $row['user.password'], $row['user.tipo_utente'], $row['user.indirizzo'], $row['user.email'], $row['user.numero_civico'], $row['user.citta'], $row['user.cap'], $row['user.provincia']);
        if (!$bind)
        {
            error_log("[caricaStudenteDaStmt] impossibile effettuare il binding in output");
            return null;
        }

        if (!$precomp -> fetch()) 
        {
            return null;
        }

        $precomp->close();

        return self::creaUtenteDaArray($row);
    }
}

?>
