<?php
    echo "<span class='subpage_text'>Modifica Utente</span><br>";
    echo "<hr>";
?>

<div class="add_form">
    <form action="index.php" method="post">
        <table>
        <tr><td><label for="nome">Nome</label></td>
            <td><input class="mod" type="text" name="nome" value="<?= $this->user->getNome() ?>"></td>
        </tr>
        
        <tr><td><label for="nome">Cognome</label></td>
            <td><input class="mod" type="text" name="cognome" value="<?= $this->user->getCognome() ?>"></td>
        </tr>
        
        <tr><td><label for="nome">Username</label></td>
            <td><input class="mod" type="text" name="username" value="<?= $this->user->getUsername() ?>"></td>
        </tr>
        
        <tr><td><label for="nome">Password</label></td>
            <td><input class="mod" type="text" name="password" value="<?= $this->user->getPassword() ?>"></td>
        </tr>
        
        <tr><td><label for="nome">Indirizzo</label></td>
            <td><input class="mod" type="text" name="indirizzo" value="<?= $this->user->getIndirizzo() ?>"></td>
        </tr>
        
        <tr><td><label for="nome">Email</label></td>
            <td><input class="mod" type="text" name="mail" value="<?= $this->user->getEmail() ?>"></td>
        </tr>
        
        <tr><td><label for="nome">Numero Civico</label></td>
            <td><input class="mod" type="text" name="civico" value="<?= $this->user->getCivico() ?>"></td>
        </tr>
        
        <tr><td><label for="nome">Citta</label></td>
            <td><input class="mod" type="text" name="citta" value="<?= $this->user->getCitta() ?>"></td>
        </tr>
        
        <tr><td><label for="nome">Cap</label></td>
            <td><input class="mod" type="text" name="cap" value="<?= $this->user->getCap() ?>"></td>
        </tr>
        
        <tr><td><label for="nome">Provincia</label></td>
            <td><input class="mod" type="text" name="provincia" value="<?= $this->user->getProvincia() ?>"></td>
        </tr>
        
        <tr><td>
            </td>
            <td>
                <button class="update_button" type="submit" name="subpage" value="updated_user">Aggiorna Utente</button>
            </td>
        </tr>
        </table>
        
        <!-- Qui di seguito ho utilizzo un piccolo trucco:
        Mi serve id_user al ritorno di tutti questi $request ma,
        a causa della creazione di un nuovo controller,
        $user salvato in ambiente globale veniva perso.
        Inserendo l' id come input nascosto posso utilizzare anche il suo id senza problemi. -->
        <input id="id_prodotto" type="hidden" name="id_user" value="<?= $this->user->getId() ?>">
    </form>
</div>
