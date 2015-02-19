<?php

// Classe per rappresentare un generico utente
class Prodotto
{
    // Id dell' utente
    private $nome;

    // Username dell' utente
    private $tipologia;
    
    // Password dell' utente
    private $schermo;
    
    // Nome dell' utente
    private $ram;
    
    // Cognome dell' utente
    private $cpu;
    
    // Email dell' utente
    private $hard_disk;
    
    // Indirizzo dell' utente
    private $os;
     
 
    private $descrizione;
    
    // Numero civico
    private $art_disponibili;
    
    // Citta
    private $prezzo;
    
    // Provincia di residenza
    private $id_prodotto;
    private $id_carrello;
    
    // Costruttore
    public function __construct()
    {
        
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }
    
    public function getNome()
    {
        return $this->nome;
    }

    public function setTipologia($tipologia)
    {
        $this->tipologia = $tipologia;
    }
    
    public function getTipologia()
    {
        return $this->tipologia;
    }
    
    public function setSchermo($schermo)
    {
        $this->schermo = $schermo;
    }
    
    public function getSchermo()
    {
        return $this->schermo;
    }
    
    public function setRam($ram)
    {
        $this->ram = $ram;
    }
    
    public function getRam()
    {
        return $this->ram;
    }
    
    public function setCpu($cpu)
    {
        $this->cpu = $cpu;
    }
    
    public function getCpu()
    {
        return $this->cpu;
    }
    
    public function setHardDisk($hard_disk)
    {
        $this->hard_disk = $hard_disk;
    }
    
    public function getHardDisk()
    {
        return $this->hard_disk;
    }
    
    public function setOs($os)
    {
        $this->os = $os;
    }
    
    public function getOs()
    {
        return $this->os;
    }
     
    public function setDescrizione($descrizione)
    {
        $this->descrizione = $descrizione;
    }
    
    public function getDescrizione()
    {
        return $this->descrizione;
    }
    
    public function setArtDisponibili($art_disponibili)
    {
        $this->art_disponibili = $art_disponibili; 
    }
    
    public function getArtDisponibili()
    {
        return $this->art_disponibili;
    }
    
    public function setPrezzo($prezzo)
    {
        $this->prezzo = $prezzo;
    }
    
    public function getPrezzo()
    {
        return $this->prezzo;
    }
    
    public function setIdProdotto($id_prodotto)
    {
        $this->id_prodotto = $id_prodotto;
    }
    
    public function getIdProdotto()
    {
        return $this->id_prodotto;
    }
    
    public function setIdCarrello($id_carrello)
    {
        $this->id_carrello = $id_carrello;
    }
    
    public function getIdCarrello()
    {
        return $this->id_carrello;
    }
}
?>

