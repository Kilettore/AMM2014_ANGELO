<?php

/**
 * Classe che contiene una lista di variabili di configurazione
 *
 * @author Davide Spano
 */
class Impostazioni 
{

    // variabili di accesso per il database 
    private static $appPath;

    /**
     * Restituisce il path relativo nel server corrente dell'applicazione
     * Lo uso perche' la mia configurazione locale e' ovviamente diversa da quella 
     * pubblica. Gestisco il problema una volta per tutte in questo script
     */
    public static function getApplicationPath() {
        if (!isset(self::$appPath)) 
        {
            //echo "sto per scegliere il nome in base a localhost o server web<br>";
            // restituisce il server corrente
            switch ($_SERVER['HTTP_HOST']) 
            {
                case 'localhost':
                    // configurazione locale
                    self::$appPath = 'http://' . $_SERVER['HTTP_HOST'] . '/esAMM2014/esami14/';
                    break;
                case 'spano.sc.unica.it':
                    // configurazione pubblica
                    self::$appPath = 'http://' . $_SERVER['HTTP_HOST'] . '/amm2014/davide/esami14/';
                    break;

                default:
                    self::$appPath = '';
                    break;
            }
        }
        
        return self::$appPath;
    }

}

?>
