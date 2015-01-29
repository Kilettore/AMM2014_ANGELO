<?php

include_once 'Base.php';
include_once 'Database.php';
//include_once 'Docente.php';
//include_once 'Studente.php';
//include_once 'CorsoDiLaureaFactory.php';
//include_once 'DipartimentoFactory.php';

/**
 * Classe per la creazione degli utenti del sistema
 *
 * @author Davide Spano
 */
class UserDatabase 
{

    private static $singleton;

    private function __constructor()
    {
        
    }

    /**
     * Restiuisce un singleton per creare utenti
     * @return \UserFactory
     */
    public static function instance() {
        if (!isset(self::$singleton)) {
            self::$singleton = new UserDatabase();
        }

        return self::$singleton;
    }

    /**
     * Carica un utente tramite username e password
     * @param string $username
     * @param string $password
     * @return \User|\Docente|\Studente
     */
    public function caricaUtente($username, $password) 
    {
        echo ("carica utente<br>");
        $mysqli = Database::connectDatabase();
        if (!isset($mysqli))
        {
            error_log("[loadUser] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }
        echo "connessione al database stabilita con successo!<br>";

        // cerco prima nella tabella utenti
        $query = 
            "select *
            from user 
            where user.username = ? and user.password = ?";
            
            /*studenti.id studenti_id,
            studenti.nome studenti_nome,
            studenti.cognome studenti_cognome,
            studenti.matricola studenti_matricola,
            studenti.email studenti_email,
            studenti.citta studenti_citta,
            studenti.via studenti_via,
            studenti.cap studenti_cap,
            studenti.provincia studenti_provincia,
            studenti.numero_civico studenti_numero_civico,
            studenti.username studenti_username,
            studenti.password studenti_password,
            
            CdL.id CdL_id,
            CdL.nome CdL_nome,
            CdL.codice CdL_codice,
            
            dipartimenti.id dipartimenti_id,
            dipartimenti.nome dipartimenti_nome
            
            from studenti 
            join CdL on studenti.cdl_id = CdL.id
            join dipartimenti on CdL.dipartimento_id = dipartimenti.id
            where studenti.username = ? and studenti.password = ?"; */
        
        // Precompilo la query con il prepared statement
        $precomp = $mysqli->stmt_init();
        $precomp->prepare($query);
        if (!$precomp) 
        {
            error_log("[loadUser] impossibile inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }
        
        // Con il bind lego i punti di domanda presenti nella query e li sostituisco con le variabili che mi servono
        if (!$precomp->bind_param('ss', $username, $password)) 
        {
            error_log("[loadUser] impossibile effettuare il binding in input");
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

        // ora cerco un docente
        $query = "select 
               docenti.id docenti_id,
               docenti.nome docenti_nome,
               docenti.cognome docenti_cognome,
               docenti.email docenti_email,
               docenti.citta docenti_citta,
               docenti.cap docenti_cap,
               docenti.via docenti_via,
               docenti.provincia docenti_provincia,
               docenti.numero_civico docenti_numero_civico,
               docenti.ricevimento docenti_ricevimento,
               docenti.username docenti_username,
               docenti.password docenti_password,
               dipartimenti.id dipartimenti_id,
               dipartimenti.nome dipartimenti_nome
               
               from docenti 
               join dipartimenti on docenti.dipartimento_id = dipartimenti.id
               where docenti.username = ? and docenti.password = ?";

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[loadUser] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$stmt->bind_param('ss', $username, $password)) {
            error_log("[loadUser] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $docente = self::caricaDocenteDaStmt($stmt);
        if (isset($docente)) {
            // ho trovato un docente
            $mysqli->close();
            return $docente;
        }
    }

    /**
     * Restituisce un array con i Docenti presenti nel sistema
     * @return array
     */
    public function &getListaDocenti() {
        $docenti = array();
        $query = "select 
               docenti.id docenti_id,
               docenti.nome docenti_nome,
               docenti.cognome docenti_cognome,
               docenti.email docenti_email,
               docenti.citta docenti_citta,
               docenti.cap docenti_cap,
               docenti.via docenti_via,
               docenti.provincia docenti_provincia,
               docenti.numero_civico docenti_numero_civico,
               docenti.ricevimento docenti_ricevimento,
               docenti.username docenti_username,
               docenti.password docenti_password,
               dipartimenti.id dipartimenti_id,
               dipartimenti.nome dipartimenti_nome
               
               from docenti 
               join dipartimenti on docenti.dipartimento_id = dipartimenti.id";
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getListaDocenti] impossibile inizializzare il database");
            $mysqli->close();
            return $docenti;
        }
        $result = $mysqli->query($query);
        if ($mysqli->errno > 0) {
            error_log("[getListaDocenti] impossibile eseguire la query");
            $mysqli->close();
            return $docenti;
        }

        while ($row = $result->fetch_array()) {
            $docenti[] = self::creaDocenteDaArray($row);
        }

        $mysqli->close();
        return $docenti;
    }

    /**
     * Restituisce la lista degli studenti presenti nel sistema
     * @return array
     */
    public function &getListaStudenti() {
        $studenti = array();
        $query = "select * from studenti " .
                "join CdL on cdl_id = CdL.id" .
                "join dipartimenti on CdL.dipartimento_id = dipartimenti.id";
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getListaStudenti] impossibile inizializzare il database");
            $mysqli->close();
            return $studenti;
        }
        $result = $mysqli->query($query);
        if ($mysqli->errno > 0) {
            error_log("[getListaStudenti] impossibile eseguire la query");
            $mysqli->close();
            return $studenti;
        }

        while ($row = $result->fetch_array()) {
            $studenti[] = self::creaStudenteDaArray($row);
        }

        return $studenti;
    }

    /**
     * Carica uno studente dalla matricola
     * @param int $matricola la matricola da cercare
     * @return Studente un oggetto Studente nel caso sia stato trovato,
     * NULL altrimenti
     */
    public function cercaStudentePerMatricola($matricola) {


        $intval = filter_var($matricola, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (!isset($intval)) {
            return null;
        }

        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[cercaStudentePerMatricola] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        $query = "select studenti.id studenti_id,
            studenti.nome studenti_nome,
            studenti.cognome studenti_cognome,
            studenti.matricola studenti_matricola,
            studenti.email studenti_email,
            studenti.citta studenti_citta,
            studenti.via studenti_via,
            studenti.cap studenti_cap,
            studenti.provincia studenti_provincia,
            studenti.numero_civico studenti_numero_civico,
            studenti.username studenti_username,
            studenti.password studenti_password,
            
            CdL.id CdL_id,
            CdL.nome CdL_nome,
            CdL.codice CdL_codice,
            
            dipartimenti.id dipartimenti_id,
            dipartimenti.nome dipartimenti_nome
            
            from studenti 
            join CdL on studenti.cdl_id = CdL.id
            join dipartimenti on CdL.dipartimento_id = dipartimenti.id
            where studenti.matricola = ?";
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[cercaStudentePerMatricola] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$stmt->bind_param('i', $intval)) {
            error_log("[cercaStudentePerMatricola] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $toRet =  self::caricaStudenteDaStmt($stmt);
        $mysqli->close();
        return $toRet;
    }

    /**
     * Cerca uno studente per id
     * @param int $id
     * @return Studente un oggetto Studente nel caso sia stato trovato,
     * NULL altrimenti
     */
    public function cercaUtentePerId($id, $role) {
        $intval = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (!isset($intval)) {
            return null;
        }
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[cercaUtentePerId] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        switch ($role) {
            case User::Studente:
                $query = "select 
            studenti.id studenti_id,
            studenti.nome studenti_nome,
            studenti.cognome studenti_cognome,
            studenti.matricola studenti_matricola,
            studenti.email studenti_email,
            studenti.citta studenti_citta,
            studenti.via studenti_via,
            studenti.cap studenti_cap,
            studenti.provincia studenti_provincia, 
            studenti.numero_civico studenti_numero_civico,
            studenti.username studenti_username,
            studenti.password studenti_password,
            
            CdL.id CdL_id,
            CdL.nome CdL_nome,
            CdL.codice CdL_codice,
            
            dipartimenti.id dipartimenti_id,
            dipartimenti.nome dipartimenti_nome
            
            from studenti 
            join CdL on studenti.cdl_id = CdL.id
            join dipartimenti on CdL.dipartimento_id = dipartimenti.id
            where studenti.id = ?";
                $stmt = $mysqli->stmt_init();
                $stmt->prepare($query);
                if (!$stmt) {
                    error_log("[cercaUtentePerId] impossibile" .
                            " inizializzare il prepared statement");
                    $mysqli->close();
                    return null;
                }

                if (!$stmt->bind_param('i', $intval)) {
                    error_log("[cercaUtentePerId] impossibile" .
                            " effettuare il binding in input");
                    $mysqli->close();
                    return null;
                }

                return self::caricaStudenteDaStmt($stmt);
                break;

            case User::Docente:
                $query = "select 
               docenti.id docenti_id,
               docenti.nome docenti_nome,
               docenti.cognome docenti_cognome,
               docenti.email docenti_email,
               docenti.citta docenti_citta,
               docenti.cap docenti_cap,
               docenti.via docenti_via,
               docenti.provincia docenti_provincia,
               docenti.numero_civico docenti_numero_civico,
               docenti.ricevimento docenti_ricevimento,
               docenti.username docenti_username,
               docenti.password docenti_password,
               dipartimenti.id dipartimenti_id,
               dipartimenti.nome dipartimenti_nome
               
               from docenti 
               join dipartimenti on docenti.dipartimento_id = dipartimenti.id
               where docenti.id = ?";

                $stmt = $mysqli->stmt_init();
                $stmt->prepare($query);
                if (!$stmt) {
                    error_log("[cercaUtentePerId] impossibile" .
                            " inizializzare il prepared statement");
                    $mysqli->close();
                    return null;
                }

                if (!$stmt->bind_param('i', $intval)) {
                    error_log("[loadUser] impossibile" .
                            " effettuare il binding in input");
                    $mysqli->close();
                    return null;
                }

                $toRet =  self::caricaDocenteDaStmt($stmt);
                $mysqli->close();
                return $toRet;
                break;

            default: return null;
        }
    }

    /**
     * Crea uno studente da una riga del db
     * @param type $row
     * @return \Studente
     */
    public function creaUtenteDaArray($row) 
    {
        echo "crea utente da array<br>";
        $utente = new Base();
        $utente->impostaId($row['user.id_user']);
        $utente->impostaNome($row['user.nome']);
        $utente->impostaCognome($row['user.cognome']);
        $utente->impostaUsername($row['user.username']);
        $utente->impostaPassword($row['user.password']);
        $utente->impostaTipoUtente(Base::user);
        $utente->impostaIndirizzo($row['user.indirizzo']);
        $utente->impostaEmail($row['user.email']);
        $utente->impostaCivico($row['user.numero_civico']);
        $utente->impostaCitta($row['user.citta']);
        $utente->impostaCap($row['user.cap']);
        $utente->impostaProvincia($row['user.provincia']);

        /*if (isset($row['CdL_id']))
            $studente->setCorsoDiLaurea(CorsoDiLaureaFactory::instance()->creaDaArray($row));*/
        echo "utente da array creato<br>";
        return $utente;
    }

    /**
     * Crea un docente da una riga del db
     * @param type $row
     * @return \Docente
     */
    public function creaDocenteDaArray($row) {
        $docente = new Docente();
        $docente->setId($row['docenti_id']);
        $docente->setNome($row['docenti_nome']);
        $docente->setCognome($row['docenti_cognome']);
        $docente->setEmail($row['docenti_email']);
        $docente->setCap($row['docenti_cap']);
        $docente->setCitta($row['docenti_citta']);
        $docente->setVia($row['docenti_via']);
        $docente->setProvincia($row['docenti_provincia']);
        $docente->setNumeroCivico($row['docenti_numero_civico']);
        $docente->setRicevimento($row['docenti_ricevimento']);
        $docente->setRuolo(User::Docente);
        $docente->setUsername($row['docenti_username']);
        $docente->setPassword($row['docenti_password']);

        $docente->setDipartimento(DipartimentoFactory::instance()->creaDaArray($row));
        return $docente;
    }

    /**
     * Salva i dati relativi ad un utente sul db
     * @param User $user
     * @return il numero di righe modificate
     */
    public function salva(User $user) {
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[salva] impossibile inizializzare il database");
            $mysqli->close();
            return 0;
        }

        $stmt = $mysqli->stmt_init();
        $count = 0;
        switch ($user->getRuolo()) {
            case User::Studente:
                $count = $this->salvaStudente($user, $stmt);
                break;
            case User::Docente:
                $count = $this->salvaDocente($user, $stmt);
        }

        $stmt->close();
        $mysqli->close();
        return $count;
    }

    /**
     * Rende persistenti le modifiche all'anagrafica di uno studente sul db
     * @param Studente $s lo studente considerato
     * @param mysqli_stmt $stmt un prepared statement
     * @return int il numero di righe modificate
     */
    private function salvaStudente(Studente $s, mysqli_stmt $stmt) {
        $query = " update studenti set 
                    password = ?,
                    nome = ?,
                    cognome = ?,
                    email = ?,
                    numero_civico = ?,
                    citta = ?,
                    provincia = ?,
                    matricola = ?,
                    cap = ?,
                    via = ?
                    where studenti.id = ?
                    ";
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[salvaStudente] impossibile" .
                    " inizializzare il prepared statement");
            return 0;
        }

        if (!$stmt->bind_param('ssssississi', $s->getPassword(), $s->getNome(), $s->getCognome(), $s->getEmail(), $s->getNumeroCivico(), $s->getCitta(), $s->getProvincia(), $s->getMatricola(), $s->getCap(), $s->getVia(), $s->getId())) {
            error_log("[salvaStudente] impossibile" .
                    " effettuare il binding in input");
            return 0;
        }

        if (!$stmt->execute()) {
            error_log("[caricaIscritti] impossibile" .
                    " eseguire lo statement");
            return 0;
        }

        return $stmt->affected_rows;
    }
    
    /**
     * Rende persistenti le modifiche all'anagrafica di un docente sul db
     * @param Docente $d il docente considerato
     * @param mysqli_stmt $stmt un prepared statement
     * @return int il numero di righe modificate
     */
    private function salvaDocente(Docente $d, mysqli_stmt $stmt) {
        $query = " update docenti set 
                    password = ?,
                    nome = ?,
                    cognome = ?,
                    email = ?,
                    citta = ?,
                    provincia = ?,
                    cap = ?,
                    via = ?,
                    ricevimento = ?,
                    numero_civico = ?,
                    dipartimento_id = ?
                    where docenti.id = ?
                    ";
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[salvaStudente] impossibile" .
                    " inizializzare il prepared statement");
            return 0;
        }

