<div class="add_form">
    <form action="index.php" method="post">
		
        <table>
        <tr><td><label for="nome">Nome</label></td>
    	<td><input id="nome" type="text" name="nome"></td>
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
    	<td><input id="schermo" type="text" name="schermo"></td>
        </tr>
        
        <tr><td><label for="ram">Ram</label></td>
    	<td><input id="ram" type="text" name="ram"></td>
        </tr>
        
        <tr><td><label for="cpu">Cpu</label></td>
    	<td><input id="cpu" type="text" name="cpu"></td>
        </tr>
        
        <tr><td><label for="hard_disk">Hard Disk</label></td>
    	<td><input id="hard_disk" type="text" name="hard_disk"></td>
        </tr>
        
        <tr><td><label for="os">Sistema Operativo</label></td>
    	<td><input id="os" type="text" name="os"></td>
        </tr>
        
        <tr><td><label for="descrizione">Descrizione</label></td>
    	<td><input id="descrizione" type="text" name="descrizione"></td>
        </tr>
        
        <tr><td><label for="art_disponibili">Articoli Disponibili</label></td>
    	<td><input id="art_disponibili" type="text" name="art_disponibili"></td>
        </tr>
        
        <tr><td><label for="prezzo">Prezzo</label></td>
    	<td><input id="prezzo" type="text" name="prezzo"></td>
        </tr>
        
        <tr><td></td><td><button type="submit" name="subpage" value="added">Aggiungi al Database</button></td></tr>
        </table>
    </form>
</div>

