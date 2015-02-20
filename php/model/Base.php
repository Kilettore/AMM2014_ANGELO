<?php

// Classe per rappresentare un generico utente
class Base
{
    //Costante che definisce il ruolo di utente che ha effettuato il login
    const user = 1;
    
    //Costante che definisce il ruolo commerciante (in questo caso è anche admin)
    const comm = 2;
    
    private $id;
    private $username;
    private $password;
    private $nome;
    private $cognome;
    private $email;
    private $via;
    private $tipo;
    private $numeroCivico;
    private $citta;
    private $provincia;
    private $cap;
    
    // Costruttore
    public function __construct()
    {
        
    }

    public function esiste()
    {
        // implementazione di comodo, va fatto con il db
        return isset($this->tipo);
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username) 
    {
        $this->username = $username;
        return true;
    }

    public function getPassword() 
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return true;
    }

    public function getNome() 
    {
        return $this->nome;
    }

    public function setNome($nome) 
    {
        $this->nome = $nome;
        return true;
    }

    public function getCognome() 
    {
        return $this->cognome;
    }

    public function setCognome($cognome) 
    {
        $this->cognome = $cognome;
        return true;
    }

    public function getTipoUtente()
    {
        return $this->tipo;
    }

    public function setTipoUtente($tipoUser) 
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

    public function getEmail() 
    {
        return $this->email;
    }

    public function setEmail($email) 
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            return false;
        }
        $this->email = $email;
        return true;
    }

    public function getIndirizzo()
    {
        return $this->via;
    }

    public function setIndirizzo($via) 
    {
        $this->via = $via;
        return true;
    }

    public function getCivico()
    {
        return $this->numeroCivico;
    }

    public function setCivico($civico) 
    {
        $intVal = filter_var($civico, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (isset($intVal)) 
        {
            $this->numeroCivico = $intVal;
            return true;
        }
        return false;
    }

    public function setCitta($citta)
    {
        $this->citta = $citta;
        return true;
    }

    public function getCitta() 
    {
        return $this->citta;
    }
    
    public function setProvincia($provincia) 
    {
        $this->provincia = $provincia;
        return true;
    }

    public function getProvincia() 
    {
        return $this->provincia;
    }

    public function getCap() 
    {
        return $this->cap;
    }

    public function setCap($cap) 
    {
        $this->cap = $cap;
        return true;
    }

    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $intVal = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if(!isset($intVal))
        {
            return false;
        }
        $this->id = $intVal;
    }
}

?>