        if (!$stmt->bind_param('sssssssssiii', 
                $d->getPassword(), 
                $d->getNome(), 
                $d->getCognome(), 
                $d->getEmail(), 
                $d->getCitta(),
                $d->getProvincia(),
                $d->getCap(), 
                $d->getVia(), 
                $d->getRicevimento(),
                $d->getNumeroCivico(), 
                $d->getDipartimento()->getId(),
                $d->getId())) {
            error_log("[salvaStudente] impossibile" .
                    " effettuare il binding in input");
            return 0;
        }

        if (!$stmt->execute()) {
            error_log("[caricaIscritti] impossibile" .
                    " eseguire lo statement");
            return 0;
        }

        return $stmt->affected_rows;
    }

    /**
     * Carica un docente eseguendo un prepared statement
     * @param mysqli_stmt $stmt
     * @return null
     */
    private function caricaDocenteDaStmt(mysqli_stmt $stmt) {

        if (!$stmt->execute()) {
            error_log("[caricaDocenteDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['docenti_id'], 
                $row['docenti_nome'], 
                $row['docenti_cognome'], 
                $row['docenti_email'], 
                $row['docenti_citta'],
                $row['docenti_cap'],
                $row['docenti_via'],
                $row['docenti_provincia'], 
                $row['docenti_numero_civico'],
                $row['docenti_ricevimento'],
                $row['docenti_username'], 
                $row['docenti_password'], 
                $row['dipartimenti_id'], 
                $row['dipartimenti_nome']);
        if (!$bind) {
            error_log("[caricaDocenteDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        if (!$stmt->fetch()) {
            return null;
        }

        $stmt->close();

        return self::creaDocenteDaArray($row);
    }

    /**
     * Carica uno studente eseguendo un prepared statement
     * @param mysqli_stmt $stmt
     * @return null
     */
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
