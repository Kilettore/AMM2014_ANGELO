<?php

include_once basename(__DIR__) . '/../model/ProdDatabase.php';

echo "<span class='subpage_text'>Risultati della ricerca</span><hr>";

// Cerco i prodotti da mostrare nella tabella
$result = ProdDatabase::instance()->searchProduct($this->input_search);

if(count($result) === 0)
{
    echo "<p class='subpage_indirizzo'><i>Nessun risultato trovato</i><br>";
}

foreach($result as $row)
{
?>
    <table>
    <tr><td class="td_img" rowspan="9">
            <?php if($row->getTipologia() === 'desktop') echo "<img src='images/desktop.png' style='max-width: 150px; max-height: 140px;'>";
                  if($row->getTipologia() === 'laptop') echo "<img src='images/laptop.png' style='max-width: 150px; max-height: 140px;'>";
                  if($row->getTipologia() === 'smartphone') echo "<img src='images/smartphone.png' style='max-width: 150px; max-height: 140px;'>";
                  if($row->getTipologia() === 'tablet') echo "<img src='images/tablet.png' style='max-width: 150px; max-height: 140px;'>";
            ?>
        </td>
    </tr>
    <tr><td colspan = 2><p class="nome_prodotto"><?= $row->getNome() ?></p></td></tr>
    <tr><td id="td_main"><b>Tipo:</b> <?= $row->getTipologia() ?></td><td rowspan = 2><b>Schermo:</b><?= $row->getSchermo() ?> </td></tr>
    <tr><td><b>Ram:</b> <?= $row->getRam() ?></td></tr>
    <tr><td><b>Cpu:</b> <?= $row->getCpu() ?></td>
        <td rowspan = 2>
            <?php if($row->getArtDisponibili() > 0) 
                   echo "<form action='index.php' method='post'>
                            <button class='button_add_cart' type='submit' name='carrello' value=". $row->getIdProdotto() .">Aggiungi al carrello</button>
                         </form>";
            ?>
        </td>
    </tr>
    <tr><td><b>Hard Disk:</b> <?= $row->getHardDisk() ?></td></tr>
    <tr><td><b>Sistema Operativo:</b> <?= $row->getOs() ?></td>
        <td rowspan = 3 id="options"><form action="index.php" method="post">
                                        <button type="submit" name="modifica" value="<?= $row->getIdProdotto() ?>" style="border:none; background:none;">
                                            <img src="images/modify.png" width=45px height=45px title="Modifica" vspace="5" alt="Modifica" align="middle">
                                        </button>
                                        <button type="submit" name="elimina" value="<?= $row->getIdProdotto() ?>" style="border:none; background:none;">
                                            <img src="images/delete.png" width=45px height=45px title="Elimina" vspace="5" alt="Elimina" align="middle">
                                        </button>
                                     </form>
        </td>
    </tr>
    <tr><td><b>Quantità disponibile:</b> <?= $row->getArtDisponibili() ?></td></tr>
    <tr><td><b>Prezzo:</b> <?= $row->getPrezzo() ?> </td></tr>
    </table>
    <br>
    <hr>
<?php
}
?>
