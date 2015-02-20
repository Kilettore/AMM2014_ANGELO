<?php
// Struttura dati per popolare index.php, il quale contiene solo lo "scheletro" del sito
class ViewDescriptor 
{
    const get = 'get';
    const post = 'post';
    
    private $title; //titolo della pagina nella finestra del browser
    private $logo_file;
    private $menu_file;
    private $login_content;
    private $leftBar_file;
    private $rightBar_file;
    private $content_file;
    private $messaggioErrore;
    private $messaggioConferma;
    private $pagina;
    private $sottoPagina;
    private $footer;
    private $js;
    private $json;
    
    // Costruttore
    public function __construct()
    {
        $this->js = array();
        $this->json = false;
    }

    public function getTitle() 
    {
        return $this->title;
    }

    public function setTitle($title) 
    {
        $this->title = $title;
    }

    public function setLogoFile($logoFile) {
        $this->logo_file = $logoFile;
    }

    public function getLogoFile() {
        return $this->logo_file;
    }
    
    public function getLoginContent()
    {
        return $this->login_content;
    }
    
    public function setLoginContent($loginContent)
    {
        $this->login_content = $loginContent;
    }

    public function getMenuFile()
    {
        return $this->menu_file;
    }

    public function setMenuFile($menuFile) 
    {
        $this->menu_file = $menuFile;
    }

    public function getLeftBarFile() 
    {
        return $this->leftBar_file;
    }

    public function setLeftBarFile($leftBar) 
    {
        $this->leftBar_file = $leftBar;
    }

    public function getRightBarFile() 
    {
        return $this->rightBar_file;
    }
    
    public function setRightBarFile($rightBar) 
    {
        $this->rightBar_file = $rightBar;
    }

    public function setContentFile($contentFile) 
    {
        $this->content_file = $contentFile;
    }

    public function getContentFile() 
    {
        return $this->content_file;
    }
    
    public function getMessaggioErrore() 
    {
        return $this->messaggioErrore;
    }

    public function setMessaggioErrore($msg) 
    {
        $this->messaggioErrore = $msg;
    }

    public function getSottoPagina() 
    {
        return $this->sottoPagina;
    }

    public function setSottoPagina($pag) 
    {
        $this->sottoPagina = $pag;
    }

    public function getMessaggioConferma() 
    {
        return $this->messaggioConferma;
    }

    public function setMessaggioConferma($msg) 
    {
        $this->messaggioConferma = $msg;
    }

    public function getPagina() 
    {
        return $this->pagina;
    }

    public function setPagina($pagina) 
    {
        $this->pagina = $pagina;
    }
    
    public function addScript($nome)
    {
        $this->js[] = $nome;
    }
    
    public function &getScripts()
    {
        return $this->js;
    }
    
    public function isJson()
    {
        return $this->json;
    }
    
    public function toggleJson()
    {
        $this->json = true;
    }
    
    public function setFooter($footer)
    {
        $this->footer = $footer;
    }
    
    public function getFooter()
    {
        return $this->footer;
    }
}

?>
