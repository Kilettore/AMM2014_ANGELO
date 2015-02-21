<?php
switch ($vd->getSottoPagina()) 
{
    case 'carrello':
        include 'carrello.php';
        break;
    
    case 'chisiamo':
        include 'chi_siamo.php';
        break;
    
    case 'partner':
        include 'partner.php';
        break;
        
    case 'modifica_utente':
        include 'modifica_utente.php';
        break;
    
    case 'result_user':
        include 'result_user.php';
        break;
    
    case 'cerca':
        if($this->input_search !== '')
        {
            include 'cerca.php';
            break;
        }
?>
    <?php default: 
    include_once basename(__DIR__) . "/../model/ProdDatabase.php";
    
    $result = ProdDatabase::instance()->loadEveryProduct();
		
    //$count = 0;
    while($row = $result->fetch_object())
    {
    ?>
        <table>
        <tr><td class="td_img" rowspan="9">
            <?php if($row->tipologia === 'desktop') echo "<img src='images/desktop.png' style='max-width: 150px; max-height: 140px;'>";
                  if($row->tipologia === 'laptop') echo "<img src='images/laptop.png' style='max-width: 150px; max-height: 140px;'>";
                  if($row->tipologia === 'smartphone') echo "<img src='images/smartphone.png' style='max-width: 150px; max-height: 140px;'>";
                  if($row->tipologia === 'tablet') echo "<img src='images/tablet.png' style='max-width: 150px; max-height: 140px;'>";
            ?>
            </td>
        </tr>
        <tr><td colspan = 2><p class="nome_prodotto"><?= $row->nome ?></p></td></tr>
        <tr><td class="td_main"><b>Tipo:</b> <?= $row->tipologia ?></td><td rowspan = 2><b>Schermo:</b> <?= $row->schermo?> </td></tr>
        <tr><td><b>Ram:</b> <?= $row->ram ?></td></tr>
        <tr><td colspan = 2><b>Cpu:</b> <?= $row->cpu ?></td></tr>
        <tr><td colspan = 2><b>Hard Disk:</b> <?= $row->hard_disk ?></td></tr>
        <tr><td colspan = 2><b>Sistema Operativo:</b> <?= $row->os ?></td></tr>
        <tr><td><b>Quantità disponibile:</b> <?= $row->art_disponibili?> </td>
            <td rowspan = 2>
                <?php if($row->art_disponibili > 0) 
                        echo "<form action='index.php' method='post'>
                                <button class='button_add_cart' type='submit' name='carrello' value=". $row->id_prodotto .">Aggiungi al carrello</button>
                              </form>";
                ?>
            </td>
        </tr>
        <tr><td colspan = 2><b>Prezzo:</b> <?= $row->prezzo?> </td></tr>
            <td> 
        </table>
        <br>
        <hr>
    
    <?php
    }
    break;
     
}
?>