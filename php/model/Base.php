<?php

// Classe per rappresentare un generico utente
class Base
{
    //Costante che definisce il ruolo di utente che ha effettuato il login
    const user = 1;
    
    //Costante che definisce il ruolo commerciante (in questo caso è anche admin)
    const comm = 2;
    
    // Id dell' utente
    private $id;

    // Username dell' utente
    private $username;
    
    // Password dell' utente
    private $password;
    
    // Nome dell' utente
    private $nome;
    
    // Cognome dell' utente
    private $cognome;
    
    // Email dell' utente
    private $email;
    
    // Indirizzo dell' utente
    private $indirizzo;
     
    /* Il tipo dell'utente.
     * Può essere uno user normale, un admin o un commerciante. Uso questa
     * variabile per controllare gli accessi al database e caricare di
     * conseguenza una pagina consona all' utente che ha fatto il login
     */
    private $tipo;
    
    // Numero civico
    private $numeroCivico;
    
    // Citta
    private $citta;
    
    // Provincia di residenza
    private $provincia;
    
    // Cap dell' utente
    private $cap;
    
    // Costruttore
    public function __construct()
    {
        
    }

    /**
     * Verifica se l'utente esista per il sistema
     * @return boolean true se l'utente esiste, false altrimenti
     */
    public function esiste()
    {
        // implementazione di comodo, va fatto con il db
        return isset($this->tipo);
    }

    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Imposta lo username per l'autenticazione dell'utente. 
     * I nomi che si ritengono validi contengono solo lettere maiuscole e minuscole.
     * La lunghezza del nome deve essere superiore a 5
     * @param string $username
     * @return boolean true se lo username e' ammissibile ed e' stato impostato,
     * false altrimenti
     */
    public function impostaUsername($username) 
    {
        // utilizzo la funzione filter var specificando un'espressione regolare
        // che implementa la validazione personalizzata
        if (!filter_var($username, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/[a-zA-Z]{5,}/')))) 
        {
            return false;
        }
        $this->username = $username;
        echo username;
        return true;
    }

    public function getPassword() 
    {
        return $this->password;
    }

    public function impostaPassword($password)
    {
        $this->password = $password;
        return true;
    }

    public function getNome() 
    {
        return $this->nome;
    }

    public function impostaNome($nome) 
    {
        $this->nome = $nome;
        return true;
    }

    public function getCognome() 
    {
        return $this->cognome;
    }

    public function impostaCognome($cognome) 
    {
        $this->cognome = $cognome;
        return true;
    }

    public function getTipoUtente()
    {
        return $this->tipo;
    }

    public function impostaTipoUtente($tipoUser) 
    {
        switch ($tipoUser) 
        {
            case self::user:
            case self::comm:
                $this->tipo = $tipoUser;
                return true;
            default:
                return false;
        }
    }

    public function getEmail() {
        return $this->email;
    }

    public function impostaEmail($email) 
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        $this->email = $email;
        return true;
    }

    public function getVia() {
        return $this->via;
    }

    public function impostaIndirizzo($via) 
    {
        $this->via = $via;
        return true;
    }

    public function getNumeroCivico()
    {
        return $this->numeroCivico;
    }

    public function impostaCivico($civico) 
    {
        $intVal = filter_var($civico, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (isset($intVal)) {
            $this->numeroCivico = $intVal;
            return true;
        }
        return false;
    }

    public function impostaCitta($citta)
    {
        $this->citta = $citta;
        return true;
    }

    public function getCitta() 
    {
        return $this->citta;
    }
    
    public function impostaProvincia($provincia) 
    {
        $this->provincia = $provincia;
        return true;
    }

    public function getProvincia() 
    {
        return $this->provincia;
    }

    // Restituisce il codice di avviamento postale dell' utente
    public function getCap() 
    {
        return $this->cap;
    }

    // Imposta il codice di avviamento postale dell' utente
    public function impostaCap($cap) 
    {
        if (!filter_var($cap, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/[0-9]{5}/')))) 
        {
            return false;
        }
        $this->cap = $cap;
        return true;
    }

    
    // Restituisce l' id utente
    public function getId(){
        return $this->id;
    }
    
    // Imposta l' Id utente
    public function impostaId($id){
        $intVal = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if(!isset($intVal)){
            return false;
        }
        $this->id = $intVal;
    }
    
    /**
     * Compara due utenti, verificandone l'uguaglianza logica
     * @param User $user l'utente con cui comparare $this
     * @return boolean true se i due oggetti sono logicamente uguali, 
     * false altrimenti
     */
    public function equals(User $user) {

        return  $this->id == $user->id &&
                $this->nome == $user->nome &&
                $this->cognome == $user->cognome &&
                $this->ruolo == $user->ruolo;
    }

}

?>
