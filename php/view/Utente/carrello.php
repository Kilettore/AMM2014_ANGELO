<?php

include_once basename(__DIR__) . '/../model/UserDatabase.php';
include_once basename(__DIR__) . '/../model/ProdDatabase.php';

//carico in memoria l' utente di cui devo visualizzare i dati
$user = UserDatabase::instance()->cercaUtentePerId($_SESSION[self::user], $_SESSION[self::role]);
echo "<span class='subpage_text'>Carrello di ".$user->getNome().' '.$user->getCognome()."</span><br>";
echo "<hr>";

// Carico su result i prodotti da mostrare nel carrello
$result = ProdDatabase::instance()->loadCart($user->getId());

$count = 0;

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
    <tr><td class="td_main"><b>Tipo:</b> <?= $row->getTipologia() ?></td><td><b>Schermo:</b> <?= $row->getSchermo() ?> </td></tr>
    <tr><td colspan = 2><b>Ram:</b> <?= $row->getRam() ?></td></tr>
    <tr><td colspan = 2><b>Cpu:</b> <?= $row->getCpu() ?></td></tr>
    <tr><td colspan = 2><b>Hard Disk:</b> <?= $row->getHardDisk() ?></td></tr>
    <tr><td><b>Sistema Operativo:</b> <?= $row->getOs() ?></td>
        <td rowspan = 3><form action="index.php" method="post">
                                         <button type="submit" name="elimina_da_carrello" value="<?= $row->getIdCarrello() ?>" style="border:none; background:none;">
                                             <img src="images/delete.png" width=45px height=45px title="Elimina" vspace="5" alt="Elimina" align="middle">
                                         </button>
                                     </form>
        </td>
    </tr>
    <tr><td><b>Quantità disponibile:</b> <?= $row->getArtDisponibili() ?> </td></tr>
    <tr><td><b>Prezzo:</b> <?= $row->getPrezzo() ?> </td></tr>
    </table>
    <hr>
<?php
$count = $count + $row->getPrezzo();
}
if($count != 0)
{
    echo "<span class='price'>Il prezzo totale e' di: ".$count."€</span>";
    echo "<form action='index.php' method='post'>
              <button class='button_shop' type='submit' name='end_shop' value='".$user->getId().">'>Acquista i Prodotti</button>
          </form>";
}
else echo "<span class='price'>Il carrello e' vuoto</span>";
?>
