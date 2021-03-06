<?php
    echo "<span class='subpage_text'>Modifica Prodotto</span><br>";
    echo "<hr>";
?>

<div class="add_form">
    <form action="index.php" method="post">
        <table>
        <tr><td><label for="nome">Nome</label></td>
            <td><input class="mod" type="text" name="nome" value="<?= $this->prodotto->getNome() ?>"></td>
        </tr>
        
        <tr><td><label for="tipologia">Tipologia</label></td>
            <td><select id="tipologia" name="tipologia">
                <option value="laptop">Laptop</option>
                <option value="desktop">Desktop</option>
                <option value="tablet">Tablet</option>
                <option value="smartphone">Smartphone</option>
            </select>
            </td>
        </tr>
        
        <tr><td><label for="schermo">Schermo</label></td>
            <td><input class="mod" type="text" name="schermo" value="<?= $this->prodotto->getSchermo() ?>"></td>
        </tr>
        
        <tr><td><label for="ram">Ram</label></td>
            <td><input class="mod" type="text" name="ram" value="<?= $this->prodotto->getRam() ?>"></td>
        </tr>
        
        <tr><td><label for="cpu">Cpu</label></td>
            <td><input class="mod" type="text" name="cpu" value="<?= $this->prodotto->getCpu() ?>"></td>
        </tr>
        
        <tr><td><label for="hard_disk">Hard Disk</label></td>
            <td><input class="mod" type="text" name="hard_disk" value="<?= $this->prodotto->getHardDisk() ?>"></td>
        </tr>
        
        <tr><td><label for="os">Sistema Operativo</label></td>
            <td><input class="mod" type="text" name="os" value="<?= $this->prodotto->getOs() ?>"></td>
        </tr>
        
        <tr><td><label for="descrizione">Descrizione</label></td>
    	    <td><textarea id="descrizione" name="descrizione"><?= $this->prodotto->getDescrizione() ?></textarea></td>
        </tr>
        
        <tr><td><label for="art_disponibili">Articoli Disponibili</label></td>
            <td><input class="mod" type="text" name="art_disponibili" value="<?= $this->prodotto->getArtDisponibili() ?>"></td>
        </tr>
        
        <tr><td><label for="prezzo">Prezzo</label></td>
            <td><input class="mod" type="text" name="prezzo" value="<?= $this->prodotto->getPrezzo() ?>"></td>
        </tr>
        
        <tr><td>
            </td>
            <td><button class="update_button" type="submit" name="subpage" value="updated">Aggiorna il Database</button></td>
        </tr>
        </table>
        
        <!-- Qui di seguito ho utilizzo un piccolo trucco:
        Mi serve id_prodotto al ritorno di tutti questi $request ma,
        a causa della creazione di un nuovo controller,
        $prodotto salvato in ambiente globale veniva perso.
        Inserendo l' id come input nascosto posso utilizzare anche il suo id senza problemi. -->
        <input id="id_prodotto" type="hidden" name="id_prodotto" value="<?= $this->prodotto->getIdProdotto() ?>">
        
    </form>
</div>

