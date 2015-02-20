<div id="logged_in">
    <span class="login_name">Benvenuto 
    <?php

    include_once basename(__DIR__) . '/../model/UserDatabase.php';

    //carico in memoria l' utente di cui devo visualizzare i dati
    $user = UserDatabase::instance()->cercaUtentePerId($_SESSION[self::user], $_SESSION[self::role]);
    echo $user->getNome().' '.$user->getCognome() ?>
    !</span>

    <form action="index.php" method="post">
        <button id="pulsante_carrello" type="submit" name="subpage" value="carrello">
            <img class="imgcarrello" src="images/carrello.png"><span class="text_button">Carrello</span>
        </button>
        <button id="settings" type="submit" name="subpage" value="impostazioni_user">
            <img class="imgsettings" src="images/settings.png">
        </button>
        <input id="pulsante_logout" type="submit" name="logout" value="Logout">
    </form>
</div>

